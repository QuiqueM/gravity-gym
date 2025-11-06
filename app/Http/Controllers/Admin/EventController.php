<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GymClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('gymClass')->latest()->get();

        return inertia('events/Index', compact('events'));
    }

    public function create()
    {
        $classes = GymClass::whereHas('type', function ($query) {
            $query->where('name', 'Evento');
        })->get();

        return inertia('events/Create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:5120',
            'class_id' => 'required|exists:classes,id',
        ]);

        if ($request->hasFile('image')) {
            $uuid = (string) \Illuminate\Support\Str::uuid();
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $uuid.'.'.$extension;
            $path = $request->file('image')->storeAs('events', $filename, 's3');
            $data['image'] = $path;
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Evento creado correctamente');
    }

    public function show(Event $event)
    {
        $event->load('gymClass');

        return inertia('events/Show', compact('event'));
    }

    public function edit(Event $event)
    {
        $classes = GymClass::whereHas('type', function ($query) {
            $query->where('name', 'Evento');
        })->get();

        return inertia('events/Edit', compact('event', 'classes'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:5120',
            'class_id' => 'required|exists:classes,id',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('s3')->delete($event->image);
            }
            $uuid = (string) \Illuminate\Support\Str::uuid();
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $uuid.'.'.$extension;
            $data['image'] = $request->file('image')->storeAs('events', $filename, 's3');
        } else {
            $data['image'] = $event->image;
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Evento actualizado correctamente');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('s3')->delete($event->image);
        }
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Evento eliminado correctamente');
    }
}
