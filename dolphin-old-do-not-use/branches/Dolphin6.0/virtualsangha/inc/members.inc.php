<?php

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

require_once( "membership_levels.inc.php" );

/**
 * Prints total members statisric
 */

function contr_panel_members_total($w = "")
{
	global $site;
	global $prof;
	global $PageCompMemberStat_db_num;

	$free_mode = getParam("free_mode") == "on" ? 1 : 0;

	// members statistics
	$total_c2 = strlen( $_POST['total_c2'] ) ? $_POST['total_c2'] : getParam( "default_country" );
	$total_arr = db_arr( "SELECT COUNT(ID) FROM `Profiles` WHERE Status = 'Active'" );
	$total_arr_week = db_arr( "SELECT COUNT(ID) FROM `Profiles` WHERE Status = 'Active' AND (TO_DAYS(NOW()) - TO_DAYS(LastReg)) <= 7" );
	if ( !$free_mode )
		$total_arr_gold = getMembersCount( MEMBERSHIP_ID_STANDARD, '', true );
	$total_c_arr = db_arr( "SELECT COUNT(ID) FROM `Profiles` WHERE Status = 'Active' AND `Country` = '". process_db_input($total_c2) ."'" );
	$total_members = $total_arr[0];
	$total_c_members = $total_c_arr[0];

	$c_arr = $prof['countries'];

	$ret = '';
	$ret .= '<div class="totalRegBlock">';
		$ret .= '<div class="clear_both"></div>';
		$ret .= '<div style="position:relative; float:left;"><img src="' . $site['icons'] . 'group.gif" alt="" /></div>';
		$ret .= '<div style="position:relative; float:left; white-space:nowrap; left:5px; font-weight:bold; color:#000;">' . _t("_Total Registered") . '</div>';
		$ret .= '<div class="clear_both"></div>';
		$ret .= '<div style="position:relative;  margin:0px 0px;"></div>';
		$ret .= '<div class="member_stat_block">';
			$ret .= '<div><img src="' . $site['icons'] . 'us.gif" alt="" /><span style="margin-left:5px;">' . _t("_Total") . '&nbsp;-&nbsp;' .  $total_arr[0] . '</span></div>';
			if ( !$free_mode )
				$ret .= '<div><img src="' . $site['icons'] . 'us.gif" alt="" /><span style="margin-left:5px;">' . _t("_Gold Members") . '&nbsp;-&nbsp;' .  $total_arr_gold  . '</span></div>';

			$ret .= '<div><img src="' . $site['icons'] . 'us.gif" alt="" /><span style="margin-left:5px;">' . _t("_New this week") . '&nbsp;-&nbsp;' . $total_arr_week[0]  . '</span></div>';
			$ret .= '<div><img src="' . $site['icons'] . 'us.gif" alt="" /><span style="margin-left:5px;">' . $total_c_members . '&nbsp;' . _t("_members") . '&nbsp;' . _t("_from") . '</span></div>';
			$ret .= '<div class="mem_stat_country">';
				$ret .= '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="cForm2">';
				$ret .= '<input type="hidden" name="total_c2" value="' . $total_c2 . '" />';
				$ret .= '<select class="mem_stat_country_select" name="total_c2" onChange="javascript:this.form.submit();">';
				foreach ( $c_arr as $key => $value )
				{
					$ret .= '<option value="' . $key . '"';
					if ( $key == $total_c2 )
						$ret .= ' selected="selected"';
					$ret .= '>' . _t( '__'.$value ) . '</option>';
				}
				$ret .= '</select>';
				$ret .= '</form>';
			$ret .= '</div>';
		$ret .= '</div>';
	$ret .= '</div>';

	return $ret;
}


