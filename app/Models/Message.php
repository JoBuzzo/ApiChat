<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;


    protected $fillable = [
        'chat_id',
        'user_id',
        'parent_id',
        'content',
        'media',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function getMediaAttribute($value){
        if($value){
            return $value = asset("storage/messages/".$value);
        }
    }


}
