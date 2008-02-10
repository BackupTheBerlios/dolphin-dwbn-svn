<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );


// --------------- page variables and login

$_page['name_index'] 	= 25;
$_page['css_name']		= 'profile_edit.css';
$_page['js'] = 1;

$ADMIN = member_auth( 1, false );
$logged['admin'] = $ADMIN;

// Check if moderator logged in.
$moderator = member_auth(3, false);
$logged['moderator'] = $moderator;
// Give moderator all admin rights for this page.
$ADMIN = $ADMIN || $moderator;
//

if ( !$ADMIN )
$logged['member'] = member_auth( 0 );


// --------------- GET/POST actions

$enable_match = getParam("enable_match") == "on" ? 1 : 0;
$enable_match = (int)$enable_match;

$votes_on = $votes;
$autoApproval_ifProfile = isAutoApproval('profile');

$enable_ray = (getParam( 'enable_ray' ) == 'on');

$enable_ray_pro = (getParam( 'enable_ray_pro' ) == 'on');

//---------------------------------

$ID = getID( $_GET['ID'] );

if ( $_COOKIE['memberID'] == $ID && !$ADMIN )
{
	$member['ID']        = (int)$_COOKIE['memberID'];
	$member['Password']    = $_COOKIE['memberPassword'];
	if ( check_login( $member['ID'], $member['Password'], 'Profiles', false ) )
	$MEMBER = true;
}

// check for access
//if ( (!$ID && $MEMBER) && !$ADMIN )
if ( (!$ID || $ID != $member['ID']) && !$ADMIN )
{
	$_page['header'] = "$site[title] "._t("_Member Profile");
	$_page['header_text'] = _t("_View profile");
	$_page['name_index'] = 0;

	$_page_cont[0]['page_main_code'] = _t_err("_Member Profile NA for view").'<br /><br />';
	PageCode();
	exit;
}

// check for existing profile
if ( $ID )
{
	$p_arr = getProfileInfo( $ID );
	
    if ( !$p_arr )
    {
	    $_page['header'] = "$site[title] "._t("_Member Profile");
	    $_page['header_text'] = "$site[title] "._t("_Member Profile");
	    $_page['name_index'] = 0;

		$_page_cont[0]['page_main_code'] = _t_err("_Profile NA").'<br />aa<br />';
	    PageCode();
	    exit();
    }
}

// database field visibility

$db_vsbl = 1;
$db_editbl = 0;
if ( $MEMBER )
{
	$db_vsbl = 2;
	$db_editbl = 1;
}
elseif ( $ADMIN )
{
	$db_vsbl = 4;
	$db_editbl = 2;
}

// check for a new added profile
if ( !$ID && $ADMIN )
$NEW_TO_ADD = TRUE;

// admin actions such as delete profile or give membership
if ( !$demo_mode && $ADMIN && $_GET['ID'] ) // && $_GET[prf_form_submit])
{
if ( $_GET['what_do'] == "Yes" )
    $add = 1;
elseif ( $_GET['what_do'] == "No" )
    $add = 0;



   switch ( $_GET['prf_form_submit'] )
   {
    case "Confirm":
            activation_mail($_GET[ID] , 0 );
            $status_admin_ex = _t_err("_ADM_PROFILE_CONFIRM_EM");
            break;
    case "Send":
            profile_send_message( $_GET[ID], $_GET[Message] );
            $status_admin_ex = _t_err("_ADM_PROFILE_SEND_MSG");
            break;
   }


}

//---------------------------------- SAVE CHANGES
// adding profile - in admin mode
// updating profile
// check for changes - and change profile status
// check for modification
// check email for modification
// change cookie - if password was changed
// featured profiles for admin only
// sending latter to user - about changed his status
// update field - last modification
$change_error_text = "";

