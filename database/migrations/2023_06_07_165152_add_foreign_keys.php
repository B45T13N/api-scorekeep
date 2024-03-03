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
            $table->uuid('gameId');
            $table->uuid('volunteerTypeId');

            $table->foreign('gameId')->references('uuid')->on('games');
            $table->foreign('volunteerTypeId')->references('uuid')->on('volunteer_types');
        });

        Schema::table('games', function (Blueprint $table)
        {
            $table->uuid('visitorTeamId');
            $table->uuid('timekeeperId')->nullable();
            $table->uuid('localTeamId')->nullable();
            $table->uuid('secretaryId')->nullable();
            $table->uuid('roomManagerId')->nullable();
            $table->uuid('drinkManagerId')->nullable();

            $table->foreign('localTeamId')->references('uuid')->on('local_teams');
            $table->foreign('timekeeperId')->references('uuid')->on('volunteers')->onDelete('cascade');
            $table->foreign('secretaryId')->references('uuid')->on('volunteers')->onDelete('cascade');
            $table->foreign('roomManagerId')->references('uuid')->on('volunteers')->onDelete('cascade');
            $table->foreign('drinkManagerId')->references('uuid')->on('volunteers')->onDelete('cascade');
            $table->foreign('visitorTeamId')->references('uuid')->on('visitor_teams')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table)
        {
            $table->uuid('localTeamId');
            $table->foreign('localTeamId')->references('uuid')->on('local_teams');
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
            $table->dropForeign(['drinkManagerId']);
            $table->dropForeign(['visitorTeamId']);

            $table->dropColumn('localTeamId');
            $table->dropColumn('timekeeperId');
            $table->dropColumn('secretaryId');
            $table->dropColumn('roomManagerId');
            $table->dropColumn('drinkManagerId');
            $table->dropColumn('visitorTeamId');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['localTeamId']);
            $table->dropColumn('localTeamId');
        });
    }
};
