<?

/*

todo: assign new meta key bool "is_persistency"
todo: copy user-profiles.php template style and change to search for is_persistency only
todo: remove extra layout stuff from new template
todo: [gravityform id="" title="false" description="false" ajax="true" tabindex="" field_values=""]
todo: id
todo: field_values
todo: build form assign values
todo: test single via preview
todo: test multiple via layout

*/

/*
    Begin Shortcode
*/

function display_persistency_users()
{

    // Select all users where saNumber = $agentNumber
    $persistency = array(
        'meta_key' => 'is_persistency',
        'meta_value' => 'true'
    );

    // ask for an array of Users, begin empty string
    $get_persistency_agents = get_users($persistency);
    $hierarchyHTML = '';

    // run the profile builder for each User in the array
    foreach ($get_persistency_agents as $agent) {
        $hierarchyHTML .= add_agent_profile_element($agent);
    }

    // Display all returned HTML
    return $hierarchyHTML;
}

add_shortcode('display_persistency_users', 'display_persistency_users');

/*

    add_agent_profile_element

*/


/**
 * add_agent_profile_element
 * 
 * Gets meta tags for a given WP User Object, assigns a default profile picture, then builds an html block.
 *
 * @param object $agent_object created via get_users()
 * @return string HTML Block to display in parent function
 */

function add_agent_profile_element($agent_object)
{
    //grab ID and email from argument object, grab the rest from meta tags, assemble First and Last name
    $user_id = $agent_object->ID;
    $email_address = $agent_object->user_email;
    $agent_number = get_user_meta($user_id, 'agent_number', true);
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $phone_number = get_user_meta($user_id, 'phone_number', true);
    $agent_position = get_user_meta($user_id, 'agent_position', true);
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

    //HTML sections with css from Avada
    $opening_div = '<div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" >';
    $img_a_end_div = '<a href="#"><img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300" class="person-img img-responsive wp-image-4666 lazyautosizes lazyloaded"></a></div> </div> ';
    $name_and_agent_number = '<div class="person-desc"> <div class="person-author"> <div class="person-author-wrapper"><span class="person-name">' . $agent_name . '</span><span class="person-title">Agent Number: ' . $agent_number . '</span></div> </div>';
    $text_block = '<div class="person-content fusion-clearfix"> <p>Email: <a href="mailto:' . $email_address . '" target="_blank">' . ((!empty($email_address)) ? $email_address : 'No Email Address') . '</a><br />Phone Number: <a href="tel:' . $phone_number . '" target="_blank">' . ((!empty($phone_number)) ? $phone_number : 'No Phone Number') . '</a></p>';
    
    $persistency_block = generate_persistency_block( $agent_number, $first_name );

    
    $close_divs = '</div> </div> </div>';

    //put the profile block together and return it to display_persistency_users()
    $assembled_HTML = $opening_div . $img_a_end_div . $name_and_agent_number . $text_block . $persistency_block . $close_divs;

    return $assembled_HTML; // sends back the assembled block of information for the agent, Profile picture, with a section on Persistency and an Update Form
}


/**
 * generate_persistency_block
 *
 * @param string $agent_number The agent number to build into field_value gravity form shortcode.
 * @return void
 */

function generate_persistency_block( $agent_number, $first_name ) {
    $htmlContainerTop = '<div class="persistency__block">';
    // $gravityFormsShortcode = do_shortcode('[gravityform id="68" title="false" description="false" ajax="false" tabindex="" field_values="persistency_agent_number=' . $agent_number . '"]');
    $gravityFormsShortcode = '<div class="persistency__container">
    <div style="text-align:center;">
        <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
            title="Update '. $first_name .'\'s Persistency" href="https://thejohnson.group/agent-portal/quality-portal/persistency-tracker/?agent_id=' . $agent_number . '"
            target="_self"><span class="fusion-button-text">Update</span></a>
    </div>
</div>';
    $htmlContainerBottom = '</div>';

    return $htmlContainerTop . $gravityFormsShortcode . $htmlContainerBottom;
} 


?>