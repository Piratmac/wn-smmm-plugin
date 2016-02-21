<?php namespace Piratmac\Smmm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateAssetValuesTable extends Migration
{

  public function up()
  {

    // Table containing all existing assets (at least the ones the user wants)
    Schema::table('piratmac_smmm_asset_values', function($table)
    {
      $table->primary(['asset_id', 'date']);
    });


  }

  public function down () {}
}
