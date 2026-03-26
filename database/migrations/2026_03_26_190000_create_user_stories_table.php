<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ref');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('acceptance_criteria')->nullable();
            $table->string('priority')->default('P2');
            $table->string('status')->default('open'); // open, in_progress, done, closed
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('requirements', function (Blueprint $table) {
            $table->foreignId('user_story_id')->nullable()->after('module_id')->constrained()->nullOnDelete();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('user_story_id')->nullable()->after('requirement_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_story_id');
        });
        Schema::table('requirements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_story_id');
        });
        Schema::dropIfExists('user_stories');
    }
};
