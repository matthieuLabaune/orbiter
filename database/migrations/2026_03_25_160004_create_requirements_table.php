<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('ref');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('acceptance_criteria')->nullable();
            $table->string('priority')->default('P2');
            $table->string('vv_status')->default('untested');
            $table->integer('version')->default(1);
            $table->integer('risk_impact')->nullable();
            $table->integer('risk_probability')->nullable();
            $table->integer('risk_detectability')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
