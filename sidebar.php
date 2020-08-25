<?php
/**
 * The Template for displaying all single posts
 *
 *
 * @package  WordPress
 * @subpackage  Timber
 */
use Timber\Timber;
$context = array();
$context['dynamic_sidebar'] = Timber::get_widgets('dynamic_sidebar');
Timber::render('pages/sidebar.twig', $context);
// Timber::render(array('pages/sidebar.twig'), $data);
