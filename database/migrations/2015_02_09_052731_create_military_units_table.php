<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilitaryUnitsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('military_units');
        
        Schema::create('military_units', function($table)
        {
            $table->increments('id');
            $table->tinyInteger('server')->index();
            $table->string('name', 32);
            $table->smallInteger('mu_id')->unsigned()->index();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('military_units');
    }

}
