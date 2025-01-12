<?php

use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('chat');
});

Route::post('/chat', [ChatController::class, 'chat']);
