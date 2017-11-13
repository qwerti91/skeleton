<?php

require("config/config.php");

use Core;
use Core\Routing\Dispatcher;
use Core\Routing\Request;
use Core\Routing\Router;
use Utils\l10n;


function __($string, $targetLang = null) {
	if($targetLang != null) {
		return l10n::getTranslation($string, $targetLang);
	} else {
		return l10n::getTranslation($string);
	}
}




/*
    put here custom Routes
*/

//Router::addRoute("/ajax/*", ["controller" => "ajax", "action" => "parseAjaxRequest"]);

Router::addRoute(['oth', 'method'], ['index', 'index']);
// Router::addRoute(['index', 'index'], ['index', 'bla']);


// print_r(Router::$routes);
// exit;

/*
    end of custom Routes
*/

Core::$request = new Request();
Dispatcher::dispatch(Core::$request);