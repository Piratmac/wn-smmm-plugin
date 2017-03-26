<?php namespace Piratmac\Smmm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class PortfoliosAddBaseCurrency extends Migration
{

  public function up()
  {

    Schema::table('piratmac_smmm_portfolios', function($table)
    {
      $table->string('base_currency_id');

      // Foreign key
      $table->foreign('base_currency_id', 'piratmac_smmm_portfolios_default_currency_foreign')->references('id')->on('piratmac_smmm_assets');
    });


    Schema::table('piratmac_smmm_assets', function($table)
    {
      $table->string('base_currency_id')->nullable();

      // Foreign key
      $table->foreign('base_currency_id', 'piratmac_smmm_assets_default_currency_foreign')->references('id')->on('piratmac_smmm_assets');
    });


  }

  public function down () {}
}
