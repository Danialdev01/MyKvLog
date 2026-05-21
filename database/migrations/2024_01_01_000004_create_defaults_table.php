<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defaults', function (Blueprint $table) {
            $table->id('default_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('default_internship_period')->default(90);
            $table->string('default_department')->nullable();
            $table->string('default_company')->nullable();
            $table->text('default_job_scope')->nullable();
            $table->timestamp('default_updated_at')->nullable();
            $table->timestamp('default_created_at')->nullable();
            $table->string('default_status')->default('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defaults');
    }
};