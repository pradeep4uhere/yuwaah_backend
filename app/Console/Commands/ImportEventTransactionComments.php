<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Log;
use Carbon\Carbon;

class ImportEventTransactionComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:event-comments {file : Path to CSV file}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import event transaction comments from CSV';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("CSV file not found: {$file}");
            return Command::FAILURE;
        }

        $this->info("Starting import...");
        $this->info("File: {$file}");

        $handle = fopen($file, 'r');

        if (!$handle) {
            $this->error("Unable to open CSV file.");
            return Command::FAILURE;
        }

        // Read CSV header
        $header = fgetcsv($handle);

        if (!$header) {
            fclose($handle);
            $this->error("CSV file is empty.");
            return Command::FAILURE;
        }

        // Remove BOM and trim headers
        $header = array_map(function ($value) {
            return trim(str_replace("\xEF\xBB\xBF", '', $value));
        }, $header);

        $requiredHeaders = [
            'Event ID',
            'Sakhi ID',
            'Event Comment',
            'Event Comment Date',
        ];

        foreach ($requiredHeaders as $requiredHeader) {
            if (!in_array($requiredHeader, $header)) {
                fclose($handle);

                $this->error(
                    "Required CSV column missing: {$requiredHeader}"
                );

                return Command::FAILURE;
            }
        }

        // Map header name => index
        $headerIndex = array_flip($header);

        $inserted = 0;
        $skipped = 0;
        $eventNotFound = 0;
        $invalidDate = 0;
        $errors = [];

        $batch = [];
        $batchSize = 100;

        while (($row = fgetcsv($handle)) !== false) {

            // Skip completely empty rows
            if (count(array_filter($row, fn($value) => trim($value) !== '')) === 0) {
                continue;
            }

            try {

                $eventId = trim(
                    $row[$headerIndex['Event ID']] ?? ''
                );

                $sakhiId = trim(
                    $row[$headerIndex['Sakhi ID']] ?? ''
                );

                $comment = $row[$headerIndex['Event Comment']] ?? '';

                $comment = mb_convert_encoding(
                    $comment,
                    'UTF-8',
                    'Windows-1252'
                );

                $comment = trim($comment);

                $commentDate = trim(
                    $row[$headerIndex['Event Comment Date']] ?? ''
                );

                $eventStatus = trim(
                    $row[$headerIndex['Event Status']] ?? ''
                );

                // Skip if comment is empty
                if ($comment === '') {
                    $skipped++;
                    continue;
                }

                // Event ID is required
                if ($eventId === '') {
                    $skipped++;
                    continue;
                }

                /*
                 * Find event transaction.
                 *
                 * Assuming event_transactions.id = Event ID
                 */
                $eventTransaction = DB::connection('mysql2')
                    ->table('event_transactions')
                    ->where('id', $eventId)
                    ->first();

                if (!$eventTransaction) {
                    $eventNotFound++;

                    $errors[] = [
                        'event_id' => $eventId,
                        'sakhi_id' => $sakhiId,
                        'reason' => 'Event transaction not found',
                    ];

                    continue;
                }

                /*
                * Find Sakhi / Agent
                * CSV Sakhi ID = yuwaah_sakhi.sakhi_id
                */
                $sakhi = DB::connection('mysql2')
                    ->table('yuwaah_sakhi')
                    ->where('sakhi_id', $sakhiId)
                    ->first();

                if (!$sakhi) {

                    $errors[] = [
                        'event_id' => $eventId,
                        'sakhi_id' => $sakhiId,
                        'reason' => 'Sakhi not found in yuwaah_sakhi',
                    ];

                    $this->warn(
                        "Sakhi not found: {$sakhiId} | Event ID: {$eventId}"
                    );

                    continue;
                }

                $agentId = trim($sakhi->id);

                // Convert comment date
                $createdAt = null;

                if ($commentDate !== '') {
                    try {
                        $createdAt = \Carbon\Carbon::parse($commentDate)
                            ->startOfDay()
                            ->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        $invalidDate++;

                        $errors[] = [
                            'event_id' => $eventId,
                            'reason' => "Invalid date: {$commentDate}",
                        ];

                        continue;
                    }
                }

                /*
                 * Prepare comment record
                 */
                $batch[] = [
                    'sakhi_id' => $sakhiId !== '' ? $sakhiId : null,
                    'comment' => $comment,
                    'status' => $eventStatus,
                    'agent_id' => $agentId,
                    'comment_type' => 'internal',
                    'event_transaction_id' => $eventTransaction->id,
                    'user_id' => $eventTransaction->id,
                    'user_name' => 'shweta nair',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                // Insert every 500 records
                if (count($batch) >= $batchSize) {

                    try {
                
                        DB::connection('mysql')
                            ->table('event_transaction_comments')
                            ->insert($batch);
                
                        $inserted += count($batch);
                
                        $this->info("Inserted: {$inserted}");
                
                        $batch = [];
                
                    } catch (\Throwable $e) {
                
                        $this->error("BATCH INSERT FAILED");
                        $this->error($e->getMessage());
                
                        // Save the batch for debugging
                        $errorFile = storage_path(
                            'logs/event_comment_failed_batch_' . date('Ymd_His') . '.json'
                        );
                
                        file_put_contents(
                            $errorFile,
                            json_encode(
                                $batch,
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                            )
                        );
                
                        $this->error("Failed batch saved to: {$errorFile}");
                
                        return Command::FAILURE;
                    }
                }

            } catch (\Throwable $e) {

                $errors[] = [
                    'event_id' => $eventId ?? null,
                    'reason' => $e->getMessage(),
                ];

                $this->error(
                    "Error processing Event ID {$eventId}: {$e->getMessage()}"
                );
                
            }
        }

        // Insert remaining records
        if (!empty($batch)) {

            DB::connection('mysql')->table('event_transaction_comments')
                ->insert($batch);

            $inserted += count($batch);
        }

        fclose($handle);

        /*
         * Import summary
         */
        $this->newLine();

        $this->info("======================================");
        $this->info("      IMPORT COMPLETED");
        $this->info("======================================");

        $this->info("Inserted        : {$inserted}");
        $this->info("Skipped         : {$skipped}");
        $this->info("Event Not Found : {$eventNotFound}");
        $this->info("Invalid Dates   : {$invalidDate}");
        $this->info("Errors          : " . count($errors));

        /*
         * Save errors to log
         */
        if (!empty($errors)) {

            $errorFile = storage_path(
                'logs/event_comment_import_errors_' . date('Ymd_His') . '.json'
            );

            file_put_contents(
                $errorFile,
                json_encode(
                    $errors,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                )
            );

            $this->warn(
                "Error details saved to: {$errorFile}"
            );
            
        }

        return Command::SUCCESS;
    }
}
