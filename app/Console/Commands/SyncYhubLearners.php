<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\YhubLearner;

class SyncYhubLearners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yhub:sync-learners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handleFirsttime()
    {
        $this->info('Fetching data from YHUB API...');
    
        $response = Http::get(config('api.bigquery_url'));
    
        $this->info("HTTP Status: " . $response->status());
    
        if ($response->failed()) {
            $this->error('API request failed.');
            return Command::FAILURE;
        }
    
        $learners = $response->json();
    
        if (!is_array($learners) || empty($learners)) {
            $this->warn('No data returned from API.');
            return Command::SUCCESS;
        }
    
        $this->info("Total records fetched: " . count($learners));
        //die;
    
        $chunks = array_chunk($learners, 1000);
        $created = 0;
        $updated = 0;
    
        foreach ($chunks as $index => $chunk) {
            $this->info("Processing chunk " . ($index + 1) . " of " . count($chunks));
    
            foreach ($chunk as $data) {
                $learner = YhubLearner::Create(
                    [
                        'country'             => $data['Country'] ?? null,
                        'email_address'       => $data['Email Address'],
                        'user_id'             => $data['User ID'] ?? null,
                        'first_name'          => $data['FirstName'] ?? null,
                        'last_name'           => $data['LastName'] ?? null,
                        'gender'              => $data['Gender'] ?? null,
                        'role'                => $data['Role'] ?? null,
                        'grade'               => $data['Grade'] ?? null,
                        'state'               => $data['State'] ?? null,
                        'district'            => $data['District'] ?? null,
                        'school'              => $data['School'] ?? null,
                        'course_name'         => $data['Course Name'],
                        'completion_status'   => $data['Completion Status'] ?? 0,
                        'course_end_datetime' => isset($data['CourseEndDatetime']) ? date('Y-m-d H:i:s', strtotime($data['CourseEndDatetime'])) : null,
                        'completion_percent'  => $data['Completion %'] ?? null,
                        'load_date'           => isset($data['Load_Date']) ? date('Y-m-d', strtotime($data['Load_Date'])) : null,
                    ]
                );
    
                if ($learner->wasRecentlyCreated) {
                    $created++;
                } else {
                    $updated++;
                }
            }
        }
    
        $this->info("Sync complete. Created: $created | Updated: $updated");
        return Command::SUCCESS;
    }



    
    public function handle()
{
    $this->info('Fetching total number of records from BigQuery...');
    
    $url = env('BIGQUERY_API_COUNT_URL_LIVE'); // API that returns total count
    
    $dataUrl = env('BIGQUERY_API_URL_LIVE'); // Your existing data API
    $limit = 1000;
    $created = 0;

    // 1. Get total count
    $response = Http::get($url);
    if ($response->failed()) {
        $this->error("Failed to fetch total count.");
        return Command::FAILURE;
    }

    $total = $response->json('total');
    $this->info("Total records in BigQuery: $total");

    // 2. Check if table is empty
    $isFirstTime = YhubLearner::count() === 0;
    $latestLoadDate = !$isFirstTime ? YhubLearner::max('load_date') : null;
    //$latestLoadDate = "2025-02-04";

    $this->info($isFirstTime
        ? 'First time loading: inserting all records...'
        : "Filtering records with Load_Date > $latestLoadDate");

    // 3. Loop through BigQuery results in chunks
    for ($offset = 0; $offset < $total; $offset += $limit) {
        $this->info("Fetching records from offset: $offset");

        $response = Http::get($dataUrl, [
            'offset' => $offset,
            'limit' => $limit
        ]);

        if ($response->failed()) {
            $this->error("Failed to fetch records at offset $offset.");
            break;
        }

        $learners = $response->json();
        if (!is_array($learners) || empty($learners)) {
            $this->warn("No data returned at offset $offset.");
            break;
        }

        $filteredLearners = collect($learners)->filter(function ($data) use ($isFirstTime, $latestLoadDate) {
            if ($isFirstTime) return true;
            if (empty($data['Load_Date'])) return false;

            $loadDate = date('Y-m-d', strtotime($data['Load_Date']));
            return $loadDate > $latestLoadDate;
        })->values()->all();

        $this->info("Filtered records to insert: " . count($filteredLearners));

        foreach (array_chunk($filteredLearners, 1000) as $chunk) {
            $mobiles = [];
            foreach ($chunk as $data) {
                if (!YhubLearner::where('email_address', $data['Email Address'])->exists()) {
                    YhubLearner::create([
                        'email_address'       => $data['Email Address'] ?? null,
                        'completion_status' => (strtolower($data['Completed_1_Course']) == 'yes') ? 1 : 0,
                        'load_date'           => isset($data['Load_Date']) ? date('Y-m-d', strtotime($data['Load_Date'])) : null,
                    ]);
                    $created++;
                }
            }
            if ($mobiles) {
                DB::table('learners')
                    ->whereIn('normalized_mobile', $mobiles)
                    ->update(['course_completed' => 1]);
            }
        }
    }

    $this->info("Sync complete. Total Created: $created");
    return Command::SUCCESS;
}
    
}