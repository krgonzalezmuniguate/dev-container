<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['menu_id', 'name', 'url'];
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
