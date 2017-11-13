<?php

namespace Utils;

class Utils {
	public static function getRequestMethod() {
		return $_SERVER["REQUEST_METHOD"];
	}

	public function __($string, $targetLang = null) {

		$db = $_SESSION["db"];
		$lang = $_SESSION["lang"];

		if($targetLang !== null) {
			$lang = $targetLang;
		}

		$storeLiteral = $db->prepare("INSERT INTO l10n_literals (literal) VALUES (?)");
		$getLang = $db->prepare("SELECT langId FROM l10n_languages WHERE langShort = ?");
		$getTranslatedLiteral = $db->prepare("SELECT t.translation FROM l10n_lang_literals t INNER JOIN l10n_literals lt ON t.literalId = lt.literalId INNER JOIN l10n_languages lg on t.langId = lg.langId WHERE lg.langId = ? AND lt.literalId = ?");

		$checkLiteral = $db->prepare("SELECT literalId FROM l10n_literals WHERE literal = ?");
		$checkLiteral->bind_param("s", $string);

		if($checkLiteral->execute()) {
			$checkLiteral->store_result();

			if($checkLiteral->num_rows > 0) {
				$checkLiteral->bind_result($literalId);
				$checkLiteral->fetch();
				$checkLiteral->close();

				$getLang->bind_param("s", $lang);
				$getLang->execute();
				$getLang->bind_result($langId);
				$getLang->fetch();
				$getLang->close();

				$getTranslatedLiteral->bind_param("ii", $langId, $literalId);
				$getTranslatedLiteral->execute();
				$getTranslatedLiteral->store_result();
				if($getTranslatedLiteral->num_rows > 0) {
					$getTranslatedLiteral->bind_result($translatedString);
					$getTranslatedLiteral->fetch();
					$getTranslatedLiteral->close();
				} else {
					return $string;
				}
				
				return $translatedString == "" ? $string : $translatedString;

			} else {
				$checkLiteral->close();
				$storeLiteral->bind_param("s", $string);

				$storeLiteral->execute();
			}
		}

		return $string;
	}

	public static function crypto_rand_secure($min, $max) {
		$range = $max - $min;
		if ($range < 1) return $min; // not so random...
		$log = ceil(log($range, 2));
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		return $min + $rnd;
	}

	public static function getToken($length) {
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet) - 1;
		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[ self::crypto_rand_secure(0, $max) ];
		}
		return $token;
	}

	public static function isAjaxRequest() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest"){
			return true;
		} else {
			return false;
		}
	}
}