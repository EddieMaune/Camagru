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
            <video id="video">
                Stream not available...
            </video>
            <button id="photo-button" class="btn btn-dark"> Snap</button>
            <button id="clear-button">Clear</button>
            <canvas id="canvas"></canvas>
    </div>
    <div class="bottom-container">
        <div id="photos">
        </div>
    </div>
    <p id="output"></p>

    <script src="js/main.js"></script>
</body>
</html>