<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
//Route::redirect('/', 'panel');

Route::get('pivot-data', [
    'as'    => 'pivot.data',
    'uses'   => 'DashboardController@pivotData'
]);

Route::post('pivot-data', [
    'as'    => 'pivot.data.get',
    'uses'   => 'DashboardController@productAll2'
]);

//Backend Before Auth
Route::group(['prefix' => 'admin', 'middleware' => ['guest', 'prevent.back.history']], function () { 
    Route::get('/', function () {
        return view('welcome');
    });

    // Route::get('/home', function () {
    //     return view('backend.home');
    // });
    
    Route::get('login', [
        'as'    => 'admin.login',
        'uses'   => 'Backend\Auth\LoginController@showLoginForm'
    ]);

    Route::post('auth', [
        'as'    => 'admin.auth',
        'uses'   => 'Backend\Auth\LoginController@login'
    ]);
    
    Route::get('register', [
        'as'    => 'admin.register',
        'uses'   => 'Backend\Auth\RegisterController@showRegistrationForm'
    ]);

    Route::post('register', [
        'as'    => 'admin.saveRegister',
        'uses'   => 'Backend\Auth\RegisterController@register'
    ]);
});

//Backend After Auth
Route::group(['prefix' => 'admin', 'middleware' => ['prevent.back.history', 'backend']], function () {    
    Route::get('panel', [
        'as'    => 'admin.panel',
        'uses'   => 'Backend\AdminController@panel'
    ]);

    Route::post('logout', [
        'as'    => 'admin.logout',
        'uses'   => 'Backend\Auth\LoginController@logout'
    ]);

    Route::get('password/change_password', [
        'as'    => 'admin.password.showChangeForm',
        'uses'   => 'Backend\Auth\ChangePasswordController@showChangeForm'
    ]);

    Route::post('password/change_password', [
        'as'    => 'admin.password.change',
        'uses'   => 'Backend\Auth\ChangePasswordController@change'
    ]);

    Route::get('index', [
        'as'    => 'admin.index',
        'uses'   => 'Backend\AdminController@index'
    ]);


    //Test

    Route::get('test', [
        'as'    => 'test.index',
        'uses'   => 'Backend\TestController@index'
    ]);

    Route::get('getAll', [
        'as'    => 'test.getAll',
        'uses'   => 'Backend\TestController@getAll'
    ]);

    Route::get('item', [
        'as'    => 'test.item',
        'uses'   => 'Backend\TestController@item'
    ]);

    Route::post('testStore', [
        'as'    => 'test.store',
        'uses'   => 'Backend\TestController@store'
    ]);

    Route::post('testUpdate', [
        'as'    => 'test.update',
        'uses'   => 'Backend\TestController@update'
    ]);

    Route::post('testEdit', [
        'as'    => 'test.edit',
        'uses'   => 'Backend\TestController@edit'
    ]);

    Route::post('testDelete', [
        'as'    => 'test.delete',
        'uses'   => 'Backend\TestController@destroy'
    ]);

    //Register User

    // Route::get('register', [
    //     'as'    => 'user.register',
    //     'uses'   => 'Frontend\Auth\RegisterController@showRegistrationForm'
    // ]);


    // //Authorization
    // Route::get('role_auth', [
    //     'as'    => 'role_auth.index',
    //     'uses'   => 'AuthorizationController@index'
    // ]);

    // Route::get('test_get', [
    //     'as'    => 'test_get',
    //     'uses'   => 'AuthorizationController@get'
    // ]);

    // Route::post('test_post', [
    //     'as'    => 'test_post',
    //     'uses'   => 'AuthorizationController@post'
    // ]);

    // Route::put('test_put', [
    //     'as'    => 'test_put',
    //     'uses'   => 'AuthorizationController@put'
    // ]);

    // Route::delete('test_delete', [
    //     'as'    => 'test_delete',
    //     'uses'   => 'AuthorizationController@delete'
    // ]);
});

//Frontend Before Auth
Route::group(['middleware' => ['guest', 'prevent.back.history']], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('login', [
        'as'    => 'user.login',
        'uses'   => 'Frontend\Auth\LoginController@showLoginForm'
    ]);

    Route::post('auth', [
        'as'    => 'user.auth',
        'uses'   => 'Frontend\Auth\LoginController@login'
    ]);
});

