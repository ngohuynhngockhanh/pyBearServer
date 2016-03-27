<?php
namespace Helpers;

use \Sokil\Mongo\Client;

/*
 * database Helper - extending PDO to use custom methods
 *
 * @author David Carr - dave@daveismyname.com - http://daveismyname.com
 * @version 2.1
 * @date June 27, 2014
 * @date May 18 2015
 */
class Mongo extends Client
{
    /**
     * @var array Array of saved databases for reusing
     */
    protected static $instances = array();

    /**
     * Static method get
     *
     * @param  array $group
     * @return \helpers\database
     */
    public static function get($group = false)
    {
       
        $id = DSN_HOST . '.' . DNS_DBNAME;

        // Checking if the same
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }
		
		self::$instances[$id] = new Client(DSN_HOST);
		self::$instances[$id] = self::$instances[$id]->getDatabase(DNS_DBNAME);
		return self::$instances[$id];
    }

}
