<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterController extends Controller
{
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    protected function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->get('name'), //$data['name'],
            'email' => $request->get('email'), //$data['email'],
            'password' => Hash::make($request->get('password')), //Hash::make($data['password']),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        $user->api_token = $token;
        $user->save();

        Auth::login($user);

        return response()->json([
            'name' => $user->name,
            'token' => $token,
            'user_id' => $user->id,
        ]);
    }
}
