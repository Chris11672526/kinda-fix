<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * NO ELOQUENT ORM - Using Query Builder only per DORSU requirements
 */
class AdminDashboardController extends Controller
{
    // ── __construct REMOVED — middleware is handled in routes/web.php ────────

    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function index()
    {
        $monthlyRegistrations = DB::table('customers')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyRevenueRaw = DB::table('payments')
            ->selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(total_paid) as total")
            ->where('status', 'Paid')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyRevenue = $monthlyRegistrations->pluck('month')->map(
            fn($m) => (float) ($monthlyRevenueRaw[$m] ?? 0)
        );

        return view('admin.dashboard', [
            'totalCustomers'      => DB::table('customers')->whereNull('deleted_at')->count(),
            'activeMemberships'   => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships'  => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments'     => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue'        => DB::table('payments')->where('status', 'Paid')->sum('total_paid') ?? 0,
            'sessionCount'        => DB::table('attendance')->count(),
            'customers'           => DB::table('customers')->whereNull('deleted_at')->latest()->limit(6)->get(),
            'payments'            => DB::table('payments')->latest('payment_date')->limit(5)->get(),
            'monthlyLabels'       => $monthlyRegistrations->pluck('month'),
            'monthlyValues'       => $monthlyRegistrations->pluck('total'),
            'monthlyRevenue'      => $monthlyRevenue,
            'membershipBreakdown' => DB::table('memberships')->selectRaw('status, COUNT(*) as total')->groupBy('status')->get(),
        ]);
    }

    // ── Members ───────────────────────────────────────────────────────────────
    public function members()
    {
        $customers = DB::table('customers')
            ->leftJoin('branches', 'customers.branch_id', '=', 'branches.id')
            ->leftJoin('memberships', function ($join) {
                $join->on('memberships.customer_id', '=', 'customers.id')
                     ->whereRaw('memberships.id = (
                         SELECT id FROM memberships m2
                         WHERE m2.customer_id = customers.id
                         ORDER BY m2.id DESC LIMIT 1
                     )');
            })
            ->leftJoin('membership_plans', 'membership_plans.id', '=', 'memberships.membership_plan_id')
            ->whereNull('customers.deleted_at')
            ->select(
                'customers.*',
                'branches.name as branch_name',
                'memberships.status as membership_status',
                'memberships.expiration_date',
                'membership_plans.name as plan_name'
            )
            ->orderByDesc('customers.created_at')
            ->get();

        return view('admin.members', [
            'customers'          => $customers,
            'totalCustomers'     => DB::table('customers')->whereNull('deleted_at')->count(),
            'activeMemberships'  => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships' => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments'    => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue'       => DB::table('payments')->where('status', 'Paid')->sum('total_paid') ?? 0,
            'sessionCount'       => DB::table('attendance')->count(),
            'branches'           => DB::table('branches')->whereNull('deleted_at')->get(),
            'plans'              => DB::table('membership_plans')->where('is_active', 1)->get(),
        ]);
    }

