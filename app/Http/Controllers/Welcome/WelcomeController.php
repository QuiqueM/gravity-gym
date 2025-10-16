<?php
namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MembershipPlan;
use App\Models\Review;

class WelcomeController extends Controller
{
    public function index()
    {
        $events = Event::all();
        $plans = MembershipPlan::all();
        $comments = Review::with('user')->latest()->get();
        return inertia('Welcome', [
            'events' => $events,
            'plans' => $plans,
            'comments' => $comments,
        ]);
    }
}
