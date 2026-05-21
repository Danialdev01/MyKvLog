<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('references', function (Blueprint $table) {
            $table->id('reference_id');
            $table->unsignedBigInteger('log_id');
            $table->foreign('log_id')->references('log_id')->on('logs')->onDelete('cascade');
            $table->string('reference_file')->nullable();
            $table->string('reference_image')->nullable();
            $table->string('reference_diagram')->nullable();
            $table->timestamp('reference_created_at')->useCurrent();
            $table->string('reference_status')->default('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};