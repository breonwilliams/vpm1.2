<?php
/**
  * Template Name: Right Sidebar Page
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
Timber::render(array('pages/page-' . $post->post_name . '.twig', 'pages/right-sidebar-template-page.twig'), $context);
