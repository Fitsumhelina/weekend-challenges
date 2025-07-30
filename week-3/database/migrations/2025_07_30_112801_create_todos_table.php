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
            Schema::create('todos', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('status', ['not_started', 'todo', 'in_progress', 'finished'])->default('not_started');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
