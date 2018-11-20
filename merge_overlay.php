<?php
    $image1 = 'image1.png';
    $image2 = 'image2.png';
    $final_image = rand();
    list($width, $height) = get_image_size($image_2);
    //file_get_contents returns file as string and imagecreatefromstring creates a new image from the image stream in the string
    $image1 = imagecreatefromstring(file_get_contents($image1));
    $image2 = imagecreatefromstring(file_get_contents($image2));
    //imagecopymerge(dest_image, src_image, dest_x, dest_y, src_x, src_y, src_width, src_height, pct) <- thats the prototype
    imagecopymerge($image1, $image2, 0,0, 0, 0, $width, $height, 100);
    //header(content-type: image/png) and imagepng() outputs a png image to either the browser or a file.. in this case its a file.
    image_png($image1, $final_image);

?>