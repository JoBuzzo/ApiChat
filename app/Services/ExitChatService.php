<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Http\Response;

class ExitChatService extends Service
{

    /**
     * Sair de um chat
     *
     * @param  Chat  $id
     * @param  User  $user_id
     * @return \App\Traits\HttpResponses
     */
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

            return self::noContent();
        }
        return self::error('Unauthorized',Response::HTTP_UNAUTHORIZED, [
            'error' => 'You cannot leave a private chat.'
        ]);
    }
}
