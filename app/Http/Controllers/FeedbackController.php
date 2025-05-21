<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Services\FeedbackMiningService;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    protected $miningService;

    public function __construct(FeedbackMiningService $miningService)
    {
        $this->miningService = $miningService;
    }

    public function index()
    {
        return view('user.feedback');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:library_services,book_collection,facilities,staff,website,suggestions,other',
            'rating' => 'required|integer|min:1|max:5',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->has('is_anonymous') ? null : Auth::id(),
            'category' => $request->category,
            'rating' => $request->rating,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Perform data mining
        $feedback->sentiment = $this->miningService->analyzeSentiment($request->message);
        $feedback->topics = json_encode($this->miningService->extractTopics($request->message));
        $feedback->is_anomaly = $this->miningService->detectAnomalies($feedback);
        $feedback->user_segment = json_encode($this->miningService->segmentUser($feedback));
        $feedback->trend_data = json_encode($this->miningService->calculateTrendData($feedback));
        
        // Get frequent patterns from all feedback
        $allFeedback = Feedback::all();
        $feedback->frequent_patterns = json_encode($this->miningService->findFrequentPatterns($allFeedback));
        
        $feedback->save();

        return redirect()->route('user.feedback')
            ->with('success', 'Thank you for your feedback! We will review it soon.');
    }

    public function analytics()
    {
        $categoryStats = Feedback::getCategoryStats();
        $ratingDistribution = Feedback::getRatingDistribution();
        $recentTrends = Feedback::getRecentTrends();
        $sentimentStats = Feedback::getSentimentStats();
        $topicDistribution = Feedback::getTopicDistribution();
        $anomalyStats = Feedback::getAnomalyStats();
        $userSegmentStats = Feedback::getUserSegmentStats();
        
        $latestFeedback = Feedback::with('user')
            ->latest()
            ->take(5)
            ->get();

        $overallStats = [
            'total_feedback' => Feedback::count(),
            'average_rating' => Feedback::avg('rating'),
            'this_month_count' => Feedback::whereMonth('created_at', now()->month)->count(),
            'this_month_avg' => Feedback::whereMonth('created_at', now()->month)->avg('rating'),
            'anomaly_count' => Feedback::where('is_anomaly', true)->count(),
        ];

        return view('admin.feedback.analytics', compact(
            'categoryStats',
            'ratingDistribution',
            'recentTrends',
            'latestFeedback',
            'overallStats',
            'sentimentStats',
            'topicDistribution',
            'anomalyStats',
            'userSegmentStats'
        ));
    }

    /**
     * Display a listing of the feedback.
     */
    public function indexAdmin()
    {
        $feedback = Feedback::with('user')
            ->latest()
            ->get();

        return view('admin.feedback.index', compact('feedback'));
    }

    public function ajaxList(Request $request)
    {
        $query = Feedback::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
                  });
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }
        if ($request->filled('sentiment')) {
            $query->where('sentiment', $request->input('sentiment'));
        }
        if ($request->filled('is_anomaly')) {
            $query->where('is_anomaly', $request->input('is_anomaly'));
        }
        
        $feedback = $query->orderByDesc('created_at')->get();
        return response()->json(['feedback' => $feedback]);
    }
} 