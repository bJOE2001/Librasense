<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryVisit;
use Illuminate\Http\Request;

class LibraryVisitController extends Controller
{
    public function markEntry(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'type' => 'required|in:student,non-student'
        ]);

        // Check if user already has an active visit
        $activeVisit = LibraryVisit::where('user_id', $request->user_id)
            ->whereNull('exit_time')
            ->first();

        if ($activeVisit) {
            return back()->with('error', 'User already has an active visit.');
        }

        // Create new visit with entry time
        $visit = LibraryVisit::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'type' => $request->type,
            'entry_time' => now(),
        ]);

        return back()->with('success', 'User marked as entered successfully.');
    }

    public function markExit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $visit = LibraryVisit::where('user_id', $request->user_id)
            ->whereNull('exit_time')
            ->latest()
            ->first();

        if (!$visit) {
            return back()->with('error', 'No active visit found for this user.');
        }

        $visit->exit_time = now();
        $visit->calculateDuration();
        $visit->save();

        return back()->with('success', 'User marked as exited successfully.');
    }
} 