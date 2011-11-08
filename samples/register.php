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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>Registrácia</title>
<script type="text/javascript">
<!--
	var minPassStrength = <?php echo MINPASSSTRENGTH; ?>;

	function passStrength(password) {
		var lowercase = false;             // obsahuje malé písmená?
		var uppercaseb = false;            // obsahuje veľké písmeno na začiatku?
		var uppercasem = false;            // obsahuje veľké písmená a mimo začiatku?
		var numbers = false;               // obsahuje čísla?
		var whitespace = false;            // obsahuje medzeru?
		var specials = false;              // obsahuje špeciálne znaky?

		for(i = 0; i < password.length; ++i) {
			if(password[i] >= "a" && password[i] <= "z") lowercase = true; else						// malé písmená	
			if(password[i] >= "A" && password[i] <= "Z") if(i == 0) uppercaseb = true; else uppercasem = true; else  	// veľké písmená
			if(password[i] >= "0" && password[i] <= "9") numbers = true; else                                          	// čísla
			if(password[i] == " ") whitespace = true; else specials = true;                                             	// medzera; všetko ostatné sú špeciálne
		}

		var chars = 0;
		if(lowercase) chars += 26;
		if(uppercasem) chars += 26;
		if(numbers) chars += 10;
		if(whitespace) ++chars;
		if(specials) chars += 34;

		num = Math.pow(chars, password.length);
		if(!uppercasem && uppercaseb) num *= 2;
		return num;
	}

	function checkPass1() {
		if(passStrength(document.getElementById("password").value) < minPassStrength) document.getElementById("passinf1").innerHTML = "Heslo je píliš slabé!"; else document.getElementById("passinf1").innerHTML = "Sila hesla je OK";
		if(document.getElementById("password").value != document.getElementById("password2").value) document.getElementById("passinf2").innerHTML = "Heslá nie sú rovnaké!"; else document.getElementById("passinf2").innerHTML = "Heslá sú rovnaké.";
	}
//-->
</script>
</head>

<body>
	<h2>Registrácia</h2>
	<?php
		//error_reporting(E_ALL);
		$regok = false;
		if(count($_POST) > 0) {
			if(!($errors = checkInput($_POST))) {
				if(register($_POST)) {
					echo "Registrácia úspešná, môžete sa <a href=\"login.php\">prihlásiť</a>.";
					$regok = true;
				} else echo "Registrácia zlyhala.";
			} else {
				echo "Vyskytli sa tieto chyby:<br/><ul style=\"color: #FF0000;\">";
				foreach($errors as $err) {
					echo "<li>".errorstr($err)."</li>";
				}
				echo "</ul>";
			}
		}
		if(!$regok) {
	?>
		<form action="" method="post">
			<div style="float: left;">
				Prihlasovacie meno<br/>
				E-mail<br/>
				Heslo<br/>
				Heslo pre kontrolu ešte raz<br/>
				Meno<br/>
				Priezvisko<br/>
			</div>
			<div style="float: left;">
				<input type="text" name="username"<?php if(isset($_POST["username"])) echo " value=\"".htmlspecialchars($_POST["username"])."\""; ?>/><br/>
				<input type="text" name="email"<?php if(isset($_POST["email"])) echo " value=\"".htmlspecialchars($_POST["email"])."\""; ?>/><br/>
				<input type="password" name="password" id="password" onkeyup="checkPass1();"/> <span id="passinf1"></span><br/>
				<input type="password" name="password2" id="password2" onkeyup="checkPass1();"/> <span id="passinf2"></span><br/>
				<input type="text" name="firstname"<?php if(isset($_POST["firstname"])) echo " value=\"".htmlspecialchars($_POST["firstname"])."\""; ?>/><br/>
				<input type="text" name="lastname"<?php if(isset($_POST["lastname"])) echo " value=\"".htmlspecialchars($_POST["lastname"])."\""; ?>/><br/>
			</div>
			<input type="submit" value="Odoslať" style="float: left; clear: left;"/>
		</form>
	<?php	} ?>
		
</body>
</html>
