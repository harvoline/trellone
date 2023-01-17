<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\Guard;

class LogoutController extends Controller
{
    // public function logout(Request $request)
    // {
    //     Auth::logout();

    //     return response()->json([
    //         'message' => 'Successfully logged out'
    //     ]);
    // }

    // method for user logout and delete token
    public function logout()
    {
        Auth::user()->token()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
