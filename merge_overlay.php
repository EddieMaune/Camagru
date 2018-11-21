<?php


    $image1 = $_GET['image1'];
    $image2 = $_GET['image2'];
    $final_image = rand().".png";

    list($width, $height) = getimagesize($image2);

    //file_get_contents returns file as string and imagecreatefromstring creates a new image from the image stream in the string
    $image1 = imagecreatefromstring(file_get_contents($image1));
    $image2 = imagecreatefromstring(file_get_contents($image2));

    //imagecopymerge(dest_image, src_image, dest_x, dest_y, src_x, src_y, src_width, src_height, pct) <- thats the prototype
    //imagecopymerge($image1, $image2, 0,0, 0, 0, $width , $height, 100);
    //imagecopyresampled is like image copy merge.
    imagecopyresampled($image1, $image2, 50, 50,0, 0, 50, 50, $width, $height);
    //header(content-type: image/png) and imagepng() outputs a png image to either the browser or a file.. in this case its a file.
    header("Content-type: image/png");
    imagepng($image1);
    echo "wtf";
    /*
    image_png($image1, images/$final_image);*/

?>