<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKidCounterRequest;
use App\Http\Requests\StorePregnancyCounterRequest;
use App\Models\Counter;
use App\Models\Kid;
use App\Repositories\CounterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CounterController extends Controller
{
    protected $counterRepository;

    public function __construct(CounterRepository $counterRepository)
    {
        $this->counterRepository = $counterRepository;
    }

    public function getCountersForManageCounters() {
        // Fetch the counters
        $counters = Counter::all();

        // Return them to the Inertia view
        return Inertia::render('ManageCounters', [
            'counters' => $counters
        ]);
    }
    public function getCountersForUser($userId) {

    }

    public function deleteCounter( $counterId) {
     $this->counterRepository->deleteCounter($counterId);
    }


    public function counterForm() {
        return Inertia::render('CounterForm');
    }
    //TODO test
    public function storeKid(StoreKidCounterRequest $request)
    {
        // The incoming request is valid, so you can retrieve validated data
        $validatedData = $request->validated();

        // Use the repository to create a new Kid and Counter
        $this->counterRepository->createKidAndCounter($validatedData);

        return response()->json([
            'message' => 'Counter created successfully',
        ]);
    }

    //TODO
    public function storePregnancy (StorePregnancyCounterRequest $request){
    }
}