//while ( ( ( $ADMIN && !$demo_mode) || $MEMBER ) && ( $_POST[SaveChanges] == 'YES' ) )
if ( ( $ADMIN  || $MEMBER ) && ( $_POST['SaveChanges'] == 'YES' ) )
{
	// fill array with POST values
	$respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & $db_vsbl AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page[name_index]."',show_on_page)) ORDER BY `order` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
	    $fname = get_input_name ( $arrpd );

	    switch ($arrpd['type'])
	    {
	    case 'set': // set of checkboxes
	        $vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);
	        $p_arr_new[$fname] = '';
	        foreach ( $vals as $v )
	        {
	            if ( strlen(trim($v)) <= 0 ) continue;
	            $p_arr_new[$fname."_".$v] = process_pass_data($_POST[$fname."_".$v]);
	            if ( $_POST[$fname."_".$v] == 'on' )
	            {
	                if ( strlen($p_arr_new[$fname]) )
	                    $p_arr_new[$fname] .= ",$v";
	                else
	                    $p_arr_new[$fname] .= $v;
	            }
	        }
	        break;
		
		case 'date':
				$p_arr_new[$fname] = sprintf("%04d-%02d-%02d",
					(int)$_POST[$fname . '_year'],
					(int)$_POST[$fname . '_month'],
					(int)$_POST[$fname . '_day']
					);
			break;
		
	    default:
			if( $fname == 'Password1' ) //change password
			{
				if( empty( $_POST[$fname] ) ) //the field leaved empty. and we leaving it as is
					$p_arr_new[$fname] = $p_arr['Password'];
				else
					$p_arr_new[$fname] = md5( process_pass_data( $_POST[$fname] ) );
			}
			else
			{
				if ( $arrpd['get_value'] )
		        {
		            $funcbody = $arrpd['get_value'];
		            $func = create_function('$arg0', $funcbody);
		            $p_arr_new[$fname] = process_pass_data($func($_POST));
		        }
		        else
		            $p_arr_new[$fname] = process_pass_data($_POST[$fname]);
			}
	        break;
	    }
	}

	// check values
	if ( !$ADMIN )
	{
	    $respd = db_res("SELECT * FROM `ProfilesDesc` WHERE `visible` & $db_vsbl AND `editable` & $db_editbl AND ( FIND_IN_SET('0', `show_on_page`) OR FIND_IN_SET('".(int)$_page['name_index']."', `show_on_page`)) ORDER BY `order` ASC");
	    while ( $arrpd = mysql_fetch_array($respd) )
	    {
	        if ( !strlen($arrpd['check']) )
				continue;
			
	        $fname = get_input_name ( $arrpd );
			
	        $funcbody = $arrpd['check'];
	        $func = create_function('$arg0', $funcbody);
	        if( !$func($p_arr_new[$fname]) )
				$result_text .= _t_err($arrpd['because']);
	    }
	}
	else
	{
	    if ( !conf_nick($_POST['NickName']) && $p_arr['NickName'] != $p_arr_new['NickName'] )
			$result_text .= _t_err("_this_nick_already_used");//$result_text .= report_err( _t("This Nickname already used !!"));
		
		if( $_POST['Password1'] != $_POST['Password2'] )
			$result_text .= _t_err( '_PWD_INVALID3' );
	}

	if ( !strlen ($result_text) )
	{
		// check for modification (change status)
		if ( !$autoApproval_ifProfile && $MEMBER && !$NEW_TO_ADD && $p_arr['Status'] != "Unconfirmed" && $p_arr['Status'] != "Approval" )
		{
		    $respd = db_res("SELECT * FROM `ProfilesDesc` WHERE `visible` & $db_vsbl AND `editable` & $db_editbl AND ( FIND_IN_SET('0', `show_on_page`) OR FIND_IN_SET('".(int)$_page['name_index']."', `show_on_page`)) AND `to_db` = 1 ORDER BY `order` ASC");
		    while ( $arrpd = mysql_fetch_array($respd) )
		    {
		        $fname = get_input_name ( $arrpd );
		        $dbname = get_field_name ( $arrpd );

		        if ( $dbname == 'Password' )
					continue;

		        switch ($arrpd['type'])
		        {
		        case 'a':
		        case 'c':
		            if ( strcmp(trim($p_arr_new[$fname]),trim($p_arr[$dbname])) )
		            {
		                $STATUS_CHANGE_TO = "Approval";
		                $p_arr['Status'] = "Approval";
		            }
		        }

		        if ( $STATUS_CHANGE_TO == "Approval" )
		            break;
		    }
		}

		// Check if email has changed.  If this is the case,
		// Check if it's valid.
		if ( strcmp( $p_arr['Email'], $p_arr_new['Email'] ) )
		{
		    $Email = $p_arr_new['Email'];
		    if ( !conf_email ( $Email ) )
		    {
		        $result_text .= _t("_EMAIL_ALREADY_USED_BY", $Email, "<a href=\"profile.php?ID=$exist_arr[ID]\">$exist_arr[NickName]</a>");
		    }
		    else
		    {
		        $STATUS_CHANGE_TO = "Unconfirmed";
		        $p_arr['Status'] = $STATUS_CHANGE_TO; // Necessary to correctly display profile status.
		    }
		}


		// new/update profile

		$cl_where = "";
		if ( !$ADMIN && !$exist_arr )
		    $cl_values = "UPDATE `Profiles` SET ";
		elseif ( $ADMIN && $_POST['NewProfile'] == 'YES' )
		    $cl_values = "INSERT INTO `Profiles` SET ";
		elseif ( $ADMIN )
		    $cl_values = "UPDATE `Profiles` SET ";
		$cl_first = 0;

		$respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & $db_vsbl AND `editable` & $db_editbl AND `to_db` = 1 AND ( FIND_IN_SET('0', `show_on_page`) OR FIND_IN_SET('".(int)$_page['name_index']."', `show_on_page`)) ORDER BY `order` DESC");

		while ( $arrpd = mysql_fetch_array($respd) )
		{
			$fname = get_input_name ( $arrpd );
			$dbname = get_field_name ( $arrpd );
			$fval = $p_arr_new[$fname];

			if ( $dbname == "Status" && $fval == 'Active' && $enable_match && $ID > 0 )
				$send_cupid_mail_id = $ID;

			switch ( $arrpd['type'] )
			{
				case 'set': // set of checkboxes
				case 'r': // reference to array for combo box
				case 'c': // text box
				case 'p': // input box password
				case 'a': // text Area - inputed value for textarea is checked while displaying
				case 'rb': // radio buttons
				case 'e': // enum combo box
				case 'en': // enum combo box with numbers
				case 'eny': // enum combo box with numbers
				case 'date': // date
					$fval = process_db_input( $fval, 0, 1 );
					$cl_values .= " `$dbname` = '$fval'";
					$cl_values .= ", ";
				break;	
			}
		}
		
		$len = strlen ($cl_values) - 1;
		while ( $cl_values[$len] == ',' || $cl_values[$len] == ' ' ) {
			$cl_values[$len] = ' ';
			$len--;
		}


		$cl_values .= ", `LastModified` = NOW() ";

		if ( $ADMIN )
		{
		    srand(time());
		    $seed = rand();
		    if ( $_POST[NewProfile] == 'YES' )
		        $cl_values .= "";
		    else
		        $cl_values .= " WHERE ID = '{$p_arr['ID']}'";
		}
		elseif ( strlen($STATUS_CHANGE_TO) )
		{
		    $cl_values .= ", `Status` = '$STATUS_CHANGE_TO'";
		    $cl_values .= " WHERE ID = ".(int)($ID);
		}
		else
		    $cl_values .= " WHERE ID = ".(int)($ID);


		if ( !$exist_arr || $ADMIN )
		{
		    $create_result = db_res( $cl_values );

		    $affected_rows = mysql_affected_rows( $MySQL->link );
		    if ( !$affected_rows )
		        $result_text .= _t("_No modification");//'No modifications were done.';
		    else
		    {
		        if ( $ADMIN && $_POST['NewProfile'] == 'YES' )
		        {
					$IDnormal = mysql_insert_id( $MySQL->link );
					createUserDataFile( $IDnormal );
					
					$result_text .= _t_action("_New profile created")." ID: <a href='profile_edit.php?ID=$IDnormal'>$IDnormal</a>.";
		        }
		        else
		        {
					createUserDataFile( $ID );
		            if ( 'Unconfirmed' == $STATUS_CHANGE_TO )
		            {
		                // Send confirmation request to the user.
		                activation_mail( $ID );
		            }
		            $result_text .= _t_action('_MODIFICATIONS_APPLIED');
		        }
		    }



			if ( $ADMIN )
			{
				$Featured = $_POST['Featured'] == "on" ? 1 : 0;
				db_res( "UPDATE `Profiles` SET `Featured` = '{$Featured}' WHERE `ID` = {$ID};" );
				createUserDataFile( $ID );
			}

		    if ( $MEMBER )
		        setcookie( "memberPassword", $p_arr_new['Password1'], 0, "/" );

		    if ( $send_cupid_mail_id )
		    {
		        cupid_email ($send_cupid_mail_id);
		    }


		    //
		    // MODULES [ BEGIN ]
		    //

		    if ( !strlen($p_arr_new['Sex']) && (int)$p_arr_new['Sex'] == 0 )
		    	$p_arr_new['Sex'] =  $p_arr['Sex'];

		    if ( $ADMIN )
		    {
		    	if ($_POST[NewProfile] == 'YES' && ! $ID) // Second condition is here just in case :)
		    	{
		            $arr = db_arr("SHOW TABLE STATUS LIKE 'Profiles'");
		            $ID = $arr['Auto_increment'] - 1;
		            modules_add($ID);

		            if (strlen($p_arr_new['Status']) > 0 && $p_arr_new['Status'] != 'Active')
		            {
		            	modules_block($ID);
		            }
		            else
		            {
		            	$check_res = checkAction($ID, ACTION_ID_USE_CHAT);
						if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED )
						{
							modules_block($ID, 'chat');
						}
						$check_res = checkAction($ID, ACTION_ID_USE_FORUM);
						if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED )
						{
							modules_block($ID, 'forum');
						}
		            }
		    	}
				else // If updating profile, not adding a new one
				{
		        	if ( $p_arr['Status'] != 'Rejected' && $p_arr_new['Status'] == 'Rejected' )
		        	{
			            // Send email about rejection


			            $recipient = $p_arr_new['Email'];

		        	    $message = getParam("t_Rejection");
			            $subject = getParam('t_Rejection_subject');

		    	        sendMail( $p_arr_new['Email'], $subject, $message, $p_arr['ID'] );

		        	}

		        	if ( $p_arr['Status'] != 'Active' && $p_arr_new['Status'] == 'Active' )
		        	{
			            // Send emain about activation HERE
		            	$recipient = $p_arr_new['Email'];

		            	$message = getParam("t_Activation");
		            	$subject = getParam('t_Activation_subject');

		            	sendMail( $p_arr_new['Email'], $subject, $message, $p_arr['ID'] );

		            	$check_res = checkAction($ID, ACTION_ID_USE_CHAT);
						if ( $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED )
						{
							modules_unblock($ID, 'chat');
						}
						$check_res = checkAction($ID, ACTION_ID_USE_FORUM);
						if ( $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED )
						{
							modules_unblock($ID, 'forum');
						}
		        	}

		        	// Block member if admin changed his/her status not for the best
		        	if ($p_arr['Status'] == 'Active' && strlen($p_arr_new['Status']) > 0 && $p_arr_new['Status'] != 'Active')
		        	{
		        		modules_block($ID);
		        	}
				}
		    }
		    else
		    {
		    	// Block member if his/her status was changed in consequence of some fields changing
		    	if (strlen($STATUS_CHANGE_TO) > 0)
				{
					modules_block($ID);
				}
		    }

			if ($_POST['NewProfile'] != 'YES')
			{
				if ($p_arr['NickName'] != $p_arr_new['NickName'])
				{
					modules_update($ID, '', $p_arr['NickName']);
				}
				else
				{
					modules_update($ID);
				}
			}

				//
		        // MODULES [ END ]
		        //

		} // see line 368
	} // see line 239
} // see line 163


