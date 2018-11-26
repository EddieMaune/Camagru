<?PHP
    include_once ("authentication/session.php");
    include_once ("config/setup.php");

    if (!isset($_SESSION['id']))
    {
        $msg = "You need to be logged in to take pictures";
        header("Location: authentication/login.php?msg=$msg");
    }
?>

<!doctype html>
<html lang="en">
<head>
    <LINK rel="stylesheet" type="text/css" href="style/style.css">
    <LINK rel="stylesheet" type="text/css" href="style/w3.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capture</title>

</head>
<body>

    <div class="navbar">
        <h1>
            Camagru
        </h1>
        <A href="index.php">Home</A>
        <A href="upload.php">Upload</A>
        <A href="capture.php">Take/Edit Photo</A>
        <hr>
        <h2>
            Take a picture
        </h2>
    </div>
    <div class="w3-container w3-mobile w3-animate-bottom">
            <div class="overlay w3-mobile">
            <img class="w3-round w3-hover-sepia" id="overlay_1"  style="display: none;">
            <img id="overlay_2"  style="display: none;">
            <img id="overlay_3"  style="display: none;">
            <img id="overlay_4"  style="display: none;">
        </div>
            <video id="video" class="w3-mobile">
                Stream not available...
            </video>
            <button id="photo-button" class="w3-btn w3-gray  w3-mobile"> Snap</button>
           <!-- <button id="clear-button">Clear</button>-->
            <canvas id="canvas" class="w3-mobile"></canvas>
        <div class="w3-content w3-center w3-mobile" style="float: right;height:500px;overflow: scroll;" id="photos">

            <?PHP
            /*
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
                    echo "<img src='images/$image'>";

                    echo "
                             <FORM method='post' id='remove'>
                                   <input type='submit' onclick='delimage();'value='Delete' name='delete'>
                                   <input type='hidden' id='image' value='$image' name='image'>
                                   <input type='hidden' id='image_id' value='$img_id' name='image_id'>
                                   <input type='hidden' value='$uid' name='user_id'>
                                   <input type='hidden'  name='from'>
                             </FORM>";
                    echo "</div>";
                }
            }
            catch (PDOException $ex)
            {
                echo $ex;
            }*/
            ?>
        </div>
    </div>
    <div >
        <div>
        </div>
    </div>
    <div class="w3-container">
        <img class="w3-round w3-hover-sepia" id="emoji_1" src="images/overlays/smileyemoji.png" onclick="temp(this)">
        <img id="emoji_2" src="images/overlays/fireemoji.png" onclick="temp(this)">
        <img id="emoji_3" src="images/overlays/pooemoji.png" onclick="temp(this)">
        <img id="emoji_4" src="images/overlays/monkey.png" onclick="temp(this)">
    </div>
    <p id="output"></p>
    <script>
        var overlays = [];

        function temp(x)
        {
            if (x.id == "emoji_1")
            {
                if (document.getElementById('overlay_1').hasAttribute('src')) {
                    document.getElementById("overlay_1").style.display = "none";
                    document.getElementById("overlay_1").removeAttribute('src');
                    const i = overlays.indexOf('smileyemoji.png');
                    if (i > -1)
                    {
                        overlays.splice(i, 1);
                        console.log(overlays);
                    }
                }
                else {
                    document.getElementById("overlay_1").style.display = "block";
                    document.getElementById("overlay_1").setAttribute('src', x.src);
                    overlays.push('smileyemoji.png');
                    console.log(overlays);
                }
            }
            if (x.id == "emoji_2")
            {
                if (document.getElementById('overlay_2').hasAttribute('src')) {
                    document.getElementById("overlay_2").style.display = "none";
                    document.getElementById("overlay_2").removeAttribute('src');
                    const i = overlays.indexOf('fireemoji.png');
                    if (i > -1)
                    {
                        overlays.splice(i, 1);
                        console.log(overlays);
                    }
                }
                else {
                    document.getElementById("overlay_2").style.display = "block";
                    document.getElementById("overlay_2").setAttribute('src', x.src);
                    overlays.push('fireemoji.png');
                    console.log(overlays);
                }
            }
            if (x.id == "emoji_3")
            {
                if (document.getElementById('overlay_3').hasAttribute('src')) {
                    document.getElementById("overlay_3").style.display = "none";
                    document.getElementById("overlay_3").removeAttribute('src');
                    const i = overlays.indexOf('pooemoji.png');
                    if (i > -1)
                    {
                        overlays.splice(i, 1);
                        console.log(overlays);
                    }
                }
                else {
                    document.getElementById("overlay_3").style.display = "block";
                    document.getElementById("overlay_3").setAttribute('src', x.src);
                    overlays.push('pooemoji.png');
                    console.log(overlays);
                }
            }
            if (x.id == "emoji_4")
            {
                if (document.getElementById('overlay_4').hasAttribute('src')) {
                    document.getElementById("overlay_4").style.display = "none";
                    document.getElementById("overlay_4").removeAttribute('src');
                    const i = overlays.indexOf('monkey.png');
                    if (i > -1)
                    {
                        overlays.splice(i, 1);
                        console.log(overlays);
                    }
                }
                else {
                    document.getElementById("overlay_4").style.display = "block";
                    document.getElementById("overlay_4").setAttribute('src', x.src);
                    overlays.push('monkey.png');
                    console.log(overlays);
                }
            }
        }
        //Global variables
        let width = 500,
            height = 0,
            filter = 'none',
            streaming = false;
        //DOM Element
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photos = document.getElementById('photos');
        const photoButton = document.getElementById('photo-button');
        const clearButton = document.getElementById('clear-button');
        const smiley = document.getElementById('emoji_1');
        const fire = document.getElementById('emoji_2');
        const poo = document.getElementById('emoji_3');
        const monkey = document.getElementById('emoji_4');

        //Get media stream
        navigator.mediaDevices.getUserMedia({video: true, audio: false})
            .then(function (stream)
            {
                //Link to the video source
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err)
            {
                console.log(`Error: ${err}`);
            });

        //Play when ready
        video.addEventListener('canplay', function (e) {
            if (!streaming)
            {
                height = video.videoHeight / (video.videoWidth / width);
                console.log(height);
                video.setAttribute('width', width);
                video.setAttribute('height', height);
                canvas.setAttribute('width', width);
                canvas.setAttribute('height', height);
                streaming = true;
            }

        }, false);
        //photo button event
        photoButton.addEventListener('click', function(e)
        {
            takePicture();
           // load_image();
            e.preventDefault();
        }, false);
        //Take Picture from canvas
        function takePicture()
        {

            const context = canvas.getContext('2d');
            if (width && height)
            {
                var hr = new XMLHttpRequest();
                var phpurl = "store_image.php";
                //set canvas props
                canvas.width = width;
                canvas.height = height;
                //Draw an image of the video on canvas
                context.drawImage(video, 0, 0, width, height);

                //create image from the canvas
                const imgUrl = canvas.toDataURL('image/png');
                for (var i = 0; i < overlays.length; i++)
                {
                    if (overlays[i] == "smileyemoji.png")
                    {
                        context.drawImage(smiley, 0, 0, 50, 50);
                    }
                    if (overlays[i] == "fireemoji.png")
                    {
                        context.drawImage(fire, 450, 0, 50, 50);
                    }
                    if (overlays[i] == "pooemoji.png")
                    {
                        context.drawImage(poo, 0, 325, 50, 50);
                    }
                    if (overlays[i] == "monkey.png")
                    {
                        context.drawImage(monkey, 450, 325, 50, 50);
                    }
                }
                // ajax
                var post_vars = "image_url=" + imgUrl;
                for (var i = 0; i < overlays.length; i++)
                {
                    if (overlays[i] == "smileyemoji.png")
                    {
                       post_vars += "&" + "overlays[]=smileyemoji.png";
                    }
                    if (overlays[i] == "fireemoji.png")
                    {
                        post_vars += "&" + "overlays[]=fireemoji.png";
                    }
                    if (overlays[i] == "pooemoji.png")
                    {
                       post_vars += "&" + "overlays[]=pooemoji.png";
                    }
                    if (overlays[i] == "monkey.png")
                    {
                        post_vars += "&" + "overlays[]=monkey.png";
                    }
                }
                //console.log(post_vars);
                hr.open("POST", phpurl, true );
                hr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                hr.onreadystatechange = function () {
                    if (hr.readyState == 4 && hr.status == 200) {
                        var return_data = hr.responseText;
                        load_image();
                    }
               //     document.getElementById("output").innerHTML = return_data;
                }
                hr.send(post_vars);
               // load_image();
                //create img element
               // const div_a = document.createElement('div');
               // const img = document.createElement('img');
                //console.log(imgUrl);
                //Set img src
                /*alert
                img.setAttribute('src', imgUrl);
                img.setAttribute('height', 100);
                // add image to photos div
                div_a.appendChild(img);
                photos.appendChild(div_a);*/
                //location.reload();

            }
        }

        //clear Event
      /*  clearButton.addEventListener('click', function(e)
        {
            //clear images
            photos.innerHTML = "";
        });*/

      window.onload = function () {
          load_image();
       // document.getElementById('remove').addEventListener('submit', function(event){
         //   event.preventDefault();
           // }

        //})
          };

      function delimage(button) {
          /*alert(button.getAttribute("data-image_id"));
          alert(button.getAttribute("data-image"));
          alert(theinfo.getAttribute("data-from"));*/
          var hr = new XMLHttpRequest();
          var image_id = button.getAttribute('data-image_id');
          var image = button.getAttribute('data-image');
          var post_vars = "delete=1&image_id="+image_id + "&image=" + image + "&from=1";

          hr.open("POST", "delete.php", true );
          hr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
          hr.onreadystatechange = function () {
              if (hr.readyState == 4 && hr.status == 200) {
                  var return_data = hr.responseText;
                  //document.getElementById("output").innerHTML = return_data;
                  console.log(return_data);
              }
          }
          hr.send(post_vars);
          load_image();
      }

      function load_image() {
            var hr = new XMLHttpRequest();
           // var post_vars = "delete=1&image_id="+image_id + "&image=" + image + "&from=1";
           // console.log(image);

            hr.open("POST", "private_gallery.php", true);
            hr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            hr.onreadystatechange = function () {
                if (hr.readyState == 4 && hr.status == 200) {
                    var return_data = hr.responseText;
                    document.getElementById("photos").innerHTML = return_data;
                    //console.log(return_data);
                }
            }
            hr.send();
        }

    </script>
</body>
</html>