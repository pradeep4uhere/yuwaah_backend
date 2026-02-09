<?php

namespace App\Models;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class ApiSyncLog extends Model
{
    
    
    protected $fillable = [
        'api_name',
        'run_date',
        'run_date',
        'total_fetched',
        'total_inserted',
        'total_updated',
        'status',
        'error_message',
        'started_at',
        'completed_at',
    ];
    
}
