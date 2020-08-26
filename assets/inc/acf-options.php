<?php
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page();

}

/* functions.php */
add_filter( 'timber_context', 'mytheme_timber_context'  );

function mytheme_timber_context( $context ) {
    $context['options'] = get_fields('option');
    return $context;
}
