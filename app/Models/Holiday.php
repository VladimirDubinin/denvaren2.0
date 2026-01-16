<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'chat_id',
        'date',
        'description',
        'repeat',
    ];
}
