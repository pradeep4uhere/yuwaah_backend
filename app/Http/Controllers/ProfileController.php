<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Learner;
use Illuminate\Support\Facades\DB;
use App\Models\EventTransactionComment;
use Log;
use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Exports\EventTransactionExport;
use Maatwebsite\Excel\Facades\Excel;


class ProfileController extends Controller
{




    function listAllBigQueryTables()
    {
        $projectId = 'siif-408307';
        $datasetId = 'datamodel'; // <- Your dataset name

        $keyFilePath = storage_path('app/keys/siif-408307-5e60ea7940a7.json');
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $keyFilePath);

        try {
            $bigQuery = new BigQueryClient([
                'projectId' => $projectId,
                'keyFile' => json_decode(file_get_contents($keyFilePath), true),
            ]);

            $dataset = $bigQuery->dataset($datasetId);

            $tables = $dataset->tables();

            $tableNames = [];

            foreach ($tables as $table) {
                $tableNames[] = $table->id();  // Gets the table name
            }

            return $tableNames;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    function queryBigQuery($offset,$limit)
    {
        $projectId = 'siif-408307';

        $keyFilePath = storage_path('app/keys/siif-408307-5e60ea7940a7.json');
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $keyFilePath);


        // Create a BigQuery client
        $bigQuery = new BigQueryClient([
            'projectId' => $projectId,
            'keyFile' => json_decode(file_get_contents($keyFilePath), true),
        ]);

        $offset = (int) $offset;
        $limit = (int) $limit;

        $query = "select * from siif-408307.datamodel.Learners LIMIT $limit OFFSET $offset";
       


        try {
            // Run the query
            $queryJob = $bigQuery->query($query);
            $queryResults = $bigQuery->runQuery($queryJob);
            //dd($queryResults);

            // Fetch the results
            // Fetch the rows and return as an array
            $rows = [];
            foreach ($queryResults as $row) {
                // Convert objects to arrays and remove any double quotes
                $rows[] = str_replace('"', "",$row);
            }


            return $rows;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }







    public function allLearnerFromBigQueryList(Request $request){
        //$userlist = $this->listAllBigQueryTables();
      

        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 1000);
        $userlist = $this->queryBigQuery($offset, $limit);
        return response()->json($userlist);
    }



    function queryBigQueryCount()
    {
        $projectId = 'siif-408307';
        $keyFilePath = storage_path('app/keys/siif-408307-5e60ea7940a7.json');
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $keyFilePath);

        $bigQuery = new BigQueryClient([
            'projectId' => $projectId,
            'keyFile' => json_decode(file_get_contents($keyFilePath), true),
        ]);

        $query = "SELECT COUNT(*) as total FROM `siif-408307.datamodel.Learners`";

        try {
            $queryJob = $bigQuery->query($query);
            $queryResults = $bigQuery->runQuery($queryJob);

            foreach ($queryResults as $row) {
                return (int) $row['total'];
            }
    
            // Fallback if no row found
            return 0;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function countLearners()
    {
        $total = $this->queryBigQueryCount();
        return response()->json(['total' => $total]);
    }


    
    
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }



    public function allUsers(Request $request){
        $user = User::all();
        //dd($user);
        return view('profile.allusers', [
            'user' => $user,
        ]);
    }


