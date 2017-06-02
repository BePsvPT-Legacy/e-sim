<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilitaryUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('military_units', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('server', ['primera', 'secura', 'suna']);
            $table->smallInteger('military_unit_id')->unsigned();
            $table->string('name', 64);
            $table->timestamp('updated_at')->nullable();

            $table->unique(['military_unit_id', 'server']);

            $table->index(['updated_at']);
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
