<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoPathToInvoiceTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->string('logo_path')->nullable();
        });
    }

    public function down()
    {
        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });
    }
}
