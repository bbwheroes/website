<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contribution_requests', function (Blueprint $table) {
            $table->id();
            $table->string('module', 3);
            $table->string('teacher', 4);
            $table->string('task_name');
            $table->string('slugified_task_name');
            $table->json('collaborators')->nullable();
            $table->string('github_username');
            $table->string('status')->default('pending'); // pending, accepted, declined
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contribution_requests');
    }
};
