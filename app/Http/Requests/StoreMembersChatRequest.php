<?php

namespace App\Http\Requests;

use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Http\Request;

class StoreMembersChatRequest
{

    public static function validate(Request $request)
    {
        $request->validate([
            'id' => 'exists:chats,id',
            'ids' => [
                'array',
                function ($attribute, $value, $fail) use ($request) {

                    if (count($value) < 1) {

                        $fail('Você deve passar ao menos 1 id');
                    }

                    if (count(array_unique($value)) < count($value)) {
                        $fail('Os valores em ' . $attribute . ' não podem ser repetidos.');
                    }

                    foreach ($value as $userId) {
                        if(!is_null($userId)){
                            if (!User::where('id', $userId)->exists()) {
                                $fail('O usuário com o ID ' . $userId . ' não existe.');
                            }
                        }else{
                            $fail('O usuário com o ID null não existe.');
                        }
                    }

                    $chatId = $request->id;
                    foreach ($value as $userId) {
                        $conditional = ChatUser::withTrashed()->where('chat_id', $chatId)->where('user_id', $userId)->where('leave', 0)->exists();
                        if ($conditional) {
                            $fail('O usuário com o ID ' . $userId . ' já está associado a este chat.');
                        }
                    }
                },

            ]
        ]);
    }
}
