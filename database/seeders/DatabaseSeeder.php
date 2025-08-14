<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuarios base
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $instructors = User::factory(3)->instructor()->create();
        $members = User::factory(20)->create();

        // Planes de membresía
        $plans = \App\Models\MembershipPlan::factory()->count(3)->create();

        // Membresías y pagos para miembros
        foreach ($members as $member) {
            $plan = $plans->random();
            $startsAt = now()->subDays(fake()->numberBetween(0, 20));
            $endsAt = (clone $startsAt)->addDays($plan->duration_days);

            $membership = \App\Models\Membership::factory()->for($member)->for($plan, 'plan')->create([
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => 'active',
            ]);

            \App\Models\Payment::factory()->for($member)->for($membership)->create([
                'amount' => $plan->price,
                'status' => 'paid',
            ]);
        }

        // Tipos de clase, clases y horarios
        $classTypes = \App\Models\ClassType::factory()->count(4)->create();

        $classes = collect();
        foreach ($classTypes as $type) {
            $classes = $classes->merge(
                \App\Models\GymClass::factory()->count(3)->create([
                    'class_type_id' => $type->id,
                    'instructor_id' => $instructors->random()->id,
                ])
            );
        }

        $schedules = collect();
        foreach ($classes as $class) {
            $schedules = $schedules->merge(
                \App\Models\ClassSchedule::factory()->count(5)->create([
                    'class_id' => $class->id,
                ])
            );
        }

        // Registros a clases
        foreach ($schedules as $schedule) {
            $attendees = $members->random(fake()->numberBetween(3, 10));
            foreach ($attendees as $user) {
                // Evitar duplicados por unique index
                if (!\App\Models\ClassRegistration::where('class_schedule_id', $schedule->id)->where('user_id', $user->id)->exists()) {
                    \App\Models\ClassRegistration::factory()->create([
                        'class_schedule_id' => $schedule->id,
                        'user_id' => $user->id,
                        'status' => 'registered',
                    ]);
                }
            }
        }

        // Asistencias (al azar de los registrados)
        foreach ($schedules as $schedule) {
            $registered = \App\Models\ClassRegistration::where('class_schedule_id', $schedule->id)->pluck('user_id');
            $present = $registered->random(min($registered->count(), fake()->numberBetween(2, 8)));
            foreach ($present as $userId) {
                \App\Models\Attendance::factory()->create([
                    'user_id' => $userId,
                    'class_schedule_id' => $schedule->id,
                    'checked_in_at' => $schedule->starts_at,
                ]);
            }
        }

        // Rutinas y ejercicios
        foreach ($members as $member) {
            $routines = \App\Models\WorkoutRoutine::factory()->count(fake()->numberBetween(1, 3))->for($member)->create();
            foreach ($routines as $routine) {
                \App\Models\WorkoutExercise::factory()->count(fake()->numberBetween(3, 6))->create([
                    'routine_id' => $routine->id,
                ]);
            }
        }
    }
}