//---------------------------------- SAVE CHANGES [END]



// check if we going to add new profile
if ( $ADMIN && !$ID )
    $NEW_TO_ADD = true;

// Set membership level
if ( $ADMIN && $_POST['SetMembership'] == 'YES' )
{
	if ( is_numeric($_POST['MembershipDays']) || $_POST['MembershipDays'] == 'unlimited' || !isset($_POST['MembershipDays']) )
	{
		if ( $_POST['MembershipDays'] == 'unlimited' || !isset($_POST['MembershipDays']) )
			$membership_days = 0;
		else
			$membership_days = (int)$_POST['MembershipDays'];
		$membership_id = (int)$_POST['MembershipID'];
		$immediately = ($_POST['MembershipImmediately'] == 'on');
		$membership_result = setMembership( $ID, $membership_id, $membership_days, $immediately );
		if (!$membership_result)
			$membership_message = "<font color=\"red\">Failed to set membership</font>";
		else
		{
			$check_res = checkAction($ID, ACTION_ID_USE_CHAT);
			if ($check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
				modules_unblock($ID, 'chat');
			else
				modules_block($ID, 'chat');

			$check_res = checkAction($ID, ACTION_ID_USE_FORUM);
			if ($check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
				modules_unblock($ID, 'forum');
			else
				modules_block($ID, 'forum');
		}
	}
}

// fill array with POST values
if ( !(( ($ADMIN && !$demo_mode) || $MEMBER ) && ( $_POST['SaveChanges'] == 'YES' )) )
{
    // fill array with POST values
    $respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & $db_vsbl AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page[name_index]."',show_on_page)) ORDER BY `order` ASC");
    while ( $arrpd = mysql_fetch_array($respd) )
    {
        $fname = get_input_name( $arrpd );

        switch ($arrpd[type])
        {
        case 'set': // set of checkboxes
            break;
        default:
            if ( $arrpd['get_value'] )
            {
                $funcbody = $arrpd['get_value_db'];
                $func = create_function('$arg0', $funcbody);
                $p_arr_new[$fname] = $func($p_arr);
            }
            break;
        }
    }
}
else
{
	$respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & $db_vsbl AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page[name_index]."',show_on_page)) ORDER BY `order` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
	    $fname = get_input_name ( $arrpd );

	    switch ($arrpd['type'])
	    {
	    case 'set': // set of checkboxes
	        break;

		case 'date':
				$p_arr_new[$fname] = sprintf("%04d-%02d-%02d",
					(int)$_POST[$fname . '_year'],
					(int)$_POST[$fname . '_month'],
					(int)$_POST[$fname . '_day']
					);
			break;

	    default:
			if( $fname == 'Password1' )
			{
				if( empty( $_POST[$fname] ) )
					$p_arr_new[$fname] = $p_arr['Password'];
				else
					$p_arr_new[$fname] = md5( process_pass_data( $_POST[$fname] ) );
			}
			else
				$p_arr_new[$fname] = process_pass_data($_POST[$fname]);
	        break;
	    }
	}
}

