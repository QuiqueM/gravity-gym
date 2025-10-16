<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\user;
use App\Models\Role;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        $users = $query->paginate(15);

        return inertia('Users/Index', ['users' => $users, 'search' => $request->search]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Users/Create', ['roles' => Role::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'role_id' => 'required|exists:'.Role::class.',id',
            'phone' => 'required|string|min:10|max:20|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'qr_code' => null, // El QR se genera después
        ]);

        $user->roles()->sync([$request->role_id]);

        return to_route('admin.users.index')->with('success', 'Usuario creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(user $user)
    {
        $user->load('roles', 'membership.plan');
        $memberships_plan = MembershipPlan::where('is_active', true)->get();
        return inertia('Users/Show', ['user' => $user, 'memberships_plan' => $memberships_plan]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:10|max:20|unique:users,phone,'.$user->id.',id',
        ]);

        $user->update($request->only('name', 'phone'));

        return to_route('profile.edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        $user->delete();
        return back()->with('success', 'Usuario eliminado');
    }

    public function editProfile()
    {
        $user = auth()->user()->load('roles', 'membership.plan');
        $memberships_plan = MembershipPlan::where('is_active', true)->get();
        return inertia('Users/Profile', ['user' => $user, 'memberships_plan' => $memberships_plan]);
    }

    public function updateAvatarProfile(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:5120', // Máximo 5MB
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            $uuid = (string) \Illuminate\Support\Str::uuid();
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $filename = $uuid . '.' . $extension;
            $path = $request->file('avatar')->storeAs('avatars', $filename, 's3');
            $user->avatar = $path;
            $user->save();
        }
    }
}
