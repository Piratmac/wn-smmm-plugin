<?php namespace Piratmac\Smmm\Models;

use Model;

/**
 * AssetValue Model
 */
class AssetValue extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_smmm_asset_values';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['asset_id', 'date', 'value'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = ['asset' => 'Piratmac\Smmm\Models\Asset'];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public $timestamps = false;

}