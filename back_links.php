<?php
add_action( 'gravityview/template/links/back/url', 'gv_change_back_link_gviews', 10, 2 );

/**
 * Modify the back link URL for a specific View
 * 
 * @param string $href The original back link
 * @param \GV\Template_Context The current context
 *
 * @return string Modified URL, if View ID equals 7126
 */
function gv_change_back_link_gviews( $href = '', $context = null ) {
    
    // get original url for parse to grab parameters
    $base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
    $url = $base_url . $_SERVER["REQUEST_URI"];
    $parameters = '?' . parse_url($url, PHP_URL_QUERY);

    $page_ids = array ( '7350', '10234', '10939', '10941', '10943');

	if( in_array($context->view->ID, $page_ids) ) {
		return $href . $parameters;
	}
	
	return $href;
}


?>