<?php
namespace Models;

use Core\ModelMongo;

class Config extends ModelMongo {
	protected static $instances = array();
	
	public function get($key) {
		if (isset(self::$instances[$key]))
			return self::$instances[$key]->get('value');
		self::$instances[$key] = $this->mongo->getCollection('config')->find()->where('key', $key)->findOne();
		if (self::$instances[$key])
			return self::$instances[$key]->get('value');
		return null;
	}
	public function set($key, $value) {
		if (isset(self::$instances[$key]))
			self::$instances[$key]->set('value', $value);
		else
			self::$instances[$key] = $this->mongo->getCollection('config')->createDocument(['key' => $key, 'value' => $value]);
		
		self::$instances[$key]->save();
	}
}