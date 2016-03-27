<?php
namespace Core;

use Helpers\Mongo;

/*
 * model - the base model
 *
 * @author David Carr - dave@daveismyname.com - http://daveismyname.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated May 18 2015
 */

abstract class ModelMongo extends Model
{
    /**
     * hold the database connection
     * @var object
     */
    protected $mongo;

    /**
     * create a new instance of the database helper
     */
    public function __construct()
    {
        //connect to PDO here.
        $this->mongo = Mongo::get();
    }
}
