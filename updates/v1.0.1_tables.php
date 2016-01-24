<?php namespace Piratmac\Smmm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePortfolioTables extends Migration
{

  public function up()
  {
    // Table for holding all portfolios
    Schema::dropIfExists('piratmac_smmm_portfolios');
    Schema::create('piratmac_smmm_portfolios', function($table)
    {
      $table->engine = 'InnoDB';
      $table->increments('id');
      $table->string('description');
      $table->string('number')->default(NULL)->nullable();
      $table->date('open_date');
      $table->date('close_date')->default(NULL)->nullable();
      $table->string('broker')->default(NULL)->nullable();

      // Link to user
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id', 'piratmac_smmm_portfolios_user_id_foreign')->references('id')->on('backend_users');

      $table->timestamps();

      $table->softDeletes();
    });

    // Table containing all existing assets (at least the ones the user wants)
    Schema::dropIfExists('piratmac_smmm_assets');
    Schema::create('piratmac_smmm_assets', function($table)
    {
      $table->engine = 'InnoDB';
      $table->string('id')->primary();
      $table->string('title');

      $table->enum('source', ['bourso', 'yahoo'])->default(NULL)->nullable();
      $table->enum('type', ['cash', 'stock', 'bond', 'mixed']);

      $table->date('display_from');
      $table->date('display_to')->default(NULL)->nullable();

      $table->timestamps();

      $table->softDeletes();

      $table->index(['id', 'display_from', 'display_to'], 'code_display_dates');
    });


    // Table containing the market values of the assets
    Schema::dropIfExists('piratmac_smmm_asset_values');
    Schema::create('piratmac_smmm_asset_values', function($table)
    {
      $table->engine = 'InnoDB';
      $table->string('asset_id');
      $table->date('date');
      $table->decimal('value', 15, 5);

      // Foreign key
      $table->foreign('asset_id', 'piratmac_smmm_asset_values_asset_foreign')->references('id')->on('piratmac_smmm_assets');
    });





    // Table containing the events on a portfolio (cash increases / decreases, asset buys/sells, ...)
    Schema::dropIfExists('piratmac_smmm_portfolio_movements');
    Schema::create('piratmac_smmm_portfolio_movements', function($table)
    {
      $table->engine = 'InnoDB';
      $table->increments('id');

      // Link to portfolio
      $table->integer('portfolio_id')->unsigned();
      $table->foreign('portfolio_id', 'piratmac_smmm_portfolio_movements_portfolio_foreign')->references('id')->on('piratmac_smmm_portfolios');
      $table->date('date');
      $table->enum('type', ['cash_entry', 'cash_exit', 'asset_buy', 'asset_sell', 'fee']);

      // Link to assets
      $table->string('asset_id')->nullable();
      $table->foreign('asset_id', 'piratmac_smmm_portfolio_movements_asset_foreign')->references('id')->on('piratmac_smmm_assets');
      $table->decimal('asset_count', 10, 5);
      $table->decimal('unit_value', 12, 5);
      $table->decimal('fee', 10, 5);

    });


    // Table containing the situation of a portfolio (basically the "sum" of the events
    Schema::dropIfExists('piratmac_smmm_portfolio_contents');
    Schema::create('piratmac_smmm_portfolio_contents', function($table)
    {
      $table->engine = 'InnoDB';
      // Link to portfolio
      $table->integer('portfolio_id')->unsigned();
      $table->foreign('portfolio_id', 'piratmac_smmm_portfolio_contents_portfolio_foreign')->references('id')->on('piratmac_smmm_portfolios');

      // Link to assets
      $table->string('asset_id')->nullable();
      $table->foreign('asset_id', 'piratmac_smmm_portfolio_contents_asset_foreign')->references('id')->on('piratmac_smmm_assets');


      $table->date('date_from');
      $table->date('date_to')->default('9999-12-31');
      $table->decimal('asset_count', 10, 5);
      $table->decimal('average_price_tag', 12, 2);

    });




  }

  public function down()
  {
    if (Schema::hasTable('piratmac_smmm_portfolio_movements')) {
      Schema::table('piratmac_smmm_portfolio_movements', function ($table) {
        $table->dropForeign('piratmac_smmm_portfolio_movements_portfolio_foreign');
        $table->dropForeign('piratmac_smmm_portfolio_movements_asset_foreign');
      });
    }


    if (Schema::hasTable('piratmac_smmm_portfolio_contents')) {
      Schema::table('piratmac_smmm_portfolio_contents', function ($table) {
        $table->dropForeign('piratmac_smmm_portfolio_contents_portfolio_foreign');
        $table->dropForeign('piratmac_smmm_portfolio_contents_asset_foreign');
      });
    }


    if (Schema::hasTable('piratmac_smmm_asset_values')) {
      Schema::table('piratmac_smmm_asset_values', function ($table) {
        $table->dropForeign('piratmac_smmm_asset_values_asset_foreign');
      });
    }



    Schema::dropIfExists('piratmac_smmm_portfolio_movements');
    Schema::dropIfExists('piratmac_smmm_portfolio_contents');
    Schema::dropIfExists('piratmac_smmm_asset_values');
    Schema::dropIfExists('piratmac_smmm_portfolios');
    Schema::dropIfExists('piratmac_smmm_assets');
  }

}
