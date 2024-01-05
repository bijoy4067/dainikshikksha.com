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
            //image
            $table->string('featured_image')->nullable();
            $table->string('social_featured_image')->nullable();

            $table->foreignId('author_id')->constrained(
                table: 'authors',
                indexName: 'id'
            );

            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->json('lead_position')->nullable();
            $table->boolean('show_created_at')->default(1);
            $table->boolean('show_updated_at')->default(1);
            $table->boolean('show_featured_image')->default(1);
            $table->enum('language', ['en', 'bn'])->default('en');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('category_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('news_id');

            $table->timestamps();
        });

        Schema::create('news_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id');
            $table->foreignId('tag_id');

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
