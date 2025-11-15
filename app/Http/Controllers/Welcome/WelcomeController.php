<?php
namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MembershipPlan;
use App\Models\Review;
use App\Models\Promotion;
use App\Models\Branch;

class WelcomeController extends Controller
{
    public function index()
    {
        $events = Event::whereHas('gymClass.schedules', function ($query) {
            $query->where('starts_at', '>', now());
        })->get();
        
        $plans = MembershipPlan::all();
        $comments = Review::with('user')->get();
        $promotions = Promotion::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();

        return inertia('Welcome', [
            'events' => $events,
            'plans' => $plans,
            'comments' => $comments,
            'promotions' => $promotions,
            'branches' => $branches,
        ]);
    }
}
