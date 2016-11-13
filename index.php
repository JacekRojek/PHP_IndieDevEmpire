<?php

	session_start();

	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
	{
		header('Location: app.php');
		exit();
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>IndieDevEmpire</title>
</head>

<body>

	Welcome to Indie Dev Empire<br /><br />

	<a href="register.php">Register free account!</a>
	<br /><br />

	<form action="login.php" method="post">

		Login: <br /> <input type="text" name="login" /> <br />
		Password: <br /> <input type="password" name="password" /> <br /><br />
		<input type="submit" value="Log In" />

	</form>

<?php
	if(isset($_SESSION['error']))	echo $_SESSION['error'];
?>

</body>
</html>
