<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTransactionComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'agent_id',
        'event_transaction_id',
        'user_id',
        'user_name',
        'comment_type'
    ];
}
