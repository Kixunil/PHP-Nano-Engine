<?php

/*
Copyright (C) 2011 by Martin Habovštiak

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

include "errorconfig.php";

// Error functions
	$errno = 0;

	function err($err) {
		global $errno;
		if(!$errno) $errno = $err;
	}

	function errorstr($err = NULL, $lang = "sk") {
		global $errno;
		if($err === NULL) $err = $errno;
		$errors = Array(
			"en" => Array(
					EDBCONN => "Unable connect to database",
					EDBSEL => "Unable select database",
					EQUERY => "Unable perform database query",

					EBADUNAME => "Bad user name",
					EBADEMAIL => "Bad e-mail address",
					EDIFFPASS => "Passwords are different",
					EWEAKPASS => "Password is too weak"
			),
				"sk" => Array(
					EDBCONN => "Nepodarilo sa pripojiť k databáze",
					EDBSEL => "Nepodarilo sa vybrať databázu",
					EQUERY => "Databáze sa nepodarilo vykonať požiadavku",

					EBADUNAME => "Chybné prihlasovacie meno",
					EBADEMAIL => "Chybná e-mailová adresa",
					EDIFFPASS => "Heslá nie sú rovnaké",
					EWEAKPASS => "Príliš slabé heslo"
			)
		);
		return $errors[$lang][$err];
	}

	function checkdebug() {
		if(!DEBUG) return false;
		global $allowedDebugIP;
		return array_search($_SERVER["REMOTE_ADDR"], $allowedDebugIP) !== false;
	}

	function debugmsg($msg) {	
		if(!checkdebug()) return;
		echo "\n<!-- DEBUG: ".$msg." -->\n";
	}

	function debug($err = NULL, $lang = "sk") {
		debugmsg(errorstr($err, $lang));
	}
?>
