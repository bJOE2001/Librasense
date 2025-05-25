<?php

namespace App\Services;

use App\Models\Feedback;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeedbackMiningService
{
    /**
     * Analyze sentiment of feedback message
     */
    public function analyzeSentiment(string $message): string
    {
        // Enhanced sentiment analysis with more comprehensive word lists
        $positiveWords = [
            'good', 'great', 'excellent', 'wonderful', 'amazing', 'love', 'happy', 'satisfied',
            'fantastic', 'brilliant', 'outstanding', 'perfect', 'awesome', 'impressive', 'enjoy',
            'pleased', 'delighted', 'helpful', 'friendly', 'efficient', 'quick', 'fast', 'easy',
            'convenient', 'recommend', 'thank', 'thanks', 'appreciate', 'best', 'better'
        ];
        
        $negativeWords = [
            'bad', 'poor', 'terrible', 'awful', 'disappointed', 'hate', 'unhappy', 'dissatisfied',
            'horrible', 'worst', 'worse', 'useless', 'waste', 'slow', 'difficult', 'hard',
            'complicated', 'confusing', 'frustrated', 'annoyed', 'angry', 'upset', 'problem',
            'issue', 'error', 'wrong', 'broken', 'missing', 'late', 'delay', 'rude', 'unprofessional',
            'unhelpful', 'ignore', 'neglect', 'inadequate', 'unsatisfactory', 'disappointing',
            'frustrating', 'annoying', 'inconvenient', 'unreliable', 'unstable', 'outdated', 'obsolete',
            'incompetent', 'inefficient', 'delayed', 'incomplete', 'inaccurate', 'incorrect',
            'unacceptable', 'unreasonable', 'expensive', 'costly', 'overpriced', 'wasted',
            'pointless', 'meaningless', 'unnecessary', 'redundant', 'duplicate', 'repetitive',
            'boring', 'dull', 'uninteresting', 'uninspiring', 'unmotivating', 'demotivating'
        ];

        // Strong sentiment phrases
        $positivePhrases = [
            'above and beyond', 'went out of their way', 'exceptional service', 'highly recommend',
            'extremely helpful', 'absolutely wonderful', 'incredibly helpful', 'very satisfied',
            'super friendly', 'always professional', 'perfect experience', 'love this library'
        ];
        $negativePhrases = [
            'terrible service', 'extremely rude', 'completely ignored', 'utterly unhelpful',
            'very disappointed', 'not helpful at all', 'not satisfied', 'not acceptable',
            'waste of time', 'worst experience', 'never coming back', 'absolutely terrible',
            'staff was rude', 'staff is rude', 'staff were rude', 'unprofessional behavior'
        ];

        $intensifiers = [
            'very', 'extremely', 'absolutely', 'completely', 'totally', 'really', 'incredibly',
            'exceptionally', 'particularly', 'especially', 'highly', 'immensely', 'enormously',
            'exceedingly', 'tremendously', 'intensely', 'profoundly', 'deeply', 'thoroughly',
            'utterly', 'entirely', 'fully', 'wholly', 'perfectly', 'definitely',
            'certainly', 'surely', 'undoubtedly', 'indeed', 'truly', 'genuinely', 'sincerely'
        ];

        $messageLower = strtolower($message);
        $positiveScore = 0;
        $negativeScore = 0;

        // Phrase-based detection (strong influence)
        foreach ($positivePhrases as $phrase) {
            if (strpos($messageLower, $phrase) !== false) {
                $positiveScore += 5;
            }
        }
        foreach ($negativePhrases as $phrase) {
            if (strpos($messageLower, $phrase) !== false) {
                $negativeScore += 5;
            }
        }

        // Tokenize message for negation-aware word scoring
        $words = preg_split('/\s+/', $messageLower);
        $negationWords = ['not', "don't", "doesn't", "didn't", "isn't", "aren't", "wasn't", "weren't", "hasn't", "haven't", "hadn't", "won't", "wouldn't", "couldn't", "shouldn't", "can't", "cannot", 'never', 'no', 'neither', 'nor', 'none', 'nobody', 'nothing', 'nowhere', 'hardly', 'barely', 'scarcely'];
        $negateNext = false;
        foreach ($words as $i => $word) {
            if (in_array($word, $negationWords)) {
                $negateNext = true;
                continue;
            }
            // Check for positive/negative words
            if (in_array($word, $positiveWords)) {
                if ($negateNext) {
                    $negativeScore += 1.5; // Negated positive becomes negative
                    $negateNext = false;
                } else {
                    $positiveScore += 1.5;
                }
            } elseif (in_array($word, $negativeWords)) {
                if ($negateNext) {
                    $positiveScore += 2.0; // Negated negative becomes positive
                    $negateNext = false;
                } else {
                    $negativeScore += 2.0;
                }
            } else {
                $negateNext = false;
            }
        }

        // Apply intensity modifiers
        foreach ($intensifiers as $intensifier) {
            if (strpos($messageLower, $intensifier) !== false) {
                $positiveScore *= 1.5;
                $negativeScore *= 1.5;
            }
        }

        // Consider message length in scoring (reduced impact)
        $wordCount = str_word_count($messageLower);
        if ($wordCount > 0) {
            $positiveScore = $positiveScore / sqrt($wordCount * 0.8);
            $negativeScore = $negativeScore / sqrt($wordCount * 0.8);
        }

        // If strong negative phrase or multiple negative words, never classify as positive
        if ($negativeScore >= 3 && $negativeScore > $positiveScore) {
            return 'negative';
        }
        // If strong positive phrase or multiple positive words, never classify as negative
        if ($positiveScore >= 3 && $positiveScore > $negativeScore) {
            return 'positive';
        }

        // Determine sentiment with lower threshold (1.1 instead of 1.2)
        if ($positiveScore > $negativeScore * 1.1) {
            return 'positive';
        } elseif ($negativeScore > $positiveScore * 1.1) {
            return 'negative';
        } else {
            if ($positiveScore < 0.5 && $negativeScore < 0.5) {
                return 'neutral';
            }
            return $positiveScore > $negativeScore ? 'positive' : 'negative';
        }
    }

    /**
     * Extract topics from feedback message
     */
    public function extractTopics(string $message): array
    {
        $message = strtolower($message);
        $topics = [];
        
        // Define topic keywords and their categories
        $topicKeywords = [
            'library_services' => ['service', 'staff', 'help', 'assist', 'support', 'librarian', 'employee'],
            'book_collection' => ['book', 'collection', 'title', 'author', 'genre', 'fiction', 'non-fiction', 'textbook'],
            'facilities' => ['facility', 'building', 'room', 'space', 'area', 'chair', 'table', 'computer', 'wifi', 'internet'],
            'website' => ['website', 'online', 'web', 'digital', 'system', 'platform', 'interface', 'login', 'account'],
            'hours' => ['hour', 'time', 'schedule', 'open', 'close', 'available', 'access'],
            'noise' => ['noise', 'quiet', 'loud', 'silent', 'disturb', 'peace'],
            'cleanliness' => ['clean', 'dirty', 'hygiene', 'maintain', 'maintenance', 'tidy'],
            'technology' => ['technology', 'tech', 'digital', 'computer', 'printer', 'scanner', 'device'],
            'accessibility' => ['access', 'accessible', 'disability', 'wheelchair', 'elevator', 'ramp'],
            'events' => ['event', 'program', 'workshop', 'seminar', 'lecture', 'activity']
        ];
        
        // Check for topic keywords in the message
        foreach ($topicKeywords as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    $topics[] = $topic;
                    break; // Move to next topic once a keyword is found
                }
            }
        }
        
        // If no topics found, add 'general' as default
        if (empty($topics)) {
            $topics[] = 'general';
        }
        
        return array_unique($topics);
    }

    /**
     * Find frequent patterns in feedback messages
     */
    public function findFrequentPatterns(Collection $feedback): array
    {
        $patterns = [];
        $messages = $feedback->pluck('message')->toArray();
        
        // Common English stop words to exclude
        $stopWords = [
            'a', 'an', 'the', 'and', 'or', 'but', 'if', 'because', 'as', 'what',
            'i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your',
            'yours', 'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she',
            'her', 'hers', 'herself', 'it', 'its', 'itself', 'they', 'them', 'their',
            'theirs', 'themselves', 'this', 'that', 'these', 'those', 'am', 'is', 'are',
            'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do',
            'does', 'did', 'doing', 'would', 'should', 'could', 'ought', 'i\'m', 'you\'re',
            'he\'s', 'she\'s', 'it\'s', 'we\'re', 'they\'re', 'i\'ve', 'you\'ve', 'we\'ve',
            'they\'ve', 'i\'d', 'you\'d', 'he\'d', 'she\'d', 'we\'d', 'they\'d', 'i\'ll',
            'you\'ll', 'he\'ll', 'she\'ll', 'we\'ll', 'they\'ll', 'isn\'t', 'aren\'t',
            'wasn\'t', 'weren\'t', 'hasn\'t', 'haven\'t', 'hadn\'t', 'doesn\'t', 'don\'t',
            'didn\'t', 'won\'t', 'wouldn\'t', 'shan\'t', 'shouldn\'t', 'can\'t', 'cannot',
            'couldn\'t', 'mustn\'t', 'let\'s', 'that\'s', 'who\'s', 'what\'s', 'here\'s',
            'there\'s', 'when\'s', 'where\'s', 'why\'s', 'how\'s', 'to', 'from', 'up',
            'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then',
            'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both',
            'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not',
            'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will',
            'just', 'don', 'should', 'now', 'very', 'well', 'also', 'much', 'many', 'lot',
            'good', 'bad', 'great', 'nice', 'better', 'best', 'worse', 'worst', 'like',
            'want', 'need', 'use', 'used', 'using', 'get', 'got', 'getting', 'make',
            'makes', 'made', 'making', 'come', 'comes', 'came', 'coming', 'take', 'takes',
            'took', 'taking', 'see', 'sees', 'saw', 'seeing', 'know', 'knows', 'knew',
            'knowing', 'think', 'thinks', 'thought', 'thinking', 'feel', 'feels', 'felt',
            'feeling', 'go', 'goes', 'went', 'going', 'say', 'says', 'said', 'saying',
            'tell', 'tells', 'told', 'telling', 'ask', 'asks', 'asked', 'asking', 'work',
            'works', 'worked', 'working', 'seem', 'seems', 'seemed', 'seeming', 'try',
            'tries', 'tried', 'trying', 'leave', 'leaves', 'left', 'leaving', 'call',
            'calls', 'called', 'calling', 'help', 'helps', 'helped', 'helping', 'look',
            'looks', 'looked', 'looking', 'find', 'finds', 'found', 'finding', 'give',
            'gives', 'gave', 'giving', 'keep', 'keeps', 'kept', 'keeping', 'let', 'lets',
            'let', 'letting', 'put', 'puts', 'put', 'putting', 'mean', 'means', 'meant',
            'meaning', 'become', 'becomes', 'became', 'becoming', 'show', 'shows', 'showed',
            'showing', 'move', 'moves', 'moved', 'moving', 'live', 'lives', 'lived',
            'living', 'stand', 'stands', 'stood', 'standing', 'turn', 'turns', 'turned',
            'turning', 'start', 'starts', 'started', 'starting', 'stop', 'stops', 'stopped',
            'stopping', 'hold', 'holds', 'held', 'holding', 'bring', 'brings', 'brought',
            'bringing', 'begin', 'begins', 'began', 'beginning', 'write', 'writes', 'wrote',
            'writing', 'read', 'reads', 'read', 'reading', 'spend', 'spends', 'spent',
            'spending', 'grow', 'grows', 'grew', 'growing', 'lose', 'loses', 'lost',
            'losing', 'add', 'adds', 'added', 'adding', 'change', 'changes', 'changed',
            'changing', 'fall', 'falls', 'fell', 'falling', 'stay', 'stays', 'stayed',
            'staying', 'speak', 'speaks', 'spoke', 'speaking', 'pay', 'pays', 'paid',
            'paying', 'send', 'sends', 'sent', 'sending', 'build', 'builds', 'built',
            'building', 'understand', 'understands', 'understood', 'understanding', 'draw',
            'draws', 'drew', 'drawing', 'break', 'breaks', 'broke', 'breaking', 'spend',
            'spends', 'spent', 'spending', 'cut', 'cuts', 'cut', 'cutting', 'rise',
            'rises', 'rose', 'rising', 'drive', 'drives', 'drove', 'driving', 'buy',
            'buys', 'bought', 'buying', 'wear', 'wears', 'wore', 'wearing', 'choose',
            'chooses', 'chose', 'choosing', 'seek', 'seeks', 'sought', 'seeking', 'throw',
            'throws', 'threw', 'throwing', 'catch', 'catches', 'caught', 'catching',
            'deal', 'deals', 'dealt', 'dealing', 'win', 'wins', 'won', 'winning',
            'force', 'forces', 'forced', 'forcing', 'hit', 'hits', 'hit', 'hitting',
            'eat', 'eats', 'ate', 'eating', 'meet', 'meets', 'met', 'meeting', 'sit',
            'sits', 'sat', 'sitting', 'stand', 'stands', 'stood', 'standing', 'lie',
            'lies', 'lay', 'lying', 'lead', 'leads', 'led', 'leading', 'lose', 'loses',
            'lost', 'losing', 'save', 'saves', 'saved', 'saving', 'get', 'gets', 'got',
            'getting', 'set', 'sets', 'set', 'setting', 'run', 'runs', 'ran', 'running',
            'walk', 'walks', 'walked', 'walking', 'jump', 'jumps', 'jumped', 'jumping',
            'fly', 'flies', 'flew', 'flying', 'ride', 'rides', 'rode', 'riding', 'swim',
            'swims', 'swam', 'swimming', 'climb', 'climbs', 'climbed', 'climbing', 'fall',
            'falls', 'fell', 'falling', 'throw', 'throws', 'threw', 'throwing', 'catch',
            'catches', 'caught', 'catching', 'hold', 'holds', 'held', 'holding', 'carry',
            'carries', 'carried', 'carrying', 'push', 'pushes', 'pushed', 'pushing',
            'pull', 'pulls', 'pulled', 'pulling', 'lift', 'lifts', 'lifted', 'lifting',
            'drop', 'drops', 'dropped', 'dropping', 'throw', 'throws', 'threw', 'throwing',
            'catch', 'catches', 'caught', 'catching', 'hold', 'holds', 'held', 'holding',
            'carry', 'carries', 'carried', 'carrying', 'push', 'pushes', 'pushed', 'pushing',
            'pull', 'pulls', 'pulled', 'pulling', 'lift', 'lifts', 'lifted', 'lifting',
            'drop', 'drops', 'dropped', 'dropping', 'throw', 'throws', 'threw', 'throwing',
            'catch', 'catches', 'caught', 'catching', 'hold', 'holds', 'held', 'holding',
            'carry', 'carries', 'carried', 'carrying', 'push', 'pushes', 'pushed', 'pushing',
            'pull', 'pulls', 'pulled', 'pulling', 'lift', 'lifts', 'lifted', 'lifting',
            'drop', 'drops', 'dropped', 'dropping'
        ];
        
        // Simple pattern finding based on common phrases
        foreach ($messages as $message) {
            $words = explode(' ', strtolower($message));
            $filteredWords = array_filter($words, function($word) use ($stopWords) {
                return !in_array($word, $stopWords) && strlen($word) > 2;
            });
            
            $filteredWords = array_values($filteredWords); // Re-index array
            
            for ($i = 0; $i < count($filteredWords) - 1; $i++) {
                $phrase = $filteredWords[$i] . ' ' . $filteredWords[$i + 1];
                if (!isset($patterns[$phrase])) {
                    $patterns[$phrase] = 0;
                }
                $patterns[$phrase]++;
            }
        }
        
        // Filter out patterns that appear less than 3 times
        $patterns = array_filter($patterns, function($count) {
            return $count >= 3;
        });
        
        arsort($patterns);
        return array_slice($patterns, 0, 10, true);
    }

    /**
     * Detect anomalies in feedback
     */
    public function detectAnomalies(Feedback $feedback): bool
    {
        // Simple anomaly detection based on rating and sentiment
        $recentFeedback = Feedback::where('created_at', '>=', now()->subDays(7))
            ->where('id', '!=', $feedback->id)
            ->get();
        
        if ($recentFeedback->isEmpty()) {
            return false;
        }
        
        $avgRating = $recentFeedback->avg('rating');
        $stdDev = $this->calculateStandardDeviation($recentFeedback->pluck('rating')->toArray());
        
        // Consider it an anomaly if rating is more than 2 standard deviations from mean
        return abs($feedback->rating - $avgRating) > (2 * $stdDev);
    }

    /**
     * Segment users based on feedback patterns
     */
    public function segmentUser(Feedback $feedback): array
    {
        $user = $feedback->user;
        if (!$user) {
            return ['segment' => 'anonymous'];
        }
        
        $userFeedback = Feedback::where('user_id', $user->id)->get();
        
        $segments = [];
        
        // Active user segment
        if ($userFeedback->count() >= 5) {
            $segments[] = 'active_user';
        }
        
        // Critical user segment
        if ($userFeedback->where('rating', '<=', 2)->count() >= 3) {
            $segments[] = 'critical_user';
        }
        
        // Positive user segment
        if ($userFeedback->where('rating', '>=', 4)->count() >= 3) {
            $segments[] = 'positive_user';
        }
        
        return $segments ?: ['regular_user'];
    }

    /**
     * Calculate trend data for feedback
     */
    public function calculateTrendData(Feedback $feedback): array
    {
        $trends = [];
        
        // Daily trend
        $dailyFeedback = Feedback::whereDate('created_at', $feedback->created_at)
            ->where('id', '!=', $feedback->id)
            ->count();
        $trends['daily_count'] = $dailyFeedback + 1;
        
        // Weekly trend
        $weeklyFeedback = Feedback::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        $trends['weekly_count'] = $weeklyFeedback;
        
        // Monthly trend
        $monthlyFeedback = Feedback::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $trends['monthly_count'] = $monthlyFeedback;
        
        return $trends;
    }

    /**
     * Helper function to calculate standard deviation
     */
    private function calculateStandardDeviation(array $numbers): float
    {
        $count = count($numbers);
        if ($count === 0) {
            return 0;
        }
        
        $mean = array_sum($numbers) / $count;
        $squaredDifferences = array_map(function($number) use ($mean) {
            return pow($number - $mean, 2);
        }, $numbers);
        
        return sqrt(array_sum($squaredDifferences) / $count);
    }
} 