    public function allEventTranscation(Request $request)
    {

            //All Program Code 

        
            $programCode = DB::connection('mysql2')
                ->table('partners')
                ->select('name')
                ->where('status', 1)
                ->get();

            //dd($programCode);
                
            $eventTypeArray = DB::connection('mysql2')
                ->table('yuwaah_event_type')
                ->where('status', 1)
                ->get();

            $eventCategoryArray = [];
            if ($request->event_type > 0) {
            //Get all Event Category
            $eventCategoryArray = DB::connection('mysql2')
                ->table('yuwaah_event_masters')
                ->where('event_type_id', $request->event_type)
                ->get();
            }


            $baseQuery = DB::connection('mysql2')
            ->table('event_transactions')
            ->leftJoin('yuwaah_event_masters', 'event_transactions.event_category', '=', 'yuwaah_event_masters.id')
            ->leftJoin('yuwaah_event_type', 'yuwaah_event_masters.event_type_id', '=', 'yuwaah_event_type.id')
            ->leftJoin('yuwaah_sakhi', 'event_transactions.ys_id', '=', 'yuwaah_sakhi.id')
            ->join('learners', 'learners.id', '=', 'event_transactions.learner_id')
            ->where('yuwaah_sakhi.csc_id','!=','Sandbox_Testing');


            $baseQuery->when($request->filled('status'), function ($q) use ($request) {
                $q->where('event_transactions.review_status', $request->status);
            });
            
            $baseQuery->when($request->event_type > 0, function ($q) use ($request) {
                $q->where('yuwaah_event_type.id', $request->event_type);
            });
            
            $baseQuery->when($request->event_category > 0, function ($q) use ($request) {
                $q->where('event_transactions.event_category', $request->event_category);
            });
            
            $baseQuery->when($request->filled('from_date') && $request->filled('to_date'), function ($q) use ($request) {
                $q->whereBetween('event_transactions.created_at', [
                    $request->from_date,
                    $request->to_date
                ]);
            });
            
            $baseQuery->when($request->filled('program_code'), function ($q) use ($request) {
                $q->where('learners.PROGRAM_CODE', 'like', "%{$request->program_code}%");
            });


            $baseQuery->when($request->filled('beneficiary_name'), function ($q) use ($request) {
                $q->where('event_transactions.beneficiary_name', 'like', "%{$request->beneficiary_name}%");
            });

            $baseQuery->when($request->filled('id'), function ($q) use ($request) {
                $q->where('event_transactions.id', '=', $request->id);
            });

            $baseQuery->when($request->filled('benificiery_mobile'), function ($q) use ($request) {
                $q->where('event_transactions.beneficiary_phone_number', 'like', "%{$request->benificiery_mobile}%");
            });


            



            $event_transactions = (clone $baseQuery)
            ->select(
                'event_transactions.*',
                'event_transactions.beneficiary_name as beneficiary_name',
                'yuwaah_event_masters.event_type as event_master_name',
                'yuwaah_event_masters.event_category as event_master_category',
                'yuwaah_event_masters.description',
                'yuwaah_event_masters.eligibility',
                'yuwaah_event_masters.fee_per_completed_transaction',
                'yuwaah_event_masters.date_event_created_in_master',
                'yuwaah_event_masters.document_1',
                'yuwaah_event_masters.document_2',
                'yuwaah_event_masters.document_3',
                'yuwaah_event_masters.status',
                'yuwaah_sakhi.csc_id',
                'yuwaah_sakhi.name as field_agent_name',
                'yuwaah_sakhi.sakhi_id',
                'yuwaah_event_type.name as event_name',
                'learners.PROGRAM_STATE',
                'learners.PROGRAM_DISTRICT',
                'learners.PROGRAM_CODE'
            )
            ->orderBy('event_transactions.review_status')
            ->orderBy('event_transactions.id', 'desc')
            ->paginate(50);


            $statusCounts = [];
            // $statusCounts = (clone $baseQuery)
            // ->select(
            //     'learners.PROGRAM_CODE',
            //     DB::raw("COUNT(*) as total"),
            //     DB::raw("SUM(CASE WHEN event_transactions.review_status = 'Accepted' THEN 1 ELSE 0 END) as accepted_count"),
            //     DB::raw("SUM(CASE WHEN event_transactions.review_status = 'Rejected' THEN 1 ELSE 0 END) as rejected_count"),
            //     DB::raw("SUM(CASE WHEN event_transactions.review_status = 'Pending' THEN 1 ELSE 0 END) as pending_count"),
            //     DB::raw("SUM(CASE WHEN event_transactions.review_status IS NULL OR event_transactions.review_status = 'Open' THEN 1 ELSE 0 END) as open_count")
            // )
            // ->groupBy('learners.PROGRAM_CODE')
            // ->orderBy('learners.PROGRAM_CODE')
            // ->get();

            //dd($event_transactions);
        
        //     $query = DB::connection('mysql2')
        //         ->table('event_transactions')
        //         ->leftJoin('yuwaah_event_masters', 'event_transactions.event_category', '=', 'yuwaah_event_masters.id')
        //         ->leftJoin('yuwaah_event_type', 'yuwaah_event_masters.event_type_id', '=', 'yuwaah_event_type.id')
        //         ->leftJoin('yuwaah_sakhi', 'event_transactions.ys_id', '=', 'yuwaah_sakhi.id')
        //         ->join('learners', 'learners.id', '=', 'event_transactions.learner_id')
        //         ->select(
        //             'event_transactions.*',
        //             'yuwaah_event_masters.event_type as event_master_name',
        //             'yuwaah_event_masters.event_category as event_master_category',
        //             'yuwaah_event_masters.description',
        //             'yuwaah_event_masters.eligibility',
        //             'yuwaah_event_masters.fee_per_completed_transaction',
        //             'yuwaah_event_masters.date_event_created_in_master',
        //             'yuwaah_event_masters.document_1',
        //             'yuwaah_event_masters.document_2',
        //             'yuwaah_event_masters.document_3',
        //             'yuwaah_event_masters.status',
        //             'yuwaah_sakhi.csc_id',
        //             'yuwaah_sakhi.name as field_agent_name',
        //             'yuwaah_sakhi.sakhi_id',
        //             'yuwaah_event_type.name as event_name',
        //             'learners.PROGRAM_STATE',
        //             'learners.PROGRAM_DISTRICT',
        //             'learners.PROGRAM_CODE'
        //         )
        //         ->where('yuwaah_sakhi.csc_id','!=','Sandbox_Testing')
        //         ->orderBy('event_transactions.id', 'desc');
        
            
        // $statusCounts = DB::connection('mysql2')
        //     ->table('event_transactions')
        //     ->join('learners', 'learners.id', '=', 'event_transactions.learner_id')
        //     ->select(
        //         'learners.PROGRAM_CODE',

        //         DB::raw("COUNT(*) as total"),

        //         DB::raw("SUM(CASE WHEN event_transactions.review_status = 'Accepted' THEN 1 ELSE 0 END) as accepted_count"),

        //         DB::raw("SUM(CASE WHEN event_transactions.review_status = 'Rejected' THEN 1 ELSE 0 END) as rejected_count"),

        //         DB::raw("SUM(CASE WHEN event_transactions.review_status = 'Pending' THEN 1 ELSE 0 END) as pending_count"),

        //         DB::raw("SUM(CASE WHEN event_transactions.review_status IS NULL OR event_transactions.review_status = '' THEN 1 ELSE 0 END) as open_count")
        //     )
        //     ->groupBy('learners.PROGRAM_CODE')
        //     ->orderBy('learners.PROGRAM_CODE')
        //     ->get();
            
        //     /* Apply filters only if values exist */
        //     if ($request->filled('submit')) {

        //         $query->where(function ($q) use ($request) {
            
        //             // STATUS
        //             if ($request->filled('status')) {
        //                 if($request->status!=''){
        //                     //dd($request->status);
        //                     $q->Where('event_transactions.review_status', $request->status);
        //                 }
        //             }
            
        //             // EVENT TYPE
        //             if ($request->event_type > 0) {
        //                 //dd($request->event_type);
        //                 $q->Where('yuwaah_event_type.id', $request->event_type);
        //             }
            
        //             // EVENT CATEGORY
        //             if ($request->event_category > 0) {
        //                 $q->orWhere('event_transactions.event_category', $request->event_category);
        //             }
            
        //             // DATE RANGE
        //             if ($request->from_date != '' && $request->to_date != '') {
        //                 $q->orWhereBetween('event_transactions.created_at', [
        //                     $request->from_date,
        //                     $request->to_date
        //                 ]);
        //             }
        //             if ($request->filled('benificiery_name')) {
        //                 $q->Where('event_transactions.beneficiary_name', 'like', "%{$request->benificiery_name}%");
        //             }
            
        //             if ($request->filled('benificiery_mobile')) {
        //                 $q->Where('event_transactions.beneficiary_phone_number', 'like', "%{$request->benificiery_mobile}%");
        //             }

        //             if ($request->filled('sakhi_id')) {
        //                 $q->Where('yuwaah_sakhi.sakhi_id', 'like', "%{$request->sakhi_id}%");
        //             }
            

        //             if ($request->filled('program_code')) {
        //                 $q->Where('learners.PROGRAM_CODE', 'like', "%{$request->program_code}%");
        //             }
            
            
        //             if ($request->filled('search_text')) {
        //                 $search = $request->search_text;
        //                 $q->orWhere(function ($qq) use ($search) {
        //                     $qq->where('event_transactions.beneficiary_name', 'like', "%$search%")
        //                     ->orWhere('event_transactions.beneficiary_phone_number', 'like', "%$search%")
        //                     ->orWhere('event_transactions.event_value', 'like', "%$search%");
        //                 });
        //             }
                    
        //             // You can add more OR conditions here...
        //         });
        //     }
        // /*
        //     if ($request->filled('benificiery_name')) {
        //         $query->where('event_transactions.beneficiary_name', 'like', "%{$request->benificiery_name}%");
        //     }
        
        //     if ($request->filled('benificiery_mobile')) {
        //         $query->where('event_transactions.beneficiary_phone_number', 'like', "%{$request->benificiery_mobile}%");
        //     }
        
        //     if ($request->filled('search_text')) {
        //         $search = $request->search_text;
        //         $query->where(function ($q) use ($search) {
        //             $q->where('event_transactions.beneficiary_name', 'like', "%$search%")
        //             ->orWhere('event_transactions.beneficiary_phone_number', 'like', "%$search%")
        //             ->orWhere('event_transactions.event_value', 'like', "%$search%");
        //         });
        //     }*/
            
            
        
        //     $event_transactions = $query
        //     ->orderBy('event_transactions.review_status')
        //     ->orderBy('event_transactions.id', 'desc')
        //     ->paginate(50);
        //     //dd($event_transactions);
        
        //dd($statusCounts);
        return view('profile.alleventtransaction', 
        compact(
                'event_transactions', 
                'eventTypeArray',
                'eventCategoryArray',
                'programCode',
                'statusCounts'
        ));
    }
    






