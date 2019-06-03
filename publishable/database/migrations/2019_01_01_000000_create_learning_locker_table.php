<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use HT2\LaraLocker\Models\Statement;

class CreateLearningLockerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_locker', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_id');
            $table->string('url');
            $table->integer('key');
            $table->integer('secret');
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
        Schema::dropIfExists('xapi_statements');
    }
}
