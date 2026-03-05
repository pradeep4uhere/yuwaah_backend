<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\LearnerCourse;

class SyncYhubLearnersWithCourseName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yhub:sync-coursename-learners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync All Learners with Completed Course and Course Name';

    /**
     * Execute the console command.
     */
    public function handleFirsttime()
    {
        $this->info('Fetching data from dashboard API...');
        
        $response = Http::get(config('api.bigquery_coursename_url'));
    
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
                $phone = trim($data['Phone_Number']);
                if (preg_match('/^\+91\s?(\d{10})$/', $phone, $matches)) {
                    $phone = $matches[1]; // Extract 10 digit number
                }
                $learner = LearnerCourse::Create(
                    [
                        'phone_number'      => $phone,
                        'course_name'       => $data['CourseName'] ?? null,
                        'completed_course'  => (strtolower($data['Completed_1_Course']) == 'yes') ? 1 : 0,
                        'load_date'         => isset($data['Load_Date']) ? date('Y-m-d', strtotime($data['Load_Date'])) : null,
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
    
    $url = config('api.bigquery_coursename_count_url');  // API that returns total count
    //dd($url);
    $dataUrl = config('api.bigquery_coursename_url'); // Your existing data API
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
    $isFirstTime = LearnerCourse::count() === 0;
    $latestLoadDate = !$isFirstTime ? LearnerCourse::max('load_date') : null;
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
                if (!LearnerCourse::where('phone_number', $data['Phone_Number'])->exists()) {
                    $phone = trim($data['Phone_Number']);
                    if (preg_match('/^\+91\s?(\d{10})$/', $phone, $matches)) {
                        $phone = $matches[1]; // Extract 10 digit number
                    }
                    LearnerCourse::create([
                        'phone_number'       => $phone ?? null,
                        'course_name'       => $data['CourseName'] ?? null,
                        'completed_course' => (strtolower($data['Completed_1_Course']) == 'yes') ? 1 : 0,
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