// check for featured  member
if ( $ADMIN  )
{
	$featured_arr = getProfileInfo( $ID ); //db_arr( "SELECT `Featured` FROM `Profiles` WHERE `ID` = $ID" );
	$Featured = (int)$featured_arr['Featured'];
}

// --------------- [ END ] GET/POST actions

// --------------- page components

$_ni = $_page['name_index'];

$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
    global $site;
    global $dir;
    global $_page;
    global $p_arr;
    global $p_arr_new;
    global $db_vsbl;
    global $db_editbl;
    global $tab;
    global $autoApproval_ifProfile;

    global $ID;
    global $MEMBER;
    global $ADMIN;
    global $NEW_TO_ADD;
    global $Featured;

    global $pic;
    global $pics;
    global $pic_num;


    global $enable_audio_upload;
    global $enable_video_upload;
    global $sound_file_exist;
    global $video_file_exist;

    global $status_admin_ex;
    global $change_error_text;
    global $result_text;
    global $pictures_text;
    global $membership_message;

    global $max_thumb_height;
    global $max_thumb_width;

    global $enable_ray;
    global $enable_ray_pro;



    ob_start();

	if ( $NEW_TO_ADD )
	{
		$_page['header'] = _t("_New Member");
		$_page['header_text'] = _t("_New Member Add Here");
	}
	else
	{
		$_page['header'] = process_line_output($p_arr['NickName']) .": ". process_line_output( strmaxtextlen($p_arr['DescriptionMe'], 45) );

		$_page['header_text'] = "<b>". process_line_output($p_arr['NickName']) ."</b> - ";
		$_page['header_text'] .= _t('_'.$p_arr['Sex']);
		$_page['header_text'] .= ", " . _t("_y/o", age( $p_arr['DateOfBirth'] )) ." (ID: $p_arr[ID])";
	}


    echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>';

    if ( $ADMIN && $status_admin_ex )
        echo ( $status_admin_ex );


    if ( $change_error_text )
        echo $change_error_text . '<br />';

        global $moderator;
        if ($ADMIN && !$moderator)
        {
        echo "<div align=right class=text2><a href=\"$_SERVER[PHP_SELF]\">"._t("_Add New Profile")."</a></div>";
        }

  if ( $result_text )
      echo '<center>' . $result_text . '</center><br />';

