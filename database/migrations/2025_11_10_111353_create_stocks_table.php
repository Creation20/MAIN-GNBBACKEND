<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration creates the stocks table with all required fields
     * including conditional fields for different vendor types
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            
            // Core Information
            $table->date('date')->nullable()->comment('Date received');
            $table->string('vendor')->nullable()->comment('Legal Deposit, Donation, Purchase');
            $table->string('matForm')->nullable()->comment('Material form: Hardcopy, Softcopy, Audio, etc.');
            $table->string('matType')->nullable()->comment('Fiction or Non-fiction');
            $table->string('contentDesc')->nullable()->comment('Juvenile or Adult');
            
            // Bibliographic Information
            $table->string('title', 500)->nullable()->comment('Book title');
            $table->string('author')->comment('Author name - required field');
            $table->string('copyNo')->nullable()->comment('Copy number');
            $table->string('accessionNo', 100)->nullable()->comment('Accession number');
            
            // Publication Details
            $table->string('areaOfResponsibility')->nullable();
            $table->string('editionStatement', 100)->nullable();
            $table->string('publishersName')->nullable();
            $table->string('placeOfPublication')->nullable();
            $table->string('yearOfPublication', 4)->nullable();
            
            // Physical Description
            $table->string('preliminaryPages', 50)->nullable();
            $table->string('numberOfPages', 50)->nullable();
            $table->string('heightOfBook', 50)->nullable()->comment('Height in cm');
            $table->string('illustrations')->nullable()->comment('Illustrations/Diagrams/Maps');
            
            // Identifiers
            $table->string('isbn')->nullable()->comment('ISBN or ISMN number');
            $table->string('gnb')->nullable()->comment('Ghana National Bibliography number');
            $table->string('class_number')->nullable()->comment('Classification number');
            $table->string('sysOfClass')->nullable()->comment('System of classification (e.g., DDC)');
            
            // Contact Information
            $table->string('poBox', 100)->nullable();
            $table->string('poBoxLocation')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Additional Information
            $table->string('subject')->nullable();
            $table->string('nonFictionType')->nullable()->comment('Required when matType is Non-fiction: Other or Textbook');
            
            // Conditional Fields Based on Vendor
            $table->string('materialSource')->nullable()->comment('Required when vendor is Donation: Local or Foreign');
            $table->string('price')->nullable()->comment('Required when vendor is Purchase');
            
            // Foreign Keys and Flags
            $table->foreignId('classification_id')
                ->nullable()
                ->constrained('classifications')
                ->onDelete('set null')
                ->comment('Link to classifications table');
            
            $table->boolean('is_gnb_stock')
                ->default(false)
                ->comment('Whether this is GNB stock');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('vendor');
            $table->index('matType');
            $table->index('is_gnb_stock');
            $table->index('classification_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};