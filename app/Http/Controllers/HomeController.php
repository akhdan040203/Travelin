<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Schedule;

class HomeController extends Controller
{
    public function index()
    {
        $featuredDestinations = Destination::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->get()
            ->sortByDesc(function ($destination) {
                $category = strtolower($destination->category->name ?? '');
                $slug = strtolower($destination->slug ?? '');

                return str_contains($category, 'pantai')
                    || str_contains($slug, 'bali')
                    || str_contains($slug, 'raja-ampat');
            })
            ->values()
            ->take(6);

        $categories = Category::where('is_active', true)
            ->withCount(['destinations' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $upcomingSchedules = Schedule::with('destination')
            ->where('status', 'open')
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->take(4)
            ->get();


        $stats = [
            'destinations' => Destination::where('is_active', true)->count(),
            'bookings' => \App\Models\Booking::count(),
        ];

        $popularDestinations = Destination::with('category')
            ->where('is_active', true)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(6)
            ->get();

        return view('pages.home', compact(
            'featuredDestinations',
            'categories',
            'upcomingSchedules',
            'stats',
            'popularDestinations'
        ))->with('transparent', true);
    }
}
