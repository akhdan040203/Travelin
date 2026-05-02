<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with('destination')->withCount('bookings');

        if ($request->filled('destination')) {
            $query->where('destination_id', $request->destination);
        }

        $schedules = $query->orderBy('departure_date', 'desc')->paginate(15)->withQueryString();
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();

        return view('admin.schedules.index', compact('schedules', 'destinations'));
    }

    public function create()
    {
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();
        return view('admin.schedules.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'departure_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:departure_date',
            'quota' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'meeting_point' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed,full',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['booked'] = 0;

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();
        return view('admin.schedules.edit', compact('schedule', 'destinations'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'quota' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'meeting_point' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed,full',
            'notes' => 'nullable|string|max:1000',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->bookings()->count() > 0) {
            return back()->with('error', 'Jadwal tidak bisa dihapus karena sudah ada booking.');
        }
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}
