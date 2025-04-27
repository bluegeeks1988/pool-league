<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('fixtures', function (Blueprint $table) {
        $table->id();
        $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
        $table->foreignId('home_team_id')->constrained('teams')->cascadeOnDelete();
        $table->foreignId('away_team_id')->constrained('teams')->cascadeOnDelete();
        $table->date('match_date')->nullable();
        $table->time('match_time')->nullable();
        $table->integer('home_score')->nullable();
        $table->integer('away_score')->nullable();
        $table->enum('status', ['scheduled', 'completed'])->default('scheduled');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
