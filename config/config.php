<?php
ini_set('display_errors', 1);

//config
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__DIR__));

$queryString = explode("&", $_SERVER["QUERY_STRING"])[0];
$requestUri = explode("?", rawurldecode($_SERVER["REQUEST_URI"]))[0];

define('URL', str_replace(str_replace("url=", "", $queryString), "", $requestUri));
define("DB_HOST", "localhost");
define("DB_USER", "bulapras");
define("DB_PASS", 'W2au25Jq');
define("DB_NAME", "bulapras_members_db");


define('LIBS_PATH', 'Libs' . DS);
define('CONTROLLER_PATH', 'Controllers' . DS);
define('MODELS_PATH', 'Models' . DS);
define('VIEWS_PATH', 'Views' . DS);


if ( ! defined( "PATH_SEPARATOR" ) ) {
	if ( strpos( $_ENV[ "OS" ], "Win" ) !== false ) {
		define( "PATH_SEPARATOR", ";" );
	} else {
		define( "PATH_SEPARATOR", ":" );
	}
}

set_include_path(get_include_path() . PATH_SEPARATOR . ROOT . DS);
set_include_path(get_include_path() . PATH_SEPARATOR . ROOT . DS . "libs" . DS);
spl_autoload_register(
	function($desiredClassName) {
		$desiredClassName = str_replace("\\", "/", $desiredClassName);

		if(substr($desiredClassName, 0, 4) === 'App/') {
			$desiredClassName = substr($desiredClassName, 4);
			if(file_exists($desiredClassName . ".php")) {
				require_once $desiredClassName . ".php";
			} else {
				throw new Exception("Class " . $desiredClassName . " Not Found in App!");
			}
			return;
		}

		if(file_exists(LIBS_PATH . $desiredClassName . ".php")) {
			require_once LIBS_PATH . $desiredClassName . ".php";
		} else {
			throw new Exception("Class " . $desiredClassName . " Not Found in Libs!");
		}
	}
);

register_shutdown_function(
	function() {
		$error = error_get_last();
		// echo "<pre>";
		// print_r($error);
		// echo "</pre>";
	}
);

set_exception_handler(
	function($e) {
		echo "<br>";
		echo $e->getMessage();
		echo "<br />";
		echo $e->getFile();
		echo "<br />";
		echo "line: " . $e->getLine();
		echo "<br />";
		echo "<br> must .. implement .. custom .. exception .. pages ..";
	}
);