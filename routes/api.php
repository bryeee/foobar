<?php

use App\Http\Controllers\GetGitHubUser;
use App\Http\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/health-check', function () {
    return ['time' => Carbon::now()->format('F d, Y h:i:s A')];
 });

Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');
});

// single action controller
Route::post('/github/users', GetGitHubUser::class)->middleware('auth:api');

// bonus challenge
Route::get('/hamming-distance', function (Request $request) {
    if ($request->has('foo') && $request->has('bar')) {
        $foo = $request->foo;
        $bar = $request->bar;

        $x = (int)$foo ^ (int)$bar;

        $setBits = 0;
    
        while ($x > 0) {
            $setBits += $x & 1;
            $x >>= 1;
        }
    
        return $setBits;
    }
    
    return 'please provide number for foo and bar';
});
