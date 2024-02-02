function resnav() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

const urlParams = new URLSearchParams(window.location.search);
if(urlParams.get('video')){
    const img = document.getElementById('info-poster');

    const videoLink = urlParams.get('video');
    const videoPlayer = document.getElementById('movie-player');
    const videoSource = document.getElementById('video-source');

    videoSource.src = videoLink;
    videoPlayer.play();
}



