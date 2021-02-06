<?php

use App\Models\Email;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable()->index();
            $table->string('sender')->index();
            $table->string('recipient')->index();
            $table->longText('text')->nullable();
            $table->longText('html')->nullable();
            $table->string('status')->default(Email::STATUS_INIT);
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
        Schema::dropIfExists('emails');
    }
}
