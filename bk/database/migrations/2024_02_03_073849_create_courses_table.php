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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('Course_name');
            $table->string('course_image');
            $table->double('price');
            $table->double('offered_price');
            $table->string('course_slug');
            $table->text('description');
            $table->longText('full_desctiption');
            $table->string('feedback_rating');
            $table->string('instructors');
            $table->string('duration');
            $table->string('language');
            $table->string('live_session');
            $table->string('validity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
