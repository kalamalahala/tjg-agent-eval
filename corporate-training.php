<?php

/*
* Begin shortcode
* harvest other stuff
*/

add_shortcode('display_new_agents_in_training', 'display_new_agents_in_training');
function display_new_agents_in_training($atts)
{

    $vars = shortcode_atts(array(
        'agency_name' => ''
    ), $atts, 'display_new_agents_in_training');

    // $atts is probably display mode, also form ids
    // Display list of agents in user table that have is_new_agent flag as true
    // todo: add is_new_agent to user meta column and set a couple agents to true
    // todo: update user reg form to flag all new accounts as true
    // todo: harvest display functions from pers or profiles
    // todo: display results of, and buttons to create
    //          * phase 1, 2, and 3
    //          * link to gravity view list of results from agent
    //          * create new exam phase 1/2/3
    // todo: create forms (3 forms?)

    if (!empty($vars['agency_name'])) {
        $new_agent_query = array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'is_new_agent',
                    'value' => 'true',
                    'compare' => '='
                ),
                array(
                    'key' => 'agency_name',
                    'value' => $vars['agency_name'],
                    'compare' => '='
                ),
                array(
                    'key' => 'is_dashboard_visible',
                    'value' => 'false',
                    'compare' => '!='
                )
            )
        );
    } else {
        $new_agent_query = array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'is_new_agent',
                    'value' => 'true',
                    'compare' => '='
                ),
                array(
                    'key' => 'is_dashboard_visible',
                    'value' => 'hidden',
                    'compare' => '!='
                )
            )
        );
    }

    $new_agents = get_users($new_agent_query);
    $layoutHTML = '';

    // layout functions in loops, switch case for shortcode mode

    switch ($vars['mode']) {
        case '':
            foreach ($new_agents as $agent) {
                $layoutHTML .= create_new_agent_profile_basic($agent);
            }
            break;
    }

    // return all returned HTML
    return $layoutHTML;
}


/**
 * create_new_agent_profile_basic
 * 
 * builds new agent training review block for dashboard view
 * 
 * @param object agent object passed in from primary function
 * @return string html divs to send back
 */
function create_new_agent_profile_basic($agent)
{

    //grab ID and other meta tags
    $agent_userID = $agent->ID;
    $email_address = $agent->user_email;
    $agent_number = get_user_meta($agent_userID, 'agent_number', true);
    $first_name = get_user_meta($agent_userID, 'first_name', true);
    $last_name = get_user_meta($agent_userID, 'last_name', true);
    $phone_number = get_user_meta($agent_userID, 'phone_number', true);
    $agent_position = get_user_meta($agent_userID, 'agent_position', true);
    $agent_name = $first_name . ' ' . $last_name;
    $assembled_HTML = '';

    // if ( $agent_number == '00002' ) {
    //     print('<pre style="margin-bottom:60px;">');
    //     var_dump($agent_number);
    //     print('</pre>');
    // }


    // How do we want to filter who can see the "New Agent Portal"? I think everyone should be able to see it but unsure, run it by CJ
    // // Leadership Positions for viewing New Agent Portal button
    // $leadership = ['Junior Partner, Senior Partner, Corporate Trainer, Agency Owner, ']

    //base case and admin skip
    if (is_null($agent_number) || $agent_number == '42215') {
        return false;
    }

    $phase_form_ids = array(
        'phase_1_id' => '71',
        'phase_2_id' => '73',
        'phase_3_id' => '74',
        'phase_4_id' => '76',
        'phase_5_id' => '77'
    );

    //check for profile pic, use Logo as default if no profile
    $default_pic = "https://thejohnson.group/wp-content/uploads/default.png";
    $pic_url = '';
    $request_profile_pic = get_user_meta($agent_userID, 'profile_pic_url', true);
    if (empty($request_profile_pic)) {
        $pic_url = $default_pic;
    } else {
        $pic_url = $request_profile_pic;
    }

    //HTML sections with css from Avada
    $opening_div = '<div class="person__container__wrapper"><div class="fusion-person person fusion-person-center fusion-person-icon-top"> <div class="person-shortcode-image-wrapper"> <div class="person-image-container hover-type-none dropshadow" >';
    $img_a_end_div = '<img src="' . $pic_url . '" alt="' . $agent_name . '" width="200" height="300" class="person-img img-responsive wp-image-4666 lazyautosizes lazyloaded"></div> </div> ';
    $name_and_agent_number = '<div class="person-desc"> <div class="person-author"> <div class="person-author-wrapper"><span class="person-name">' . $agent_name . '</span><span class="person-title">Agent Number: ' . $agent_number . '</span></div> </div>';
    $text_block = '<div class="person-content fusion-clearfix"> <p>Email: <a href="mailto:' . $email_address . '" target="_blank">' . ((!empty($email_address)) ? $email_address : 'No Email Address') . '</a><br />Phone Number: <a href="tel:' . $phone_number . '" target="_blank">' . ((!empty($phone_number)) ? $phone_number : 'No Phone Number') . '</a></p>';

    $phases_results_block = phase_results_block($agent_number, $first_name, $phase_form_ids);

    $close_divs = '</div> </div> </div> </div> </div>';

    //put the profile block together and return it to display_persistency_users()
    $assembled_HTML = $opening_div . $img_a_end_div . $name_and_agent_number . $text_block . $phases_results_block . $close_divs;

    return $assembled_HTML; // sends back the assembled block of information for the agent, Profile picture, with a section on Persistency and an Update Form

}
/**
 * Build the 5 column layout for the 5 quizzes, not ready to dynamically code values sorry lol
 *
 * @param int $agent_number
 * @param string $first_name
 * @param array $phase_form_ids
 * @return HTML
 */
