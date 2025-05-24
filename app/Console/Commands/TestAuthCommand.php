<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class TestAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:auth {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test authorization gates for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $users = [User::find($userId)];
            if (!$users[0]) {
                $this->error("User with ID {$userId} not found.");
                return;
            }
        } else {
            $users = User::all();
        }

        $this->info('Testing Authorization Gates');
        $this->info('==========================');

        foreach ($users as $user) {
            if (!$user) continue;
            
            $this->newLine();
            $this->info("User: {$user->name} (ID: {$user->id})");
            $this->info("Type: {$user->user_type}");
            $this->info("Active: " . ($user->is_active ? 'Yes' : 'No'));
            
            $this->newLine();
            $this->info('Gate Tests:');
            
            $gates = [
                'admin-access',
                'doctor-access', 
                'patient-access',
                'lab-access',
                'pharmacy-access'
            ];
            
            foreach ($gates as $gate) {
                $result = Gate::forUser($user)->allows($gate);
                $status = $result ? '<fg=green>ALLOWED</>' : '<fg=red>DENIED</>';
                $this->line("  - {$gate}: {$status}");
            }
            
            $this->newLine();
            $this->info('Role Tests:');
            
            $roles = ['admin', 'doctor', 'patient', 'laboratory_staff', 'pharmacist'];
            
            foreach ($roles as $role) {
                $hasRole = $user->hasRole($role);
                $status = $hasRole ? '<fg=green>YES</>' : '<fg=red>NO</>';
                $this->line("  - Has {$role} role: {$status}");
            }
            
            $this->info('----------------------------------------');
        }
    }
}
