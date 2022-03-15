<?php

add_shortcode('agent_level_gravity_view', 'agent_level_gravity_view');

/**
 * Returns a gravity view ID for the currently logged in Agent, or the one specified by agent_id
 *
 * @param int $atts the ID of the Gravity View
 * @return string the Gravity View Shortcode
 */
function agent_level_gravity_view($atts)
{
    // init string
    $gravityViewShortcode = '';
    $userToQuery = '';

    // get ID from shortcode parameter view_id
    $atts = shortcode_atts(array(
        'view_id' => '',
        'field_id' => '',
        'post_id' => '',
        'embed_mode' => '',
        'user_field_id' => ''
    ), $atts, 'agent_level_gravity_view');

    // agent_id parameter logic to get current agent number or a specified one
    // Unfortunately have not found a more elegant way to search and return a user's ID based on a meta value for that user.
    // Long form query used, and only if they are visible.
    // The visibile part is likely unnecessary but useful for debugging
    if (!isset($_GET['agent_id'])) {
        $userToQuery = get_current_user_id();
        $agentNumber = get_user_meta($userToQuery, 'agent_number', true);
    } else {
        $agent_query = array(
            'meta_query' => array(
                array(
                    'key' => 'agent_number',
                    'value' => $_GET['agent_id'],
                    'compare' => '='
                ),
                array(
                    'key' => 'is_dashboard_visible',
                    'value' => 'hidden',
                    'compare' => '!='
                )
            )
        );
        $agentToView = get_users($agent_query);
        foreach ($agentToView as $agent) {
            $userToQuery = $agent->ID;
        }
    }

    // build shortcode with:
    // $atts['view_id'] - the ID of the gravity view
    // $atts['field_id'] - the ID of the form field to filter by, must enter manually, but may omit
    // $agentNumber - the 5 digit agent number as acquired above 

    // embed_mode case handler
    switch ($atts['embed_mode']) {
        case 'view_assignments':
            $gravityViewShortcode = do_shortcode('');
            break;

        case 'user_id':
            $gravityViewShortcode = do_shortcode('[gravityview id="' . $atts['view_id'] . '" search_field="' . $atts['user_field_id'] . '" search_value="' . $userToQuery . '" post_id="' . $atts['post_id'] . '"]');
            break;

        default:
            $gravityViewShortcode = do_shortcode('[gravityview id="' . $atts['view_id'] . '" search_field="' . $atts['field_id'] . '" search_value="' . $agentNumber . '" post_id="' . $atts['post_id'] . '"]');
            break;
    }

    // return gravityview shortcode for relevant atts
    return $gravityViewShortcode;
}


add_action('gform_after_submission_75', 'set_entry_to_Submitted', 10, 2);
function set_entry_to_Submitted($entry, $form) {
    // get Unique ID from submission
    $assignment_id = rgar($entry, '15');

    // ask for matching entry from Phase One form? all 5 Phase forms?
    // here's the 5 forms to query
    // we want to find the entry that was created and has this unique ID, then adjust its Radio button for status to Submitted
    $phase_form_ids = array(
        'phase_1_id' => '71',
        'phase_2_id' => '73',
        'phase_3_id' => '74',
        'phase_4_id' => '76',
        'phase_5_id' => '77'
    );

    $gfAPIquery = array(
        'meta' => $assignment_id
    );

    
}