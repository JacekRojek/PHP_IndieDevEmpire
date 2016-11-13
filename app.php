<?php

	session_start();

	if (!isset($_SESSION['logged']))
	{
		header('Location: index.php');
		exit();
	}

?>

<?php include 'head.php'; ?>
<div class="container">
	<?php
		echo "<p>Welcome ".$_SESSION['user'].'! [ <a href="logout.php">Log Out!</a> ]</p>';
		echo "<p><b>Money</b>: ".$_SESSION['money'];
		echo " | <b>Reputation</b>: ".$_SESSION['reputation'];
		echo " | <b>Level</b>: ".$_SESSION['level'];
		echo " | <b>Experience</b>: ".$_SESSION['experience']."</p>";

		echo "<p><b>E-mail</b>: ".$_SESSION['email'];
		echo "<br /><b>Days premium</b>: ".$_SESSION['premium']."</p>";
	 	echo '<a href="game.php">Game!</a>';
	?>
</div>



</body>
</html>
