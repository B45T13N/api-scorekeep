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
            $table->uuid()->primary();
            $table->string('address');
            $table->string('category');
            $table->dateTime('gameDate');
            $table->boolean('isCancelled')->nullable()->default(false);
            $table->dateTime('cancelledDate')->nullable();
            $table->boolean('isDeleted')->nullable()->default(false);
            $table->dateTime('deletedDate')->nullable();

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
