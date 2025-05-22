<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LibraryVisit;
use App\Models\User;
use Illuminate\Support\Carbon;

class LibraryVisitLogController extends Controller
{
    /**
     * Show the in/out log and handle search/mark actions.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $user = null;
        $openVisit = null;
        $message = null;

        // Search for user by student_id, qr_code, or name
        if ($query) {
            $user = User::where('student_id', $query)
                ->orWhere('qr_code', $query)
                ->orWhere('name', 'like', "%$query%")
                ->first();

            if ($user) {
                $openVisit = LibraryVisit::where('user_id', $user->id)
                    ->whereNull('exit_time')
                    ->latest('entry_time')
                    ->first();
            }
        }

        // All users with presence status
        $users = User::where('email', '!=', 'admin@librasense.com')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                $openVisit = $user->libraryVisits()->whereNull('exit_time')->latest('entry_time')->first();
                $user->is_inside = (bool) $openVisit;
                $user->current_visit = $openVisit;
                return $user;
            });

        // Count users currently inside (open visits)
        $currentInside = $users->where('is_inside', true)->count();

        return view('admin.library-visits.log', compact('user', 'openVisit', 'users', 'query', 'message', 'currentInside'));
    }

    /**
     * Mark entry for a user.
     */
    public function markEntry(Request $request)
    {
        $userId = $request->input('user_id');
        $name = $request->input('name');
        
        // Get the user from database to get their correct type
        $user = User::find($userId);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Prevent duplicate open visit
        $openVisit = LibraryVisit::where('user_id', $userId)
            ->whereNull('exit_time')
            ->first();
        if ($openVisit) {
            return back()->with('error', 'User is already inside.');
        }

        LibraryVisit::create([
            'user_id' => $userId,
            'visitor_name' => $name,
            'visitor_type' => $user->role->name ?? 'student',
            'entry_time' => now(),
        ]);

        return back()->with('success', 'Entry marked successfully.');
    }

    /**
     * Mark exit for a user.
     */
    public function markExit(Request $request)
    {
        $userId = $request->input('user_id');
        $openVisit = LibraryVisit::where('user_id', $userId)
            ->whereNull('exit_time')
            ->latest('entry_time')
            ->first();
        if (!$openVisit) {
            return back()->with('error', 'No open visit found for this user.');
        }
        $openVisit->update(['exit_time' => now()]);
        return back()->with('success', 'Exit marked successfully.');
    }

    /**
     * Show analytics for library visits (entries/exits per hour, average duration).
     */
    public function analytics()
    {
        $today = Carbon::today();
        $visits = LibraryVisit::whereDate('entry_time', $today)->get();

        // Group entries and exits by hour
        $entriesByHour = array_fill(0, 24, 0);
        $exitsByHour = array_fill(0, 24, 0);
        $totalDuration = 0;
        $durationCount = 0;

        foreach ($visits as $visit) {
            $inHour = $visit->entry_time->format('G');
            $entriesByHour[$inHour]++;
            if ($visit->exit_time) {
                $outHour = $visit->exit_time->format('G');
                $exitsByHour[$outHour]++;
                $totalDuration += $visit->entry_time->diffInMinutes($visit->exit_time);
                $durationCount++;
            }
        }

        $avgDuration = $durationCount ? round($totalDuration / $durationCount) : 0;

        return view('admin.library-visits.analytics', [
            'entriesByHour' => $entriesByHour,
            'exitsByHour' => $exitsByHour,
            'avgDuration' => $avgDuration,
        ]);
    }

    /**
     * Handle QR code scan for user presence (AJAX).
     */
    public function scanQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        $user = \App\Models\User::where('qr_code', $request->qr_code)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Toggle presence (mark in/out)
        $visit = \App\Models\LibraryVisit::where('user_id', $user->id)->whereNull('exit_time')->first();
        if ($visit) {
            // Mark out
            $visit->exit_time = now();
            $visit->save();
            $status = 'out';
        } else {
            // Mark in
            \App\Models\LibraryVisit::create([
                'user_id' => $user->id,
                'visitor_name' => $user->name,
                'visitor_type' => $user->role->name ?? 'student',
                'entry_time' => now(),
            ]);
            $status = 'in';
        }

        return response()->json(['message' => "User {$user->name} marked $status."]);
    }
}
