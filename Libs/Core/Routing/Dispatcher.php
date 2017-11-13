<?php
namespace Core\Routing;

use Core;
use Core\Controller;
use Core\Database\Database;
use Core\Database\DBPdo;

class Dispatcher {
	public static function dispatch(Request $request) {
		
		Core::$session = new \Utils\Session();
		Core::$session::init();
		Core::$session->set('db', new DBPdo());

		if(isset($_GET["lang"])) {
			$_SESSION["lang"] = $_GET["lang"];
		} else {
			if(!isset($_SESSION["lang"])) {
				$_SESSION["lang"] = "bg-BG";
			}
		}

		if(file_exists(CONTROLLER_PATH . $request->controller . 'Controller' . ".php")) {
			require_once(CONTROLLER_PATH . $request->controller . 'Controller' . ".php");
		} else {
			//implement missing controller 
			header("HTTP/1.0 404 Not Found");
			echo '404: controller not found not found, soz';
			exit;
		}

		$controller ='App\Controllers\\' .  $request->controller . 'Controller';
		$controller = new $controller();

		if($controller instanceof Controller) {
			if(is_callable([$controller, $request->action])) {
				call_user_func_array([$controller, $request->action], $request->params);
				$controller->render($request->action);
			} else {
				throw new \Exception(get_class($controller) . ' does not implement ' . $request->action . '()');
			}
		} else {
			throw new \Exception(get_class($controller) . ' must extend ' . Controller::class);
		}
	}
}