//Frontend After Auth
Route::group(['middleware' => ['prevent.back.history', 'frontend']], function () {
    Route::get('panel', [
        'as'    => 'user.panel',
        'uses'   => 'Frontend\UserController@panel'
    ]);

    Route::post('logout', [
        'as'    => 'user.logout',
        'uses'   => 'Frontend\Auth\LoginController@logout'
    ]);

    Route::get('password/change_password', [
        'as'    => 'user.password.showChangeForm',
        'uses'   => 'Frontend\Auth\ChangePasswordController@showChangeForm'
    ]);

    Route::post('password/change_password', [
        'as'    => 'user.password.change',
        'uses'   => 'Frontend\Auth\ChangePasswordController@change'
    ]);

    Route::get('index', [
        'as'    => 'user.index',
        'uses'   => 'Frontend\UserController@index'
    ]);

    Route::get('user', [
        'as'    => 'user.data',
        'uses'   => 'Frontend\UserController@data'
    ]);

    Route::get('user_getAll', [
        'as'    => 'user.getAll',
        'uses'   => 'Frontend\UserController@getAll'
    ]);

    Route::get('log', [
        'as'    => 'log.index',
        'uses'   => 'LogController@index'
    ]);

    Route::get('log_getAll', [
        'as'    => 'log.getAll',
        'uses'   => 'LogController@getAll'
    ]);

    //Authorization
    Route::get('role', [
        'as'    => 'role.index',
        'uses'   => 'AuthorizationController@index'
    ]);

    Route::get('role_get', [
        'as'    => 'role.get',
        'uses'   => 'AuthorizationController@get'
    ]);

    Route::post('role_store', [
        'as'    => 'role.store',
        'uses'   => 'AuthorizationController@post'
    ]);

    Route::put('role_update', [
        'as'    => 'role.update',
        'uses'   => 'AuthorizationController@put'
    ]);

    Route::delete('role_delete', [
        'as'    => 'role.delete',
        'uses'   => 'AuthorizationController@delete'
    ]);

    Route::get('role_item', [
        'as'    => 'role.item',
        'uses'   => 'AuthorizationController@item'
    ]);

    //User
    
    Route::post('register', [
        'as'    => 'user.saveRegister',
        'uses'   => 'Frontend\Auth\RegisterController@register'
    ]);

    Route::get('user2', [
        'as'    => 'user2.index',
        'uses'   => 'User2Controller@index'
    ]);

    Route::get('user2GetAll', [
        'as'    => 'user2.getAll',
        'uses'   => 'User2Controller@getAll'
    ]);

    Route::get('user2GetItem', [
        'as'    => 'user2.item',
        'uses'   => 'User2Controller@item'
    ]);

    Route::post('user2Store', [
        'as'    => 'user2.store',
        'uses'   => 'User2Controller@store'
    ]);

    Route::post('user2Update', [
        'as'    => 'user2.update',
        'uses'   => 'User2Controller@update'
    ]);

    Route::post('user2Edit', [
        'as'    => 'user2.edit',
        'uses'   => 'User2Controller@edit'
    ]);

    Route::post('user2Delete', [
        'as'    => 'user2.delete',
        'uses'   => 'User2Controller@destroy'
    ]);

    //Channel

    Route::get('channel', [
        'as'    => 'channel.index',
        'uses'   => 'ChannelController@index'
    ]);

    Route::get('channelGetAll', [
        'as'    => 'channel.getAll',
        'uses'   => 'ChannelController@getAll'
    ]);

    Route::get('channelGetItem', [
        'as'    => 'channel.item',
        'uses'   => 'ChannelController@item'
    ]);

    Route::post('channelStore', [
        'as'    => 'channel.store',
        'uses'   => 'ChannelController@store'
    ]);

    Route::post('channelUpdate', [
        'as'    => 'channel.update',
        'uses'   => 'ChannelController@update'
    ]);

    Route::post('channelEdit', [
        'as'    => 'channel.edit',
        'uses'   => 'ChannelController@edit'
    ]);

    Route::post('channelDelete', [
        'as'    => 'channel.delete',
        'uses'   => 'ChannelController@destroy'
    ]);

    //Organization

    Route::get('organization', [
        'as'    => 'organization.index',
        'uses'   => 'OrganizationController@index'
    ]);

    Route::get('organizationGetAll', [
        'as'    => 'organization.getAll',
        'uses'   => 'OrganizationController@getAll'
    ]);

    Route::get('organizationGetItem', [
        'as'    => 'organization.item',
        'uses'   => 'OrganizationController@item'
    ]);

    Route::post('organizationStore', [
        'as'    => 'organization.store',
        'uses'   => 'OrganizationController@store'
    ]);

    Route::post('organizationUpdate', [
        'as'    => 'organization.update',
        'uses'   => 'OrganizationController@update'
    ]);

    Route::post('organizationEdit', [
        'as'    => 'organization.edit',
        'uses'   => 'OrganizationController@edit'
    ]);

    Route::post('organizationDelete', [
        'as'    => 'organization.delete',
        'uses'   => 'OrganizationController@destroy'
    ]);

    Route::get('li_regional', [
        'as'    => 'organization.item.regional',
        'uses'   => 'OrganizationController@liRegional'
    ]);

    Route::get('li_witel/{id}', [
        'as'    => 'organization.item.witel',
        'uses'   => 'OrganizationController@liWitel'
    ]);

    Route::post('organizationRegionalStore', [
        'as'    => 'organization.store.regional',
        'uses'   => 'OrganizationController@storeRegional'
    ]);

    Route::post('organizationRegionalWitel', [
        'as'    => 'organization.store.witel',
        'uses'   => 'OrganizationController@storeWitel'
    ]);

    //Payment

    Route::get('payment', [
        'as'    => 'payment.index',
        'uses'   => 'PaymentController@index'
    ]);

    Route::get('paymentGetAll', [
        'as'    => 'payment.getAll',
        'uses'   => 'PaymentController@getAll'
    ]);

    Route::get('paymentGetItem', [
        'as'    => 'payment.item',
        'uses'   => 'PaymentController@item'
    ]);

    Route::post('paymentStore', [
        'as'    => 'payment.store',
        'uses'   => 'PaymentController@store'
    ]);

    Route::post('paymentlUpdate', [
        'as'    => 'payment.update',
        'uses'   => 'PaymentController@update'
    ]);

    Route::post('paymentlEdit', [
        'as'    => 'payment.edit',
        'uses'   => 'PaymentController@edit'
    ]);

    Route::post('paymentDelete', [
        'as'    => 'payment.delete',
        'uses'   => 'PaymentController@destroy'
    ]);

    //Product Family

    Route::get('product_family', [
        'as'    => 'product_family.index',
        'uses'   => 'ProductFamilyController@index'
    ]);

    Route::get('product_familyGetAll', [
        'as'    => 'product_family.getAll',
        'uses'   => 'ProductFamilyController@getAll'
    ]);

    Route::get('product_familyGetItem', [
        'as'    => 'product_family.item',
        'uses'   => 'ProductFamilyController@item'
    ]);

    Route::post('product_familyStore', [
        'as'    => 'product_family.store',
        'uses'   => 'ProductFamilyController@store'
    ]);

    Route::post('product_familyUpdate', [
        'as'    => 'product_family.update',
        'uses'   => 'ProductFamilyController@update'
    ]);

    Route::post('product_familyEdit', [
        'as'    => 'product_family.edit',
        'uses'   => 'ProductFamilyController@edit'
    ]);

    Route::post('product_familyDelete', [
        'as'    => 'product_family.delete',
        'uses'   => 'ProductFamilyController@destroy'
    ]);

    //Product

    Route::get('product', [
        'as'    => 'product.index',
        'uses'   => 'ProductController@index'
    ]);

    Route::get('productGetAll', [
        'as'    => 'product.getAll',
        'uses'   => 'ProductController@getAll'
    ]);

    Route::get('productGetItem', [
        'as'    => 'product.item',
        'uses'   => 'ProductController@item'
    ]);

    Route::post('productStore', [
        'as'    => 'product.store',
        'uses'   => 'ProductController@store'
    ]);

    Route::post('productUpdate', [
        'as'    => 'product.update',
        'uses'   => 'ProductController@update'
    ]);

    Route::post('productEdit', [
        'as'    => 'product.edit',
        'uses'   => 'ProductController@edit'
    ]);

    Route::post('productDelete', [
        'as'    => 'product.delete',
        'uses'   => 'ProductController@destroy'
    ]);

    //Transaction Success

    Route::get('transaction_success', [
        'as'    => 'transaction.success',
        'uses'   => 'TransactionController@transactionSuccess'
    ]);

    Route::get('transaction_success_GetAll', [
        'as'    => 'transaction.getAllSuccess',
        'uses'   => 'TransactionController@getAllSuccess'
    ]);

    //Transaction Rejected

    Route::get('transaction_rejected', [
        'as'    => 'transaction.rejected',
        'uses'   => 'TransactionController@transactionRejected'
    ]);

    Route::get('transaction_rejected_GetAll', [
        'as'    => 'transactionRejected.getAll',
        'uses'   => 'TransactionController@getAllRejected'
    ]);

    Route::post('trans_reject_retry', [
        'as'    => 'transactionRejected.retry',
        'uses'   => 'TransactionController@retryRejected'
    ]);

    Route::post('trans_reject_retry_bulk', [
        'as'    => 'transactionRejected.retryBulk',
        'uses'   => 'TransactionController@retryBulkRejected'
    ]);

    Route::post('trans_reject_edit', [
        'as'    => 'transactionRejected.edit',
        'uses'   => 'TransactionController@edit'
    ]);

    Route::post('trans_reject_update', [
        'as'    => 'transactionRejected.update',
        'uses'   => 'TransactionController@update'
    ]);

    Route::get('dashboard_administrator', [
        'as'    => 'dashboard.administrator',
        'uses'   => 'DashboardController@administrator'
    ]);

    Route::get('dashboard_pivot', [
        'as'    => 'dashboard.pivot',
        'uses'   => 'DashboardController@pivot'
    ]);

    Route::get('dashboard_pivot2', [
        'as'    => 'dashboard.pivot2',
        'uses'   => 'DashboardController@pivot2'
    ]);

    Route::get('dashboard_finance', [
        'as'    => 'dashboard.finance',
        'uses'   => 'DashboardController@finance'
    ]);

    Route::post('dashboard_value', [
        'as'    => 'dashboard.value',
        'uses'   => 'DashboardController@value'
    ]);

    Route::post('dashboard_product', [
        'as'    => 'dashboard.product',
        'uses'   => 'DashboardController@product'
    ]);

    Route::post('dashboard_product_all', [
        'as'    => 'dashboard.productAll',
        'uses'   => 'DashboardController@productAll'
    ]);

    Route::post('dashboard_product_all2', [
        'as'    => 'dashboard.productAll2',
        'uses'   => 'DashboardController@productAll2'
    ]);

    //Report Revenue Summary

    Route::get('revenue_summary', [
        'as'    => 'report.revenue_summary',
        'uses'   => 'ReportController@summary'
    ]);

    Route::get('revenue_summary_GetAll', [
        'as'    => 'report.getAllSummary',
        'uses'   => 'ReportController@getAllSummary'
    ]);

    Route::get('revenue_summary_GetAll2', [
        'as'    => 'report.getAllSummary2',
        'uses'   => 'ReportController@getAllSummary2'
    ]);

    Route::get('revenue_summary_GetAll3', [
        'as'    => 'report.getAllSummary3',
        'uses'   => 'ReportController@getAllSummary3'
    ]);

    Route::post('getSummaryRevenue', [
        'as'    => 'getSummaryRevenue',
        'uses'   => 'ReportController@getSummaryRevenue'
    ]);

    //Report Revenue By Product

    Route::get('revenue_by_product', [
        'as'    => 'report.revenue_by_product',
        'uses'   => 'ReportController@byProduct'
    ]);

    Route::get('revenue_by_product_GetAll', [
        'as'    => 'report.getAllByProduct',
        'uses'   => 'ReportController@getAllByProduct'
    ]);

    //API
    Route::get('api', [
        'as'    => 'api.index',
        'uses'   => 'ApiController@index'
    ]);
});

// Route::group(['prefix' => 'api'], function () {
//     Route::get('getToken', [
//         'as'    => 'api.get_token',
//         'uses'   => 'ApiController@getToken'
//     ]);

//     Route::get('generateToken', [
//         'as'    => 'api.generate_token',
//         'uses'   => 'ApiController@generateToken'
//     ]);

//     Route::get('trans_success', [
//         'as'    => 'api.trans_success',
//         'uses'   => 'ApiController@trans_success'
//     ]);

//     Route::get('rev_by_org', [
//         'as'    => 'api.rev_by_org',
//         'uses'   => 'ApiController@byOrg'
//     ]);

//     Route::get('rev_by_product', [
//         'as'    => 'api.rev_by_product',
//         'uses'   => 'ApiController@byProduct'
//     ]);
// });

// Route::get('gettest', [
//     'as'    => 'gettest',
//     'uses'   => 'ApiController@gettest'
// ]);