<?php

/*
 Php nano engine, simple utility for managing users and mysql queries
    Copyright (C) 2011  Martin Habovštiak

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

	include("mysqlfunc.php");
	include("userconfig.php");

	$user = NULL;

	function passStrength($password) {	// ráta silu hesla
		$lowercase = false;		// obsahuje malé písmená?
		$uppercaseb = false;		// obsahuje veľké písmeno na začiatku?
		$uppercasem = false;		// obsahuje veľké písmená a mimo začiatku?
		$numbers = false;		// obsahuje čísla?
		$whitespace = false;		// obsahuje medzeru?
		$specials = false;		// obsahuje špeciálne znaky?

		for($i = 0; $i < strlen($password); ++$i) {	// prechádza všetky písmená hesla a nastavuje dané typy písmen
			if($password[$i] >= "a" && $password[$i] <= "z") $lowercase = true; else					// malé písmená
			if($password[$i] >= "A" && $password[$i] <= "Z") if($i == 0) $uppercaseb = true; else $uppercasem = true; else	// veľké písmená
			if($password[$i] >= "0" && $password[$i] <= "9") $numbers = true; else						// čísla
			if($password[$i] == " ") $whitespace = true; else $specials = true;						// medzera; všetko ostatné sú špeciálne
		}

		$chars = 0;			// prirátavanie počtu podľa typu (písmen je 26, číslic 10, medzera len jedna a ostatných je 34)
		if($lowercase) $chars += 26;
		if($uppercasem) $chars += 26;
		if($numbers) $chars += 10;
		if($whitespace) ++$chars;
		if($specials) $chars += 34;

		$num = pow($chars, strlen($password));		// umocnenie počet podľa typu na dĺžku hesla
		if(!$uppercasem && $uppercaseb) $num *= 2;	// Ak bolo veľké písmeno na začiatku ale nebolo v strede násobí sa 2
		return $num;
	}

	function checkInput($data) {								// kontrola vstupu do registrácie
		$errors = Array();
		if(!preg_match(VALIDUNAME, $data["username"])) $errors[] = EBADUNAME;		// používateľské meno
		if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) $errors[] = EBADEMAIL;		// e-mail
		if($data["password"] != $data["password2"]) $errors[] = EDIFFPASS;		// rovnosť hesiel
		if(passStrength($data["password"]) < MINPASSSTRENGTH) $errors[] = EWEAKPASS;	// sila hesla

		return (count($errors) == 0)?NULL:$errors;	// vráti pole chýb, ak nejaké boli
	}

	function getRndStr40() {				// vytvorí náhodné 20 bajtové číslo zapísané hexadecimálne
		$urandom = fopen("/dev/urandom", "r");		// súbor /dev/urandom generuje náhodné dáta; bohužiaľ použiteľné iba na unixoch
		if($urandom)					// ak sa otvorenie podarilo
			$randomstr = fgets($urandom, "40");	// načítanie 40 bajtov
		else
			for($i = 0; $i < 40; ++$i) $randomstr = $randomstr.chr(rand(0, 255)); // ak sa nepodarí, použije sa menej kvalitná náhoda - rand()
		return sha1($randomstr);			// zahashovanie
	}

	function genSaltedPass($pass, $salt) {			// vygenerovanie slaného hesla
		$split = 10;					// bulharská konštanta na zmätenie útočníkov (lepšie by bolo použiť šifru ale toto je rýchlejšie)
		$salted = substr($salt, 0, $split).$pass.substr($salt, $split, 40-$split);	// pridanie soli k heslu
		return sha1($salted);				// zahashovanie
	}

	function register($data) {				// registrácia; NEKONTROLUJE!!! Kontrolu treba spustiť predtým!!!
		$salt = getRndStr40();				// vygenerovanie slaného hashu
		$data["password"] = genSaltedPass($data["password"], $salt).$salt;

		$allowed = Array("username", "firstname", "lastname", "email", "password", "cookie", "anticsrf"); // zoznam povolených položiek do tabuľky
		$data = array_intersect_key($data, array_flip($allowed));					// odfiltrovanie nadbytočných položiek (ochrana pred chybami)

		return insertRow(USERTABLE, $data);								// vloženie do tabuľky
	}

	function checkPass($password, $hash) {					// kontrola zadaného hesla
		$salt = substr($hash, 40, 40);
		return substr($hash, 0, 40) == genSaltedPass($password, $salt);
	}

	function login($data) {
		global $user;
		$userdata = getRow(USERTABLE, $data["username"], "username");				// vybratie riadku podľa používateľského mena
		if(!$userdata || !checkPass($data["password"], $userdata["password"])) {	// kontrola hesla
			$user = false;
			return NULL;
		}
		$user = $userdata;									// nastavenie singleton
		$cookie = getRndStr40();								// vygenerovanie nového cookie
		if(updateRow(USERTABLE, Array("cookie" => $cookie), $user["id"])) $user["cookie"] = $cookie; // ak sa nepodarí aktualizovať cookie, ostane starý (spôsobí problémy)

		return $user;
	}

	function logout($user) {
		return updateRow(USERTABLE, Array("cookie" => ""), $user["id"]);				// vynulovanie cookie
	}

	function checkCookie($cookie) {
		if($cookie == "") return false;			// prázdny cookie - neprihlásený, tento riadok NEMAZAŤ!!!
		global $user;					// singleton
		if($user !== NULL) return $user;
		return $user = getRow(USERTABLE, $cookie, "cookie");	// vybratie riadka z db
	}

	function genAntiCSRF($user) {
		$token = getRndStr40();
		if(updateRow(USERTABLE, Array("anticsrf" => $token))) return $token; else return false;
	}

	function checkAntiCSRF($user, $data, $regen = false) {			// všeobecná anticsrf funkcia
		if($regen) {
			if($user["anticsrf"] == $data["anticsrf"]) {
				if($token = genAntiCSRF($user)) return $user["anticsrf"] = $token; else return NULL;
			} else return false;
		} else
		return $user["anticsrf"] == $data["anticsrf"];
	}

	function setCaptcha($answer, $id = NULL) {
		if($uid === NULL) {
			$cookie = "X".getRndStr40();
			if(insertRow(CAPTCHATABLE, Array("cookie" => $cookie, "answer" => $answer))) return $cookie; else return false;
		} else return insertRow(CAPTCHATABLE, Array("cookie" => (is_string($id))?"X".$id:$id, "answer" => $answer), true);
	}

	function checkCaptcha($answer, $id, $newanswer = NULL) {
		if(is_string($id)) $id = "X".$id;
		if($row = getRow(CAPTCHATABLE, $id, "cookie")) {
			if($answer == $row["answer"]) {
				deleteRow(CAPTCHATABLE, $id, "cookie");
				return true;
			} else if($newanswer !== NULL){
				if(updateRow(CAPTCHATABLE, Array("answer" => $answer), $id, "answer")) return $id; else return false;
				
			} else return false;
		} else if($newanswer !== NULL) return setCaptcha($answer, $id); else return false;
	}
?>
