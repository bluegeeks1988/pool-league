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
    Schema::create('competitions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('league_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->enum('type', ['round_robin', 'knockout'])->default('round_robin');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
