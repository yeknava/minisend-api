<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('email_id')->unsigned()->nullable();
            $table->foreign('email_id')->references('id')->on('emails');
            $table->string('title', 100)->nullable();
            $table->string('driver')->nullable();
			$table->string('filename', 255);
			$table->string('extension', 12);
			$table->string('mimetype', 30);
			$table->string('path');
			$table->string('sha1', 40);
			$table->bigInteger('filesize')->unsigned();
			$table->string('uploader_ip', 45);
			$table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
