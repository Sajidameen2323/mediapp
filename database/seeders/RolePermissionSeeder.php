<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage-users',
            'manage-patients',
            'view-medical-records',
            'create-medical-records',
            'edit-medical-records',
            'delete-medical-records',
            'manage-prescriptions',
            'create-prescriptions',
            'view-prescriptions',
            'dispense-medications',
            'manage-lab-tests',
            'create-lab-tests',
            'view-lab-results',
            'manage-appointments',
            'view-appointments',
            'create-appointments',
            'manage-inventory',
            'view-reports',
            'generate-reports',
            'manage-system-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $doctorRole = Role::create(['name' => 'doctor']);
        $doctorRole->givePermissionTo([
            'manage-patients',
            'view-medical-records',
            'create-medical-records',
            'edit-medical-records',
            'manage-prescriptions',
            'create-prescriptions',
            'view-prescriptions',
            'manage-lab-tests',
            'create-lab-tests',
            'view-lab-results',
            'manage-appointments',
            'view-appointments',
            'create-appointments',
            'view-reports',
            'generate-reports',
        ]);

        $patientRole = Role::create(['name' => 'patient']);
        $patientRole->givePermissionTo([
            'view-medical-records',
            'view-prescriptions',
            'view-lab-results',
            'view-appointments',
            'create-appointments',
        ]);

        $labStaffRole = Role::create(['name' => 'laboratory_staff']);
        $labStaffRole->givePermissionTo([
            'manage-lab-tests',
            'create-lab-tests',
            'view-lab-results',
            'view-reports',
            'generate-reports',
        ]);

        $pharmacistRole = Role::create(['name' => 'pharmacist']);
        $pharmacistRole->givePermissionTo([
            'view-prescriptions',
            'dispense-medications',
            'manage-inventory',
            'view-reports',
            'generate-reports',
        ]);
    }
}
