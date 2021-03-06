<?

add_shortcode('dashboard_header', 'dashboard__header');

function dashboard__header($atts)
{

    $headerProperties = shortcode_atts(array('mode' => ''), $atts, 'dashboard_header');

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

    // add default case for Assignments to load current day
    if ($_GET['mode'] == 'view' && !$_GET['phase']) {
        if ($headerProperties['mode'] == 'single-new') {
            reload_current_day();
        } 
    }

    // pull meta tags into user array based on agent_id parameter or current user
    $dashboard__user = array(
        'first_name' => get_user_meta($userToDisplay, 'first_name', true),
        'last_name' => get_user_meta($userToDisplay, 'last_name', true),
        'agent_position' => get_user_meta($userToDisplay, 'agent_position', true)
    );

    // begin HTML layouts

    switch ($headerProperties['mode']) {
        case 'single':
            $page__title = '<div class="pageTitle">Presentation Training</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        case 'single-new':
            $page__title = '<div class="pageTitle">Your Assignments</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle"> Welcome, ' . $dashboard__user['first_name'] . '!</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        case 'hw-submit':
            $page__title = '<div class="pageTitle">Assignment Submission</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        case 'corporate':
            $page__title = '<div class="pageTitle">Corporate Training</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        case 'calendar':
            $page__title = '<div class="pageTitle">Calendar Invites</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        case 'wcn':
            $page__title = '<div class="pageTitle">Calendar Invites</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        case 'update_account':
            $page__title = '<div class="pageTitle">Update Account Information</div>';
            $dashboard__name__and__title = '<div class="nameAndTitle">' . $dashboard__user['first_name'] . ' ' . $dashboard__user['last_name'] . ' - ' . $dashboard__user['agent_position'] . '</div>';
            $description__box = '<div class="descriptionBox">' . $dashboard__name__and__title . '</div>';
            break;

        case 'recruiting':
            $page__title = '<div class="pageTitle">Recruiting Portal</div>';
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

function reload_current_day() {
    $phase_array = array(
        'Monday' => 'one',
        'Tuesday' => 'two',
        'Wednesday' => 'three',
        'Thursday' => 'four',
        'Friday' => 'five'
    );

    $today_day_name = date('l', strtotime('today'));

    header('Location: https://thejohnson.group/agent-portal/new-agent/training/?mode=view&phase=' . $phase_array[$today_day_name]);
    return true;
}