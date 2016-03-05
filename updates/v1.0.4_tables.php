<?php namespace Piratmac\Smmm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AssetsAddSync extends Migration
{

  public function up()
  {

    // Table containing all existing assets (at least the ones the user wants)
    Schema::table('piratmac_smmm_assets', function($table)
    {
      $table->boolean('synced');
    });


  }

  public function down () {}
}
