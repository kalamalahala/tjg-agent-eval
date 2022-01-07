<?

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
    $opening_div = '<div class="person__container__wrapper"><div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" >';
    $img_a_end_div = '<a href="#"><img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300" class="person-img img-responsive wp-image-4666 lazyautosizes lazyloaded"></a></div> </div> ';
    $name_and_agent_number = '<div class="person-desc"> <div class="person-author"> <div class="person-author-wrapper"><span class="person-name">' . $agent_name . '</span><span class="person-title">Agent Number: ' . $agent_number . '</span></div> </div>';
    $text_block = '<div class="person-content fusion-clearfix"> <p>Email: <a href="mailto:' . $email_address . '" target="_blank">' . ((!empty($email_address)) ? $email_address : 'No Email Address') . '</a><br />Phone Number: <a href="tel:' . $phone_number . '" target="_blank">' . ((!empty($phone_number)) ? $phone_number : 'No Phone Number') . '</a></p>';
    
    $persistency_block = generate_persistency_block( $agent_number, $first_name );

    
    $close_divs = '</div> </div> </div> </div> </div>';

    //put the profile block together and return it to display_persistency_users()
    $assembled_HTML = $opening_div . $img_a_end_div . $name_and_agent_number . $text_block . $persistency_block . $close_divs;

    return $assembled_HTML; // sends back the assembled block of information for the agent, Profile picture, with a section on Persistency and an Update Form
}


/**
 * generate_persistency_block
 *
 * @param string $agent_number The agent number for /update/ parameter
 * @param string $first_name The agent's first name, for the anchor title
 * @return string HTML Output
 */

function generate_persistency_block( $agent_number, $first_name ) {

    // get the current month window as string
    $lastMonth = date('F', strtotime('-1 month'));
    $thisMonth = date('F', strtotime('today'));
    $nextMonth = date('F', strtotime('+1 month'));

    // use fetch_persistency_stats to get the most recent entry of each month and return that entry's result as an array

    $persLastMonthQuery = fetch_persistency_stats( $agent_number, $lastMonth );
    $persThisMonthQuery = fetch_persistency_stats( $agent_number, $thisMonth );
    $persNextMonthQuery = fetch_persistency_stats( $agent_number, $nextMonth );

    // print('<p margin-bottom:20px;><h1>');
    // print($persThisMonthQuery[0][8]);
    // print($persNextMonthQuery[0][8]);
    // print($persLastMonthQuery[0][8]);
    // print('</h1></p>');













    // //make query for get_entries, field 1
    // $persistencyGFAPIQuery['field_filters'][] = array(
    //     'key' => '1',
    //     'value' => $agent_number
    // );

    // $persSubmissions = GFAPI::get_entries( '68', $persistencyGFAPIQuery );

    // $persPercentages = array();
    // foreach ( $persSubmissions as $entry ) {
    //     $persPercentages[] = $entry[8];
    // }

    // $agent_Persistency = round($persPercentages[0]);
    // print('<pre>');
    // // var_dump($persSubmissions);
    // var_dump($persPercentages);
    // print('</pre>');
    // foreach ($persPercentages as $key => $value) {

    //     print('<h1>'. $persPercentages['6'] .'</h1>');
    // }
    /*
    * 1: agent number
    * 2: name 2.3 and 2.6
    * 3: submit
    * 4: lapse
    * 5: Net
    * 8: Percentage
    * 9: Month of Report
    */

    $htmlContainerTop = '<div class="persistency__block"><div class="persistency__container"><div class="persistency__stats">';
    
    $persistency_stats = '<ul>
        <li style="' . 'background-color: ' . ((!is_null($persLastMonthQuery[0][8]) ? numberToColorHsl(round($persLastMonthQuery[0][8]), 65, 100) : "")) . '" class="persistency__stat"><div class="month__header">' . $lastMonth . '</div><div class="percentage">' . ((is_null($persLastMonthQuery[0][8])) ? '<span class="tinyfont">No Data</span>' : '<span>' . round($persLastMonthQuery[0][8]) . '%') . '</div></li>
        <li style="' . 'background-color: ' . ((!is_null($persThisMonthQuery[0][8]) ? numberToColorHsl(round($persThisMonthQuery[0][8]), 65, 100) : "")) . '"class="persistency__stat"><div class="month__header">' . $thisMonth . '</div><div class="percentage">' . ((is_null($persThisMonthQuery[0][8])) ? '<span class="tinyfont">No Data</span>' : round($persThisMonthQuery[0][8]) . '%') . '</li>
        <li style="' . 'background-color: ' . ((!is_null($persNextMonthQuery[0][8]) ? numberToColorHsl(round($persNextMonthQuery[0][8]), 65, 100) : "")) . '"class="persistency__stat"><div class="month__header">' . $nextMonth . '</div><div class="percentage">' . ((is_null($persNextMonthQuery[0][8])) ? '<span class="tinyfont">No Data</span>' : round($persNextMonthQuery[0][8]) . '%') . '</li>
    </ul>';
    
    $update_button = '<div style="text-align:center;">
        <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
            title="Update '. $first_name .'\'s Persistency" href="https://thejohnson.group/agent-portal/quality-portal/persistency-tracker/update/?agent_id=' . $agent_number . '"
            target="_self"><span class="fusion-button-text">Update</span></a>
    </div>
</div>';
    $htmlContainerBottom = '</div>';

    return $htmlContainerTop . $persistency_stats . $update_button . $htmlContainerBottom;
} 

    /**
     * Search Gravity Forms GFAPI for the Month window and return an array
     *
     * @param string $arg_agent_id
     * @param string $arg_month
     * @return array query results
     * 
     * - [1]: agent number
     * - [2]: name 2.3 and 2.6
     * - [3]: submit
     * - [4]: lapse
     * - [5]: Net
     * - [8]: Percentage
     * - [9]: Month of Report
     * 
     */

