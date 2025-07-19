<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListDatabaseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:list-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all tables in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tableColumn = "Tables_in_" . $dbName;
        
        $this->info('Database: ' . $dbName);
        $this->info('Available tables:');
        
        foreach ($tables as $table) {
            $this->line("- " . $table->$tableColumn);
        }
        
        // Specifically check for payments table
        $paymentTableExists = collect($tables)->contains(function ($table) use ($tableColumn) {
            return $table->$tableColumn == 'payments';
        });
        
        if ($paymentTableExists) {
            $this->info('✓ Payments table exists');
        } else {
            $this->error('✗ Payments table does not exist');
            
            // Get migration status for payments migration
            $this->info('Checking migration status:');
            $this->call('migrate:status', [
                '--path' => 'database/migrations/2025_07_19_000001_create_payments_table.php'
            ]);
        }
    }
}
