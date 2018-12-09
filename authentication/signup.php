<?PHP
include_once("../config/setup.php");
include_once("utilities.php");
/*if (!isset($_POST['signup']))
if (isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache'))
{
    echo "reloaded";
}*/

if (isset($_POST["signup"]))
{
	//header("Refresh:0");
	$form_errors = array();

	$required_fields = array('firstname', 'username', 'password', 'email', 'confirm_password');

	$form_errors = array_merge($form_errors, check_empty_fields($required_fields));
	$fields_to_check =  array('username' => 4, 'password' => 6, 'firstname' => 1);
	$form_errors = array_merge($form_errors, check_min_length($fields_to_check));
	$form_errors = array_merge($form_errors, check_email($_POST));
	if (isset($_POST['password']))
	    $form_errors = array_merge($form_errors, check_password_strength($_POST['password']));
	if (empty($form_errors))
	{

		$email =  htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
		$firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
		$username = htmlspecialchars( $_POST['username'], ENT_QUOTES, 'UTF-8');
		$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
		$confirm_password = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES, 'UTF-8') ;


		if ($confirm_password == $password)
		{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try {
                $confirm_code = rand();
                $sql_insert = "INSERT INTO users (firstname,  username, password, email, confirmed, confirm_code, reg_date) VALUES (:firstname, :username, :password, :email, :confirmed, :confirm_code,  now())";
                $statement = $connection->prepare($sql_insert);
                $statement->execute(array(':firstname' => $firstname, ':username' => $username, ':password' => $hashed_password, ':confirmed' => 0, ':confirm_code' => $confirm_code, ':email' => $email));
                $confirmation_message = "
					Confirm Your Email
					Click the link below to verify your account.
					http://localhost:8080/Camagru/authentication/confirm.php?username=$username&code=$confirm_code
				";
                mail($email, "Camagru Email Confirmation", $confirmation_message, "From: dontreply@camagru.com");
                if ($statement->rowCount() == 1)
                    $result = "<P style='padding: 20px; color: green;'> Registration Successful, Please check your email for confirmation link.</P>";
            } catch (PDOException $ex) {
                $result = "<P style='padding: 20px; color: red;'> An error occurred:" . $ex->getMessage() . "</P>";
                if (strstr($result, "Duplicate"))
                    $result = "<P style='color:red;'>Email is already a registered user</P>";
            }
        }
        else
        {
            $result = "<P style='color:red;'>Re-entered password does not match password</P>";
        }
	}
	else
	{
		if (count($form_errors) == 1)
		{
			$result = "<P style='color:red;'> There is one required field in the form</P>";
		}
		else
		{
			$result =  "<P style='color: red;'> There  were ".count($form_errors)." errors in the form <BR> </P>";
		}
	}
}
?>
<!DOCTYPE HTML>
<HTML>
	<HEAD>
        <meta charset="UTF-8">
        <LINK rel="stylesheet" type="text/css" href="../style/w3.css">
		<TITLE>
			Registration
		</TITLE>
	</HEAD>
	<BODY>
		<H1>
			Camagru
		</H1>
		<HR>
		<H2>
		   Registration
		</H2>
<?php
if (isset($result))
{
	echo $result;
	//unset($result);
}
else
{
    echo "";
}
if (!empty($form_errors))
{
	echo show_errors($form_errors);
	$form_errors = NULL;
}

?>
		<FORM method="post" action="">
			<TABLE>
				 <TR>
					<TD>
						<LABEL for="firstname">Firstname:</LABEL>
					</TD>
					<TD>
						<input type="text" value="<?PHP if(isset($_POST['firstname'])) echo $_POST['firstname'];?>" name="firstname">
					</TD>
				</TR>
				 <TR>
					<TD>
						<LABEL for="email">Email:</LABEL>
					</TD>
					<TD>
						<input type="text" value="<?PHP if(isset($_POST['email'])) echo $_POST['email'];?>" name="email">
					</TD>
				</TR>
				<TR>
					<TD>
						<LABEL for="username">Username:</LABEL>
					</TD>
					<TD>
						<input type="text" value="<?PHP if(isset($_POST['username'])) echo $_POST['username'];?>" name="username">
					</TD>
				</TR>
				<TR>
					<TD>
						<LABEL for="password">Password:</LABEL>
					</TD>
					<TD>
						<input type="password" value="" name="password">
					</TD>
                <TR>
                    <TD>
                        <LABEL for="confirm_password">Confirm Password:</LABEL>
                    </TD>
                    <TD>
                        <INPUT type="password" value="" name="confirm_password">
                    </TD>
				</TR>
				<TR>
						<TD></TD>
						<TD>
							<INPUT style="float:right;" type="submit" value="signup" name="signup">
						</TD>
					</TD>
				</TR>
			</TABLE>
		</FORM>
		<P>
			<A href="../index.php">Home</A>
		</P>
	</BODY>
</HTML>
