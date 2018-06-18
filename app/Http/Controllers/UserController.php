<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
//use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    /**
     * Where to redirect users after updating User
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function update()
    {
        $user = request()->user();

        $this->validate(request(), [
            'gender' => 'required'
        ]);

        $user->gender = request('gender');
        request()->session()->forget('no_gender');
        $user->save();

        return back();
    }

}
