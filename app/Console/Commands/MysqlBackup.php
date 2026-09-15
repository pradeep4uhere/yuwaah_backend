<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MysqlBackup extends Command
{
    protected $signature = 'backup:mysql';

    protected $description = 'Backup MySQL databases and compress them';

    public function handle()
    {
        $backupPath = storage_path('app/backups/mysql');

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        /*
         * Delete old backups
         */
        foreach (glob($backupPath . '/*.sql.gz') as $oldFile) {
            @unlink($oldFile);
        }

        /*
         * Database 1
         */
        $db1Success = $this->createBackup(
            config('database.connections.mysql.host'),
            config('database.connections.mysql.port'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $backupPath
        );

        if (!$db1Success) {
            $this->error(
                'First database backup failed: ' .
                config('database.connections.mysql.database')
            );

            return Command::FAILURE;
        }

        /*
         * Database 2
         */
        $db2Success = $this->createBackup(
            config('database.connections.mysql2.host'),
            config('database.connections.mysql2.port'),
            config('database.connections.mysql2.username'),
            config('database.connections.mysql2.password'),
            config('database.connections.mysql2.database'),
            $backupPath
        );

        if (!$db2Success) {
            $this->error(
                'Second database backup failed: ' .
                config('database.connections.mysql2.database')
            );

            return Command::FAILURE;
        }

        $this->info('All database backups completed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Create compressed MySQL backup.
     */
    private function createBackup(
        $dbHost,
        $dbPort,
        $dbUser,
        $dbPass,
        $dbName,
        $backupPath
    ) {
        $timestamp = now()->format('Y-m-d_H-i-s');
    
        $finalFile = $backupPath . '/' .
            $dbName . '_' . $timestamp . '.sql.gz';
    
        $tempSqlFile = tempnam(sys_get_temp_dir(), 'mysql_sql_');
        $errorFile = tempnam(sys_get_temp_dir(), 'mysql_error_');
    
        try {
    
            if (empty($dbUser) || empty($dbPass) || empty($dbName)) {
                throw new \RuntimeException(
                    "Missing database configuration for {$dbName}"
                );
            }
    
            /*
             * Pass password through the environment instead of
             * command-line or MySQL option file.
             */
            $command = sprintf(
                'MYSQL_PWD=%s mysqldump ' .
                '-h %s ' .
                '-P %s ' .
                '-u %s ' .
                '--no-tablespaces ' .
                '--single-transaction ' .
                '--routines ' .
                '--triggers ' .
                '--events ' .
                '%s > %s 2> %s',
                escapeshellarg($dbPass),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbName),
                escapeshellarg($tempSqlFile),
                escapeshellarg($errorFile)
            );
    
            $output = [];
    
            exec($command, $output, $returnCode);
    
            $errorOutput = '';
    
            if (file_exists($errorFile)) {
                $errorOutput = trim(
                    file_get_contents($errorFile)
                );
            }
    
            if ($returnCode !== 0) {
    
                $this->error(
                    "mysqldump failed for database: {$dbName}"
                );
    
                if (!empty($errorOutput)) {
                    $this->error($errorOutput);
                }
    
                return false;
            }
    
            if (
                !file_exists($tempSqlFile) ||
                filesize($tempSqlFile) === 0
            ) {
                $this->error(
                    "Empty SQL backup generated for: {$dbName}"
                );
    
                return false;
            }
    
            /*
             * Compress backup.
             */
            $gzipCommand = sprintf(
                'gzip -c %s > %s',
                escapeshellarg($tempSqlFile),
                escapeshellarg($finalFile)
            );
    
            exec(
                $gzipCommand,
                $gzipOutput,
                $gzipReturnCode
            );
    
            if ($gzipReturnCode !== 0) {
    
                $this->error(
                    "gzip failed for database: {$dbName}"
                );
    
                @unlink($finalFile);
    
                return false;
            }
    
            if (
                !file_exists($finalFile) ||
                filesize($finalFile) === 0
            ) {
                $this->error(
                    "Final backup file is empty: {$dbName}"
                );
    
                @unlink($finalFile);
    
                return false;
            }
    
            $this->info(
                "Backup created: " .
                basename($finalFile) .
                " (" .
                $this->formatBytes(filesize($finalFile)) .
                ")"
            );
    
            return true;
    
        } catch (\Throwable $e) {
    
            $this->error(
                "Backup exception for {$dbName}: " .
                $e->getMessage()
            );
    
            \Log::error(
                'MySQL backup exception',
                [
                    'database' => $dbName,
                    'error' => $e->getMessage(),
                ]
            );
    
            return false;
    
        } finally {
    
            if (file_exists($tempSqlFile)) {
                @unlink($tempSqlFile);
            }
    
            if (file_exists($errorFile)) {
                @unlink($errorFile);
            }
        }
    }

    /**
     * Convert bytes to readable format.
     */
    private function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }
}