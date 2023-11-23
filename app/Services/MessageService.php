<?php

namespace App\Services;

use App\Http\Requests\MessageStoreRequest;
use App\Models\ChatUser;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageService extends Service
{

    /**
     * Enviar mensagens em um chat
     *
     * @param  Request  $request
     * @return \App\Traits\HttpResponses
     * 
     * @request $request->user_id
     * @request $request->chat_id
     * @request $request->parent_id (Id da mensagem que foi respondida)
     * @request $request->media
     * @request $request->content
     */
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
            return self::response('Message sent', Response::HTTP_OK, $message);
        }
        return self::error('Chat not found', Response::HTTP_NOT_FOUND, [
            "error" => "Chat $request->chat_id not found"
        ]);
    }
}
