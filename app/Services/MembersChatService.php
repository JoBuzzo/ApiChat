<?php

namespace App\Services;

use App\Http\Requests\StoreMembersChatRequest;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MembersChatService extends Service
{

    /**
     * Requisitos
     *
     * verificar se o chat existe na tabela chats ✅
     * verificar se o chat não é privado, (não pode adicionar usuários em um chat privado) ✅
     * se o usuário adicionado havia saido do chat, o campo leave deve ficar = false ✅
     * 
     * 
     */


    /**
     * Adicionar novos membros á um chat
     *
     * @param  Request  $request
     * @return \App\Traits\HttpResponses
     * 
     * @request $request->id (id do chat)
     * @request $request->ids[] (ids dos usuários)
     */
    public static function store(Request $request)
    {        

        if (!$chat = Chat::find($request->id)) {
            return self::error('Not Found', Response::HTTP_NOT_FOUND, [
                'error' => 'Chat not found',
            ]);
          
        }

        if ($chat->users->count() <= 2 && $chat->name == Null) {
            return self::error('Unauthorized',Response::HTTP_UNAUTHORIZED, [
                'error' => 'Cannot add new members to a private chat',
            ]);
        }

        StoreMembersChatRequest::validate($request);

        $chat->addUsers($request->ids);

        return self::response('New members added successfully', Response::HTTP_OK);
    }
}
