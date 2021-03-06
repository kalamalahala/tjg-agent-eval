<?

// this is the shortcode that displays buttons and separators based on role and attribute passed
add_shortcode( 'get_quick_links', 'login_panel_display_admin_buttons' );

function login_panel_display_admin_buttons ( $atts ) {

    $atts = shortcode_atts( array( 
        'mode' => ''
    ), $atts, 'get_quick_links');

    $current_user = get_current_user_id( );
    $agent_position = get_user_meta( $current_user, 'agent_position', true );
    $is_new_agent = get_user_meta( $current_user, 'is_new_agent', true );
    $agent_number = get_user_meta( $current_user, 'agent_number', true );
    $admin_positions = array('Agency Owner', 'Quality Manager', 'Administrator', 'Senior Partner', 'Junior Partner');

    //html copied from avada element

    $div_flex_box_opener = '<div class="divFlex">';
    $div_flex_box_closer = '</div>';
    
    $divider_div_element = '<div class="fusion-separator fusion-has-icon" style="align-self: center;margin-left: auto;margin-right: auto;margin-top:0px;margin-bottom:0px;width:100%;max-width:60%;"><div class="fusion-separator-border sep-single sep-dotted" style="border-color:#e8ebef;border-top-width:16px;"></div><span class="icon-wrapper" style="border-color:transparent;font-size:61px;width: 1.75em; height: 1.75em;border-width:16px;padding:16px;margin-top:-8px"><i class="icon-accountant-check" style="font-size: inherit;color:#15d16c;" aria-hidden="true"></i></span><div class="fusion-separator-border sep-single sep-dotted" style="border-color:#e8ebef;border-top-width:16px;"></div></div><div class="separatorSubTitle">Leadership & Quality Links</div>';
    $divider_div_2 = '<div class="fusion-separator fusion-has-icon" style="align-self: center;margin-left: auto;margin-right: auto;margin-top:0px;margin-bottom:0px;width:100%;max-width:60%;"><div class="fusion-separator-border sep-single sep-dotted" style="border-color:#e8ebef;border-top-width:16px;"></div><span class="icon-wrapper" style="border-color:transparent;font-size:61px;width: 1.75em; height: 1.75em;border-width:16px;padding:16px;margin-top:-8px"><i class="icon-accountant-customers" style="font-size: inherit;color:#15d16c;" aria-hidden="true"></i></span><div class="fusion-separator-border sep-single sep-dotted" style="border-color:#e8ebef;border-top-width:16px;"></div></div><div class="separatorSubTitle">Agent Quick Links</div>';
    $pending_issue = '<div style="text-align:center;"><style type="text/css">.fusion-button.button-2 {border-radius:32px;}.fusion-button.button-2.button-3d{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}.button-2.button-3d:active{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}</style><a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes " title="Pending Applications and newly written business pending issue." href="https://thejohnson.group/agent-portal/quality/" target="_blank" style="margin-bottom:20px;"><span class="fusion-button-text">Quality ??? Pending WCN Forms</span></a></div>';
    $pending_business_tracker_manager = '<div style="text-align:center;"><style type="text/css">.fusion-button.button-2 {border-radius:32px;}.fusion-button.button-2.button-3d{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}.button-2.button-3d:active{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}</style><a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes " title="Pending Business Tracker" href="https://thejohnson.group/agent-portal/quality-portal/pending/" target="_blank" style="margin-bottom:20px;"><span class="fusion-button-text">Pending Business Manager</span></a></div>';
    $agent_evaluation = '<div style="text-align:center;"> <style type="text/css"> .fusion-button.button-2 { border-radius: 32px; } .fusion-button.button-2.button-3d { -webkit-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); } .button-2.button-3d:active { -webkit-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); } </style><a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes " title="Agent Training" href="https://thejohnson.group/agent-portal/quality-portal/agent-training/" target="_blank" style="margin-bottom:20px;"><span class="fusion-button-text">New Agent Training - Your Team</span></a> </div>';

    $corporate_training_dashboard = '<div style="text-align:center;"><style type="text/css"> .fusion-button.button-2 { border-radius: 32px; } .fusion-button.button-2.button-3d { -webkit-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); } .button-2.button-3d:active { -webkit-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); } </style><a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes " title="Corporate Training Dashboard" href="https://thejohnson.group/agent-portal/corporate-training/" target="_blank" style="margin-bottom:20px;"><span class="fusion-button-text">Corporate Training Dashboard</span></a> </div>';

    $pending_business_tracker = '<div class="pending_business_tracker"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="Your Pending Business"
        href="https://thejohnson.group/agent-portal/agent/pending-business/" target="_blank" style="margin-bottom:20px;"><span
            class="fusion-button-text">Pending Business Tracker</span></a>
</div></div>';
    $persistency_tracker = '<div class="persistency_tracker"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="Persistency Tracker"
        href="https://thejohnson.group/agent-portal/quality-portal/persistency-tracker/" target="_blank" style="margin-bottom:20px;"><span
            class="fusion-button-text">Persistency Tracker</span></a>
    </div></div>';
    $cal_invites = '<div class="cal_invites"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="View your submitted Calendar Invites"
        href="https://thejohnson.group/agent-portal/agent/reports/calendar-invites/" target="_self" style="margin-bottom:20px;"><span
            class="fusion-button-text">View Your <strong>Calendar Invites</strong></span></a>
</div></div>';
    $wcn_report = '<div class="wcn_report"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="View Your WCN Form Submissions"
        href="#" target="_self" style="margin-bottom:20px;"><span
            class="fusion-button-text">View Your <strong>WCN Form Submissions</strong></span></a>
</div></div>';

    $new_agent_portal = '<div class="new_agent_portal"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="Go to New Agent Portal"
        href="https://thejohnson.group/agent-portal/new-agent/?agent_id='. $agent_number .'" target="_self" style="margin-bottom:20px;"><span
            class="fusion-button-text">New Agent Portal</span></a>
</div></div>';

    $invite_verification = '<div class="invite_verification"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="Verify Calendar Invites"
        href="https://thejohnson.group/agent-portal/agent/reports/calendar-invites/verify/" target="_self" style="margin-bottom:20px;"><span
            class="fusion-button-text">Verify Calendar Invites</span></a>
</div></div>';

    $recruiting_portal = '<div class="recruiting_portal"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="Verify Calendar Invites"
        href="https://thejohnson.group/agent-portal/recruiting/" target="_self" style="margin-bottom:20px;"><span
            class="fusion-button-text">Recruiting Portal</span></a>
</div></div>';

    $regular_agent_portal = '<div class="recruiting_portal"><div style="text-align:center;">
    <a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes"
        title="Agent Portal"
        href="https://thejohnson.group/agent-portal/agent/" target="_self" style="margin-bottom:20px;"><span
            class="fusion-button-text">Agent Portal</span></a>
</div></div>';

    /* Button Combinations */

    // let's reorganize the button stack to be more apparent

    $admin_buttons = $divider_div_element . $pending_issue . $pending_business_tracker_manager . $persistency_tracker . $agent_evaluation . $corporate_training_dashboard . $invite_verification;
    $agent_buttons = $divider_div_2 . (($is_new_agent == 'true') ? $new_agent_portal : $regular_agent_portal ) . $pending_business_tracker . $cal_invites . $wcn_report;
    $recruiting_buttons = $divider_div_2 . $recruiting_portal;
    $corporate_trainer_buttons = $divider_div_2 . $agent_evaluation . $corporate_training_dashboard . $new_agent_portal;
    
    if ( empty($atts['mode']) ) {   
        if ( in_array( $agent_position, $admin_positions, false ) ) {
            return $div_flex_box_opener . $admin_buttons . $agent_buttons . $corporate_trainer_buttons . $div_flex_box_closer;
        } else if ( $agent_position == 'Agent' ) {
            return $div_flex_box_opener . $agent_buttons . $div_flex_box_closer;
        } else if ( $agent_position == 'Corporate Trainer' ) {
            return $div_flex_box_opener . $corporate_trainer_buttons . $div_flex_box_closer;
        } else if ( $agent_position == 'Director of Recruiting' ) {
            return $div_flex_box_opener . $recruiting_buttons . $div_flex_box_closer;
        } else {
            return null;
        }
    } elseif ( $atts['mode'] == 'newagent' ) {
        return $div_flex_box_opener . $divider_div_2 . $new_agent_portal . $div_flex_box_closer;
    }

}