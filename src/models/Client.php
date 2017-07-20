<?php
/**
 * File "Client.php"
 * @author Thomas Bourrely
 * 19/07/2017
 */

namespace mainApp\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 *
 * @package mainApp\models
 */
class Client extends Model
{
    /*******************
     * ELOQUENT CONFIG
     *******************/
    protected $connection   = 'server';
    protected $table        = 'clients';
    protected $primaryKey   = 'client_id';
    protected $fillable     = ['client_id', 'client_secret', 'name', 'redirect_uri'];

    public $timestamps      = false;
    public $incrementing    = false;
    /*******************
     * END OF ELOQUENT CONFIG
     *******************/
}