<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
use Timber\Timber;
use Timber\Helper;
use Timber\User;
use Timber\Post;

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['wp_title'] .= ' - ' . $post->title();

// get the author's work title - to be displayed under name in EduInsights posts
if ($post->post_type == 'post') {
  $context['author_id'] = $post->author->id;
  $ref_field = get_field('team_member_reference', 'user_'. $post->author->id );
  if ( is_array($ref_field) ) {
    $team_member_id = $ref_field[0]->ID;
    $context['team_member'] = get_field_objects($team_member_id);
    $context['team_member_id'] = $team_member_id;
    $context['team_member_work_title'] = get_field('team_work_title', $team_member_id);
  }
}

// get the first Portfolio item - to be linked in the last portfolio post in the Next Project section
// it's actually the last item when ordered chronologically
if ($post->post_type == 'portfolio') {
  $args = array(
    'post_type' => 'portfolio',
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page'=>1,
  );
  $context['first_portfolio_item'] = Timber::get_posts($args)[0];
}


if (post_password_required($post->ID)){
	Timber::render('pages/single-password.twig', $context);
} else {
	Timber::render(array('pages/single-' . $post->ID . '.twig', 'pages/single-' . $post->post_type . '.twig', 'pages/single.twig'), $context);
}


