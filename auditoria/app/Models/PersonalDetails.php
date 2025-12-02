<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PersonalDetails extends Model
{
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'phone_number',
        'address',
        'nit',
        'dpi',
        'gender',
        'city',
        'department',
        'country',
        'email_personal',
        'ethnic_group',
        'code_job',
        'profile_picture_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
