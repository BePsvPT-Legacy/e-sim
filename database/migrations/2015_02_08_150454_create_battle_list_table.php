<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBattleListTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('battle_list');
        
        Schema::create('battle_list', function($table)
        {
            $table->increments('id');
            $table->tinyInteger('server')->index();
            $table->mediumInteger('battle_id')->unsigned()->index();
            $table->tinyInteger('rounds')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battle_list');
    }

}
