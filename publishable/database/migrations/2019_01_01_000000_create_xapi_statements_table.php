<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
//use HT2\LaraLocker\Models\Statement;

class CreatexAPIStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xapi_statements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('statement');
            $table->enum('status', Statement::$statuses)->default(Statement::STATUS_INACTIVE);
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