<?php

	session_start();

	if (!isset($_SESSION['registered']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['registered']);
	}

	//Usuwanie zmiennych pamiętających wartości wpisane do formularza
	if (isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
	if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
	if (isset($_SESSION['fr_pass'])) unset($_SESSION['frpass1']);
	if (isset($_SESSION['fr_pass'])) unset($_SESSION['frpass2']);
	if (isset($_SESSION['fr_terms'])) unset($_SESSION['fr_terms']);

	//Usuwanie błędów rejestracji
	if (isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['epass'])) unset($_SESSION['epass']);
	if (isset($_SESSION['e_terms'])) unset($_SESSION['e_terms']);
	if (isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>IndieDevEmpire</title>
</head>

<body>

	Thank You for registering!<br /><br />

	<a href="index.php">Log In!</a>
	<br /><br />

</body>
</html>
