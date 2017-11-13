<?php

namespace Core\Routing;

class Router {

	public static $routes = [];

	public static function addRoute(array $from, array $to) {
		
		// for incomplete routes skip rule
		if(empty($from) || empty($to)) {
			return;
		}
		
		// dont clear existing route
		if(!isset(self::$routes[ $from[0] ])) {
			self::$routes[ $from[0] ] = [];
			self::$routes[ $from[0] ]['actions'] = [];
		}

		// overwrite controller every time
		self::$routes[ $from[0] ]['controller'] = $to[0];
		// append new actions
		self::$routes[ $from[0] ]['actions'][ $from[1] ] = $to[1];
	}

	
	protected static function checkRoute($route) {}

}