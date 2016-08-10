<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\TaxRule;
use Syscover\Pulsar\Models\Attachment;
use Syscover\ShoppingCart\PriceRule;
use Syscover\ShoppingCart\TaxRule as TaxRuleShoppingCart;
use Syscover\ShoppingCart\Facades\CartProvider;
use Syscover\ShoppingCart\Item;

/**
 * Class ShoppingCartController
 * @package App\Http\Controllers
 */

class ShoppingCartController extends Controller
{

    public function showShoppingCart()
    {
        // get cart items from shoppingCart
        $response['cartItems'] = CartProvider::instance()->getCartItems();

        return view('www.content.shopping_cart', $response);
    }

    /**
     * Función que añade un producto al carro de compra
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addShoppingCart(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $product = Product::builder()
            ->where('lang_id_112', session('userLang'))
            ->where('slug_112', $parameters['slug'])
            ->where('active_111', true)
            ->first();

        // get image to shopping cart
        $attachment = Attachment::builder()
            ->where('lang_id', session('userLang'))
            ->where('resource_id', 'market-product')
            ->where('family_id', config('www.attachmentsFamily.productSheet'))
            ->where('object_id', $product->id_111)
            ->first();

        // create a property on product to save image for shopping cart
        $product->shoppingCartImage = $attachment;

        // get tax rule with default parameters
        $taxRules = TaxRule::builder()
            ->where('country_id_103', config('market.taxCountry'))
            ->where('customer_class_tax_id_106', config('market.taxCustomerClass'))
            ->where('product_class_tax_id_107', $product->product_class_tax_id_111)
            ->orderBy('priority_104', 'asc')
            ->get();

        // create taxRule with format for shopping cart
        $taxRulesShoppingCart = [];
        foreach ($taxRules as $taxRule)
        {
            $taxRulesShoppingCart[] = new TaxRuleShoppingCart(
                Lang::has($taxRule->translation_104) ? trans($taxRule->translation_104) : $taxRule->name_104,
                $taxRule->tax_rate_103,
                $taxRule->priority_104,
                $taxRule->sort_order_104
            );
        }

        // Know if product is transportable
        // Options:
        // 1 - downloadable
        // 2 - transportable
        // 3 - transportable_downloadable
        // 4 - service
        $isTransportable = $product->type_id_111 == 2 || $product->type_id_111 == 3? true : false;

        // when get price from product, internally calculate subtotal and total.
        // we don't want save this object on shopping cart, if login user with different prices and add same product, will be different because the product will have different prices
        $optionsProduct = $product;

        // intance row to add product
        CartProvider::instance()->add(new Item($product->id_111, $product->name_112, 1, $product->price_111, $product->weight_111, $isTransportable, $taxRulesShoppingCart,[
            'product' => $optionsProduct
        ]));
        
        return redirect()->route('shoppingCart-' . session('userLang'));
    }

    public function updateShoppingCart(Request $request)
    {
        // check idf exist coupon code
        if($request->has('applyCouponCode'))
        {
            $cartPriceRule = CartPriceRule::builder(session('userLang'))->where('coupon_code_120', 'like', $request->input('applyCouponCode'))->first();

            if($cartPriceRule != null)
            {
                CartProvider::instance()->addCartPriceRule(
                    new PriceRule(
                        $cartPriceRule->name_text_value,
                        $cartPriceRule->description_text_value,
                        $cartPriceRule->discount_type_id_120,
                        $cartPriceRule->free_shipping_120,
                        $cartPriceRule->discount_fixed_amount_120,
                        $cartPriceRule->discount_percentage_120,
                        $cartPriceRule->maximum_discount_amount_120,
                        $cartPriceRule->apply_shipping_amount_120,
                        $cartPriceRule->combinable_120
                    )
                );
            }
            else
            {
                // cupón no existente
            }


            // todo, add code al hitórico de uso
            //CouponLibrary::addCouponCode(CartProvider::instance(), $request->input('applyCode'), user_lang(), auth('crm'));
        }

        $cartItems = CartProvider::instance()->getCartItems();

        foreach($cartItems as $item)
        {
            if(is_numeric($request->input($item->rowId)))
            {
                CartProvider::instance()->setQuantity($item->rowId, (int)$request->input($item->rowId));
            }
        }

        return redirect()->route('shoppingCart-' . session('userLang'));
    }

    public function deleteShoppingCart(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        CartProvider::instance()->remove($parameters['rowId']);

        return redirect()->route('shoppingCart-' . session('userLang'));
    }
}