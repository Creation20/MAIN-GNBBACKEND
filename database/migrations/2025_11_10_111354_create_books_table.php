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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('authorname');
            $table->string('publisher')->nullable();
            $table->string('isbn')->nullable()->unique();
            $table->string('accessionNo')->nullable()->unique();
            $table->string('copyNo')->nullable()->unique();
            $table->string('purchasePrice')->nullable()->unique();
            $table->foreignId('stock_id')->nullable()->constrained('stocks')->onDelete('cascade');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
