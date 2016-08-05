<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductsCategories;
use Syscover\Market\Models\TaxRule;
use Syscover\Pulsar\Models\Attachment;
use Syscover\ShoppingCart\Facades\CartProvider;

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




        // Atention!, if there are only one category by product, you can use slug category for url product
        $productsCategories = ProductsCategories::builder(session('userLang'))
            ->whereIn('product_id_113', $response['products']->pluck('id_111'))
            ->get();





        // get product class from all products to calculate taxes
        $productClasses = collect();
        foreach ($response['products'] as $product)
        {
            if($product->product_class_tax_id_111 != null && ! $productClasses->contains($product->product_class_tax_id_111))
                $productClasses->push($product->product_class_tax_id_111);
        }

        // get tax rules from all kind of product to calculate your tax
        // like this, with only one query, get data to calculate tax from all products
        $taxRules = TaxRule::builder()
            ->where('country_id_103', config('market.taxCountry'))
            ->where('customer_class_tax_id_106', config('market.taxCustomerClass'))
            ->whereIn('product_class_tax_id_107', $productClasses->toArray())
            ->orderBy('priority_104', 'asc')
            ->get();





        // We add properties to products, including each category at your product
        $response['products']->transform(function ($product, $key) use ($productsCategories, $taxRules) {
            // add category to create slug
            $product->mappedCategory    = $productsCategories->where('product_id_113', $product->id_111)->first();
            // add tax rules for this product
            $product->taxRules          = $taxRules->where('product_class_tax_id_107', $product->product_class_tax_id_111);
            return $product;
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

    public function checkout()
    {
        $response['cartItems'] = CartProvider::instance()->getCartItems();

        return view('www.content.checkout', $response);
    }
}