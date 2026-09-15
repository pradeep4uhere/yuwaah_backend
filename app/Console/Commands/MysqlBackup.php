<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        /*
         * Temporary MySQL configuration file
         */
        $configFile = tempnam(sys_get_temp_dir(), 'mysql_backup_');
    
        if ($configFile === false) {
            $this->error('Unable to create temporary MySQL config file.');
            return false;
        }
    
        /*
         * Write MySQL credentials
         */
        $configContent = "[client]\n";
        $configContent .= "user=" . $dbUser . "\n";
        $configContent .= "password=" . $dbPass . "\n";
        $configContent .= "host=" . $dbHost . "\n";
        $configContent .= "port=" . $dbPort . "\n";
    
        file_put_contents($configFile, $configContent);
    
        // Protect credentials
        chmod($configFile, 0600);
    
        /*
         * Temporary SQL file
         */
        $tempSqlFile = $backupFile . '.tmp.sql';
    
        /*
         * Escape values
         */
        $configFileEscaped = escapeshellarg($configFile);
        $dbNameEscaped = escapeshellarg($dbName);
        $tempSqlFileEscaped = escapeshellarg($tempSqlFile);
        $backupFileEscaped = escapeshellarg($backupFile);
    
        /*
         * Step 1:
         * Run mysqldump WITHOUT gzip.
         */
        $command = "mysqldump " .
            "--defaults-extra-file={$configFileEscaped} " .
            "--single-transaction " .
            "--no-tablespaces " .
            "--routines " .
            "--triggers " .
            "--events " .
            "{$dbNameEscaped} > {$tempSqlFileEscaped}";
    
        $output = [];
        $returnCode = 0;
    
        exec($command, $output, $returnCode);
    
        /*
         * Remove credentials immediately
         */
        if (file_exists($configFile)) {
            unlink($configFile);
        }
    
        /*
         * Check mysqldump result
         */
        if ($returnCode !== 0) {
    
            $this->error("mysqldump failed for database: {$dbName}");
    
            Log::error('mysqldump command failed', [
                'database' => $dbName,
                'return_code' => $returnCode,
                'output' => $output,
            ]);
    
            if (file_exists($tempSqlFile)) {
                unlink($tempSqlFile);
            }
    
            return false;
        }
    
        /*
         * Make sure SQL file exists
         */
        if (!file_exists($tempSqlFile) || filesize($tempSqlFile) === 0) {
    
            $this->error("mysqldump created an empty file: {$dbName}");
    
            if (file_exists($tempSqlFile)) {
                unlink($tempSqlFile);
            }
    
            return false;
        }
    
        /*
         * Step 2:
         * Compress SQL file.
         */
        $gzipCommand =
            "gzip -c {$tempSqlFileEscaped} > {$backupFileEscaped}";
    
        $gzipOutput = [];
        $gzipReturnCode = 0;
    
        exec($gzipCommand, $gzipOutput, $gzipReturnCode);
    
        /*
         * Delete temporary SQL file
         */
        if (file_exists($tempSqlFile)) {
            unlink($tempSqlFile);
        }
    
        /*
         * Check gzip result
         */
        if ($gzipReturnCode !== 0) {
    
            $this->error("gzip failed for database: {$dbName}");
    
            Log::error('gzip backup failed', [
                'database' => $dbName,
                'return_code' => $gzipReturnCode,
                'output' => $gzipOutput,
            ]);
    
            if (file_exists($backupFile)) {
                unlink($backupFile);
            }
    
            return false;
        }
    
        /*
         * Final backup validation
         */
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


