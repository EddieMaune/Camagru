<!DOCTYPE HTML>
<HTML>
	<HEAD>
		<META charset="utf-8">
		<TITLE>Email Confirmation</TITLE>
	</HEAD>
	<BODY>
<?PHP
	require("../config/setup.php");
	
	$username = $_GET['username'];
	$code = $_GET['code'];

	if (isset($username) && isset($code))
	{
		try
		{
			$sql = "SELECT * FROM users WHERE username = :username";
			$statement = $connection->prepare($sql);
			$statement->execute(array(':username'=> $username));
			while ($row = $statement->fetch())
			{
				$db_code = $row['confirm_code'];
			}
			if ($code == $db_code)
			{
				try
				{
					$sql = "UPDATE users SET confirmed = :confirm WHERE username = :username";
					$statement = $connection->prepare($sql);
					$statement->execute(array(':confirm' => 1, ':username' => $username));
					$sql = "UPDATE users SET confirm_code = :confirm_code WHERE username = :username";
					$statement = $connection->prepare($sql);
					$statement->execute(array(':confirm_code' => 0, ':username' => $username));
		
					echo "<P style='color:green;'>Email Confirmed You can login now.</P>";
					header("Refresh: 2; url=login.php");
				}
				catch (PDOException $e)
				{
					echo "An Error occurred".$e;
				}
			}
			else
			{
				echo "An Error occurred: confirm code invalid";
			}
		}
		catch(PDOException $ex)
		{
			echo "An error occurred: ".$ex;
		}
	}
	else
	{
		header("Location: ../index.php");
	}
?>
	</BODY>
</HTML>
