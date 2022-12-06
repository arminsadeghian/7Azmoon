<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('quiz_id')->nullable();
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->json('options')->nullable();
            $table->float('score')->nullable();
            $table->unsignedTinyInteger('is_active')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
