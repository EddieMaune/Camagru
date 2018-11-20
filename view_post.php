
<!DOCTYPE HTML>
<HTML>
    <HEAD>
        <LINK rel="stylesheet" type="text/css" href="style/style.css">
        <LINK rel="stylesheet" type="text/css" href="style/w3.css">
        <TITLE>
            Post
        </TITLE>
    </HEAD>
    <BODY>
    <H1>
        Camagru
    </H1>
    <A href="index.php">Home</A>
    <A href="upload.php">Upload</A>
    <A href="#">Take/Edit Photo</A>
    <HR>
    <?PHP
    include_once('authentication/session.php');
    include_once('config/setup.php');
    if (!isset($_SESSION['username']))
    {
        $msg = "You need to be logged in to comment/like images";
        header("Location: authentication/login.php?msg=$msg");
    }
    $img = $_GET['image'];
    $img_id = $_GET['img_id'];
    $img_path = "images/".$img;
    echo "<IMG src='$img_path' height='400' width='400'>";
    try {

        $sql = "SELECT * FROM images WHERE id = $img_id";
        $statement = $connection->prepare($sql);
        $statement->execute();
        if ($statement->rowCount() == 1)
        {
            while ($row = $statement->fetch())
            {
                $likes = $row['likes'];
                $uid = $row['user_id'];
            }
        }
    }
    catch(PDOException $ex) {
        echo $ex;
    }
    ?>
    <div>
        <FORM action="likes.php" method="post">
            <input type="submit" value="Like" name="like"><?PHP if(isset($likes)) echo $likes;?>
            <input type="hidden" value="<?PHP echo $img;?>" name="image">
            <input type="hidden" value="<?PHP echo $img_id;?>" name="image_id">
            <input type="hidden" value="<?PHP if(isset($likes)) echo $likes; else echo '0';?>" name="likes">
        </FORM>
        <?PHP if ($uid == $_SESSION['id'])
            echo "
        <FORM action='delete.php' method='post'>
            <input type='submit' value='Delete' name='delete'>
            <input type='hidden' value='$img' name='image'>
            <input type='hidden' value='$img_id' name='image_id'>
            <input type='hidden' value='$uid' name='user_id'>
        </FORM>";?>
    </div>
    <div id="comments">
        <div class="comment">
            <?PHP
            try {

                $sql = "SELECT * FROM comments WHERE image_id = $img_id";
                $statement = $connection->prepare($sql);
                $statement->execute();
                while ($row = $statement->fetch()) {
                    $user_firstname = $row['user_firstname'];
                    $comment = $row['comment'];
                    echo "<h3><small>$user_firstname</small> </h3>";
                    echo "<p>$comment</p>
                          <hr>";
                }
            }
            catch (PDOException $ex)
            {
                echo $ex;
            }
            ?>


        </div>

        <FORM action="comments.php" method="post">
            <textarea required name="comment"></textarea>
            <input type="submit" value = "Leave a comment" name="comment_btn">
            <input type="hidden" value="<?PHP echo $img;?>" name="image">
            <input type="hidden" value="<?PHP echo $img_id;?>" name="image_id">
        </FORM>
    </div>
    </BODY>
</HTML>
