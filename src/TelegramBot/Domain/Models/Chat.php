<?php

namespace App\TelegramBot\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'telegram_id',
        'username',
        'waiting_add_answer',
        'waiting_delete_answer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'telegram_id',
        'username',
    ];

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class, 'chat_id', 'id');
    }
}
