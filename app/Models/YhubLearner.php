<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YhubLearner extends Model
{
    use HasFactory;

    // Use different DB connection
    protected $connection = 'mysql2';

    protected $table = 'yhub_learners';


    protected $fillable = [
        'country',
        'user_id',
        'first_name',
        'last_name',
        'email_address',
        'gender',
        'role',
        'grade',
        'state',
        'district',
        'school',
        'course_name',
        'completion_status',
        'course_end_datetime',
        'completion_percent',
        'load_date',
    ];

    protected $casts = [
        'course_end_datetime' => 'datetime',
        'load_date' => 'date',
        'completion_status' => 'boolean',
    ];
}
