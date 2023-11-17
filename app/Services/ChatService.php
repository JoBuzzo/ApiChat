<?php

namespace App\Services;

use App\Http\Requests\ChatStoreRequest;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatService
{

    public static function index($id)
    {
        $chats = Chat::with(['users' => function ($query) use ($id) {
            $query->where('user_id', '!=', $id)->where('leave', false);
        }])->whereHas('users', function ($query) use ($id) {
            $query->where('user_id', $id)->where('leave', false);
        })->get();
        

        // Caso o chat ter apenas o outro usuário nele,
        // o nome do chat terá o nome do outro usuário (amigo)
        $chats->each(function ($chat) {
            if ($chat->users->count() == 1 && $chat->name == null) {
                $friend = $chat->users->first();
                $chat->name = $friend->name;
                $chat->photo = $friend->getRawOriginal('avatar');
            }
        });

        return response()->json(['chats' => $chats]);
    }

    public static function show($id, $user_id)
    {
        if (!$chat = Chat::with(['users'])->find($id)) {
            return response()->json(['Error' => 'chat não encontrado']);
        }

        if ($chat->users->contains($user_id)) {
            if (!$chat->name && $chat->users->count() <= 2) {
                foreach ($chat->users as $user) {
                    if ($user->id != $user_id) {
                        $chat->name = $user->name;
                        $chat->photo = $user->getRawOriginal('avatar');
                        break;
                    }
                }
            }
        } else {
            return response()->json(['Error' => 'Você não tem permição para acessar esse chat']);
        }

        $chatUser = ChatUser::withTrashed()->where('user_id', $user_id)->where('chat_id', $id)->first();

        if ($chatUser->leave) {
            return response()->json([
                'error' => 'Você não pode acessar um chat que você saiu'
            ]);
        }

        $messages = Message::where('messages.chat_id', $chat->id)
            ->leftJoin('users', 'messages.user_id', '=', 'users.id')
            ->leftJoin('messages as parent', 'messages.parent_id', '=', 'parent.id')
            ->leftJoin('users as parent_user', 'parent.user_id', '=', 'parent_user.id')
            ->select(
                'users.name as user_name',
                'messages.user_id',
                'messages.id as message_id',
                'messages.content',
                'messages.media',
                'messages.sent_at',
                'messages.parent_id as parent_message_id',
                'parent_user.id as parent_user_id',
                'parent_user.name as parent_user_name',
                'parent.content as parent_content',
            );


        if ($chatUser->deleted_at == null) {

            return response()->json([
                'chat' => $chat,
                'messages' => $messages
                    ->orderBy('messages.sent_at', 'asc')
                    ->get()
            ]);
        }


        return response()->json([
            'chat' => $chat,
            'messages' => $messages
                ->where('messages.sent_at', '>', $chatUser->deleted_at)
                ->orderBy('messages.sent_at', 'asc')
                ->get()
        ]);
    }

    public static function store(Request $request)
    {
        ChatStoreRequest::validate($request);

        $photo = $request->photo ? FileHandlerService::store($request->photo, 'images') : null;

        $chat = Chat::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photo
        ]);

        $chat->users()->attach($request->ids);

        return response()->json([
            'chat' => $chat,
            'users' => $chat->users
        ]);
    }

    public static function destroy($id, $user_id)
    {
        if ($chatUser = ChatUser::withTrashed()->where('chat_id', '=', $id)->where('user_id', $user_id)->first()) {

            $chatUser->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
