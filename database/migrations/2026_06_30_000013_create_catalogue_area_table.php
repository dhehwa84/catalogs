<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalogue_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('suburb_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['catalogue_id', 'suburb_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogue_area');
    }
};
