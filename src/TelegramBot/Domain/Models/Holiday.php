<?php

namespace App\TelegramBot\Domain\Models;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Holiday
 *
 * @property int $chat_id
 * @property Carbon|null $date
 * @property string|null $description
 * @property boolean|null $repeat
 *
 * @method static Builder|Holiday active()
 */
class Holiday extends Model
{
    protected $fillable = [
        'chat_id',
        'date',
        'description',
        'repeat',
    ];

    /**
     * Форматы дат
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date:d.m.Y',
        'created_at' => 'date:d.m.Y H:i:s',
        'updated_at' => 'date:d.m.Y H:i:s',
    ];

    /**
     * @return HasOne
     */
    /*public function chat(): HasOne
    {
        return $this->hasOne(TelegraphChat::class, 'id', 'chat_id');
    }*/

    public function scopeActive($query)
    {
        return $query->whereNotNull('date')->whereNotNull('description');
    }
}
