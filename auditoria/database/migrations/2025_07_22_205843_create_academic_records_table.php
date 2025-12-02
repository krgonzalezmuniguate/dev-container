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
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('degree'); // E.g., "Bachelor of Science"
            $table->string('major')->nullable(); // E.g., "Computer Science"
            $table->string('institution'); // E.g., "Universidad de San Carlos de Guatemala"
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable(); // Can be graduation_date
            $table->boolean('is_current')->default(false); // Is this an ongoing program?
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->char('state', 1)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
