<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBattlesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('battles');
        
        Schema::create('battles', function($table)
        {
            $table->increments('id');
            $table->tinyInteger('server')->index();
            $table->mediumInteger('battle_id')->unsigned()->index();
            $table->tinyInteger('round')->unsigned()->index();
            $table->mediumInteger('damage')->unsigned();
            $table->tinyInteger('weapon')->unsigned();
            $table->boolean('berserk');
            $table->boolean('defender_side');
            $table->mediumInteger('citizen_id')->unsigned();
            $table->tinyInteger('citizenship')->unsigned();
            $table->smallInteger('military_unit')->unsigned();
            $table->timestamp('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battles');
    }

}
