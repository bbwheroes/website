<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('module', 3);
            $table->string('teacher', 4);
            $table->string('task_name');
            $table->string('slugified_task_name');
            $table->string('username');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('approved')->default(false);
            $table->timestamps();
            
            $table->index(['module', 'teacher', 'task_name', 'username']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
