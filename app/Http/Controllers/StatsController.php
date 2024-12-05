<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pregnancy;
use App\Models\Kid;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Get the aggregated stats for users, pregnancies, and children.
     *
     * @return JsonResponse
     */
    public function getAggregatedStats(): JsonResponse
    {
        $usersCount = User::count();
        $pregnanciesCount = Pregnancy::count();
        $childrenCount = Kid::count();

        // Calculate average children per parent
        //! We are calculating based on parent_id not users since users with no children are not parents technicaly
        $averageChildren = Kid::select(DB::raw('count(*) as children_count'))
            ->groupBy('parent_id')
            ->havingRaw('count(*) > 0')
            ->pluck('children_count')
            ->avg();


        return response()->json([
            'users' => $usersCount,
            'pregnancies' => $pregnanciesCount,
            'children' => $childrenCount,
            'averageChildren' => $averageChildren,
        ]);
    }
}
