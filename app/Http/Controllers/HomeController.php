<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function user_token()
    {
        $user = Auth::user();

        // return view('user_token', [
        //     'api_token' => $user->api_token,
        // ]);

        return response()->json([
            'api_token' => $user->api_token,
        ]);
    }
}
