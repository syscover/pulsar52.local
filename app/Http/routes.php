<?php

// Atención usar middlelware langlocale.pulsar para abarcar todas las rutas, para obtener el userLang de la session
Route::group(['middleware' => ['web', 'pulsar.langlocale']], function () {
    
    Route::get('/',                                                                         ['as'=>'home',                      'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    Route::get('/es',                                                                       ['as'=>'home-es',                   'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    
    // Market routes
    Route::get('/es/product/list',                                                          ['as'=>'productList-es',            'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProductsList']);
    Route::get('/es/product/{category}/{slug}',                                             ['as'=>'product-es',                'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProduct']);

    // Shopping cart routes
    Route::get('/es/carro/de/compra',                                                       ['as'=>'shoppingCart-es',           'uses'	=> '\App\Http\Controllers\ShoppingCartController@showShoppingCart']);
    Route::match(['get', 'post'], '/es/carro/de/compra/anadir/producto/{slug}',             ['as'=>'addShoppingCart-es',        'uses'	=> '\App\Http\Controllers\ShoppingCartController@addShoppingCart']);
    Route::match(['get', 'post'], '/es/carro/de/compra/borrar/producto/{rowId}',            ['as'=>'deleteShoppingCart-es',     'uses'	=> '\App\Http\Controllers\ShoppingCartController@deleteShoppingCart']);
    Route::put('/es/carro/de/comprar/actualizar/producto',                                  ['as'=>'updateShoppingCart-es',     'uses'	=> '\App\Http\Controllers\ShoppingCartController@updateShoppingCart']);

    Route::get('/es/account/login',                                                         ['as'=>'login-es',                  'uses'	=> '\App\Http\Controllers\CustomerFrontendController@login']);
    Route::get('/es/account/logout',                                                        ['as'=>'logout-es',                 'uses'	=> '\App\Http\Controllers\Auth\AuthController@logout']);

    Route::get('/es/account/sing-in',                                                       ['as'=>'get-singIn-es',             'uses'	=> '\App\Http\Controllers\CustomerFrontendController@getSingIn']);
    Route::post('/es/account/sing-in',                                                      ['as'=>'post-singIn-es',            'uses'	=> '\App\Http\Controllers\CustomerFrontendController@postSingIn']);
    Route::put('/es/account/sing-in',                                                       ['as'=>'put-singIn-es',             'uses'	=> '\App\Http\Controllers\CustomerFrontendController@putSingIn']);
});

Route::group(['middleware' => ['web', 'pulsar.langlocale', 'auth:crm']], function() {
    Route::get('/es/account',                                                               ['as'=>'account-es',                'uses'	=> '\App\Http\Controllers\CustomerFrontendController@account']);
});

Route::group(['middleware' => ['web', 'pulsar.langlocale']], function () {
    Route::post('/account/customer/set/auth',                                               ['as' => 'setAuth',                 'uses' => '\App\Http\Controllers\Auth\AuthController@authenticate']);

});





/* TEMPLATE */
//Route::get('/',                                     ['as'=>'home',                              function(){ return view('www.content.home',                     []  );}   ]);
//Route::get('/shop',                                 ['as'=>'shop',                              function(){ return view('www.content.shop',                     []  );}   ]);
//Route::get('/product',                              ['as'=>'product',                           function(){ return view('www.content.product',                  []  );}   ]);
Route::get('/gallery',                              ['as'=>'gallery',                           function(){ return view('www.content.gallery',                  []  );}   ]);
Route::get('/gallery-alt',                          ['as'=>'gallery-alt',                       function(){ return view('www.content.gallery-alt',              []  );}   ]);
Route::get('/contact',                              ['as'=>'contact',                           function(){ return view('www.content.contact',                  []  );}   ]);

//Route::get('/shopping-cart',                        ['as'=>'shopping',                          function(){ return view('www.content.shopping-cart',            []  );}   ]);
Route::get('/fullmenu',                             ['as'=>'fullmenu',                          function(){ return view('www.content.fullmenu',                 []  );}   ]);
Route::get('/checkout',                             ['as'=>'checkout',                          function(){ return view('www.content.checkout',                 []  );}   ]);

Route::get('/info',                                 ['as'=>'info',                              function(){ return view('www.content.info',                     []  );}   ]);