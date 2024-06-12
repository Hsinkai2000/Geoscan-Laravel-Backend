<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerPost(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Register successfully');
    }

    public function login()
    {
        return view("web.login");
    }

    public function loginPost(Request $request)
    {
        $user_params = $request->only((new User)->getFillable());
        debug_log('user', [$user_params]);

        if (Auth::attempt(['username' => $user_params['username'], 'password' => $user_params['password']])) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Login success');
        };

        back()->with('error', 'username or Password invalid');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
