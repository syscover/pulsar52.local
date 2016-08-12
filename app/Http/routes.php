<?php

// Atención usar middlelware pulsar.navTools para abarcar todas las rutas, para obtener el userLang de la session
Route::group(['middleware' => ['web', 'pulsar.navTools', 'pulsar.taxRule']], function () {
    
    Route::get('/',                                                                         ['as'=>'home',                      'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    Route::get('/es',                                                                       ['as'=>'home-es',                   'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    Route::get('/en',                                                                       ['as'=>'home-en',                   'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    
    // Market routes
    Route::get('/es/product/list',                                                          ['as'=>'productList-es',            'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProductsList']);
    Route::get('/es/product/{category}/{slug}',                                             ['as'=>'product-es',                'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProduct']);

    // Shopping cart routes
    Route::get('/es/carro/de/compra',                                                       ['as'=>'getShoppingCart-es',        'uses'	=> '\App\Http\Controllers\ShoppingCartController@getShoppingCart']);
    Route::match(['get', 'post'], '/es/carro/de/compra/anadir/producto/{slug}',             ['as'=>'postShoppingCart-es',       'uses'	=> '\App\Http\Controllers\ShoppingCartController@postShoppingCart']);
    Route::match(['get', 'post'], '/es/carro/de/compra/borrar/producto/{rowId}',            ['as'=>'deleteShoppingCart-es',     'uses'	=> '\App\Http\Controllers\ShoppingCartController@deleteShoppingCart']);
    Route::put('/es/carro/de/comprar/actualizar/producto',                                  ['as'=>'putShoppingCart-es',        'uses'	=> '\App\Http\Controllers\ShoppingCartController@putShoppingCart']);

    Route::get('/es/account/login',                                                         ['as'=>'getLogin-es',               'uses'	=> '\App\Http\Controllers\CustomerFrontendController@getLogin']);
    Route::match(['get', 'post'], '/es/account/logout',                                     ['as'=>'logout-es',                 'uses'	=> '\App\Http\Controllers\CustomerFrontendController@logout']);

    Route::get('/es/account/sing-in',                                                       ['as'=>'getSingIn-es',              'uses'	=> '\App\Http\Controllers\CustomerFrontendController@getSingIn']);
    Route::post('/es/account/sing-in',                                                      ['as'=>'postSingIn-es',             'uses'	=> '\App\Http\Controllers\CustomerFrontendController@postSingIn']);
    Route::put('/es/account/sing-in',                                                       ['as'=>'putSingIn-es',              'uses'	=> '\App\Http\Controllers\CustomerFrontendController@putSingIn']);


    // Factura Directa routes
    Route::get('/es/factura/directa/clients',                                               ['as'=>'facturaDirectaClients-es',  'uses'	=> '\App\Http\Controllers\FacturaDirectaController@getClients']);
});

Route::group(['middleware' => ['web', 'pulsar.navTools', 'auth:crm']], function() {
    Route::get('/es/account',                                                               ['as'=>'account-es',                'uses'	=> '\App\Http\Controllers\CustomerFrontendController@account']);

    // EN
    // Checkout routes
    Route::get('/es/checkout/shipping',                                                     ['as'=>'getCheckout01-es',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout01']);
    Route::post('/es/checkout/shipping',                                                    ['as'=>'postCheckout01-es',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout01']);
    Route::get('/es/checkout/invoice',                                                      ['as'=>'getCheckout02-es',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout02']);
    Route::post('/es/checkout/invoice',                                                     ['as'=>'postCheckout02-es',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout02']);
    Route::get('/es/checkout/payment',                                                      ['as'=>'getCheckout03-es',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout03']);
    Route::post('/es/checkout/payment',                                                     ['as'=>'postCheckout03-es',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout03']);

    // ES
});

Route::group(['middleware' => ['web', 'pulsar.navTools']], function () {
    //EN
    Route::post('/en/account/login/',                                                       ['as' => 'postLogin-en',           'uses' => '\App\Http\Controllers\CustomerFrontendController@postLogin']);

    //ES
    Route::post('/es/cuenta/login/',                                                        ['as' => 'postLogin-es',           'uses' => '\App\Http\Controllers\CustomerFrontendController@postLogin']);
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