<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $promotions = Promotion::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return Inertia::render('Promotions/Index', [
            'promotions' => $promotions,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Promotions/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = (string) Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = Storage::disk('s3')->putFileAs('promotions', $image, $fileName, 'public');
            $data['image'] = $imagePath;
        }

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción creada exitosamente.');
    }

    public function edit(Promotion $promotion)
    {
        return Inertia::render('Promotions/Edit', [
            'promotion' => $promotion,
        ]);
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($promotion->image) {
                Storage::disk('s3')->delete($promotion->image);
            }
            $image = $request->file('image');
            $fileName = (string) Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = Storage::disk('s3')->putFileAs('promotions', $image, $fileName, 'public');
            $data['image'] = $imagePath;
        }

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción actualizada exitosamente.');
    }

    public function destroy(Promotion $promotion)
    {
        if ($promotion->image) {
            Storage::disk('s3')->delete($promotion->image);
        }
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción eliminada exitosamente.');
    }
}
