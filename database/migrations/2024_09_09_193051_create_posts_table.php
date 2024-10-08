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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->references('id')->on('post_categories');
            $table->foreignId('parent_id')->nullable()->references('id')->on('posts');
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->text('content');
            $table->string('status')->default('draft');
            $table->string('type')->default('post');
            $table->string('visibility')->default('public');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
