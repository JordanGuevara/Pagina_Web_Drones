const video = document.querySelector('video');

video.addEventListener('loadedmetadata', function() {
  video.currentTime = 62;
});

video.addEventListener('timeupdate', function() {
  if (video.currentTime >= 88) { 
    video.currentTime = 62; 
  }
});

video.play(); 