<?php

    // Redirect /fashion/ to P750K Join.
    // if Referring Agent and Referral Code are set, pass them to the URL
if (is_page('17123')) {
    $ra = (isset($_GET['ra'])) ? $_GET['ra'] : '';
    $rc = (isset($_GET['rc'])) ? $_GET['rc'] : '';
    $params = (!empty($ra) && !empty($rc)) ? '?ra=' . $ra . '&rc=' . $rc : '';

    header('Location: https://thejohnson.group/p750k/join/' . $params);
}

?>