<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalogues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('catalogue_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->enum('status', ['draft', 'pending_review', 'published', 'rejected', 'expired', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            $table->unique(['shop_id', 'slug']);
            $table->index(['status', 'valid_from', 'valid_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogues');
    }
};