function contr_panel_members_onl($w = "")
{
	global $site;
	global $prof;

	// members statistics
	$total_c = strlen( $_POST['total_c'] ) ? $_POST['total_c'] : getParam( "default_country" );

	$total_c_members_onl = get_users_online_number('c', $total_c );
	$total_arr_chatting = get_users_online_number('t');
	$members_online = get_users_online_number();

	$c_arr = $prof['countries'];

	$ret = '';
	$ret .= '<div class="membes_statistic_block">';
		$ret .= '<div class="clear_both"></div>';
		$ret .= '<div style="position:relative;  float:left;"><img src="' . $site['icons'] . 'group.gif" alt="" /></div>';
		$ret .= '<div style="position:relative; float:left; white-space:nowrap; left:5px; font-weight:bold; color:#000;"><a href="search_result.php?online_only=show" target="_blank">' . _t("_Currently Online") . '</a></div>';
		$ret .= '<div class="clear_both"></div>';
		$ret .= '<div style="position:relative; margin:0px 0px;"></div>';
		$ret .= '<div class="member_stat_block">';
			$ret .= '<div><img src="' . $site['icons'] . 'us.gif" alt="" /><span style="margin-left:5px;">' . _t("_Total") . '&nbsp;-&nbsp;' .  $members_online . '</span></div>';
			$ret .= '<div><img src="' . $site['icons'] . 'us.gif" alt="" /><span style="margin-left:5px;">' . _t("_Chatting") . '&nbsp;-&nbsp;' .  $total_arr_chatting . '</span></div>';
			$ret .= '<div><img src="' . $site['icons'] . 'us.gif" alt="" /><span style="margin-left:5px;">' . $total_c_members_onl . '&nbsp;' . _t("_members") . '&nbsp;' . _t("_from") . '</span></div>';
			$ret .= '<div class="mem_stat_country">';
				$ret .= '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="cForm">';
				$ret .= '<input type="hidden" name="total_c" value="' . $total_c . '" />';
				$ret .= '<select class="mem_stat_country_select" name="total_c" onChange="javascript:this.form.submit();">';
				foreach ( $c_arr as $key => $value )
				{
					$ret .= '<option value="' . $key . '"';
					if ( $key == $total_c )
						$ret .= ' selected="selected"';
					$ret .= '>' . _t( '__'.$value ) . '</option>';
				}
				$ret .= '</select>';
				$ret .= '</form>';
			$ret .= '</div>';
		$ret .= '</div>';
	$ret .= '</div>';

	return $ret;

}

/**
 * returns HTML code for one search row
 */
