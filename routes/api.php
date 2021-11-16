<?php

use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/telegram')->group(function () {
    Route::post('/webhook', [TelegramWebhookController::class, 'index']);
    Route::get('/set', [TelegramWebhookController::class, 'set']);
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

// trades - Последние 1000 (или больше) сделок. Можно определить направление и медианную стоимость. Получить график,
// чтобы понять, куда движется цена (за которую покупают)

// ask / bid - узнать текущие предложения по продаже и покупке
