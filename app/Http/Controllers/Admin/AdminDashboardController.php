<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Destination;
use App\Models\Review;

use App\Models\Schedule;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_destinations' => Destination::count(),
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::whereIn('status', ['paid', 'completed'])->sum('total_price'),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),

            'unread_contacts' => Contact::where('is_read', false)->count(),
            'active_schedules' => Schedule::where('status', 'open')->where('departure_date', '>=', now())->count(),
        ];

        $recentBookings = Booking::with(['user', 'schedule.destination'])
            ->latest()
            ->take(5)
            ->get();

        $recentContacts = Contact::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentContacts'));
    }
}
