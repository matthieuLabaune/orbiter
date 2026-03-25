<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagram_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagram_id')->constrained()->onDelete('cascade');
            $table->text('mermaid_source');
            $table->integer('version');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagram_versions');
    }
};
