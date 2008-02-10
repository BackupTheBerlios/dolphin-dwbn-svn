<?php
/**
*                            Orca Interactive Forum Script
*                              ---------------
*     Started             : Mon Mar 23 2006
*     Copyright           : (C) 2007 BoonEx Group
*     Website             : http://www.boonex.com
* This file is part of Orca - Interactive Forum Script
* GPL
**/

/**
 *
 * Modify call to orca.php url here
 * put complete url to $integration_url variable
 * leave $integration_url variable emty if user is not logged in
 *******************************************************************************/

     global $gConf;

    if ($_COOKIE['adminID'])
        $add_to_url = "&adminID=" . $_COOKIE['adminID'] . "&adminPassword=" . $_COOKIE['adminPassword'];
    else
    if ($_COOKIE['memberID'])
        $add_to_url = "&memberID=" . $_COOKIE['memberID'] . "&memberPassword=" . $_COOKIE['memberPassword']; 

    switch ($action)
    {
    case 'login_user':        
        $integration_url = $gConf['url']['xml'] . "?action=$action" . $add_to_url;
        break;
    default:
        $integration_url = $gConf['url']['xml'] . "?action=$action&user=" . urlencode($user) . $add_to_url;
    }

?>
