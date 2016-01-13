<?php namespace Piratmac\Smmm\Models;

use Model;

/**
 * PortfolioMovement Model
 */
class PortfolioMovement extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_smmm_portfolio_movements';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['date', 'type', 'stock_count', 'unit_value', 'fee', 'portfolio_id', 'stock_id'];

    /**
     * @var boolean Don't use timestamps
     */
    public $timestamps = false;



    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = ['portfolio' => 'Piratmac\Smmm\Models\Portfolio', 'stock' => 'Piratmac\Smmm\Models\Stock' ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}