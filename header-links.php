<?

add_shortcode ( 'dashboard_header', 'dashboard__header' );

function dashboard__header ( $atts ) {

    $headerProperties = shortcode_atts( array('mode' => ''), $atts, 'dashboard_header' );

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

    // pull meta tags into user array based on agent_id parameter or current user
    $dashboard__user = array(
        'first_name' => get_user_meta($userToDisplay, 'first_name', true),
        'last_name' => get_user_meta($userToDisplay, 'last_name', true),
        'agent_position' => get_user_meta($userToDisplay, 'agent_position', true)
    );

    // begin HTML layouts

    switch ( $headerProperties['mode'] ) {
        case 'single':
            $page__title = '<div class="pageTitle">Presentation Training</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;
        
        case 'calendar':
            $page__title = '<div class="pageTitle">Calendar Invites</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        default:
        $page__title = '<div class="pageTitle">Leadership Dashboard</div>';
        $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
        $page__subtitle = '<div class="pageSubtitle">Viewing ' . $dashboard__user['first_name'] . '\'s Team</div>';
        $name__title__etc = $dashboard__name__and__title . $page__subtitle;
        $description__box = '<div class="descriptionBox">' . $name__title__etc . '</div>';
            break;
    }

    return $page__title . $description__box;

}