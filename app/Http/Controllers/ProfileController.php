<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\Profile\StoreProfile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        return view('profile.index');
    }

    public function store(StoreProfile $request)
    {
        $user = \user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->has('password') && $request->password != '') {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        session()->forget('user');
        return Reply::success('Profile updated successfully.');
    }

}
