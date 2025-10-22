<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return Inertia::render('Branches/Index', [
            'branches' => $branches,
        ]);
    }

    public function create()
    {
        return Inertia::render('Branches/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = (string) Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = Storage::disk('s3')->putFileAs('branches', $image, $fileName, 'public');
            $data['image'] = $imagePath;
        }

        Branch::create($data);

        return redirect()->route('admin.branches.index')->with('success', 'Sucursal creada exitosamente.');
    }

    public function edit(Branch $branch)
    {
        return Inertia::render('Branches/Edit', [
            'branch' => $branch,
        ]);
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($branch->image) {
                Storage::disk('s3')->delete($branch->image);
            }
            $image = $request->file('image');
            $fileName = (string) Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = Storage::disk('s3')->putFileAs('branches', $image, $fileName, 'public');
            $data['image'] = $imagePath;
        }

        $branch->update($data);

        return redirect()->route('admin.branches.index')->with('success', 'Sucursal actualizada exitosamente.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->image) {
            Storage::disk('s3')->delete($branch->image);
        }
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'Sucursal eliminada exitosamente.');
    }
}
