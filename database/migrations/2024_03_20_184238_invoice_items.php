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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->unique()->nullable(false);
            $table->integer('sortorder');
            $table->string('vat21');
            $table->string('vat0');
            $table->string('amountexcl')->nullable(false);
            $table->string('amountincl')->nullable(false);
            $table->integer('ispostage')->nullable();
            $table->integer('number_of_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
