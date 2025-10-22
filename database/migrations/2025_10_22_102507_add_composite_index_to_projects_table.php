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
        Schema::table('projects', function (Blueprint $table) {
            // Composite index for optimizing published projects query
            // Query: WHERE status = 'published' ORDER BY project_date DESC, created_at DESC
            $table->index(['status', 'project_date', 'created_at'], 'projects_status_dates_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_status_dates_index');
        });
    }
};
