<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibraryVisit;
use App\Models\User;
use Carbon\Carbon;

class DummyLibraryVisitsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('email', '!=', 'admin@librasense.com')->get();
        
        foreach ($users as $user) {
            // Generate 1-3 visits per user
            $numVisits = rand(1, 3);
            
            for ($i = 0; $i < $numVisits; $i++) {
                // Generate entry time within the last 7 days
                $entryTime = Carbon::now()->subDays(rand(0, 7))->setHour(rand(8, 17))->setMinute(rand(0, 59));
                
                // 80% chance of having an exit time
                $hasExit = rand(1, 100) <= 80;
                
                if ($hasExit) {
                    // Exit time between 30 minutes and 4 hours after entry
                    $exitTime = (clone $entryTime)->addMinutes(rand(30, 240));
                    $durationMinutes = $entryTime->diffInMinutes($exitTime);
                } else {
                    $exitTime = null;
                    $durationMinutes = null;
                }

                LibraryVisit::create([
                    'user_id' => $user->id,
                    'visitor_name' => $user->name,
                    'visitor_type' => $user->role->name ?? 'student',
                    'entry_time' => $entryTime,
                    'exit_time' => $exitTime,
                    'duration_minutes' => $durationMinutes,
                    'qr_code' => $user->qr_code,
                ]);
            }
        }
    }
} 