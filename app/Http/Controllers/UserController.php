<?php

namespace App\Http\Controllers;

use App\helpers\SendMail;
use Illuminate\Support\Facades\Auth;
use App\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse|Redirector
     */
    public function index()
    {
        if((int)Auth::id()!==1) {
            dd();
        }
        $users = User::all();

        return view('auth.user', compact('users'));
    }


    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(User $user)
    {
        if(Auth::user()->email == request('email')) {

            $this->validate(request(), [
                'name' => 'required',
                //  'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed'
            ]);

            $user->name = request('name');
            // $user->email = request('email');
            $user->password = bcrypt(request('password'));

            $user->save();

            return back();

        }
        else{

            $this->validate(request(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed'
            ]);

            $user->name = request('name');
            $user->email = request('email');
            $user->password = bcrypt(request('password'));

            $user->save();

            return back();

        }
    }
    public function active_user(int $id){
        if((int)Auth::id()!==1) {
            dd();

//            SendMail::send('mohamed.konate@billetexpress.ml, emmanuel.bama@billetexpress.ml, labs@data-transport.org',$data['name'], $data['email']);

        }

        $user = User::find($id);
        $user->state = $user->state===1?0:1;
        $user->save();

        SendMail::active_mail($user->email,$user->name);

        return back();

//        $users = User::all();
//        return view('auth.user', compact('users'));
    }
    public function reset_user(int $id){
        if((int)Auth::id()!==1) {
            dd();
        }
        $user = User::find($id);
        $user->key_api = md5(uniqid(microtime(), TRUE));
        $user->save();

        return back();

//        $users = User::all();
//        return view('auth.user', compact('users'));
    }
}
