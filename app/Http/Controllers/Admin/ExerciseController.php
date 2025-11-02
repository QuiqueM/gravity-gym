<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $exercises = Exercise::with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->paginate(10);

        $categories = Category::all();

        return Inertia::render('Exercises/Index', [
            'exercises' => $exercises,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id']),
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Exercises/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'demonstration_video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:20480',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('demonstration_video')) {
            $video = $request->file('demonstration_video');
            $fileName = (string) Str::uuid() . '.' . $video->getClientOriginalExtension();
            //$videoPath = Storage::disk('s3')->putFileAs('exercise_videos', $video, $fileName, 'public');
            $videoPath = $request->file('demonstration_video')->storeAs('exercise_videos', $fileName, 's3');
            $data['demonstration_video'] = $videoPath;
        }

        Exercise::create($data);

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio creado exitosamente.');
    }

    public function edit(Exercise $exercise)
    {
        $categories = Category::all();
        return Inertia::render('Exercises/Edit', [
            'exercise' => $exercise->load('category'),
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Exercise $exercise)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'demonstration_video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:20480',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->except('demonstration_video');

        if ($request->hasFile('demonstration_video')) {
            if ($exercise->demonstration_video) {
                Storage::disk('s3')->delete($exercise->demonstration_video);
            }
            $video = $request->file('demonstration_video');
            $fileName = (string) Str::uuid() . '.' . $video->getClientOriginalExtension();
            $videoPath = $request->file('demonstration_video')->storeAs('exercise_videos', $fileName, 's3');
            $data['demonstration_video'] = $videoPath;
        }

        $exercise->update($data);

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio actualizado exitosamente.');
    }

    public function destroy(Exercise $exercise)
    {
        if ($exercise->demonstration_video) {
            Storage::disk('s3')->delete($exercise->demonstration_video);
        }
        $exercise->delete();

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio eliminado exitosamente.');
    }
}
