<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function bookings(Request $request)
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['schedule.destination'])
            ->latest()
            ->paginate(8);

        return view('user.bookings', compact('bookings'));
    }

    public function wishlist(Request $request)
    {
        $wishlists = $request->user()
            ->wishlists()
            ->with('destination.category')
            ->latest()
            ->paginate(9);

        return view('user.wishlist', compact('wishlists'));
    }
}
