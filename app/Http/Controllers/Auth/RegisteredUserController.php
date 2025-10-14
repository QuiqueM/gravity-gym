<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Role;
use App\Models\MembershipPlan;
use App\Models\Membership;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => 'required|string|min:10|max:20|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Generar el QR con id y email y almacenarlo en S3
        $qrData = json_encode(['id' => $user->id, 'email' => $user->email]);
        $localPath = sys_get_temp_dir() . '/user_' . $user->id . '.png';
        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate($qrData, $localPath);
        $s3Path = 'qrCodes/user_' . $user->id . '.png';
        $fileStream = fopen($localPath, 'r');
        Storage::disk('s3')->put($s3Path, $fileStream);
        fclose($fileStream);
        unlink($localPath);
        $user->qr_code = $s3Path;
        $user->save();

        // Asignar el rol 'Member' al usuario registrado
        $memberRole = Role::where('name', 'Member')->first();
        if ($memberRole) {
            $user->roles()->attach($memberRole->id);
        }
        // Asignar el plan de membresía inicial al usuario registrado
        $initialPlan = MembershipPlan::where('is_membership_initial', true)->first();
        if ($initialPlan) {
            Membership::create([
                'user_id' => $user->id,
                'membership_plan_id' => $initialPlan->id,
                'starts_at' => now()->tz('America/Mexico_City'),
                'ends_at' => now()->addDays($initialPlan->duration_days)->tz('America/Mexico_City'),
                'is_active' => true,
                'remaining_classes' => $initialPlan->class_limit,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
