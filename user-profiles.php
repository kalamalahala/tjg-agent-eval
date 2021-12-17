<?


function show_agent_profile_individual($agent_object)
{
    $user_id = $agent_object->ID;
    $email_address= $agent_object->user_email;
    $agent_number = get_user_meta($user_id, 'agent_number', true);
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    // $email_address = get_user_meta($user_id, 'user_email', true);
    $phone_number = get_user_meta($user_id, 'phone_number', true);
    // $css_id = '#'.$agent_number;
    $css_class = '.'.$agent_number;
    $popover = 'popover-'.$agent_number;

    $agent_name = $first_name . ' ' . $last_name;

    //html block from Avada builder. Does "fusion-person-n" need to be counted and updated? or can they all be the same class?
    $opening_div = '<div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" >';

    //check for profile pic, use Logo as default if no profile
    $default_pic = "https://thejohnson.group/wp-content/uploads/2021/02/BlackTextLogo.png";
    $pic_url = '';
    $request_profile_pic = get_user_meta($user_id, 'profile_pic_url', true);

    // var_dump($request_profile_pic);

    if (empty($request_profile_pic)) {
        $pic_url = $default_pic;
    } else {
        $pic_url = $request_profile_pic;
    }

    //insert profile pic, agent name, and link to individual profile page by agent number
    $img_a_end_div = '<a href="https://thejohnson.group/agent-portal/agent/profile/?agent_id=' . $agent_number . '" target="_blank"><img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300" class="person-img img-responsive wp-image-4666 lazyautosizes lazyloaded"></a></div> </div> ';

    //name, agent number, additional text, close div
    $name_and_agent_number = '<div class="person-desc"> <div class="person-author"> <div class="person-author-wrapper"><span class="person-name">'. $agent_name .'</span><span class="person-title">Agent Number: ' . $agent_number . '</span></div> </div>';
    $text_block = '<div class="person-content fusion-clearfix"> <p>Email Address: <a href="mailto:'. $email_address .'" target="_blank">'. ((!empty($email_address)) ? $email_address : 'No Email Address') .'</a><br />Phone Number: <a href="tel:'. $phone_number . '" target="_blank">'. ((!empty($phone_number)) ? $phone_number : 'No Phone Number') . '</a></p>';
    $text_addendum = '<ul><li><a href="#" target="_blank">Presentation Review</a></li> <li class="testScores">score shortcode here</li> <li><a href="#" target="_blanks">Pending Business</a></li></ul>';
    $close_divs = '</div> </div> </div>';

     
    $assembled_HTML = $opening_div . $img_a_end_div . $name_and_agent_number . $text_block . $text_addendum .  $close_divs;
    return $assembled_HTML;
}

function display_agent_hierarchy()
{

    // Check for agent_id parameter. If not provided, use the currently logged in user. If provided, use the User Object associated with that Agent Number
    if (!isset($_GET['agent_id'])) {
        $userToDisplay = get_current_user_id();
    } else {
        $query = array(
            'meta_key' => 'agent_number',
            'meta_value' => $_GET['agent_id']
        );
        $getUserObject = get_users($query);
        foreach ( $getUserObject as $user ) {
            $userToDisplay = $user->ID;
        }
    }

    //assign meta values
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
        //pull Users object by searching if this agentNumber exists in someone's Supervisor field
        $downlineQuery = array(
            'meta_key' => 'saNumber',
            'meta_value' => $agentNumber
        );
    }

    $findDownline = get_users($downlineQuery);

    //put a blank html to be filled momentarily
    $hierarchyHTML = '';

    // var_dump($findDownline);
    // print_r($findDownline);

    foreach ($findDownline as $agent) {
        $hierarchyHTML .= show_agent_profile_individual($agent);
    }

    return $hierarchyHTML;
}

add_shortcode('display_agent_hierarchy', 'display_agent_hierarchy');
