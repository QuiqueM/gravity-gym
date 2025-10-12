<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
      $user = auth()->user();
      $review = Review::where('user_id', $user->id)->first();
      return Inertia::render('settings/RateUs', [
          'review' => $review,
      ]);
    }

    public function store(Request $request)
    {
      $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
      ]);

      $userId = Auth::id();
      if (Review::where('user_id', $userId)->exists()) {
        return back()->withErrors(['review' => 'Ya has enviado una reseña.']);
      }

      $review = Review::create([
        'user_id' => $userId,
        'rating' => $validated['rating'],
        'comment' => $validated['comment'],
      ]);

      return back()->with('success', 'Review submitted successfully!');
    }

    public function show(Review $review)
    {
      return $review->load('user');
    }

    public function destroy(Review $review)
    {
      $review->delete();
      return response()->noContent();
    }

    public function update(Request $request, Review $review)
    {

      $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
      ]);

      $review->update($validated);

      return back()->with('success', 'Review updated successfully!');
    }
}
