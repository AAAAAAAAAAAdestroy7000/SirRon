// DOUBLE BUFFERING VIDEO MANAGER
// Uses two video elements to crossfade SMOOTHLY before the first one ends.

var videoList = [];
var currentVideoIndex = 0;
var activeVideo = 1; // 1 or 2
var FADE_DURATION = 1500; // ms
var CROSSFADE_START_TIME = 2.0; // Seconds before end to start fading

var v1 = document.getElementById("bg-video-1");
var v2 = document.getElementById("bg-video-2");

function loadVideos() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../otherreqs/get_videos.php");
    xhr.onload = function () {
        if (xhr.status === 200) {
            videoList = JSON.parse(xhr.responseText);
            if (videoList.length > 0) {
                initPlayer();
            }
        }
    };
    xhr.send();
}

function initPlayer() {
    // Stage 1: Play first video in V1
    v1.src = "../videos/" + videoList[0];
    v1.style.opacity = 0;
    v1.play().then(() => {
        v1.style.opacity = 1;
        monitorProgress(v1); // Start monitoring
    }).catch(e => console.log("Autoplay blocked", e));

    currentVideoIndex = 0;
}

// Prepare next video logic
function getNextIndex() {
    var next = currentVideoIndex + 1;
    if (next >= videoList.length) next = 0;
    return next;
}

// Monitor the active video's time
function monitorProgress(videoElement) {

    // We bind a specific 'timeupdate' handler
    var handler = function () {
        if (!videoElement.duration) return;

        var timeLeft = videoElement.duration - videoElement.currentTime;

        // Trigger crossfade if we are near end AND not already fading
        if (timeLeft <= CROSSFADE_START_TIME && !videoElement.dataset.fading) {
            videoElement.dataset.fading = "true";
            performCrossfade();

            // cleanup listener
            videoElement.removeEventListener('timeupdate', handler);
        }
    };

    videoElement.addEventListener('timeupdate', handler);
    // Reset fading flag
    videoElement.dataset.fading = "";
}

function performCrossfade() {
    var nextIndex = getNextIndex();
    var nextSrc = "../videos/" + videoList[nextIndex];

    var currentVid = (activeVideo === 1) ? v1 : v2;
    var nextVid = (activeVideo === 1) ? v2 : v1;

    // Prep next video
    nextVid.src = nextSrc;
    nextVid.load(); // Force load

    // Play next video (it's currently opacity 0)
    nextVid.play().then(() => {

        // CSS Transition handles the fade
        currentVid.style.opacity = 0;
        nextVid.style.opacity = 1;

        // Update state
        currentVideoIndex = nextIndex;
        activeVideo = (activeVideo === 1) ? 2 : 1;

        // Start monitoring the NEW active video
        monitorProgress(nextVid);

    }).catch(e => console.log("Next video play error", e));
}

// Initial styles for opacity transition
v1.style.transition = "opacity 1.5s ease-in-out";
v2.style.transition = "opacity 1.5s ease-in-out";
v1.style.opacity = 0;
v2.style.opacity = 0;

// Start system
loadVideos();
