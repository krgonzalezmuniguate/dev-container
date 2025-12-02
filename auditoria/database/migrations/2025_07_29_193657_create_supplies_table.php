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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->integer('maximum_stock')->nullable();
            $table->string('type_acquisition')->nullable();
            $table->date('expiration_date')->nullable();
            $table->foreignId('provider_id')->nullable()->constrained('providers')->onDelete('cascade');
            $table->foreignId('supply_category_id')->nullable()->constrained('supply_categories')->onDelete('cascade');
            $table->foreignId('measurement_unit_id')->nullable()->constrained('measurement_units')->onDelete('cascade');
            $table->char('state', 1)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
