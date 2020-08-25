<?php
/**
  * Template Name: Full Width Page
  *
  * Template for displaying a page without sidebar even if a sidebar widget is published.
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
$context['dynamic_sidebar'] = NULL;
$context['left_sidebar'] = NULL;
Timber::render(array('pages/page-' . $post->post_name . '.twig', 'pages/fullwidth-template-page.twig'), $context);
