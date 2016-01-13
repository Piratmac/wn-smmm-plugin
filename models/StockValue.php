<?php namespace Piratmac\Smmm\Models;

use Model;

/**
 * StockValue Model
 */
class StockValue extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_smmm_stock_values';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['stock_id', 'date', 'value'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = ['stock' => 'Piratmac\Smmm\Models\Stock'];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public $timestamps = false;

}