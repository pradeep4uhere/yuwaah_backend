<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class MysqlBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create MySQL database backup';


   

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');

        $backupPath = storage_path('app/backups');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Delete old .sql.gz backup files
        $files = glob($backupPath . '/*.sql.gz');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        $fileName = $dbName . '_' . date('Y-m-d_H-i-s') . '.sql.gz';
        $fullPath = $backupPath . '/' . $fileName;

        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > {$fullPath}";

        system($command);

        //Another Database

        $dbName2 = env('DB_SECOND_DATABASE');
        $dbUser2 = env('DB_SECOND_USERNAME');
        $dbPass2 = env('DB_SECOND_PASSWORD');
        $dbHost2 = env('DB_SECOND_HOST');

        $backupPath = storage_path('app/backups');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $fileName = $dbName2 . '_' . date('Y-m-d_H-i-s') . '.sql.gz';
        $fullPath = $backupPath . '/' . $fileName;

        $command = "mysqldump --user={$dbUser2} --password={$dbPass2} --host={$dbHost2} {$dbName2}  | gzip > {$fullPath}";

        system($command);

        //$this->uploadToDrive($fullPath);

        $this->info("Backup created: " . $fileName);
        \Log::info('Backup uploaded successfully.');

    }

}
