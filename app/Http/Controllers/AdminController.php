<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Mail;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Admin;
use App\Models\ActivityLog;
use App\Models\Newsletter;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function log($action){
        $log = new ActivityLog();
		$log->user_id = Auth::guard("admin")->user()->id;
		$log->type = ADMIN;
		$log->action = $action;
		$log->computer_ip = \Request::ip();
		$log->session_id = \Session::getId();

		$log->save();
    }

    public function index(Request $request){
        $data = Newsletter::orderBy('id','desc')->limit(5)->get();
        $totalSubscription = Newsletter::count();
        $activeSubscription = Newsletter::where('status', ACTIVE)->count();
        $inactiveSubscription = Newsletter::where('status', INACTIVE)->count();
        return view('admin/index', compact('data','totalSubscription','activeSubscription','inactiveSubscription'));
    }
    
    public function allAdminUsers() {
        $admin = Admin::orderBy('id','desc')->get();
        return view('admin/adminusers', compact('admin'));
    }

    //Create a new admin user
	public function createAdmin(Request $request){
       
		$admin = new Admin();
        $admin->fname = $request->fname;
        $admin->lname = $request->lname;
        $admin->tel = $request->tel;
        $admin->email = $request->email;
        $admin->role = $request->role;
        $admin->address = $request->address;
        $admin->status = 1;
        $admin->password = bcrypt($request->password);
        $admin->created_by = Auth::guard("admin")->user()->id;

		if($admin->save()){

            $response = array(
                "status" => "success",
                "message" => "Admin User was created successfully",
            );

            $this->log("Added new admin. Email - ".$request->email);

            return Response::json($response);
        } else {
            $response = array(
                "status" => "Unsuccessfull",
                "message" => "Error creating user. please try again",
            );
            return Response::json($response);
        }
    }

    public function updateAdmin(Request $request){
		$image = $request->file('image');
        if(!is_null($image) && $image != ''){
            $imageName  = time() . '.' . $image->getClientOriginalExtension();
            $path = "images/users";
            $image->move($path, $imageName);
        }

		$admin = Admin::where('id',$request->id)->first();
		$admin->fname = $request->fname;
        $admin->lname = $request->lname;
        $admin->tel = $request->tel;
        $admin->email = $request->email;
        $admin->address = $request->address;
        $admin->role = $request->role;
        if(!is_null($image) && $image != ''){
            $admin->image = $imageName;
        }
		if($admin->save()){

            $response = array(
                "status" => "success",
                "message" => "updated successfully",
            );
            $this->log("Admin user updated account details. Email - ".$request->email);
            return Response::json($response);
        } else {
            $response = array(
                "status" => "unsuccessful",
                "message" => "Error updating account",
            );
            return Response::json($response);
        }

    }

    public function updateAdminRole(Request $request){
		$admin = Admin::where('id',$$request->id)->first();
		$admin->role = $request->role;
        if($admin->save()){

            $response = array(
                "status" => "success",
                "message" => "Role updated successfully",
            );
            $this->log("Admin user role updated. Email - ".$request->email);
            return Response::json($response);
        } else {
            $response = array(
                "status" => "unsuccessful",
                "message" => "Error updating User",
            );
            return Response::json($response);
        }

    }

    public function adminStatus(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|array',
            'id.*' => 'required',
        ]);
        if ($validator->fails()){
            $response = array(
                "status" => "unsuccessful",
                "message" => $validator->messages()->first(),
                );
                return Response::json($response);
        }
        $id = $request->id;
       
		if($request->submit == "active") {
            foreach ($id as $idd) {
                Admin::where('id',$idd)->update(array('status' => ACTIVE));
                $response = array(
                    "status" => "success",
                    "message" => "Operation Successful",
                );
                $log = Admin::where('id',$idd)->first();
                $this->log("activated admin user with email - $log->email");
            }   
        }elseif($request->submit == "inactive"){
            foreach ($id as $idd) {
                Admin::where('id',$idd)->update(array('status' => INACTIVE));
                $response = array(
                    "status" => "success",
                    "message" => "Operation Successful",
                );
                $log = Admin::where('id',$idd)->first();
                $this->log("deactivated admin user with email - $log->email");
            }
        } elseif($request->submit == "delete"){
            foreach ($id as $idd) {
                $log = Admin::where('id',$idd)->first();
                $this->log("deleted admin user with email  - $log->email");
                Admin::where('id',$idd)->delete();
                $response = array(
                    "status" => "success",
                    "message" => "Operation Successful",
                );
                
            }
        }
		return Response::json($response);
    }

    public function getNewsletterSubscriptions() {
        $data = Newsletter::orderBy('id', 'desc')->get();
        return view('admin/newsletter', compact('data'));
    }

    public function getProfile(Request $request){
        $user_key = Auth::guard("admin")->user()->id;
        $admin = admin::where('id',$user_key)->first();
        return view('admin/profile', compact('admin'));
    }

    public function getChangePassword(Request $request){
        $user_key = Auth::guard("admin")->user()->id;
        $admin = admin::where('id',$user_key)->first();
        return view('admin/change-password', compact('admin'));
    }

    public function changePassword(Request $request){
		$user = Auth::guard('admin')->user();
		$old = $request->curpass;
		$newp = $request->newpass;

		if($newp == "" || $old == ""){
			$response = array(
            	'status' => 'error',
            	'message' => 'Empty password field entered',
        	);
        	return Response::json($response);
		}
		elseif(Hash::check($old, $user->password)){
			$user->password = bcrypt($newp);
			$user->save();
			$response = array(
            	'status' => 'success',
            	'message' => 'Password changed successfully',
        	);
			return Response::json($response);
		}
		else{
			$response = array(
            	'status' => 'error',
            	'message' => 'Invalid password entered',
        	);
        	return Response::json($response);
		}
    }
    
    public function resetPassword(Request $request){
        $item = Admin::where('id',$request->id)->first();
        $item->password = bcrypt($request->password);
        if($item->save()){
            $response = array(
                "status" => "success",
                "message" => "Operation Successful",
            );

            $this->log("Change password for admin user with Email - $item->email");
            return Response::json($response); //return status response as json
        } else {
            $response = array(
                "status" => "unsuccessful",
                "message" => "Error encountered. Please try again",
            );
            return Response::json($response); //return status response as json
        }
    }

    public function getLog() {
        $data = ActivityLog::orderBy('id', 'desc')->get();
        return view('admin/log', compact('data'));
    }

}