if ( $ADMIN && !$NEW_TO_ADD && $enable_video_upload && $enable_ray && $enable_ray_pro && file_exists( $dir['root'] . "ray/modules/video/admin.php" ) )
{
	$sRayHeaderPath = $dir['root'] . "ray/inc/header.inc.php";
	$iId = (int)$p_arr['ID'];
	$aAdmin = db_arr("SELECT `Name`, `Password` FROM `Admins` LIMIT 1");
	$sNick = $aAdmin['Name'];
	$sPassword = $aAdmin['Password'];
	echo "<tr><td><div style=\"width:179px; padding-bottom:10px; margin-left:auto; margin-right:auto;\">";
	require_once( $dir['root'] . "ray/modules/video/admin.php" );
	echo "</div></td></tr>";
}

if ( $ADMIN && !$NEW_TO_ADD )
{
	// Print membership information and controls
	$memberships_arr = getMemberships();
	$membership_info = getMemberMembershipInfo($ID);
?>
    <!-- MEMBERSHIP [BEGIN] -->

	<tr><td>
		<center><?= $membership_message ?></center>
		<form name="MembershipForm" action="profile_edit.php?ID=<? echo $p_arr['ID']; ?>" method=post>
		<input type="hidden" name="SetMembership" value="YES">
		<table width=100% cellspacing=0 cellpadding=2 class="text2" border=0>
<?
	echo print_rows_set_membership( 1, $memberships_arr, $membership_info, 3, "table", "panel", "25%" );
?>
		</table>
		<center>
			<input class=no type="submit" value="Set" style="width: 50px;">
		</center>
		</form>
    <hr>
    </td></tr>

    <!-- MEMBERSHIP [ END ] -->
<?
}

