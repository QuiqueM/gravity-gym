<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_days');
            $table->integer('class_limit')->nullable();
            $table->decimal('price', 8, 2);
            $table->boolean('requires_payment')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_membership_initial')->default(false);
            $table->jsonb('features')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
