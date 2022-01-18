<?php

/*
* Begin shortcode
* harvest other stuff
*/

add_shortcode( 'display_new_agents_in_training', 'display_new_agents_in_training' );
function display_new_agents_in_training ( $atts ) {

    $vars = shortcode_atts(array(
        'mode' => ''
    ), $atts, 'display_new_agents_in_training' );

    // $atts is probably display mode, also form ids
    // Display list of agents in user table that have is_new_agent flag as true
    // todo: add is_new_agent to user meta column and set a couple agents to true
    // todo: update user reg form to flag all new accounts as true
    // todo: harvest display functions from pers or profiles
    // todo: display results of, and buttons to create
    //          * phase 1, 2, and 3
    //          * link to gravity view list of results from agent
    //          * create new exam phase 1/2/3
    // todo: create forms (3 forms?)


    $new_agent_query = array( 
        'meta_key' => 'is_new_agent',
        'meta_value' => 'true'
    );

    $new_agents = get_users($new_agent_query);
    $layoutHTML = '';

    // layout functions in loops, switch case for shortcode mode

    switch ($vars['mode']) {
        case '':
            foreach ($new_agents as $agent) {
                $layoutHTML .= create_new_agent_profile_basic($agent);
            }
            break;
    }

    // return all returned HTML
    return $layoutHTML;

}


/**
 * create_new_agent_profile_basic
 * 
 * builds new agent training review block for dashboard view
 * 
 * @param object agent object passed in from primary function
 * @return string html divs to send back
 */
function create_new_agent_profile_basic ( $agent ) {

    //grab ID and other meta tags
    $agent_userID = $agent->ID;
    $email_address = $agent->user_email;
    $agent_number = get_user_meta($agent_userID, 'agent_number', true);
    $first_name = get_user_meta($agent_userID, 'first_name', true);
    $last_name = get_user_meta($agent_userID, 'last_name', true);
    $phone_number = get_user_meta($agent_userID, 'phone_number', true);
    $agent_position = get_user_meta($agent_userID, 'agent_position', true);
    $agent_name = $first_name . ' ' . $last_name;
    $assembled_HTML = '';

    $vars = array(
        'phase_1_id' => '',
        'phase_2_id' => '',
        'phase_3_id' => ''
    );

    //check for profile pic, use Logo as default if no profile
    $default_pic = "https://thejohnson.group/wp-content/uploads/default.png";
    $pic_url = '';
    $request_profile_pic = get_user_meta($agent_userID, 'profile_pic_url', true);
    if (empty($request_profile_pic)) {
        $pic_url = $default_pic;
    } else {
        $pic_url = $request_profile_pic;
    }

    //HTML sections with css from Avada
    $opening_div = '<div class="person__container__wrapper"><div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" >';
    $img_a_end_div = '<a href="#"><img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300" class="person-img img-responsive wp-image-4666 lazyautosizes lazyloaded"></a></div> </div> ';
    $name_and_agent_number = '<div class="person-desc"> <div class="person-author"> <div class="person-author-wrapper"><span class="person-name">' . $agent_name . '</span><span class="person-title">Agent Number: ' . $agent_number . '</span></div> </div>';
    $text_block = '<div class="person-content fusion-clearfix"> <p>Email: <a href="mailto:' . $email_address . '" target="_blank">' . ((!empty($email_address)) ? $email_address : 'No Email Address') . '</a><br />Phone Number: <a href="tel:' . $phone_number . '" target="_blank">' . ((!empty($phone_number)) ? $phone_number : 'No Phone Number') . '</a></p>';

    $phases_results_block = phase_results_block( $agent_number, $first_name, $vars['phase_1_id'], $vars['phase_2_id'], $vars['phase_3_id'] );
    
    $close_divs = '</div> </div> </div> </div> </div>';

    //put the profile block together and return it to display_persistency_users()
    $assembled_HTML = $opening_div . $img_a_end_div . $name_and_agent_number . $text_block . $phases_results_block . $close_divs;

    return $assembled_HTML; // sends back the assembled block of information for the agent, Profile picture, with a section on Persistency and an Update Form

}

function phase_results_block ( $agent_number, $first_name, $id1, $id2, $id3 ) {
    // query the 3 form ids for latest result, return the 3 blocks back

    $phaseOneQuery = fetch_phase_stats ( $agent_number, $id1 );
    $phaseTwoQuery = fetch_phase_stats ( $agent_number, $id2 );
    $phaseThreeQuery = fetch_phase_stats ( $agent_number, $id3 );

    $htmlBlock = '';

    $htmlContainerTop = '<div class="training__block"><div class="training__container"><div class="training__stats">';

    $phase_stats = '<ul>
        <li style="" class="phase__one__stats"><div class="phase__header">Phase One</div><div class="percentage">69% (add ternary for no data)</div></li>
        <li style="" class="phase__two_stats"><div class="phase__header">Phase Two</div><div class="percentage">70%</div></li>
        <li style="" class="phase__three_stats"><div class="phase__header">Phase Three</div><div class="percentage">71%</div></li>
        </ul>';

    $update_button = '<div style="text-align:center;">
        <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
            title="Create New Exam for '. $first_name .'" href="#"
            target="_self"><span class="fusion-button-text">Create Exam</span></a>
    </div>
</div>';
    $htmlContainerBottom = '</div>';

    return $htmlContainerTop . $phase_stats . $update_button . $htmlContainerBottom;


    return $htmlBlock;
}

function fetch_phase_stats ( $agentNumber, $form_id ) {

    // build API Query for form results
    $GFAPI_query = array(
        'field_filters' => array(
            'mode' => 'all',
            array( 'key' => '', 'value' => $agentNumber )
        )
    );

    $entries = GFAPI::get_entries( $form_id, $GFAPI_query );

    return $entries;

}

?>