//Global variables
let width = 500,
    height = 500,
    filter = 'none',
    streaming = false;
//DOM Element
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const photos = document.getElementById('photos');
const photoButton = document.getElementById('photo-button');
const clearButton = document.getElementById('clear-button');
const photoFilter = document.getElementById('photo-filter');

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
       // height = video.videoHeight / (video.videoWidth / width);
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

function temp(x)
{
    if (x.id == "emoji_1")
    {
        if (document.getElementById('overlay_1').hasAttribute('src')) {
            document.getElementById("overlay_1").style.display = "none";
            document.getElementById("overlay_1").removeAttribute('src');
        }
        else {
            document.getElementById("overlay_1").style.display = "block";
            document.getElementById("overlay_1").setAttribute('src', x.src);
        }
    }
    if (x.id == "emoji_2")
    {
        if (document.getElementById('overlay_2').hasAttribute('src')) {
            document.getElementById("overlay_2").style.display = "none";
            document.getElementById("overlay_2").removeAttribute('src');
        }
        else {
            document.getElementById("overlay_2").style.display = "block";
            document.getElementById("overlay_2").setAttribute('src', x.src);
        }
    }
    if (x.id == "emoji_3")
    {
        if (document.getElementById('overlay_3').hasAttribute('src')) {
            document.getElementById("overlay_3").style.display = "none";
            document.getElementById("overlay_3").removeAttribute('src');
        }
        else {
            document.getElementById("overlay_3").style.display = "block";
            document.getElementById("overlay_3").setAttribute('src', x.src);
        }
    }
    if (x.id == "emoji_4")
    {
        if (document.getElementById('overlay_4').hasAttribute('src')) {
            document.getElementById("overlay_4").style.display = "none";
            document.getElementById("overlay_4").removeAttribute('src');
        }
        else {
            document.getElementById("overlay_4").style.display = "block";
            document.getElementById("overlay_4").setAttribute('src', x.src);
        }
    }
}