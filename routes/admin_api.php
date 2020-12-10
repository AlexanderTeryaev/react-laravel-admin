<?php

use Illuminate\Support\Facades\Route;


Route::post('/login', 'AuthController@login')->name('login');


// TODO: Add check middleware: check platform
Route::group(['middleware' => 'auth:user'], function () {
    Route::get('/me', 'UserController@me')->name('me');
    Route::post('/logout', 'AuthController@logout')->name('logout');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/group_switch/{id}', 'HomeController@group_switch')->name('home.group_switch');
    Route::post('/md_upload', 'HomeController@upload_md_images')->name('home.upload_md_images');

    Route::get('/get-user-installation-chart-data', 'HomeController@getUserDailyInstallation');
    Route::get('/get-questions-answers-data', 'HomeController@getQuestionDailyAnswered');
    Route::get('/get-repartition-data', 'HomeController@getRepartition');

    // Api in groups
    // Route::get('groups/{id}/edit', 'GroupController@edit');
    // Route::get('groups/create', 'GroupController@create');
    Route::post('groups/{id}/update', 'GroupController@update');

    // Api in featured
    Route::get('featured/{id}/edit', 'FeaturedController@edit');
    Route::get('featured/create', 'FeaturedController@create');
    Route::post('featured/{id}/update', 'FeaturedController@update');

    // Api in categories
    Route::get('categories/{id}/edit', 'CategoryController@edit');
    Route::get('categories/create', 'CategoryController@create');
    Route::post('categories/{id}/update', 'CategoryController@update');

    // Api in Quizz
    Route::get('quizzes/author', 'QuizzController@create');
    Route::get('quizzes/{id}/edit', 'QuizzController@edit');
    Route::post('quizzes/{id}/questions/image', 'QuizzController@questions_image');
    Route::post('quizzes/{id}/update', 'QuizzController@update');

    // Api in Question
    Route::get('questions/{id}/edit', 'QuestionController@edit');
    Route::get('questions/create', 'QuestionController@create');
    Route::post('questions/{id}/update', 'QuestionController@update');

    // Api in imports
    Route::get('/imports/import', 'ImportController@import')->name('imports.import');
    Route::get('/imports/delete', 'ImportController@destroy_all')->name('imports.destroyAll');

    // Api in Author
    Route::get('authors/{id}/edit', 'AuthorController@edit');
    Route::get('authors/create', 'AuthorController@create');
    Route::post('authors/{id}/update', 'AuthorController@update');

    // Api in report
    Route::get('reports/{id}/edit', 'reports@edit');
    Route::get('reports/create', 'reports@create');
    Route::post('reports/{id}/update', 'reports@update');

    // Api in Training
    Route::get('shop_trainings/{id}/edit', 'ShopTrainingController@edit');
    Route::get('shop_trainings/create', 'ShopTrainingController@create');
    Route::post('shop_trainings/{id}/update', 'ShopTrainingController@update');

    // Api in shop quizz
    Route::get('shop_quizzes/create', 'ShopQuizzController@create');
    Route::get('shop_quizzes/{id}/edit', 'ShopQuizzController@edit');
    Route::post('shop_quizzes/{id}/questions/image', 'ShopQuizzController@questions_image');
    Route::post('shop_quizzes/{id}/update', 'ShopQuizzController@update');

    // Api in shop Question
    Route::get('shop_questions/{id}/edit', 'ShopQuestionController@edit');
    Route::get('shop_questions/create', 'ShopQuestionController@create');
    Route::post('shop_questions/{id}/update', 'ShopQuestionController@update');

    // Api in shop imports
    Route::get('/shop_imports/import', 'ShopQuestionImportController@import')->name('shop_imports.import');
    Route::get('/shop_imports/delete', 'ShopQuestionImportController@destroy_all')->name('shop_imports.destroyAll');

    // Api in shop Author
    Route::get('shop_authors/{id}/edit', 'ShopAuthorController@edit');
    Route::get('shop_authors/create', 'ShopAuthorController@create');
    Route::post('shop_authors/{id}/update', 'ShopAuthorController@update');

    // Api in plan
    Route::get('plan/{id}/edit', 'PlanController@edit');
    Route::get('plan/create', 'PlanController@create');
    Route::post('plan/{id}/update', 'PlanController@update');

    // Api in coin pack
    Route::get('coins_pack/{id}/edit', 'CoinsPackController@edit');
    Route::get('coins_pack/create', 'CoinsPackController@create');
    Route::post('coins_pack/{id}/update', 'CoinsPackController@update');

    // Api in config
    Route::get('configs/{id}/edit', 'ConfigController@edit');
    Route::get('configs/create', 'ConfigController@create');
    Route::post('configs/{id}/update', 'ConfigController@update');

    Route::apiResources([
        'users' => 'UserController',
        'groups' => 'GroupController',    
        'insight_recipients' => 'InsightRecipientController',
        'featured' => 'FeaturedController',
        'categories' => 'CategoryController',
        'quizzes' => 'QuizzController',
        'questions' => 'QuestionController',
        'imports' => 'ImportController',
        'authors' => 'AuthorController',
        'reports' => 'ReportController',
        'shop_trainings' => 'ShopTrainingController',
        'shop_quizzes' => 'ShopQuizzController',
        'shop_questions' => 'ShopQuestionController',
        'shop_imports' => 'ShopQuestionImportController',
        'shop_authors' => 'ShopAuthorController',
        'plan' => 'PlanController',
        'coins_pack' => 'CoinsPackController',
        'configs' => 'ConfigController',
    ]);

    Route::get('allGroups', 'GroupController@allGroups');
    // Api in User Detail
    Route::get('users/{id}/{groupid}/leftgroup', 'UserController@leftgroup')->middleware('permission:edit user')->name('users.leftgroup');
    Route::get('users/{id}/{group_id}/training_doc', 'UserController@traning_doc')->name('users.training_doc');
    // Api in Group Detail
    Route::post('groups/{group_id}/manager', 'GroupController@store_manager')->name('groups.manager.store');
    Route::delete('groups/{group_id}/manager/{id}', 'GroupController@destroy_manager')->name('groups.manager.destroy');
    Route::post('groups/{group_id}/config', 'GroupController@store_config')->name('groups.config.store');
    Route::delete('groups/{group_id}/config/{id}', 'GroupController@destroy_config')->name('groups.config.destroy');
    Route::post('groups/{group_id}/email_domain', 'GroupController@store_email_domain')->name('groups.email_domain.store');
    Route::delete('groups/{group_id}/email_domain/{id}', 'GroupController@destroy_email_domain')->name('groups.email_domain.destroy');    
    
});
Route::get('groups/{id}/users/download', 'GroupController@download_users')->name('groups.download.users');