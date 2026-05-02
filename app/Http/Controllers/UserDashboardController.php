<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $bookings = Booking::with('schedule.destination')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'active_bookings' => Booking::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed', 'paid'])->count(),
            'completed_trips' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_spent' => Booking::where('user_id', $user->id)->whereIn('status', ['paid', 'completed'])->sum('total_price'),
        ];

        return view('pages.user.dashboard', compact('bookings', 'stats'));
    }

    public function bookings()
    {
        $bookings = Booking::with('schedule.destination')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pages.user.bookings', compact('bookings'));
    }

    public function wishlist()
    {
        $wishlists = auth()->user()->wishlists()
            ->with('destination.category')
            ->latest()
            ->paginate(12);

        return view('pages.user.wishlist', compact('wishlists'));
    }
}