?>
	
    <form name="jform" method="post" action="profile_edit.php?ID=<? echo $p_arr['ID']; ?>">
<?
	if ( $NEW_TO_ADD )
	{?>
		<input type="hidden" name="NewProfile" value="YES" />
	<?}
?>
<input type="hidden" name="SaveChanges" value="YES" />
<?

if ( $ADMIN )
{
	?>
	<table cellspacing="0" cellpadding="0" class="small2" align="center">
		<tr>
			<td align="right" width="75%"><?= _t("_Mark as Featured") ?></td>
			<td align="left" width="25%">
				<input type="checkbox" name="Featured"
				  <? if ( $Featured ) echo 'checked="checked"'; ?> >
			</td>
		</tr>
	</table>
	<?
}

?>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
    	<tr>
    <td valign="top">

    <table border="0" cellspacing="1" cellpadding="0" width="100%">
    <tr><td align="center" valign="middle">
<?


if ( $NEW_TO_ADD )
    echo _t("_New Member").'<br /><br />';
else
{
	?>
        <table border="0" cellspacing="0" cellpadding="0" width="250">
			<tr>
				<td align="center" valign="middle" style="position:relative; display:block;width:112px;">
					<?= get_member_thumbnail($p_arr['ID'], 'none') ?>
    	</td>
		<td>
	<?
    $yes_ph = 0;

    require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );
	$oPhoto = new ProfilePhotos( $p_arr['ID'] );
	$oPhoto -> getMediaArray();
	$yes_ph = $oPhoto -> iMediaCount;

    if ( $yes_ph )
    {
		?>
			<a href="<?=$site['url'] ?>photos_gallery.php?ID=<?= $p_arr['ID'] ?>" ><?= _t("_More Photos") ?></a><br />
		<?
    }

    if ( !$MEMBER )
    {
        echo "<div class=small2>"._t("_Last logged in").": ";
	    if ( !$p_arr['LastLoggedIn'] || $p_arr['LastLoggedIn'] == "0000-00-00 00:00:00" )
	        echo _t("_never");
	    else
	        echo $p_arr['LastLoggedIn'];
	    echo "</div>";
    }
	else
	{
		echo _t("_Profile status"); ?>:
		<b><font class=prof_stat_<? echo $p_arr['Status']; ?>> <? echo _t("_".$p_arr['Status']); ?> </font></b><br />
	<?
	    if ( $MEMBER )
		    switch ( $p_arr['Status'] )
		    {
		        case 'Active':echo '<a href="change_status.php">'._t("_Suspend account").'</a><br />';break;
		        case 'Suspended':echo '<a href="change_status.php">'._t("_Activate account").'</a><br />';break;
		    }
	}

	?>
    	</td></tr>
        </table>
	<?
}
?>
    </td>
    <td valign="top" align="center" width="100%">

