<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\ClassLevel;
use App\Models\Stream;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles (only if they don't exist)
        if (Role::count() === 0) {
            $super_admin = Role::create([
                'name' => 'Super Admin',
                'description' => 'System owner with full access and policy control',
                'authority' => 'BOTH',
                'permissions' => [
                    'users.manage', 'roles.manage', 'settings.manage',
                    'approvals.manage', 'reports.view', 'audit.view'
                ]
            ]);

            $principal = Role::create([
                'name' => 'Principal',
                'description' => 'Academic & staff oversight',
                'authority' => 'TSC',
                'permissions' => [
                    'staff.manage', 'students.approve', 'staff.approve',
                    'academics.manage', 'attendance.view', 'reports.view',
                    'approvals.review'
                ]
            ]);

            $deputy_academic = Role::create([
                'name' => 'Deputy Academic',
                'description' => 'Curriculum & attendance management',
                'authority' => 'TSC',
                'permissions' => [
                    'academics.manage', 'attendance.record', 'grades.manage',
                    'reports.academic', 'curriculum.manage'
                ]
            ]);

            $deputy_admin = Role::create([
                'name' => 'Deputy Admin',
                'description' => 'Operations & discipline',
                'authority' => 'BOM',
                'permissions' => [
                    'operations.manage', 'discipline.manage', 'staff.view',
                    'students.view', 'reports.operational'
                ]
            ]);

            $teacher = Role::create([
                'name' => 'Teacher',
                'description' => 'Teaching and grading',
                'authority' => 'TSC',
                'permissions' => [
                    'attendance.record', 'grades.record', 'students.view'
                ]
            ]);

            $bursar = Role::create([
                'name' => 'Bursar',
                'description' => 'Fees and payroll management',
                'authority' => 'BOM',
                'permissions' => [
                    'fees.manage', 'payments.verify', 'payroll.manage',
                    'reports.financial', 'audit.view'
                ]
            ]);
        }

        // Create class levels (only if they don't exist)
        if (ClassLevel::count() === 0) {
            ClassLevel::create(['name' => 'Form 1', 'level' => 1]);
            ClassLevel::create(['name' => 'Form 2', 'level' => 2]);
            ClassLevel::create(['name' => 'Form 3', 'level' => 3]);
            ClassLevel::create(['name' => 'Form 4', 'level' => 4]);
        }

        // Create streams (only if they don't exist)
        if (Stream::count() === 0) {
            Stream::create(['name' => 'Stream A']);
            Stream::create(['name' => 'Stream B']);
            Stream::create(['name' => 'Stream C']);
        }

        // Create super admin user (only if none exists)
        if (User::count() === 0) {
            User::create([
                'name' => 'System Administrator',
                'email' => 'admin@elimucore.local',
                'phone' => '+254700000000',
                'password' => bcrypt('Admin@123'),
                'role' => 'super_admin',
                'is_approved' => true,
                'approved_at' => now(),
                'is_active' => true
            ]);

            // Create sample principal
            User::create([
                'name' => 'Principal John Kamau',
                'email' => 'principal@elimucore.local',
                'phone' => '+254700000001',
                'password' => bcrypt('Principal@123'),
                'role' => 'principal',
                'employee_id' => 'EMP001',
                'staff_number' => 'TSC0001',
                'is_approved' => true,
                'approved_at' => now(),
                'is_active' => true
            ]);

            // Create sample teacher
            User::create([
                'name' => 'Teacher Jane Mwangi',
                'email' => 'teacher@elimucore.local',
                'phone' => '+254700000002',
                'password' => bcrypt('Teacher@123'),
                'role' => 'teacher',
                'employee_id' => 'EMP002',
                'staff_number' => 'TSC0002',
                'is_approved' => true,
                'approved_at' => now(),
                'is_active' => true
            ]);

            // Create sample bursar
            User::create([
                'name' => 'Bursar Peter Kipchoge',
                'email' => 'bursar@elimucore.local',
                'phone' => '+254700000003',
                'password' => bcrypt('Bursar@123'),
                'role' => 'bursar',
                'employee_id' => 'EMP003',
                'staff_number' => 'BOM0001',
                'is_approved' => true,
                'approved_at' => now(),
                'is_active' => true
            ]);

            $this->command->info('✅ Database seeding completed successfully!');
            $this->command->info('');
            $this->command->info('Super Admin Account:');
            $this->command->info('  Email: admin@elimucore.local');
            $this->command->info('  Password: Admin@123');
            $this->command->info('');
            $this->command->info('Sample Accounts:');
            $this->command->info('  Principal: principal@elimucore.local / Principal@123');
            $this->command->info('  Teacher: teacher@elimucore.local / Teacher@123');
            $this->command->info('  Bursar: bursar@elimucore.local / Bursar@123');
        } else {
            $this->command->info('Database already seeded!');
        }
    }
}

