<?php

add_shortcode ( 'view_my_assignments', 'agent_assignments' );

function agent_assignments ( $atts ) {
    // Collect arguments
    $args = shortcode_atts( array(
        'agent_id' => '',
        'field_id' => '1',
        'post_id' => '',
        'view_id' => ''
    ), $atts, 'view_my_assignments' );

    // Can Gravity View do this?
    return do_shortcode('[gravity_view view_id=');
}

?>