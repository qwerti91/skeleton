<?php

namespace Core\Database;

class DBPdo {
	
	protected $link;
	
	public function __construct() {
		$this->__wakeup();
	}
	
	public function __sleep() {
		return [];
	}
	
	public function __wakeup() {
		$this->link = new \PDO('sqlite:test.sqlite3');
	}

	public function __call($name, $arguments) {
		return call_user_func_array([$this->link, $name], $arguments);
	}


}?>