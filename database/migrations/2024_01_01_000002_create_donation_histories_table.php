<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donor_id');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->date('donation_date');
            $table->integer('units')->default(1);
            $table->string('hospital_name')->nullable();
            $table->string('location')->nullable();
            $table->string('recipient_name')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('donor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_histories');
    }
};
