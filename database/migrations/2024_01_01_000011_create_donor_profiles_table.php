<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->string('district');
            $table->string('area')->nullable();
            $table->string('phone');
            $table->date('last_donation_date')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_public')->default(true);
            $table->text('bio')->nullable();
            $table->integer('total_donations')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donor_profiles');
    }
};
