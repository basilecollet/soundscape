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
        Schema::create('section_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_key'); // Identifier for the section
            $table->string('page'); // Page identifier (home, about, contact)
            $table->boolean('is_enabled')->default(true); // Whether the section is enabled
            $table->timestamps();

            $table->unique(['section_key', 'page']); // Ensure one setting per section per page
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_settings');
    }
};
