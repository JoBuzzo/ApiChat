<?php

namespace App\Services;

use App\Http\Requests\MessageStoreRequest;
use App\Models\ChatUser;
use App\Models\Message;
use Illuminate\Http\Request;
class MessageService
{
    public static function store(Request $request)
    {
        MessageStoreRequest::validate($request);

        $chatUser = ChatUser::withTrashed()->where('user_id', $request->user_id)
            ->where('chat_id', $request->chat_id)
            ->first();


        if ($chatUser && !$chatUser->leave) {

            $media = $request->media ? FileHandlerService::store($request->media, 'images') : null;

            $message = Message::create([
                'user_id' => $request->user_id,
                'chat_id' => $request->chat_id,
                'parent_id' => $request->parent_id,
                'content' => $request->content,
                'media' => $media,
            ]);
            return response()->json(['message' => $message]);
        }
        return response()->json(['error' => 'chat não encontrado']); 
    }
}
