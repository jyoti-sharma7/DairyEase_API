<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use File;

class ProfileController extends Controller
{
    public function update_profile(Request $request)
    {
        //validating data
        $validator = Validator::make($request->all(),[
            'profile_photo'=>'nullable|image|mimes:jpg,bmp,png'
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Validations fails',
                'errors'=>$validator->errors()
            ],422);
        }

        $user=$request->User();
//if user has already photo then delete old photo
        if($request->hasFile('profile_photo')){
            if($user->profile_photo){
                $old_path=public_path().'/uploads/profile_images/'
                .$user->profile_photo;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }
            //if user upload and not upload in(else) profile photo
            $image_name='profile-image-'.time().'.'.$request-> 
            profile_photo->extension();
            $request->profile_photo->move(public_path('/uploads/profile_images'),$image_name);
        }
        else{
            $image_name=$user->profile_photo;
        }
        $user->update([
            'profile_photo'=>$image_name
        ]);
        return response()->json([
            'message'=>'profile updated successfully',
        ],200);
    }
}
