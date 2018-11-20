<?PHP
include_once("../config/setup.php");
if (isset($_POST['btn']))
{
	$email = $_POST['email'];
	$confirm_code = rand();

	$confirmation_message = "
		Confirm Your Email
		Click the link below to reset your password.
		http://localhost:8080/Camagru/authentication/forgot_password.php?email=$email&code=$confirm_code
							";
	try
	{
		$sql = "SELECT email FROM users where email = :email";
	   	$statement = $connection->prepare($sql);
		$statement->execute(array(':email'=>$email));
		if ($statement->rowCount() == 1)
		{
			mail($email, "Camagru Email Confirmation", $confirmation_message, "From: dontreply@camagru.com");
				$sql = "UPDATE users SET confirm_code = :confirm_code WHERE email = :email";
				$statement = $connection->prepare($sql);
				$statement->execute(array(':confirm_code' => $confirm_code, ':email' => $email));
				$result = "<P style='color:green;'>Reset email sent.</P>";
		}
		else
		{
			$result = "<P style='color:red'>$email is not a registered user email address</P>";
		}
	}
	catch (PDOException $ex)
	{
		echo "An error Occurred: ".$ex;
	}
}
?>
<!DOCTYPE HTML>
<HTML>
	<HEAD>
        <LINK rel="stylesheet" type="text/css" href="../style/w3.css">
        <TITLE>
            Submit Email
        </TITLE>
	</HEAD>
	<BODY>
		<H1>
			Camagru
		</H1>
		<HR>
		<H2>
			Reset Password
		</H2>
<?PHP
if (isset($result))
	echo $result;
?>
		<FORM method="post" action="">
			<INPUT type="email" placeholder="Email Address"name="email">
			<INPUT type="submit" value="submit email" name="btn">
		</FORM>
		<A href="../index.php">Home</A>
	</BODY>
</HTML>
