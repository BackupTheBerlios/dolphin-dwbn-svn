<?php
/**
*                            Orca Interactive Forum Script
*                              ---------------
*     Started             : Mon Mar 23 2006
*     Copyright           : (C) 2007 BoonEx Group
*     Website             : http://www.boonex.com
* This file is part of Orca - Interactive Forum Script
* Creative Commons Attribution 3.0 License
**/

/**
 *
 * Modify call to orca.php url here
 * put complete url to $integration_file variable
 * leave $integration_file variable emty if user is not logged in
 *******************************************************************************/

    global $gConf;

    $add_to_url = '';
    $add_to_array = array();

    if ($_COOKIE['adminID'])
    {
        $add_to_url = "&adminID=" . $_COOKIE['adminID'] . "&adminPassword=" . $_COOKIE['adminPassword'];
        $add_to_array = array ('adminID' => $_COOKIE['adminID'], 'adminPassword' => $_COOKIE['adminPassword']);
    }
    else
    if ($_COOKIE['memberID'])
    {
        $add_to_url = "&memberID=" . $_COOKIE['memberID'] . "&memberPassword=" . $_COOKIE['memberPassword']; 
        $add_to_array = array ('memberID' => $_COOKIE['memberID'], 'memberPassword' => $_COOKIE['memberPassword']);
    }    

    switch ($action)
    {
    case 'login_user':

        if (!empty($add_to_url) || !empty($add_to_array))
        {
            if ('url' == $gConf['integration'])
            {
                $integration_file = $gConf['url']['integration'] . '?action=' . $action . $add_to_url;
            }
            else
            {
                $integration_file['file'] = $gConf['dir']['integration'];
                $integration_file['vars'] = array_merge (array ('action' => $action), $add_to_array);
            }
        }
        break;
    default:        
        if ('url' == $gConf['integration'])
        {        
            $integration_file = $gConf['url']['integration'] . '?action=' . $action .  '&user=' . urlencode($user) . $add_to_url;
        }
        else
        {
            $integration_file['file'] = $gConf['dir']['integration'];
            $integration_file['vars'] = array_merge (array ('action' => $action, 'user' => $user), $add_to_array);
        }        
    }

?>
