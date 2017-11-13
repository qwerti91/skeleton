<?php

namespace Core\Database;

class Database extends \MySQLi {
	
	public function __construct() {
		parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		parent::set_charset('utf8');
	}
}