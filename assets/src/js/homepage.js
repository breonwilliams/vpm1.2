// var $j = jQuery.noConflict();
// jQuery(function($) {
//   $(function() {
//     $.scrollify({
//       section: '.screen-panel',
//       scrollbars: true,
//       offset: 5, // wtf
//       standardScrollElements: '.vp-standard-scroll',
//       interstitialSection: '.site-header,.site-footer',
//       before: function(i, panels) {
//         var ref = panels[i].attr('data-section-name');

//         $('.pagination a.active').removeClass('active');
//         $('.pagination a[href=#' + ref + ']').addClass('active');

//         $('.' + ref + ' .content').addClass('moved');
//       },
//       after: function(i, panels) {
//         var ref = panels[i].attr('data-section-name');

//         for (var j = 0; j < panels.length; j++) {
//           if (j > i) {
//             panels[j].find('.moved').removeClass('moved');
//           }
//         }
//       },
//       afterResize: initialPosition,
//       afterRender: initialPosition
//     });

//     $('.pagination a').on('click', function() {
//       $.scrollify.move($(this).attr('href'));
//     });

//     function initialPosition() {
//       var current = $.scrollify.current();
//     }
//   });
// });

// fullpage.js settings
const myFullpage = new fullpage('#fullpage', {
  // License key
  licenseKey: '561A79A4-671B43EC-93B9AFAC-780364CA',

  //Navigation
  menu: '#fp-nav',
  lockAnchors: false,
  anchors: [
    'home',
    'cu-boulder-emp',
    'northern-virginia-community-college',
    'unc-system-nc-affordable-education',
    'meet-the-team',
    'visionpoint-culture',
    'eduInsights',
    'ready-to-work-with-us'
  ],
  navigation: false,
  navigationPosition: 'right',
  navigationTooltips: [
    'home',
    'cu-boulder-emp',
    'northern-virginia-community-college',
    'unc-system-nc-affordable-education',
    'meet-the-team',
    'visionpoint-culture',
    'eduInsights',
    'ready-to-work-with-us'
  ],
  showActiveTooltip: false,
  slidesNavigation: false,

  //Scrolling
  css3: true,
  scrollingSpeed: 700,
  autoScrolling: true,
  fitToSection: true,
  fitToSectionDelay: 1000,
  scrollBar: true,
  easing: 'easeInOutCubic',
  easingcss3: 'ease',
  loopBottom: false,
  loopTop: false,
  loopHorizontal: true,
  continuousVertical: false,
  continuousHorizontal: false,
  scrollHorizontally: false,
  interlockedSlides: false,
  dragAndMove: false,
  offsetSections: true,
  resetSliders: false,
  fadingEffect: true,
  normalScrollElements: '.vp-standard-scroll',
  scrollOverflow: false,
  scrollOverflowReset: false,
  // scrollOverflowOptions: null,
  touchSensitivity: 15,
  // bigSectionsDestination: null,

  //Accessibility
  keyboardScrolling: true,
  animateAnchor: true,
  recordHistory: true,

  //Design
  controlArrows: true,
  verticalCentered: true,
  sectionsColor: [
    '#1c2855',
    '#363636',
    '#ffffff',
    '#363636',
    '#ffffff',
    '#ffffff',
    '#f7f7f7',
    '#282828'
  ],
  paddingTop: '',
  paddingBottom: '89px',
  // fixedElements: '.site-header',
  responsiveWidth: 813,
  responsiveHeight: 376,
  responsiveSlides: false,
  parallax: false,
  // parallaxOptions: { type: 'reveal', percentage: 62, property: 'translate' },
  // cards: false,
  // cardsOptions: { perspective: 100, fadeContent: true, fadeBackground: true },

  //Custom selectors
  sectionSelector: '.vp-fp-section',
  slideSelector: '.slide',

  lazyLoading: true
});

// Hero section - .screen-1 animation
/*
commented out but leaving until the hero section design is fully solidified
*/
// function fadeInBgImg() {
//   const screen1 = document.querySelector('.screen-1');

//   const bgScreen = document.querySelector('.vp-bg-panel');
//   screen1Content = document.querySelector('.vp-fg-panel_content');

//   const screen1H1 = document.querySelector('.screen-1 h1');
//   const screen1Subtitle = document.querySelector('.vp-js-subtitle');

//   setTimeout(function() {
//     bgScreen.classList.add('vp-swap-opacity');
//     screen1.classList.add('vp-swap-circle-color');
//     screen1Subtitle.classList.add('vp-subtitle-fade-in');
//   }, 600);
// }

// fire function on page load
// window.addEventListener('load', function() {
//   fadeInBgImg();
// });

$(window).on('load', function() {
  setTimeout(function(){
    $('.vpa-fade').each(function() {
      $(this).removeClass('vp-is-hidden');
    });
  }, 250);
});