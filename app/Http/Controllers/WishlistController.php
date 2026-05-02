<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function toggle(Request $request, Destination $destination): RedirectResponse|JsonResponse
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('destination_id', $destination->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'wishlisted' => false,
                    'count' => auth()->user()->wishlists()->count(),
                    'message' => 'Destinasi dihapus dari wishlist.',
                ]);
            }

            return back()->with('success', 'Destinasi dihapus dari wishlist.');
        }

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'destination_id' => $destination->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'wishlisted' => true,
                'count' => auth()->user()->wishlists()->count(),
                'message' => 'Destinasi ditambahkan ke wishlist.',
            ]);
        }

        return back()->with('success', 'Destinasi ditambahkan ke wishlist.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        Wishlist::where('user_id', auth()->id())
            ->where('destination_id', $destination->id)
            ->delete();

        return back()->with('success', 'Destinasi dihapus dari wishlist.');
    }
}
