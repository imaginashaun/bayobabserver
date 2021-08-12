<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AppleSigninController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return Socialite::driver("sign-in-with-apple")
            ->scopes(["name", "email"])
            ->redirect();
    }

    public function callback(Request $request)
    {
        // get abstract user object, not persisted
      $user = Socialite::driver("sign-in-with-apple")
            ->user();




              //$newuser = User::create(['name'=>$user->getName(), 'email'=>$user->getEmail()]);

              $name = "No Name";
              if($user->getName() != ""){
                  $name = $user->getName();
              }else if($user->getNickname() != ""){
                  $name = $user->getNickname();
              }


        $newuser = User::where('email', '=', $user->getEmail())->first();
        if ($newuser === null) {
            $newuser = new User();
            $newuser->name = $name;
            $newuser->email = $user->getEmail();
            $newuser->provider_id = $user->getId();
            $newuser->provider = "apple";

            $newuser->save();
            $newuser = User::where('email', '=', $user->getEmail())->first();
        }



             if( Auth::loginUsingId($newuser->id, true)) {
              //   redirectUrl('./');
                 return redirect()->route('home');

             }

//        echo $newuser->id;


        //print_r($newuser);

        //die();

        // or use Socialiter to automatically manage user resolution and persistence
  /*     $user = Socialiter::driver("sign-in-with-apple")
            ->login();
*/

    }
}