function fetch_persistency_stats ( $arg_agent_id, $arg_month ) {

    // Match All: Agent Number and Month
    $GFAPIQuery = array(
        'field_filters' => array( 
            'mode' => 'all',
            array( 'key' => '1', 'value' => $arg_agent_id ),
            array( 'key' => '9', 'value' => $arg_month )            
        )
    );

    $entries = GFAPI::get_entries(68, $GFAPIQuery);

    return $entries;
}


/**
 * github stuff
 */

function hslToRgb($h, $s, $l){
    #    var r, g, b;
        if($s == 0){
            $r = $g = $b = $l; // achromatic
        }else{
            if($l < 0.5){
                $q =$l * (1 + $s);
            } else {
                $q =$l + $s - $l * $s;
            }
            $p = 2 * $l - $q;
            $r = hue2rgb($p, $q, $h + 1/3);
            $g = hue2rgb($p, $q, $h);
            $b = hue2rgb($p, $q, $h - 1/3);
        }
        $return=array(floor($r * 255), floor($g * 255), floor($b * 255));
        return $return;
    }
    
    function hue2rgb($p, $q, $t){
        if($t < 0) { $t++; }
        if($t > 1) { $t--; }
        if($t < 1/6) { return $p + ($q - $p) * 6 * $t; }
        if($t < 1/2) { return $q; }
        if($t < 2/3) { return $p + ($q - $p) * (2/3 - $t) * 6; }
        return $p;
    }
    /**
     * Convert a number to a color using hsl, with range definition.
     * Example: if min/max are 0/1, and i is 0.75, the color is closer to green.
     * Example: if min/max are 0.5/1, and i is 0.75, the color is in the middle between red and green.
     * @param i (floating point, range 0 to 1)
     * param min (floating point, range 0 to 1, all i at and below this is red)
     * param max (floating point, range 0 to 1, all i at and above this is green)
     */
    function numberToColorHsl($i, $min, $max) {
        $ratio = $i;
        if ($min> 0 || $max < 1) {
            if ($i < $min) {
                $ratio = 0;
            } elseif ($i > $max) {
                $ratio = 1;
            } else {
                $range = $max - $min;
                $ratio = ($i-$min) / $range;
            }
        }
        // as the function expects a value between 0 and 1, and red = 0° and green = 120°
        // we convert the input to the appropriate hue value
        $hue = $ratio * 1.2 / 3.60;
        //if (minMaxFactor!=1) hue /= minMaxFactor;
        //console.log(hue);
    
        // we convert hsl to rgb (saturation 100%, lightness 50%)
        $rgb = hslToRgb($hue, 0.95, .75);
        // we format to css value and return
        return 'rgb('.$rgb[0].','.$rgb[1].','.$rgb[2].',0.7)'; 
    }