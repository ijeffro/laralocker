<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use HT2\LaraLocker\Models\Statement;

class CreatexAPIVerbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xapi_verbs', function (Blueprint $table) {
            $table->increments('id');
            $table->json('verbs');
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