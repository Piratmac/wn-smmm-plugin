<?php namespace Piratmac\Smmm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class MovementsRemoveFee extends Migration
{

  public function up()
  {

    Schema::table('piratmac_smmm_portfolio_movements', function($table)
    {
      $table->dropColumn('fee');
    });


  }

  public function down () {}
}
