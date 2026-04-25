<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->date('last_donation_date')->nullable();
            $table->integer('total_donations')->default(0);
            $table->enum('role', ['admin', 'donor'])->default('donor');
            $table->enum('status', ['temporary', 'approved', 'rejected', 'banned'])->default('temporary');
            $table->boolean('is_available')->default(true);
            $table->text('health_notes')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->boolean('contact_visible')->default(true);
            $table->boolean('address_visible')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['blood_group', 'status', 'is_available']);
            $table->index('district');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
