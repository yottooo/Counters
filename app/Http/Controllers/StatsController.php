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
     * Retrieve aggregated statistics for users, pregnancies, and children.
     *
     * This method provides the following statistics:
     * - Total number of users
     * - Total number of pregnancies
     * - Total number of children
     * - Average number of children per parent (only considering parents with at least one child)
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing aggregated statistics.
     *
     * Example Response:
     * {
     *     "users": 150,
     *     "pregnancies": 45,
     *     "children": 120,
     *     "averageChildren": 2.4
     * }
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

    /**
     * Retrieve statistics of kids grouped by age ranges.
     *
     * The age ranges are as follows:
     * - Below 1 year
     * - Between 1 and 6 years
     * - Between 7 and 18 years
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing the age groups and counts.
     *
     * Example Response:
     * [
     *     { "age_group": "Below 1", "count": 5 },
     *     { "age_group": "1-6", "count": 10 },
     *     { "age_group": "7-18", "count": 8 },
     * ]
     */
    public function getKidsByAgeStats(): JsonResponse
    {
        // Retrieve kids and group them into age ranges, excluding those above 18
        $ageGroups = Kid::select(DB::raw("
        CASE
            WHEN (strftime('%Y', 'now') - strftime('%Y', birthday)) < 1 THEN 'Below 1'
            WHEN (strftime('%Y', 'now') - strftime('%Y', birthday)) BETWEEN 1 AND 6 THEN '1-6'
            WHEN (strftime('%Y', 'now') - strftime('%Y', birthday)) BETWEEN 7 AND 18 THEN '7-18'
        END as age_group,
        COUNT(*) as count
    "))
            ->whereRaw("(strftime('%Y', 'now') - strftime('%Y', birthday)) <= 18") // Exclude kids older than 18
            ->groupBy('age_group')
            ->orderByRaw("CASE age_group
                    WHEN 'Below 1' THEN 1
                    WHEN '1-6' THEN 2
                    WHEN '7-18' THEN 3
                  END")
            ->get();

        return response()->json($ageGroups);
    }

    /**
     * Retrieve statistics of pregnancies grouped by their ending month.
     *
     * The statistics cover the next 9 months, counting pregnancies
     * based on the termin date (due date).
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing months and their counts.
     *
     * Example Response:
     * [
     *     { "month": "January", "count": 2 },
     *     { "month": "February", "count": 0 },
     *     { "month": "March", "count": 1 },
     *     { "month": "April", "count": 0 },
     *     { "month": "May", "count": 3 },
     *     ...
     * ]
     */
    public function getPregnancyByMonth(): JsonResponse
    {
        $pregnanciesByMonth = Pregnancy::select(DB::raw("
        strftime('%m', termin_date) as month, COUNT(*) as count
    "))
            ->whereBetween('termin_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->addMonths(9)->endOfMonth()
            ])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month'); // Key by month for easier merging

        // Full list of months rotated to start from the current month
        $currentMonth = Carbon::now()->month;

        $allMonths = collect(range(0, 11))->mapWithKeys(function ($offset) use ($currentMonth) {
            $month = ($currentMonth + $offset - 1) % 12 + 1; // Ensure it wraps around 12 months
            $monthKey = str_pad($month, 2, '0', STR_PAD_LEFT);
            return [
                $monthKey => [
                    'month' => Carbon::createFromFormat('!m', $month)->format('F'),
                    'count' => 0, // Default count is 0
                ],
            ];
        });

        // Merge the results with default months
        $mergedData = $allMonths->map(function ($default, $month) use ($pregnanciesByMonth) {
            if ($pregnanciesByMonth->has($month)) {
                $default['count'] = $pregnanciesByMonth->get($month)->count;
            }
            return $default;
        })->values();

        return response()->json($mergedData);
    }


}
