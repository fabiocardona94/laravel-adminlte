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
        Schema::create('tr_evaluation_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('evaluation_user_id');
            $table->primary(['evaluation_id', 'question_id']);

            $table->timestamps();

            $table->foreign('evaluation_id')->references('id')->on('evaluations')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    
            $table->foreign('question_id')->references('id')->on('bank_questions')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    
            $table->foreign('evaluation_user_id')->references('id')->on('evaluation_users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_evaluation_questions');
    }
};
