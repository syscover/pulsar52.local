<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductsCategories;
use Syscover\Pulsar\Models\Attachment;

/**
 * Class MarketFrontendController
 * @package App\Http\Controllers
 * 
 * ATENCIÓN! Para constantes usar el fichero de configuracón config/www.php
 * 
 */

class MarketFrontendController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProductsList()
    {
        $response = [];

        // Option 1 - get products by categories
        $response['products'] = Product::productsByCategories([
                config('www.productsListCategories.tarjetas'),
                config('www.productsListCategories.escapadas'),
                config('www.productsListCategories.experiencias')
            ])
            ->where('lang_id_112', session('userLang'))
            ->where('active_111', true)
            ->orderBy('sorting', 'asc')
            ->get();


        // Option 2 - get products
        /*
        $response['products'] = Product::builder()
            ->where('lang_id_112', session('userLang'))
            ->where('active_111', true)
            ->orderBy('sorting', 'asc')
            ->get();
        */


        // Atention, if there are only one category by product, you can use slug category for url product
        $productsCategories = ProductsCategories::builder(session('userLang'))
            ->whereIn('product_id_113', $response['products']->pluck('id_111'))
            ->get();

        // We mapped products, including each category at your product
        $response['products']->map(function ($item, $key) use ($productsCategories) {
            $item->mappedCategory = $productsCategories->where('product_id_113', $item->id_111)->first();
        });
        
        
        // get atachments to products
        $response['attachments'] = Attachment::builder()
            ->where('lang_id', session('userLang'))
            ->where('resource_id', 'market-product')
            ->where('family_id', config('www.attachmentsFamily.productList'))
            ->orderBy('sorting', 'asc')
            ->get()
            ->keyBy('object_id');
        
        return view('www.content.product_list', $response);
    }

    public function getProduct(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $response = [];

        $response['product'] = Product::builder()
            ->where('lang_id_112', session('userLang'))
            ->where('slug_112', $parameters['slug'])
            ->where('active_111', true)
            ->first();

        // get atachments to product
        $response['attachments'] = Attachment::builder()
            ->where('lang_id', session('userLang'))
            ->where('resource_id', 'market-product')
            ->where('object_id', $response['product']->id)
            ->where('family_id', config('www.attachmentsFamily.productSheet'))
            ->orderBy('sorting', 'asc')
            ->get();


        return view('www.content.product', $response);
    }
}