function resnav() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}



const img = document.getElementById('info-poster');

    const urlParams = new URLSearchParams(window.location.search);
    const videoLink = urlParams.get('video');
    console.log(videoLink);
    const videoPlayer = document.getElementById('movie-player');
    const videoSource = document.getElementById('video-source');

    videoSource.src = videoLink;
    videoPlayer.play();


