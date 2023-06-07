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
        Schema::table('timekeepers', function (Blueprint $table)
        {
            $table->bigInteger('gameId')->unsigned();

            $table->foreign('gameId')->references('id')->on('games');
        });

        Schema::table('secretaries', function (Blueprint $table)
        {
            $table->bigInteger('gameId')->unsigned();

            $table->foreign('gameId')->references('id')->on('games');
        });

        Schema::table('room_managers', function (Blueprint $table)
        {
            $table->bigInteger('gameId')->unsigned();

            $table->foreign('gameId')->references('id')->on('games');
        });

        Schema::table('games', function (Blueprint $table)
        {
            $table->bigInteger('visitorTeamId')->unsigned();
            $table->bigInteger('timekeeperId')->unsigned()->nullable();
            $table->bigInteger('localTeamId')->unsigned()->nullable();
            $table->bigInteger('secretaryId')->unsigned()->nullable();
            $table->bigInteger('roomManagerId')->unsigned()->nullable();

            $table->foreign('localTeamId')->references('id')->on('local_teams');
            $table->foreign('timekeeperId')->references('id')->on('timekeepers')->onDelete('cascade');
            $table->foreign('secretaryId')->references('id')->on('secretaries')->onDelete('cascade');
            $table->foreign('roomManagerId')->references('id')->on('room_managers')->onDelete('cascade');
            $table->foreign('visitorTeamId')->references('id')->on('visitor_teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
