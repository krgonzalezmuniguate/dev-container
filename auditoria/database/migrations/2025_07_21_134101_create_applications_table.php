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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');  // Relación con el menú (módulo)
            $table->string('name');  // Nombre de la aplicación, como 'Gestión del Personal'
            $table->string('url');
            $table->string('icon');
            $table->string('color');
            $table->string('description');
            $table->string('slug');
            $table->char('state', 1)->default('1');
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
