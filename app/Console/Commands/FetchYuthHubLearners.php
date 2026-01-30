<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Learner; // if you're using the Learner model
use App\Models\ApiFetchLog;

class FetchYuthHubLearners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:learners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting API data fetch...');

        $page = 1;
        $perPage = 1000;
        $totalRecords = null;

        try {
            // Step 1: Get Token
            $loginResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://youthhub.org/api/login', [
                'username' => 'powerbi',
                'password' => 'MtufISqYihHdftiS'
            ]);

            if (!$loginResponse->ok()) {
                $this->error('Failed to authenticate with YuthHub.');
                return;
            }

            $token = $loginResponse->json('accessToken');
            $cleanToken = str_replace('Bearer ', '', $token);

            // Step 2: Loop with pagination
            $page = 1;
            $perPage = 1000;
            $totalRecords = null;

            do {
                $this->info("Fetching page: $page");

                $userResponse = Http::withToken($cleanToken)->get('https://youthhub.org/api/gpud', [
                    'pn' => $page,
                    'ps' => $perPage,
                    'ma' => ''
                ]);

                if (!$userResponse->ok()) {
                    $this->error("API fetch failed on page $page");
                    break;
                }

                $responseData = $userResponse->json();
                $users = $responseData['data'] ?? [];
                $recordsFetched = count($users);
                $totalRecords = (int) $responseData['totalRecordCount'];
                $recordsRemaining = max($totalRecords - ($page * $perPage), 0);

                foreach ($users as $index => $user) {
                    $profile = $user['profileInfo'] ?? null;
                    $gender = ucfirst(strtolower($profile['gender'] ?? 'Male'));
                    $validGenders = ['Male', 'Female', 'Other'];
                    if (!in_array($gender, $validGenders)) $gender = 'Male';

                    $dob = $profile['date_of_birth'];
                    // Remove unwanted characters except numbers and /
                    // Decode URL encoded characters first
                    $dob = urldecode($dob);
                    $dob = preg_replace('/[^0-9\/]/', '', $dob);
                    $dob = trim($dob);
                    //if($profile['first_name']=='Khushi'){
                        // echo $dob;
                        // var_dump($profile['date_of_birth']);
                        // echo "DOB: [$dob]\n";
                        // die;
                        // die;
                    //}
                    try {
                        $dob = (!empty($dob) && strtolower($dob) !== 'undefined')
                            ? Carbon::createFromFormat('d/m/Y', $dob)->format('Y-m-d')
                            : null;
                    } catch (\Exception $e) {
                        $dob = null;
                    }
                    //echo '---'.$dob.'---';
                    //echo "\n";
                    //  if($profile['first_name']=='Khushi'){
                    //     echo $dob;
                    //     var_dump($profile['date_of_birth']);
                    //     echo "DOB: [$dob]\n";
                    //     die;
                    //     die;
                    // }
                    //echo $profile['engilsh_proficiency_level'];
                    Learner::updateOrCreate(
                        // Lookup criteria — must be unique identifier, like email or external_id
                        ['primary_phone_number' => $profile['user_phone_number']],
                        [
                            'first_name' => $profile['first_name'] ?? 'NA',
                            'last_name' => $profile['last_name'] ?? 'NA',
                            'email' => $profile['email_id'] ?? time().rand(100000,999999).'@yuthhub.com',
                            'gender' => $gender,
                            'secondary_phone_number' => $profile['secondary_phone_number'] ?? null,
                            'current_job_title' => $profile['current_job_title'] ?? null,
                            'date_of_birth' => $dob,
                            'current_location_zip' => $profile['current_location_zip'] ?? null,
                            'profile_photo_url' => $profile['profile_photo_url'] ?? null,
                            'current_street' => isset($profile['current_address']) 
                            ? substr($profile['current_address'], 0, 250) 
                            : null,
                            'education_level' => is_array($profile['education_level'] ?? null)
                                ? json_encode($profile['education_level'])
                                : ($profile['education_level'] ?? null), 
                            'experience_years' => is_array($profile['experience_years'] ?? null)
                                ? json_encode($profile['experience_years'])
                                : ($profile['experience_years'] ?? null),
                            'MONTHLY_FAMILY_INCOME_RANGE'=>$profile['monthly_family_income_range'],
                            'USER_EMAIL'=>$profile['user_email'],
                            'DISTRICT_CITY'=>$profile['city'],
                            'STATE'=>$profile['loc_state'],
                            'PIN_CODE'=>$profile['pin_code'],
                            'PROGRAM_CODE'=>$profile['program_code'],
                            'PROGRAM_STATE'=>$profile['institute_state'],
                            'PROGRAM_DISTRICT'=>$profile['institute_district'],
                            'UNIT_INSTITUTE'=>$profile['unit'],
                            'SOCIAL_CATEGORY'=>$profile['social_category'],
                            'RELIGION'=>$profile['religion'],
                            'USER_MARIAL_STATUS'=>$profile['marital_status'],
                            'DIFFRENTLY_ABLED'=>$profile['differently_abled'],
                            'IDENTITY_DOCUMENTS'=>$profile['identity_document'],
                            'REASON_FOR_LEARNING_NEW_SKILLS'=>$profile['reason_for_learning_new_skills'],
                            'EARN_AT_MY_OWN_TIME'=>$profile['earn_at_my_own_time'],
                            'RELOCATE_FOR_JOB'=>$profile['job_preferences_mobility'],
                            'no_of_pathway_completed'=>$profile['no_of_pathway_completed'],
                            'no_of_pathway_enrolled'=>$profile['no_of_pathway_enrolled'],
                            'preferred_job_domain1'=>$profile['get_a_job_qualification'],
                            'preferred_job_domain2'=>$profile['get_a_job_when_can_start'],
                            'preferred_job_domain3'=>$profile['get_a_job_experiance'],
                            'preferred_job_domain4'=>$profile['run_a_business'],
                            'english_knowledge'=> $profile['engilsh_proficiency_level'],
                            'digital_proficiency'=> $profile['digital_proficiency_level'],
                            'create_date'=>$profile['create_date'],
                        ]
                    );
                }

                // Log this fetch to api_fetch_log table
                ApiFetchLog::create([
                    'page_number' => $page,
                    'records_fetched' => $recordsFetched,
                    'total_records' => $totalRecords,
                    'records_remaining' => $recordsRemaining,
                ]);

                $page++;

            } while ($recordsRemaining > 0);

            $this->info("All records fetched successfully.");

        } catch (\Exception $e) {
            Log::error('Exception during learner fetch: ' . $e->getMessage());
            $this->error('An error occurred: ' . $e->getMessage());
        }



    }




    private function parseDate($date)
    {
        if (!$date) return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

}
