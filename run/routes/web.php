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

//use UniSharp\LaravelFilemanager\Lfm;
//Route::group(['prefix' => 'cms/laravel-filemanager', 'middleware' => ['web', 'customer', 'auth']], function () {
//    Lfm::routes();
//});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('/player_data/opt_table', 'CMS\LuckyDrawController@opt_table')->name('player_data.opt_table');


//test
//
//Route::get('/', 'HomeController@pre_open')->name('home');
//Route::get('/home', 'HomeController@pre_open')->name('home');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');


Route::get('/register', 'RegisterController@index')->name('register');
//Route::post('/register/save', 'RegisterController@save')->name('register.save');
Route::get('/share/{dt}/{file}', 'ShareController@index')->name('share');



//images

Route::get('/storage/{path}', function ($path) {

//    return $path;

    $img = Image::cache(function ($img) use ($path) {

        $img->make(public_path() . '/storage/' . $path)->resize(300, 200);

    }, 10, true);

    return $img->response('jpg');

})->where(['path' => '[0-9a-z\/\._]+']);



//聯絡我們

Route::get('/contact', 'ContactController@index')->name('contact.index');

Route::post('/contact/store', 'ContactController@store')->name('contact.store');

Route::post('/contact/subscriber', 'ContactController@subscriber')->name('contact.subscriber');





//Auth::routes(['register' => false]); //前台不提供註冊頁面 ,如要關閉前台的判斷就整段MARK起來





//後台登入頁面

Route::get('/cms/', 'CMS\LoginController@index')->name('cms.login.index');

Route::get('/cms/login', 'CMS\LoginController@index')->name('cms.login.index');

Route::get('/cms/login/logout', 'CMS\LoginController@logout')->name('cms.login.logout');

Route::post('/cms/login/checker', 'CMS\LoginController@checker')->name('cms.login.checker');



//客戶聯絡資料不經登入

//Route::get('construction_registration/{uuid}/callout_view', 'CMS\ConstructionRegistrationController@callout_view')->name('cms.construction_registration.callout_view');

//Route::get('construction_registration/{uuid}/customer_view', 'CMS\ConstructionRegistrationController@customer_view')->name('cms.construction_registration.customer_view');



//權限

//Route::resource('permissions', 'CMS\PermissionController');

//後台頁面

