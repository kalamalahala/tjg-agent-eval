<?

/*
    other functions to be called
*/

function show_agent_profile_individual($agent_object)
{
    //grab ID and email from argument object, grab the rest from meta tags, assemble First and Last name
    $user_id = $agent_object->ID;
    $email_address = $agent_object->user_email;
    $agent_number = get_user_meta($user_id, 'agent_number', true);
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $phone_number = get_user_meta($user_id, 'phone_number', true);
    $agent_name = $first_name . ' ' . $last_name;

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
    $badge_HTML = calculate_presentation_badges($agent_object);

    //HTML sections with css from Avada
    $opening_div = '<div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" >';
    $img_a_end_div = '<a href="https://thejohnson.group/agent-portal/agent/profile/?agent_id=' . $agent_number . '" target="_blank"><img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300" class="person-img img-responsive wp-image-4666 lazyautosizes lazyloaded"></a></div> </div> ';
    $name_and_agent_number = '<div class="person-desc"> <div class="person-author"> <div class="person-author-wrapper"><span class="person-name">' . $agent_name . '</span><span class="person-title">Agent Number: ' . $agent_number . '</span></div> </div>';
    $text_block = '<div class="person-content fusion-clearfix"> <p>Email Address: <a href="mailto:' . $email_address . '" target="_blank">' . ((!empty($email_address)) ? $email_address : 'No Email Address') . '</a><br />Phone Number: <a href="tel:' . $phone_number . '" target="_blank">' . ((!empty($phone_number)) ? $phone_number : 'No Phone Number') . '</a></p>';
    $badges = '<ul><li><a href="#" target="_blank">Presentation Proficiency</a></li> <li class="testScores">'. $badge_HTML .'</li> <li><a href="#" target="_blanks">Pending Business</a></li></ul>';
    $close_divs = '</div> </div> </div>';

    //put the profile block together and return it to display_agent_hierarchy()
    $assembled_HTML = $opening_div . $img_a_end_div . $name_and_agent_number . $text_block . $badges .  $close_divs;


    //scratch area to run calculate_presentation_badges

    return $assembled_HTML;
}

function calculate_presentation_badges($arg) {

    // Grab user object's ID and get_user_meta for the agent number
    $badgeAgentID = $arg->ID;
    $badgeAgentNumber = get_user_meta($badgeAgentID, 'agent_number', true);

    // the four presentation review form ids (assuming 63, 64, 65 are correct)
    $badgeFormIDs = array( '62', '63', '64', '65' );

    // Field 11 will need to be the agent number field on EVERY FORM until I better understand arrays
    $badgeGFAPIQuery['field_filters'][] = array(
        'key' => '11',
        'value' => $badgeAgentNumber
    );

    // instantiate arrays
    $presentationTrainingSubmissions = array();
    $iteratedResults = array();
    $examDates = array();

    // get all form submissions for agent for the four forms and fill array
    foreach ($badgeFormIDs as $formID) {
        $presentationTrainingSubmissions[] = GFAPI::get_entries($formID, $badgeGFAPIQuery);
    }

    // add most recent result to array
    // also create array of most recent date submitted
    foreach ($presentationTrainingSubmissions as $key => $value ) {
        $iteratedResults[] = $presentationTrainingSubmissions[$key][0]['16'];
        $examDates[] = (!is_null($presentationTrainingSubmissions[$key][0]['13'])) ? date('m/d/Y', strtotime($presentationTrainingSubmissions[$key][0]['13'])) : 'Untaken';
    }

    // open the flexbox and remove list styles
    $iconSet = '<div class="iconSetContainer"><ul class="checkboxIcons">';

    $passIconContainer = '<li class="passIcon">';
    $failIconContainer = '<li class="failIcon">';
    $nullIconContainer = '<li class="nullIcon">';
    $iconHeader = '';
    $passFailIcon = '';

    /*

    Need to figure out a way to do the following:
        Display Icon fa-check-square in a green color when condition = Pass
        display icon fa-times-square in a red color when condition = fail
        display icon fa-exclamation-square in a yellow color when condition = [add a condition to forms 63 and 64]

    */

    
    foreach ($iteratedResults as $key => $value ) {
        
        //assign icon header text based on quiz number
        switch ($key) {
            case '0':
                $iconHeader = '<div class="iconHeader">25%</div><i class="fa fa-check-square" title="Presentation Proficiency: 25%"></i><div class="iconFooter">'. $examDates[$key] .'</div></li>';
                break;
            case '1':
                $iconHeader = '<div class="iconHeader">50%</div><i class="fa fa-check-square" title="Presentation Proficiency: 50%"></i><div class="iconFooter">'. $examDates[$key] .'</div></li>';
                break;
            case '2':
                $iconHeader = '<div class="iconHeader">75%</div><i class="fa fa-check-square" title="Presentation Proficiency: 75%"></i><div class="iconFooter">'. $examDates[$key] .'</div></li>';
                break;
            case '3':
                $iconHeader = '<div class="iconHeader">100%</div><i class="fa fa-check-square" title="Presentation Proficiency: 100%"></i><div class="iconFooter">'. $examDates[$key] .'</div></li>';
                break;
        }

        //assign icon color based on pass/fail, append header from $iconHeader
        switch ($value) {
            case 'pass':
                $iconSet .= $passIconContainer . $iconHeader;
                break;
            case 'fail':
                $iconSet .= $failIconContainer . $iconHeader;
                break;
            default:
                $iconSet .= $nullIconContainer . $iconHeader;
                break;
        }
    }
    $iconSet .= '</ul></div>';

    // print('<pre>');
    // var_dump($iteratedResults);
    // print('</pre>');

    // print('<pre> $presentationTrainingSubmissions[0][0]["16"] '. $presentationTrainingSubmissions[0][0]['16'] . '</pre>');
    // print('GFAPI Query for agent ' . $badgeAgentNumber . ': <pre>');
    // var_dump($presentationTrainingSubmissions);
    // print('</pre>');



    $outgoingHTML = $iconSet;
    
    return $outgoingHTML;
}


/*
    Begin Shortcode
*/

function display_agent_hierarchy()
{

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

    //if Agency Owner is viewing, query all users with all relevant roles
    if ($agentPosition == 'Agency Owner') {
        $downlineQuery = array(
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'meta_key' => 'agent_position',
                    'meta_value' => 'Agent'
                ),
                array(
                    'meta_key' => 'agent_position',
                    'meta_value' => 'Junior Partner'
                ),
                array(
                    'meta_key' => 'agent_position',
                    'meta_value' => 'Senior Partner'
                ),
                array(
                    'meta_key' => 'agent_position',
                    'meta_value' => 'Quality Manager'
                )
            )
        );
    } else {
        // Select all users where saNumber = $agentNumber
        $downlineQuery = array(
            'meta_key' => 'saNumber',
            'meta_value' => $agentNumber
        );
    }

    // ask for an array of Users, begin empty string
    $findDownline = get_users($downlineQuery);
    $hierarchyHTML = '';

    // run the profile builder for each User in the array
    foreach ($findDownline as $agent) {
        $hierarchyHTML .= show_agent_profile_individual($agent);
    }

    // Display all returned HTML
    return $hierarchyHTML;
}

add_shortcode('display_agent_hierarchy', 'display_agent_hierarchy');
