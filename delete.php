<?php
    include_once ("config/setup.php");
    if (isset($_POST['delete']))
    {
        try
        {
            $image_id = $_POST['image_id'];
            $sql = "DELETE FROM images WHERE id = $image_id";
            $connection->exec($sql);
            $sql = "DELETE FROM comments WHERE image_id = $image_id";
            $connection->exec($sql);
            $sql = "DELETE FROM likes WHERE image_id = $image_id";
            $connection->exec($sql);
            if (unlink("images/".$_POST['image'])) {
                echo "deleted";
                if (isset($_POST['from']))
                    header("Location:capture.php");
                else
                    header("Location:index.php");
            }
            else{
                echo "Failed to delete";
            }
        }
        catch(PDOException $ex)
        {
            echo $ex;
        }
    }
?>