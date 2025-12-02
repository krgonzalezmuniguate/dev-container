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
        Schema::create('request_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->enum('status', ['pending', 'authorized', 'rejected'])->default('pending');
            $table->text('path_file')->nullable();
            $table->date('date_authorized')->nullable();
            $table->text('reason_rejection')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_supplies');
    }
};
