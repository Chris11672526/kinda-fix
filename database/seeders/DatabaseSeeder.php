<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        DB::table('roles')->insert([
            ['name' => 'admin',   'description' => 'Full system access'],
            ['name' => 'staff',   'description' => 'Front desk and operations'],
            ['name' => 'trainer', 'description' => 'Fitness trainer / coach'],
            ['name' => 'member',  'description' => 'Gym member / customer'],
        ]);

        // 2. Branches - Updated with your locations
        DB::table('branches')->insert([
            [
                'name' => 'Manikling Branch',
                'address' => 'Manikling Street, Compostela Valley',
                'city' => 'Compostela Valley',
                'phone' => '082-555-0001',
                'email' => 'manikling@fitzone.com',
                'manager_name' => 'Maria Santos',
                'opened_at' => '2020-01-15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Banay-Banay Branch',
                'address' => 'Banay-Banay Avenue, Compostela Valley',
                'city' => 'Compostela Valley',
                'phone' => '082-555-0002',
                'email' => 'banaybanay@fitzone.com',
                'manager_name' => 'Juan Reyes',
                'opened_at' => '2021-03-10',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lupon Branch',
                'address' => 'Main Road, Lupon',
                'city' => 'Lupon',
                'phone' => '082-555-0003',
                'email' => 'lupon@fitzone.com',
                'manager_name' => 'Rosa Garcia',
                'opened_at' => '2021-06-20',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pantukan Branch',
                'address' => 'Commercial District, Pantukan',
                'city' => 'Pantukan',
                'phone' => '082-555-0004',
                'email' => 'pantukan@fitzone.com',
                'manager_name' => 'Carlos Mendoza',
                'opened_at' => '2022-02-01',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 3. Admin & Staff Users (password: "password")
        $hashedPassword = Hash::make('password');
        DB::table('users')->insert([
            ['role_id' => 1, 'branch_id' => 1, 'name' => 'Admin User',     'email' => 'admin@fitzone.com',    'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'branch_id' => 1, 'name' => 'Ana Macaraeg',   'email' => 'ana@fitzone.com',      'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'branch_id' => 2, 'name' => 'Ben Florendo',   'email' => 'ben@fitzone.com',      'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'branch_id' => 3, 'name' => 'Clara Montoya',  'email' => 'clara@fitzone.com',    'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'branch_id' => 4, 'name' => 'David Cruz',     'email' => 'david@fitzone.com',    'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 1, 'name' => 'Trainer User 1', 'email' => 'trainer1@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 1, 'name' => 'Trainer User 2', 'email' => 'trainer2@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 2, 'name' => 'Trainer User 3', 'email' => 'trainer3@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 2, 'name' => 'Trainer User 4', 'email' => 'trainer4@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 3, 'name' => 'Trainer User 5', 'email' => 'trainer5@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 4, 'name' => 'Trainer User 6', 'email' => 'trainer6@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Trainers
        DB::table('trainers')->insert([
            [
                'user_id' => 6,
                'branch_id' => 1,
                'first_name' => 'Marco',
                'last_name' => 'Villanueva',
                'gender' => 'Male',
                'birthdate' => '1990-04-12',
                'phone' => '09171234501',
                'specialization' => 'Strength & Conditioning, Bodybuilding',
                'certification' => 'NSCA-CPT, ACE Personal Trainer',
                'experience_years' => 8,
                'hire_date' => '2018-02-01',
                'salary' => 28000.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 7,
                'branch_id' => 1,
                'first_name' => 'Liza',
                'last_name' => 'Domingo',
                'gender' => 'Female',
                'birthdate' => '1993-07-25',
                'phone' => '09171234502',
                'specialization' => 'Zumba, Aerobics, Dance Fitness',
                'certification' => 'Zumba Instructor, Group Fitness Certified',
                'experience_years' => 6,
                'hire_date' => '2019-05-15',
                'salary' => 24000.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 8,
                'branch_id' => 2,
                'first_name' => 'Jerome',
                'last_name' => 'Aquino',
                'gender' => 'Male',
                'birthdate' => '1988-11-03',
                'phone' => '09171234503',
                'specialization' => 'CrossFit, HIIT, Functional Training',
                'certification' => 'CrossFit Level 2, CF-L1 Trainer',
                'experience_years' => 10,
                'hire_date' => '2020-07-01',
                'salary' => 30000.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 9,
                'branch_id' => 2,
                'first_name' => 'Patricia',
                'last_name' => 'Salazar',
                'gender' => 'Female',
                'birthdate' => '1995-02-18',
                'phone' => '09171234504',
                'specialization' => 'Yoga, Pilates, Flexibility',
                'certification' => 'RYT-200 Yoga, Pilates Mat Certified',
                'experience_years' => 5,
                'hire_date' => '2021-01-10',
                'salary' => 22000.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 10,
                'branch_id' => 3,
                'first_name' => 'Ryan',
                'last_name' => 'Tolentino',
                'gender' => 'Male',
                'birthdate' => '1991-09-30',
                'phone' => '09171234505',
                'specialization' => 'Weight Loss, Cardio, Nutrition Coaching',
                'certification' => 'ACE-CPT, Nutrition Specialist',
                'experience_years' => 7,
                'hire_date' => '2021-04-01',
                'salary' => 26000.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 11,
                'branch_id' => 4,
                'first_name' => 'Elena',
                'last_name' => 'Santos',
                'gender' => 'Female',
                'birthdate' => '1992-03-14',
                'phone' => '09171234506',
                'specialization' => 'Boxing, Kickboxing, Self-Defense',
                'certification' => 'Boxing Coach, Muay Thai Trainer',
                'experience_years' => 9,
                'hire_date' => '2020-06-15',
                'salary' => 29000.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 5. Realistic Membership Plans - Premium Gym Plans
        DB::table('membership_plans')->insert([
            [
                'name' => 'Trial Pass',
                'duration_days' => 3,
                'price' => 99.00,
                'description' => 'Perfect for trying out our facilities',
                'features' => 'Access to all branches, Unlimited gym access, Basic facilities',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Day Pass',
                'duration_days' => 1,
                'price' => 50.00,
                'description' => 'Single day access for drop-ins',
                'features' => 'One branch access, 24-hour gym access, Locker use',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Weekly Pass',
                'duration_days' => 7,
                'price' => 250.00,
                'description' => 'One week of unlimited gym access',
                'features' => 'Unlimited gym access, One branch, Locker & shower access, Water dispenser',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Monthly Basic',
                'duration_days' => 30,
                'price' => 699.00,
                'description' => 'Classic one-month membership at basic tier',
                'features' => 'Unlimited gym access, One branch, Locker & shower, WiFi access, Members app',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Monthly Premium',
                'duration_days' => 30,
                'price' => 999.00,
                'description' => 'All-access one-month premium membership',
                'features' => 'Access all 4 branches, Unlimited gym access, All facilities, 1 group class/week, Nutrition consultation, Members app',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Quarterly',
                'duration_days' => 90,
                'price' => 1899.00,
                'description' => 'Three-month commitment with great savings',
                'features' => 'Access all branches, Unlimited gym access, Unlimited group classes, 2 PT sessions/month, Locker priority, Gym bag provided',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Semi-Annual',
                'duration_days' => 180,
                'price' => 3499.00,
                'description' => 'Six-month plan with premium benefits',
                'features' => 'Access all branches, Unlimited everything, 4 PT sessions/month, Priority facilities, Guest passes (2x month), Fitness assessment, Merchandise discount',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Annual Plus',
                'duration_days' => 365,
                'price' => 5999.00,
                'description' => 'Best value - Full year commitment with exclusive perks',
                'features' => 'Access all branches, Unlimited everything, 8 PT sessions/month, VIP locker, Guest passes weekly, Fitness gear discount, Priority class booking, Birthday month free',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Student Monthly',
                'duration_days' => 30,
                'price' => 449.00,
                'description' => 'Special discount for students (Valid ID required)',
                'features' => 'Unlimited gym access, One branch, Basic facilities, Locker & shower, WiFi, Members app',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Senior Monthly',
                'duration_days' => 30,
                'price' => 399.00,
                'description' => 'Special rate for seniors 60+ (Valid ID required)',
                'features' => 'Unlimited gym access, One branch, Light equipment & cardio, Locker & shower, WiFi, Senior group classes',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Couple Monthly',
                'duration_days' => 30,
                'price' => 1299.00,
                'description' => 'Save together - Two memberships for one great price',
                'features' => 'Unlimited gym access for 2, One branch, Lockers for 2, Couple training sessions, WiFi, Members app for both',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Corporate Annual',
                'duration_days' => 365,
                'price' => 4999.00,
                'description' => 'Corporate bulk membership - Minimum 5 members',
                'features' => 'Access all branches, Unlimited gym access, 4 PT sessions/month, Corporate discount, Team events, Wellness programs',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 6. Equipment Categories
        DB::table('equipment_categories')->insert([
            ['name' => 'Cardio Equipment', 'description' => 'Treadmills, ellipticals, stationary bikes, rowing machines'],
            ['name' => 'Free Weights', 'description' => 'Dumbbells, barbells, kettlebells, weight plates'],
            ['name' => 'Weight Machines', 'description' => 'Cable machines, leg press, chest press, leg curl'],
            ['name' => 'Functional Training', 'description' => 'Battle ropes, TRX, medicine balls, plyo boxes'],
            ['name' => 'Stretching & Recovery', 'description' => 'Yoga mats, foam rollers, stretching bars, massage tools'],
            ['name' => 'Boxing & Combat', 'description' => 'Heavy bags, speed bags, punch pads, boxing gloves'],
        ]);

        // 7. Sample Equipment for all branches
        DB::table('equipment')->insert([
            // MANIKLING BRANCH (Branch 1)
            ['category_id' => 1, 'branch_id' => 1, 'name' => 'Treadmill', 'brand' => 'Life Fitness', 'model' => 'F3', 'serial_number' => 'SN-TM-001', 'quantity' => 6, 'purchase_date' => '2020-01-10', 'purchase_price' => 85000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'branch_id' => 1, 'name' => 'Elliptical Machine', 'brand' => 'Precor', 'model' => 'EFX221', 'serial_number' => 'SN-EL-001', 'quantity' => 4, 'purchase_date' => '2020-03-15', 'purchase_price' => 60000.00, 'condition' => 'Good', 'last_maintained' => '2025-04-01', 'next_maintenance' => '2025-10-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'branch_id' => 1, 'name' => 'Stationary Bike', 'brand' => 'Technogym', 'model' => 'Bike700', 'serial_number' => 'SN-BK-001', 'quantity' => 5, 'purchase_date' => '2020-02-20', 'purchase_price' => 45000.00, 'condition' => 'Good', 'last_maintained' => '2025-04-15', 'next_maintenance' => '2025-10-15', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'branch_id' => 1, 'name' => 'Dumbbell Set', 'brand' => 'Ativafit', 'model' => 'HEX (5-50 lbs)', 'serial_number' => 'SN-DB-001', 'quantity' => 2, 'purchase_date' => '2020-01-10', 'purchase_price' => 35000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'branch_id' => 1, 'name' => 'Barbell & Weight Plates', 'brand' => 'Rogue', 'model' => 'Ohio Bar', 'serial_number' => 'SN-BB-001', 'quantity' => 5, 'purchase_date' => '2020-01-10', 'purchase_price' => 28000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'branch_id' => 1, 'name' => 'Cable Machine', 'brand' => 'Hammer Strength', 'model' => 'MG-7802', 'serial_number' => 'SN-CM-001', 'quantity' => 2, 'purchase_date' => '2021-06-01', 'purchase_price' => 95000.00, 'condition' => 'Good', 'last_maintained' => '2025-04-15', 'next_maintenance' => '2025-10-15', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'branch_id' => 1, 'name' => 'Leg Press Machine', 'brand' => 'Hammer Strength', 'model' => 'V-Squat', 'serial_number' => 'SN-LP-001', 'quantity' => 2, 'purchase_date' => '2021-06-01', 'purchase_price' => 75000.00, 'condition' => 'Good', 'last_maintained' => '2025-04-15', 'next_maintenance' => '2025-10-15', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'branch_id' => 1, 'name' => 'Battle Ropes', 'brand' => 'Generic', 'model' => '15m x 2in', 'serial_number' => 'SN-BR-001', 'quantity' => 4, 'purchase_date' => '2022-01-01', 'purchase_price' => 5000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 5, 'branch_id' => 1, 'name' => 'Yoga Mat Premium', 'brand' => 'Manduka', 'model' => 'PRO', 'serial_number' => 'SN-YM-001', 'quantity' => 25, 'purchase_date' => '2022-01-01', 'purchase_price' => 1500.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 5, 'branch_id' => 1, 'name' => 'Foam Roller', 'brand' => 'TriggerPoint', 'model' => '13 inch', 'serial_number' => 'SN-FR-001', 'quantity' => 10, 'purchase_date' => '2022-02-01', 'purchase_price' => 1200.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 6, 'branch_id' => 1, 'name' => 'Heavy Bag', 'brand' => 'Everlast', 'model' => '70 lbs', 'serial_number' => 'SN-HB-001', 'quantity' => 3, 'purchase_date' => '2022-03-01', 'purchase_price' => 3500.00, 'condition' => 'Good', 'last_maintained' => '2025-04-01', 'next_maintenance' => '2025-10-01', 'created_at' => now(), 'updated_at' => now()],

            // BANAY-BANAY BRANCH (Branch 2)
            ['category_id' => 1, 'branch_id' => 2, 'name' => 'Treadmill', 'brand' => 'Life Fitness', 'model' => 'F3', 'serial_number' => 'SN-TM-101', 'quantity' => 5, 'purchase_date' => '2020-07-01', 'purchase_price' => 85000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'branch_id' => 2, 'name' => 'Stationary Bike', 'brand' => 'Technogym', 'model' => 'Bike700', 'serial_number' => 'SN-BK-101', 'quantity' => 4, 'purchase_date' => '2020-08-10', 'purchase_price' => 45000.00, 'condition' => 'Good', 'last_maintained' => '2025-04-15', 'next_maintenance' => '2025-10-15', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'branch_id' => 2, 'name' => 'Smith Machine', 'brand' => 'Body-Solid', 'model' => 'GS348Q', 'serial_number' => 'SN-SM-101', 'quantity' => 2, 'purchase_date' => '2021-01-01', 'purchase_price' => 80000.00, 'condition' => 'Good', 'last_maintained' => '2025-04-01', 'next_maintenance' => '2025-10-01', 'created_at' => now(), 'updated_at' => now()],

            // LUPON BRANCH (Branch 3)
            ['category_id' => 1, 'branch_id' => 3, 'name' => 'Treadmill', 'brand' => 'Life Fitness', 'model' => 'F3', 'serial_number' => 'SN-TM-201', 'quantity' => 4, 'purchase_date' => '2021-04-01', 'purchase_price' => 85000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'branch_id' => 3, 'name' => 'Dumbbell Set', 'brand' => 'Ativafit', 'model' => 'HEX (5-50 lbs)', 'serial_number' => 'SN-DB-201', 'quantity' => 2, 'purchase_date' => '2021-04-01', 'purchase_price' => 35000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],

            // PANTUKAN BRANCH (Branch 4)
            ['category_id' => 1, 'branch_id' => 4, 'name' => 'Treadmill', 'brand' => 'Life Fitness', 'model' => 'F3', 'serial_number' => 'SN-TM-301', 'quantity' => 3, 'purchase_date' => '2022-03-01', 'purchase_price' => 85000.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'branch_id' => 4, 'name' => 'TRX Suspension System', 'brand' => 'TRX', 'model' => 'Pro Pack', 'serial_number' => 'SN-TRX-301', 'quantity' => 8, 'purchase_date' => '2022-06-01', 'purchase_price' => 4500.00, 'condition' => 'Good', 'last_maintained' => '2025-05-01', 'next_maintenance' => '2025-11-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 8. Classes for all branches
        DB::table('classes')->insert([
            // Manikling Branch
            ['trainer_id' => 1, 'branch_id' => 1, 'name' => 'Strength & Power', 'description' => 'Build muscle and maximize your strength potential', 'schedule_day' => 'Mon,Wed,Fri', 'start_time' => '06:00:00', 'end_time' => '07:00:00', 'max_capacity' => 15, 'fee' => 0.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 2, 'branch_id' => 1, 'name' => 'Zumba Cardio Blast', 'description' => 'High-energy dance workout for cardio fitness', 'schedule_day' => 'Tue,Thu,Sat', 'start_time' => '07:00:00', 'end_time' => '08:00:00', 'max_capacity' => 25, 'fee' => 0.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Banay-Banay Branch
            ['trainer_id' => 3, 'branch_id' => 2, 'name' => 'CrossFit WOD', 'description' => 'Intense functional fitness daily workout', 'schedule_day' => 'Mon,Tue,Wed,Thu,Fri', 'start_time' => '05:30:00', 'end_time' => '06:30:00', 'max_capacity' => 12, 'fee' => 200.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 4, 'branch_id' => 2, 'name' => 'Morning Yoga Flow', 'description' => 'Relaxing yoga for flexibility and balance', 'schedule_day' => 'Mon,Wed,Fri', 'start_time' => '07:00:00', 'end_time' => '08:00:00', 'max_capacity' => 20, 'fee' => 0.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Lupon Branch
            ['trainer_id' => 5, 'branch_id' => 3, 'name' => 'Cardio Blast', 'description' => 'Intense cardio and fat-burning session', 'schedule_day' => 'Tue,Thu,Sat', 'start_time' => '06:00:00', 'end_time' => '07:00:00', 'max_capacity' => 18, 'fee' => 0.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 5, 'branch_id' => 3, 'name' => 'Weight Loss Bootcamp', 'description' => '30-minute high-intensity weight loss program', 'schedule_day' => 'Mon,Wed,Fri,Sun', 'start_time' => '17:30:00', 'end_time' => '18:00:00', 'max_capacity' => 20, 'fee' => 100.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Pantukan Branch
            ['trainer_id' => 6, 'branch_id' => 4, 'name' => 'Boxing Fundamentals', 'description' => 'Learn boxing basics and techniques', 'schedule_day' => 'Mon,Wed,Fri,Sat', 'start_time' => '18:00:00', 'end_time' => '19:00:00', 'max_capacity' => 16, 'fee' => 150.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 6, 'branch_id' => 4, 'name' => 'Kickboxing Advanced', 'description' => 'Advanced kickboxing techniques and drills', 'schedule_day' => 'Tue,Thu,Sat', 'start_time' => '19:00:00', 'end_time' => '20:00:00', 'max_capacity' => 14, 'fee' => 200.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
