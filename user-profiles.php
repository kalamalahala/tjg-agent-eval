<?


function show_agent_profile_individual($agent_object)
{
    $user_id = $agent_object->ID;
    $agent_number = get_user_meta($user_id, 'agent_number', true);
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $agent_name = $first_name . ' ' . $last_name;

    //html block from Avada builder. Does "fusion-person-n" need to be counted and updated? or can they all be the same class?
    $opening_div = '<div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" style="border:5px solid #e2e2e2;-webkit-box-shadow: 3px 3px 7px rgba(0,0,0,0.3);box-shadow: 3px 3px 7px rgba(0,0,0,0.3);-webkit-border-radius:0px;-moz-border-radius:0px;border-radius:0px;">';

    //check for profile pic, use Logo as default if no profile
    $default_pic = "https://thejohnson.group/wp-content/uploads/2021/02/BlackTextLogo.png";
    $pic_url = '';
    $request_profile_pic = get_user_meta($user_id, 'profile_pic_url', true);

    if (isset($request_profile_pic)) {
        $pic_url = $request_profile_pic;
    } else {
        $pic_url = $default_pic;
    }

    //insert profile pic, agent name, and link to individual profile page by agent number
    $img_a_end_div = '<a href="https://thejohnson.group/agent-portal/agent/profile/?agent_id=' . $agent_number . '" target="_blank"><img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300"></a></div> </div> ';

    $assembled_HTML = $opening_div . $img_a_end_div;
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
        $userToDisplay = get_users($query);
    }

    //assign meta values
    $agentNumber = get_user_meta($userToDisplay, 'agent_number', true);

    //pull Users object by searching if this agentNumber exists in someone's Supervisor field
    $downlineQuery = array(
        'meta_key' => 'saNumber',
        'meta_value' => $agentNumber
    );
    $findDownline = get_users($downlineQuery);

    //put a blank html to be filled momentarily
    $hierarchyHTML = '<div>Info for agent ' . $agentNumber . '.</div>';

    // var_dump($findDownline);
    // print_r($findDownline);

    foreach ($findDownline as $agent) {
        $hierarchyHTML .= show_agent_profile_individual($agent);
    }

    return $hierarchyHTML;
}

add_shortcode('display_agent_hierarchy', 'display_agent_hierarchy');
