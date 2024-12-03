<?php

namespace App\Repositories;

use App\Models\Kid;
use App\Models\Counter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CounterRepository
{
    /**
     * Create a new Kid and Counter entry.
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createKidAndCounter(array $data) {
        // Get the authenticated user's ID (parent_id)
        $parentId = Auth::id();

        try {
            // Start a transaction to ensure both records are created together or neither
            DB::beginTransaction();

            // Create the new kid entry
            $kid = Kid::create([
                'name' => $data['kidName'],
                'gender' => $data['gender'],
                'birthday' => $data['birthday'],
                'parent_id' => $parentId,
            ]);

            // Create a new entry in the counters table with parent_id, type, and type_id
            Counter::create([
                'name' => $data['counterName'],
                'parent_id' => $parentId,
                'type' => 'kid',  // The type will be 'kid' for this case
                'type_id' => $kid->id,
            ]);

            // Commit the transaction if everything went smoothly
            DB::commit();

            // Return a success message or response
            return response()->json(['message' => 'Kid and counter created successfully'], 201);

        } catch (Exception $e) {
            // Handle any other general exceptions
            DB::rollBack();
            Log::error('Error creating Kid and Counter: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the Kid and Counter'], 500);
        }
    }

    public function deleteCounter($counterId) {
        // Begin a transaction to ensure both deletions happen together
        DB::beginTransaction();

        try {
            // Find the counter by its ID
            $counter = Counter::findOrFail($counterId);

            // If the type is 'kid', delete the associated Kid
            if ($counter->type === 'kid' && $counter->type_id) {
                // Find the associated Kid and delete it
                $kid = Kid::find($counter->type_id);
                if ($kid) {
                    $kid->delete();
                }
            }

            // Delete the counter
            $counter->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Counter and associated Kid deleted successfully.'], 200);

        } catch (\Exception $e) {
            // Rollback the transaction if anything goes wrong
            DB::rollBack();

            Log::error('Error deleting Counter and associated Kid: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while deleting the Counter and Kid.'], 500);
        }
    }


}
