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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('social_title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('upper_title')->nullable();

            $table->text('news_body')->nullable();
            $table->text('summery')->nullable();
            $table->text('social_summery')->nullable();

            $table->json('author_id')->nullable();
            // $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');

            $table->json('category_id')->nullable();
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->json('tag_id')->nullable();
            // $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->json('lead_position')->nullable();
            $table->boolean('show_created_at')->default(1);
            $table->boolean('show_updated_at')->default(1);
            $table->boolean('show_featured_image')->default(1);
            $table->enum('language', ['en', 'bn'])->default('en');

            $table->drafts();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
