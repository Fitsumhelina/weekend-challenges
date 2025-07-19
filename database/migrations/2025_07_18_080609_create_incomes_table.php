<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
      Schema::create('incomes', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->decimal('amount', 15, 2);
        $table->unsignedBigInteger('source')->nullable();
        $table->foreign('source')->references('id')->on('users')->nullOnDelete();
        $table->text('description')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->timestamps();
        $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
    });

    }

   
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
