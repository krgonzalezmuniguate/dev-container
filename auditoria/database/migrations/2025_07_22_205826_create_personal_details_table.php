<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date_of_birth');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('nit',13)->unique();
            $table->string('dpi',13)->unique();
            $table->string('gender');
            $table->string('email_personal')->nullable();
            $table->string('city')->nullable();
            $table->string('department')->nullable();
            $table->string('country')->nullable();
            $table->string('ethnic_group')->nullable();
            $table->string('profile_picture_url')->nullable();
            $table->string('code_job');
            $table->char('state', 1)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_details');
    }
};
