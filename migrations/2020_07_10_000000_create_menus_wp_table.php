<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusWpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( config('menu.table_prefix') . config('menu.table_name_menus'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('clientes_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->foreign('clientes_id')
                ->references('id')->on('clientes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( config('menu.table_prefix') . config('menu.table_name_menus'));
    }
}
