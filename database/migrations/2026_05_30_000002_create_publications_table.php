<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tag', 60)->default('Report');
            $table->string('category', 100)->nullable();
            $table->unsignedSmallInteger('year');
            $table->text('description');
            $table->longText('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->string('accent', 40)->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['category', 'year']);
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
