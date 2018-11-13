<?PHP
    include_once("config/setup.php");
    include_once("authentication/session.php");

    if (isset($_SESSION['username']))
    {
        if (isset($_POST['submit_image']))
        {
            //where image is gonna be placed
            $target_folder = "images/";
            $target_file = rand().basename($_FILES['image']['name']);
            $target = $target_folder.$target_file;
            $image = $target_file;//$_FILES['image']['name'];
            try
            {
                $sql = "INSERT INTO images (image, likes, user_id, date_created) VALUES(:image, :likes, :user_id, NOW())";
                $statement = $connection->prepare($sql);
                $statement->execute(array(':image' => $image, ':likes' => 0, 'user_id' => $_SESSION['id']));
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target))
                {
                    $msg = "<P style='color:green;'>Image uploaded successfully</P>";
                }
                else {
                    $msg = "There was a problem uploading";
                }
            }
            catch (PDOException $ex)
            {
                echo "Error: " . $ex;

            }
        }
    }
    else
    {
        header("Location: index.php");
    }
?>
<HTML>
    <HEAD>
        <TITLE>
            Upload Image
        </TITLE>
    </HEAD>
    <BODY>
    <H1>
        Camagru
    </H1>
    <HR>
    <?PHP if (isset($msg))
            echo $msg;
    ?>
        <FORM method="POST" action="upload.php" enctype="multipart/form-data">
            <INPUT type="file" name="image">
            <INPUT type="submit" name="submit_image" value="Upload">
        </FORM>
        <A href="index.php">Home</A>
    </BODY>
</HTML>