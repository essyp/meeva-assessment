<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Mail;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use App\Helpers\ResponseHelper;
use App\Models\Newsletter;

class HomeController extends Controller
{
    public function unSubscribe($email){
        if (Newsletter::where('email', $email)->where('status', INACTIVE)->exists()) {
            $msg = 'Already unsubscribed!!';
            return view('unsubscribe', compact('email','msg'));
        }
        
        if (Newsletter::where('email', $email)->exists()) {
            $item = Newsletter::where('email', $email)->update(['status' => INACTIVE]);
            $msg = 'Unsubscribed Successfully!!';
            return view('unsubscribe', compact('email','msg'));
        } else {
            $msg = 'Email does not exist on our database';
            return view('unsubscribe', compact('email','msg'));
        }
    }
}
