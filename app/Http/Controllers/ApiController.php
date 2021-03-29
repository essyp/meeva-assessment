<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
// use Mail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscription;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use App\Helpers\ResponseHelper;
use App\Models\Newsletter;
use App\Jobs\SendSubscriptionEmail;
use Log;

class ApiController extends Controller
{
    public function subscribe(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'min:7', 'max:80', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'],
            'name' => 'required|string',
        ]);
        if ($validator->fails()){
            return ResponseHelper::responseDisplay(400, $validator->messages()->first(), '');
        }

        if (Newsletter::where('email', $request->email)->where('status', ACTIVE)->exists()) {
            return ResponseHelper::responseDisplay(400, 'Already subscribed!!', '');
        }

        if (Newsletter::where('email', $request->email)->where('status', INACTIVE)->exists()) {
            $item = Newsletter::where('email', $request->email)->update(['status' => ACTIVE, 'name' => $request->name]);
            return ResponseHelper::responseDisplay(200, 'Subscribed Successfully', $item);
        }
        
        $item = new Newsletter();
        $item->name = $request->name;
        $item->email = $request->email;
        if($item->save()){
            SendSubscriptionEmail::dispatch($item);
            Log::info('Dispatched message ' . $item->email);
            // Mail::to($request->email)->send(new Subscription($item));
            // $this->sendSuccessMail("Newsletter Subscription",$request->email,$request->name);
            return ResponseHelper::responseDisplay(200, 'Thanks for subscribing to our newsletter', $item);
        } else {
            return ResponseHelper::responseDisplay(400, 'Error encountered . Please try again', $item);
        }
    }
    
    public function sendSuccessMail($subject,$email,$name){
        $data = array(
                'name'=> $name,
                'email'=> $email,
                'subject'=> $subject,
            );
    
        Mail::send('mails/subscription', $data, function($message)
            use($email,$subject,$name) {
                $message->from('fmogbana@gmail.com', 'Francis Mogbana');
                $message->to($email, $name)->subject($subject);
        });
    }

    public function unSubscribe(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'min:7', 'max:80', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'],
        ]);
        if ($validator->fails()){
            return ResponseHelper::responseDisplay(400, $validator->messages()->first(), '');
        }

        if (Newsletter::where('email', $request->email)->where('status', INACTIVE)->exists()) {
            return ResponseHelper::responseDisplay(400, 'Already unsubscribed!!', '');
        }
        
        if (Newsletter::where('email', $request->email)->exists()) {
            $item = Newsletter::where('email', $request->email)->update(['status' => INACTIVE]);
            if($item){
                return ResponseHelper::responseDisplay(200, 'Unsubscribe Successfully', $item);
            } else {
                return ResponseHelper::responseDisplay(400, 'Error encountered . Please try again', '');
            }
        } else {
            return ResponseHelper::responseDisplay(400, 'Email does not exist on our database', '');
        }
    }

    public function unSubscribeEmail($email){
        if (Newsletter::where('email', $email)->where('status', INACTIVE)->exists()) {
            return ResponseHelper::responseDisplay(400, 'Already unsubscribed!!', '');
        }
        
        if (Newsletter::where('email', $email)->exists()) {
            $item = Newsletter::where('email', $email)->update(['status' => INACTIVE]);
            if($item){
                return ResponseHelper::responseDisplay(200, 'Unsubscribe Successfully', $item);
            } else {
                return ResponseHelper::responseDisplay(400, 'Error encountered . Please try again', '');
            }
        } else {
            return ResponseHelper::responseDisplay(400, 'Email does not exist on our database', '');
        }
    }

    public function allSubscriptions(){
        $data = Newsletter::all();
        if($data){
            return ResponseHelper::responseDisplay(200, 'Operation sucessful', $data);
        } else {
            return ResponseHelper::responseDisplay(400, 'Failed', '');
        }
    }
}
