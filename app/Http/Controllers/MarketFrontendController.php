<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sermepa\Tpv\Tpv;
use Syscover\Market\Libraries\PayPalLibrary;
use Syscover\Market\Models\Order;
use Syscover\Market\Models\PaymentMethod;
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

    public function getCheckout01()
    {
        $response['cartItems']  = CartProvider::instance()->getCartItems();
        $response['customer']   = auth('crm')->user();

        return view('www.content.checkout_01', $response);
    }

    public function postCheckout01(Request $request)
    {
        $response['cartItems']  = CartProvider::instance()->getCartItems();
        $response['customer']   = auth('crm')->user();

        // set shipping on shopping cart
        CartProvider::instance()->setShipping([
            'name'              => $request->input('name'),
            'surname'           => $request->input('surname'),
            'country'           => $request->input('country'),
            'territorialArea1'  => $request->input('territorialArea1'),
            'territorialArea2'  => $request->input('territorialArea2'),
            'territorialArea3'  => $request->input('territorialArea3'),
            'cp'                => $request->input('cp'),
            'address'           => $request->input('address'),
        ]);

        return redirect()->route('getCheckout02-' . session('userLang'));
    }

    public function getCheckout02()
    {
        $response['cartItems']      = CartProvider::instance()->getCartItems();
        $response['customer']       = auth('crm')->user();
        $response['shipping']       = CartProvider::instance()->getShipping();

        $response['paymentMethods'] = PaymentMethod::builder()
            ->where('lang_id_115', user_lang())
            ->where('active_115', true)
            ->orderBy('sorting_115', 'asc')
            ->get();

        return view('www.content.checkout_02', $response);
    }

    public function postCheckout02(Request $request)
    {
        // create data order
        $orderDate  = date('U');
        $customer   = auth('crm')->user();

        // create order
        $orderAux = [
            'date_116'                          => $orderDate,
            'date_text_116'				        => date(config('pulsar.datePattern') . ' H:i', $orderDate),
            'status_id_116'                     => 1, // Outstanding
            'ip_116'                            => $request->ip(),  // customer IP
            'payment_method_id_116'             => $request->input('paymentMethod'),
            'comments_116'                      => null,

            'has_gift_116'                      => false,
            'gift_from_116'                     => null,
            'gift_to_116'                       => null,
            'gift_message_116'                  => null,

            'subtotal_116'                      => CartProvider::instance()->subtotal(),
            'shipping_116'                      => CartProvider::instance()->hasFreeShipping()? 0 :  CartProvider::instance()->getShippingAmount(),
            'row_discount_amount_116'           => 0,
            'total_discount_percentage_116'     => 0,
            'total_discount_amount_116'         => CartProvider::instance()->discount(),
            'tax_amount_116'                    => 0,
            'total_116'                         => CartProvider::instance()->total(),

            'customer_id_116'                   => $customer->id_301,
            'customer_company_116'              => $customer->company_301,
            'customer_tin_116'                  => $customer->tin_301,
            'customer_name_116'                 => $customer->name_301,
            'customer_surname_116'              => $customer->surname_301,
            'customer_email_116'                => $customer->email_301,
            'customer_phone_116'                => $customer->phone_301,
            'customer_mobile_116'               => $customer->mobile_301,

            'invoice_country_id_116'            => $customer->country_id_301,
            'invoice_territorial_area_1_id_116' => $customer->territorial_area_1_id_301,
            'invoice_territorial_area_2_id_116' => $customer->territorial_area_2_id_301,
            'invoice_territorial_area_3_id_116' => $customer->territorial_area_3_id_301,
            'invoice_cp_116'                    => $customer->cp_301,
            'invoice_locality_116'              => $customer->locality_301,
            'invoice_address_116'               => $customer->address_301,
            'invoice_latitude_116'              => $customer->latitude_301,
            'invoice_longitude_116'             => $customer->longitude_301,
            'has_invoice_116'                   => $request->has('hasInvoice'),
            'invoiced_116'                      => false,

            // comprobamos si hay envío que realizar
            'has_shipping_116'                  => $request->input('dataShipping') == 'diff' || $request->input('dataShipping') == 'same'? true : false
        ];

        // create order in database
        $order = Order::create($orderAux);



        // Redsys Payment
        if($request->input('paymentMethod') === '1')
        {

        }
        // PayPal Payment
        elseif($request->input('paymentMethod') === '2')
        {
            $this->throwPayPalPaymentMethod($order);
        }
    }

    private function throwRedsysPaymentMethod(Order $order)
    {
        try
        {
            $redsys = new Tpv();
            $redsys->setAmount($order->total_116);
            $redsys->setOrder(config('market.orderIdPrefix') . $order->id_116);
            $redsys->setMerchantcode(config('market.redSysEnviroment') == 'live' ? config('market.redSysLiveMerchantCode') : config('market.redSysTestMerchantCode'));
            $redsys->setCurrency('978');
            $redsys->setTransactiontype('0');
            $redsys->setTerminal('1');

            $redsys->setUrlOk(route('redsysPaymentResponseOk'));
            $redsys->setUrlKo(route('redsysPaymentResponseNook'));
            $redsys->setVersion('HMAC_SHA256_V1');
            $redsys->setTradeName(config('market.redSysEnviroment') == 'live'? config('market.redSysLiveMerchantName') : config('market.redSysTestMerchantName'));
            $redsys->setTitular($order->customer_name_116 . ' ' . $order->customer_surname_116);
            $redsys->setProductDescription(trans('web.redsysProductDescription'));
            $redsys->setEnviroment(config('market.redSysEnviroment'));

            // signature SHA256
            $signature = $redsys->generateMerchantSignature(config('market.redSysEnviroment') == 'live'? config('market.redSysLiveKey') : config('market.redSysTestKey'));
            $redsys->setMerchantSignature($signature);

            Order::setOrderLog($order->id_116, trans('market::pulsar.message_customer_go_to_tpv'));

            return response()->json([
                'status'    => 'success',
                'redsys'    => $redsys->createForm()
            ]);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $form;

    }

    private function throwPayPalPaymentMethod(Order $order)
    {
        Order::setOrderLog($order->id_116, trans('market::pulsar.message_customer_go_to_paypal'));

        return response()->json([
            'status'        => 'success',
            'order'         => $order,
            'payPal'        => PayPalLibrary::createForm($order->id_116)
        ]);
    }
}