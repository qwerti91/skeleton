<?php

namespace Core\Routing;


class Request {

	public $method;
	public $isAjax = false;

	public $controller;
	public $action;
	public $params;

	public function __construct() {


		unset($_GET['url']);

		$this->method = $_SERVER["REQUEST_METHOD"];
		$this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest" ? true : false;

		// remove URL from request uri
		$request = implode('', explode(URL, parse_url($_SERVER["REQUEST_URI"])["path"], 2));
		// split to pieces
		$query = explode('/', $request, 3);
		// assign pieces
		$this->controller = strtolower($query[0] != '' ? $query[0] : 'index');
		$this->action = strtolower(isset($query[1]) && $query[1] != '' ? $query[1] : 'index');
		$this->params = isset($query[2]) ? explode('/', $query[2]) : [];
		
		// attach static routes
		if(isset(Router::$routes[ $this->controller ])) {
			if(isset(Router::$routes[ $this->controller ]['actions'][ $this->action ])) {
				$this->action = Router::$routes[ $this->controller ]['actions'][ $this->action ];
			}
			$this->controller = ucfirst(Router::$routes[ $this->controller ]['controller']);
		}

		$this->controller = ucfirst($this->controller);
	}

	public function get() {
		return $_GET;
	}

	public function post() {
		return $_POST;
	}

	public function server() {
		return $_SERVER;
	}

	/*
	public static function createFromGlobals() {
		// remove url from query string
		unset($_GET['url']);

		//include Routes in creation
		
		
		$url = parse_url($_SERVER["REQUEST_URI"]);
		$url["path"] = implode("", explode(URL, $url["path"], 2));
		$query = explode("/", $url["path"]);

		$controller = ucwords($query[0] != "" ? $query[0] : "Index") . "Controller";
		$action = isset($query[1]) && $query[1] != "" ? $query[1] : "Index";
		$action = explode("&", $action)[0];


		print_r($method);
		echo '<br />';
		print_r($controller);
		echo '<br />';
		print_r($action);
		echo '<br />';
		print_r($query);
		echo '<br />';
		print_r($_GET);
		echo '<br />';
		print_r($_POST);
		echo '<br />';
		print_r($_SERVER);
		echo '<br />';
		print_r($_ENV);
		exit;
		//hard coded routes
		///////////////////////
		//					///
		//	  BAD BAD BAD 	///
		//					///
		///////////////////////
		
		$predefinedRoutes = array(
			"ajax" => array(
				"controller" => "AjaxController"
				, "action" => "parseAjaxRequest"
			)
			, 'login' => [
				'controller' => 'IndexController'
				, 'action' => 'login'
			]
			, 'category' => [
				'controller' => 'IndexController'
				, 'action' => 'view_category'
			]
			, 'article' => [
				'controller' => 'IndexController'
				, 'action' => 'view_article'
			]
			, 'index' => [
				'controller' => 'IndexController'
				, 'action' => 'index'
			]
		);
		
		if(isset($predefinedRoutes[ $query[0] ])) {
			$controller = $predefinedRoutes[ $query[0] ]["controller"];
			$action = $predefinedRoutes[ $query[0] ]["action"];

			array_shift($query);
		} else {
			array_shift($query);
			array_shift($query);
		}

		//////////////////////
		//////////////////////

		

		$params = $query;

		
		return compact("method", "controller", "action", "params");
	}
	*/
}