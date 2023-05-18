

$(document).ready(function () {

   var slide = $(".slide");
   var scrollAmount = slide.children().first().width();
   slide = slide.get(0);

   $(".img-svg1").click(function () {
      slide.scrollBy(-scrollAmount, 0);
   });

   $(".img-svg2").click(function () {
      slide.scrollBy(scrollAmount, 0);
   });

});