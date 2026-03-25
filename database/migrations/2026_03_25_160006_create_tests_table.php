<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('ref');
            $table->string('title');
            $table->text('procedure')->nullable();
            $table->text('expected_result')->nullable();
            $table->string('type')->default('manual');
            $table->timestamps();

            $table->unique(['project_id', 'ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
