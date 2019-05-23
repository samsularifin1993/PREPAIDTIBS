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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// JWT API
Route::group(['prefix' => 'auth', 'middleware' => ['api']], function ($router) {
    Route::post('login', [
        'as'    => 'api.login',
        'uses'   => 'API\AuthController@login'
    ]);

    Route::post('register', [
        'as'    => 'api.register',
        'uses'   => 'API\AuthController@register'
    ]);
});

Route::group(['prefix' => 'auth', 'middleware' => ['api', 'jwt']], function ($router) {
    Route::get('logout', [
        'as'    => 'api.logout',
        'uses'   => 'API\AuthController@logout'
    ]);

    Route::get('me', [
        'as'    => 'api.me',
        'uses'   => 'API\AuthController@me'
    ]);

    Route::get('refresh', [
        'as'    => 'api.refresh',
        'uses'   => 'API\AuthController@refresh'
    ]);
});

Route::group(['middleware' => ['api', 'jwt']], function ($router) {
    Route::get('logout', [
        'as'    => 'api.logout',
        'uses'   => 'API\AuthController@logout'
    ]);

    Route::get('me', [
        'as'    => 'api.me',
        'uses'   => 'API\AuthController@me'
    ]);

    Route::get('refresh', [
        'as'    => 'api.refresh',
        'uses'   => 'API\AuthController@refresh'
    ]);


    // Channel

    // Route::get('channel', [
    //     'as'    => 'api.channel.index',
    //     'uses'   => 'API\ChannelController@index'
    // ]);

    // Route::get('channel/{id}', [
    //     'as'    => 'api.channel.show',
    //     'uses'   => 'API\ChannelController@show'
    // ]);

    // Route::post('channel', [
    //     'as'    => 'api.channel.store',
    //     'uses'   => 'API\ChannelController@store'
    // ]);
    
    // Route::put('channel/{id}', [
    //     'as'    => 'api.channel.update',
    //     'uses'   => 'API\ChannelController@update'
    // ]);
    
    // Route::delete('channel/{id}', [
    //     'as'    => 'api.channel.destroy',
    //     'uses'   => 'API\ChannelController@destroy'
    // ]);

    Route::resource('channel', 'API\ChannelController');
    Route::resource('payment', 'API\PaymentController');
    Route::resource('organization', 'API\OrganizationController');
    Route::resource('product_family', 'API\ProductFamilyController');
    Route::resource('product', 'API\ProductController');
    
    Route::get('trx_success', [
        'as'    => 'api.transaction.success',
        'uses'   => 'API\TransactionController@trxSuccess'
    ]);

    Route::get('trx_rejected', [
        'as'    => 'api.transaction.rejected',
        'uses'   => 'API\TransactionController@trxRejected'
    ]);

    Route::post('reprocess_rejected', [
        'as'    => 'api.transaction.reprocess_rejected',
        'uses'   => 'API\TransactionController@retryRejected'
    ]);

    Route::post('reprocess_rejected_bulk', [
        'as'    => 'api.transaction.reprocess_rejected_bulk',
        'uses'   => 'API\TransactionController@retryBulkRejected'
    ]);

    Route::post('log', [
        'as'    => 'api.log.index',
        'uses'   => 'API\LogController@getAll'
    ]);

    Route::post('report_all', [
        'as'    => 'api.report.all',
        'uses'   => 'API\ReportController@sumAll'
    ]);

    Route::post('report_by_org', [
        'as'    => 'api.report.report_by_org',
        'uses'   => 'API\ReportController@sumByOrg'
    ]);

    Route::post('report_by_product', [
        'as'    => 'api.report.report_by_product',
        'uses'   => 'API\ReportController@sumByProduct'
    ]);
});
