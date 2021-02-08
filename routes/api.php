<?php

Route::group([
    'prefix' => 'auth'
  ], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('checksession', 'AuthController@checksession');

        Route::group([
          'prefix' => 'create'
        ], function () {
          Route::post('dir', 'CreateController@dir');
          Route::post('eventos', 'CreateController@eventos');
          Route::post('productos', 'CreateController@productos');
          Route::post('anuncios', 'CreateController@anuncios');
          Route::post('uploadImages', 'CreateController@uploadImages');
        });

        Route::group([
          'prefix' => 'tags'
        ], function () {
          Route::post('addfav/{type}/{id}', 'CreateController@addfav');
          Route::post('addview/{type}/{id}', 'CreateController@addview');
          Route::post('addshare/{type}/{id}', 'CreateController@addshare');
          Route::post('addcomments/{type}/{id}', 'CreateController@addcomments');
        });

        Route::group([
          'prefix' => 'mydata'
        ], function () {
          Route::post('delete/{type}/{id}', 'DataController@delete');
          Route::get('favs', 'DataController@favs');
          Route::get('prombus/{id}', 'DataController@prombus');
          Route::get('mybusiness', 'DataController@mybusiness');
          Route::get('business', 'DataController@business');
          Route::get('products', 'DataController@products');
          Route::get('events', 'DataController@events');
          Route::get('ads', 'DataController@ads');
          Route::get('seebusiness/{id}', 'DataController@seebusiness');
          Route::get('seeevents/{id}', 'DataController@seeevents');
          Route::get('seeproducts/{id}', 'DataController@seeproducts');
          Route::get('seeads/{id}', 'DataController@seeads');
          Route::get('lastedbusiness', 'DataController@lastedbusiness');
          Route::get('lastedbillboard', 'DataController@lastedbillboard');
          Route::get('lastedproduct', 'DataController@lastedproduct');
          Route::get('lastedadd', 'DataController@lastedadd');
          Route::get('statics/{type}/{id}', 'DataController@mystatics');
          Route::get('listbusiness/{type}/{category}/{subcategory}', 'DataController@listbusiness');
          Route::get('listads/{type}/{category}/{subcategory}', 'DataController@listbusiness');
          Route::get('get/{type}/{id}', 'DataController@getdata');

        });

    });
  });


  Route::group([
    'prefix' => 'data'
  ], function () {

    Route::get('categories', 'DataController@categories');

    Route::get('questions', 'DataController@questions');
    Route::get('categorys/{type}', 'DataController@categorys');
    Route::get('subcategorys/{type}/{id}', 'DataController@subcategorys');

    Route::get('categoriesBusiness', 'DataController@categoriesBusiness');
    Route::get('subcategoriesBusiness/{id}', 'DataController@subcategoriesBusiness');

    Route::get('categoriesAds', 'DataController@categoriesAds');
    Route::get('subcategoriesAds/{id}', 'DataController@subcategoriesAds');

    Route::get('categoriesEvents', 'DataController@categoriesEvents');
    Route::get('subcategoriesEvents/{id}', 'DataController@subcategoriesEvents');

    Route::get('categoriesProducts', 'DataController@categoriesProducts');
    Route::get('subcategoriesProducts/{id}', 'DataController@subcategoriesProducts');

  });


