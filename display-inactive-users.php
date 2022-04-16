<?php

// WordPress: display-inactive-users.php
// Search all users by meta tag 'is_dashboard_visible' for 'false'

add_shortcode('display-inactive-users', 'display_inactive_users');

function display_inactive_users() {
    $return_html = '<h3>Inactive Users</h3><div class="inactive-user-card">';
    $args = array(
        'meta_query' => array(
            array(
                'key' => 'is_dashboard_visible',
                'value' => 'false',
                'compare' => '='
            )
        )
    );
    $users = get_users($args);
    // create HTML link to toggle visibility based on agent_number meta tag
    foreach ($users as $user) {
        $return_html .= '<div class="user-card">';
        $link = '<a href="https://thejohnson.group/agent-portal/agent/?toggle_visible_agent_id=' . $user->agent_number . '">Toggle Visibility</a>';
        $return_html .= 'User ID: ' . $user->ID . '<br />';
        $return_html .= 'User Name: ' . $user->user_login . '<br />';
        $return_html .= 'User Email: ' . $user->user_email . '<br />';
        $return_html .= 'User Display Name: ' . $user->display_name . '<br />';
        $return_html .= 'User First Name: ' . $user->first_name . '<br />';
        $return_html .= 'User Last Name: ' . $user->last_name . '<br />';
        $return_html .= 'User Role: ' . $user->roles[0] . '<br />';
        $return_html .= 'User Dashboard Visibility: ' . get_user_meta($user->ID, 'is_dashboard_visible', true) . ' ' . $link . '<br />';
        $return_html .= '</div>';
    }

    return $return_html . '</div>';
}