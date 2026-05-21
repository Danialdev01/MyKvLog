<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->integer('log_day');
            $table->string('log_location')->nullable();
            $table->date('log_date');
            $table->string('log_section')->nullable();
            $table->text('log_summary')->nullable();
            $table->text('log_knowledge')->nullable();
            $table->text('log_tools')->nullable();
            $table->text('log_note')->nullable();
            $table->timestamp('log_updated_at')->useCurrent();
            $table->timestamp('log_created_at')->useCurrent();
            $table->string('log_status')->default('draft');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};