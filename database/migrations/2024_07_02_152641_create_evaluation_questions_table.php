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
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evaluation');
            $table->unsignedBigInteger('id_question');
            $table->primary(['id_evaluation', 'id_question']);
            $table->foreign('id_evaluation')->references('id')->on('evaluations')
                    ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_question')->references('id')->on('bank_questions')
                    ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};
