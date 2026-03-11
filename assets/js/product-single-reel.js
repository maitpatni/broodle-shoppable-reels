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
videoClose.addEventListener('click', function () {
    video.pause(); // Pause the video
    document.querySelector('#draggableDiv').style.display = 'none'; // Hide the draggable div
});

// Reference to the modal and draggable div
var VideoModal = document.querySelector('#VideoModal');
var draggableDiv = document.querySelector('#draggableDiv');

// Handle click event on the draggable div
draggableDiv.querySelector('.videoBox').addEventListener('click', function () {
    VideoModal.style.display = 'block'; // Show the modal
    VideoModal.classList.add('show'); // Add 'show' class for modal
    const modalVideo = VideoModal.querySelector('.video'); // Get the video element inside the modal
    if (modalVideo) {
        modalVideo.play(); // Play the video inside the modal
    }
    draggableDiv.style.display = 'none'; // Hide the draggable div
});

// Handle volume toggle button
VideoModal.querySelector('.volume_btn').addEventListener('click', function () {
    const modalVideo = VideoModal.querySelector('.video'); // Get the video element inside the modal
    if (modalVideo) {
        modalVideo.muted = !modalVideo.muted; // Toggle mute state
        if (modalVideo.muted) {
            this.classList.add('muted'); // Add 'muted' class
        } else {
            this.classList.remove('muted'); // Remove 'muted' class
        }
    }
});

// Handle close button inside the modal
VideoModal.querySelector('.close-btn_mobile').addEventListener('click', function () {
    
    VideoModal.style.display = 'none'; // Hide the modal
    VideoModal.classList.remove('show'); // Remove 'show' class
    const modalVideo = VideoModal.querySelector('.video'); // Get the video element inside the modal
    if (modalVideo) {
        modalVideo.pause(); // Pause the video
        modalVideo.muted = false; // Unmute the video
    }

    draggableDiv.style.display = 'block'; // Show the draggable div
    video.play(); // Play the main video (optional)
});
