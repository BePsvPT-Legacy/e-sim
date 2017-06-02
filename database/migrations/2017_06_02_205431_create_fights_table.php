<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fights', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('server', ['primera', 'secura', 'suna']);
            $table->mediumInteger('battle_id')->unsigned();
            $table->tinyInteger('round')->unsigned();
            $table->mediumInteger('citizen_id')->unsigned();
            $table->tinyInteger('citizenship_id')->unsigned();
            $table->smallInteger('military_unit_id')->unsigned()->nullable();
            $table->mediumInteger('damage')->unsigned();
            $table->tinyInteger('weapon')->unsigned();
            $table->boolean('is_berserk');
            $table->boolean('is_defender');
            $table->timestamp('time')->nullable();

            $table->index(['server', 'battle_id', 'round']);
            $table->index(['server', 'battle_id', 'military_unit_id']);
            $table->index(['citizen_id', 'server']);
            $table->index(['military_unit_id', 'server']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fights');
    }
}
