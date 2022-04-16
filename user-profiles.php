<?

/*
    other functions to be called
*/

function show_agent_profile_individual($agent_object, $sa_Number, $viewerAgentPosition, $mode)
{
    
    //grab ID and email from argument object, grab the rest from meta tags, assemble First and Last name
    $user_id = $agent_object->ID;
    $email_address = $agent_object->user_email;
    // escape if hidden
    $visibility = get_user_meta($user_id, 'is_dashboard_visible', true);
    if ($visibility == 'false') return; 

    // print '<pre style="margin-bottom: 60px;">';
    // var_dump($visibility);
    // print '</pre>';


    $agent_number = get_user_meta($user_id, 'agent_number', true);
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $phone_number = get_user_meta($user_id, 'phone_number', true);
    $agent_position = get_user_meta($user_id, 'agent_position', true);
    $agent_name = $first_name . ' ' . $last_name;

    if ($agent_number == '42215' && $viewerAgentPosition == 'Corporate Trainer') {
        return false;
    }

    //check for profile pic, use Logo as default if no profile
    $default_pic = "https://thejohnson.group/wp-content/uploads/default.png";
    $pic_url = '';
    $request_profile_pic = get_user_meta($user_id, 'profile_pic_url', true);
    if (empty($request_profile_pic)) {
        $pic_url = $default_pic;
    } else {
        $pic_url = $request_profile_pic;
    }


    // get test scores and return icons
    $badge_HTML = create_presentation_badges($agent_object, $sa_Number);
    $downline_HTML = ask_for_downline($agent_number, $first_name);

    //HTML sections with css from Avada
    $opening_div = '<div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" >';
    $img_a_end_div = '<img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300" class="person-img img-responsive wp-image-4666 lazyautosizes lazyloaded"><div class="person-toggle-container"><a href="https://thejohnson.group/agent-portal/quality-portal/agent-training/?toggle_visible_agent_id=' . $agent_number . '" target="_self" title="Toggle Visibility"><i class="fa-solid fa-magnifying-glass"></i>&nbsp Toggle Visibility</a></div></div> </div> ';
    $name_and_agent_number = '<div class="person-desc"> <div class="person-author"> <div class="person-author-wrapper"><span class="person-name">' . $agent_name . '</span><span class="person-title">Agent Number: ' . $agent_number . '</span></div> </div>';
    $text_block = '<div class="person-content fusion-clearfix"> <p>Email: <a href="mailto:' . $email_address . '" target="_blank">' . ((!empty($email_address)) ? $email_address : 'No Email Address') . '</a><br />Phone Number: <a href="tel:' . $phone_number . '" target="_blank">' . ((!empty($phone_number)) ? $phone_number : 'No Phone Number') . '</a></p>';
    $badges = '<ul><li><strong>Field Training</strong></li><a href="https://thejohnson.group/agent-portal/quality-portal/agent-training/presentation-inspection/?agent_id=' . $agent_number . '&mode=view" target="_blank"><li><strong>View Exams</strong></li></a><li class="testScores">' . $badge_HTML . '</li>';
    $corporate_training_buttons = '<li><div class="corporateHeader">View Classroom Training Progress</div><div class="corporateFlexbox" style="text-align:center; margin-top: 10px;">
    <a class="fusion-button button-3d button-small button-default button-2" title="' . $first_name . '\'s Corporate Training Phase One Results" href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_one" target="_blank"><span class="fusion-button-text">Mon</span></a>
    <a class="fusion-button button-3d button-small button-default button-2" title="' . $first_name . '\'s Corporate Training Phase Two Results" href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_two" target="_blank"><span class="fusion-button-text">Tue</span></a>
    <a class="fusion-button button-3d button-small button-default button-2" title="' . $first_name . '\'s Corporate Training Phase Three Results" href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_three" target="_blank"><span class="fusion-button-text">Wed</span></a>
    <a class="fusion-button button-3d button-small button-default button-2" title="' . $first_name . '\'s Corporate Training Phase Four Results" href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_four" target="_blank"><span class="fusion-button-text">Thu</span></a>
    <a class="fusion-button button-3d button-small button-default button-2" title="' . $first_name . '\'s Corporate Training Phase Five Results" href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_five" target="_blank"><span class="fusion-button-text">Fri</span></a>
    </div></li>';
    $pending_business_and_downline = '<li><a href="#">' . $first_name . '\'s Pending Business</a></li></ul>' . $downline_HTML;
    $close_divs = '</div> </div> </div>';

    //put the profile block together and return it to display_agent_hierarchy()

    if ($mode == 'single') {
        $assembled_HTML = $opening_div . $badges . $close_divs;
    } else {
        $assembled_HTML = $opening_div . $img_a_end_div . $name_and_agent_number . $text_block . $badges . $corporate_training_buttons .  $pending_business_and_downline .  $close_divs;
    }

    return $assembled_HTML; // sends back the assembled block of information for the agent, Profile picture, and presentation training status
}

