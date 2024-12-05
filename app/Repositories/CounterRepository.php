<?php

namespace App\Repositories;

use App\Models\Fetus;
use App\Models\Kid;
use App\Models\Counter;
use App\Models\Pregnancy;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CounterRepository
{
    /**
     * Create a new Kid and Counter entry.
     *
     * @param array $data
     * @return JsonResponse
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

    /**
     * Create a new Kid and pregnancy entry.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createPregnancyAndCounter(array $data): JsonResponse {
        // Get the authenticated user's ID (parent_id)
        $parentId = Auth::id();

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the Pregnancy record
            $pregnancy = Pregnancy::create([
                'termin_date' => $data['dueDate'],
                'parent_id' => $parentId,
            ]);

            // Create the Counter record
            $counter = Counter::create([
                'name' => $data['counterName'],
                'parent_id' => $parentId,
                'type' => $data['type'], // The type will be 'pregnancy'
                'type_id' => $pregnancy->id, // Reference the pregnancy ID
            ]);

            // Insert each kid into the Fetus table
            foreach ($data['kids'] as $kid) {
                Fetus::create([
                    'pregnancy_id' => $pregnancy->id,
                    'gender' => $kid['gender'],
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'message' => 'Pregnancy and counter created successfully.',
                'counter' => $counter,
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Return an error response
            return response()->json([
                'message' => 'Failed to create pregnancy and counter.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateKidAndCounter(int $id, array $data): JsonResponse {
        $parentId = Auth::id();

        try {
            DB::beginTransaction();

            // Find the Counter by ID and ensure it belongs to the authenticated user
            $counter = Counter::where('id', $id)->where('parent_id', $parentId)->firstOrFail();

            // If the type was 'pregnancy', handle deletion of related pregnancy and fetuses
            if ($counter->type === 'pregnancy') {
                // Delete the related fetuses from the Fetus table
                Fetus::where('pregnancy_id', $counter->type_id)->delete();

                // Delete the related pregnancy from the Pregnancy table
                Pregnancy::where('id', $counter->type_id)->delete();
            }

            // Update the Counter name
            $counter->update([
                'name' => $data['counterName'],
            ]);

            // If the type is 'kid', find the related Kid and update it
            if ($counter->type === 'kid') {
                $kid = Kid::findOrFail($counter->type_id);
                $kid->update([
                    'name' => $data['kidName'],
                    'gender' => $data['gender'],
                    'birthday' => $data['birthday'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Kid and counter updated successfully'], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Counter or Kid not found'], 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating Kid and Counter: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the Kid and Counter'], 500);
        }
    }

    public function updatePregnancyAndCounter(int $id, array $data): JsonResponse {
        $parentId = Auth::id();

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Retrieve the Pregnancy record by its ID
            $pregnancy = Pregnancy::where('id', $id)
                ->where('parent_id', $parentId)
                ->first();

            // Retrieve the Counter record by the same ID (or update if needed)
            $counter = Counter::where('id', $id)
                ->where('parent_id', $parentId)
                ->first();

            // If either record is not found, return an error response
            if (!$pregnancy || !$counter) {
                return response()->json([
                    'message' => 'Pregnancy or Counter not found.',
                ], 404);
            }

            // Check if the previous type of Counter was 'kid'
            if ($counter->type === 'kid') {
                // Delete the related Kid record based on type_id
                Kid::where('id', $counter->type_id)->delete();
            }


            // Update the Pregnancy record
            $pregnancy->update([
                'termin_date' => $data['dueDate'],
            ]);

            // Update the Counter record (the same ID will be used)
            $counter->update([
                'name' => $data['counterName'],
                'type' => 'pregnancy', // Ensure it's updated to 'pregnancy'
                'type_id' => $pregnancy->id, // Reference the pregnancy ID
            ]);

            // Update the fetuses (kids) in the Fetus table
            foreach ($data['kids'] as $kid) {
                // Update existing fetuses or create new ones if needed
                Fetus::updateOrCreate(
                    ['pregnancy_id' => $pregnancy->id, 'gender' => $kid['gender']],
                    ['gender' => $kid['gender']] // Example of how to update (adjust as needed)
                );
            }

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'message' => 'Pregnancy and counter updated successfully.',
                'counter' => $counter,
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            Log::error('Error updating Pregnancy and Counter: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => 'Failed to update pregnancy and counter.',
                'error' => $e->getMessage(),
            ], 500);
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
            //TODO if type is pregnancy delete form preg table and fetus table

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

    /**
     * Get a counter by ID with the appropriate relationships loaded.
     *
     * @param int $id
     * @return Counter|null
     */
    public function getCounter(int $id): ?Counter {
        $type = Counter::where('id', $id)->value('type');

        return Counter::where('id', $id)
            ->when(
                $type === 'kid',
                fn ($query) => $query->with('kid'),
                fn ($query) => $query->with('pregnancy.fetuses')
            )
            ->first();
    }

    /**
     * Get all counters for a user with the appropriate relationships loaded.
     *
     * @param int $userId
     * @return Collection
     */
    public function getCountersForUser(int $userId): Collection {
        return Counter::where('parent_id', $userId)
            ->get()
            ->each(function ($counter) {
                if ($counter->type === 'kid') {
                    $counter->load('kid');
                } elseif ($counter->type === 'pregnancy') {
                    // Load pregnancy and fetuses relationship
                    $counter->load('pregnancy.fetuses');

                    // Add 'days_left' as an attribute of the pregnancy model
                    $counter->pregnancy->days_left = $counter->pregnancy->days_left;
                }
            });
    }
}
