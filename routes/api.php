<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    dd(213);
    return $request->user();
});*/

Route::post('/register', 'Auth\AuthController@register');
Route::post('/login', 'Auth\AuthController@login');

Route::group(['middleware'=>['auth:api']],function() {

    Route::get('/robot', function (Request $request) {
        $info = $request->input('info');
        $key = config('services.robot.key');
        $url = config('services.robot.api');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'json' => compact("info", "key")
        ]);
        $contents = json_decode($response->getBody()->getContents(),true);

        \Illuminate\Support\Facades\Log::info(print_r($contents,true));
        return response()->json(['data' => $contents]);
    });

    Route::get('/notice', function () {
        return response()->json([
            'title' => '欢迎您的到来，你可以自由发言',
            'href' => 'JavaScript:;',
        ]);
    });

    Route::get('/message/history', 'Api\MessageController@history');

});
