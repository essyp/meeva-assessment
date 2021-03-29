<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Mail;
use Session;
use Illuminate\Support\Str;
use App\Models\Admin;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $maxAttempts = 5; // change to the max attemp you want.
    protected $decayMinutes = 5;

    private function throttle_seconds() {
        if(session()->has('throttle_seconds')) {
            $throttle_seconds = session('throttle_seconds');
            $throttle_current_time = session('throttle_current_time');
            $throttle_seconds = $throttle_current_time - time();
            if($throttle_seconds < 0) {
                $throttle_seconds = 0;
                $this->forget_throttle_seconds();
            }
        } else {
            session(['throttle_seconds' => ($this->decayMinutes* 60)]);
            session(['throttle_current_time' => (time() + ($this->decayMinutes* 60))]);
            $throttle_seconds = session('throttle_seconds');
        }
        return $throttle_seconds;
    }

    private function forget_throttle_seconds() {
        if(session()->has('throttle_seconds')) {
            session()->forget('throttle_seconds');
            session()->forget('throttle_current_time');
        }
    }

    public function authenticate(Request $request) {
        if ($this->hasTooManyLoginAttempts($request)) {
            $seconds = $this->throttle_seconds();
            $this->fireLockoutEvent($request);
            // $this->sendLockoutResponse($request);
            $response = array("status" => "fail", "message" => "The given data was invalid.\nToo many login attempts. Please try again in {$seconds} seconds.");
            return Response::json($response);
        }
        $validator = Validator::make($request->all(), [
           'email' => 'required|string|max:200',
           'password' => 'required|string'
       ]);

       if ($validator->fails()) {
        $this->incrementLoginAttempts($request);
        $response = array("status" => "fail", "message" => $validator->messages()->first());
        return Response::json($response);
       }
        $email = $request->email;
        $password = $request->password;
        isset($request->remember_me) ? $remember = true : $remember = false;

        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password, 'status' => ACTIVE], $remember)) {
            // The user is active, not suspended, and exists.
            $this->clearLoginAttempts($request);
            $response = array("status" => "success", "message" => "Login Successful");
            return Response::json($response);
        }

        $this->incrementLoginAttempts($request);
        $this->forget_throttle_seconds();
        $response = array("status" => "fail", "message" => "Wrong email or password");
        return Response::json($response);
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        session()->flush();
        return redirect('/admin/login');
        // $response = array("status" => "success", "message" => "Logout Successful");
        // return Response::json($response);
    }
  
}
