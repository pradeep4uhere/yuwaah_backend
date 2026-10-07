<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnerImportFailure extends Model
{
    protected $table = 'learner_import_failures';

    protected $fillable = [
        'learner_import_id',
        'row_number',
        'candidate_id',
        'email',
        'mobile_number',
        'reason',
        'row_data',
    ];

    protected $casts = [
        'row_data' => 'array',
    ];

    public function import()
    {
        return $this->belongsTo(
            LearnerImport::class,
            'learner_import_id'
        );
    }
}