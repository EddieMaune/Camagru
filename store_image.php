<?php
    include_once("config/setup.php");
    include_once("authentication/session.php");
    if (isset($_POST['image_url']) && isset($_SESSION['id']))
    {
        //$image_url = $_POST['image_url'];
        $image = rand().".png";
        $image_path = "images/".$image;
        $img_url = str_replace("data:image/png;base64,", "", $_POST['image_url']);
        $formatted_image_url = str_replace(" ", "+", $img_url);
        $decoded = base64_decode($formatted_image_url);
        file_put_contents($image_path, $decoded);
        try{
            $sql = "INSERT INTO images (image, likes, user_id, date_created) VALUES (:image, :likes, :user_id, NOW())";
            $statement = $connection->prepare($sql);
            $statement->execute(array(":image" => $image, ":likes"=>0, ":user_id" => $_SESSION['id']));

        }
        catch(PDOException $ex)
        {
            echo $ex;
        }
        echo "Success";
    }
    ?>