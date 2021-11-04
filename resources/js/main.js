jQuery(document).ready(function ($) {
  //FIXED HEADER
  window.onscroll = function () {
    if (window.pageYOffset > 100) {
      $("#header").addClass("active");
    } else {
      $("#header").removeClass("active");
    }
  };
});
