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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('category');
            $table->string('gameDate');

            $table->integer('timekeeper_id')->unsigned();
            $table->integer('secretary_id')->unsigned();
            $table->integer('room_manager_id')->unsigned();
            $table->integer('visitor_team_id')->unsigned();

            $table->foreign('timekeeper_id')->references('id')->on('time_keepers');
            $table->foreign('secretary_id')->references('id')->on('secretaries');
            $table->foreign('room_manager_id')->references('id')->on('room_managers');
            $table->foreign('visitor_team_id')->references('id')->on('visitor_teams');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game');
    }
};
