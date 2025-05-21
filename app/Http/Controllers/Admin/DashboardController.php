<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Feedback;
use App\Models\VisitorAnalytics;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->checkAdmin();
            return $next($request);
        });
    }

    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('is_available', true)->count(),
            'total_loans' => Loan::where('status', 'active')->whereNull('return_date')->count(),
            'overdue_loans' => Loan::where('status', 'active')->where('is_overdue', true)->whereNull('return_date')->count(),
            'total_users' => User::where('email', '!=', 'admin@librasense.com')->count(),
            'total_feedback' => Feedback::count(),
        ];

        $recent_loans = Loan::with(['user', 'book'])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        $recent_feedback = Feedback::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_loans', 'recent_feedback'));
    }

    public function analytics(Request $request)
    {
        $route = $request->route()->getName();

        // Data for all subpages
        $visitor_data = \App\Models\VisitorAnalytics::select(
            \DB::raw('DATE(created_at) as date'),
            \DB::raw('COUNT(*) as count'),
            \DB::raw('COUNT(CASE WHEN visitor_type = \'student\' THEN 1 END) as students'),
            \DB::raw('COUNT(CASE WHEN visitor_type = \'non_student\' THEN 1 END) as non_students'),
            \DB::raw('COUNT(CASE WHEN visitor_type = \'guest\' THEN 1 END) as guests')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $book_circulation = \App\Models\Loan::select(
            \DB::raw('DATE(loan_date) as date'),
            \DB::raw('COUNT(*) as count'),
            \DB::raw('COUNT(CASE WHEN is_overdue = true THEN 1 END) as overdue'),
            \DB::raw('COUNT(CASE WHEN return_date IS NULL THEN 1 END) as active')
        )
        ->where('loan_date', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Data for in/out tracking
        $entriesByHour = $exitsByHour = [];
        if (
            $route === 'admin.analytics.library-inout-tracking'
            || $route === 'admin.analytics'
        ) {
            $today = now()->startOfDay();
            $visits = \App\Models\LibraryVisit::whereDate('entry_time', $today)->get();
            $entriesByHour = array_fill(0, 24, 0);
            $exitsByHour = array_fill(0, 24, 0);
            foreach ($visits as $visit) {
                $inHour = $visit->entry_time->format('G');
                $entriesByHour[$inHour]++;
                if ($visit->exit_time) {
                    $outHour = $visit->exit_time->format('G');
                    $exitsByHour[$outHour]++;
                }
            }
        }

        switch ($route) {
            case 'admin.analytics.visitor-statistics':
                return view('admin.analytics.visitor-statistics', compact('visitor_data', 'book_circulation'));
            case 'admin.analytics.visitor-flow':
                return view('admin.analytics.visitor-flow', compact('visitor_data', 'book_circulation'));
            case 'admin.analytics.library-inout-tracking':
                return view('admin.analytics.library-inout-tracking', compact('entriesByHour', 'exitsByHour'));
            default:
                return view('admin.analytics', compact('visitor_data', 'book_circulation', 'entriesByHour', 'exitsByHour'));
        }
    }
} 