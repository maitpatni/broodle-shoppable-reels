// // Reference to the video element
// var video = document.getElementById('pdp_video');

// // Reference to the pause/play button
// var pausePlayButton = document.querySelector('.pause_play');

// // Add event listener to the button
// pausePlayButton.addEventListener('click', function() {
//     if (video.paused) {
//         video.play(); // Play the video
//         // Show the "pause" icon and hide the "play" icon
//         this.querySelector('.pause').classList.remove('hide');
//         this.querySelector('.play').classList.add('hide');
//     } else {
//         video.pause(); // Pause the video
//         // Show the "play" icon and hide the "pause" icon
//         this.querySelector('.play').classList.remove('hide');
//         this.querySelector('.pause').classList.add('hide');
//     }
// });

// var videoClose = document.querySelector('.video-close');
// videoClose.addEventListener('click', function() {
//     var video = document.getElementById('pdp_video');
//     video.pause(); // Pause the video
//     document.querySelector('#draggableDiv').style.display = 'none'; // Hide the div
// });

// var VideoModal = document.querySelector('#VideoModal');
// var draggableDiv = document.querySelector('#draggableDiv');
// draggableDiv.addEventListener('click',function(){
//     VideoModal.style.display = 'block';
//     VideoModal.classList.add('show');
//     VideoModal.querySelector('.video').play();
//     this.style.display = 'none';
// })
// VideoModal.querySelector('.volume_btn').addEventListener('click', function() {
//     const video = VideoModal.querySelector('.video'); // Get the video element
//     video.muted = !video.muted; // Toggle the muted state
//     if (video.muted) {
//         this.classList.add('muted'); // Add the 'muted' class
//     } else {
//         this.classList.remove('muted'); // Remove the 'muted' class
//     }
// });

// VideoModal.querySelector('.close-btn_mobile').addEventListener('click', function() {
//     var video = document.getElementById('pdp_video');
//     VideoModal.style.display = 'none';
//     VideoModal.classList.remove('show');
//     const video = VideoModal.querySelector('.video'); // Get the video element
//     draggableDiv.style.display = 'block';
//     video.play();
//     if (VideoModal.querySelector('.video')) {
//         VideoModal.querySelector('.video').muted = false; // Unmute the video
//         VideoModal.querySelector('.video').pause(); // Pause the video (optional, depending on your needs)
//     } 
// });

// Reference to the video element
var video = document.getElementById('pdp_video');

// Reference to the pause/play button
var pausePlayButton = document.querySelector('.pause_play');

// Add event listener to the button only if elements exist
if (pausePlayButton && video) {
    pausePlayButton.addEventListener('click', function () {
        if (video.paused) {
            video.play(); // Play the video
            // Show the "pause" icon and hide the "play" icon
            var pauseIcon = this.querySelector('.pause');
            var playIcon = this.querySelector('.play');
            if (pauseIcon) pauseIcon.classList.remove('hide');
            if (playIcon) playIcon.classList.add('hide');
        } else {
            video.pause(); // Pause the video
            // Show the "play" icon and hide the "pause" icon
            var pauseIcon = this.querySelector('.pause');
            var playIcon = this.querySelector('.play');
            if (playIcon) playIcon.classList.remove('hide');
            if (pauseIcon) pauseIcon.classList.add('hide');
        }
    });
}

// Reference to the video close button
var videoClose = document.querySelector('.video-close');
if (videoClose) {
    videoClose.addEventListener('click', function () {
        if (video) video.pause();
        var dd = document.querySelector('#draggableDiv');
        if (dd) dd.style.display = 'none';
    });
}

// Reference to the modal and draggable div
var VideoModal = document.querySelector('#VideoModal');
var draggableDiv = document.querySelector('#draggableDiv');

if (draggableDiv && VideoModal) {
    // Handle click event on the draggable div
    var videoBox = draggableDiv.querySelector('.videoBox');
    if (videoBox) {
        videoBox.addEventListener('click', function () {
            VideoModal.style.display = 'block';
            VideoModal.classList.add('show');
            var modalVideo = VideoModal.querySelector('.video');
            if (modalVideo) modalVideo.play();
            draggableDiv.style.display = 'none';
        });
    }

    // Handle volume toggle button
    var volumeBtn = VideoModal.querySelector('.volume_btn');
    if (volumeBtn) {
        volumeBtn.addEventListener('click', function () {
            var modalVideo = VideoModal.querySelector('.video');
            if (modalVideo) {
                modalVideo.muted = !modalVideo.muted;
                if (modalVideo.muted) {
                    this.classList.add('muted');
                } else {
                    this.classList.remove('muted');
                }
            }
        });
    }

    // Handle close button inside the modal
    var closeBtn = VideoModal.querySelector('.close-btn_mobile');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            VideoModal.style.display = 'none';
            VideoModal.classList.remove('show');
            var modalVideo = VideoModal.querySelector('.video');
            if (modalVideo) {
                modalVideo.pause();
                modalVideo.muted = false;
            }
            draggableDiv.style.display = 'block';
            if (video) video.play();
        });
    }
}
