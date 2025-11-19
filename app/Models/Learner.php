<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Learner extends Model
{
    use HasFactory;

    protected $connection = 'mysql2'; // This forces it to use the secondary DB


    protected $fillable = [
        'first_name',
        'last_name',
        'status',
        'date_of_birth',
        'gender',
        'primary_phone_number',
        'email',
        'institution',
        'education_level',
        'digital_proficiency',
        'english_knowledge',
        'interested_in_opportunities',
        'opportunity_types',
        'job_mobility',
        'job_kind',
        'job_qualifications',
        'job_timing',
        'experience_years',
        'work_hours_per_day',
        'work_kind',
        'earn_qualifications',
        'business_status',
        'business_description',
        'MONTHLY_FAMILY_INCOME_RANGE',
        'USER_EMAIL',
        'DISTRICT_CITY',
        'STATE',
        'PIN_CODE',
        'PROGRAM_CODE',
        'PROGRAM_STATE',
        'PROGRAM_DISTRICT',
        'UNIT_INSTITUTE',
        'SOCIAL_CATEGORY',
        'RELIGION',
        'USER_MARIAL_STATUS',
        'DIFFRENTLY_ABLED',
        'IDENTITY_DOCUMENTS',
        'REASON_FOR_LEARNING_NEW_SKILLS',
        'EARN_AT_MY_OWN_TIME',
        'RELOCATE_FOR_JOB',
        'WHEN_CAN_USER_START',
        'USER_NEED_HELP_WITH'
    ];

    protected $casts = [
        'opportunity_types' => 'array',
        'job_qualifications' => 'array',
        'earn_qualifications' => 'array',
        'interested_in_opportunities' => 'boolean',
        'date_of_birth' => 'date',
    ];


    public function OpportunitiesAssigned()
    {
        return $this->hasMany(OpportunitiesAssigned::class, 'learner_id');
    }


    public function EventAssigned()
    {
        return $this->hasMany(EventAssigned::class, 'learner_id');
    }


     /**
     * All Pater Count For Admin Panel
     */
    public static function getAllCount(){
        return self::count();
    }

    public static function getLearnerAgeGroup(){
        $ageGroups = DB::table('learners')
        ->select(DB::raw('
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 14 AND 20 THEN 1 ELSE 0 END) as age_group_14_20,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 21 AND 30 THEN 1 ELSE 0 END) as age_group_21_30,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 31 AND 40 THEN 1 ELSE 0 END) as age_group_31_40,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 40 THEN 1 ELSE 0 END) as age_group_40_plus
        '))
        ->first();
        
        // Check if there are no records in the learners table
        if (!$ageGroups) {
            return [
                "14-20" => 0,
                "21-30" => 0,
                "31-40" => 0,
                "40+" => 0
            ];
        }
        return [
                "14-20" => $ageGroups->age_group_14_20,
                "21-30" => $ageGroups->age_group_21_30,
                "31-40"=>  $ageGroups->age_group_31_40,
                "40+" =>   $ageGroups->age_group_40_plus
        ];
    }




    public static function getGenderCount()
    {
        // Get the counts for each gender group
        $genderCounts = DB::table('learners')
            ->select(DB::raw('
                SUM(CASE WHEN gender = "Male" THEN 1 ELSE 0 END) as male_count,
                SUM(CASE WHEN gender = "Female" THEN 1 ELSE 0 END) as female_count,
                SUM(CASE WHEN gender = "Other" THEN 1 ELSE 0 END) as other_count
            '))
            ->first();
    
        return [
            "Male" => $genderCounts->male_count,
            "Female" => $genderCounts->female_count,
            "Other" => $genderCounts->other_count
        ];
    }
    

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
    
    

}
