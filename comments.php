<?php
    //include_once('view_post.php');
    include_once('authentication/session.php');
    include_once('config/setup.php');

    if (isset($_POST['comment_btn']))
    {
        $img = $_POST['image'];
        $img_id = $_POST['image_id'];
        $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
        $user_id = $_SESSION['id'];
        $uid = $_POST['uid'];
        $user_firstname;

        try
        {
            $sql = "SELECT * FROM users WHERE id = $user_id";
            $statement = $connection->prepare($sql);
            $statement->execute();
            if ($statement->rowCount() == 1)
            {
                while ($row = $statement->fetch())
                {
                    $user_firstname = $row['firstname'];
                    $receive_notifications = $row['receive_notifications'];
                }
            }
        }
        catch (PDOException $ex)
        {
            echo "<P style='color:red;'>" . $ex . "</P>";
        }
        try
        {
            $sql = "SELECT * FROM users WHERE id = $uid";
            $statement = $connection->prepare($sql);
            $statement->execute();
            if ($statement->rowCount() == 1)
            {
                while ($row = $statement->fetch())
                {
                    $user_email = $row['email'];
                }
            }
        }
        catch (PDOException $ex)
        {
            echo "<P style='color:red;'>" . $ex . "</P>";
        }
        try
        {
            if (isset($user_id) && isset($user_firstname)) {

                $sql = "INSERT INTO comments (comment, image_id, user_id, user_firstname) VALUES (:comment, :image_id, :user_id, :user_firstname)";
                $statement = $connection->prepare($sql);
                $statement->execute(array(':comment' => $comment, ':image_id' => $img_id, ':user_id' => $user_id, ':user_firstname' => $user_firstname));
                if ($statement->rowCount() == 1)
                {
                    if ($receive_notifications == 1 && $uid != $user_id)
                    {
                        $confirmation_message = "$user_firstname commented on your picture saying: $comment";
                        mail($user_email, "Camagru Notification", $confirmation_message, "From: dontreply@camagru.com");
                        //echo "what";
                    }
                   // echo " not";
                    header("Location: view_post.php?image=$img&img_id=$img_id");
                }
                else
                {
                    echo "<H1>Error</H1>";
                }
            }
        }
        catch(PDOException $ex)
        {
            echo "<P style='color:red;'>".$ex."</P>";
        }
    }
?>