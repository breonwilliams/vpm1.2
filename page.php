<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
use Timber\Post;
use Timber\Timber;
$context = Timber::get_context();
$post = new Post();
$context['post'] = $post;


$templates = array('pages/page-' . $post->post_name . '.twig', 'pages/default-template-page.twig');

if (is_front_page()) {

  // get 3 newest EduInsights posts to display on the homepage News section
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'ignore_sticky_posts' => 1,
    'orderby' => 'date',
    'order' => 'DESC'
  );
  $context['newest_posts'] = Timber::get_posts( $args );

  array_unshift($templates, 'pages/home.twig');
}
Timber::render($templates, $context);
