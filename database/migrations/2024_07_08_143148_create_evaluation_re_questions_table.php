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
        Schema::create('evaluation_re_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('question_id');
            $table->tinyInteger('status')->default(0);
            $table->primary(['evaluation_id', 'question_id']);

            $table->timestamps();

            $table->foreign('evaluation_id')->references('id')->on('evaluations')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    
            $table->foreign('question_id')->references('id')->on('evaluation_bank_questions')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_re_questions');
    }
};
