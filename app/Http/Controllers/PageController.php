<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\User;
use Illuminate\Http\Request;
use App\Event;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function home()
    {
            return view('server.login');
    }

    public function logout()
    {
        session()->forget('user');

        return redirect('/');
    }

    public function loginUser(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user =  User::where('usr_user_name', '=', $username)->where('usr_password', '=', $password)->first();

        if($user)
        {
            session(['user' => $user]);

            return redirect('events');
        }

        session()->flash('badauth', 'Username or password incorrect.');

        return redirect('/');
    }

    public function overview(Request $request)
    {
        return view('server.overview');
    }
}
