<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Syscover\Market\Models\GroupCustomerClassTax;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\TaxRule;
use Syscover\Pulsar\Models\Package;
use Syscover\ShoppingCart\Cart;
use Syscover\ShoppingCart\CartItemTaxRules;
use Syscover\ShoppingCart\Facades\CartProvider;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Route to get login form
     *
     * @var string
     */
    protected $loginPath;

    /**
     * Redirect route after logout
     *
     * @var string
     */
    protected $logoutPath;

    /**
     * Here you can customize your guard, this guar has to set in auth.php config
     *
     * @var string
     */
    protected $guard;


    public function __construct()
    {
        $this->redirectTo   = route('account-' . user_lang());
        $this->loginPath    = route('login-' . user_lang());
        $this->logoutPath   = route('home-' . user_lang());
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'user'      => 'required',
            'password'  => 'required',
        ]);

        $credentials = [
            'user_301'  => $request->input('user'),
            'password'  => $request->input('password')
        ];

        if(auth('crm')->attempt($credentials, $request->has('remember')))
        {
            // check if customer is active
            if(! auth('crm')->user()->active_301)
            {
                auth('crm')->logout();

                // error user inactive
                if($request->input('responseType') == 'json')
                {
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'User inactive'
                    ]);
                }
                else
                {
                    return redirect($this->loginPath)->withErrors([
                        'message'   => 'User inactive'
                    ])->withInput();
                }
            }

            // set customer class tax if market package is installed
            $marketPackage = Package::builder()->find(12);
            if($marketPackage != null && $marketPackage->active_012 == true)
            {
                $groupCustomerClassTax = GroupCustomerClassTax::builder()->where('group_id_102', auth('crm')->user()->group_id_301)->first();

                if($groupCustomerClassTax != null)
                    auth('crm')->user()->classTax = $groupCustomerClassTax->id_100;
            }

            // Authentication OK!
            // Reload Shopping cart with new tax rules
            if(CartProvider::instance()->getCartItems()->count() > 0)
            {
                $cartProducts = Product::builder()
                    ->whereIn('id_111', CartProvider::instance()->getCartItems()->pluck('id'))
                    ->get();

                $taxRules = TaxRule::builder()
                    ->where('country_id_103', empty(auth('crm')->user()->country_id_301)? config('market.taxCountry') : auth('crm')->user()->country_id_301)
                    ->where('customer_class_tax_id_106', empty(auth('crm')->user()->classTax)? config('market.taxCustomerClass') : auth('crm')->user()->classTax)
                    ->whereIn('product_class_tax_id_107', $cartProducts->pluck('product_class_tax_id_111')->toArray())
                    ->orderBy('priority_104', 'asc')
                    ->get();

                $taxRules = $taxRules->groupBy('product_class_tax_id_107')
                    ->map(function($taxRule, $key){
                        return $taxRule->sortBy('priority_104');
                    });

                foreach (CartProvider::instance()->getCartItems() as $item)
                {
                    // reset tax rules from item
                    $item->taxRules = new CartItemTaxRules();

                    // if there ara any tax rule, and product with tax rule
                    if($taxRules->count() > 0 && $cartProducts->where('id_111', $item->id)->count() > 0 && $taxRules->get($cartProducts->where('id_111', $item->id)->first()->product_class_tax_id_111)->count() > 0)
                    {
                        // get tax rules from item
                        $itemTaxRules = $taxRules->get($cartProducts->where('id_111',$item->id)->first()->product_class_tax_id_111);

                        // add tax rules to item
                        foreach ($itemTaxRules as $itemTaxRule)
                        {
                            $item->addTaxRule($itemTaxRule->getTaxRuleShoppingCart());
                        }
                    }
                    // force to calculate amounts
                    $item->calculateAmounts(Cart::PRICE_WITHOUT_TAX);
                }
            }

            if($request->input('responseType') == 'json')
            {
                return response()->json([
                    'status'    => 'success',
                    'customer'  => auth('crm')->user()
                ]);
            }
            else
            {
                return redirect()->intended($this->redirectTo);
            }
        }

        // error authentication
        if($request->input('responseType') == 'json')
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User or password incorrect'
            ]);
        }
        else
        {
            return redirect($this->loginPath)->withErrors([
                'message' => 'User or password incorrect'
            ])->withInput();
        }
    }

    /**
     * Logout user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth('crm')->logout();

        // todo revisar!!! no funciona
        // Reload Shopping cart with default tax rules
        if(CartProvider::instance()->getCartItems()->count() > 0)
        {
            $cartProducts = Product::builder()
                ->whereIn('id_111', CartProvider::instance()->getCartItems()->pluck('id'))
                ->get();

            $taxRules = TaxRule::builder()
                ->where('country_id_103', env('TAX_COUNTRY'))
                ->where('customer_class_tax_id_106', env('TAX_CUSTOMER_CLASS'))
                ->whereIn('product_class_tax_id_107', $cartProducts->pluck('product_class_tax_id_111')->toArray())
                ->orderBy('priority_104', 'asc')
                ->get();

            $taxRules = $taxRules->groupBy('product_class_tax_id_107')
                ->map(function($taxRule, $key){
                    return $taxRule->sortBy('priority_104');
                });

            foreach (CartProvider::instance()->getCartItems() as $item)
            {
                // reset tax rules from item
                $item->resetTaxRules();

                // if there ara any tax rule, and product with tax rule
                if($taxRules->count() > 0 && $cartProducts->where('id_111', $item->id)->count() > 0 && $taxRules->get($cartProducts->where('id_111', $item->id)->first()->product_class_tax_id_111)->count() > 0)
                {
                    // get tax rules from item
                    $itemTaxRules = $taxRules->get($cartProducts->where('id_111',$item->id)->first()->product_class_tax_id_111);

                    // add tax rules to item
                    foreach ($itemTaxRules as $itemTaxRule)
                    {
                        $item->addTaxRule($itemTaxRule->getTaxRuleShoppingCart());
                    }
                }
                // force to calculate amounts
                $item->calculateAmounts(Cart::PRICE_WITHOUT_TAX);
            }
        }

        return redirect($this->logoutPath);
    }
}
