<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableBlockedRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("blocked_requests",function (Blueprint $column) {
           $column->increments("id");
           $column->timestamps();
           $column->string("useragent");
           $column->string("ip");
           $column->string("request_count");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists("blocked_requests");
    }
}
