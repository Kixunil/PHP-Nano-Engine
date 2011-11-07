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

	define("VALIDUNAME", "/^[a-zA-Z0-9._\-]{4,15}$/");
	define("MINPASSSTRENGTH", 144555105949057024); // lámanie hesla so silou 31536000000000000 bude trvať približne jeden rok
	define("VALIDFIRSTNAME", "/.*/");
	define("VALIDLASTNAME", "/.*/");
?>
