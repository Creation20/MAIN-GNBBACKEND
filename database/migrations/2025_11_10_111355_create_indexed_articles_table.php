<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * This migration creates the indexed_articles table with all required fields
     * including conditional fields for Publication type articles
     */
    public function up(): void
    {
        Schema::create('indexed_articles', function (Blueprint $table) {
            $table->id();

            // Core Information
            $table->date('date')->comment('Date of publication');
            $table->string('articleOrNot')->comment('Article or Publication');
            $table->string('contentDesc')->comment('Juvenile or Adult');
            $table->string('matType')->comment('Newspaper, Journal, or Magazine');

            // Bibliographic Information
            $table->string('title')->nullable()->comment('Article/Publication title');
            $table->string('writersDetails')->comment('Author/Writer name');
            $table->string('newspaperJournalMagazineName')->nullable()->comment('Publication name');
            $table->string('numberOfPages')->nullable()->comment('Number of pages');
            $table->string('issn')->comment('Serial No/Vol No/ISSN');

            // Contact Information
            $table->string('poBox')->nullable();
            $table->string('poBoxLocation')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Additional Information
            $table->string('subject')->nullable();

            // Foreign Keys
            $table->foreignId('classification_id')->nullable()->constrained('classifications')->onDelete('set null');

            // Publication-Specific Fields (required when articleOrNot = 'Publication')
            $table->string('vendor')->nullable()->comment('Required for Publications: Legal Deposits, Donation, or Purchase');
            $table->string('copyNo')->nullable()->comment('Required for Publications: Copy number');
            $table->string('matForm')->nullable()->comment('Required for Publications: Material form');
            $table->string('placeOfPublication')->nullable()->comment('Required for Publications: Place of publication');
            $table->string('yearOfPublication')->nullable()->comment('Required for Publications: Year of publication');
            $table->string('price')->nullable()->comment('Required when vendor is Purchase');

            // Timestamps
            $table->timestamps();

            // Indexes for better query performance
            $table->index('articleOrNot');
            $table->index('matType');
            $table->index('vendor');
            $table->index('classification_id');
            $table->index('created_at');
            $table->index('date');

            // Add GNB-related columns
            $table->string('gnb_number')->nullable()->unique();
            $table->integer('gnb_year')->nullable()->index();
            $table->integer('gnb_sequence')->nullable();

            $table->string('class_number')->nullable();
            $table->string('sysOfClass')->nullable();
            // Add index for faster querying
            $table->index(['gnb_year', 'gnb_sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indexed_articles', function (Blueprint $table) {
            $table->dropColumn(['class_number', 'sysOfClass']);
        });


        Schema::dropIfExists('indexed_articles');
    }

};
