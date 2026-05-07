/**
 * Broodle Shoppable Reels — Product Page Floating Video Player
 * Compatible with Bootstrap 5.x
 */
(function() {
    'use strict';

    // Wait for DOM ready
    function init() {
        var video = document.getElementById('pdp_video');
        var pausePlayButton = document.querySelector('.pause_play');
        var videoClose = document.querySelector('.video-close');
        var VideoModal = document.querySelector('#VideoModal');
        var draggableDiv = document.querySelector('#draggableDiv');

        // ─── Pause/Play Button ───
        if (pausePlayButton && video) {
            pausePlayButton.addEventListener('click', function (e) {
                e.stopPropagation();
                if (video.paused) {
                    video.play();
                    var pauseIcon = this.querySelector('.pause');
                    var playIcon = this.querySelector('.play');
                    if (pauseIcon) pauseIcon.classList.remove('hide');
                    if (playIcon) playIcon.classList.add('hide');
                } else {
                    video.pause();
                    var pauseIcon2 = this.querySelector('.pause');
                    var playIcon2 = this.querySelector('.play');
                    if (playIcon2) playIcon2.classList.remove('hide');
                    if (pauseIcon2) pauseIcon2.classList.add('hide');
                }
            });
        }

        // ─── Close Floating Video ───
        if (videoClose) {
            videoClose.addEventListener('click', function (e) {
                e.stopPropagation();
                if (video) video.pause();
                if (draggableDiv) draggableDiv.style.display = 'none';
            });
        }

        // ─── Click Floating Video → Open Full Modal ───
        if (draggableDiv && VideoModal) {
            var videoBox = draggableDiv.querySelector('.videoBox');
            if (videoBox) {
                videoBox.addEventListener('click', function () {
                    // Use Bootstrap 5 API to show modal (with safety check)
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var bsModal = bootstrap.Modal.getInstance(VideoModal);
                        if (!bsModal) {
                            bsModal = new bootstrap.Modal(VideoModal, { backdrop: 'static', keyboard: false });
                        }
                        bsModal.show();
                    } else {
                        VideoModal.style.display = 'block';
                        VideoModal.classList.add('show');
                    }

                    var modalVideo = VideoModal.querySelector('.video');
                    if (modalVideo) {
                        modalVideo.muted = false;
                        modalVideo.play().catch(function() {
                            // Autoplay blocked, try muted
                            modalVideo.muted = true;
                            modalVideo.play().catch(function() {});
                        });
                    }
                    draggableDiv.style.display = 'none';
                });
            }

            // ─── Volume Toggle ───
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

            // ─── Close Full Modal → Show Floating Video Again ───
            var closeBtn = VideoModal.querySelector('.close-btn_mobile');
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    // Use Bootstrap 5 API to hide modal (with safety check)
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var bsModal = bootstrap.Modal.getInstance(VideoModal);
                        if (bsModal) {
                            bsModal.hide();
                        }
                    } else {
                        VideoModal.style.display = 'none';
                        VideoModal.classList.remove('show');
                    }

                    var modalVideo = VideoModal.querySelector('.video');
                    if (modalVideo) {
                        modalVideo.pause();
                        modalVideo.muted = true;
                    }

                    // Show floating player again and resume
                    draggableDiv.style.display = 'block';
                    if (video) {
                        video.muted = true;
                        video.play().catch(function() {});
                    }
                });
            }

            // ─── Handle Bootstrap 5 hidden event to clean up ───
            VideoModal.addEventListener('hidden.bs.modal', function() {
                var modalVideo = VideoModal.querySelector('.video');
                if (modalVideo) {
                    modalVideo.pause();
                }
            });
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
