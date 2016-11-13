<?php

	session_start();

	if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['password'];

		$login = htmlentities($login, ENT_QUOTES, "UTF-8");

		if ($result = @$connection->query(
		sprintf("SELECT * FROM users WHERE user='%s'",
		mysqli_real_escape_string($connection,$login))))
		{
			$users_count = $result->num_rows;
			if($users_count>0)
			{
				$line = $result->fetch_assoc();

				if (password_verify($haslo, $line['password']))
				{
					$_SESSION['logged'] = true;
					$_SESSION['id'] = $line['id'];
					$_SESSION['email'] = $line['email'];
					$_SESSION['user'] = $line['user'];
					$_SESSION['money'] = $line['money'];
					$_SESSION['reputation'] = $line['reputation'];
					$_SESSION['level'] = $line['level'];
					$_SESSION['experience'] = $line['experience'];
					$_SESSION['premium'] = $line['premium'];

					unset($_SESSION['error']);
					$result->free_result();
					header('Location: app.php');
				}
				else
				{
					$_SESSION['error'] = '<span style="color:red">Wrong login or password!</span>';
					header('Location: index.php');
				}

			} else {

				$_SESSION['error'] = '<span style="color:red">Wrong login or password!</span>';
				header('Location: index.php');

			}

		}

		$connection->close();
	}

?>
