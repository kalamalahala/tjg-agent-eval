<?php

add_shortcode('wcn_appointment_time', 'get_appointment_time');

function get_appointment_time($atts)
{

    // get form ID of WCN form, get field ID of appointment time from shortcode attributes
    $vars = shortcode_atts(
        array(
            'form_id' => '',
            'wcnid_field' => '',
            'appointment_field' => ''
        ),
        $atts,
        'wcn_appointment_time'
    );

    // Check for set query parameter, otherwise return false
    if (isset($_GET['wcnid'])) {
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

    // init variable and iterate over return, should only be one item
    $WCN_datetime_SSA_Entry = '';
    foreach ($WCN_entries as $entry) {
        $WCN_datetime_SSA_Entry .= $entry[$vars['appointment_field']];
    }

    // plug returned value into SSA Appointment Object
    $WCN_Appointment = new SSA_Appointment_Object($WCN_datetime_SSA_Entry);
    // $WCN_datetime = date('l, F jS, Y g:ia', strtotime($WCN_Appointment->start_date_datetime));
    $WCN_datetime_utc = $WCN_Appointment->start_date;
    $time_zone = 'America/New_York';
    $WCN_DateTime = new DateTime($WCN_datetime_utc, new DateTimeZone($time_zone));
    $WCN_DateTime_Formatted = $WCN_DateTime->modify('-5 hours');
    $WCN_DateTime_Formatted = $WCN_DateTime->format('l, F jS, Y \a\t g:ia');




    return '<div class="dateTime"><div class="apptTimeLabel"><i class="fa-solid fa-phone fa-phone-square">&nbsp;</i>WCN Call Time<div class="apptTimeText">' . $WCN_DateTime_Formatted . '</div></div></div>';
}
