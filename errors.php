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

// Database errors
	define("EDBCONN", 1); // connecting to DB
	define("EDBSEL", 2);  // selecting database
	define("EQUERY", 3);  // performing query

// User input errors
	define("EBADUNAME", 11);
	define("EBADEMAIL", 12);
	define("EDIFFPASS", 13);
	define("EWEAKPASS", 14);

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
?>
