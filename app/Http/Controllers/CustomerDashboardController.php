<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * CustomerDashboardController
 * ─────────────────────────────────────────────────────────────────────────────
 * ZERO Eloquent ORM. All queries use DB::table() (Query Builder) only.
 */
class CustomerDashboardController extends Controller
{
    private function customerData(): array
    {
        $userId = Auth::id();

        $customer = DB::table('customers')->where('user_id', $userId)->first();
        abort_unless($customer, 404, 'Customer record not found.');

        $membership = DB::table('memberships')
            ->join('membership_plans', 'membership_plans.id', '=', 'memberships.membership_plan_id')
            ->where('memberships.customer_id', $customer->id)
            ->orderByDesc('memberships.id')
            ->select(
                'memberships.*',
                'membership_plans.name as plan_name',
                'membership_plans.price',
                'membership_plans.features'
            )
            ->first();

        $daysRemaining = $membership
            ? max(0, Carbon::today()->diffInDays(Carbon::parse($membership->expiration_date), false))
            : 0;

        // Trainer assignments with trainer names
        $assignments = DB::table('trainer_assignments')
            ->join('trainers', 'trainers.id', '=', 'trainer_assignments.trainer_id')
            ->where('trainer_assignments.customer_id', $customer->id)
            ->select(
                'trainer_assignments.*',
                'trainers.first_name as trainer_first_name',
                'trainers.last_name as trainer_last_name',
                'trainers.specialization as trainer_specialization'
            )
            ->latest('trainer_assignments.id')
            ->get();

        return [
            'customer'      => $customer,
            'membership'    => $membership,
            'daysRemaining' => $daysRemaining,
            'branch'        => DB::table('branches')->where('id', $customer->branch_id)->first(),
            'equipment'     => DB::table('equipment')->where('branch_id', $customer->branch_id)->get(),
            'trainers'      => DB::table('trainers')->where('branch_id', $customer->branch_id)->where('status', 'Active')->get(),
            'payments'      => DB::table('payments')->where('customer_id', $customer->id)->latest('payment_date')->get(),
            'assignments'   => $assignments,
        ];
    }

    public function index()    { return view('customer.dashboard', $this->customerData()); }
    public function payments() { return view('customer.payments',  $this->customerData()); }
    public function equipment(){ return view('customer.equipment', $this->customerData()); }
    public function trainers() { return view('customer.trainers',  $this->customerData()); }

    // ── Cancel Membership ─────────────────────────────────────────────────────
    public function cancelMembership()
    {
        $customer = DB::table('customers')->where('user_id', Auth::id())->first();

        $membership = DB::table('memberships')
            ->where('customer_id', $customer->id)
            ->where('status', 'Active')
            ->latest('id')
            ->first();

        if (! $membership) {
            return back()->with('membership_status', 'No active membership found.');
        }

        DB::table('memberships')->where('id', $membership->id)->update([
            'status'     => 'Cancelled',
            'notes'      => trim(($membership->notes ? $membership->notes."\n" : '').'Cancelled by customer on '.now()->format('Y-m-d H:i:s')),
            'updated_at' => now(),
        ]);

        return redirect()->route('customer.dashboard')
            ->with('membership_status', 'Your membership has been cancelled.');
    }

    // ── Pay Pending ───────────────────────────────────────────────────────────
    public function payPending(Request $request, int $payment)
    {
        $customer = DB::table('customers')->where('user_id', Auth::id())->first();

        $validated = $request->validate([
            'payment_method' => ['required', 'in:Cash,GCash,Credit Card,Bank Transfer,PayMaya'],
            'reference_no'   => ['nullable', 'string', 'max:100'],
        ]);

        $existingPayment = DB::table('payments')
            ->where('id', $payment)
            ->where('customer_id', $customer->id)
            ->first();

        abort_unless($existingPayment, 404);

        if ($existingPayment->status !== 'Pending') {
            return back()->with('payment_status', 'This payment is already processed.');
        }

        DB::table('payments')->where('id', $payment)->update([
            'payment_method' => $validated['payment_method'],
            'reference_no'   => $validated['reference_no'] ?? $existingPayment->reference_no,
            'payment_date'   => now(),
            'status'         => 'Paid',
            'updated_at'     => now(),
        ]);

        return redirect()->route('customer.payments')
            ->with('payment_status', 'Payment submitted successfully.');
    }

    // ── Apply for Trainer ─────────────────────────────────────────────────────
    public function applyTrainer(Request $request)
    {
        $customer = DB::table('customers')->where('user_id', Auth::id())->first();

        $validated = $request->validate([
            'trainer_id'     => ['required', 'exists:trainers,id'],
            'start_date'     => ['required', 'date', 'after_or_equal:today'],
            'sessions_total' => ['required', 'integer', 'min:1', 'max:100'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        // Prevent duplicate active assignment to same trainer
        $existing = DB::table('trainer_assignments')
            ->where('customer_id', $customer->id)
            ->where('trainer_id', $validated['trainer_id'])
            ->where('status', 'Active')
            ->first();

        if ($existing) {
            return back()->with('trainer_status', 'You already have an active assignment with this trainer.');
        }

        // Verify the trainer belongs to the customer's branch
        $trainer = DB::table('trainers')
            ->where('id', $validated['trainer_id'])
            ->where('branch_id', $customer->branch_id)
            ->where('status', 'Active')
            ->first();

        if (! $trainer) {
            return back()->with('trainer_status', 'This trainer is not available at your branch.');
        }

        DB::table('trainer_assignments')->insert([
            'trainer_id'     => $validated['trainer_id'],
            'customer_id'    => $customer->id,
            'start_date'     => $validated['start_date'],
            'sessions_total' => $validated['sessions_total'],
            'sessions_done'  => 0,
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'Pending',   // starts as Pending until admin approves
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('customer.trainers')
            ->with('trainer_status', 'Your trainer application has been submitted! Please wait for admin approval.');
    }

    // ── Cancel Trainer Application ────────────────────────────────────────────
    public function cancelTrainer(Request $request, int $assignmentId)
    {
        $customer = DB::table('customers')->where('user_id', Auth::id())->first();

        $assignment = DB::table('trainer_assignments')
            ->where('id', $assignmentId)
            ->where('customer_id', $customer->id)
            ->first();

        abort_unless($assignment, 404);

        if (! in_array($assignment->status, ['Pending', 'Active'])) {
            return back()->with('trainer_status', 'This assignment cannot be cancelled.');
        }

        DB::table('trainer_assignments')->where('id', $assignmentId)->update([
            'status'     => 'Cancelled',
            'updated_at' => now(),
        ]);

        return redirect()->route('customer.trainers')
            ->with('trainer_status', 'Trainer assignment has been cancelled.');
    }
}