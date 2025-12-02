<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestSupply extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'status',
        'path_file',
        'manager_id',
        'date_authorized',
        'reason_rejection'
    ];
}
