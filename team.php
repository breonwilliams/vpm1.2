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
$data['title'] = 'Team';

// get all posts except the featured one
$args = array(
  'post_type' => 'team_members',
  'post_status' => 'publish',
  'orderby' => 'date',
  'order' => 'ASC',
  'posts_per_page'=>-1,
);
$data['posts'] = new PostQuery($args);

Timber::render('landing-pages/team.twig', $data);
