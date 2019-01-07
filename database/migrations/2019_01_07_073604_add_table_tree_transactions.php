<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableTreeTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string("no_transaction");
            $table->integer("quantity")->default(0);
            $table->integer("id_pohon")->nullable();
            $table->string("email")->nullable();
            $table->string("id_part_from");
            $table->string("id_part_to");
            $table->unique('no_transaction');
            $table->index('id_part_from');
            $table->index('id_part_to');
            $table->index(['id_part_from','id_part_to']);
            $table->index('id_pohon');
            $table->index(['id_pohon','id_part_from']);
            $table->index(['id_pohon','id_part_from','id_part_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree_transactions');
    }
}
