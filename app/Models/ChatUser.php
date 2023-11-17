<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ChatUser extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $primary = ['chat_id', 'user_id'];
    protected $table = 'chat_user';
    protected $fillable = [
        'user_id',
        'chat_id',
        'leave',
        'joined_at',
    ];

    public function delete(){

        return DB::update('update '.$this->table.' set deleted_at = ? where user_id = ? and chat_id = ?', [now(), $this->user_id, $this->chat_id]);
    }

    protected $casts = [
        'joined_at' => 'datetime',
        'leave' => 'boolean'
    ];
    
    /**
     * Get the user that owns the ChatUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the chat that owns the ChatUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    /**
     * Get all of the messages for the ChatUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id', 'chat_id');
    }
}
