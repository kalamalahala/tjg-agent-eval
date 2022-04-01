<?

add_action('gform_after_submission', 'set_entry_to_Submitted', 10, 2);
function set_entry_to_Submitted($entry, $form) {
    // exit out for base case if not correct form
    if ($form['id'] != '75') {
        $error_value = GFAPI::log_debug('Not form 75, so not adjusting any field values here.');
        return false;
    }
    // get Unique ID from submission, field 15
    $assignment_id = rgar($entry, '15');

    // here's the 5 forms to query for matching $assignment_id
    // we want to find the entry that was created and has this unique ID, then adjust its Radio button for status to Submitted
    $phase_form_ids = array('71', '73', '74', '76', '77');


    // matching field values for each form
    $assignment_status_field_ids = array(
        '71' => '39', 
        '73' => '36', 
        '74' => '45', 
        '76' => '45', 
        '77' => '45'
    );

    $entry_id = '';
    $input_id = '';
    $assignment_status = '';
    
    $gfAPIquery['field_filters'][] = array(
        'value' => $assignment_id
    );

    $assignment_entry = GFAPI::get_entries($phase_form_ids, $gfAPIquery);

    foreach ($assignment_entry as $assignment) {
        $entry_id = $assignment->id;
        $form_id = $assignment->form_id;
        $input_id = $assignment_status_field_ids[$form_id];
        $assignment_status = $assignment[0][$input_id];

        if ($assignment_status == 'Pending') { 
            // change_entry_to_Submitted($entry_id, $input_id, 'Submitted');
            $assignment[0][$input_id] = 'Submitted';
            $result = GFAPI::update_entry($assignment);
            if (!$result) {
                GFAPI::log_debug('Update failed');
                GFAPI::log_debug($entry_id);
                GFAPI::log_debug($form_id);
                GFAPI::log_debug($input_id);
                GFAPI::log_debug($assignment_status);
                return false;
            }
        }
    }
    

}

function change_entry_to_Submitted($entry_id, $input_id, $value) {
    $entry_change = GFAPI::update_entry_field($entry_id, $input_id, $value);
    if (!$entry_change) { return false; }    
}

?>