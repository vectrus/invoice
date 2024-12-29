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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->unique()->nullable(false);
            $table->integer('contact_id')->nullable();
            $table->string('invoicenumber')->unique()->nullable(false);
            $table->string('vat21')->nullable();
            $table->string('vat0')->nullable();
            $table->string('amountexcl')->nullable(false);
            $table->string('amountincl')->nullable(false);
            $table->string('invoicedate');
            $table->string('amountpostageincl')->nullable();
            $table->string('duedate')->nullable();
            $table->string('payed')->nullable();
            $table->string('paydate')->nullable();
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
