<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request){
        $incomingFields = $request -> validate([
            'login_name' => 'required',
            'login_password' => 'required'
        ]);

        if (auth()-> attempt(['name' => $incomingFields['login_name'], 'password' => $incomingFields['login_password']])){
            $request -> session()-> regenerate();

        }
        return redirect('/');

    }
    public function logout(){
        auth()->logout();
        return redirect('/');
    }

    public function register(Request $request){
        $incomingFields = $request -> validate([
            'name' => ['required', 'min:3', 'max:10', Rule::unique ('users','name')], // or as simple 'required',
            'email' => ['required', 'email', Rule::unique ('users', 'email')], // or a simple 'required',
            'password' => ['required', 'min:8', 'max:200'] // or a simple 'required'
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/');
    }
}