Route::group(['prefix' => 'cms','middleware' => ['auth:web']], function () {

    //後台儀表板頁面

    Route::get('dashboard', 'CMS\DashboardController@index')->name('cms.dashboard.index');

    //後台管理員頁面

//    Route::resource('users', 'CMS\userController', ['as' => 'cms']);

    Route::post('users/store', 'CMS\UserController@store')->name('cms.users.store');

    Route::get('users', 'CMS\UserController@index')->name('cms.users.index');

    Route::get('users/create', 'CMS\UserController@create')->name('cms.users.create');

    Route::delete('users/destroy/{user}', 'CMS\UserController@destroy')->name('cms.users.destroy');

    Route::put('users/update/{user}', 'CMS\UserController@update')->name('cms.users.update');

    Route::get('users/show/{user}', 'CMS\UserController@show')->name('cms.users.show');

    Route::get('users/{user}/edit/', 'CMS\UserController@edit')->name('cms.users.edit');



    //權限管理

//    Route::resource('permissions', 'CMS\PermissionController');

    Route::post('permissions/store', 'CMS\PermissionController@store')->name('cms.permissions.store');

    Route::get('permissions', 'CMS\PermissionController@index')->name('cms.permissions.index');

    Route::get('permissions/create', 'CMS\PermissionController@create')->name('cms.permissions.create');

    Route::delete('permissions/destroy/{user}', 'CMS\PermissionController@destroy')->name('cms.permissions.destroy');

    Route::put('permissions/update/{user}', 'CMS\PermissionController@update')->name('cms.permissions.update');

    Route::get('permissions/show/{user}', 'CMS\PermissionController@show')->name('cms.permissions.show');

    Route::get('permissions/{user}/edit/', 'CMS\PermissionController@edit')->name('cms.permissions.edit');



    //角色管理

//    Route::resource('roles', 'CMS\RoleController');

    Route::post('roles/store', 'CMS\RoleController@store')->name('cms.roles.store');

    Route::get('roles', 'CMS\RoleController@index')->name('cms.roles.index');

    Route::get('roles/create', 'CMS\RoleController@create')->name('cms.roles.create');

    Route::delete('roles/destroy/{user}', 'CMS\RoleController@destroy')->name('cms.roles.destroy');

    Route::put('roles/update/{user}', 'CMS\RoleController@update')->name('cms.roles.update');

    Route::get('roles/show/{user}', 'CMS\RoleController@show')->name('cms.roles.show');

    Route::get('roles/{user}/edit/', 'CMS\RoleController@edit')->name('cms.roles.edit');


    //聯絡我們管理

    Route::post('contact_us/store', 'CMS\ContactUsController@store')->name('cms.contact_us.store');

    Route::get('contact_us', 'CMS\ContactUsController@index')->name('cms.contact_us.index');

    Route::get('contact_us/create', 'CMS\ContactUsController@create')->name('cms.contact_us.create');

    Route::delete('contact_us/destroy/{id}', 'CMS\ContactUsController@destroy')->name('cms.contact_us.destroy');

    Route::put('contact_us/update/{id}', 'CMS\ContactUsController@update')->name('cms.contact_us.update');

    Route::get('contact_us/show/{id}', 'CMS\ContactUsController@show')->name('cms.contact_us.show');

    Route::get('contact_us/{id}/edit/', 'CMS\ContactUsController@edit')->name('cms.contact_us.edit');

    Route::get('contact_us/{id}/reply/', 'CMS\ContactUsController@reply')->name('cms.contact_us.reply');

    Route::post('contact_us/store_reply', 'CMS\ContactUsController@store_reply')->name('cms.contact_us.store_reply');



    //訂閱者管理

    Route::post('subscribers/store', 'CMS\SubscriberController@store')->name('cms.subscribers.store');

    Route::get('subscribers', 'CMS\SubscriberController@index')->name('cms.subscribers.index');

    Route::get('subscribers/create', 'CMS\SubscriberController@create')->name('cms.subscribers.create');

    Route::delete('subscribers/destroy/{id}', 'CMS\SubscriberController@destroy')->name('cms.subscribers.destroy');

    Route::put('subscribers/update/{id}', 'CMS\SubscriberController@update')->name('cms.subscribers.update');

    Route::get('subscribers/show/{id}', 'CMS\SubscriberController@show')->name('cms.subscribers.show');

    Route::get('subscribers/{id}/edit/', 'CMS\SubscriberController@edit')->name('cms.subscribers.edit');



    //電子報管理

    Route::post('newsletters/store', 'CMS\NewsletterController@store')->name('cms.newsletters.store');

    Route::get('newsletters', 'CMS\NewsletterController@index')->name('cms.newsletters.index');

    Route::get('newsletters/create', 'CMS\NewsletterController@create')->name('cms.newsletters.create');

    Route::delete('newsletters/destroy/{id}', 'CMS\NewsletterController@destroy')->name('cms.newsletters.destroy');

    Route::put('newsletters/update/{id}', 'CMS\NewsletterController@update')->name('cms.newsletters.update');

    Route::get('newsletters/show/{id}', 'CMS\NewsletterController@show')->name('cms.newsletters.show');

    Route::get('newsletters/{id}/edit/', 'CMS\NewsletterController@edit')->name('cms.newsletters.edit');



    //送報

    Route::get('newsboy/{id}/sending', 'CMS\NewsboyController@index')->name('cms.newsboy.index');

    Route::get('newsboy/{id}/test', 'CMS\NewsboyController@test')->name('cms.newsboy.test');



    //電子報測試管理

    Route::post('newsletter_testers/store', 'CMS\NewsletterTesterController@store')->name('cms.newsletter_testers.store');

    Route::get('newsletter_testers', 'CMS\NewsletterTesterController@index')->name('cms.newsletter_testers.index');

    Route::get('newsletter_testers/create', 'CMS\NewsletterTesterController@create')->name('cms.newsletter_testers.create');

    Route::delete('newsletter_testers/destroy/{id}', 'CMS\NewsletterTesterController@destroy')->name('cms.newsletter_testers.destroy');

    Route::put('newsletter_testers/update/{id}', 'CMS\NewsletterTesterController@update')->name('cms.newsletter_testers.update');

    Route::get('newsletter_testers/show/{id}', 'CMS\NewsletterTesterController@show')->name('cms.newsletter_testers.show');

    Route::get('newsletter_testers/{id}/edit/', 'CMS\NewsletterTesterController@edit')->name('cms.newsletter_testers.edit');

//後台獎項頁面
    Route::get('prize', 'CMS\PrizeController@index')->name('cms.prize.index');
    Route::get('prize/create', 'CMS\PrizeController@create')->name('cms.prize.create');
    Route::post('prize/store', 'CMS\PrizeController@store')->name('cms.prize.store');
    Route::get('prize/{ref_id}/edit', 'CMS\PrizeController@edit')->name('cms.prize.edit');
    Route::put('prize/update', 'CMS\PrizeController@update')->name('cms.prize.update');
    Route::delete('prize/{uuid}/delete', 'CMS\PrizeController@delete')->name('cms.prize.delete')->where('uuid', '[A-Za-z0-9_.\-]+');

    //抽獎
    Route::post('prize/{uuid}/draw/{lottery_list}', 'CMS\LuckyDrawController@draw')->name('cms.prize.draw')->where('uuid', '[A-Za-z0-9_.\-]+')->where('lottery_list', '[0-9_.\-]+');
    Route::post('prize/{uuid}/clear/{lottery_list}', 'CMS\LuckyDrawController@clear')->name('cms.prize.clear')->where('uuid', '[A-Za-z0-9_.\-]+')->where('lottery_list', '[0-9_.\-]+');
    Route::get('prize/{uuid}/awards', 'CMS\LuckyDrawController@awards')->name('cms.prize.awards')->where('uuid', '[A-Za-z0-9_.\-]+');

    //匯出得獎名單
    Route::get('prize/{uuid}/export_awards', 'CMS\LuckyDrawController@export_awards')->name('cms.prize.export_awards')->where('uuid', '[A-Za-z0-9_.\-]+');
    Route::get('prize/{uuid}/draw_test', 'CMS\LuckyDrawController@draw_test')->name('cms.prize.draw_test')->where('uuid', '[A-Za-z0-9_.\-]+');

    //後台玩家頁面
    Route::get('player', 'CMS\PlayerController@index')->name('cms.player.index');
    Route::put('player/update', 'CMS\PlayerController@update')->name('cms.player.update');
    Route::delete('player/destroy/{player}', 'CMS\PlayerController@destroy')->name('cms.player.destroy');

    Route::get('player_invoice', 'CMS\PlayerInvoiceController@index')->name('cms.player_invoice.index');
    Route::delete('player_invoice/destroy/{player}', 'CMS\PlayerInvoiceController@destroy')->name('cms.player_invoice.destroy');



});



//上傳檔案

Route::post('/cms/upload/file', 'CMS\FileUploadController@file')->name('cms.upload.file');



//上傳圖片

Route::post('/cms/upload/image', 'CMS\FileUploadController@image')->name('cms.upload.image');





//run 送信監聽器

//Route::get('/send_mail_listen', function () {

//    return Artisan::call('queue:listen');

//})->name('send.mail.listen');



//Route::get('/flush_listen', function () {

//    Artisan::call('queue:flush');

//    return ['queue:flush'];

//});



Route::get('/check_mailer', 'CheckMailerController@index')->name('check_mailer.index');

//

//Route::get('/storage_link', function () {

//    Artisan::call('storage:link');

//    return ['storage:link'];

//});



//Route::get('/cache_clear', function () {

//    Artisan::call('cache:clear');

//    Artisan::call('config:clear');

//    return ['cache:clear'];

//});

