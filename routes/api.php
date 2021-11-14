<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Команды
// Актуализировать данные
//  - по конкретной паре
//  - по всем парам

// Состояние команд
//  - что сейчас выполняется и сколько осталось
//  - сколько выполнялись коман

// Статистика
//  - команды: сколько они выполнялись и выполняются
//  - историй операций: что покупалось и продавалось на каких условиях
//  -
