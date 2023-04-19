<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    public function changeProfile(){
        return view('vendorProfile');
    }
    public function updateProfile(Request $request){
        if($request->has('profileEdit')){
            //validate
            $request->validate([
                'user_name' => 'required|string',
                'user_number' => 'required|max:11',
                'user_email' => 'required|email|string',
            ]);
            
            User::whereId(Auth()->user()->id)->update([
                'name' => $request->user_name,
                'number' => $request->user_number,
                'email' => $request->user_email,
            ]);

            return back()->with("message", "Profile Details changed successfully!");

        }else if($request->has('passwordEdit')){
            //validate
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'max:8|required|confirmed',
                'new_password_confirmation' => 'required',
            ]);
            //match old password validate
            if(!\Hash::check($request->old_password, Auth()->user()->password)){
                return redirect()->back()->with('error', 'Password Verification did not match! Failed to update password.');
            }
            //update New Password
            User::whereId(Auth()->user()->id)->update([
                'password' => \Hash::make($request->new_password)
            ]);

            return back()->with("message", "Password changed successfully!");
        }else{

        }
    }
}