    public function eventEdit(Request $request,$id){
        if ($request->isMethod('post')) {
            $newValue = $request->get('fee_per_completed_transaction');
        
            DB::connection('mysql2')->table('event_transactions')
                ->where('id', $id) // Replace with your condition
                ->update(['event_value' => $newValue]);
        
            return redirect()->back()->with('success_value', 'Fee per completed transaction updated successfully.');
        }
        $event_transactions = DB::connection('mysql2')
        ->table('event_transactions')
        ->join('yuwaah_event_masters', 'event_transactions.event_category', '=', 'yuwaah_event_masters.id')
        ->join('yuwaah_event_type', 'yuwaah_event_type.id', '=', 'yuwaah_event_masters.event_type_id')
        ->join('yuwaah_sakhi', 'yuwaah_sakhi.id', '=', 'event_transactions.ys_id')
        ->join('learners', 'learners.id', '=', 'event_transactions.learner_id')
        ->select(
            'event_transactions.*',
            'yuwaah_event_type.name as event_name',
            'yuwaah_event_masters.event_type as event_master_name',
            'yuwaah_event_masters.event_category as event_master_category',
            'yuwaah_event_masters.description as description',
            'yuwaah_event_masters.eligibility as eligibility',
            'yuwaah_event_masters.fee_per_completed_transaction as fee_per_completed_transaction',
            'yuwaah_event_masters.date_event_created_in_master as date_event_created_in_master',
            'yuwaah_event_masters.document_1 as document_1',
            'yuwaah_event_masters.document_2 as document_2',
            'yuwaah_event_masters.document_3 as document_3',
            'yuwaah_event_masters.status as status',
            'yuwaah_sakhi.sakhi_id as field_agent_id',
            'yuwaah_sakhi.name as field_agent_name',
            'learners.PROGRAM_STATE',
            'learners.PROGRAM_DISTRICT',
            'learners.PROGRAM_CODE',
            
            'yuwaah_sakhi.contact_number as field_agent_contact'
        )
        ->where('event_transactions.id', '=', $id)
        ->first();
    
        //dd($event_transactions);

        //All Comment For this Event Trasnactions
        $commentList = EventTransactionComment::where('event_transaction_id',$id)->orderBy('id','desc')->get();
        //dd($commentList);
        $industries = config('industries.industry_types');
        $functions = config('jobfunction.functions');
       // dd($functions);
        return view('profile.eventtransactiondetails', [
            'event_transactions' => $event_transactions,
            'event_transaction_id'=>$id,
            'commentList'=>$commentList,
            'industries'=>$industries,
            'functions'=>$functions
        ]);
    }



