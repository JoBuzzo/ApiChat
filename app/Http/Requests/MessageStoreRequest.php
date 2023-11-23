<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class MessageStoreRequest
{
    /**
     * Undocumented function
     *
     * @param  Request  $request
     * 
     * @return void
     * 
     * @request $request->user_id
     * @request $request->chat_id
     * @request $request->parent_id (Id da mensagem que foi respondida)
     * @request $request->media
     * @request $request->content
     */
    public static function validate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:chat_user,user_id',
            'chat_id' => 'required|exists:chat_user,chat_id',
            'parent_id' => 'nullable|exists:messages,id',
            'media' => 'nullable|file',
            'content' =>  function ($attribute, $value, $fail) use ($request) {
                if (!$request->media && !$value) {
                    return $fail('O campo ' . $attribute . ' deve ser preenchido.');
                }
            },
        ]);
    }
}
