<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->withCount(['destinations' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('pages.destinations.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $destination = Destination::with([
            'category',
            'galleries',
            'availableSchedules',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();



        // Related destinations
        $relatedDestinations = Destination::with('category')
            ->where('is_active', true)
            ->where('id', '!=', $destination->id)
            ->where('category_id', $destination->category_id)
            ->take(3)
            ->get();

        return view('pages.destinations.show', compact('destination', 'relatedDestinations'))
            ->with('transparent', true);
    }
}