    public function saveEventTransactionComment(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'agent_id' => 'required|integer',
            'event_transaction_id' => 'required|integer',
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
            'review_status'=>'required|string'
        ]);
        //dd($request->all());
        try {
             // log this
            \Log::info('Attempting to create comment by the reviwer');
            EventTransactionComment::create([
                'comment' => $request->input('comment'),
                'agent_id' => $request->input('agent_id'),
                'event_transaction_id' => $request->input('event_transaction_id'),
                'user_id' => $request->input('user_id'),
                'status' => $request->input('review_status'),
                'comment_type' => $request->input('comment_type'),
                'user_name' => $request->input('user_name')
                
            ]);
            $status = $request->input('review_status');
            //Update the Status into Event Transaction Table
            //dd($request->input('field_type'));
            DB::connection('mysql2')
            ->table('event_transactions')
            ->where('id', $request->input('event_transaction_id'))
            ->update(
                [
                    'review_status' => $status,
                    'field_type' => $request->input('field_type'),
                    'industry_type' => $request->input('industry_type')
                ]
            );

            return redirect()->back()->with('success', 'Comment added successfully!');
        } catch (\Exception $e) {
            \Log::error('Comment creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add comment: ' . $e->getMessage());
        }
    }







/***
 * Get Learner From YuthHub
 */
