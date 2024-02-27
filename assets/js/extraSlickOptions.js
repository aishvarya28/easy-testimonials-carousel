(function($) {
  'use strict';

  //create temporal object to get slick object
  var getSlick = function() {
    var $tmp = $('<div>').slick();
    var slick = $tmp[0].slick.constructor;
    $tmp.slick('unslick');
    return slick;
  };

  if ($.fn.slick) {
    var Slick = getSlick();
    if (Slick) {
      //hook checkResponsive method
      var checkResponsiveOrig = Slick.prototype.checkResponsive;
      Slick.prototype.checkResponsive = function(initial, forceUpdate) {
        var _ = this;
        if (_.options.autoSlidesToShow && !_.options.infinite && _.options.variableWidth) {
          var sliderWidth = _.$slider.width();
          var width = 0, length = _.$slides.length;
          for (var i = 0; i < length; i++) {
            width += $(_.$slides[i]).outerWidth();
          }
          _.averageSlidesWidth = width / length;
          _.options.slidesToShow = Math.floor(sliderWidth / _.averageSlidesWidth) || 1;
          //force update arrows
          if (_.lastSlidesToShow !== _.options.slidesToShow) {
            _.lastSlidesToShow = _.options.slidesToShow;
            if (initial === true) {
              _.currentSlide = _.options.initialSlide;
            }
            _.refresh(initial);
          }
        }
        return checkResponsiveOrig.apply(this, arguments);
      };
      //hook getLeft method
      var getLeftOrig = Slick.prototype.getLeft;
      Slick.prototype.getLeft = function(slideIndex) {
        var _ = this;
        if (_.options.autoSlidesToShow && !_.options.infinite && _.options.variableWidth) {
          var targetSlide = _.$slideTrack.children('.slick-slide').eq(slideIndex);
          if (targetSlide[0]) {
            var diff = 0;
            if (slideIndex) {
              var sliderWidth = _.$slider.width();
              var otherSlidesWidth = (_.slideCount - slideIndex) * _.averageSlidesWidth;
              if (otherSlidesWidth < sliderWidth) {
                diff = sliderWidth - otherSlidesWidth;
              }
            }
            return (targetSlide[0].offsetLeft - diff) * -1;
          }
          return  0;
        }
        return getLeftOrig.apply(this, arguments);
      };
    }
  }
})(jQuery);