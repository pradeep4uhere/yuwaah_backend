<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerCourse extends Model
{
    use HasFactory;

    protected $connection = 'mysql2'; // This forces it to use the secondary DB

    protected $table = 'learner_courses';

    protected $fillable = [
        'phone_number',
        'course_name',
        'completed_1_course',
        'load_date'
    ];

    public $timestamps = false;


    public function learner()
    {
        return $this->belongsTo(Learner::class, 'phone_number', 'normalized_mobile');
    }
}
