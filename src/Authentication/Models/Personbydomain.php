<?php

/**
 * This file is part of the Lasalle Software library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-library-pkg
 * @link       https://github.com/LaSalleSoftware/lsv2-library-pkg
 *
 */

namespace Lasallesoftware\Library\Authentication\Models;

// Laravel facades
use Illuminate\Support\Facades\DB;

// Laravel classes
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * This is the model class for personbydomain.
 *
 * This is the table for logging into the app.
 *
 * @package Lasallesoftware\Library\Authentication\Models
 */
class Personbydomain extends Authenticatable
{
    use Notifiable;


    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'personbydomains';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'person_id',
        'person_first_name',
        'person_surname',
        'email',
        'installed_domain_id',
        'installed_domain_title',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to many (inverse) relationship with persons.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function person()
    {
        return $this->belongsTo('Lasallesoftware\Library\Profiles\Models\Person');
    }

    /*
     * One to many (inverse) relationship with installed_domain.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function installed_domain()
    {
        return $this->belongsTo('Lasallesoftware\Library\Profiles\Models\Installed_domain');
    }

    /*
    * One to many (inverse) relationship with email.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function email()
    {
        return $this->belongsTo('Lasallesoftware\Library\Profiles\Models\Email');
    }

    /*
    * One to many relationship with login.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function login()
    {
        return $this->hasMany('Lasallesoftware\Library\Authentication\Models\Login');
    }

    /*
    * Many to many relationship with lookup_role.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function lookup_role()
    {
        //return $this->belongsToMany('Lasallesoftware\Library\Authentication\Models\Lookup_user_group');

        return $this->belongsToMany(
            'Lasallesoftware\Library\Authentication\Models\Lookup_role',
            'personbydomain_lookup_roles',
            'personbydomain_id',
            'lookup_role_id'
        );
    }

    /*
     * A post may have one, and only one, personbydomain.
     *
     * The post database table has the field "personbydomain_id". So the post model specifies "hasOne".
     * The personbydomain is the "inverse of the relationship" (per the lexicon of https://laravel.com/docs/5.8/eloquent-relationships#one-to-one)
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function post()
    {
        return $this->belongsTo('Lasallesoftware\Blogbackend\Models\Post');
    }


    ///////////////////////////////////////////////////////////////////
    //////////////          LOCAL SCOPES            ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Is it the owner role?
     *
     * @return bool
     */
    public function scopeIsOwner()
    {
        return $this->lookup_role()->where('lookup_role_id', 1)->exists();
    }

    /**
     * Is it the super administrator role?
     *
     * @return bool
     */
    public function scopeIsSuperadministrator()
    {
        return $this->lookup_role()->where('lookup_role_id', 2)->exists();
    }

    /**
     * Is it the administrator role?
     *
     * @return bool
     */
    public function scopeIsAdministrator()
    {
        return $this->lookup_role()->where('lookup_role_id', 3)->exists();
    }

    /**
     * Does the current user (ie, personbydomain) have the specified role?
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        if ($role == strtolower('owner')) {
            return $this->IsOwner();
        }

        if ($role == strtolower('superadministrator')) {
            return $this->IsSuperadministrator();
        }

        if ($role == strtolower('administrator')) {
            return $this->IsAdministrator();
        }

        return false;
    }

}
