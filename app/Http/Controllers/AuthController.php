<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function registerPage()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                notify()->warning($validator->errors()->first());
                return redirect()->back()->withInput();
            }
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            notify()->emotify('success', 'Your Account successfully created');
            return redirect()->route('user.login');
        } catch (\Exception $e) {
            notify()->warning('Something Went Wrong, Please Try Later :(');
        }
    }
    public function loginUser(Request $request)
    {
        try {
            $credentials = ['email' => $request->email, 'password' => $request->password];

            if (Auth::guard('user')->attempt($credentials)) {
                notify()->emotify('success', 'Welcome To My Item, Buy It Now Using PayPal :)');
                $item['item_description'] = ' Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi, labore officia libero, magni dolores laboriosam nisi atque omnis qui impedit saepe accusamus deleniti, voluptas doloribus provident. Debitis, non! Suscipit, ipsa?        ';
                $item['item_name'] = 'Nike Shoes';
                $item['item_price'] = 10;
                $item['item_currency'] = 'USD';
                $item['item_title'] = 'Nike Shoes ';
                $item['item_qty'] = 1;
                $item['item_image'] = asset('assets/shoes.png');
                return view('payment.item', compact('item'));
            }
            notify()->warning('Your credentials Is Not Rights :(');
            return view('auth.login');
        } catch (\Exception $e) {
            notify()->warning('Something Went Wrong, Please Try Later :(');
            return view('auth.login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('user.login');
    }
}
