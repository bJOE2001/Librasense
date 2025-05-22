<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\User;
use App\Services\FeedbackMiningService;
use Carbon\Carbon;

class DummyFeedbackSeeder extends Seeder
{
    protected $miningService;

    public function __construct()
    {
        $this->miningService = new FeedbackMiningService();
    }

    public function run(): void
    {
        // Get all users except admin
        $users = User::where('email', '!=', 'admin@librasense.com')->get();
        
        // Feedback categories
        $categories = [
            'library_services',
            'book_collection',
            'facilities',
            'staff',
            'website',
            'suggestions',
            'other'
        ];

        // Positive subjects
        $positiveSubjects = [
            'Great library experience',
            'Need more books in this section',
            'Excellent staff service',
            'Website needs improvement',
            'Library hours are perfect',
            'Clean and quiet environment',
            'More study spaces needed',
            'Digital resources are helpful',
            'Events are well organized',
            'Need more computers',
            'Parking is convenient',
            'Wifi connection issues',
            'Printing service feedback',
            'Book reservation system',
            'Library app suggestions'
        ];

        // Negative subjects
        $negativeSubjects = [
            'Terrible service experience',
            'Unacceptable waiting times',
            'Staff needs immediate training',
            'Website is completely broken',
            'Extremely noisy environment',
            'Inadequate and dirty facilities',
            'Overcrowded and uncomfortable',
            'Outdated and useless collection',
            'Unreliable and slow wifi',
            'Rude and unhelpful staff'
        ];

        // Positive messages
        $positiveMessages = [
            'The library staff was very helpful and knowledgeable. They assisted me in finding the books I needed quickly.',
            'I love the quiet study areas. Perfect for concentration and getting work done.',
            'The online catalog system is easy to use, but sometimes it takes time to load.',
            'More power outlets would be helpful in the study areas.',
            'The new book collection is impressive, especially the science fiction section.',
            'The library events are well organized and informative.',
            'The printing service is reliable and cost-effective.',
            'The library app needs some improvements in the search functionality.',
            'The study rooms are well-maintained and comfortable.',
            'The digital resources section could use more variety.',
            'The library hours are convenient for students.',
            'The parking area needs better lighting in the evening.',
            'The book reservation system works smoothly.',
            'The library website could be more user-friendly.',
            'The staff is always friendly and professional.'
        ];

        // Negative messages
        $negativeMessages = [
            'The staff was extremely rude and unhelpful. They made me feel unwelcome and refused to assist with my questions. This is the worst service I have ever experienced.',
            'The library is always too noisy and crowded. It\'s impossible to concentrate or study effectively. The staff does nothing to maintain a quiet environment.',
            'The website is constantly crashing and the online booking system never works properly. This is a complete waste of time and resources.',
            'The book collection is outdated and many books are in poor condition. It\'s frustrating and disappointing to find relevant materials.',
            'The wifi connection is terrible and keeps dropping every few minutes. It\'s impossible to do online research. This is unacceptable for a modern library.',
            'The study rooms are always full and the reservation system is confusing and unreliable. The staff is unhelpful when issues arise.',
            'The staff members are rude and unprofessional. They don\'t seem to care about helping students and often ignore requests for assistance.',
            'The library hours are inconvenient and the opening times are not clearly communicated. This causes unnecessary frustration and wasted trips.',
            'The facilities are poorly maintained and many computers are broken or outdated. The chairs are uncomfortable and the tables are dirty.',
            'The printing service is expensive and the machines are always out of order. The staff is unhelpful when technical issues occur.',
            'The library app is useless and keeps crashing. The search function doesn\'t work at all. This is a complete waste of development resources.',
            'The parking situation is a nightmare. There are never enough spaces and it\'s too expensive. The staff is unhelpful with parking issues.',
            'The book return process is complicated and the staff is not helpful when issues arise. This is frustrating and time-consuming.',
            'The digital resources are limited and many links are broken or outdated. This makes it difficult to access necessary materials.',
            'The library environment is not conducive to studying. It\'s too hot, the chairs are uncomfortable, and the noise level is unacceptable.'
        ];

        // Generate feedback for each user
        foreach ($users as $user) {
            // Generate 1-3 feedback entries per user
            $feedbackCount = rand(1, 3);
            
            for ($i = 0; $i < $feedbackCount; $i++) {
                // 40% chance of negative feedback
                if (rand(1, 100) <= 40) {
                    // Select from negative subjects and messages
                    $subject = $negativeSubjects[array_rand($negativeSubjects)];
                    $message = $negativeMessages[array_rand($negativeMessages)];
                    $rating = rand(1, 2); // Force low rating for negative feedback
                } else {
                    // Select from positive subjects and messages
                    $subject = $positiveSubjects[array_rand($positiveSubjects)];
                    $message = $positiveMessages[array_rand($positiveMessages)];
                    $rating = rand(3, 5); // Higher rating for positive feedback
                }
                
                // Create feedback with random date within last 30 days
                $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                $feedback = Feedback::create([
                    'user_id' => $user->id,
                    'category' => $categories[array_rand($categories)],
                    'rating' => $rating,
                    'subject' => $subject,
                    'message' => $message,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt
                ]);

                // Perform data mining
                $feedback->sentiment = $this->miningService->analyzeSentiment($message);
                $feedback->topics = json_encode($this->miningService->extractTopics($message));
                $feedback->is_anomaly = $this->miningService->detectAnomalies($feedback);
                $feedback->user_segment = json_encode($this->miningService->segmentUser($feedback));
                $feedback->trend_data = json_encode($this->miningService->calculateTrendData($feedback));
                
                $feedback->save();
            }
        }
    }
} 