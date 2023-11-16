<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use App\Services\MessageService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chats($id)
    {
        return ChatService::index($id);
    }

    public function createChat(Request $request)
    {
        return ChatService::store($request);
    }

    public function show($id, $user_id)
    {
        return ChatService::show($id, $user_id);
    }

    public function sendMessage(Request $request)
    {
        return MessageService::store($request);
    }


    //ao usuário sair do grupo ou deletar a conversa, ficará salvo quando ele deletou.
    public function destroy($id, $user_id)
    {
        return ChatService::destroy($id, $user_id);
    }
}
