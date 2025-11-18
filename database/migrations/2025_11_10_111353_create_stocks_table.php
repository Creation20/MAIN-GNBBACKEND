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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('vendor')->nullable();;
            $table->string('matForm')->nullable();;
            $table->string('matType')->nullable();;
            $table->string('contentDesc')->nullable();;
            $table->string('title')->nullable();;
            $table->string('author');;
            $table->string('copyNo')->nullable();;
            $table->string('accessionNo')->nullable();;
            $table->string('areaOfResponsibility')->nullable();;
            $table->string('editionStatement')->nullable();;
            $table->string('publishersName')->nullable();;
            $table->string('placeOfPublication')->nullable();;
            $table->string('yearOfPublication')->nullable();;
            $table->string('preliminaryPages')->nullable();;
            $table->string('numberOfPages')->nullable();;
            $table->string('heightOfBook')->nullable();;
            $table->string('poBox')->nullable();;
            $table->string('poBoxLocation')->nullable();
            $table->string('telephone')->nullable();;
            $table->string('email')->nullable();;
            $table->string('website')->nullable();;
            $table->string('illustrations')->nullable();;
            $table->string('subject')->nullable();;
            $table->string('nonFictionType')->nullable();;
            $table->string('isbn')->nullable();
            $table->string('gnb')->nullable();           
            $table->string('class_number')->nullable();  
            $table->string('sysOfClass')->nullable(); 
            $table->foreignId('classification_id')->nullable()->constrained('classifications')->onDelete('set null');
            $table->boolean('is_gnb_stock')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['gnb', 'sysOfClass', 'class_number']);
        });
    }
};
