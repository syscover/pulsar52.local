<?php

// Atención usar middlelware pulsar.navTools para abarcar todas las rutas, para obtener el userLang de la session
Route::group(['middleware' => ['web', 'pulsar.navTools', 'pulsar.taxRule']], function () {
    
    Route::get('/',                                                                         ['as'=>'home',                      'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    Route::get('/es',                                                                       ['as'=>'home-es',                   'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    Route::get('/en',                                                                       ['as'=>'home-en',                   'uses'	=> '\App\Http\Controllers\WebFrontendController@home']);
    
    // MARKET ROUTES
    // EN
    Route::get('/en/product/list',                                                          ['as'=>'productList-en',            'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProductsList']);
    Route::get('/en/product/{category}/{slug}',                                             ['as'=>'product-en',                'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProduct']);

    // ES
    Route::get('/es/producto/listado',                                                      ['as'=>'productList-es',            'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProductsList']);
    Route::get('/es/producto/{category}/{slug}',                                            ['as'=>'product-es',                'uses'	=> '\App\Http\Controllers\MarketFrontendController@getProduct']);

    // SHOPPING CART
    // EN
    Route::get('/en/shopping/cart',                                                         ['as'=>'getShoppingCart-es',        'uses'	=> '\App\Http\Controllers\ShoppingCartController@getShoppingCart']);
    Route::match(['get', 'post'], '/en/shopping/cart/add/product/{slug}',                   ['as'=>'postShoppingCart-es',       'uses'	=> '\App\Http\Controllers\ShoppingCartController@postShoppingCart']);
    Route::match(['get', 'post'], '/en/shopping/cart/delete/product/{rowId}',               ['as'=>'deleteShoppingCart-es',     'uses'	=> '\App\Http\Controllers\ShoppingCartController@deleteShoppingCart']);
    Route::put('/en/shopping/cart/update',                                                  ['as'=>'putShoppingCart-es',        'uses'	=> '\App\Http\Controllers\ShoppingCartController@putShoppingCart']);

    // ES
    Route::get('/es/carro/de/compra',                                                       ['as'=>'getShoppingCart-es',        'uses'	=> '\App\Http\Controllers\ShoppingCartController@getShoppingCart']);
    Route::match(['get', 'post'], '/es/carro/de/compra/anadir/producto/{slug}',             ['as'=>'postShoppingCart-es',       'uses'	=> '\App\Http\Controllers\ShoppingCartController@postShoppingCart']);
    Route::match(['get', 'post'], '/es/carro/de/compra/borrar/producto/{rowId}',            ['as'=>'deleteShoppingCart-es',     'uses'	=> '\App\Http\Controllers\ShoppingCartController@deleteShoppingCart']);
    Route::put('/es/carro/de/comprar/actualizar',                                           ['as'=>'putShoppingCart-es',        'uses'	=> '\App\Http\Controllers\ShoppingCartController@putShoppingCart']);

    // CUSTOMER ACCOUNT
    // EN
    Route::get('/en/account/login',                                                         ['as'=>'getLogin-en',               'uses'	=> '\App\Http\Controllers\CustomerFrontendController@getLogin']);
    Route::match(['get', 'post'], '/en/account/logout',                                     ['as'=>'logout-en',                 'uses'	=> '\App\Http\Controllers\CustomerFrontendController@logout']);
    Route::get('/en/account/sing-in',                                                       ['as'=>'getSingIn-en',              'uses'	=> '\App\Http\Controllers\CustomerFrontendController@getSingIn']);
    Route::post('/en/account/sing-in',                                                      ['as'=>'postSingIn-en',             'uses'	=> '\App\Http\Controllers\CustomerFrontendController@postSingIn']);
    Route::put('/en/account/sing-in',                                                       ['as'=>'putSingIn-en',              'uses'	=> '\App\Http\Controllers\CustomerFrontendController@putSingIn']);

    // ES
    Route::get('/es/cuenta/login',                                                          ['as'=>'getLogin-es',               'uses'	=> '\App\Http\Controllers\CustomerFrontendController@getLogin']);
    Route::match(['get', 'post'], '/es/cuenta/logout',                                      ['as'=>'logout-es',                 'uses'	=> '\App\Http\Controllers\CustomerFrontendController@logout']);
    Route::get('/es/cuenta/registro',                                                       ['as'=>'getSingIn-es',              'uses'	=> '\App\Http\Controllers\CustomerFrontendController@getSingIn']);
    Route::post('/es/cuenta/registro',                                                      ['as'=>'postSingIn-es',             'uses'	=> '\App\Http\Controllers\CustomerFrontendController@postSingIn']);
    Route::put('/es/cuenta/registro',                                                       ['as'=>'putSingIn-es',              'uses'	=> '\App\Http\Controllers\CustomerFrontendController@putSingIn']);


    // FACTURA DIRECTA
    // EN
    Route::get('/en/factura/directa/clients',                                               ['as'=>'facturaDirectaClients-en',  'uses'	=> '\App\Http\Controllers\FacturaDirectaController@getClients']);

    // ES
    Route::get('/es/factura/directa/clientes',                                              ['as'=>'facturaDirectaClients-es',  'uses'	=> '\App\Http\Controllers\FacturaDirectaController@getClients']);
});

Route::group(['middleware' => ['web', 'pulsar.navTools', 'auth:crm']], function() {

    // CUSTOMER ACCOUNT
    // EN
    Route::match(['get', 'post'], '/en/account',                                            ['as'=>'account-en',                'uses'	=> '\App\Http\Controllers\CustomerFrontendController@account']);

    // ES
    Route::match(['get', 'post'], '/es/cuenta',                                             ['as'=>'account-es',                'uses'	=> '\App\Http\Controllers\CustomerFrontendController@account']);

    // CHECKOUT
    // EN
    Route::get('/en/checkout/shipping',                                                     ['as'=>'getCheckout01-en',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout01']);
    Route::post('/en/checkout/shipping',                                                    ['as'=>'postCheckout01-en',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout01']);
    Route::get('/en/checkout/invoice',                                                      ['as'=>'getCheckout02-en',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout02']);
    Route::post('/en/checkout/invoice',                                                     ['as'=>'postCheckout02-en',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout02']);
    Route::get('/en/checkout/payment',                                                      ['as'=>'getCheckout03-en',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout03']);
    Route::post('/en/checkout/payment',                                                     ['as'=>'postCheckout03-en',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout03']);

    // ES
    Route::get('/es/realizar/pedido/envio',                                                 ['as'=>'getCheckout01-es',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout01']);
    Route::post('/es/realizar/pedido/envio',                                                ['as'=>'postCheckout01-es',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout01']);
    Route::get('/es/realizar/pedido/factura',                                               ['as'=>'getCheckout02-es',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout02']);
    Route::post('/es/realizar/pedido/factura',                                              ['as'=>'postCheckout02-es',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout02']);
    Route::get('/es/realizar/pedido/pago',                                                  ['as'=>'getCheckout03-es',          'uses'	=> '\App\Http\Controllers\MarketFrontendController@getCheckout03']);
    Route::post('/es/realizar/pedido/pago',                                                 ['as'=>'postCheckout03-es',         'uses'	=> '\App\Http\Controllers\MarketFrontendController@postCheckout03']);
});

Route::group(['middleware' => ['web', 'pulsar.navTools']], function () {
    // LOGIN
    Route::post('/en/account/login/',                                                       ['as' => 'postLogin',           'uses' => '\App\Http\Controllers\CustomerFrontendController@postLogin']);
});