<?php

add_shortcode( 'display_training_results', 'display_gv7126' );

function display_gv7126 ( $atts ) {
    // [gravityview id="7126" search_field="" search_operator="" search_value="" filter=""];

    // agent is gonna be selected from the leadership dashboard
    // going to want to display the gravityview of submissions based on
    // the agent selected

    // Check for agent_id parameter, get user's ID from WP User object. If not provided, use the currently logged in user.
    if (!isset($_GET['agent_id'])) {
        $userToDisplay = get_current_user_id();
    } else {
        $query = array(
            'meta_key' => 'agent_number',
            'meta_value' => $_GET['agent_id']
        );
        $getUserObject = get_users($query);
        foreach ($getUserObject as $user) {
            $userToDisplay = $user->ID;
        }
    }
    // Get meta values
    $agentNumber = get_user_meta($userToDisplay, 'agent_number', true);
    
    // gv displays table with results, each row has a link to view submission
    // figure out a location to place a "create exam" button

    $viewID = '7126';
    $agentNumberField = '11';
    $saNumberField = '12';

    $gravityViewCode = '[gravityview id="'.$viewID.'"  search_field="'.$agentNumberField.'" search_operator="is" search_value="'.$agentNumber.'"]';
    return do_shortcode($gravityViewCode);
    

}

add_action( 'gravityview/template/links/back/url', 'gv_change_back_link', 10, 2 );

/**
 * Modify the back link URL for a specific View
 * 
 * @param string $href The original back link
 * @param \GV\Template_Context The current context
 *
 * @return string Modified URL, if View ID equals 7126
 */
function gv_change_back_link( $href = '', $context = null ) {
    
    // GitHub snippet to get full URI, parameters, and anchor
    $base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
    $url = $base_url . $_SERVER["REQUEST_URI"];

    // parse_url with PHP_URL_QUERY to return all parameters as a string, append them to a question mark
    $parameters = '?' . parse_url($url, PHP_URL_QUERY);

	if( 7126 === $context->view->ID ) {
        // Use original Go Back link with appended parameters
		return $href . $parameters;
	}
	
	return $href;
}

?>