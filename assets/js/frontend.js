jQuery.noConflict();
!(function ($) {
  "use strict";

  /**
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */

  var BeFilterProductsOnCarousel = function ($scope, $) {
    $(".be-products-filter .filter-tab").click(function () {
      $(".be-products-filter .filter-tab").removeClass("active");
      $(this).addClass("active");

      let $settings = $(this).parent().data("settings");
      let $filter_by = $(this).data("filter-by");

      $.ajax({
        type: "POST",
        url: hch_objs.ajax_url,
        data: {
          action: "be_filter_products_on_carousel",
          settings: $settings,
          filter_by: $filter_by,
        },
        beforeSend: function (xhr) {
          $(".be-products-wrapper .preloader").addClass("show");
        },
        success: function (response) {
          $(".be-products-wrapper .preloader").removeClass("show");
          $(".be-products-ls").html(response);
        },
      });
    });
  };

  var beProductHover = function () {
    var product = jQuery(".be-slider.products .product");

    product.each(function (e) {
      var fadeBlock = jQuery(this).find(".product-fade-block");
      var contentBlock = jQuery(this).find(".product-content-fade");
      var outerHeight = 0;

      if (fadeBlock.length) {
        fadeBlock.each(function (e) {
          var self = jQuery(this);
          outerHeight += self.outerHeight();

          contentBlock.css("marginBottom", -outerHeight);
        });
      }
    });
  };

  /* Handler for Sliders */
  var BeProductsSliderHandler = function ($scope, $) {
    var container = $(".be-slider");

    container.each(function () {
      var self = $(this);

      var sliderItems = $(".slider-item");
      sliderItems.imagesLoaded(function () {
        self.closest(".slider-wrapper").addClass("slider-loaded");
      });

      var autoplay = $(this).data("autoplay");
      var autospeed = $(this).data("autospeed");
      var arrows = $(this).data("arrows");
      var dots = $(this).data("dots");
      var slidescroll = $(this).data("slidescroll");
      var slidespeed = $(this).data("slidespeed");
      var asnav = $(this).data("asnav");
      var focusselect = $(this).data("focusselect");
      var vertical = $(this).data("vertical");
      var mobileslide = 1;

      if ($(this).hasClass("products")) {
        var mobileslide = $(this).data("mobile");
      }

      self.not(".slick-initialized").slick({
        autoplay: autoplay,
        autoplaySpeed: autospeed,
        arrows: arrows,
        dots: dots,
        slidesToShow: 4,
        slidesToScroll: slidescroll,
        speed: slidespeed,
        asNavFor: asnav,
        focusOnSelect: focusselect,
        centerPadding: false,
        cssEase: "cubic-bezier(.48,0,.12,1)",
        vertical: vertical,
        responsive: [
          {
            breakpoint: 9999,
            settings: "unslick",
          },
          {
            breakpoint: 991,
            settings: {
              slidesToShow: mobileslide < 3 ? mobileslide : 2,
            },
          },
        ],
      });
    });

    // Rebuild the carousel after unslick
    $(window).resize(function () {
      $(".be-slider").not(".slick-initialized").slick("resize");
    });
    $(window).on("orientationchange", function () {
      $(".be-slider").not(".slick-initialized").slick("resize");
    });
  };

  var BeSliderHandler = function ($scope, $) {
    var container = $(".be-normal-slider");

    container.each(function () {
      var self = $(this);

      var sliderItems = $(".slider-item");
      sliderItems.imagesLoaded(function () {
        self.closest(".slider-wrapper").addClass("slider-loaded");
      });

      var autoplay = $(this).data("autoplay");
      var autospeed = $(this).data("autospeed");
      var arrows = $(this).data("arrows");
      var dots = $(this).data("dots");
      var slideshow = $(this).data("slideshow");
      var slidescroll = $(this).data("slidescroll");
      var slidespeed = $(this).data("slidespeed");
      var asnav = $(this).data("asnav");
      var focusselect = $(this).data("focusselect");
      var vertical = $(this).data("vertical");
      var mobileslide = $(this).data("mobile");

      self.not(".slick-initialized").slick({
        autoplay: autoplay,
        autoplaySpeed: autospeed,
        arrows: arrows,
        dots: dots,
        slidesToShow: slideshow,
        slidesToScroll: slidescroll,
        speed: slidespeed,
        asNavFor: asnav,
        focusOnSelect: focusselect,
        centerPadding: false,
        cssEase: "cubic-bezier(.48,0,.12,1)",
        vertical: vertical,
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: slideshow < 5 ? slideshow : 4,
            },
          },
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: slideshow < 4 ? slideshow : 3,
            },
          },
          {
            breakpoint: 991,
            settings: {
              slidesToShow: slideshow < 3 ? slideshow : 2,
            },
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: slideshow < 2 ? slideshow : mobileslide,
            },
          },
        ],
      });
    });
  };

  // Make sure you run this code under Elementor.
  $(window).on("elementor/frontend/init", function () {
    // Products Carousel 2.
    elementorFrontend.hooks.addAction("frontend/element_ready/products-carousel-2.default", beProductHover);
    elementorFrontend.hooks.addAction("frontend/element_ready/products-carousel-2.default", BeProductsSliderHandler);
    elementorFrontend.hooks.addAction("frontend/element_ready/products-carousel-2.default", BeFilterProductsOnCarousel);

    // Recipes Carousel
    elementorFrontend.hooks.addAction("frontend/element_ready/recipes-carousel.default", BeSliderHandler);
  });
})(jQuery);