function PrintSearhResult( $p_arr, $templ_search, $iNumber=1 )
{
	global $site;
	global $pa_icon_preload;
	global $prof;
	global $logged;
	global $enable_match;
	global $tmpl;
	global $dir;
	global $max_thumb_width;
	global $max_thumb_height;
	global $anon_mode;
	global $enable_zodiac;
	global $enable_friendlist;
	global $pic_num;
	global $oTemplConfig;
	global $NickName;

	$member['ID'] = (int)$_COOKIE['memberID'];

	$free_mode = getParam("free_mode") == "on" ? 1 : 0;
	$bEnableRay = (getParam( 'enable_ray' ) == 'on');

	$gl_thumb_width = $max_thumb_width;
	$gl_thumb_height = $max_thumb_height;

	// get user online status
	$user_is_online = get_user_online_status($p_arr[ID]);

	$templ = $templ_search;

	// ------------ template variables --------------


	$thumbnail = get_member_thumbnail( $p_arr['ID'], 'none' );

	// online/offline status
	$launch_ray_im = "";
	//--- Ray IM integration ---//	
	if ( $user_is_online )
	{
		$check_res = checkAction( $member['ID'], ACTION_ID_USE_RAY_IM );
		if ($bEnableRay && $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED && $member['ID'] != $p_arr['ID']  && !empty($member['ID']))
		{			
			$iSndId = (int)$member['ID'];
			$sSndPassword = getPassword($member['ID']);
			$iRspId = (int)$p_arr['ID'];
			$launch_ray_im = "<a href=\"javascript:void(0);\" onClick=\"javascript: openRayWidget( 'im', 'user', '" . $iSndId . "', '" . $sSndPassword . "', '" . $iRspId . "' ); return false;\">"._t("_IM now", $p_arr['NickName'])."</a>";
		}
	}
	//--- Ray IM integration ---//

	// Template customizations

		// profile Nick/Age/Sex etc.
		$nick = '<a href="' . getProfileLink( $p_arr['ID'] ) . '">' . $p_arr['NickName'] . '</a>';

		$age_str = ($p_arr['DateOfBirth'] != "0000-00-00" ? (_t("_y/o", age( $p_arr['DateOfBirth'] )) .' ') : "");
		$age_only = ($p_arr['DateOfBirth'] != "0000-00-00" ? ( age( $p_arr['DateOfBirth'] )) : "");
		$y_o_sex = $age_str . _t("_".$p_arr['Sex']);

		$city =  _t("_City").": ".process_line_output($p_arr['City']);
		$country = _t("_Country").": "._t("__".$prof['countries'][$p_arr['Country']]).'&nbsp;<img src="'. ($site['flags'].strtolower($p_arr['Country'])) .'.gif" alt="flag" />';
		$city_con = process_line_output($p_arr['City']).", "._t("__".$prof['countries'][$p_arr['Country']]).'&nbsp;<img src="'. ($site['flags'].strtolower($p_arr['Country'])) .'.gif" alt="flag" />';
		$occupation = process_line_output($p_arr['Occupation']);
		$children = ($p_arr['Children'] ? ($p_arr['Children']." "._t("_children")) : "");
		$id = _t("_ID").": ".$p_arr['ID'];

		// description
		$i_am = _t("_I am");
		$i_am_desc = process_smiles( strip_tags( process_text_output( $p_arr['DescriptionMe'] ), '<img>' ) ) . "...";
		$you_are = _t("_You are");
		$you_are_desc = process_smiles(process_text_output($p_arr['DescriptionYou']))."... <a href=\"".getProfileLink($p_arr['ID'])."\">"._t("_more")."</a>";
		/*
		echo '<hr>';
		print_r( $p_arr );
		echo '<hr>';
		*/

		$sCity = $p_arr['City'];
		//echo $sCity . '<hr>';

//--- Ray IM integration start ---//

	$ai_im = '';
	$al_im = '';

	$chechActionRes = checkAction($member['ID'], ACTION_ID_USE_RAY_IM);
	if( $bEnableRay && $chechActionRes[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED  && $member['ID'] != $p_arr['ID'] && !empty( $member['ID'] ) )
	{
		$iSndId = (int) $member['ID'];
		$sSndPassword = getPassword( $member['ID'] );
		$iRspId = (int) $p_arr['ID'];

		if( $user_is_online )
		{
			$file_icon = $dir['root'] ."templates/tmpl_" . $tmpl . "/images/icons/action_im_small.gif";
			if( file_exists( $file_icon ) )
				$ai_im  .= "<img alt=\"" . _t("_IM now") . "\" class=\"links_image\" name=i06$p_arr[ID] src=\"$site[url]templates/tmpl_$tmpl/images/icons/action_im_small.gif\" />";
			$al_im .= '<a href="javascript:void(0);" onClick="javascript: openRayWidget( \'im\', \'user\', \'' . $iSndId . '\', \'' . $sSndPassword . '\', \'' . $iRspId . '\' ); return false;">';
			$al_im = '<span class="links_span">' . $ai_im . $al_im . ucwords( _t( "_IM now", $p_arr['NickName'] ) ) . '</a></span>';
		}
	}

//--- Ray IM integration end ---//

/* Standard IM commented out
	else if ( $enable_im )
	{
		if ( $user_is_online )
		{
			$al_im = "<a href=\"javascript:void(0);\" OnClick=\"javascript: launchAddToIM({$p_arr['ID']}); return false;\"";
		}
		else
		{
			$al_im = "<a href='javascript: void(0);' OnClick=\"javascript: alert('". _t("_Sorry, user is OFFLINE") ."'); return false;\"";
		}

		if ( $pa_icon_preload )
			$al_im .= "onMouseOut =\"javascript: i06$p_arr[ID].src='$site[images]pa_im.gif';\"";

		$al_im .= ">";

		$file_icon = $dir['root'] ."templates/tmpl_".$tmpl."/images/pa_im.gif";
		if ( file_exists( $file_icon ) )
			$ai_im .= $al_im ."<img name=i06$p_arr[ID] src=\"". $site['images']. "pa_im.gif\" border=0></a>";
		$al_im .= _t("_chat now") ."</a>";
	}
 Standard IM commented out */

/* View Profile commented out
	    $ai_viewprof = "<img alt=\""._t("_View Profile")."\" name=i00$p_arr[ID] src=\"$site[images]pa_profile.gif\" border=0>";
	    $al_viewprof = "<a href=\"$p_arr[NickName]\"";

        if ( $pa_icon_preload )
        {
         $al_viewprof.="onMouseOver=\"javascript:i00$p_arr[ID].src='$site[images]pa_profile2.gif';\"";
         $al_viewprof.="onMouseOut =\"javascript:i00$p_arr[ID].src='$site[images]pa_profile.gif';\"";
        }
		$al_viewprof .= ">";
		$ai_viewprof = $al_viewprof.$ai_viewprof."</a>";
        $al_viewprof .= _t("_View Profile")."</a>";
 View Profile commented out */

//--- Greeting start ---//

		$chechActionRes = checkAction($member['ID'], ACTION_ID_SEND_VKISS);
		if( ( $chechActionRes[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED )  && ( $member['ID'] != $p_arr['ID'] ) )
		{
			$ai_kiss = "<img alt=\"" . _t("_Greet") . "\" class=\"links_image\" name=i01$p_arr[ID] src=\"" . $site['url'] . "templates/tmpl_" . $tmpl . "/images/icons/action_greet_small.gif\" />";
			$al_kiss = '<a target=_blank href="greet.php?sendto=' . $p_arr[ID] . '"';
			if ( $pa_icon_preload )
			{
				$al_kiss.="onMouseOver=\"javascript: i01$p_arr[ID].src='$site[images]pa_kiss2.gif';\"";
				$al_kiss.="onMouseOut =\"javascript: i01$p_arr[ID].src='$site[images]pa_kiss.gif';\"";
			}
			$al_kiss .= ">";
			$al_kiss = "<span class=\"links_span\">" . $ai_kiss . $al_kiss . _t("_Greet")."</a></span>";
		}
		else
		{
			$al_kiss =	''; 
		}
		
//--- Greeting end ---//

//--- Contact start ---//

		$chechActionRes = checkAction($member['ID'], ACTION_ID_SEND_MESSAGE);
		if( ( $chechActionRes[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED )  && ( $member['ID'] != $p_arr['ID'] ) )
		{
			$ai_sendmsg  = "<img alt=\""._t("_SEND_MESSAGE")."\" name=i02$p_arr[ID] src=\"$site[url]templates/tmpl_$tmpl/images/icons/action_send_small.gif\" class=\"links_image\" />";
			$al_sendmsg  = "<a href=\"compose.php?ID=$p_arr[ID]\"";
	        if ( $pa_icon_preload )
	        {
	        	$al_sendmsg.="onMouseOver=\"javascript: i02$p_arr[ID].src='$site[images]pa_send2.gif';\"";
	        	$al_sendmsg.="onMouseOut =\"javascript: i02$p_arr[ID].src='$site[images]pa_send.gif';\"";
	        }
	        $al_sendmsg .= ">";
			$al_sendmsg  = "<span class=\"links_span\">" . $ai_sendmsg . $al_sendmsg . _t("_Contact")."</a></span>";
		}
		else
		{
			$al_sendmsg = '';
		}
		
//--- Contact end ---//		

		if ( $logged['member'] )
	    {
			$ai_hot  ="<img alt=\""._t("_hot member")."\" name=i03$p_arr[ID] src=\"$site[images]pa_hot.gif\" border=0>";
			$al_hot  ="<a href=\"javascript:void(0);\" onClick=\"javascript:window.open( 'list_pop.php?action=hot&ID=".$p_arr['ID']."', '', 'width=280,height=200,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\"";
			if ( $pa_icon_preload )
			{
			 $al_hot.="onMouseOver=\"javascript: i03$p_arr[ID].src='$site[images]pa_hot2.gif';\"";
			 $al_hot.="onMouseOut =\"javascript: i03$p_arr[ID].src='$site[images]pa_hot.gif';\"";
			}
			$al_hot .= ">";
			$ai_hot  = $al_hot.$ai_hot."</a>";
			$al_hot .= _t("_hot member")."</a>";

			$ai_friend  ="<img alt=\""._t("_friend member")."\" name=i03$p_arr[ID] src=\"$site[images]pa_friend.gif\" border=0>";
			$al_friend  ="<a href=\"javascript:void(0);\" onClick=\"javascript:window.open( 'list_pop.php?action=friend&ID=".$p_arr['ID']."', '', 'width=280,height=200,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\"";
			if ( $pa_icon_preload )
			{
			 $al_friend.="onMouseOver=\"javascript: i03$p_arr[ID].src='$site[images]pa_friend2.gif';\"";
			 $al_friend.="onMouseOut =\"javascript: i03$p_arr[ID].src='$site[images]pa_friend.gif';\"";
			}
			$al_friend .= ">";
			$ai_friend  = $al_friend.$ai_friend."</a>";
			$al_friend .= _t("_friend member")."</a>";

			$ai_block  ="<img alt=\""._t("_block member")."\" name=i04$p_arr[ID] src=\"$site[images]pa_block.gif\" border=0>";
			$al_block  ="<a href=\"javascript:void(0);\" onClick=\"javascript:window.open( 'list_pop.php?action=block&ID=".$p_arr['ID']."', '', 'width=280,height=200,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\"";
			if ( $pa_icon_preload )
			{
			 $al_block.="onMouseOver=\"javascript: i04$p_arr[ID].src='$site[images]pa_block2.gif';\"";
			 $al_block.="onMouseOut =\"javascript: i04$p_arr[ID].src='$site[images]pa_block.gif';\"";
			}
			$al_block .= ">";
			$ai_block  = $al_block.$ai_block."</a>";
			$al_block .= _t("_block member")."</a>";
		}
		
//--- Fave Start ---//

	if( $logged['member'] && $member['ID'] != $p_arr['ID'] )
	{
		$ai_fave  = "<img alt=\"" . _t("_Fave") . "\" class=\"links_image\" name=i03$p_arr[ID] src=\"$site[url]templates/tmpl_$tmpl/images/icons/action_fave_small.gif\" />";
		$al_fave  = "<a href=\"javascript:void(0);\" onclick=\"window.open( 'list_pop.php?action=hot&amp;ID=$profileID',    '', 'width={$oTemplConfig -> popUpWindowWidth},height={$oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );\">";
		$al_fave = '<span class="links_span">' . $ai_fave . $al_fave . _t("_Fave") . "</a></span>";
	}
	else
	{
		$al_fave = '';
	}

//--- Fave End ---//
	
	$more = '<a href="' . getProfileLink( $p_arr['ID'] ) . '" target="_blank">';
		$more .= '<img src="' . $site['icons'] . 'desc_more.gif" alt="' . _t('_more') . '" />';
	$more .= '</a>';

	$enable_more_photos = ( 'on' == getParam("more_photos_on_searchrow")) ? 1 : 0;
	if( $enable_more_photos )
	{
		$more_photos = '';
		for( $i=1 ; $i<=$pic_num; ++$i )
		{
			if( $p_arr['Pic_' . $i . '_addon'] > 0 )
			{
				$photo_counter ++;
			}
		}
		if( ( 0 < $photo_counter ) && ( $logged['admin'] || $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED ) )
		{
			$more_photos = '<a href="javascript:void(0);" onClick="javascript: get_gallery(' . $p_arr['ID'] . ');" class="search_more_photo">';
				$more_photos .= $photo_counter . '&nbsp;' . _t("_More Photos");
			$more_photos .= '</a>';
		}
		else
		{
			$more_photos = '';
		}
	}

	// ------------ [end] template variables --------------

	// replace template variables
	$templ = str_replace ( "__row_title__", process_line_output($p_arr[Headline]), $templ );
	$templ = str_replace ( "__n__", $iNumber, $templ );
	$templ = str_replace ( "__thumbnail__", $thumbnail, $templ );
	$templ = str_replace ( "__launch_ray_im__", $launch_ray_im, $templ );

	// match progress bar
	if( $logged['member'] && ( $member['ID'] != $p_arr['ID'] ) && $enable_match )
	{
		$templ = str_replace ( "__match__", getProfileMatch( $member['ID'], $p_arr['ID'] ), $templ );
	}
	else
	{
		$templ = str_replace ( "__match__", '', $templ );
	}


	$templ = str_replace ( "__nick__", $nick, $templ );
	$templ = str_replace ( "__nick__", $nick, $templ );
	$templ = str_replace ( "__age_sex__", $y_o_sex, $templ );
	$templ = str_replace ( "__city__", $city, $templ );
	$templ = str_replace ( "__just_city__", $sCity, $templ );
	$templ = str_replace ( "__just_age__", $age_only, $templ );


	$templ = str_replace ( "__city_con__", $city_con, $templ );
	$templ = str_replace ( "__country__", $country, $templ );
	$templ = str_replace ( "__occupation__", $occupation, $templ );
	$templ = str_replace ( "__children__", $children, $templ );
	$templ = str_replace ( "__id__", $id, $templ );


	if ( $enable_zodiac )
	{
		$templ = str_replace ( "__zodiac_sign__", getProfileZodiac($p_arr['DateOfBirth']), $templ );
	}
	else
	{
		$templ = str_replace ( "__zodiac_sign__", "", $templ );
	}

	/*if( $enable_friendlist )
	{
		$templ = str_replace ( "__friend_list__", ShowFriendList($p_arr[ID]), $templ );
	}
	else
	{
		$templ = str_replace ( "__friend_list__", "", $templ );
	}*/


	$templ = str_replace ( "__i_am__", $i_am, $templ );
	$templ = str_replace ( "__i_am_desc__", $i_am_desc, $templ );
	$templ = str_replace ( "__you_are__", $you_are, $templ );
	$templ = str_replace ( "__you_are_desc__", $you_are_desc, $templ );

	$templ = str_replace ( "__ai_im__", $ai_im, $templ );
	$templ = str_replace ( "__al_im__", $al_im, $templ );
	$templ = str_replace ( "__ai_viewprof__", $ai_viewprof, $templ );
	$templ = str_replace ( "__al_viewprof__", $al_viewprof, $templ );
	$templ = str_replace ( "__ai_kiss__", $ai_kiss, $templ );
	$templ = str_replace ( "__al_kiss__", $al_kiss, $templ );
	$templ = str_replace ( "__ai_sendmsg__", $ai_sendmsg, $templ );
	$templ = str_replace ( "__al_sendmsg__", $al_sendmsg, $templ );
	$templ = str_replace ( "__ai_fave__", $ai_fave, $templ );
	$templ = str_replace ( "__al_fave__", $al_fave, $templ );
	$templ = str_replace ( "__ai_hot__", $ai_hot, $templ );
	$templ = str_replace ( "__al_hot__", $al_hot, $templ );
	$templ = str_replace ( "__ai_block__", $ai_block, $templ );
	$templ = str_replace ( "__al_block__", $al_block, $templ );
	$templ = str_replace ( "__from__", _t("_from"), $templ );
	$templ = str_replace ( "__more__", $more, $templ );
	$templ = str_replace ( "__more_photos__", $more_photos, $templ );
	$templ = str_replace ( "__images__", $site['images'], $templ );
	if ( function_exists( 'colors_select' ) )
		$templ = str_replace ( "__designBoxColor__", colors_select(), $templ );

	return $templ;

}


?>