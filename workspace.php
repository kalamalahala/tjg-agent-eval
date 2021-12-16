<?


add_shortcode( 'get_quality_links', 'login_panel_display_admin_buttons' );

function login_panel_display_admin_buttons ( ) {

    $current_user = get_current_user_id( );
    $agent_position = get_user_meta( $current_user, 'agent_position', true );
    $admin_positions = array('Agency Owner', 'Quality Manager', 'Administrator');

    //html copied from avada element

    $divider_div_element = '<div class="fusion-separator fusion-has-icon" style="align-self: center;margin-left: auto;margin-right: auto;margin-top:0px;margin-bottom:0px;width:100%;max-width:60%;"><div class="fusion-separator-border sep-single sep-dotted" style="border-color:#e8ebef;border-top-width:16px;"></div><span class="icon-wrapper" style="border-color:transparent;font-size:61px;width: 1.75em; height: 1.75em;border-width:16px;padding:16px;margin-top:-8px"><i class="icon-accountant-check" style="font-size: inherit;color:#15d16c;" aria-hidden="true"></i></span><div class="fusion-separator-border sep-single sep-dotted" style="border-color:#e8ebef;border-top-width:16px;"></div></div>';
    $pending_issue = '<div style="text-align:center;"><style type="text/css">.fusion-button.button-2 {border-radius:32px;}.fusion-button.button-2.button-3d{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}.button-2.button-3d:active{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}</style><a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes " title="Pending Applications and newly written business pending issue." href="https://thejohnson.group/agent-portal/quality/" target="_blank" style="margin-bottom:20px;"><span class="fusion-button-text">Quality – WCN Forms</span></a></div>';
    $pending_business_tracker = '<div style="text-align:center;"><style type="text/css">.fusion-button.button-2 {border-radius:32px;}.fusion-button.button-2.button-3d{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}.button-2.button-3d:active{-webkit-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);-moz-box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);box-shadow: inset 0px 1px 0px #fff,0px 4px 0px #27ae5b,1px 6px 6px 3px rgba(0,0,0,0.3);}</style><a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes " title="Pending Business Tracker" href="https://thejohnson.group/agent-portal/quality-portal/pending/" target="_blank" style="margin-bottom:20px;"><span class="fusion-button-text">Pending Business Tracker</span></a></div>';
    $agent_evaluation = '<div style="text-align:center;"> <style type="text/css"> .fusion-button.button-2 { border-radius: 32px; } .fusion-button.button-2.button-3d { -webkit-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); } .button-2.button-3d:active { -webkit-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); box-shadow: inset 0px 1px 0px #fff, 0px 4px 0px #27ae5b, 1px 6px 6px 3px rgba(0, 0, 0, 0.3); } </style><a class="fusion-button button-3d button-large button-default button-2 fusion-button-span-yes " title="Agent Training" href="https://thejohnson.group/agent-portal/quality-portal/agent-training/" target="_blank" style="margin-bottom:20px;"><span class="fusion-button-text">Agent Training</span></a> </div>';

    /* Button Combinations */

    $admin_buttons = $divider_div_element . $pending_issue . $pending_business_tracker . $agent_evaluation;


    if ( in_array( $agent_position, $admin_positions, false ) ) {
        return $admin_buttons;
    }
}