<?

add_action('wp', 'verify_calendar_invite');

/**
 * Hooks into 'wp', checks for the invite id and verify parameters
 * Then updates the entry associated with the provided id
 * @return null
 */
function verify_calendar_invite() {
    if (!isset($_GET['invite_id'])) { return; }
    if (!isset($_GET['verify'])) { return; }

    $GFAPI_query = array(
        'field_filters' => array(
            'mode' => 'all',
            array('key' => 23, 'value' => $_GET['invite_id'])
        )
    );

    $entry = GFAPI::get_entries(14, $GFAPI_query);
    foreach ($entry as $invite) {
        $invite['34.1'] = 'Verified';
        try {
            GFAPI::update_entry($invite);
        } catch (Exception $e) {
            echo 'Error: ' . $e;
        }
    }
}