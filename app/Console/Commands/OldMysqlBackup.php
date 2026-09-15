<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OldMysqlBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old_backup:mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create MySQL database backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $backupPath = storage_path('app/backups');

        // Create backup directory if it doesn't exist
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        /*
         * Delete old backup files
         */
        foreach (glob($backupPath . '/*.sql.gz') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        /*
         * ============================
         * FIRST DATABASE
         * ============================
         */
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT', 3306);

        $fileName1 = $dbName . '_' . date('Y-m-d_H-i-s') . '.sql.gz';
        $fullPath1 = $backupPath . '/' . $fileName1;

        $result1 = $this->createBackup(
            $dbName,
            $dbUser,
            $dbPass,
            $dbHost,
            $dbPort,
            $fullPath1
        );

        if (!$result1) {
            $this->error("First database backup failed: {$dbName}");
            Log::error("MySQL backup failed", [
                'database' => $dbName,
            ]);

            return Command::FAILURE;
        }

        $this->info("Backup created: {$fileName1}");

        /*
         * ============================
         * SECOND DATABASE
         * ============================
         */
        $dbName2 = env('DB_SECOND_DATABASE');
        $dbUser2 = env('DB_SECOND_USERNAME');
        $dbPass2 = env('DB_SECOND_PASSWORD');
        $dbHost2 = env('DB_SECOND_HOST');
        $dbPort2 = env('DB_SECOND_PORT', 3306);

        $fileName2 = $dbName2 . '_' . date('Y-m-d_H-i-s') . '.sql.gz';
        $fullPath2 = $backupPath . '/' . $fileName2;

        $result2 = $this->createBackup(
            $dbName2,
            $dbUser2,
            $dbPass2,
            $dbHost2,
            $dbPort2,
            $fullPath2
        );

        if (!$result2) {
            $this->error("Second database backup failed: {$dbName2}");
            Log::error("MySQL backup failed", [
                'database' => $dbName2,
            ]);

            return Command::FAILURE;
        }

        $this->info("Backup created: {$fileName2}");

        Log::info('MySQL backups created successfully.', [
            'database_1' => $dbName,
            'database_2' => $dbName2,
            'backup_path' => $backupPath,
        ]);

        $this->info('All database backups completed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Create a compressed MySQL backup.
     */
    private function createBackup(
        string $dbName,
        string $dbUser,
        string $dbPass,
        string $dbHost,
        string $dbPort,
        string $backupFile
    ): bool {
        // Create temporary MySQL config
        $configFile = tempnam(sys_get_temp_dir(), 'mysql_backup_');
    
        if ($configFile === false) {
            $this->error('Unable to create temporary MySQL config file.');
            return false;
        }
    
        // MySQL credentials
        $configContent = "[client]\n";
        $configContent .= "user=" . $dbUser . "\n";
        $configContent .= "password=" . $dbPass . "\n";
        $configContent .= "host=" . $dbHost . "\n";
        $configContent .= "port=" . $dbPort . "\n";
    
        file_put_contents($configFile, $configContent);
        chmod($configFile, 0600);
    
        // Temporary SQL file
        $tempSqlFile = $backupFile . '.tmp.sql';
    
        /*
         * IMPORTANT:
         * --defaults-extra-file must be the FIRST option
         * after mysqldump.
         */
        $command = sprintf(
            'mysqldump --defaults-extra-file=%s --no-tablespaces --single-transaction --routines --triggers --events %s > %s 2>&1',
            escapeshellarg($configFile),
            escapeshellarg($dbName),
            escapeshellarg($tempSqlFile)
        );
    
        $output = [];
        $returnCode = 0;
    
        exec($command, $output, $returnCode);
    
        // Remove credentials immediately
        if (file_exists($configFile)) {
            unlink($configFile);
        }
    
        // Check mysqldump
        if ($returnCode !== 0) {
    
            $this->error("mysqldump failed for database: {$dbName}");
    
            Log::error('mysqldump failed', [
                'database' => $dbName,
                'return_code' => $returnCode,
                'output' => $output,
            ]);
    
            if (file_exists($tempSqlFile)) {
                unlink($tempSqlFile);
            }
    
            return false;
        }
    
        // Check SQL file
        if (!file_exists($tempSqlFile) || filesize($tempSqlFile) === 0) {
    
            $this->error("Backup SQL file is empty: {$dbName}");
    
            if (file_exists($tempSqlFile)) {
                unlink($tempSqlFile);
            }
    
            return false;
        }
    
        // Compress SQL
        $gzipCommand = sprintf(
            'gzip -c %s > %s',
            escapeshellarg($tempSqlFile),
            escapeshellarg($backupFile)
        );
    
        $gzipOutput = [];
        $gzipReturnCode = 0;
    
        exec($gzipCommand, $gzipOutput, $gzipReturnCode);
    
        // Remove temporary SQL
        if (file_exists($tempSqlFile)) {
            unlink($tempSqlFile);
        }
    
        if ($gzipReturnCode !== 0) {
    
            $this->error("gzip failed for database: {$dbName}");
    
            if (file_exists($backupFile)) {
                unlink($backupFile);
            }
    
            return false;
        }
    
        // Final validation
        if (!file_exists($backupFile) || filesize($backupFile) === 0) {
    
            $this->error("Backup file is empty: {$dbName}");
    
            if (file_exists($backupFile)) {
                unlink($backupFile);
            }
    
            return false;
        }
    
        return true;
    }
    
}


