<?php

namespace App\Services;

use App\Http\Requests\StoreMembersChatRequest;
use App\Models\Chat;
use Illuminate\Http\Request;

class MembersChatService
{

    /**
     * Undocumented function
     *
     * verificar se o chat existe na tabela chats ✅
     * verificar se o chat não é privado, (não pode adicionar usuários em um chat privado) ✅
     * se o usuário adicionado havia saido do chat, o campo leave deve ficar = false ✅
     * 
     * 
     */
    public static function store(Request $request)
    {        

        if (!$chat = Chat::find($request->id)) {
            return response()->json([
                'error' => 'Chat not found',
            ]);
        }

        if ($chat->users->count() <= 2 && $chat->name == Null) {
            return response()->json([
                'error' => 'Não pode adicionar novos integrantes para um chat privado'
            ]);
        }

        StoreMembersChatRequest::validate($request);

        $chat->addUsers($request->ids);

        return response()->json([
            'success' => true,
        ]);


    }
}
