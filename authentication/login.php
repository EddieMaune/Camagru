<?PHP
include_once("../config/setup.php");
include_once("utilities.php");
include_once("session.php");
if (isset($_POST['login']))
{
	$form_errors = array();
	$required_fields = array('username', 'password');
	$form_errors = array_merge($form_errors, check_empty_fields($required_fields));
	if (empty($form_errors))
	{
		//collect form data
		$user = $_POST['username'];
		$password = $_POST['password'];

		//check if user exists
		$sql = "SELECT * FROM users WHERE username = :username";
		$statement = $connection->prepare($sql);
		$statement->execute(array(':username' => $user));
		//check if row was returned i.e. if row is returned user actually exists.
		if ($statement->rowCount() == 1)
		{
			while($row = $statement->fetch())
			{
				$id = $row['id'];
				$hashed_password = $row['password'];
				$username = $row['username'];
				$confirmed = $row['confirmed'];
				if ($confirmed == 1)
				{
					if (password_verify($password, $hashed_password))
					{
						$_SESSION['id'] = $id;
						$_SESSION['username'] = $username;
						header("location: ../index.php");
					}
					else
					{
						$result = "<P style='color:red;'>Invalid username/password</P>";
					}
				}
				else
				{
					$result = "<P style='color:red'>Your account is not activated, please check your email for confirmation message</P>";
				}
			}
		}
		else
		{
			$result = "<P style='color:red;'>User is not registered</P>";
		}
	}
	else
	{
		if (count($form_errors) == 1)
			$result = "<P style='color:red;'>There was one error in the form</P>";
		else
			$result = "<P style='color:red;'>There were ".count($form_errors)." errors in the form</P>";
	}
}
?>
<!DOCTYPE HTML>
<HTML>
    <HEAD>
        <LINK rel="stylesheet" type="text/css" href="../style/w3.css">
        <TITLE>
            Login
        </TITLE>
    </HEAD>
	<BODY>
		<H1>
			Camagru
		</H1>
		<HR>
        <H2>
           Login
		</H2>
<?PHP
if (isset($_GET['msg']))
{
    $result = "<P style='color:red;'>".$_GET['msg'].".</P>";
}
if (isset($result))
	echo $result;
if (!empty($form_errors))
	echo show_errors($form_errors);
?>
		<FORM method="post" action="login.php">
			<TABLE>
				<TR>
					<TD>
						Username:
					</TD>
					<TD>
						<input type="text" value="" name="username">
					</TD>
				</TR>
				<TR>
					<TD>
						Password:
					</TD>
					<TD>
						<input type="password" value="" name="password">
					</TD>
				</TR>
				<TR>
						<TD><A href="email_submit.php">Forgot Password?</A></TD>
						<TD>
							<INPUT style="float:right;" type="submit" value="login" name="login">
						</TD>
					</TD>
				</TR>
			</TABLE>
		</FORM>
		<P>
			<A href="../index.php">Home
			</A>
	</BODY>
</HTML>
