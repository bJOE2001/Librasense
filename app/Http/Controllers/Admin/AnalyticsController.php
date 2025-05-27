<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Models\LibraryVisit;
use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'active_users' => User::where('email', '!=', 'admin@librasense.com')
                ->where(function($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(30))
                        ->orWhereHas('loans', function($q) {
                            $q->where('created_at', '>=', Carbon::now()->subDays(30));
                        })
                        ->orWhereHas('libraryVisits', function($q) {
                            $q->where('created_at', '>=', Carbon::now()->subDays(30));
                        });
                })->count(),
            'new_users' => User::where('email', '!=', 'admin@librasense.com')
                ->whereMonth('created_at', Carbon::now()->month)->count(),
            'active_loans' => Loan::whereNull('return_date')->count(),
            'recent_visits' => LibraryVisit::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
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

        // Get user distribution by school
        $schoolStats = $this->getSchoolStats();

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
            'popularBooks',
            'schoolStats'
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
        $days = 7;
        $dates = collect();
        $activity = collect();

        // Generate dates for the last 7 days
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates->push($date);
            
            // Count activities for each day
            $dailyActivity = [
                'loans' => Loan::whereDate('created_at', $date)->count(),
                'visits' => LibraryVisit::whereDate('created_at', $date)->count(),
                'feedback' => Feedback::whereDate('created_at', $date)->count(),
                'logins' => DB::table('sessions')
                    ->whereRaw('to_timestamp(last_activity)::date = ?', [$date])
                    ->count()
            ];
            
            $activity->push($dailyActivity);
        }

        return [
            'labels' => $dates->map(function($date) {
                return Carbon::parse($date)->format('M d');
            })->toArray(),
            'data' => [
                'loans' => $activity->pluck('loans')->toArray(),
                'visits' => $activity->pluck('visits')->toArray(),
                'feedback' => $activity->pluck('feedback')->toArray(),
                'logins' => $activity->pluck('logins')->toArray()
            ]
        ];
    }

    private function getSchoolStats()
    {
        $schools = User::select('school', DB::raw('COUNT(*) as count'))
            ->whereNotNull('school')
            ->groupBy('school')
            ->orderByDesc('count')
            ->get();

        return [
            'labels' => $schools->pluck('school')->toArray(),
            'data' => $schools->pluck('count')->toArray(),
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

    public function exportUsers()
    {
        $users = \App\Models\User::where('email', '!=', 'admin@librasense.com')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];
        $columns = ['ID', 'Name', 'Email', 'School', 'Created At'];
        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->school,
                    $user->created_at,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportVisitors()
    {
        $visits = \App\Models\LibraryVisit::with('user')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="visitors.csv"',
        ];
        $columns = ['Visit ID', 'User Name', 'User Email', 'Entry Time', 'Exit Time'];
        $callback = function() use ($visits, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($visits as $visit) {
                if ($visit->user && $visit->user->email === 'admin@librasense.com') continue;
                fputcsv($file, [
                    $visit->id,
                    $visit->user ? $visit->user->name : 'Unknown',
                    $visit->user ? $visit->user->email : 'Unknown',
                    $visit->entry_time,
                    $visit->exit_time,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportCirculation()
    {
        $loans = \App\Models\Loan::with('user', 'book')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="circulation.csv"',
        ];
        $columns = ['Loan ID', 'Book Title', 'User Name', 'User Email', 'Loan Date', 'Return Date', 'Status'];
        $callback = function() use ($loans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($loans as $loan) {
                if ($loan->user && $loan->user->email === 'admin@librasense.com') continue;
                fputcsv($file, [
                    $loan->id,
                    $loan->book ? $loan->book->title : 'Unknown',
                    $loan->user ? $loan->user->name : 'Unknown',
                    $loan->user ? $loan->user->email : 'Unknown',
                    $loan->loan_date,
                    $loan->return_date,
                    $loan->status,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportInOut()
    {
        $visits = \App\Models\LibraryVisit::with('user')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inout_tracking.csv"',
        ];
        $columns = ['Visit ID', 'User Name', 'User Email', 'Entry Time', 'Exit Time', 'Duration (minutes)'];
        $callback = function() use ($visits, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($visits as $visit) {
                if ($visit->user && $visit->user->email === 'admin@librasense.com') continue;
                $duration = $visit->exit_time && $visit->entry_time ? $visit->entry_time->diffInMinutes($visit->exit_time) : null;
                fputcsv($file, [
                    $visit->id,
                    $visit->user ? $visit->user->name : 'Unknown',
                    $visit->user ? $visit->user->email : 'Unknown',
                    $visit->entry_time,
                    $visit->exit_time,
                    $duration,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
} 