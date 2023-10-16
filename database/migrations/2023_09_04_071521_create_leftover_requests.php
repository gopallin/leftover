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
        Schema::create('leftover_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leftover_id');
            $table->foreignId('donee');
            $table->text('greetings');
            $table->integer('point');
            $table->string('status', 100);
            $table->timestamps();

            $table->foreign('leftover_id')->references('id')->on('leftovers');
            $table->foreign('donee')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leftover_requests');
    }
};
