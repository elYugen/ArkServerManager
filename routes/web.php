<?php

use App\Http\Controllers\ArkControllerTest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ShopController;
use App\Services\RconService;
use Illuminate\Support\Facades\Route;

Route::get('', [AuthController::class, 'index'])->name('login');
Route::post('/auth', [AuthController::class, 'authenticate'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/player', [PlayerController::class, 'index'])->name('player.index');
Route::get('/player/show', [PlayerController::class, 'show'])->name('player.show');

Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
Route::get('/configuration/show', [ConfigurationController::class, 'show'])->name('configuration.show');
Route::post('/configuration/create', [ConfigurationController::class, 'store'])->name('configuration.store');
Route::post('/configuration/edit', [ConfigurationController::class, 'update'])->name('configuration.update');
Route::delete('/configuration/delete', [ConfigurationController::class, 'destroy'])->name('configuration.destroy');
Route::get('/configuration/shop', [ConfigurationController::class, 'loadShopConfig'])->name('configuration.shop');
Route::post('/configuration/shop/update', [ConfigurationController::class, 'updateShopConfig'])->name('configuration.shop.update');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::post('/shop/save', [ShopController::class, 'save'])->name('shop.save');
Route::get('/shop/kits', [ShopController::class, 'getKits'])->name('shop.kits');
Route::get('/shop/items', [ShopController::class, 'getItems'])->name('shop.items');
Route::post('/shop/kits', [ShopController::class, 'addKit'])->name('shop.kits.add');
Route::post('/shop/items', [ShopController::class, 'addItem'])->name('shop.items.add');
Route::delete('/shop/kits/{kitName}', [ShopController::class, 'deleteKit'])->name('shop.kits.delete');
Route::delete('/shop/items/{itemId}', [ShopController::class, 'deleteItem'])->name('shop.items.delete');
Route::put('/shop/kits/{kitName}', [ShopController::class, 'updateKit'])->name('shop.kits.update');
Route::put('/shop/items/{itemId}', [ShopController::class, 'updateItem'])->name('shop.items.update');


Route::get('/players', [ArkControllerTest::class, 'players']);



Route::get('/rcon-test', function () {
    $client = new \Thedudeguy\Rcon(
        env('ARK_RCON_HOST'),
        env('ARK_RCON_PORT'),
        env('ARK_RCON_PASSWORD'),
        3
    );

    if (!$client->connect()) {
        return ['success' => false, 'error' => 'RCON connection failed'];
    }

    $commands = [
        'listplayers',
        'arkshop.reload',
        'arkshop.players'
    ];

    $results = [];
    foreach ($commands as $cmd) {
        $results[$cmd] = $client->sendCommand($cmd);
    }

    return $results;
});

Route::get('/test', function () {
    $client = new \Thedudeguy\Rcon(
        env('ARK_RCON_HOST'),
        env('ARK_RCON_PORT'),
        env('ARK_RCON_PASSWORD'),
        3
    );

    if (!$client->connect()) {
        return ['success' => false, 'error' => 'rcon connection failed'];
    }

    $response = $client->sendCommand('serverinfo');

    return ['success' => true, 'response' => $response];
});

Route::post('/send-rcon', function (\Illuminate\Http\Request $request, RconService $rcon) {
    $command = $request->input('command');
    if (!$command) {
        return response()->json(['success' => false, 'message' => 'Aucune commande fournie']);
    }

    $response = $rcon->send($command);

    return response()->json([
        'success' => true,
        'command' => $command,
        'result' => $response
    ]);
});