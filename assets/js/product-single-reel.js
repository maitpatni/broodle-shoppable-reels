/**
 * Broodle Shoppable Reels — Product Page Floating Video Player
 * Compatible with Bootstrap 5.x
 *
 * Rules:
 * - Floating video (#pdp_video) is ALWAYS muted
 * - Popup video (#VideoModal .popup_video) unmutes when opened, mutes when closed
 * - Both modals (VideoModal + productDetail_modal) close on Escape key
 */
(function() {
    'use strict';

    function init() {
        var video = document.getElementById('pdp_video');
        var pausePlayButton = document.querySelector('#draggableDiv .pause_play');
        var videoClose = document.querySelector('#draggableDiv .video-close');
        var VideoModal = document.querySelector('#VideoModal');
        var draggableDiv = document.querySelector('#draggableDiv');

        // ─── Force floating video to always be muted ───
        if (video) {
            video.muted = true;
            video.volume = 0;
            // Prevent any unmuting of the floating video
            video.addEventListener('volumechange', function() {
                if (!this.muted || this.volume > 0) {
                    this.muted = true;
                    this.volume = 0;
                }
            });
            // Ensure autoplay works (muted autoplay is always allowed)
            video.play().catch(function() {});
        }

        // ─── Pause/Play Button (floating video) ───
        if (pausePlayButton && video) {
            pausePlayButton.addEventListener('click', function(e) {
                e.stopPropagation();
                if (video.paused) {
                    video.muted = true;
                    video.play().catch(function() {});
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
            videoClose.addEventListener('click', function(e) {
                e.stopPropagation();
                if (video) video.pause();
                if (draggableDiv) draggableDiv.style.display = 'none';
            });
        }

        // ─── Click Floating Video → Open Popup ───
        if (draggableDiv && VideoModal) {
            var videoBox = draggableDiv.querySelector('.videoBox');
            if (videoBox) {
                videoBox.addEventListener('click', function() {
                    // Hide floating player
                    draggableDiv.style.display = 'none';
                    if (video) video.pause();

                    // Show modal using Bootstrap 5
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var bsModal = bootstrap.Modal.getInstance(VideoModal);
                        if (!bsModal) {
                            bsModal = new bootstrap.Modal(VideoModal, {
                                backdrop: true,
                                keyboard: true
                            });
                        }
                        bsModal.show();
                    } else {
                        VideoModal.style.display = 'block';
                        VideoModal.classList.add('show');
                        document.body.classList.add('modal-open');
                    }

                    // Play popup video with audio
                    var modalVideo = VideoModal.querySelector('.popup_video');
                    if (modalVideo) {
                        modalVideo.muted = false;
                        modalVideo.currentTime = 0;
                        modalVideo.play().catch(function() {
                            // If unmuted autoplay blocked, play muted first
                            modalVideo.muted = true;
                            modalVideo.play().catch(function() {});
                        });
                    }
                });
            }

            // ─── Volume Toggle in Popup ───
            var volumeBtn = VideoModal.querySelector('.volume_btn');
            if (volumeBtn) {
                volumeBtn.addEventListener('click', function() {
                    var modalVideo = VideoModal.querySelector('.popup_video');
                    if (modalVideo) {
                        modalVideo.muted = !modalVideo.muted;
                        this.classList.toggle('muted', modalVideo.muted);
                    }
                });
            }

            // ─── Close Popup → Return to Floating Video ───
            var closeBtn = VideoModal.querySelector('.close-btn_mobile');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeVideoModal();
                });
            }

            // ─── Escape Key to Close Popup ───
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && VideoModal.classList.contains('show')) {
                    closeVideoModal();
                }
            });

            // ─── Bootstrap 5 hidden event ───
            VideoModal.addEventListener('hidden.bs.modal', function() {
                var modalVideo = VideoModal.querySelector('.popup_video');
                if (modalVideo) {
                    modalVideo.pause();
                    modalVideo.muted = true;
                }
                // Show floating player again
                if (draggableDiv) {
                    draggableDiv.style.display = 'block';
                    if (video) {
                        video.muted = true;
                        video.play().catch(function() {});
                    }
                }
            });

            function closeVideoModal() {
                // Mute and pause popup video
                var modalVideo = VideoModal.querySelector('.popup_video');
                if (modalVideo) {
                    modalVideo.pause();
                    modalVideo.muted = true;
                }

                // Hide modal
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var bsModal = bootstrap.Modal.getInstance(VideoModal);
                    if (bsModal) {
                        bsModal.hide();
                        return; // hidden.bs.modal event will handle the rest
                    }
                }
                // Fallback
                VideoModal.style.display = 'none';
                VideoModal.classList.remove('show');
                document.body.classList.remove('modal-open');
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();

                // Show floating player
                if (draggableDiv) {
                    draggableDiv.style.display = 'block';
                    if (video) {
                        video.muted = true;
                        video.play().catch(function() {});
                    }
                }
            }
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
