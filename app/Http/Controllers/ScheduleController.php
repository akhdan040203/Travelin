<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Destination;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with('destination.category')
            ->where('departure_date', '>=', now())
            ->where('status', 'open');

        if ($request->filled('departure_date')) {
            $query->whereDate('departure_date', $request->date('departure_date'));
        }

        if ($request->filled('destination')) {
            $query->where('destination_id', $request->destination);
        }

        $schedules = $query->orderBy('departure_date')->paginate(12)->withQueryString();

        $destinations = Destination::where('is_active', true)->orderBy('name')->get();

        return view('pages.schedules', compact('schedules', 'destinations'));
    }
}
