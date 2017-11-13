<?php

namespace Utils;

use App\View;

use Users\User;
use Users\UsersRepository;

class Auth {

	public static function frontendAuth() {

		if(!isset($_SESSION['front-loggedIn']) || $_SESSION['front-loggedIn'] !== 1 || !isset($_SESSION['user']) || !$_SESSION['user'] instanceof User) {
			if(!isset($_POST['username']) || $_POST['username'] == '' || !isset($_POST['password']) || $_POST['password'] == '') {
				$view = new View();

				$view->setLayout('index_no_sidebar');
				$view->render('Index/login');
				exit;
			} else {
				if(self::checkCredentials($_POST)) {
					$_SESSION['front-loggedIn'] = 1;
					header("location:" . URL);
					exit;
				} else {
					session_destroy();
					header("location:" . URL);
				}
				exit;
			}
		} else {
			$usersRepo = new UsersRepository();
			$usersRepo->markLastActivity($_SESSION['user']->getUserId());
		}
	}

	public static function requireAuth() {
		if(!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== 1 || !isset($_SESSION["user"]) || !$_SESSION["user"] instanceof User ) {
			if(!isset($_POST["username"]) || $_POST["username"] == "" || !isset($_POST["password"]) || $_POST["password"] == "") {
				$view = new View();

				$view->setLayout("_blank");
				$view->render("Administrator/login");
				exit;
			} else {
				if(self::checkCredentials($_POST)) {
					$_SESSION["loggedIn"] = 1;
					if($_SESSION['user']->getRoleId() != 1) {
						session_destroy();
					}
					header("location: " . URL . "administrator");
					exit;
				} else {
					session_destroy();
					header("location: " . URL . "administrator");
				}
				exit;
			}
		} else {
			$usersRepo = new UsersRepository();
			$usersRepo->markLastActivity($_SESSION["user"]->getUserId());
		}
	}

	private static function checkCredentials($arr) {
		foreach($arr as &$key) {
			$key = trim($key);
		}

		$username = $arr["username"];
		$password = $arr["password"];

		$checkUser = $_SESSION["db"]->prepare("SELECT userId FROM users where username = ? AND isActive = 1");
		$checkUser->bind_param("s", $username);

		if($checkUser->execute()) {
			$checkUser->store_result();

			if($checkUser->num_rows > 0) {
				$checkUser->bind_result($userId);
				$checkUser->store_result();

				$checkUser->fetch();
				$checkUser->close();

				$usersRepo = new UsersRepository();
				$user = $usersRepo->getById($userId);

				if($user->verifyPassword($password)) {
					$_SESSION["user"] = $user;
					$usersRepo->markLastLogin($userId);
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public static function hasPermission($permissionName) {
		$dbRef = $_SESSION["db"];
		$currentUserAlias = $_SESSION["user"]->getAlias();

		//$roleHasPermission = $dbRef->prepare("SELECT 1 FROM `role_permissions` `rp` INNER JOIN `permissions` `p` ON `rp`.`permissionId` = `p`.`permissionId` WHERE `rp`.`roleId` = (select `roleId` From `users` WHERE `username` = ?) AND `p`.`permissionName` = ?");
		//$roleHasPermission->bind_param("ss", $currentUserAlias, $permissionName);
		$roleHasPermission = $dbRef->prepare("CALL `userHasPermission` (?, ?)");
		$roleHasPermission->bind_param("ss", $currentUserAlias, $permissionName);

		$roleHasPermission->execute();
		$roleHasPermission->store_result();

		if($roleHasPermission->num_rows > 0) {
			$roleHasPermission->close();
			return true;
		} else {
			$roleHasPermission->close();
			return false;
		}

		// return $_SESSION["user"]->checkPermission($permissionName);

	}
}