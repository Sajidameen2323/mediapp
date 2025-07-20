<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetTimezone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-timezone {timezone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application timezone';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timezone = $this->argument('timezone');
        
        // Validate timezone
        if (!in_array($timezone, timezone_identifiers_list())) {
            $this->error("Invalid timezone: {$timezone}");
            $this->info('Available timezones: https://www.php.net/manual/en/timezones.php');
            return 1;
        }

        // Update .env file
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            $this->error('.env file not found');
            return 1;
        }

        $envContent = File::get($envPath);
        
        if (str_contains($envContent, 'APP_TIMEZONE=')) {
            // Replace existing timezone
            $envContent = preg_replace('/APP_TIMEZONE=.*/', "APP_TIMEZONE={$timezone}", $envContent);
        } else {
            // Add timezone after APP_URL
            $envContent = preg_replace(
                '/(APP_URL=.*\n)/',
                "$1APP_TIMEZONE={$timezone}\n",
                $envContent
            );
        }

        File::put($envPath, $envContent);

        // Clear config cache
        $this->call('config:clear');

        $this->info("Timezone set to: {$timezone}");
        $this->info("Current time: " . now()->format('Y-m-d H:i:s T'));
        
        return 0;
    }
}
