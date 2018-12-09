<?PHP
    include_once("config/setup.php");
    include_once("authentication/session.php");
    include_once("authentication/utilities.php");

    if (!isset($_SESSION['username']))
    {
        $msg = "You need to be logged in to do that.";
        header("Location: authentication/login.php?msg=$msg");
    }
    $id = $_SESSION['id'];
    if (isset($_POST[change_email]))
    {
        $new_email =  htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') ;
        try
        {
            $sql = "UPDATE users SET email = :email WHERE id = :id";
            $statement = $connection->prepare($sql);
            $statement->execute(array(":id"=>$id, ":email" => $new_email));
            if ($statement->rowCount() == 1)
            {
                $result = "<P style='color: green;'>Your email address has been updated.</P>";
            }

        }
        catch (PDOException $ex)
        {
            if (strstr($ex,"Duplicate"))
                $result = "<P style='color: red;'>A user is registered with that email address</P>";
            else
                echo $ex;

        }
    }
    if (isset($_POST['change_username']))
    {
        $form_errors = array();
        $check_length = array("username" => 5);
        $form_errors = array_merge($form_errors, check_min_length($check_length));
        $new_username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        if (empty($form_errors))
        {
            try
            {
                $sql = "UPDATE users SET username = :username WHERE id = :id";
                $statement = $connection->prepare($sql);
                $statement->execute(array(":id"=>$id, ":username" => $new_username));
                if ($statement->rowCount() == 1)
                {
                    $result = "<P style='color: green;'>Your username has been updated.</P>";
                    $_SESSION['username'] = $new_username;
                }
            }
            catch (PDOException $exception)
            {
             echo $ex;
            }
        }
    }
if (isset($_POST['change_password']))
{
    $form_errors = array();
    $required_fields = array("new_password", "confirm_password");
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
    $check_length = array("new_password"=> 6, "confirm_password"=> 6);
    $form_errors = array_merge($form_errors, check_min_length($check_length));
    if (empty($form_errors))
    {
        $password1 =  htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8');
        $password2 =  htmlspecialchars($_POST['confirm_password'], ENT_QUOTES, 'UTF-8');
        $id = $_SESSION['id'];

        if ($password1 != $password2)
        {
            $result = "<P style='color:red;'>New password and confirmation password do not match</P>";
        }
        else
        {
            try
            {
                $sql = "SELECT * from users WHERE id = :id";
                $statement_main = $connection->prepare($sql);
                $statement_main->execute(array(':id' => $id));
                if ($statement_main->rowCount() == 1)
                {
                    while ($row = $statement_main->fetch())
                    {
                            $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
                            $sql_update = "UPDATE users SET password = :password WHERE id = :id";
                            $statement = $connection->prepare($sql_update);
                            $statement->execute(array(':password'=>$hashed_password, ':id'=>$id));
                            $result = "<P style='color:green;'>Password reset successful</P>";
                            $success = 1;
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
if (isset($_POST['change_notify']))
{
    $checked = $_POST['notify'];
    if (isset($checked))
    {
        try
        {
            $sql = "UPDATE users SET receive_notifications = :receive WHERE id = :id";
            $statement = $connection->prepare($sql);
            $statement->execute(array(":receive" => 1, ":id" => $id));
            if ($statement->rowCount() == 1)
            {
                $result = "<P style='color:green;'>You will now receive notifications via email.</P>";
            }
        }
        catch (PDOException $exception)
        {
            echo $exception;
        }
    }
    else
    {
        try
        {
            $sql = "UPDATE users SET receive_notifications = :receive WHERE id = :id";
            $statement = $connection->prepare($sql);
            $statement->execute(array(":receive" => 0, ":id" => $id));
            if ($statement->rowCount() == 1)
            {
                $result = "<P style='color:green;'>You will not receive notifications via email.</P>";
            }
        }
         catch (PDOException $ex)
         {
             echo $ex;
         }
     }
}

try{
        $sql = "SELECT * FROM users WHERE id = $id";
        $stat = $connection->prepare($sql);
        $stat->execute();
        if ($stat->rowCount() == 1)
        {
            while ($row = $stat->fetch())
            {
                $is_checked = $row['receive_notifications'];
            }
        }
}
catch (PDOException $exception)
{
    echo $exception;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Preferences</title>
    <link rel="stylesheet" href="style/style.css" type="text/css">
    <link rel="stylesheet" href="style/w3.css" type="text/css">
</head>
<body>
    <h1>
        Camagru
    </h1>
    <A href="index.php">Home</A>
    <A href="capture.php">Take/Edit Photo</A>
    <hr>
    <?PHP
        if (isset($result) || isset($form_errors)) {
            echo $result;
            echo show_errors($form_errors);
        }
    ?>
    <h3>
        Change Email
    </h3>
    <form action="" method="post">
        <table>
            <tr>
                <td>
                    <label for="username">New email:</label>
                </td>
                <td>
                    <input type="email" required name="email">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="change_email" value="Change email">
                </td>
            </tr>
        </table>
    </form>
    <hr>
    <h3>
        Change Username
    </h3>
    <form action="" method="post">
        <table>
            <tr>
                <td>
                    <label for="username">New username:</label>
                </td>
                <td>
                    <input type="text" name="username" required>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="change_username" value="Change Username">
                </td>

            </tr>
        </table>
    </form>
    <hr>
    <h3>
        Change Password
    </h3>
    <form action="" method="post">
        <table>
            <tr>
                <td>
                    <label for="new_password">New Password</label>
                </td>
                <td>
                    <input type="password" name="new_password">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="confirm_password">Confirm Password</label>
                </td>
                <td>
                    <input type="password" name="confirm_password">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="change_password" value="Change Password">
                </td>
            </tr>
        </table>
    </form>
    <hr>
    <h3>
        Notifications
    </h3>
    <form action="" method="post">
        <label for="notify">Receive Notifications</label>
        <input type="checkbox" name="notify" <?PHP if ($is_checked) echo "checked";?>>
        <br>
        <input type="submit" value="save change" name="change_notify">
    </form>
    <p align="center">
        You are logged in as <?PHP if(isset($_SESSION['username'])) echo $_SESSION['username'];?>
        <A href="authentication/logout.php">Logout</A>
    </p>
</body>
</html>
