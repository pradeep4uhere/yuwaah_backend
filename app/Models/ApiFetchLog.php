<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiFetchLog extends Model
{
    protected $table = 'api_fetch_log';

    protected $fillable = [
        'page_number',
        'records_fetched',
        'total_records',
        'records_remaining',
        'fetched_at',
    ];

    public $timestamps = false;
}
