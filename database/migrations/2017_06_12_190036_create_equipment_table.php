<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('server', ['primera', 'secura', 'suna']);
            $table->integer('equipment_id')->unsigned();
            $table->enum('quality', ['Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6']);
            $table->enum('slot', ['Offhand', 'Helmet', 'Personal Armor', 'Vision', 'Weapon Upgrade', ' Pants', 'Shoes', 'Lucky Charm']);

            $table->unique(['equipment_id', 'server']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
