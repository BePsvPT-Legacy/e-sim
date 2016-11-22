<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitizensTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('citizens');
        
        Schema::create('citizens', function($table)
        {
            $table->increments('id');
            $table->tinyInteger('server')->index();
            $table->string('name', 32)->index();
            $table->mediumInteger('citizen_id')->unsigned()->index();
            $table->boolean('organization');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citizens');
    }

}