function ask_for_downline($downline__number, $leader__name)
{

    // ask for all agents that have the PERSON ELEMENT's agent number as their saNumber
    $downline__meta__query = array(
        'meta_key' => 'saNumber',
        'meta_value' => $downline__number
    );

    //return array of data that contains all users that match
    $downline__agents = get_users($downline__meta__query);

    // wrap all output in checking to see if the agent query was empty, otherwise null return
    if (!empty($downline__agents)) {

        //build layout components
        $team__container = '<div class="teamContainer"><div class="teamHeader"><p>' . $leader__name . '\'s Team: </p></div><ul class="teamList">';
        $team__items = '';
        $view__team__button = '<div class="viewTeamButton"><a href="https://thejohnson.group/agent-portal/quality-portal/agent-training/?agent_id=' . $downline__number . '" target="_blank"><button class="fusion-button button-3d button-large button-default button-2 fusion-button-span-no">View ' . $leader__name . '\'s Team</button></a></div>';

        //iterate over get_users data and add list items
        foreach ($downline__agents as $agent) {
            $agent__number = get_user_meta($agent->ID, 'agent_number', true);
            $agent__name = $agent->display_name;
            $team__items .= '<li class="teamMember"><a href="#" title="' . $agent__name . '">' . $agent__name . ' - ' . $agent__number . '</a></li>';
        }

        //append list of agents and view team button, button up the divs
        $team__container .= $team__items . '</ul>' . $view__team__button . '</div>';

        return $team__container; //return some HTML about containing that agent's team to show_agent_profile_individual
    } else {
        return null;
    }
}

