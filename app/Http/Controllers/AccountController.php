<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Support\Facades\DB;
use App\User;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate($token)
    {
      $user = DB::TABLE('users')->where('activate', $token)->first();

      $pageConfigs = [
        'bodyClass' => "bg-full-screen-image",
        'blankPage' => true
      ];

      return view('/users/activate', [
        'pageConfigs' => $pageConfigs,
        'email' => $user->email
      ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
      $request->validate([
        'password'         => 'required',
        'c_password' => 'required|same:password'           // required and has to match the password field
        ]);

      $user = DB::TABLE('users')->where('email', $request->email)->first();

      $id = $user->id;
      $user = User::find($id);
      $user->password = bcrypt($request->password);
      $user->activate = '';
      $user->save();

      return redirect('/login')->with('message', 'OK');
   }


}
