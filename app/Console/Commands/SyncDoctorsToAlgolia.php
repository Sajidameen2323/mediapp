<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Doctor;

class SyncDoctorsToAlgolia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctors:sync-algolia {--fresh : Clear and rebuild the entire index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync doctors to Algolia search index with symptom keywords';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Algolia sync for doctors...');

        if ($this->option('fresh')) {
            $this->info('Clearing existing Algolia index...');
            try {
                Doctor::removeAllFromSearch();
                $this->info('Index cleared successfully.');
            } catch (\Exception $e) {
                $this->warn('Could not clear index: ' . $e->getMessage());
            }
        }

        $doctors = Doctor::with(['user', 'services'])
            ->where('is_available', true)
            ->get();

        if ($doctors->isEmpty()) {
            $this->warn('No available doctors found to sync.');
            return;
        }

        $this->info("Found {$doctors->count()} doctors to sync.");

        $progressBar = $this->output->createProgressBar($doctors->count());
        $progressBar->start();

        $synced = 0;
        $errors = 0;

        foreach ($doctors as $doctor) {
            try {
                $doctor->searchable();
                $synced++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Failed to sync doctor {$doctor->id}: " . $e->getMessage());
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("Sync completed!");
        $this->info("Successfully synced: {$synced} doctors");
        
        if ($errors > 0) {
            $this->warn("Failed to sync: {$errors} doctors");
        }

        // Verify sync by checking if search works
        $this->info('Verifying search functionality...');
        try {
            $testResults = Doctor::search('headache')->take(1)->get();
            $this->info('Search verification successful!');
        } catch (\Exception $e) {
            $this->error('Search verification failed: ' . $e->getMessage());
            $this->warn('Make sure your Algolia credentials are correctly configured in .env file.');
        }
    }
}
