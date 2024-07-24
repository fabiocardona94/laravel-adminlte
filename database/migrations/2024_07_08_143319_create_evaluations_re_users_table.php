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
        Schema::create('evaluations_re_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('status')->default(1);
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->timestamps();

            $table->foreign('evaluation_id')->references('id')->on('evaluations')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
    
            $table->foreign('user_id')->references('id')->on('users')
                    ->onDelete('set null')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_re_users');
    }
};
