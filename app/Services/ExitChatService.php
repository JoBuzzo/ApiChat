<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatUser;

class ExitChatService
{

    public static function delete($id, $user_id)
    {
        $chat = Chat::with('users')->find($id);

        if ($chat->users->count() >= 2 &&  $chat->name != null) {
            $chatUser = ChatUser::withTrashed()
                ->where('chat_id', $id)
                ->where('user_id', $user_id)
                ->first();

            if ($chatUser->leave) {
                return response()->json([
                    'error' => 'Você ja saiu do chat'
                ]);
            }

            $chatUser->exit();

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Você não pode sair de um chat privado.']);
    }
}
