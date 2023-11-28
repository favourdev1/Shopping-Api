<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function showProfile(){
        $user= auth()->user();
        return response()->json(['user'=>$user]);
    }


    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'firstname' => 'string|max:255',
            'lastname' => 'string|max:255',
            'profile_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'string|max:255',
            'phone_number' => 'string|max:20',
            'country' => 'string|max:30',
            'city' => 'string|max:30',
      

        ]);

        $user->update($request->all());

        return response()->json([
            'status'=>'success',
            'message' => 'Profile updated successfully'],200);
    }
}
