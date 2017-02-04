<?php namespace Piratmac\Smmm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class MovementAddGain extends Migration
{

  public function up()
  {

    Schema::table('piratmac_smmm_portfolio_movements', function($table)
    {
      $table->decimal('unit_gain_upon_sell', 15, 5);
    });


  }

  public function down () {}
}