function phase_results_block($agent_number, $first_name, $phase_form_ids)
{
    // query the 3 form ids for latest result, return the 3 blocks back
    // 'phase' -> day of the week

    $phaseOneQuery = fetch_phase_stats($agent_number, $phase_form_ids['phase_1_id'], '16');
    $phaseTwoQuery = fetch_phase_stats($agent_number, $phase_form_ids['phase_2_id'], '1');
    $phaseThreeQuery = fetch_phase_stats($agent_number, $phase_form_ids['phase_3_id'], '1');
    $phaseFourQuery = fetch_phase_stats($agent_number, $phase_form_ids['phase_4_id'], '1');
    $phaseFiveQuery = fetch_phase_stats($agent_number, $phase_form_ids['phase_5_id'], '1');

    // long form logic to display icons and such

    // Leading zeroes on agent numbers cause issues, do not have leading zeroes

    // print('<pre style="margin-bottom:60px;">');
    // var_dump($phaseOneQuery);
    // print('</pre>');

    switch ($phaseOneQuery[0][30]) {
        case 'pass':
            $phaseOneScore = '<div class="passIcon"><i class="fa fa-check-square"></i></div>';
            break;
        case 'tryagain':
            $phaseOneScore = '<div class="tryAgainIcon"><i class="fa fa-exclamation-triangle"></i></div>';
            break;
        default:
            $phaseOneScore = '<div class="tinyfont">No Data</div>';
    }

    switch ($phaseTwoQuery[0][32]) {
        case 'pass':
            $phaseTwoScore = '<div class="passIcon"><i class="fa fa-check-square"></i></div>';
            break;
        case 'tryagain':
            $phaseTwoScore = '<div class="tryAgainIcon"><i class="fa fa-exclamation-triangle"></i></div>';
            break;
        default:
            $phaseTwoScore = '<div class="tinyfont">No Data</div>';
    }

    switch ($phaseThreeQuery[0][43]) {
        case 'pass':
            $phaseThreeScore = '<div class="passIcon"><i class="fa fa-check-square"></i></div>';
            break;
        case 'tryagain':
            $phaseThreeScore = '<div class="tryAgainIcon"><i class="fa fa-exclamation-triangle"></i></div>';
            break;
        default:
            $phaseThreeScore = '<div class="tinyfont">No Data</div>';
    }

    switch ($phaseFourQuery[0][43]) {
        case 'pass':
            $phaseFourScore = '<div class="passIcon"><i class="fa fa-check-square"></i></div>';
            break;
        case 'tryagain':
            $phaseFourScore = '<div class="tryAgainIcon"><i class="fa fa-exclamation-triangle"></i></div>';
            break;
        default:
            $phaseFourScore = '<div class="tinyfont">No Data</div>';
    }

    switch ($phaseFiveQuery[0][43]) {
        case 'pass':
            $phaseFiveScore = '<div class="passIcon"><i class="fa fa-check-square"></i></div>';
            break;
        case 'tryagain':
            $phaseFiveScore = '<div class="tryAgainIcon"><i class="fa fa-exclamation-triangle"></i></div>';
            break;
        default:
            $phaseFiveScore = '<div class="tinyfont">No Data</div>';
    }

    $htmlBlock = '';

    $htmlContainerTop = '<div class="training__block"><div class="training__container"><div class="training__stats">';

    $phase_stats = '<ul>
        <li style="" class="phase__one__stats"> <div class="phase__header">Monday</div><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=one&agent_id=' . $agent_number . '" target="_self" title="Create New Exam"><div class="percentage">' . $phaseOneScore . '</div></a><a href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_one" title="View" target="_self"><button class="fusion-button button-small button-default button-override button-2">View</button></a><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=one&agent_id=' . $agent_number . '" title="Create" target="_self"><button class="fusion-button button-small button-default button-2 button-override">Create</button></a></li>
        <li style="" class="phase__two__stats">  <div class="phase__header">Tuesday</div><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=two&agent_id=' . $agent_number . '" target="_self" title="Create New Exam"><div class="percentage">' . $phaseTwoScore . '</div></a><a href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_two" title="View" target="_self"><button class="fusion-button button-small button-default button-override button-2">View</button></a><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=two&agent_id=' . $agent_number . '" title="Create" target="_self"><button class="fusion-button button-small button-default button-2 button-override">Create</button></a></li>
        <li style="" class="phase__three__stats"><div class="phase__header">Wednesday</div><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=three&agent_id=' . $agent_number . '" target="_self" title="Create New Exam"><div class="percentage">' . $phaseThreeScore . '</div></a><a href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_three" title="View" target="_self"><button class="fusion-button button-small button-default button-override button-2">View</button></a><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=three&agent_id=' . $agent_number . '" title="Create" target="_self"><button class="fusion-button button-small button-default button-2 button-override">Create</button></a></li>
        <li style="" class="phase__four__stats"><div class="phase__header">Thursday</div><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=four&agent_id=' . $agent_number . '" target="_self" title="Create New Exam"><div class="percentage">' . $phaseFourScore . '</div></a><a href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_four" title="View" target="_self"><button class="fusion-button button-small button-default button-override button-2">View</button></a><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=four&agent_id=' . $agent_number . '" title="Create" target="_self"><button class="fusion-button button-small button-default button-2 button-override">Create</button></a></li>
        <li style="" class="phase__five__stats"><div class="phase__header">Friday</div><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=five&agent_id=' . $agent_number . '" target="_self" title="Create New Exam"><div class="percentage">' . $phaseFiveScore . '</div></a><a href="https://thejohnson.group/agent-portal/corporate-training/completed/?agent_id=' . $agent_number . '&mode=phase_five" title="View" target="_self"><button class="fusion-button button-small button-default button-override button-2">View</button></a><a href="https://thejohnson.group/agent-portal/corporate-training/exam/?phase=five&agent_id=' . $agent_number . '" title="Create" target="_self"><button class="fusion-button button-small button-default button-2 button-override">Create</button></a></li>
        </ul>';

    $recording_submission_button = '<div style="text-align:center;">
        <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
            title="' . $first_name . '\'s recording submissions" href="https://thejohnson.group/agent-portal/corporate-training/uploads/?agent_id=' . $agent_number . '"
            target="_self"><span class="fusion-button-text">View Recording Submissions</span></a>
    </div>';

    // check commit lol, moved div tag (line 181) because I couldn't figure out why it wasn't working

    $view_presentation_inspections = '<div style="text-align:center; margin-top: 10px;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="' . $first_name . '\'s Presentation Inspection Results" href="https://thejohnson.group/agent-portal/quality-portal/agent-training/presentation-inspection/?agent_id=' . $agent_number . '&mode=view"
        target="_self"><span class="fusion-button-text">View Presentation Performance</span></a>
</div></div>';

    // this button was intended to link to the presentation training dashboard, AJ's view. no need to render in each block, 
    // go create one manually in avada, change functionality of presentation dashboard rendering
    // to render all users with new agent flag

    // $presentation_training_dashboard = '<div style="text-align:center;">
    // <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
    //     title="'. $first_name .'\'s recording submissions" href="https://thejohnson.group/agent-portal/corporate-training/uploads/?agent_id='. $agent_number .'"
    //     target="_self"><span class="fusion-button-text">View Recording Submissions</span></a>
    // </div>
    // </div>';


    $htmlContainerBottom = '</div>';

    $htmlBlock = $htmlContainerTop . $phase_stats . $recording_submission_button . $view_presentation_inspections . $htmlContainerBottom;

    // if ( !empty($phaseOneQuery) ) {
    //     print ('<pre style="magin-bottom:60px;">');
    //     var_dump($phaseOneQuery);
    //     print('</pre>');
    // }

    return $htmlBlock;
}

function fetch_phase_stats($agentNumber, $form_id, $field_id)
{
    // build API Query for form results
    $GFAPI_query = array(
        'field_filters' => array(
            'mode' => 'all',
            array('key' => $field_id, 'value' => $agentNumber)
        )
    );

    $entries = GFAPI::get_entries($form_id, $GFAPI_query);

    return $entries;
}
