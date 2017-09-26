<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class Users extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        return User::where('name', 'like', $request->name . '%')
            ->take(10)
            ->orderBy('name')
            ->get();
    }
}
