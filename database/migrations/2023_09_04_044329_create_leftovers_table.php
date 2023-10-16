<?php

use Carbon\Carbon;
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
        Schema::create('leftovers', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('describe');
            $table->text('image');
            $table->tinyInteger('serving');
            $table->string('status', 125);
            $table->integer('min_point')->default(10);
            $table->foreignId('donor')->nullable();
            $table->string('location')->default('');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('expiry_at')->default(Carbon::now()->addDays(10));
            $table->timestamps();

            $table->foreign('donor')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leftovers');
    }
};
