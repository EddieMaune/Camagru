<?php
include_once('authentication/session.php');
include_once('config/setup.php');

if (isset($_POST['like'])) {

    $img = $_POST['image'];
    $img_id = $_POST['image_id'];
    $likes = $_POST['likes'] + 1;
    $user_id = $_SESSION['id'];

    try {
        $sql = "SELECT * FROM likes WHERE user_id = $user_id AND image_id = $img_id";
        $statement = $connection->prepare($sql);
        $statement->execute();
        if ($statement->rowCount() == 0) {
            $sql = "INSERT INTO likes (user_id, image_id) VALUES (:user_id, :image_id)";
            $statement = $connection->prepare($sql);
            $statement->execute(array(':user_id'=>$user_id, ':image_id' => $img_id));
            $sql = "UPDATE images SET likes = :likes WHERE id = :img_id";
            $statement = $connection->prepare($sql);
            $statement->execute(array(':likes' => $likes, ':img_id' => $img_id));
        }
    }
    catch (PDOException $ex)
    {
        echo $ex;
    }
    header("Location: view_post.php?image=$img&img_id=$img_id");
}
?>