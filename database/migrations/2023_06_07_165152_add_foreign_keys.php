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
        Schema::table('volunteers', function (Blueprint $table)
        {
            $table->bigInteger('gameId')->unsigned();
            $table->bigInteger('volunteerTypeId')->unsigned();

            $table->foreign('gameId')->references('id')->on('games');
            $table->foreign('volunteerTypeId')->references('id')->on('volunteer_types');
        });

        Schema::table('games', function (Blueprint $table)
        {
            $table->bigInteger('visitorTeamId')->unsigned();
            $table->bigInteger('timekeeperId')->unsigned()->nullable();
            $table->bigInteger('localTeamId')->unsigned()->nullable();
            $table->bigInteger('secretaryId')->unsigned()->nullable();
            $table->bigInteger('roomManagerId')->unsigned()->nullable();

            $table->foreign('localTeamId')->references('id')->on('local_teams');
            $table->foreign('timekeeperId')->references('id')->on('volunteers')->onDelete('cascade');
            $table->foreign('secretaryId')->references('id')->on('volunteers')->onDelete('cascade');
            $table->foreign('roomManagerId')->references('id')->on('volunteers')->onDelete('cascade');
            $table->foreign('visitorTeamId')->references('id')->on('visitor_teams')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table)
        {
            $table->unsignedBigInteger('localTeamId');
            $table->foreign('localTeamId')->references('id')->on('local_teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropForeign(['gameId']);
            $table->dropColumn('gameId');
            $table->dropForeign(['volunteerTypeId']);
            $table->dropColumn('volunteerTypeId');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['localTeamId']);
            $table->dropForeign(['timekeeperId']);
            $table->dropForeign(['secretaryId']);
            $table->dropForeign(['roomManagerId']);
            $table->dropForeign(['visitorTeamId']);

            $table->dropColumn('localTeamId');
            $table->dropColumn('timekeeperId');
            $table->dropColumn('secretaryId');
            $table->dropColumn('roomManagerId');
            $table->dropColumn('visitorTeamId');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['localTeamId']);
            $table->dropColumn('localTeamId');
        });
    }
};
