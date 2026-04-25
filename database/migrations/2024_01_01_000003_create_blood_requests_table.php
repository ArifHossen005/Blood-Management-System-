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
            $table->unsignedBigInteger('requester_id');
            $table->string('patient_name');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->integer('units_needed')->default(1);
            $table->string('hospital_name');
            $table->string('hospital_address')->nullable();
            $table->string('contact_number', 20);
            $table->date('needed_date');
            $table->enum('urgency', ['normal', 'urgent', 'emergency'])->default('normal');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'fulfilled', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('fulfilled_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fulfilled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
