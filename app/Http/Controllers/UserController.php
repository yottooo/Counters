<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function profile(): \Inertia\Response {
        return Inertia::render('ProfileSummary', [
            'user' => Auth::user()
        ]);
    }

    //TODO Gets the counters associated with this user Id
    public function getCounters() {

    }

}
