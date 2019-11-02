<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', [
                'Reduce Miss Chance',
                'Increase Critical Chance',
                'Increase Maximum Damage',
                'Increase Damage',
                'Increase Chance to Avoid Damage',
                'Increase Strength',
                'Increase Hit',
                'Increase Economy Skill',
                'Increase Chance for Free Flight',
                'Increase Chance to Use Less Weapons for Berserk',
                'Increase Chance to Find a Weapon',
            ]);
            $table->decimal('value', 5, 2);

            $table->unique(['type', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameters');
    }
}
