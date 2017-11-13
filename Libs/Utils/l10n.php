<?php

namespace Utils;

class l10n {
	public static function getTranslation($string, $targetLang = null) {
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
}