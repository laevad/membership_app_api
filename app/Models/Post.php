<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Post extends Model
{
    // use HasFactory;
    public function post()
    {
        return $this->hasOne('App\Models\User');
    }
}
