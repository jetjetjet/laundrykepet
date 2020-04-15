<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;

class LoginController extends Controller
{
    public function getLogin(Request $request)
    {
        // Redirects to home if the user is already logged into the application.
        
        if (Auth::check()){
            return redirect('/');
        }
        
        return view('Login.login');
    }

    public function postLogin(Request $request)
    {
        // Redirects to home if the user is already logged into the application.
        if (Auth::check()){
            return redirect('/');
        }
        
        // Validates input.
        $rules = array(
            'username' => 'required|max:100',
            'password' => 'required|max:100'
        );
        $validator = Validator::make($request->all(), $rules);
        // Validation fails?
        if ($validator->fails()){
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
        
		if (!Auth::attempt($request->all())){
            //$request->session()->flash('errorMessages', array(trans('messages.errorInvalidLogin')));
            $request->session()->flash('errorMessages', 'Username atau Password Salah');
            return redirect()
                ->back()
			    ->withInput($request->except('password'));
        };
        $request->session()->put('username', Auth::user()->getUserName());
        $request->session()->put('full_name', Auth::user()->getFullName());
        return redirect()->intended(); 
    }

    public function getLogoff(Request $request)
	{
        $request->session()->flush();
		Auth::logout();
		return redirect('/');
	}
}