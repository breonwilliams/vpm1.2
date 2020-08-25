jQuery(function($) {

  // Main menu fixes
  // $(window).on('resize', function(){
  //   var menuItemHeight = $(window).height() / 8;
  //   $('.main-navigation #menu .nav .nav-item').css( "height", menuItemHeight );
  // });

  function is_touch_device() {
    var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
    var mq = function(query) {
      return window.matchMedia(query).matches;
    }

    if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
      return true;
    }

    // include the 'lolz' as a way to have a non matching MQ to help terminate the join
    // https://git.io/vznFH
    var query = ['(', prefixes.join('touch-enabled),('), 'lolz', ')'].join('');
    return mq(query);
  }

  // if this is a touch device and the screen resultion is higher than desktop (1200px), show the submenu opened by default
  if (is_touch_device()) {
    var screenWidth = $(window).width();
    // console.log(screenWidth);

    if (screenWidth >= 1200) {
      $(".vp-subnav-icon").css("display", "block");
    }

    //  On orientation changes, make sure to hide the submenu
    $( window ).on( "orientationchange", function( event ) {
      $(".nav-drop").css("display", "none");
    });
  }


  $(".vp-subnav-icon").on('click', function (e) {

      var menuItemHeight = $(window).height() / 8;
      var subnavSelector = $(this).attr("data-target");
      var screenWidth = $(window).width();

      if ($(this).attr("aria-expanded") == "false") {
        $(subnavSelector).show();
        $(".main-navigation #menu .nav:hover .nav-drop a").css("color","#ffffff");
        $(".main-navigation #menu .nav:hover .nav-item a").css("color","#ffffff");
        if (screenWidth < 1200) {
          $(this).parents(".menu-item-has-children").css( "height", "auto" );
        }

        // allow the panel to be scrollable

        $(".main-navigation .panel-collapse").css( "height", "auto" );
        $(".main-navigation .panel-collapse").focus();
      }
      else {
        $(subnavSelector).hide();
        $(this).parents(".menu-item-has-children").css( "height", menuItemHeight );
      }

  });


  // Remove 'role' attribute from the footer Newsletter signup form (a11y fix)
  $(".wpcf7").removeAttr("role");

  // Functions to run only on the EduInsights page

  if (window.location.pathname === '/eduinsights/') {
    // EduInsights landing page
    // Load more EduInsights articles when clicking on 'Load More' button.

    function toggleLoadMoreButton() {
      if ($(".teaser:hidden").length == 0) {
        $("#loadMore").fadeOut('slow');
      }
      else {
        $("#loadMore").fadeIn('slow');
      }
    }

    // EduInsights landing page
    // Helper function to reveal articles
    function revealMoreArticles() {
      $(".teaser").slice(0, 6).show("slow");
      toggleLoadMoreButton();
      $("#loadMore").on('click', function (e) {
        e.preventDefault();
        $(".teaser:hidden").slice(0, 6).slideDown("fast");
        toggleLoadMoreButton();
      });
    }

    // EduInsights landing page
    // Helper function to show initial articles on page load
    $( document ).ready(function() {
      revealMoreArticles();
    });

    // EduInsights landing page
    // Filter out EduInsights articles when clicking categories checkboxes, load via ajax

    $('.categoryfilter').on('change', function() {

      var category_id = [];
      $.each($('.categoryfilter:checkbox:checked'), function(){
        category_id.push($(this).val());
      });

      $.post(
        ajaxurl,
        {
          'action': 'filter_posts',
          'category_id': category_id
        },
        function(response){
          $('.ajax-ex').hide().html(response).fadeIn('fast');revealMoreArticles();
          $("#clearResults").show();
          if (category_id.length == 0) {
            $("#clearResults").hide();
          }
        }
      );

    });

    // EduInsights listing page
    // Search EduInsights articles by keywords, load via ajax
    $('#search').submit(function(){
      $.post(
        ajaxurl,
        {
          'action': 'search_posts',
          'keywords': jQuery('#search-keywords').val()
        },
        function(response){
          $('.ajax-ex').hide().html(response).fadeIn('slow');
          revealMoreArticles();
          $("#clearResults").show();
        }
      );
      return false;
    });

    // EduInsights listing page
    // Clear the results of filters or search, load all posts via ajax
    // Clear the filter checkboxes and search field
    $('#clearResults').click(function(){
      $.post(
        ajaxurl,
        {
          'action': 'all_posts'
        },
        function(response){
          $('.ajax-ex').hide().html(response).fadeIn('slow');
          revealMoreArticles();
          $('.filters-form input:checkbox').removeAttr('checked');
          $(".search-input").val("");
          // hide the Clear Results button
          $("#clearResults").hide();
        }
      );
      return false;
    });

  }
  // End EduInsights page scripts


  // Team member page
  // Reveal/collapse content for the bio
  // And accessibility fixes

  if ($('body.single-team_members').length > 0) {

    var $bio_height = $('.expand-content').outerHeight();
    $collapsed_bio_height = '20rem';

    // set the bio height to 20rem intially
    $('.expand-content').css('height', $collapsed_bio_height);

    $('.read-bio').on('click', function(){
      $('.expand-content').toggleClass('-expanded');

      if ($('.expand-content').hasClass('-expanded')) {
        // bio panel is expanded
        // set the bio height to full expanded height
        $('.expand-content').animate( { height: $bio_height }, 500 );

        $('.read-bio').html('Read full bio <i class="fas fa-minus"></i>');
        // update the aria-expanded attribute of the region
        $('.expand-content').attr('aria-expanded', 'true');
        // move focus to the region
        $('.expand-content').focus();
        // change the label of the button to indicate that the region can be collapsed now
        $('.read-bio').attr('aria-label', 'Collapse full bio');
      }
      else {
        // bio panel is collapsed
        // set the bio height back to 20rem height
        $('.expand-content').animate( { height: $collapsed_bio_height }, 500 );
        $('.read-bio').html('Read full bio <i class="fas fa-plus"></i>');
        // update the aria-expanded attribute of the region
        $('.expand-content').attr('aria-expanded', 'false');
        // move focus to the region
        $('.expand-content').focus();
      }
    });
  }

  // End Team Member scripts

  // Capabilites page
  // Add smooth anchor scrolling with the top offset to make the image visible
  if ($('body.page-capabilities').length > 0) {
    $('.nav-child-link').click(function () {
      var anchor_href = $(this).attr('href');
      console.log(anchor_href);
      $('html, body').animate({
        scrollTop: $(anchor_href).offset().top - 200
      }, 500);
    });
  }

});
