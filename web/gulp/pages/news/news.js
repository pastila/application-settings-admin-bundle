$(document).ready(function() {
  if ($('.b-interesting__slider').length) {
    var sliderInteresting = tns({
      container: '.b-interesting__slider',
      items: 3.3,
      loop: false,
      mouseDrag: true,
      speed: 400,
      nav: false,
      controls: false,
      gutter: 32,
      cloneCount: 0,
    });
  }
});