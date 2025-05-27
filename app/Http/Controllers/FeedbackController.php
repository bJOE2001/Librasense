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

        // Enhanced anomaly detection: repeated negative feedbacks
        if ($feedback->user_id && $feedback->rating <= 2) {
            $recentNegativeCount = Feedback::where('user_id', $feedback->user_id)
                ->where('rating', '<=', 2)
                ->where('message', $feedback->message)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->count();
            if ($recentNegativeCount >= 3) {
                Feedback::where('user_id', $feedback->user_id)
                    ->where('rating', '<=', 2)
                    ->where('message', $feedback->message)
                    ->where('created_at', '>=', now()->subMinutes(10))
                    ->update(['is_anomaly' => true]);
            }
        }

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
        
        // Use weekly feedback (past 7 days) instead of just latest feedback
        $weeklyFeedback = Feedback::with('user')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderByDesc('created_at')
            ->get();

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

        // Use $weeklyFeedback for all suggestion logic
        $recentNegativeFeedback = $weeklyFeedback->filter(function($feedback) {
            return ($feedback->sentiment ?? null) === 'negative' || $feedback->rating <= 2;
        });
        $topicCounts = [];
        $topicFeedbackMap = [];
        $miningService = app(\App\Services\FeedbackMiningService::class);
        foreach ($recentNegativeFeedback as $feedback) {
            $topics = is_array($feedback->topics) ? $feedback->topics : json_decode($feedback->topics, true);
            if ($topics) {
                foreach ($topics as $topic) {
                    if (!isset($topicCounts[$topic])) $topicCounts[$topic] = 0;
                    $topicCounts[$topic]++;
                    if (!isset($topicFeedbackMap[$topic])) $topicFeedbackMap[$topic] = [];
                    $topicFeedbackMap[$topic][] = $feedback->message;
                }
            }
        }
        // Map topics to actionable suggestions
        $topicToSuggestion = [
            'staff' => 'Consider staff training or customer service workshops.',
            'library_services' => 'Review and improve library service processes.',
            'noise' => 'Review noise control policies in study areas.',
            'website' => 'Investigate and improve website/app usability.',
            'wifi' => 'Check and upgrade WiFi/internet connectivity.',
            'facilities' => 'Inspect and maintain library facilities regularly.',
            'cleanliness' => 'Increase cleaning frequency and monitor hygiene.',
            'book_collection' => 'Expand and update the book collection.',
            'technology' => 'Upgrade or maintain library technology and devices.',
            'accessibility' => 'Improve accessibility features for all users.',
            'events' => 'Organize more engaging and relevant events.',
            'hours' => 'Consider extending or adjusting library hours.',
            'general' => 'Review general feedback for miscellaneous improvements.',
        ];
        arsort($topicCounts);
        // For each top topic, extract most common phrase (if any)
        $suggestions = [];
        foreach ($topicCounts as $topic => $count) {
            $suggestionText = $topicToSuggestion[$topic] ?? ('Review feedback related to ' . $topic);
            $clusterFeedback = collect($topicFeedbackMap[$topic] ?? []);
            $clusterPatterns = $miningService->findFrequentPatterns($clusterFeedback);
            $topPhrase = null;
            if (!empty($clusterPatterns)) {
                $topPhrase = array_key_first($clusterPatterns);
            }
            $suggestions[] = [
                'text' => $suggestionText,
                'count' => $count,
                'phrase' => $topPhrase
            ];
        }
        // Book request extraction
        $bookRequests = [];
        $trailingGenericPattern = '/\b(for|in|to|the|library|collection|section|by|author|please|add|request|include|get)\b.*$/i';
        foreach ($weeklyFeedback as $feedback) {
            if (preg_match_all('/(?:add|request|include|get)\s+["\']?([A-Za-z0-9:;,.\'\- ]{2,})["\']?/i', $feedback->message, $matches)) {
                foreach ($matches[1] as $bookTitle) {
                    $bookTitle = preg_replace($trailingGenericPattern, '', $bookTitle);
                    $bookTitle = trim($bookTitle);
                    // Filter out generic words/phrases
                    if (
                        strlen($bookTitle) > 2 &&
                        !preg_match('/book|books|collection|title|author|section|more|new|old|copy|copies|for|library|please|add|request|include|get/i', $bookTitle) &&
                        !in_array(strtolower($bookTitle), ['for', 'the', 'library', 'collection', 'section', 'by', 'author', 'please', 'add', 'request', 'include', 'get'])
                    ) {
                        $bookRequests[$bookTitle] = ($bookRequests[$bookTitle] ?? 0) + 1;
                    }
                }
            }
        }
        arsort($bookRequests);
        $topBookRequests = array_slice($bookRequests, 0, 5, true);
        if (!empty($topBookRequests)) {
            $suggestions[] = [
                'text' => 'Most Requested Books',
                'count' => null,
                'phrase' => null,
                'books' => $topBookRequests
            ];
        }
        // For emerging issues, use $weeklyFeedback as well
        $allNegativeFeedback = $weeklyFeedback->filter(function($feedback) {
            return ($feedback->sentiment ?? null) === 'negative' || $feedback->rating <= 2;
        });
        $frequentPatterns = $miningService->findFrequentPatterns($allNegativeFeedback);
        foreach ($frequentPatterns as $phrase => $count) {
            if ($count > 1) {
                $suggestions[] = [
                    'text' => 'Emerging issue',
                    'count' => $count,
                    'phrase' => $phrase
                ];
            }
        }
        if (empty($suggestions)) {
            $suggestions[] = [
                'text' => 'Keep up the good work! No urgent suggestions from recent feedback.',
                'count' => null,
                'phrase' => null
            ];
        }

        return view('admin.feedback.analytics', compact(
            'categoryStats',
            'ratingDistribution',
            'recentTrends',
            'latestFeedback',
            'overallStats',
            'sentimentStats',
            'topicDistribution',
            'anomalyStats',
            'userSegmentStats',
            'suggestions'
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