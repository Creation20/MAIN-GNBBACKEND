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
            $table->dropForeign(['classification_id']);
            $table->dropColumn('classification_id');

            // Core Information
            $table->date('date')->comment('Date of publication');
            $table->string('articleOrNot')->comment('Article or Publication');
            $table->string('contentDesc')->comment('Juvenile or Adult');
            $table->string('matType')->comment('Newspaper, Journal, or Magazine');

            // Bibliographic Information
            $table->string('title')->comment('Article/Publication title');
            $table->string('writersDetails')->comment('Author/Writer name');
            $table->string('newspaperJournalMagazineName')->comment('Publication name');
            $table->string('numberOfPages')->comment('Number of pages');
            $table->string('issn')->comment('Serial No/Vol No/ISSN');

            // Contact Information
            $table->string('poBox');
            $table->string('poBoxLocation');
            $table->string('telephone');
            $table->string('email');
            $table->string('website');

            // Additional Information
            $table->string('subject')->nullable()->change();


            // Publication-Specific Fields (required when articleOrNot = 'Publication')
            $table->string('vendor')->nullable()->comment('Required for Publications: Legal Deposits, Donation, or Purchase');
            $table->string('copyNo')->nullable()->comment('Required for Publications: Copy number');
            $table->string('matForm')->nullable()->comment('Required for Publications: Material form');
            $table->string('placeOfPublication')->nullable()->comment('Required for Publications: Place of publication');
            $table->string('yearOfPublication')->nullable()->comment('Required for Publications: Year of publication');
            $table->string('price')->nullable()->comment('Required when vendor is Purchase');

            // Foreign Keys
            $table->foreignId('classification_id')->nullable()->after('subject')->constrained('classifications')->onDelete('set null');

            // Timestamps
            $table->timestamps();

            // Indexes for better query performance
            $table->index('articleOrNot');
            $table->index('matType');
            $table->index('vendor');
            $table->index('classification_id');
            $table->index('created_at');
            $table->index('date');
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