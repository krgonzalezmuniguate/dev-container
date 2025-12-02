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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->timestampTz('reserved_until_datetime');
            $table->boolean('is_fulfilled')->default(false);
            $table->foreignId('supply_id')->constrained('supplies')->onDelete('cascade');
            $table->foreignId('request_id')->constrained('request_supplies')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->enum('adjustment_type', ['1', '2'])->comment('Tipo de operación: Añadir o Sustraer');
            $table->integer('quantity_change')->nullable();
            $table->timestamp('request_datetime')->useCurrent();
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->timestamp('authorization_datetime')->nullable();
            $table->foreignId('supply_id')->constrained('supplies')->onDelete('cascade');
            $table->foreignId('requested_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('authorized_by_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('inventory_adjustments');
    }
};
