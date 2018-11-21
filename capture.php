<?PHP
    include_once ("authentication/session.php");

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
    <div class="top-container">
        <div class="overlay">
            <img id="overlay_1"  style="display: none;">
            <img id="overlay_2"  style="display: none;">
            <img id="overlay_3"  style="display: none;">
            <img id="overlay_4"  style="display: none;">
        </div>
            <video id="video">
                Stream not available...
            </video>
            <button id="photo-button" class="btn btn-dark"> Snap</button>
            <button id="clear-button">Clear</button>
            <canvas id="canvas"></canvas>
    </div>
    <div >
        <div id="photos">
        </div>
    </div>
    <div class="bottom-container">
        <img  id="emoji_1" src="images/overlays/smileyemoji.png" onclick="temp(this)">
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
                //create image from the canvas
                const imgUrl = canvas.toDataURL('image/png');
                // ajax
                var post_vars = "image_url=" + imgUrl;
                hr.open("POST", phpurl, true );
                hr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                hr.onreadystatechange = function () {
                    if (hr.readyState == 4 && hr.status == 200)
                        var return_data = hr.responseText;
                    document.getElementById("output").innerHTML = return_data;
                }
                hr.send(post_vars);
                //create img element
                const img = document.createElement('img');
                console.log(imgUrl);
                //Set img src
                img.setAttribute('src', imgUrl);
                img.setAttribute('height', 100);
                // add image to photos div
                photos.appendChild(img);
            }
        }

        //clear Event
        clearButton.addEventListener('click', function(e)
        {
            //clear images
            photos.innerHTML = "";
        });


    </script>
</body>
</html>