function create_presentation_badges($arg, $sa__Number)
{

    // Grab user object's ID and get_user_meta for the agent number
    $badgeAgentID = $arg->ID;
    $badgeAgentNumber = get_user_meta($badgeAgentID, 'agent_number', true);

    // revised to new presentation form 67 with relevant Grade fields for pass/fail/tryagain
    $badgeFormIDs = '67';
    $badgeGradeIDs = array('16', '30', '42', '50');

    // Field 11 is agent number
    $badgeGFAPIQuery['field_filters'][] = array(
        'key' => '11',
        'value' => $badgeAgentNumber
    );

    // instantiate array
    $iteratedResults = array();

    // new foreach for new form
    $presentationTrainingSubmissions = GFAPI::get_entries($badgeFormIDs, $badgeGFAPIQuery);
    $examDate = (!is_null($presentationTrainingSubmissions[0][13])) ? date('m/d/Y', strtotime($presentationTrainingSubmissions[0][13])) : 'Untaken';

    foreach ($badgeGradeIDs as $value) {

        $iteratedResults[] = $presentationTrainingSubmissions[0][$value];
    }


    // open the flexbox and remove list styles
    // TODO :: change href to be a view report of the entry
    $iconSet = '<div class="iconSetContainer"><div class="dateTaken">Last Exam: <a href="#">' . $examDate . '</a></div><ul class="checkboxIcons">';

    $passIconContainer = '<li class="passIcon">';
    $passIcon = 'fa fa-check-square';
    $failIconContainer = '<li class="failIcon">';
    $failIcon = 'fa fa-exclamation-triangle';
    $tryAgainIconContainer = '<li class="tryAgainIcon">';
    $tryAgainIcon = 'fa fa-exclamation-triangle';
    $nullIconContainer = '<li class="nullIcon">';
    $nullIcon = 'fa fa-plus';
    $examURL = 'https://thejohnson.group/agent-portal/quality-portal/agent-training/presentation-inspection/?';
    $iconHeader = '';

    foreach ($iteratedResults as $key => $value) {

        //assign icon header text based on quiz number
        switch ($key) {
            case '0':
                $iconHeader = '<div class="iconHeader">25%</div><a href="' . $examURL . 'agent_id=' . $badgeAgentNumber . '&sa=' . $sa__Number . '&form_mode=25" target="_blank">';
                break;
            case '1':
                $iconHeader = '<div class="iconHeader">50%</div><a href="' . $examURL . 'agent_id=' . $badgeAgentNumber . '&sa=' . $sa__Number . '&form_mode=50" target="_blank">';
                break;
            case '2':
                $iconHeader = '<div class="iconHeader">75%</div><a href="' . $examURL . 'agent_id=' . $badgeAgentNumber . '&sa=' . $sa__Number . '&form_mode=75" target="_blank">';
                break;
            case '3':
                $iconHeader = '<div class="iconHeader">100%</div><a href="' . $examURL . 'agent_id=' . $badgeAgentNumber . '&sa=' . $sa__Number . '&form_mode=100" target="_blank">';
                break;
        }

        //assign icon color based on pass/fail, append header from $iconHeader
        switch ($value) {
            case 'pass':
                $iconSet .= $passIconContainer . $iconHeader . '<i class="' . $passIcon . '"></i></li></a>';
                break;
            case 'fail':
                $iconSet .= $failIconContainer . $iconHeader . '<i class="' . $failIcon . '"></i></li></a>';
                break;
            case 'tryagain':
                $iconSet .= $tryAgainIconContainer . $iconHeader . '<i class="' . $tryAgainIcon . '"></i></li></a>';
                break;
            default:
                $iconSet .= $nullIconContainer . $iconHeader . '<i class="' . $nullIcon . '"></i></li></a>';
                break;
        }
    }
    $iconSet .= '</ul></div>';

    $outgoingHTML = $iconSet;

    return $outgoingHTML;
}


/*
    Begin Shortcode
*/

function display_agent_hierarchy($atts)
{
    $vars = shortcode_atts(array(
        'mode' => ''
    ), $atts, 'display_agent_hierarchy');


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
    $agentPosition = get_user_meta($userToDisplay, 'agent_position', true);

    // $new_agent_query = array(
    //     'meta_query' => array(
    //         'relation' => 'AND',
    //         array(
    //             'key' => 'is_new_agent',
    //             'value' => 'true',
    //             'compare' => '='
    //         ),
    //         array(
    //             'key' => 'agency_name',
    //             'value' => $vars['agency_name'],
    //             'compare' => '='
    //         ),
    //         array(
    //             'key' => 'is_dashboard_visible',
    //             'value' => 'false',
    //             'compare' => '!='
    //         )
    //     )
    // );

    // Check mode parameter and change functionality
    switch ($agentPosition) {
        case 'Corporate Trainer':
            $downlineQuery = array(
                        'meta_key' => 'is_new_agent',
                        'meta_value' => 'true',
                        'meta_compare' => '='
                    );
            break;

        default:
            // Select all users where saNumber = $agentNumber
            $downlineQuery = array(
                        'meta_key' => 'saNumber',
                        'meta_value' => $agentNumber,
                        'meta_compare' => '='
                    );
            break;
    }

    
    if ($vars['mode'] == 'single') {
        $downlineQuery = array(
            'meta_key' => 'agent_number',
            'meta_value' => $agentNumber
        );
    }
    
    
    // ask for an array of Users, begin empty string
    $findDownline = get_users($downlineQuery);
    // var_dump($downlineQuery);
    // print '<br><pre style="margin-bottom:60px;">';
    // var_dump($findDownline);
    // print '</pre>';
    // die;
    $hierarchyHTML = '';

    // run the profile builder for each User in the array
    foreach ($findDownline as $agent) {
        $hierarchyHTML .= show_agent_profile_individual($agent, $agentNumber, $agentPosition, $vars['mode']);
    }

    // Display all returned HTML
    return $hierarchyHTML;
}

add_shortcode('display_agent_hierarchy', 'display_agent_hierarchy');
// add_shortcode('display_single_agent', 'show_agent_profile_individual');
