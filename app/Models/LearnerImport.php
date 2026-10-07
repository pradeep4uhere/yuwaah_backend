<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LearnerImport extends Model
{
    protected $table = 'learner_imports';

    protected $fillable = [
        'file_name',
        'program_code',
        'uploaded_by',
        'total_records',
        'success_count',
        'duplicate_count',
        'failed_count',
        'status',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];


    public function uploader()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'uploaded_by'
        );
    }

    public function failures()
    {
        return $this->hasMany(
            LearnerImportFailure::class,
            'learner_import_id'
        );
    }
}