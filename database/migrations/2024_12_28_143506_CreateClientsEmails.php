<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class createEmails extends Migration
{
    public function up()
    {
        Schema::create('client_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('body');
            $table->string('sender_email');
            $table->string('recipient_email');
            $table->string('status')->default('sent');
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_emails');
    }
}
