<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Models\LibraryVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Get visitor statistics
        $visitorStats = [
            'total_visitors' => LibraryVisit::count(),
            'monthly_visitors' => LibraryVisit::whereMonth('entry_time', Carbon::now()->month)->count(),
        ];

        // Get loan statistics
        $loanStats = [
            'active_loans' => Loan::whereNull('return_date')->count(),
            'overdue_loans' => Loan::whereNull('return_date')
                ->where('due_date', '<', Carbon::now())
                ->count(),
        ];

        // Get book circulation statistics
        $bookStats = [
            'total_circulation' => Loan::count(),
            'monthly_circulation' => Loan::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        // Get user statistics - using created_at instead of last_login_at
        $userStats = [
            'active_users' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            'new_users' => User::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        // Get visitor trends (last 7 days)
        $visitorTrends = $this->getVisitorTrends();

        // Get circulation trends (last 7 days)
        $circulationTrends = $this->getCirculationTrends();

        // Get category statistics
        $categoryStats = $this->getCategoryStats();

        // Get user activity (last 7 days)
        $userActivity = $this->getUserActivity();

        // Get recent visits
        $recentVisits = LibraryVisit::with('user')
            ->latest('entry_time')
            ->take(5)
            ->get();

        // Get popular books
        $popularBooks = Book::withCount(['loans' => function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            }])
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.analytics', compact(
            'visitorStats',
            'loanStats',
            'bookStats',
            'userStats',
            'visitorTrends',
            'circulationTrends',
            'categoryStats',
            'userActivity',
            'recentVisits',
            'popularBooks'
        ));
    }

    private function getVisitorTrends()
    {
        $dates = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $visitors = LibraryVisit::select(
            DB::raw('DATE(entry_time) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->whereIn(DB::raw('DATE(entry_time)'), $dates)
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'data' => $dates->map(fn($date) => $visitors[$date]->count ?? 0)->toArray(),
        ];
    }

    private function getCirculationTrends()
    {
        $dates = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $loans = Loan::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->whereIn(DB::raw('DATE(created_at)'), $dates)
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'data' => $dates->map(fn($date) => $loans[$date]->count ?? 0)->toArray(),
        ];
    }

    private function getCategoryStats()
    {
        $categories = Book::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        return [
            'labels' => $categories->pluck('category')->toArray(),
            'data' => $categories->pluck('count')->toArray(),
        ];
    }

    private function getUserActivity()
    {
        $dates = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        // Count users who have loans or visits in the last 7 days
        $activity = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereIn(DB::raw('DATE(created_at)'), $dates)
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'data' => $dates->map(fn($date) => $activity[$date]->count ?? 0)->toArray(),
        ];
    }

    public function libraryInOutTracking(Request $request)
    {
        $date1 = $request->input('date1', now()->toDateString());
        $date2 = $request->input('date2', null);

        $getAnalytics = function($date) {
            $visits = \App\Models\LibraryVisit::whereDate('entry_time', $date)->get();
            $entriesByHour = array_fill(0, 24, 0);
            $exitsByHour = array_fill(0, 24, 0);
            $durations = [];
            foreach ($visits as $visit) {
                $inHour = $visit->entry_time->format('G');
                $entriesByHour[$inHour]++;
                if ($visit->exit_time) {
                    $outHour = $visit->exit_time->format('G');
                    $exitsByHour[$outHour]++;
                    $durations[] = $visit->entry_time->diffInMinutes($visit->exit_time);
                }
            }
            $avg = $durations ? round(array_sum($durations) / count($durations)) : 0;
            $min = $durations ? min($durations) : 0;
            $max = $durations ? max($durations) : 0;
            $hist = [0,0,0,0,0,0];
            foreach ($durations as $d) {
                if ($d <= 30) $hist[0]++;
                elseif ($d <= 60) $hist[1]++;
                elseif ($d <= 120) $hist[2]++;
                elseif ($d <= 180) $hist[3]++;
                elseif ($d <= 240) $hist[4]++;
                else $hist[5]++;
            }
            return [
                'entriesByHour' => $entriesByHour,
                'exitsByHour' => $exitsByHour,
                'avg' => $avg,
                'min' => $min,
                'max' => $max,
                'hist' => $hist,
            ];
        };

        $data1 = $getAnalytics($date1);
        $data2 = $date2 ? $getAnalytics($date2) : null;
        $hours = ['8am','9am','10am','11am','12pm','1pm','2pm','3pm','4pm','5pm','6pm'];

        return view('admin.analytics.library-inout-tracking', compact(
            'hours', 'data1', 'data2', 'date1', 'date2'
        ));
    }
} 