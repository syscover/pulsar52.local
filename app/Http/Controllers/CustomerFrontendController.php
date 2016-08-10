<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Syscover\Crm\Libraries\CustomerLibrary;
use Syscover\Crm\Models\Group;

/**
 * Class CustomerFrontendController
 * @package App\Http\Controllers
 */

class CustomerFrontendController extends Controller
{
    public function account()
    {
        $response['groups']     = Group::builder()->get();
        $response['customer']   = auth('crm')->user();

        return view('www.content.account', $response);
    }

    public function login()
    {
        $response = [];
        return view('www.content.login', $response);
    }

    public function getSingIn()
    {
        // get customer groups
        $response['groups'] = Group::builder()->get();

        return view('www.content.sing_in', $response);
    }

    public function postSingIn(Request $request)
    {
        // automatic validate
        $this->validate($request, [
            'name'      => 'required|max:255',
            'surname'   => 'required|max:255',
            'email'     => 'required|max:255|email|unique:009_301_customer,email_301',
            'password'  => 'required|between:4,15|same:repassword',
        ]);

        // manual validate
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'surname'   => 'required|max:255',
            'email'     => 'required|max:255|email|unique:009_301_customer,email_301',
            'password'  => 'required|between:4,15|same:repassword',
        ]);
        if ($validator->fails())
        {
            return redirect(route(''))
                ->withErrors($validator)
                ->withInput();
        }

        // create new customer
        $customer =  CustomerLibrary::createCustomer($request);

        // auth the customer created
        Auth::guard('crm')->login($customer);

        return redirect(route('account-' . user_lang()));
    }

    public function putSingIn(Request $request)
    {
        $rules   = [
            'name'      => 'required|max:255',
            'surname'   => 'required|max:255',
            'email'     => 'required|max:255|email|unique:009_301_customer,email_301',
            'password'  => 'required|between:4,15|same:repassword',
        ];

        if($request->input('email') == auth('crm')->user()->email_301)
            $rules['email'] = 'required|max:255|email';

        if(! $request->has('password'))
            $rules['password'] = '';

        $this->validate($request, $rules);

        // update customer
        $customer = CustomerLibrary::updateCustomer($request);

        // update password
        if($request->has('password'))
            CustomerLibrary::updatePassword($request);



        // auth the customer created
        Auth::guard('crm')->login($customer);

        return redirect(route('account-' . user_lang()));
    }
}