/* Revolution Slider
========================================================*/
$(document).ready(function() {
 jQuery('.tp-banner').show().revolution({
  dottedOverlay: "none",
  delay: 9000,
  startwidth: 1170,
  startheight: 540,
  hideThumbs: 200,
  thumbWidth: 100,
  thumbHeight: 50,
  thumbAmount: 5,
  navigationType: "bullet",
  navigationArrows: "solo",
  navigationStyle: "preview4",
  touchenabled: "on",
  onHoverStop: "on",
  swipe_velocity: 0.7,
  swipe_min_touches: 1,
  swipe_max_touches: 1,
  drag_block_vertical: false,
  parallax: "mouse",
  parallaxBgFreeze: "on",
  parallaxLevels: [7, 4, 3, 2, 5, 4, 3, 2, 1, 0],
  keyboardNavigation: "off",
  navigationHAlign: "center",
  navigationVAlign: "bottom",
  navigationHOffset: 0,
  navigationVOffset: 20,
  soloArrowLeftHalign: "left",
  soloArrowLeftValign: "center",
  soloArrowLeftHOffset: 20,
  soloArrowLeftVOffset: 0,
  soloArrowRightHalign: "right",
  soloArrowRightValign: "center",
  soloArrowRightHOffset: 20,
  soloArrowRightVOffset: 0,
  shadow: 0,
  fullWidth: "on",
  fullScreen: "off",
  spinner: "spinner1",
  stopLoop: "off",
  stopAfterLoops: -1,
  stopAtSlide: -1,
  shuffle: "off",
  autoHeight: "off",
  forceFullWidth: "off",
  hideThumbsOnMobile: "off",
  hideNavDelayOnMobile: 1500,
  hideBulletsOnMobile: "off",
  hideArrowsOnMobile: "off",
  hideThumbsUnderResolution: 0,
  hideSliderAtLimit: 0,
  hideCaptionAtLimit: 0,
  hideAllCaptionAtLilmit: 0,
  startWithSlide: 0,
  fullScreenOffsetContainer: ""
  });
});
/* Bootstrap Select
========================================================*/
jQuery(document).ready(function( $ ) {
$('.selectpicker').selectpicker({
  style: 'btn-select',
  size: 4
});
});

/* Slicknav Mobile Menu
========================================================*/
$(document).ready(function(){
  $('.wpb-mobile-menu').slicknav({
    prependTo: '.navbar-header',
    parentTag: 'liner',
    allowParentLinks: true,
    duplicate: true,
    label: '',
    closedSymbol: '<i class="fa fa-angle-right"></i>',
    openedSymbol: '<i class="fa fa-angle-down"></i>',
  });
});

/* Toggle
========================================================*/
(function ($) {
  var o = $('.toggle');
  $(document).ready(function () {
    $('.toggle').click(function (e) {
      e.preventDefault();
      var tmp = $(this);
      o.each(function () {
        if ($(this).hasClass('active') && !$(this).is(tmp)) {
          $(this).parent().find('.toggle_cont').slideToggle();
          $(this).removeClass('active');
        }
      });
      $(this).toggleClass('active');
      $(this).parent().find('.toggle_cont').slideToggle();
    });
    $(document).on('click touchstart', function (e) {
      var container = $(".toggle-wrap");
      if (!container.is(e.target) && container.has(e.target).length === 0 && container.find('.toggle').hasClass('active')) { 
        container.find('.active').toggleClass('active').parent().find('.toggle_cont').slideToggle();
      }
    });
  });
})(jQuery);

/* Nav Menu & Search
========================================================*/
$(".nav > li:has(ul)").addClass("drop");
$(".nav > li.drop > ul").addClass("dropdown");
$(".nav > li.drop > ul.dropdown ul").addClass("sup-dropdown");

/* CounterUp
========================================================*/
jQuery(document).ready(function( $ ) {
  $('.counter').counterUp({
    delay: 1,
    time: 1000
  });

/* Nivo Lightbox
========================================================*/
   $('.lightbox').nivoLightbox({
    effect: 'fadeScale',
    keyboardNav: true,
  });
   
});

/* Mixitup portfolio filter
========================================================*/
jQuery(function() {
  jQuery('#portfolio-list').mixItUp({
    animation: {
      duration: 800
    }
  });
});

/* WOW Scroll Spy
========================================================*/
var wow = new WOW({
    //disabled for mobile
    mobile: false
});
wow.init();

/*Page Loader
========================================================*/
$(window).load(function() {
  "use strict";
  $('#loader').fadeOut();
});

/* Latest Property Owl Carousel
========================================================*/
$("#latest-property").owlCarousel({
    navigation: true,
    pagination: false,
    slideSpeed: 1000,
    stopOnHover: true,
    autoPlay: true,
    items: 4,
    itemsDesktopSmall: [1024, 2],
    itemsTablet: [600, 1],
    itemsMobile: [479, 1]
});

/* Projects Carousel
========================================================*/   
$("#post-carousel").owlCarousel({
  navigation: false,
  pagination: true,
  slideSpeed: 1000,
  stopOnHover: true,
  autoPlay: true,
  items: 1,
  itemsDesktopSmall: [1024, 2],
  itemsTablet: [600, 1],
  itemsMobile: [479, 1]
});

