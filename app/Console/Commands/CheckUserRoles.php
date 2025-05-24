<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and fix user roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::all();
        
        $this->info('Checking User Roles and User Types');
        $this->info('===================================');
        
        foreach ($users as $user) {
            $this->newLine();
            $this->info("User: {$user->name} (ID: {$user->id})");
            $this->info("Type: {$user->user_type}");
            $this->info("Active: " . ($user->is_active ? 'Yes' : 'No'));
            
            $roles = $user->roles->pluck('name')->toArray();
            $this->info("Assigned Roles: " . (empty($roles) ? 'NONE' : implode(', ', $roles)));
            
            // Check if user has the role that matches their user_type
            $expectedRole = $user->user_type;
            $hasCorrectRole = $user->hasRole($expectedRole);
            
            if (!$hasCorrectRole) {
                $this->warn("  ⚠️  User does not have the '{$expectedRole}' role!");
                
                // Fix the role assignment
                $this->info("  🔧 Assigning '{$expectedRole}' role...");
                $user->assignRole($expectedRole);
                $this->info("  ✅ Role assigned successfully!");
            } else {
                $this->info("  ✅ User has correct role");
            }
        }
        
        $this->newLine();
        $this->info('All users checked and fixed!');
    }
}
