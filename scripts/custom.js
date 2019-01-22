$(document).ready(function() {
//zorgt dat de link in webapp niet opent in safari
  $("a").click(function (event) {
      event.preventDefault();
      window.location = $(this).attr("href");
  });
});