<?

$free_mode = getParam("free_mode") == "on" ? 1 : 0;

?>
    </td>

</table>

</td></tr>
<tr><td>

<table class="profile_edit_table"><!-- Profile edit page -->
<?
    $first_row = 1;
    $rd = !( $ADMIN || $MEMBER );
	
	$columns = 2;
	
    $respd = db_res("SELECT *, (`editable` & $db_editbl) AS `is_editable` FROM `ProfilesDesc` WHERE `visible` & $db_vsbl AND ( FIND_IN_SET('0', `show_on_page`) OR FIND_IN_SET('".(int)$_page['name_index']."', show_on_page)) ORDER BY `order` ASC");
    while ( $arrpd = mysql_fetch_array($respd) )
    {
        $fname = get_input_name( $arrpd );

        if ( $arrpd['get_value'] && $arrpd['to_db'] == 0 )
        {
            $funcbody = $arrpd['get_value'];
            $func = create_function('$arg0',$funcbody);
            $p_arr[$fname] = $func($p_arr_new);
        }


        if ( $arrpd['is_editable'] && isset($p_arr_new[$fname]) )
        {
            $p_arr[$fname] = $p_arr_new[$fname];
        }

        $not_first_row = 0;

        $read_only = !$arrpd['is_editable'];
        switch ($arrpd['type'])
        {
        case 'set': // set of checkboxes
            echo print_row_set( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only );
            break;
        case 'rb': // radio buttons
            echo print_row_radio_button( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only );
            break;
        case 'r': // reference to array for combo box
			if ( $fname == 'Country' )
			{
				$onchange = "flagImage = document.getElementById('flagImageId'); flagImage.src = '{$site['flags']}' + this.value.toLowerCase() + '.gif';";
				if ( strlen($p_arr[$fname]) == 0 )
					$p_arr[$fname] = getParam( 'default_country' );
				$imagecode = '<img id="flagImageId" src="'. ($site['flags'].strtolower($p_arr[$fname])) .'.gif" alt="flag" />';
			}
			else
			{
				$onchange = '';
				$imagecode = '';
			}
            echo print_row_ref( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only, $onchange, $imagecode );
            break;
        case '0': // divider
            echo print_row_delim( $first_row, $arrpd, "panel", $columns );
            $not_first_row = 1;
            $first_row = 1;
            break;
        case 'e': // enum combo box. if field name is 'Sex', than this is simple text, user can not change it
            echo print_row_enum( $first_row, $arrpd, $p_arr[$fname], "table", $javascript, $rd, $columns, "", $read_only );
            break;
        case 'en': // enum combo box with numbers
            echo print_row_enum_n( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only );
            break;
        case 'eny': // enum combo box with years
            echo print_row_enum_years( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only );
            break;
        case 'a': // text Area
            echo print_row_area( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only );
            break;
        case 'c': // input box
            echo print_row_edit( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only );
            break;
        case 'date': // date
            echo print_row_date( $first_row, $arrpd, $p_arr[$fname], "table", $rd, $columns, "", $read_only );
            break;
        case 'p': // input box password
        	echo print_row_pwd( $first_row, $arrpd, '', "table", $rd, $columns, "", $read_only );
            break;
        default:
            $not_first_row = 1;
        }
        if ( !$not_first_row && $first_row == 1 )  $first_row = 0;
    }
