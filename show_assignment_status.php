<?php

add_shortcode('show_assignment_status', 'get_assignment_stats');

function get_assignment_stats( $atts ) {
    // form_id, optional agent_id via parameter, day of the week
    $atts = shortcode_atts( array(
        'form_id' => '',
        'week_day' => '',
        'agent_id' => '',
        'agent_field_id' => '',
        'unique_id_field_id' => ''
    ), $atts, 'show_assignment_status');

    // set agent_being_viewed to either the agent_id parameter, or the one passed into the shortcode (parameter takes precedence)
    $agent_being_viewed = ( !$_GET['agent_id'] ) ? $atts['agent_id'] : $_GET['agent_id'];

    // check if agent_id still blank, then get currently logged in user instead
    if (!$agent_being_viewed) {
        $current_user_id = get_current_user_id();
        $agent_being_viewed = get_user_meta($current_user_id, 'agent_number', true);
    }

    $search_criteria = array (
        'field_filters' => array (
            'mode' => 'all',
            array ( 
                'key' => $atts['agent_field_id'],
                'value' => $agent_being_viewed
            ),
            array (
                'key' => $atts['unique_id_field_id'],
                'operator' => 'isnot',
                'value' => ''
            )
        )
    );

    $assignment = GFAPI::get_entries($atts['form_id'], $search_criteria);
    $unique_id_field_id = $atts['unique_id_field_id'];
    $unique_id_list = array_map(
        function ($element) {
            return $element[$unique_id_field_id];
        }, $assignment
    );

    // print('<pre style="margin-bottom:60px;">');
    // var_dump($unique_id_list);
    // print('</pre>');

    return $agent_being_viewed . '\'s Assignments var dumped!';
}

?>