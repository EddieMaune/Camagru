<?php
    //include_once('view_post.php');
    include_once('authentication/session.php');
    include_once('config/setup.php');

    if (isset($_POST['comment_btn']))
    {
        $img = $_POST['image'];
        $img_id = $_POST['image_id'];
        $comment = $_POST['comment'];
        $user_id = $_SESSION['id'];
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