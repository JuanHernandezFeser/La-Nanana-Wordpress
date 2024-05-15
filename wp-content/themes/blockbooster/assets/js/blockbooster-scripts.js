(function ($) {
  "use strict";
  //Loading AOS animation with css class

  //fade animation
  $(".blockbooster-fade-up").attr({
    "data-aos": "fade-up",
  });
  $(".blockbooster-fade-down").attr({
    "data-aos": "fade-down",
  });
  $(".blockbooster-fade-left").attr({
    "data-aos": "fade-left",
  });
  $(".blockbooster-fade-right").attr({
    "data-aos": "fade-right",
  });
  $(".blockbooster-fade-up-right").attr({
    "data-aos": "fade-up-right",
  });
  $(".blockbooster-fade-up-left").attr({
    "data-aos": "fade-up-left",
  });
  $(".blockbooster-fade-down-right").attr({
    "data-aos": "fade-down-right",
  });
  $(".blockbooster-fade-down-left").attr({
    "data-aos": "fade-down-left",
  });

  //slide animation
  $(".blockbooster-slide-left").attr({
    "data-aos": "slide-left",
  });
  $(".blockbooster-slide-right").attr({
    "data-aos": "slide-right",
  });
  $(".blockbooster-slide-up").attr({
    "data-aos": "slide-up",
  });
  $(".blockbooster-slide-down").attr({
    "data-aos": "slide-down",
  });

  //zoom animation
  $(".blockbooster-zoom-in").attr({
    "data-aos": "zoom-in",
  });
  $(".blockbooster-zoom-in-up").attr({
    "data-aos": "zoom-in-up",
  });
  $(".blockbooster-zoom-in-down").attr({
    "data-aos": "zoom-in-down",
  });
  $(".blockbooster-zoom-in-left").attr({
    "data-aos": "zoom-in-left",
  });
  $(".blockbooster-zoom-in-right").attr({
    "data-aos": "zoom-in-right",
  });
  $(".blockbooster-zoom-out").attr({
    "data-aos": "zoom-out",
  });
  $(".blockbooster-zoom-out-up").attr({
    "data-aos": "zoom-out-up",
  });
  $(".blockbooster-zoom-out-down").attr({
    "data-aos": "zoom-out-down",
  });
  $(".blockbooster-zoom-out-left").attr({
    "data-aos": "zoom-out-left",
  });
  $(".blockbooster-zoom-out-right").attr({
    "data-aos": "zoom-out-right",
  });

  //flip animation
  $(".blockbooster-flip-up").attr({
    "data-aos": "flip-up",
  });
  $(".blockbooster-flip-down").attr({
    "data-aos": "flip-down",
  });
  $(".blockbooster-flip-left").attr({
    "data-aos": "flip-left",
  });
  $(".blockbooster-flip-right").attr({
    "data-aos": "flip-right",
  });

  //animation ease attributes
  $(".blockbooster-linear").attr({
    "data-aos-easing": "linear",
  });
  $(".blockbooster-ease").attr({
    "data-aos-easing": "ease",
  });
  $(".blockbooster-ease-in").attr({
    "data-aos-easing": "ease-in",
  });
  $(".blockbooster-ease-in-back").attr({
    "data-aos-easing": "ease-in-back",
  });
  $(".blockbooster-ease-out").attr({
    "data-aos-easing": "ease-out",
  });
  $(".blockbooster-ease-out-back").attr({
    "data-aos-easing": "ease-out-back",
  });
  $(".blockbooster-ease-in-out-back").attr({
    "data-aos-easing": "ease-in-out-back",
  });
  $(".blockbooster-ease-in-shine").attr({
    "data-aos-easing": "ease-in-shine",
  });
  $(".blockbooster-ease-out-shine").attr({
    "data-aos-easing": "ease-out-shine",
  });
  $(".blockbooster-ease-in-out-shine").attr({
    "data-aos-easing": "ease-in-out-shine",
  });
  $(".blockbooster-ease-in-quad").attr({
    "data-aos-easing": "ease-in-quad",
  });
  $(".blockbooster-ease-out-quad").attr({
    "data-aos-easing": "ease-out-quad",
  });
  $(".blockbooster-ease-in-out-quad").attr({
    "data-aos-easing": "ease-in-out-quad",
  });
  $(".blockbooster-ease-in-cubic").attr({
    "data-aos-easing": "ease-in-cubic",
  });
  $(".blockbooster-ease-out-cubic").attr({
    "data-aos-easing": "ease-out-cubic",
  });
  $(".blockbooster-ease-in-out-cubic").attr({
    "data-aos-easing": "ease-in-out-cubic",
  });
  $(".blockbooster-ease-in-quart").attr({
    "data-aos-easing": "ease-in-quart",
  });
  $(".blockbooster-ease-out-quart").attr({
    "data-aos-easing": "ease-out-quart",
  });
  $(".blockbooster-ease-in-out-quart").attr({
    "data-aos-easing": "ease-in-out-quart",
  });

  setTimeout(function () {
    AOS.init({
      once: true,
      duration: 1200,
    });
  }, 100);

  $(window).scroll(function () {
    var scrollTop = $(this).scrollTop();
    var blockboosterStickyMenu = $(".blockbooster-sticky-menu");
    var blockboosterStickyNavigation = $(".blockbooster-sticky-navigation");

    if (blockboosterStickyMenu.length && scrollTop > 0) {
      blockboosterStickyMenu.addClass("sticky-menu-enabled blockbooster-zoom-in-up");
    } else {
      blockboosterStickyMenu.removeClass("sticky-menu-enabled");
    }
  });
  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 100) {
      jQuery(".blockbooster-scrollto-top a").fadeIn();
    } else {
      jQuery(".blockbooster-scrollto-top a").fadeOut();
    }
  });
  jQuery(".blockbooster-scrollto-top a").click(function () {
    jQuery("html, body").animate({ scrollTop: 0 }, 600);
    return false;
  });
})(jQuery);
