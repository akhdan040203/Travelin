<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::with('category')->withCount('schedules');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $destinations = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.destinations.index', compact('destinations', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'included' => 'nullable|string',
            'excluded' => 'nullable|string',
            'itinerary_days' => 'nullable|array',
            'itinerary_titles' => 'nullable|array',
            'itinerary_descriptions' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('destinations', 'public');
        }

        // Parse included/excluded from textarea (one per line)
        if (!empty($validated['included'])) {
            $validated['included'] = array_filter(array_map('trim', explode("\n", $validated['included'])));
        }
        if (!empty($validated['excluded'])) {
            $validated['excluded'] = array_filter(array_map('trim', explode("\n", $validated['excluded'])));
        }

        // Parse itinerary
        if ($request->filled('itinerary_days')) {
            $itinerary = [];
            foreach ($request->itinerary_days as $i => $day) {
                if (!empty($day)) {
                    $itinerary[] = [
                        'day' => $day,
                        'title' => $request->itinerary_titles[$i] ?? '',
                        'description' => $request->itinerary_descriptions[$i] ?? '',
                    ];
                }
            }
            $validated['itinerary'] = $itinerary;
        }

        // Clean up before creating
        unset($validated['itinerary_days'], $validated['itinerary_titles'], $validated['itinerary_descriptions']);

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil ditambahkan!');
    }

    public function edit(Destination $destination)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'included' => 'nullable|string',
            'excluded' => 'nullable|string',
            'itinerary_days' => 'nullable|array',
            'itinerary_titles' => 'nullable|array',
            'itinerary_descriptions' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($destination->featured_image) {
                Storage::disk('public')->delete($destination->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('destinations', 'public');
        } else {
            unset($validated['featured_image']);
        }

        // Parse included/excluded
        if (isset($validated['included']) && !empty($validated['included'])) {
            $validated['included'] = array_filter(array_map('trim', explode("\n", $validated['included'])));
        } else {
            $validated['included'] = null;
        }
        if (isset($validated['excluded']) && !empty($validated['excluded'])) {
            $validated['excluded'] = array_filter(array_map('trim', explode("\n", $validated['excluded'])));
        } else {
            $validated['excluded'] = null;
        }

        // Parse itinerary
        if ($request->filled('itinerary_days')) {
            $itinerary = [];
            foreach ($request->itinerary_days as $i => $day) {
                if (!empty($day)) {
                    $itinerary[] = [
                        'day' => $day,
                        'title' => $request->itinerary_titles[$i] ?? '',
                        'description' => $request->itinerary_descriptions[$i] ?? '',
                    ];
                }
            }
            $validated['itinerary'] = $itinerary;
        } else {
            $validated['itinerary'] = null;
        }

        unset($validated['itinerary_days'], $validated['itinerary_titles'], $validated['itinerary_descriptions']);

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil diperbarui!');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->featured_image) {
            Storage::disk('public')->delete($destination->featured_image);
        }
        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil dihapus!');
    }
}
