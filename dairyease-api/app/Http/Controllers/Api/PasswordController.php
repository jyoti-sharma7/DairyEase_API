<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\sendEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; 

class PasswordController extends Controller
{
    public function ForgotPassword(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $otp = rand(100000, 999999);
        $user = User::where('email', '=', $request->email)->update(['remember_token' => $otp]);

        if ($user) {
            // send otp in the email
            $mail_details = [
                'subject' => 'Testing Application API OTP',
                'body' => 'Your OTP is : ' . $otp
            ];

            Mail::to($request->email)->send(new sendEmail($mail_details));

            return response(["status" => 200, "message" => "OTP sent successfully"]);
        } else {
            return response(["status" => 401, 'message' => 'Email Not FOund']);
        }
    }
    public function verifyOtp(Request $request)
    {
        $user = User::where([['remember_token', '=', $request->otp]])->first();
    
        if ($user) {
            User::where('email', '=', $user->email)->update(['remember_token' => null]);
    
            // Create a new token for the verified user
            $accessToken = $user->createToken("API TOKEN")->plainTextToken;
    
            return response([
                "status" => 200,
                "message" => "Success",
                "token" => $accessToken,
                "email" => $user->email ,
            ]);
        } else {
            return response([
                "status" => 401,
                "message" => "Invalid",
            ]);
        }
    }
//     public function verifyOtp(Request $request)
// {
//     $otp = $request->input('otp'); // Get OTP from request

//     $user = User::where([
//         ['remember_token', '=', $otp], // Check OTP
//     ])->first();

//     if ($user) {
//         User::where('email', '=')->update(['remember_token' => null]);

//         // Create a new token for the verified user
//         $accessToken = $user->createToken("API TOKEN")->plainTextToken;

//         return response([
//             "status" => 200,
//             "message" => "Success",
//             "token" => $accessToken,
//             "email" => $email // Include email in response
//         ]);
//     } else {
//         return response([
//             "status" => 401,
//             "message" => "Invalid",
//         ]);
//     }
// }

   
    public function changePassword(Request $request)
    {
        $validatePassword = Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed',
            ]
        );

        if ($validatePassword->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatePassword->errors()
            ], 401);
        }
        $user = auth()->user();
        if ($user) {
            User::where('email', '=', $user->email)->update(['password' => Hash::make($request->password)]);
            $request->user()->currentAccessToken()->delete();
            return response(["status" => 200, "message" => "Successfully Set New Password"]);
        } else {
            return response(["status" => 401, 'message' => 'Authentication failed']);
        }
    }
}
