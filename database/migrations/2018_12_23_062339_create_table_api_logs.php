<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableApiLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string("ip");
            $table->string("useragent");
            $table->text("request_data")->nullable();
            $table->text("response_data")->nullable();
            $table->text("old_data")->nullable();
            $table->text("new_data")->nullable();
            $table->integer("response_code");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
}
