<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class Profiles extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    public function show(User $profileUser)
    {
        $activities = Activity::feed($profileUser);

        return view('profiles.show', compact('profileUser', 'activities'));
    }

    public function uploadAvatar(Request $request)
    {
        $this->validate($request, [
            'avatar' => ['required', 'image']
        ]);

        $user = auth()->user();
        $user->avatar = $request->file('avatar')->store('avatars', 'public');
        $user->save();

        return response([], 204);
    }
}