?>
</table>
</td></tr>
</table>

<br />
<?

    if ( !(!($ADMIN || $MEMBER )) )
    {
?>
<center><input type="submit" value="<?php echo _t("_Save Changes"); ?>" /></center>
</form>
<br />
<?
        if ( $MEMBER && !$NEW_TO_ADD )
        {
            if (!$autoApproval_ifProfile) attention( _t("_PROFILE_WARNING1", $site['title']) );
            attention( _t("_PROFILE_WARNING2", $site['title']) );
        }
    }

    echo "</td></tr></table>";

    $ret = ob_get_clean();

    return $ret;
}

/**
 * prints error message
 */
/*
function report_err( $str )
{
    return "<font color=\"#880000\"><b>Error:</b> $str</font><br />";
}
*/
/**
 * prints submit form for image upload
 */
function img_form ( $i )
{
    global $pics;
    global $p_arr;

    $ret = "";

    $ret .=  "<form enctype=\"multipart/form-data\" action=\"profile_edit.php?ID={$p_arr['ID']}\" method=post>\n";
    $ret .= "photo $i: \n";
    if ( $pics[$i]['exist'] )
    {
        $ret .="<a target=_blank href=\"{$pics[$i]['url']}\">View photo</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
        $ret .="<a href=\"profile_edit.php?ID={$p_arr['ID']}&amp;action=delete_$i\">Delete</a>";
        if( substr($p_arr["Pic_".$i."_addon"], 0, 1) != 'p' )
        {
          $ret .="<br />(not private)";
        }
        else
        {
          $ret .="<br />(private)";
        }

    }
    $ret .= "<br />";
    $ret .= "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"".((int)( 2 * 1024 * 1024 ))."\">";
    $ret .= "<input type=hidden name=\"${i}_UPLOAD\" value=\"YES\">";
    $ret .= "<input class=no name=file_$i type=file size=10>&nbsp;<input class=no type=submit value=\""._t("_Upload")."\">\n";

    $ret .= "</form>\n";

    return $ret;
}

?>
