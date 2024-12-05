<?php

namespace App\Http\Controllers;

use App\Repositories\CounterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    protected $counterRepository;
    public function __construct(CounterRepository $counterRepository)
    {
        $this->counterRepository = $counterRepository;
    }

    public function profile(): Response {
        $user = Auth::user();

        $counters = $this->counterRepository->getCountersForUser($user->id);

        return Inertia::render('ProfileSummary', [
            'user' => $user,
            'counters' => $counters,
        ]);
    }

    //TODO Gets the counters associated with this user Id
    public function getCounters() {
        $data = $this->counterRepository->getCountersForUser(Auth::id());

        return response()->json($data);
    }

}
