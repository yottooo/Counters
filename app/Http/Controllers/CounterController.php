<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKidCounterRequest;
use App\Http\Requests\StorePregnancyCounterRequest;
use App\Models\Counter;
use App\Models\Kid;
use App\Repositories\CounterRepository;
use Illuminate\Http\JsonResponse;
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

    public function getCountersForManageCounters(): \Inertia\Response {
        // Fetch the counters
        $counters = Counter::all();

        // Return them to the Inertia view
        return Inertia::render('ManageCounters', [
            'counters' => $counters
        ]);
    }


    public function deleteCounter( $counterId) {
     $this->counterRepository->deleteCounter($counterId);
    }


    public function counterForm($id=null) {
        if($id){
            $item = $this->counterRepository->getCounter($id);

            return Inertia::render('CounterForm', [
                'item' =>  $item
            ]);
        } else{
        return Inertia::render('CounterForm');
        }
    }

    public function storeKid(StoreKidCounterRequest $request): JsonResponse {
        // The incoming request is valid, so you can retrieve validated data
        $validatedData = $request->validated();

        // Use the repository to create a new Kid and Counter
        $this->counterRepository->createKidAndCounter($validatedData);

        return response()->json([
            'message' => 'Counter created successfully',
        ]);
    }
    public function updateKid(StoreKidCounterRequest $request,int $id): JsonResponse
    {
        $validatedData = $request->validated();

        $this->counterRepository->updateKidAndCounter($id, $validatedData);
        /*TODO when updating check if pregnancy exists for this id
        and delete form pregnancy and fetuses table for this counter id
        */

        return response()->json([
            'message' => 'Counter updated successfully',
        ], 200);
    }


    public function storePregnancy (StorePregnancyCounterRequest $request){

        $validatedData = $request->validated();

        return $this->counterRepository->createPregnancyAndCounter($validatedData);

//        return response()->json([
//            'message' => 'Counter created successfully',
//        ]);

    }

    public function updatePregnancy (StorePregnancyCounterRequest $request,int $id){

        $validatedData = $request->validated();

        return $this->counterRepository->updatePregnancyAndCounter($id, $validatedData);
        /*TODO when updating check if kid exists for this id
        and delete from kid and fetuses table for this counter id
        */



    }
}
