<?php

add_shortcode('wcn_appointment_time', 'get_appointment_time');

function get_appointment_time( $atts ) {

    // get form ID of WCN form, get field ID of appointment time from shortcode attributes
    $vars = shortcode_atts(
        array(
            'form_id' => '',
            'wcnid_field' => '',
            'appointment_field' => ''
        ), $atts, 'wcn_appointment_time'
    );
    
    // Check for set query parameter, otherwise return false
    if ( isset($_GET['wcnid']) ) {
        $WCNID = $_GET['wcnid'];
    } else {
        return false;
    }

    // construct query using shortcode atts and wcnid query parameter
    $GFAPIQuery = array(
        'field_filters' => array(
            'mode' => 'all',
            array(
                'key' => $vars['wcnid_field'],
                'value' => $WCNID
            )
        )
    );

    // query entries for form id provided by shortcode atts
    $WCN_entries = GFAPI::get_entries($vars['form_id'], $GFAPIQuery);

    // VARDUMP to find the correct form field
    // print('<pre style="margin-bottom: 60px;>');
    // var_dump($WCN_entries);
    // print('</pre>');

    $WCN_datetime = '';

    // iterate over return, should only be one item
    foreach ($WCN_entries as $entry) {
        $WCN_datetime .= $entry[$vars['appointment_field']];
    }

    return $WCN_datetime . '<p>Still working on it!</p>';
}

?>