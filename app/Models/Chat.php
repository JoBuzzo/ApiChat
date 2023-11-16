<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo'
    ];

    public function getPhotoAttribute($value){
        if($value){
            return $value = asset("storage/chats/".$value);
        }
    }
    /**
     * The users that belong to the Chat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user', 'chat_id', 'user_id')
            ->withPivot(['joined_at', 'deleted_at']);
    }
}
