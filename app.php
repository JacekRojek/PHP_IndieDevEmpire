<?php

	session_start();

	if (!isset($_SESSION['logged']))
	{
		header('Location: index.php');
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

<?php

	echo "<p>Welcome ".$_SESSION['user'].'! [ <a href="logout.php">Log Out!</a> ]</p>';
	echo "<p><b>Money</b>: ".$_SESSION['money'];
	echo " | <b>Reputation</b>: ".$_SESSION['reputation'];
	echo " | <b>Level</b>: ".$_SESSION['level'];
	echo " | <b>Experience</b>: ".$_SESSION['experience']."</p>";

	echo "<p><b>E-mail</b>: ".$_SESSION['email'];
	echo "<br /><b>Dni premium</b>: ".$_SESSION['premium']."</p>";
	
?>

</body>
</html>
