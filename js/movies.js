function resnav() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}


const urlParams = new URLSearchParams(window.location.search);
const videoLink = urlParams.get('video');
const videoPlayer = document.getElementById('movie-player');
const videoSource = document.getElementById('video-source');
const img = document.getElementById('info-poster');


switch(videoLink) {

    case 'mp4/FURY.mp4':
        document.getElementById('desc').innerHTML='In April 1945, as the Allies make their final push in the European Theater, a battle-hardened Army sergeant named Wardaddy commands a five-man Sherman tank crew on a deadly mission behind enemy lines.<br>Outnumbered, out-gunned, and with a rookie soldier thrust into their platoon, Wardaddy and his men face overwhelming odds in their heroic attempts to defend a field hospital from Waffen SS troops. ';
        document.getElementById('title').innerHTML = 'FURY';
        document.getElementById('rating').innerHTML = '8.7'
        img.src = './posters/fury-movie-poster-md.png';
        break;

    case 'mp4/annabelle.mp4':
        document.getElementById('desc').innerHTML='A couple begins to experience terrifying supernatural occurrences involving a vintage doll shortly after their home is invaded by satanic cultists.'
        document.getElementById('title').innerHTML = 'ANNABELLE';
        document.getElementById('rating').innerHTML = '5.4'
        img.src = './posters/annabelle-movie-poster.png';
        break;

    case 'mp4/avatar2.mp4':
        document.getElementById('desc').innerHTML='Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri and the army of the Na\'vi race to protect their home.'
        document.getElementById('title').innerHTML = 'Avatar The Way Of Water';
        document.getElementById('rating').innerHTML = '7.6'
        img.src = './posters/l_avatar-the-way-of-water-movie-poster.jpg';
        break;

}

videoSource.src = videoLink;
videoPlayer.play();
