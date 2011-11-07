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

	include "dbconfig.php";	// konfigurácia
	include "errors.php";	// spracovanie chýb

	$dbconn = NULL;

	function connectDb() {											// Pripojenie k databáze
		global $dbconn, $lastError;
		if($dbconn) return $dbconn;									// Singleton
		if($newconn = mysql_connect(HOST, USER, PASSWORD)) {
			if(mysql_select_db(DATABASE, $newconn)) {						// Výber databázy
				if(mysql_query("SET NAMES 'utf8'", $newconn)) $dbconn = $newconn; else {	// Nastavenie kódovania
					err(EQUERY);								// Ošetrenie chyby
					return NULL;
				}
			} else {
				err(EDBSEL);
				return NULL;
			}
		} else err(EDBCONN);
		return $newconn;
	}

	function insertRow($table, $data, $replace = false) {									// Vloženie riadka do db
		if($conn = connectDb()) {
			if($replace) $query = "REPLACE INTO ".$table." "; else $query = "INSERT INTO ".$table." ";
			$names = "(";
			$values = ") VALUES (";

			foreach($data as $key => $value) {								// Prejdenie prvkami asociatívneho poľa
				$names .= $key.", ";
				$values .= (is_int($value))?$value:("'".mysql_real_escape_string($value, $conn)."'").", ";
			}

			$names = rtrim($names, ", "); // zrušenie posledných dvoch znakov - t.j. ", "
			$values = rtrim($values, ", ");
			$query .= $names.$values.");";

			return mysql_query($query, $conn);
		} else return false;
	}

	function getRow($table, $value, $key = "id", $delete = false) {
		if($conn = connectDb()) {
			$query = "SELECT * FROM ".$table." WHERE ".$key."=".(is_int($value)?$value:"'".mysql_real_escape_string($value, $conn)."'");
			$result = mysql_query($query, $conn);
			if($result) return mysql_fetch_assoc($result); else return false;
		}
	}

	function updateRow($table, $data, $value, $key = "id") {
		if($conn = connectDb()) {
			$query = "UPDATE `".$table."` SET ";
			foreach($data as $k => $v) $query .= $k." = ".((is_int($v))?$v:"'".mysql_real_escape_string($v, $conn)."'").", ";
			$query = rtrim($query, ", ");
			$query .= " WHERE ".$key." = ".((is_int($value))?$value:"'".mysql_real_escape_string($value, $conn)."'");
			return mysql_query($query);
		} else return false;
	}

	function deleteRow($table, $value, $key = "id") {
		if($conn = connectDb()) {
			$query = "DELETE FROM ".$table." WHERE ".$key." = ".((is_int($value))?$value:"'".mysql_real_escape_string($value, $conn)."'");
			return mysql_query($query);
		}
	}
?>
