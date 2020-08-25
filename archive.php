<?php
/**
 * The template for displaying Archive pages.
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

$templates = array('pages/archive.twig', 'pages/index.twig');

$data = Timber::get_context();
// print_r($data);
$data['title'] = 'Archive';

if (is_day()) {
    $data['title'] = 'Archive: ' . get_the_date('D M Y');
} else if (is_month()) {
    $data['title'] = 'Archive: ' . get_the_date('M Y');
} else if (is_year()) {
    $data['title'] = 'Archive: ' . get_the_date('Y');
} else if (is_tag()) {
    $data['title'] = single_tag_title('', false);
    array_unshift($templates, 'landing-pages/taxonomy.twig');
} else if (is_category()) {
    $data['title'] = single_cat_title('', false);
    array_unshift($templates, 'pages/archive-' . get_query_var('cat') . '.twig', 'landing-pages/taxonomy.twig');
} else if (is_tax()){
    // if this is the archive page based on the taxonomy
    // for example, pre-filtered portfolio page, ex: /portfolio/web/
    $term = get_queried_object();
    $data['title'] = $term->name;
    $data['term'] = $term;
    // echo "<pre>"; print_r($term->taxonomy); echo "</pre>";
    if ($term->taxonomy == 'capability') {
      $data['title'] = "Portfolio";
      $data['sub_title'] = $term->name;
      array_unshift($templates, 'landing-pages/portfolio.twig');
    }
    else {
      array_unshift($templates, 'landing-pages/taxonomy.twig');
    }

} else if (is_post_type_archive()) {
    $data['title'] = post_type_archive_title('', false);
    array_unshift($templates, 'pages/archive-' . get_post_type() . '.twig');
}

$data['posts'] = Timber::get_posts();

Timber::render($templates, $data);
