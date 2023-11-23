<?php

namespace App\Http\Requests;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatStoreRequest
{

    /**
     * ChatStoreRequest::class
     *
     * @param  Request $request
     * 
     * @return void
     * 
     * @request $request->name
     * @request $request->ids[]
     * @request $request->photo
     * @request $request->description
     */
    public static function validate(Request $request)
    {
        $request->validate([
            'name' => [
                'required' => function ($attribute, $value, $fail) use ($request) {
                    if (count($request->ids) >= 2 && !$request->name) {
                        if (count($request->ids) > 2) {
                            $fail($attribute . ' deve ser preenchido.');
                        }else
                        if ($request->photo) {
                            $fail("O campo photo não pode ser aceito em um chat privado, remova a foto ou crie um grupo.");
                        }
                    }
                },
            ],
            'ids' => [
                'required',
                'array',
                'min:2',
                function ($attribute, $value, $fail) {
                    if (count(array_unique($value)) < count($value)) {
                        $fail('Os valores em ' . $attribute . ' não podem ser repetidos.');
                    }
                },
                function ($attribute, $value, $fail) use ($request) {

                    if (count($value) == 2 && !$request->name) {
                        $userIds = $value;
                        sort($userIds);

                        $chats = Chat::whereHas('users', function ($query) use ($userIds) {
                            $query->whereIn('user_id', $userIds);
                        })
                            ->with('users')
                            ->get();

                        $error = false;
                        $count = 0;
                        foreach ($chats as $chat) {
                            if (!$chat->name && $chat->users->count() == 2) {
                                foreach ($chat->users as $user) {
                                    if (in_array($user->id, $userIds)) {
                                        $count++;
                                        if ($count == 2) {
                                            $error = true;
                                        }
                                    }
                                }
                                $count = 0;
                            }
                        }

                        if ($error) {
                            $fail('Já existe uma conversa privada entre esses usuários.');
                        }
                    }
                },
                'exists:users,id',
            ],
            'photo' => 'nullable|image',
            'description' => 'nullable|min:1|max:80',
        ]);
    }
}
