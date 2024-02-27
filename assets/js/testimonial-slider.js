jQuery(document).ready(function($) {
  // Initialize ProjectLink variables with data attributes for slides to show
  var ProjectLinkDesign1 = $(".slick-design-1").data("x");
  var ProjectLinkDesign2 = $(".slick-design-2").data("y");
  var ProjectLinkDesign3 = $(".slick-design-3").data("z");

  // Remove "http://" prefix if it exists
  ProjectLinkDesign1 = ProjectLinkDesign1 ? ProjectLinkDesign1.replace("http://", "") : 0;
  ProjectLinkDesign2 = ProjectLinkDesign2 ? ProjectLinkDesign2.replace("http://", "") : 0;
  ProjectLinkDesign3 = ProjectLinkDesign3 ? ProjectLinkDesign3.replace("http://", "") : 0;

   // autoplay
  var autopone = $(".slick-design-1").data("a");
  var autoptwo = $(".slick-design-2").data("b");
  var autopthree = $(".slick-design-3").data("c");

  //arrows 
  var arrowone = $(".slick-design-1").data("p");
  var arrowtwo = $(".slick-design-2").data("q");
  var arrowthree = $(".slick-design-3").data("r");

   //dots 
   var dotsone = $(".slick-design-1").data("m");
   var dotstwo = $(".slick-design-2").data("n");
   var dotsthree = $(".slick-design-3").data("o");

   //slide delay 
   var slidedelayone = $(".slick-design-1").data("e");
   var slidedelaytwo = $(".slick-design-2").data("f");
   var slidedelaythree = $(".slick-design-3").data("g");

  // Slick settings for Design 1
  $('.slick-design-1').slick({
      slidesToShow: ProjectLinkDesign1 || 0,
      slidesToScroll: 1,
      autoplay: autopone || 0,
      autoplaySpeed: slidedelayone,
      arrows: arrowone,
      dots: dotsone,
      responsive: [{
          breakpoint: 768,
          settings: {
              slidesToShow: 1,
              arrows: false,
          }
      }]
  });

  // Slick settings for Design 2
  $('.slick-design-2').slick({
      slidesToShow: ProjectLinkDesign2 || 0,
      slidesToScroll: 1,
      autoplay: autoptwo || 0,
      autoplaySpeed: slidedelaytwo,
      arrows: arrowtwo,
      dots: dotstwo,
      responsive: [{
          breakpoint: 768,
          settings: {
              slidesToShow: 1,
              arrows: false,
          }
      }]
  });

  // Slick settings for Design 3
  $('.slick-design-3').slick({
      slidesToShow: ProjectLinkDesign3 || 0,
      slidesToScroll: 1,
      autoplay: autopthree || 0,
      autoplaySpeed: slidedelaythree,
      arrows: arrowthree,
      dots: dotsthree,
      responsive: [{
          breakpoint: 768,
          settings: {
              slidesToShow: 1,
              arrows: false,
          }
      }]
  });
});
