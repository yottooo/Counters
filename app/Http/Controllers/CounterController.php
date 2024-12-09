<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKidCounterRequest;
use App\Http\Requests\StorePregnancyCounterRequest;
use App\Models\Counter;
use App\Repositories\CounterRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CounterController extends Controller {
    protected CounterRepository $counterRepository;

    public function __construct(CounterRepository $counterRepository) {
        $this->counterRepository = $counterRepository;
    }

    public function getCountersForManageCounters(): \Inertia\Response {
        return Inertia::render('ManageCounters', [
            'counters' => Counter::where('parent_id', Auth::id())->get()
        ]);
    }


    public function deleteCounter($counterId): JsonResponse {
       return $this->counterRepository->deleteCounter($counterId);
    }


    public function counterForm($id = null): \Inertia\Response {
        if ($id) {
            $item = $this->counterRepository->getCounter($id);

            return Inertia::render('CounterForm', [
                'item' => $item
            ]);
        } else {
            return Inertia::render('CounterForm');
        }
    }

    public function storeKid(StoreKidCounterRequest $request): JsonResponse {

        $validatedData = $request->validated();

        $this->counterRepository->createKidAndCounter($validatedData);

        return response()->json([
            'message' => 'Counter created successfully',
        ]);
    }

    public function updateKid(StoreKidCounterRequest $request, int $id): JsonResponse {
        $validatedData = $request->validated();

        return $this->counterRepository->updateKidAndCounter($id, $validatedData);
    }


    public function storePregnancy(StorePregnancyCounterRequest $request): JsonResponse {

        $validatedData = $request->validated();

        return $this->counterRepository->createPregnancyAndCounter($validatedData);
    }

    public function updatePregnancy(StorePregnancyCounterRequest $request, int $id): JsonResponse {

        $validatedData = $request->validated();

        return $this->counterRepository->updatePregnancyAndCounter($id, $validatedData);
    }
}
