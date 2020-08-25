<?php
/**
 * The template for displaying EduInsights Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package    WordPress
 * @subpackage    Timber
 * @since        Timber 0.2
 */
use Timber\Timber;
use Timber\PostCollection;
use Timber\PostQuery;
use Timber\Term;

global $params;

$data = Timber::get_context();
$data['title'] = 'EduInsights';

$sticky = get_option( 'sticky_posts' );


// get the newest featured post
$args_featured = array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'post__in' => $sticky,
  'ignore_sticky_posts' => 1,
  'orderby' => 'date',
  'order' => 'DESC',
  'posts_per_page' => 1,
);
$data['featured_post'] = Timber::get_posts($args_featured);

// capture the featured post ID, so it can excluded from the list of posts
$featured_post_id = $data['featured_post'][0]->ID;

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

// get all posts except the featured one
$args = array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'orderby' => 'date',
  'order' => 'DESC',
  'posts_per_page' => -1,
  'post__not_in' => array($featured_post_id)
);

$data['posts'] = new PostQuery($args);

// get all post categories
$data['terms'] = Timber::get_terms('category');
// print_r($data['terms']);
Timber::render('landing-pages/eduinsights.twig', $data);
