<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Set the user as an admin.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function setAsAdmin(User $user)
    {
        $user->update(['is_admin' => true]);

        return response()->json(['message' => 'User set as admin successfully'], 200);
    }
}
