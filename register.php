<?php

	session_start();
 echo $_POST['password1'];
	if (isset($_POST['email']))
	{
		//Validation flag
		$valid=true;
		$nick = $_POST['nick'];

		//validate nick
		if ((strlen($nick)<3) || (strlen($nick)>20))
		{
			$valid=false;
			$_SESSION['e_nick']="Nick must be 3 to 20 symbols long!";
		}

		if (ctype_alnum($nick)==false)
		{
			$valid=false;
			$_SESSION['e_nick']="Nick can have only letters and numbers";
		}

		// validate email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$valid=false;
			$_SESSION['e_email']="Invalid Email!";
		}
		//validate password
		$pass1 = $_POST['password1'];
		$pass2 = $_POST['password2'];

		if ((strlen($pass1)<8) || (strlen($pass1)>20))
		{
			$valid=false;
			$_SESSION['e_pass']="Password must be 8 to 20 symbols long!";
		}

		if ($pass1!=$pass2)
		{
			$valid=false;
			$_SESSION['e_pass']="Passwords must be identical!";
		}

		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);

		//Terms check?
		if (!isset($_POST['terms']))
		{
			$valid=false;
			$_SESSION['e_terms']="Accept terms and conditions!";
		}

		//CAPTCHA
		$secret = "6Ldb0AsUAAAAAMs7lS6WhJLG2R3MY_lm3d_k_U8-";

		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

		$answer = json_decode($check);

		if ($answer->success==false)
		{
			$valid=false;
			$_SESSION['e_bot']="I am not a robot!";
		}

		//Catch Data
		$_SESSION['fr_nick'] = $nick;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_pass1'] = $pass1;
		$_SESSION['fr_pass2'] = $pass2;
		if (isset($_POST['terms'])) $_SESSION['fr_terms'] = true;

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Does Email exist?
				$result = $connection->query("SELECT id FROM users WHERE email='$email'");

				if (!$result) throw new Exception($connection->error);

				$ile_takich_maili = $result->num_rows;
				if($ile_takich_maili>0)
				{
					$valid=false;
					$_SESSION['e_email']="E-mail already exist!";
				}

				//Does Nick is already taken?
				$result = $connection->query("SELECT id FROM users WHERE user='$nick'");

				if (!$result) throw new Exception($connection->error);

				$nicks_number = $result->num_rows;
				if($nicks_number>0)
				{
					$valid=false;
					$_SESSION['e_nick']="Nick already exist!";
				}

				if ($valid==true)
				{
					//Add player

					if ($connection->query("INSERT INTO users VALUES (NULL, '$nick', '$pass_hash', '$email', 0, 100, 0, 0, 14)"))
					{
						$_SESSION['registered']=true;
						header('Location: welcome.php');
					}
					else
					{
						throw new Exception($connection->error);
					}

				}

				$connection->close();
			}

		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server Error!</span>';
			echo '<br />Dev Build: '.$e;
		}

	}


?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>IndieDevEmpire</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>

	<style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>

<body>

	<form method="post">

		Nickname: <br /> <input type="text" value="<?php
			if (isset($_SESSION['fr_nick']))
			{
				echo $_SESSION['fr_nick'];
				unset($_SESSION['fr_nick']);
			}
		?>" name="nick" /><br />

		<?php
			if (isset($_SESSION['e_nick']))
			{
				echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}
		?>

		E-mail: <br /> <input type="text" value="<?php
			if (isset($_SESSION['fr_email']))
			{
				echo $_SESSION['fr_email'];
				unset($_SESSION['fr_email']);
			}
		?>" name="email" /><br />

		<?php
			if (isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
		?>

		Password: <br /> <input type="password"  value="<?php
			if (isset($_SESSION['fr_pass1']))
			{
				echo $_SESSION['fr_pass1'];
				unset($_SESSION['fr_pass1']);
			}
		?>" name="password1" /><br />

		<?php
			if (isset($_SESSION['e_pass']))
			{
				echo '<div class="error">'.$_SESSION['e_pass'].'</div>';
				unset($_SESSION['e_pass']);
			}
		?>

		Repeat Password: <br /> <input type="password" value="<?php
			if (isset($_SESSION['fr_pass2']))
			{
				echo $_SESSION['fr_pass2'];
				unset($_SESSION['fr_pass2']);
			}
		?>" name="password2" /><br />

		<label>
			<input type="checkbox" name="terms" <?php
			if (isset($_SESSION['fr_terms']))
			{
				echo "checked";
				unset($_SESSION['fr_terms']);
			}
				?>/> I accept terms and conditions
		</label>

		<?php
			if (isset($_SESSION['e_terms']))
			{
				echo '<div class="error">'.$_SESSION['e_terms'].'</div>';
				unset($_SESSION['e_terms']);
			}
		?>

		<div class="g-recaptcha" data-sitekey="6Ldb0AsUAAAAAJTSuciMC3WUD16XW2b_KTtU8Xn7"></div>

		<?php
			if (isset($_SESSION['e_bot']))
			{
				echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
				unset($_SESSION['e_bot']);
			}
		?>

		<br />

		<input type="submit" value="Register!" />

	</form>

</body>
</html>