/* Owl Carousel
========================================================*/
$('#clients-scroller').owlCarousel({
    navigation: false,
    pagination: false,
    items:4,
    itemsTablet:3,
    stagePadding:90,
    smartSpeed:450,
    itemsDesktop : [1199,4],
    itemsDesktopSmall : [980,3],
    itemsTablet: [768,3],
    itemsTablet: [767,2],
    itemsTabletSmall: [480,2],
    itemsMobile : [479,1],
});

/* Testimonials Carousel
========================================================*/
$(".testimonials-carousel").owlCarousel({
  navigation: false,
  pagination: true,
  slideSpeed: 1000,
  stopOnHover: true,
  autoPlay: true,
  items: 2,
  itemsDesktopSmall: [1024, 2],
  itemsTablet: [600, 1],
  itemsMobile: [479, 1]
});

/* Content Slider
========================================================*/
$(".content-slider").owlCarousel({
  navigation: false,
  pagination: true,
  slideSpeed: 1000,
  stopOnHover: true,
  autoPlay: true,
  items: 3,
  itemsDesktop: [1024, 1],
  itemsDesktopSmall: [768, 2],
  itemsTablet: [600, 1],
  itemsMobile: [479, 1]
});

/* Touch Owl Carousel
========================================================*/
$(".touch-slider").owlCarousel({
    navigation: true,
    pagination: false,
    slideSpeed: 1000,
    stopOnHover: true,
    autoPlay: true,
    items: 1,
    itemsDesktopSmall: [1024, 1],
    itemsTablet: [600, 1],
    itemsMobile: [479, 1]
});

/* Property Carousel
========================================================*/
$("#property-carousel").owlCarousel({
    navigation: true,
    pagination: false,
    slideSpeed: 1000,
    stopOnHover: true,
    autoPlay: true,
    items: 1,
    itemsDesktopSmall: [1024, 1],
    itemsTablet: [600, 1],
    itemsMobile: [479, 1]
});

$('.touch-slider').find('.owl-prev').html('<i class="fa fa-angle-left"></i>');
$('.touch-slider').find('.owl-next').html('<i class="fa fa-angle-right"></i>');

$('#latest-property').find('.owl-prev').html('<i class="fa fa-angle-left"></i>');
$('#latest-property').find('.owl-next').html('<i class="fa fa-angle-right"></i>');

$('#property-carousel').find('.owl-prev').html('<i class="fa fa-angle-left"></i>');
$('#property-carousel').find('.owl-next').html('<i class="fa fa-angle-right"></i>');

var owl;

$(document).ready(function () {

    owl = $("#owl-demo");

    owl.owlCarousel({

        navigation: false, // Show next and prev buttons
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        afterInit: afterOWLinit, // do some work after OWL init
        afterUpdate : afterOWLinit
    });

    function afterOWLinit() {
        // adding A to div.owl-page
        $('.owl-controls .owl-page').append('<a class="item-link" />');
        var pafinatorsLink = $('.owl-controls .item-link');
        /**
         * this.owl.userItems - it's your HTML <div class="item"><img src="http://www.ow...t of us"></div>
         */
        $.each(this.owl.userItems, function (i) {
          $(pafinatorsLink[i])
              // i - counter
              // Give some styles and set background image for pagination item
              .css({
                  'background': 'url(' + $(this).find('img').attr('src') + ') center center no-repeat',
                  '-webkit-background-size': 'cover',
                  '-moz-background-size': 'cover',
                  '-o-background-size': 'cover',
                  'background-size': 'cover'
              })
              // set Custom Event for pagination item
              .click(function () {
                  owl.trigger('owl.goTo', i);
              });

        });
         // add Custom PREV NEXT controls
        $('.owl-pagination').prepend('<a href="#prev" class="prev-owl"/>');
        $('.owl-pagination').append('<a href="#next" class="next-owl"/>');
        // set Custom event for NEXT custom control
        $(".next-owl").click(function () {
            owl.trigger('owl.next');
        });
        // set Custom event for PREV custom control
        $(".prev-owl").click(function () {
            owl.trigger('owl.prev');
        });
    }
});

/*Back Top Link
========================================================*/
var offset = 200;
var duration = 500;
$(window).scroll(function() {
  if ($(this).scrollTop() > offset) {
    $('.back-to-top').fadeIn(400);
  } else {
    $('.back-to-top').fadeOut(400);
  }
});
$('.back-to-top').click(function(event) {
  event.preventDefault();
  $('html, body').animate({
    scrollTop: 0
  }, 600);
  return false;
})

/* Property Grids
========================================================*/
$('.list,switchToGrid').click(function(e) {
  e.preventDefault();
  $('.grid').removeClass("active");
  $('.list').addClass("active");
  $('.item-list').addClass("make-list");
  $('.item-list').removeClass("make-grid");
  $('.item-list').removeClass("make-compact");
  $('.item-list .add-desc-box').removeClass("col-sm-9");
  $('.item-list .add-desc-box').addClass("col-sm-7");
});
$('.grid').click(function(e) {
  e.preventDefault();
  $('.list').removeClass("active");
  $(this).addClass("active");
  $('.item-list').addClass("make-grid");
  $('.item-list').removeClass("make-list");
  $('.item-list').removeClass("make-compact");
  $('.item-list .add-desc-box').removeClass("col-sm-9");
  $('.item-list .add-desc-box').addClass("col-sm-7");
});
