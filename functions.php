<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
// require_once __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});
	// add_filter('template_include', function( $template ) {
	// 	return get_stylesheet_directory() . '/static/no-timber.html';
	// });
	return;
}

use Timber\Site;

class StarterSite extends Site {

	function __construct() {
		add_theme_support('post-formats');
		add_theme_support('post-thumbnails');

		// add more features support
		add_theme_support( 'custom-logo' );
		add_theme_support('menus');

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

    // Add support for Gutenberg blocks styles.
    add_theme_support( 'wp-block-styles' );

		add_filter('timber_context', array($this, 'add_to_context'));
    add_filter('get_twig', array($this, 'add_to_twig'));

    // Disable Gutenberg editor for the homepage
    // $homepage_post_id = get_option('page_on_front');
    // add_filter('use_block_editor_for_post', '__return_false', $homepage_post_id);

		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));
    add_action('wp_enqueue_scripts','theme_enqueue_styles');
    add_action('wp_enqueue_scripts','theme_enqueue_scripts');

				/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		// Add support for responsive embedded content.
    add_theme_support( 'responsive-embeds' );

    // Adds support for VPM color palette.
    add_theme_support( 'editor-color-palette', array(
      array(
        'name'  => __( 'White', 'vpm' ),
        'slug'  => 'vp-white',
        'color'	=> '#ffffff',
      ),
      array(
        'name'  => __( 'Gray', 'vpm' ),
        'slug'  => 'vp-mdgray',
        'color'	=> '#f5f5f5',
      ),
      array(
        'name'  => __( 'Orange', 'vpm' ),
        'slug'  => 'vp-orange',
        'color' => '#f26822',
          ),
      array(
        'name'  => __( 'Dark slate blue', 'vpm' ),
        'slug'  => 'vp-dkblue',
        'color' => '#1c2855',
      ),
      array(
        'name'  => __( 'Light navy', 'vpm' ),
        'slug'  => 'vp-ltnavy',
        'color' => '#187288',
      ),
      array(
        'name'  => __( 'Light blue', 'vpm' ),
        'slug'  => 'vp-ltblu',
        'color' => '#329bb4',
      ),
    ) );

    // Enqueue styles
    function theme_enqueue_styles() {

      // Get the theme data
      $the_theme = wp_get_theme();
        wp_enqueue_style( 'branch-styles', get_stylesheet_directory_uri() . '/assets/build/css/site.css', array(), $the_theme->get( 'Version' ) );


    }

    // Enqueue scripts
    function theme_enqueue_scripts() {
      wp_enqueue_script( 'jquery');

      // TODO Enqueue Bootstrap scripts

      // wp_enqueue_script( 'bootstrap', get_template_directory_uri().'/vendor/twbs/bootstrap/js/dist/util.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'script', get_template_directory_uri().'/assets/src/js/site.js', array('jquery'), '1.0', true );

      // pass Ajax Url to script.js
      // this is used for any ajax action on the website
      // EduInsights listing page - load more, filter by category
      // search by keywords
      wp_localize_script('script', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    }

    // Add function to filter posts by category (EduInsights listing page)
    add_action( 'wp_ajax_filter_posts', 'filter_posts' );
    add_action( 'wp_ajax_nopriv_filter_posts', 'filter_posts' );

    // Add function to search posts by keywords (EduInsights listing page)
    add_action( 'wp_ajax_search_posts', 'search_posts' );
    add_action( 'wp_ajax_nopriv_search_posts', 'search_posts' );

    // Add function to clear results of filtering or search and display all posts (EduInsights listing page)
    add_action( 'wp_ajax_all_posts', 'all_posts' );
    add_action( 'wp_ajax_nopriv_all_posts', 'all_posts' );

    // Load more posts for EduInsights listing page
    function load_more() {
      global $post;
      $offset = $_POST['offset']+1;
      // var_dump($offset);

      $posts = Timber::get_posts( array(
        'post_type' => 'post',
        'offset' => $offset,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,
      ) );

      // var_dump($posts);

      Timber::render( 'pages/teasers.twig', array( 'posts' => $posts ) );

      die();
    }

    // Filter EdiUnisghts posts by category on category select
    function filter_posts() {
      global $post;

      $posts = 'No posts found.';

      if ( isset( $_POST['category_id'] ) ) {
        $category_id = $_POST['category_id'];
        $posts = Timber::get_posts( array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'orderby' => 'date',
          'order' => 'DESC',
          'posts_per_page' => -1,
          'category__in' => $category_id,
        ) );

      }
      else {
        $posts = Timber::get_posts( array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'orderby' => 'date',
          'order' => 'DESC',
          'posts_per_page' => -1,
        ) );
      }
      // var_dump($posts);
      Timber::render( 'pages/teasers.twig', array( 'posts' => $posts ) );
      die();
    }

    // Search EduInsights posts by keywords
    function search_posts() {
      global $post;
      $posts = 'No posts found.';

      if ( isset( $_POST['keywords'] ) ) {
        $search_keywords = sanitize_text_field($_POST['keywords']);
        $posts = Timber::get_posts( array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'orderby' => 'date',
          'order' => 'DESC',
          's' => $search_keywords,
          'posts_per_page' => -1,
        ) );
        // print_r('number of posts: ');
        // print_r(count($posts));

      }
      else {
        // print_r('none');
        $posts = Timber::get_posts( array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'orderby' => 'date',
          'order' => 'DESC',
          'posts_per_page' => -1,
        ) );
      }
      // var_dump($posts);
      Timber::render( 'pages/teasers.twig', array( 'posts' => $posts ) );
      die();
    }

    // Clear filtered EdiUnisghts posts
    function all_posts() {
      global $post;
      $posts = 'No posts found.';
      $posts = Timber::get_posts( array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,
      ) );

      Timber::render( 'pages/teasers.twig', array( 'posts' => $posts ) );
      die();
    }

    //Add page slug body class
    function add_slug_body_class( $classes ) {
      global $post;
      if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
      }
      return $classes;
    }
    add_filter( 'body_class', 'add_slug_body_class' );

		/**
		 * Register two footer areas.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 */
		function branch_widgets_init() {
			$sidebars = array (
				'Footer 1' => 'footer-sidebar-1',
        'Footer 2' => 'footer-sidebar-2',
        'Header' => 'header-1'
			);
			foreach ( $sidebars as $sidebar => $sidebar_id )
			{
				register_sidebar(
					array(
						'name'          => __( $sidebar, 'branch' ),
						'id'            => $sidebar_id,
						'description'   => __( 'Add widgets here to appear in your widget areas.', 'branch' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					)
				);
			}
		}
		add_action( 'widgets_init', 'branch_widgets_init' );

		// Register navigation locations: use wp_nav_menu() in four locations: primary, gateway, utility, and footer
		register_nav_menus(
			array(
				'primary' => __( 'Primary', 'branch' ),
				'footer'  => __( 'Footer Menu', 'branch' ),
			)
		);

		/**
     * Create a main menu
     * @link https://codex.wordpress.org/Function_Reference/wp_create_nav_menu
     */

    // Check if the menu exists
    $main_menu_name = 'Main Menu';
    $main_menu_exists = wp_get_nav_menu_object( $main_menu_name );

    // If it doesn't exist, let's create it.
    if (!$main_menu_exists){
        $main_menu_id = wp_create_nav_menu($main_menu_name);

        // Set up default menu items
        wp_update_nav_menu_item($main_menu_id, 0, array(
            'menu-item-title' =>  __('About Us'),
            'menu-item-classes' => 'resources-dropdown',
            'menu-item-url' => home_url( '#' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($main_menu_id, 0, array(
            'menu-item-title' =>  __('Admissions'),
            'menu-item-classes' => 'resources-dropdown',
            'menu-item-url' => home_url( '#' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($main_menu_id, 0, array(
            'menu-item-title' =>  __('Academics'),
            'menu-item-classes' => 'resources-dropdown',
            'menu-item-url' => home_url( '#' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($main_menu_id, 0, array(
            'menu-item-title' =>  __('Resources and Services'),
            'menu-item-classes' => 'resources-dropdown',
            'menu-item-url' => home_url( '#' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($main_menu_id, 0, array(
            'menu-item-title' =>  __('Nav 1'),
            'menu-item-classes' => 'resources-dropdown',
            'menu-item-url' => home_url( '#' ),
            'menu-item-status' => 'publish'));

            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $main_menu_id;
	        set_theme_mod( 'nav_menu_locations', $locations );
    }

		    /**
     * Create the footer menu
     * @link https://codex.wordpress.org/Function_Reference/wp_create_nav_menu
     */

    // Check if the menu exists
    $footer_menu_name = 'Footer Menu';
    $footer_menu_exists = wp_get_nav_menu_object( $footer_menu_name );

    // If it doesn't exist, let's create it.
    if (!$footer_menu_exists){
        $footer_menu_id = wp_create_nav_menu($footer_menu_name);

        // Set up default menu items
        wp_update_nav_menu_item($footer_menu_id, 0, array(
            'menu-item-title' =>  __('Footer 1'),
            'menu-item-classes' => 'resources-dropdown',
            'menu-item-url' => home_url( '#' ),
            'menu-item-status' => 'publish'));

            $locations = get_theme_mod('nav_menu_locations');
            $locations['footer'] = $footer_menu_id;
	        set_theme_mod( 'nav_menu_locations', $locations );
    }

		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}


	// Add menus to context
	function add_to_context($context) {
		$context['menu'] = new Timber\Menu( 'Main Menu' , array('depth' => 2));
		$context['footermenu'] = new Timber\Menu( 'Footer Menu' );

		// add footer widgets to context
		$context['footer_widget_1'] = Timber::get_widgets('footer-sidebar-1');
    $context['footer_widget_2'] = Timber::get_widgets('footer-sidebar-2');

    $context['header_widget'] = Timber::get_widgets('header-1');

    // add sidebars to context
    $context['dynamic_sidebar'] = Timber::get_widgets('right-sidebar');
    $context['left_sidebar'] = Timber::get_widgets('left-sidebar');

		$context['site'] = $this;
		return $context;
	}


	function add_to_twig($twig) {
		/* this is where you can add your own fuctions to twig */
		$twig->addExtension(new Twig_Extension_StringLoader());
		/** Example how to add a filter
		* $twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', function(){
    *                // # some filter code
		*}));
		*/
		 /** Example how to add a function
		 * $twig->addFunction(new Twig_SimpleFunction($MyTwigFunction',function ($MyArgument1) {
	   *	  // some code
	   *      }));
		 */
		return $twig;
	}

}

// Register Team Member fields
if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key' => 'group_5c7457401c25f',
    'title' => 'Team_members fields',
    'fields' => array(
      array(
        'key' => 'field_5d950cd2badd6',
        'label' => 'Work title',
        'name' => 'team_work_title',
        'type' => 'text',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_5d9509c3f0e57',
        'label' => 'Favorites - 1',
        'name' => 'team_favorites_1',
        'type' => 'wysiwyg',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
        'delay' => 0,
      ),
      array(
        'key' => 'field_5d9509f8f0e58',
        'label' => 'Favorites - 2',
        'name' => 'team_favorites_2',
        'type' => 'wysiwyg',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
        'delay' => 0,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'team_members',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));

  endif;

// Register Capabilities taxonomy to be used in Portfolio post items
function cptui_register_my_taxes() {

	/**
	 * Taxonomy: Capabilities.
	 */

	$labels = array(
		"name" => __( "Capabilities", "custom-post-type-ui" ),
		"singular_name" => __( "Capability", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "Capabilities", "custom-post-type-ui" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'capability', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "capability",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		);
	register_taxonomy( "capability", array( "portfolio" ), $args );
}
add_action( 'init', 'cptui_register_my_taxes' );

add_action( 'init', 'cp_change_post_object' );

// Change dashboard Posts to EduInsights
function cp_change_post_object() {
  $get_post_type = get_post_type_object('post');
  $labels = $get_post_type->labels;
  $labels->name = 'EduInsights';
  $labels->singular_name = 'EduInsights';
  $labels->add_new = 'Add EduInsights Post';
  $labels->add_new_item = 'Add EduInsights Post';
  $labels->edit_item = 'Edit EduInsights Post';
  $labels->new_item = 'EduInsights Post';
  $labels->view_item = 'View EduInsights Posts';
  $labels->search_items = 'Search EduInsights Posts';
  $labels->not_found = 'No EduInsights Postsfound';
  $labels->not_found_in_trash = 'No EduInsights Posts found in Trash';
  $labels->all_items = 'All EduInsights Posts';
  $labels->menu_name = 'EduInsights';
  $labels->name_admin_bar = 'EduInsights';
}

  // Custom routing for the eduInsights blog page
  Routes::map('eduinsights', function($params){
    $query = 'posts_per_page=20&post_type=post';
    Routes::load('eduinsights.php', null, $query, 200);
  });

  // Custom routing for the Team landing page
  Routes::map('team', function($params){
    Routes::load('team.php');
  });

  // Custom routing for the Portfolio landing page
  Routes::map('portfolio', function($params){
    Routes::load('portfolio.php');
  });

  // Routes::map('blog/:name/page/:pg', function($params){
  //     $query = 'posts_per_page=3&post_type='.$params['name'].'&paged='.$params['pg'];
  //     $params = array('thing' => 'foo', 'bar' => 'I dont even know');
  //     Routes::load('archive.php', $params, $query);
  // });

new StarterSite();

include_once get_theme_file_path( 'assets/inc/acf-options.php' );
