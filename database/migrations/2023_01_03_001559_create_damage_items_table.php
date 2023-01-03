<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damage_items', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('item_id')->unsigned();
            // $table->foreign('item_id')
            //     ->references('id')->on('items')
            // ->onDelete('cascade');
            $table->bigInteger('stock_id')->unsigned();
            $table->foreign('stock_id')
                ->references('id')->on('stocks')
                ->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->string('quantity');
            // $table->string('acceptor');
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
        Schema::dropIfExists('damage_items');
    }
}
