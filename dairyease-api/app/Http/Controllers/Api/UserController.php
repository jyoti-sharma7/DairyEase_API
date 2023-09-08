<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //REGISTER API
    public function register(Request $request){
      //  validation
              $request->validate([
                "name"=>"required",
                "email"=>"required|email|unique:users",
                "contact"=>"required|numeric",
                "address"=>"required",
                "password"=>"required|confirmed|min:8"
              ]);
        $user=new User();
        $user->name= $request->name;
        $user->email= $request->email;
        $user ->contact = isset($request->contact)?$request->contact:"";
        $user->address= $request->address;
        $user->password=Hash::make($request->password);
        $otp=$this->otp();
        $user->otp_code=$otp;
        if($user->save()){
            Mail::to($user->email)->send(new VerificationMail($user,$otp));
            return [
                'message'=>"user Created, Please Check your email to verify your email.",
                'user'=>$user->email
            ];
        }else{
            return ['message'=>"User not Created"];
        }
}
 
//LOGIN API
public function login(Request $request){
    //validation
    $request->validate([
        "email" => "required|email",
        "password" => "required|min:8"
    ]);
    
// Check user
    $user = User::where("email", "=", $request->email)->first();
    
    if ($user) {
        if ($user->email_verified_at === null) {
            return response()->json([
                "status" => 0,
                "message" => "Email not verified"
            ], 401);
        }
        if (Hash::check($request->password, $user->password)) {
            //create a token
            $token = $user->createToken("auth_token")->plainTextToken;
            //send a response
            return response()->json([
                "status" => 1,
                "message" => "User logged in successfully",
                "access_token" => $token
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Password didn't match"
            ], 404);
        }
    } else {
        return response()->json([
            "status" => 0,
            "message" => "User not found"
        ], 404);
    }
}


    //PROFILE API
    public function profile(){
        return response()->json([
            "status"=>1,
            "message"=>"User profile information",
            "data"=>auth()->user()
        ]);
        
    }
   // LOGOUT API
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status"=>1,
            "message"=>"User logged out successfully"
        ]);
        
    }
    public function otp(){
        $otp = rand(100000,999999);
        return $otp;
    }
   // OTP Verification
   public function verifyOtp(Request $request)
   {
       $request->validate([
           'otp' => 'required|digits:6'
       ]);
       $user = User::where('otp_code', $request->otp)->first();
       if ($user) {
           $user->email_verified_at = now();
           $user->otp_code = null; // Clear OTP after verification
           $user->save();

           return [
               'message' => 'Email verified successfully.',
           ];
       } else {
           return [
               'message' => 'Invalid OTP or user not found.'
           ];
       }
   }
}
