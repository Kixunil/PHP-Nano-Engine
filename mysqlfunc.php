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

	function getRows($table, $value = NULL, $key = "id", $limit = NULL) {
		if($conn = connectDb()) {
			$query = "SELECT * FROM ".$table;
			if($value != NULL) {
				$query .= " WHERE ";
				if(is_array($value)) 
				{
					foreach($value as $k => $v) $query .= $k." = '".mysql_real_escape_string($v, $conn)."' AND ";
					$query = rtrim($query, "' AND ");
				} else $query .= $key."=".(is_int($value)?$value:"'".mysql_real_escape_string($value, $conn)."'");
			}
			if($limit !== NULL) $query .= " LIMIT ".$limit;
			return mysql_query($query, $conn);
		}
	}

	function getRow($table, $value, $key = "id") {
		$result = getRows($table, $value, $key, "0, 1");
		if($result) return mysql_fetch_assoc($result); else return false;
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
