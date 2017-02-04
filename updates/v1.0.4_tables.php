<?php namespace Piratmac\Smmm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AssetsAddSync extends Migration
{

  public function up()
  {

    Schema::table('piratmac_smmm_assets', function($table)
    {
      $table->boolean('synced');
    });


  }

  public function down () {}
}
