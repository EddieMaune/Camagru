<?php

include_once ("authentication/session.php");
include_once ("config/setup.php");

try{
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM images WHERE user_id=$id ORDER BY date_created DESC";
    $statement = $connection->prepare($sql);
    $statement->execute();
    while ($row = $statement->fetch())
    {
        $image = $row['image'];
        $img_id = $row['id'];
        $uid = $row['user_id'];
        echo "<div>";
        echo "<a href='view_post.php?image=$image&img_id=$img_id'><img src='images/$image'></a>";
       // echo "<BUTTON><"
        echo "
                             
        <button type='button' data-uid='$uid' data-image_id='$img_id' data-image='$image' data-from='from' onclick='delimage(this);'value='Delete' name='delete'>Delete</button>
                                
                          ";
        echo "</div>";
    }
}
catch (PDOException $ex)
{
    echo $ex;
}
?>