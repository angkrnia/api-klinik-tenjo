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
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_id');
            $table->timestamp('time_start')->nullable();
            $table->timestamp('time_end')->nullable();
            $table->string('duration')->nullable();
            $table->string('initial_conditions')->nullable();
            $table->text('result')->nullable();
            $table->text('note')->nullable();

            $table->foreign('history_id')->on('histories')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observations');
    }
};
