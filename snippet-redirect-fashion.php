<?php

function fashion_redirect() {
    // Redirect /fashion/ to P750K Join.
    // if Referring Agent and Referral Code are set, pass them to the URL
    if (is_page('17123')) {
        $ra = (isset($_GET['ra'])) ? $_GET['ra'] : '';
        $rc = (isset($_GET['rc'])) ? $_GET['rc'] : '';
        $default_params = '?ra=43740&rc=FSH040722';
        $params = (!empty($ra) && !empty($rc)) ? '?ra=' . $ra . '&rc=' . $rc : $default_params;
    
        header('Location: https://thejohnson.group/p750k/join/' . $params);
    }

}
add_action('wp_head', 'fashion_redirect');

?>