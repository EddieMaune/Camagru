<?PHP
    include_once("config/setup.php");
    include_once("authentication/session.php");


    if ($_FILES['file']['size'] > 0)
    {
        if ($_FILES['file']['size'] < 153600)
        {
            if (move_uploaded_file($_FILES['file']['tmp_name'], "images/temp/"."temp.png"))
            {
                ?>
<script type="text/javascript">
    parent.document.getElementById("message").innerHTML = "";
    parent.document.getElementById("file").value = "";
    window.parent.updatepicture("<?PHP echo 'images/temp/'.'temp.png';?>");
</script>
<?PHP
            }
            else
            {
                //upload failed
                ?>
                <script type="text/javascript">
                    parent.document.getElementById('message').innerHTML = "<font color='#ff0000'> There was an error uploading your image</font>";
                </script>

    <?PHP
            }
        }
        else
        {
            //file is too big
            ?>
            <script type="text/javascript">
                parent.document.getElementById('message').innerHTML =  "<font color='#ff0000'> Your file is bigger than 150kb, please try uploading a different one.</font>";
            </script>
            <?PHP
        }
    }
?>
  <?PHP
            /*  if (isset($_SESSION['username']))
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
    }*/
?>

