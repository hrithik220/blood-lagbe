<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('patient_name');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->string('hospital_name');
            $table->text('hospital_address');
            $table->string('district');
            $table->string('area')->nullable();
            $table->date('required_date');
            $table->integer('bags_needed')->default(1);
            $table->string('contact_number');
            $table->enum('emergency_level', ['normal', 'urgent', 'critical'])->default('normal');
            $table->enum('status', ['pending', 'approved', 'in_progress', 'fulfilled', 'cancelled', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