public function getLearnerFromYuthHub(){
    try {
        // Step 1: Get Token
        $loginResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://uat.youthhub.org/api/login', [
            'username' => 'powerbi',
            'password' => 'iThHSqMItFifduYs'
        ]);

        if (!$loginResponse->ok()) {
            return response()->json(['error' => 'Failed to get token'], 401);
        }

        $token = $loginResponse->json('accessToken'); // This already includes 'Bearer '
       // Step 2: Fetch user data
        $cleanToken = str_replace('Bearer ', '', $token); // remove Bearer
        $userResponse = Http::withToken($cleanToken)->get('https://uat.youthhub.org/api/gpud', [
            'pn' => 1,
            'ps' => 1000,
            'ma' => null
        ]);
        if (!$userResponse->ok()) {
            return response()->json(['error' => 'Failed to get user data'], 500);
        }
        // Debug the structure first
        $users = $userResponse->json('data'); // Or adjust based on actual key
        $count=0;
        // Step 3: Store to database
        foreach ($users as $index => $user) {
            // Extract nested profileInfo data
            $profile = $user['profileInfo'] ?? null;
             // Skip if phone number is missing, null, or empty
             $phoneNumber = $profile['user_phone_number'] ?? null; 
            if (empty($phoneNumber)) {
                    continue;
            }
            
            $validGenders = ['Male', 'Female', 'Other'];
            $gender = ucfirst(strtolower($profile['gender'] ?? 'Male'));
            if (!in_array($gender, $validGenders)) {
                $gender = 'Male'; // fallback if invalid
            }

            $dateOfBirth = $profile['date_of_birth'] ?? null;

            try {
                // Try to parse only if it's not null or 'undefined'
                if (!empty($dateOfBirth) && strtolower($dateOfBirth) !== 'undefined') {
                    $dateOfBirth = Carbon::parse($dateOfBirth)->format('Y-m-d');
                } else {
                    $dateOfBirth = null;
                }
            } catch (\Exception $e) {
                $dateOfBirth = null; // fallback on parse failure
            }
            
            /**
             *      
             */
            //dd($profile);
            

            Learner::updateOrCreate(
                // Lookup criteria — must be unique identifier, like email or external_id
                ['primary_phone_number' => $profile['user_phone_number']],
                [
                    'first_name' => $profile['first_name'] ?? 'NA',
                    'last_name' => $profile['last_name'] ?? 'NA',
                    'email' => $profile['email_id'] ?? time().rand(100000,999999).'@yuthhub.com',
                    'education_level' => $profile['education_level'] ?? null,
                    'experience_years' => $profile['total_work_experiance'] ?? null,
                    'gender' => $gender,
                    'secondary_phone_number' => $profile['secondary_phone_number'] ?? null,
                    'current_job_title' => $profile['current_job_title'] ?? null,
                    'date_of_birth' => $dateOfBirth,
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
                    'DISTRICT_CITY'=>$profile['loc_state'],
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
                    'preferred_job_domain4'=>$profile['run_a_business']
                ]
            );
            //echo ($index + 1) . " Learner Updated\n";
            //echo "<br/>";
        }
        return response()->json(['message' => 'User data fetched and stored successfully']);
    } catch (\Exception $e) {
        Log::error('API error: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong'], 500);
    }


    /***** New Loop */

}





public function allLearner(Request $request){
   
    $learner = Learner::paginate(1000);
    return view('profile.alllearner', [
        'user' => $learner,
    ]);
}






public function getCategories(Request $request)
{
    $typeId = $request->event_type_id;

    $query = DB::connection('mysql2')
        ->table('yuwaah_event_masters');

    if($typeId !== 'Open' && !empty($typeId)) {
        $query->where('event_type_id', $typeId);
    }

    return response()->json($query->get());
}



public function exportEventTransactions(Request $request)
{
    return Excel::download(
        new EventTransactionExport($request),
        'event_transactions.xlsx'
    );
  
}




}
