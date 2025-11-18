<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('indexed_articles', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('contentDesc');
            $table->string('writersDetails');
            $table->string('title');
            $table->string('issn');
            $table->string('articleOrNot');
            $table->string('matType');
            $table->string('newspaperJournalMagazineName');
            $table->string('numberOfPages');
            $table->string('poBox');
            $table->string('poBoxLocation');
            $table->string('telephone');
            $table->string('email');
            $table->string('subject');
            $table->string('website');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indexed_articles');
    }
};
