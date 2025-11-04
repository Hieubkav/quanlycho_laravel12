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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('from_day');
            $table->date('to_day');
            $table->timestamp('generated_at');
            $table->foreignId('created_by_admin_id')->constrained('users');
            $table->json('summary_rows')->nullable();
            $table->json('included_survey_ids');
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('generated_at');
            $table->index(['from_day', 'to_day']);
            $table->index(['active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