    public function storeMember(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:60|regex:/^[a-zA-Z\s\-\.]+$/',
            'last_name'  => 'required|string|max:60|regex:/^[a-zA-Z\s\-\.]+$/',
            'gender'     => 'required|in:Male,Female,Other',
            'birthdate'  => 'required|date|before:today',
            'phone'      => 'required|regex:/^[0-9\-\+\(\)\s]{7,20}$/',
            'email'      => 'nullable|email|unique:customers,email',
            'branch_id'  => 'required|integer|exists:branches,id',
            'status'     => 'required|in:Active,Inactive,Suspended',
            'membership_plan_id' => 'nullable|exists:membership_plans,id',
        ]);

        try {
            $customerId = DB::table('customers')->insertGetId([
                'first_name' => trim(strip_tags($validated['first_name'])),
                'last_name'  => trim(strip_tags($validated['last_name'])),
                'gender'     => $validated['gender'],
                'birthdate'  => $validated['birthdate'],
                'phone'      => preg_replace('/[^0-9\-\+\(\)]/', '', $validated['phone']),
                'email'      => $validated['email'] ?? null,
                'branch_id'  => $validated['branch_id'],
                'status'     => $validated['status'],
                'blood_type' => 'Unknown',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (! empty($validated['membership_plan_id'])) {
                $plan  = DB::table('membership_plans')->where('id', $validated['membership_plan_id'])->first();
                $start = now()->toDateString();

                $membershipId = DB::table('memberships')->insertGetId([
                    'customer_id'        => $customerId,
                    'membership_plan_id' => $plan->id,
                    'start_date'         => $start,
                    'expiration_date'    => now()->addDays($plan->duration_days)->toDateString(),
                    'status'             => 'Active',
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                DB::table('payments')->insert([
                    'membership_id'  => $membershipId,
                    'customer_id'    => $customerId,
                    'amount'         => $plan->price,
                    'discount'       => 0,
                    'total_paid'     => $plan->price,
                    'payment_method' => 'Cash',
                    'payment_date'   => now(),
                    'status'         => 'Pending',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Member created successfully.', 'id' => $customerId], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    public function updateMember(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:60|regex:/^[a-zA-Z\s\-\.]+$/',
            'last_name'  => 'required|string|max:60|regex:/^[a-zA-Z\s\-\.]+$/',
            'gender'     => 'required|in:Male,Female,Other',
            'birthdate'  => 'required|date|before:today',
            'phone'      => 'required|regex:/^[0-9\-\+\(\)\s]{7,20}$/',
            'email'      => 'nullable|email|unique:customers,email,' . $id,
            'branch_id'  => 'required|integer|exists:branches,id',
            'status'     => 'required|in:Active,Inactive,Suspended',
        ]);

        try {
            $exists = DB::table('customers')->where('id', $id)->whereNull('deleted_at')->exists();
            if (! $exists) {
                return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
            }

            DB::table('customers')->where('id', $id)->update([
                'first_name' => trim(strip_tags($validated['first_name'])),
                'last_name'  => trim(strip_tags($validated['last_name'])),
                'gender'     => $validated['gender'],
                'birthdate'  => $validated['birthdate'],
                'phone'      => preg_replace('/[^0-9\-\+\(\)]/', '', $validated['phone']),
                'email'      => $validated['email'] ?? null,
                'branch_id'  => $validated['branch_id'],
                'status'     => $validated['status'],
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Member updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    public function deleteMember($id)
    {
        try {
            $exists = DB::table('customers')->where('id', $id)->whereNull('deleted_at')->exists();
            if (! $exists) {
                return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
            }

            DB::table('customers')->where('id', $id)->update(['deleted_at' => now(), 'updated_at' => now()]);

            return response()->json(['success' => true, 'message' => 'Member deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    // ── Payments ──────────────────────────────────────────────────────────────
    public function payments()
    {
        return view('admin.payments', [
            'totalCustomers'     => DB::table('customers')->whereNull('deleted_at')->count(),
            'activeMemberships'  => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships' => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments'    => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue'       => DB::table('payments')->where('status', 'Paid')->sum('total_paid') ?? 0,
            'sessionCount'       => DB::table('attendance')->count(),
            'payments'           => DB::table('payments')
                ->join('customers', 'customers.id', '=', 'payments.customer_id')
                ->select('payments.*', 'customers.first_name', 'customers.last_name', 'customers.email')
                ->latest('payments.payment_date')
                ->get(),
        ]);
    }

    public function updatePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Paid,Pending,Refunded',
        ]);

        try {
            $exists = DB::table('payments')->where('id', $id)->exists();
            if (! $exists) {
                return response()->json(['success' => false, 'message' => 'Payment not found.'], 404);
            }

            DB::table('payments')->where('id', $id)->update([
                'status'     => $validated['status'],
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Payment status updated.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    // ── Sessions ──────────────────────────────────────────────────────────────
    public function sessions()
    {
        return view('admin.sessions', [
            'totalCustomers'     => DB::table('customers')->whereNull('deleted_at')->count(),
            'activeMemberships'  => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships' => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments'    => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue'       => DB::table('payments')->where('status', 'Paid')->sum('total_paid') ?? 0,
            'sessionCount'       => DB::table('attendance')->count(),
            'attendance'         => DB::table('attendance')
                ->join('customers', 'customers.id', '=', 'attendance.customer_id')
                ->join('branches', 'branches.id', '=', 'attendance.branch_id')
                ->leftJoin('classes', 'classes.id', '=', 'attendance.class_id')
                ->select(
                    'attendance.*',
                    'customers.first_name',
                    'customers.last_name',
                    'branches.name as branch_name',
                    'classes.name as class_name'
                )
                ->latest('attendance.check_in')
                ->get(),
        ]);
    }

    // ── Equipment ─────────────────────────────────────────────────────────────
    public function equipment()
    {
        return view('admin.equipment', [
            'totalCustomers'     => DB::table('customers')->whereNull('deleted_at')->count(),
            'activeMemberships'  => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships' => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments'    => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue'       => DB::table('payments')->where('status', 'Paid')->sum('total_paid') ?? 0,
            'sessionCount'       => DB::table('attendance')->count(),
            'equipment'          => DB::table('equipment')
                ->join('branches', 'branches.id', '=', 'equipment.branch_id')
                ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment.category_id')
                ->select('equipment.*', 'branches.name as branch_name', 'equipment_categories.name as category_name')
                ->orderBy('equipment.name')
                ->get(),
        ]);
    }

    public function updateEquipment(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity'  => 'required|integer|min:0',
            'condition' => 'required|in:New,Good,Fair,Needs Repair,Retired',
        ]);

        try {
            $exists = DB::table('equipment')->where('id', $id)->exists();
            if (! $exists) {
                return response()->json(['success' => false, 'message' => 'Equipment not found.'], 404);
            }

            DB::table('equipment')->where('id', $id)->update([
                'quantity'   => $validated['quantity'],
                'condition'  => $validated['condition'],
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Equipment updated.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    // ── Reports ───────────────────────────────────────────────────────────────
    public function reports()
    {
        $monthlyRegistrations = DB::table('customers')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports', [
            'totalCustomers'      => DB::table('customers')->whereNull('deleted_at')->count(),
            'activeMemberships'   => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships'  => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments'     => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue'        => DB::table('payments')->where('status', 'Paid')->sum('total_paid') ?? 0,
            'sessionCount'        => DB::table('attendance')->count(),
            'monthlyLabels'       => $monthlyRegistrations->pluck('month'),
            'monthlyValues'       => $monthlyRegistrations->pluck('total'),
            'membershipBreakdown' => DB::table('memberships')
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get(),
        ]);
    }
}