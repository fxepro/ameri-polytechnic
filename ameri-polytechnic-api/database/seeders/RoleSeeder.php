<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'student',
            'applicant',
            'faculty',
            'admissions_officer',
            'academic_advisor',
            'finance_officer',
            'system_admin',
            'employer_partner',
        ];

        foreach ($roles as $role) {
            \DB::table('roles')->insertOrIgnore([
                'role_name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
