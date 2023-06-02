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

            $table->bigInteger('timekeeperId')->unsigned();
            $table->bigInteger('secretaryId')->unsigned();
            $table->bigInteger('roomManagerId')->unsigned();
            $table->bigInteger('visitorTeamId')->unsigned();

            $table->foreign('timekeeperId')->references('id')->on('timekeepers')->onDelete('cascade');
            $table->foreign('secretaryId')->references('id')->on('secretaries')->onDelete('cascade');
            $table->foreign('roomManagerId')->references('id')->on('room_managers')->onDelete('cascade');
            $table->foreign('visitorTeamId')->references('id')->on('visitor_teams')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
