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
        Schema::create('evaluation_re_user_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluations_re_users_id');
            $table->unsignedBigInteger('evaluation_bank_questions_id');
            $table->unsignedBigInteger('evaluation_question_option_id');

            $table->timestamps();

            $table->foreign('evaluations_re_users_id')->references('id')->on('evaluations_re_users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('evaluation_bank_questions_id')->references('id')->on('evaluation_bank_questions')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('evaluation_question_option_id')->references('id')->on('evaluation_question_options')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_re_user_answers');
    }
};
