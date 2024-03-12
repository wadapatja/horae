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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taskable_id');
            $table->enum('taskable_type', ['plant']);
            $table->string('title', 255);
            $table->enum('type', ['plant', 'harvest', 'prune']);
            $table->enum('start_month', [1,2,3,4,5,6,7,8,9,10,11,12]);
            $table->enum('end_month', [1,2,3,4,5,6,7,8,9,10,11,12]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
