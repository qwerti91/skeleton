<?php

namespace App\Controllers;

use Core;
use Utils\Auth;

class IndexController extends Core\Controller {
	public function __construct() {
		parent::__construct();

		// Auth::frontendAuth();
	}
	public function index() {

		// $this->setLayout('_blank');
		// $this->autoRender = false;


		// try {
		// 	$_SESSION['db']->exec('CREATE TABLE IF NOT EXISTS users (userId INTEGER PRIMARY KEY, name TEXT NOT NULL)');
		// } catch(PDOException $e) {
		// 	echo $e->getMessage();
		// 	exit;
		// }

		// $insert = $_SESSION['db']->prepare('INSERT INTO users (name) VALUES (?)');
		// $insert->execute(['fil']);

		// $this->render('index');


		// $select = $_SESSION['db']->prepare('SELECT datetime("now")');
		// $select->execute();

		// while($row = $select->fetch(\PDO::FETCH_ASSOC)) {
		// 	print_r($row);
		// }


		// Auth::frontendAuth();

	}
}