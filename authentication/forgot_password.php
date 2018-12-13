<?PHP
include_once('../config/setup.php');
include_once('utilities.php');
if (isset($_GET['email']) && isset($_GET['code']))
{
	if (isset($_POST['reset']))
	{
		$form_errors = array();
		$required_fields = array("new_password", "confirm_password");
		$form_errors = array_merge($form_errors, check_empty_fields($required_fields));
		$check_length = array("new_password"=> 6, "confirm_password"=> 6);
		$form_errors = array_merge($form_errors, check_min_length($check_length));
        if (isset($_POST['new_password']))
            $form_errors = array_merge($form_errors, check_password_strength($_POST['new_password']));
		if (empty($form_errors))
		{
			$email = $_GET['email'];
			$code = $_GET['code'];
			$password1 = $_POST['new_password'];
			$password2 = $_POST['confirm_password'];

			if ($password1 != $password2)
			{
				$result = "<P style='color:red;'>New password and confirmation password do not match</P>";
			}
			else
			{
				try
				{
					$sql = "SELECT * from users WHERE email = :email";
					$statement_main = $connection->prepare($sql);
					$statement_main->execute(array(':email' => $email));
					if ($statement_main->rowCount() == 1)
					{
						while ($row = $statement_main->fetch())
						{
							if ($row['confirm_code'] == $code)
							{
								$hashed_password = password_hash($password1, PASSWORD_DEFAULT);
								$sql_update = "UPDATE users SET password = :password WHERE email = :email";
								$statement = $connection->prepare($sql_update);
								$statement->execute(array(':password'=>$hashed_password, ':email'=>$email));
								$sql_update = "UPDATE users SET confirm_code = :code WHERE email = :email";
								$statement = $connection->prepare($sql_update);
								$statement->execute(array(':code' => 0, ':email'=>$email));
								$result = "<P style='color:green;'>Password reset successful</P>";
								$success = 1;
							}
							else
							{
								$result = "<P style='color:red;'>This link has expired, please request another one</P>";
							}
						}
					}
					else
					{
						$result = "<P style='color:red;'>User email not registered</P>";
					}
				}
				catch (PDOException $ex)
				{
					$result = "<P style='color:red;'>An error occurred".$ex."</P>";
					$success = 0;
				}
			}
		}
		else
		{
			if (count($form_errors) == 1)
			{
				$result = "<P style='color:red;'>There is 1 error in the form</P>";
			}
			else
			{
				$result = "<P style='color:red;'>There are ".count($form_errors)." errors in the form";
			}
		}
	}
}
else
{
	header("Location: ../index.php");
}
?>
<!DOCTYPE HTML>
<HTML>
    <HEAD>
        <LINK rel="stylesheet" type="text/css" href="../style/w3.css">
        <TITLE>
            Reset Password
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
{
	echo $result;
	if (isset($success) && $success == 1)
		header("Refresh: 2; url=login.php");
}
if (!empty($form_errors))
    echo show_errors($form_errors);
?>
        <FORM method="post" action="">
            <TABLE>
                <TR>
                    <TD>
                        New Password:
                    </TD>
                    <TD>
                        <input type="password" value="" name="new_password">
                    </TD>
				</TR>
				<TR>
					<TD>
						Confirm Password:
					</TD>
					<TD>
						<INPUT type="password" value="" name="confirm password">
				</TR>
                <TR>
                        <TD></TD>
                        <TD>
                            <INPUT style="float:right;" type="submit" value="Reset Password" name="reset">
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
