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

	include("../userfunc.php");

	if(count($_POST) > 0) {
		if(isset($_POST["logout"])) {
			logout($user["cookie"]);
			setcookie("cookie", "");
		} else
			if(login($_POST)) {
				setcookie("cookie", $user["cookie"]);
			}
	} else checkCookie($_COOKIE["cookie"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>Prihlásenie</title>

<script type="text/javascript">
<!--
	var minPassStrength = <?php echo MINPASSSTRENGTH; ?>;

	funcion passStrength(password) {
		var lowercase = false;             // obsahuje malé písmená?
		var uppercaseb = false;            // obsahuje veľké písmeno na začiatku?
		var uppercasem = false;            // obsahuje veľké písmená a mimo začiatku?
		var numbers = false;               // obsahuje čísla?
		var whitespace = false;            // obsahuje medzeru?
		var specials = false;              // obsahuje špeciálne znaky?

		for(i = 0; i < password.length; ++i) {
			if(password[i] >= "a" && password[$i] <= "z") lowercase = true; else						// malé písmená	
			if(password[i] >= "A" && password[i] <= "Z") if(i == 0) uppercaseb = true; else uppercasem = true; else  	// veľké písmená
			if(password[i] >= "0" && password[i] <= "9") numbers = true; else                                          	// čísla
			if(password[i] == " ") whitespace = true; else specials = true;                                             	// medzera; všetko ostatné sú špeciálne

			var chars = 0;
			if(lowercase) chars += 26;
			if(uppercasem) chars += 26;
			if(numbers) chars += 10;
			if(whitespace) ++chars;
			if(specials) chars += 34;

			num = Math.pow(chars, strlen(password));
			if(!uppercasem && uppercaseb) $num *= 2;
			return num;
		}
//-->
</script>

</head>

<body>
	<?php
		if(!$user) {
	?>
		<h2>Prihlásenie</h2>
	<?php if($user !== NULL) echo "<b style=\"color: #FF0000;\">Prihlásenie neúspešné!</b>"; ?>
		<form action="" method="post">
			<div style="float: left;">
				Prihlasovacie meno</br>
				Heslo<br/>
			</div>
			<div style="float: left;">
				<input type="text" name="username"<?php if(isset($_POST["username"])) echo " value=\"".htmlspecialchars($_POST["username"])."\""; ?>/><br/>
				<input type="password" name="password"/>
			</div><br/>
			<input type="submit" value="Odoslať" style="float: left; clear: both;"/>
		</form>
	<?php
		} else if($user) {
			if(strlen($user["firstname"]) > 0 || strlen($user["lastname"]) > 0)
				echo "Ste prihlásení ako ".((strlen($user["firstname"]) > 0)?(htmlspecialchars($user["firstname"])." "):"").htmlspecialchars($user["lastname"]).".<br/>";
			else
				echo "Ste prihlásení ako ".htmlspecialchars($user["username"]).".<br/>";
	?>
		<form action="" method="post">
			<input type="submit" name="logout" value="Odhlásiť"/>
		</form>
	<?php
		}
	?>
</body>
</html>
