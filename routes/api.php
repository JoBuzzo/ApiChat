<?php

use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

route::post('criar/chat', [ChatController::class, 'createChat'])->name('store.chat.group');

route::get('chats/{id}', [ChatController::class, 'chats'])->name('index.chat.group');
route::get('chat/{id}/{user_id}', [ChatController::class, 'show'])->name('show.chat.group');

route::post('enviar/mensagem', [ChatController::class, 'sendMessage'])->name('sendMessage.chat.group');

route::delete('excluir/chat/{id}/{user_id}', [ChatController::class, 'destroy'])->name('destroy.chat.group');

route::delete('sair/chat/{id}/{user_id}', [ChatController::class, 'exit'])->name('exit.chat.group');

route::post('adicionar/integrante',  [ChatController::class, 'addMembers'])->name('add.member.chat.group');