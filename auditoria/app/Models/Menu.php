<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name'];
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
