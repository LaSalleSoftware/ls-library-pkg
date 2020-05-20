<?php

/**
 * This file is part of the Lasalle Software library package. 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/ls-library-pkg
 * @link       https://github.com/LaSalleSoftware/ls-library-pkg
 *
 */

namespace Lasallesoftware\Library\Profiles\Models;

// LaSalle Software
use Lasallesoftware\Library\Common\Models\CommonModel;

/**
 * Class Lookup_social_types
 *
 * This is a lookup table
 *
 * @package Lasallesoftware\Library\Authentication\Models
 */
class Lookup_social_type extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'lookup_social_types';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'enabled',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * LaSalle Software handles the created_at and updated_at fields, so false.
     *
     * @var bool
     */
    public $timestamps = false;


    ///////////////////////////////////////////////////////////////////
    //////////////         MODEL EVENTS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The "booting" method of the model.
     *
     * Laravel will execute this function automatically
     * https://github.com/laravel/framework/blob/e6c8aa0e39d8f91068ad1c299546536e9f25ef63/src/Illuminate/Database/Eloquent/Model.php#L197
     *
     * @return void
     */
    protected static function boot()
    {
        // parent's boot function should occur first
        parent::boot();

        // Do this when the "creating" model event is dispatched
        // https://laracasts.com/discuss/channels/eloquent/is-there-any-way-to-listen-for-an-eloquent-event-in-the-model-itself
        //
        static::creating(function($lookup_social_type) {
            self::populateTitleField($lookup_social_type);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($lookup_social_type) {
            self::populateTitleField($lookup_social_type);
        });
    }

    /**
     * Populate the "title" field.
     *
     * @param  Lookup_social_type  $lookup_social_type
     */
    private static function populateTitleField(Lookup_social_type $lookup_social_type)
    {
        // without any "save", this following statement actually populates the field!
        $lookup_social_type->title = self::deepWashText($lookup_social_type->title);
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with social
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function social()
    {
        return $this->hasMany('Lasallesoftware\Library\Profiles\Models\Social');
    }
}
