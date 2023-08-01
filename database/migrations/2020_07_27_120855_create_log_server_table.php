<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_server', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->nullable();
            $table->string('status_log')->nullable();
            $table->string('services')->nullable();
            $table->unsignedBigInteger('server_id');
            $table->timestamps();
        });

        Schema::table('log_server', function (Blueprint $table) {
            $table->foreign('server_id')->references('id')->on('monitors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_server');
    }
}