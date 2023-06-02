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
        Schema::create('timekeepers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');

            $table->bigInteger('gameId')->unsigned();

            $table->foreign('gameId')->references('id')->on('games');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timekeepers');
    }
};
