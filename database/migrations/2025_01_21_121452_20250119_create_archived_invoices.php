<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('archived_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('invoice_date');
            $table->decimal('amount_incl', 10, 2);
            $table->decimal('amount_excl', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->text('description');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    public function down()
    {
        Schema::dropIfExists('archived_invoices');
    }
}
