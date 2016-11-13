<?

session_start();
if (!isset($_SESSION['logged']))
{
  header('Location: index.php');
  exit();
}

require_once "connect.php";
try {
 $conn = new PDO("mysql:host=$host; dbname=$db_name", $db_user, $db_password);
$conn->exec("SET CHARACTER SET utf8");      // Sets encoding UTF-8
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "UPDATE `users`
         SET `reputation` = :reputation,
             `money` = :money,
             `level` = :level,
             `experience` = :experience
       WHERE `user` = $_SESSION['user']";


 $statement = $conn->prepare($sql);
 $statement->bindValue(":reputation", $_POST['reputation']);
 $statement->bindValue(":money", $_POST['money']);
 $statement->bindValue(":level", $_POST['level']);
 $statement->bindValue(":experience", $_POST['experience']);
 $count = $statement->execute();

  $conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
