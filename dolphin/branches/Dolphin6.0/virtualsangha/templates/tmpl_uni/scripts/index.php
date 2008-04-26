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

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEvents.php' );

if( $_GET['show_only'] )
{
	switch( $_GET['show_only'] )
	{
		case 'members':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'TopMembers'" );
			echo PageCompTopMembersContent( $sCaption );
		break;
		case 'photos':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'TopPhotos'" );
			echo PageCompTopPhotosContent( $sCaption );
		break;
		case 'tags':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'Tags'" );
			echo PageCompTagsContent( $sCaption );
		break;
		case 'tags_members':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'Tags'" );
			echo PageCompTagsMembersContent( $sCaption );
		break;
		case 'blogs':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'Blogs'" );
			echo PageCompBlogsContent( $sCaption );
		break;
		case 'classifieds':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'Classifieds'" );
			echo PageCompClassifiedsContent( $sCaption );
		break;
		case 'events':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'Events'" );
			echo PageCompEventsContent( $sCaption );
		break;
		case 'groups':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'Groups'" );
			echo PageCompGroupsContent( $sCaption );
		break;
		case 'ppoll':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'ProfilePoll'" );
			echo PageCompProfilePollContent( $sCaption );
		break;
		case 'sharePhotos':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'SharePhotos'" );
			echo PageCompSharePhotosContent($sCaption);
		break;
		case 'shareVideos':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'ShareVideos'" );
			echo PageCompShareVideosContent($sCaption);
		break;
		case 'shareMusic':
			$sCaption = db_value( "SELECT `Caption` FROM `IndexCompose` WHERE `Func` = 'ShareMusic'" );
			echo PageCompShareMusicContent($sCaption);
		break;
	}
}
else
{
	$_ni = $_page['name_index'];
	
	$_page_cont[$_ni]['promo_code'] = getPromoCode();
	
	$_page_cont[$_ni]['compose_index_col1'] = PageCompCreateBlocks( 1 );
	$_page_cont[$_ni]['compose_index_col2'] = PageCompCreateBlocks( 2 );
	
	$_page['extra_js'] = '<script type="text/javascript">urlIconLoading = "'.getTemplateIcon('loading.gif').'";</script>';

	// --------------- [END] page components

	PageCode();
}

// --------------- page components functions


/**
 * dynamically generates index page blocks
 */
function PageCompCreateBlocks( $Col )
{
	global $logged;
	
	if( $logged['member'] )
		$sVisible = 'memb';
	else
		$sVisible = 'non';
	
	$ret = '';
	
	$rBlocks = db_res( "SELECT * FROM `IndexCompose` WHERE `Column`=$Col AND FIND_IN_SET( '$sVisible', `Visible` ) ORDER BY `Order`" );
	while( $aBlock = mysql_fetch_assoc( $rBlocks ) )
	{
		$func = 'PageComp' . $aBlock['Func'];
		$ret .= $func( $aBlock['Caption'], $aBlock['Content'] );
	}

	return $ret;
}

function PageCompEcho( $sCaption, $sContent )
{
	return DesignBoxContent( _t($sCaption), $sContent, 1 );
}

/**
 * members statistic block
 */
function PageCompMemberStat( $sCaption )
{
	$sCode = getSiteStat();
	return DesignBoxContent ( _t($sCaption), $sCode, 1 );
}

/**
 * Top Rated Profiles block
 */
function PageCompTopRated( $sCaption )
{
    global $site;
	global $max_voting_mark;
	global $index_progressbar_w;
	global $PageCompTopRated_db_num;
	global $max_thumb_height;
	global $max_thumb_width;
	global $oTemplConfig;
	
	// most rated profiles

	// $rate_max = get_max_votes_profile();

    $oVoting = new BxTemplVotingView ('profile', 0, 0);

    $iIdMonth = $oVoting->getTopVotedItem(30, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active'");
    $iIdWeek  = $oVoting->getTopVotedItem(7, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active'");
    $iIdDay   = $oVoting->getTopVotedItem(1, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active'");

    $oVotingMonth = new BxTemplVotingView ('profile', $iIdMonth);
    $oVotingWeek  = new BxTemplVotingView ('profile', $iIdWeek);
    $oVotingDay   = new BxTemplVotingView ('profile', $iIdDay);

	$rate_memb_month  = getProfileInfo( $iIdMonth ); //db_arr( "SELECT Headline, NickName, ID, Pic_0_addon FROM Profiles WHERE `ID` = '$iIdMonth' LIMIT 1" );
	$rate_memb_week   = getProfileInfo( $iIdWeek ); //db_arr( "SELECT Headline, NickName, ID, Pic_0_addon FROM Profiles WHERE `ID` = '$iIdWeek' LIMIT 1" );
	$rate_memb_day    = getProfileInfo( $iIdDay ); //db_arr( "SELECT Headline, NickName, ID, Pic_0_addon FROM Profiles WHERE `ID` = '$iIdDay' LIMIT 1" );

	//$rate_memb_month  = db_arr( "SELECT Headline, NickName, Member, COUNT(*) AS `count`, SUM(Mark)/COUNT(*) AS mark, ID, Pic_0_addon  FROM `Votes` INNER JOIN Profiles ON (ID = Member) WHERE Status = 'Active' AND TO_DAYS(NOW()) - TO_DAYS(`Date`) <= 30 GROUP BY Member ORDER BY Mark DESC,`count` DESC LIMIT 1" );
	//$rate_memb_week   = db_arr( "SELECT Headline, NickName, Member, COUNT(*) AS `count`, SUM(Mark)/COUNT(*) AS mark, ID, Pic_0_addon  FROM `Votes` INNER JOIN Profiles ON (ID = Member) WHERE Status = 'Active' AND TO_DAYS(NOW()) - TO_DAYS(`Date`) <= 7 GROUP BY Member ORDER BY Mark DESC,`count` DESC LIMIT 1" );

	$ret = '';
//#####################################################################
	$ret .= '<div class="clear_both"></div>';
	$ret .= '<div id="prof_of_month">';
		$ret .= '<div class="top_rated_head">';
			$ret .= _t("_Month");
		$ret .= '</div>';
		if( 0 < $rate_memb_month['ID'] )
		{		
			$ret .= get_member_thumbnail($rate_memb_month['ID'], 'none' );
            $ret .= '<div class="rate_block_position">';
            $ret .= $oVotingMonth->getSmallVoting(0);
			$ret .= '</div>';
		}
		else
		{
			$ret .= '<div class="top_prof_not_avail">';
				$ret .= '<div class="no_result">'; 
					$ret .= '<div>';
						$ret .= _t("_no_top_month");
					$ret .= '</div>';
				$ret .= '</div>';
			$ret .= '</div>';
		}
	$ret .= '<div class="clear_both"></div></div>';
//#####################################################################
	$ret .= '<div id="prof_of_week">';
		$ret .= '<div class="top_rated_head">';
			$ret .= _t("_Week");
		$ret .= '</div>';
		if( 0 < $rate_memb_week['ID'] )
		{
			$ret .= get_member_thumbnail($rate_memb_week['ID'], 'none');
            $ret .= '<div class="rate_block_position">';
            $ret .= $oVotingWeek->getSmallVoting(0);
			$ret .= '</div>';
		}
		else
		{
			$ret .= '<div class="top_prof_not_avail">';
				$ret .= '<div class="no_result">'; 
					$ret .= '<div>';
						$ret .= _t("_no_top_week");
					$ret .= '</div>';
				$ret .= '</div>';
			$ret .= '</div>';
		}
	$ret .= '<div class="clear_both"></div></div>';
//#####################################################################
	$ret .= '<div id="prof_of_day">';
		$ret .= '<div class="top_rated_head">';
			$ret .= _t("_Day");
		$ret .= '</div>';
		if( 0 < $rate_memb_day['ID'] )
		{
			$ret .= get_member_thumbnail($rate_memb_day['ID'], 'none');
            $ret .= '<div class="rate_block_position">';
            $ret .= $oVotingDay->getSmallVoting(0);
			$ret .= '</div>';
		}
		else
		{
			$ret .= '<div class="top_prof_not_avail">';
				$ret .= '<div class="no_result">'; 
					$ret .= '<div>';
						$ret .= _t("_no_top_day");
					$ret .= '</div>';
				$ret .= '</div>';
			$ret .= '</div>';
		}
	$ret .= '<div class="clear_both"></div></div>';
	
	
	$ret .= '<div class="clear_both"></div>';

	
	return DesignBoxContent ( _t($sCaption), $ret, 1 );
}

/**
 * News Letters block
 */
function PageCompSubscribe( $sCaption )
{
	global $site;
	//global $tmpl;
	global $PageCompNewsLetters_db_num;
	global $oTemplConfig;

	
	$ret = '';
	$ret .= '<div class="text">' . _t("_SUBSCRIBE_TEXT", $site['title']) . '</div>';
	$ret .= '<div class="email_here" style="border:0px solid red; text-align:center; margin-bottom:5px;">' . _t("_YOUR_EMAIL_HERE") . ':</div>';
	$ret .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
		$ret .= '<input type="hidden" name="total_c2" value="' . $total_c2 . '" />';
		$ret .= '<input type="hidden" name="total_c" value="' . $total_c . '" />';
		$ret .= '<div class="input" style="border:0px solid red; text-align:center; margin-bottom:5px;">';
			$ret .= '<input name="subscribe" type="text" size="18"  onkeyup="if( emailCheck( this.value ) ) this.form.subscr.disabled=false; else this.form.subscr.disabled=true;" />';
		$ret .= '</div>';
		$ret .= '<center><input class="button" type=submit name=vote_submit value="Subscribe" disabled="disabled" id="subscr" /></center>';
		$ret .= '<input type="hidden" name="subscribe_submit" value="true" />';
	$ret .= '</form>';

	return DesignBoxContent ( _t($sCaption), $ret, 1 );
	
}

/**
 * Success story  block
 */
function PageCompFeedback( $sCaption )
{
    global $site;
	global $PageCompSuccessStory_db_num;
	global $oTemplConfig;
	
	
	//get last success story
	$story_limit_chars	= getParam("max_story_preview");
	$story_arr			= db_arr("SELECT Profiles.ID, Profiles.NickName, `Text`, `Header`, Stories.ID AS storyID FROM Stories INNER JOIN Profiles ON ( Profiles.ID = Sender )  WHERE active = 'on'  AND Status = 'Active' ORDER BY RAND() DESC LIMIT 1");
	$story_count		= db_arr("SELECT COUNT(ID) FROM `Stories` WHERE `active` = 'on'");

	$ret = '';
	if( $story_arr )
	{
		$sStoryLink = $site['url'] . 'story_view.php?ID=' . $story_arr['storyID'];
		
		$sText = strip_tags( $story_arr['Text'] );
		if( strlen( $sText ) > $story_limit_chars )
			$sText = mb_substr( $sText, 0, $story_limit_chars ) . '<a href="' . $sStoryLink . '">[...]</a>';
		
		$ret .= '<div class="clear_both"></div>';
		$ret .= '<div class="icon_block">
					'.get_member_icon( $story_arr['ID'] ).'
				</div>';

		$ret .= '<div class="blog_wrapper_n">';
			$ret .= '<div class="subject">';
				$ret .= '<a href="' . $sStoryLink . '" class="bottom_text">';
					$ret .= process_line_output( $story_arr['Header'] );
				$ret .= '</a>';
			$ret .= '</div>';
			$ret .= '<div class="author">';
				$ret .= _t( '_By Author' ) . '<a href="' . getProfileLink($story_arr['ID']) . '">';
					$ret .= process_line_output( $story_arr['NickName'] );
				$ret .= '</a>';
			$ret .= '</div>';

			$ret .= '<div class="text">';
				$ret .= $sText;
			$ret .= '</div>';
		$ret .= '</div>';
		
		$ret .= '<div class="clear_both"></div>';
	}
	else
	{
		$ret .= '<div class="no_result"><div>';
			$ret .= _t("_No success story available.");
		$ret .= '</div></div>';
	}
	
	if( $story_count['0'] > 1 )
	{
		$ret .= '<div style="position:relative; text-align:center;">';
			$ret .= '<a href="' . $site['url'] . 'stories.php">' . _t("_Read more") . '</a>'; 
		$ret .= '</div>';
	}
	
	return DesignBoxContent ( _t($sCaption), $ret, 1 );
}

/**
 * Latest News block
 */
function PageCompNews( $sCaption )
{
    global $site;
	global $PageCompNews_db_num;
	global $short_date_format;
	global $oTemplConfig;
	$php_date_format = getParam( 'php_date_format' );	

	// news
	$news_limit_chars = getParam("max_news_preview");
	$max_news_on_home = getParam("max_news_on_home");
	$news_res = db_res("SELECT `Header`, `Snippet`, `News`.`ID` AS `newsID`, UNIX_TIMESTAMP( `Date` ) AS 'Date' FROM `News` ORDER BY `Date` DESC LIMIT $max_news_on_home");

	$news_count = db_arr("SELECT COUNT(ID) FROM `News`");
	$news_counter = $news_count['0'];

	$ret = '';
	
	if( $news_counter > 0 )
	{
		while( $news_arr = mysql_fetch_assoc($news_res) )
		{
		
				//$ret .= '<img src="' . $site['icons'] . 'news.gif" alt="" />';
				//$ret .= '<span style="position:relative; left:5px; bottom:3px;">';
				//$ret .= '</span>';
			//$ret .= '<div class="news_divider"></div>';
			
			$ret .= '<div class="newsWrap">';
				$ret .= '<div class="newsHead">';
						$ret .= '<a href="' . $site['url'] . 'news_view.php?ID=' . $news_arr['newsID'] . '">';
							$ret .= process_line_output( $news_arr['Header'] );
						$ret .= '</a>';
				$ret .= '</div>';
				
				$ret .= '<div class="newsInfo"><img src="' . getTemplateIcon( 'clock.gif' ) . '" />' . date( $php_date_format, $news_arr['Date'] ) . '</div>';
				
				$ret .= '<div class="newsText">';
					$ret .= process_text_withlinks_output( $news_arr['Snippet'] );
				$ret .= '</div>';
			$ret .= '</div>';
			
		}
		
		if( $news_counter > $max_news_on_home )
		{
			$ret .= '<div style="position:relative; text-align:center;">';
				$ret .= '<a href="' . $site['url'] . 'news.php">' . _t("_Read news in archive") . '</a>';
			$ret .= '</div>';
		}
	}
	else
	{
		$ret .= '<div class="no_result"><div>' . _t("_No news available") . '</div></div>';
	}
	
	
	return DesignBoxContent( _t($sCaption), $ret, 1 );
}

/**
 * Survey block
 */
function PageCompSitePolls()
{
    global $site;
    global $PageCompSurvey_db_num;
	global $oTemplConfig;
    
	// survey
	$survey_arr = db_arr("SELECT `Question`, `ID` FROM `polls_q` WHERE `Active` = 'on' ORDER BY RAND() LIMIT 1");
	$survey_a_res = db_res("SELECT `Answer`, `IDanswer` FROM `polls_a` WHERE ID = '" . (int)$survey_arr['ID'] . "'");

	
	$ret = '';
	if( $survey_arr )
	{	
		$poll_question = process_line_output( $survey_arr['Question'] );
		$ret .= '<div class="survey_block">';
			$ret .= '<div class="survey_question">' . $poll_question . '</div>';
			$ret .= '<div class="survey_answer_block">';
			$ret .= '<form method="post" action="poll.php">';
				$ret .= '<input type="hidden" name="ID" value="' . $survey_arr['ID'] . '" />';
				$j = 1;
				while ( $survey_a_arr = mysql_fetch_array($survey_a_res) )
				{
					$answer_text = process_line_output( $survey_a_arr['Answer'] );
					$ret .= '<div class="survey_answer" ' . $add . '>';
						$ret .= '<input type="radio" name="vote" id="ans' . $survey_a_arr['IDanswer'] . '" value="' . $survey_a_arr['IDanswer'] . '"  style="background-color:transparent;"/>';
						$ret .= '<span style="margin-left:5px;"><label for="ans' . $survey_a_arr['IDanswer'] . '">' . $answer_text . '</label></span>';
					$ret .= '</div>';
					
					$j ++;
				}
				$ret .= '<div style="margin-top:10px; height:auto; line-height:18px; vertical-align:middle; text-align:center; border:0px solid red;">';
					$ret .= '<span>';
					$ret .= '<input class="button" type=submit name=vote_submit value="Cast my vote" />';
					$ret .= '</span><br />';
					$ret .= '<span style="margin-bottom:2px;">';
						$ret .= ' <a href="poll.php?ID=' . $survey_arr['ID'] . '">' . _t("_Results") . '</a> | <a href="polls.php">' . _t("_Polls") . '</a>';
					$ret .= '</span>';
				$ret .= '</div>';
			$ret .= '</form>';
			$ret .= '</div>';
		$ret .= '</div>';
	}
	else
	{
		$ret .= '<div class="no_result"><div>' . _t("_No polls available") . '</div></div>';
	}
	
	return $ret;
}

/**
 * Featured members block
 */
function PageCompFeatured( $sCaption )
{
	global $site;
	global $prof;
	//global $tmpl;
	global $PageCompFeatured_db_num;
	global $max_thumb_width;
	global $max_thumb_height;
	global $oTemplConfig;

	
	$feature_num 	= getParam('featured_num');
	$feature_mode 	= getParam("feature_mode");

	// get random featured profiles
	//$max_thumb_width = getParam("thumb_width");
	//$max_thumb_height = getParam ("thumb_height");

	if ( $feature_num )
	{
		$featured_res = db_res( "SELECT * FROM `Profiles` WHERE `Status` = 'Active' AND `Featured` = '1' ORDER BY RAND() LIMIT $feature_num" );
		
		//$ret .= '<div class="featured_container">';
			$ret .= '<div class="clear_both"></div>';
		
		if( mysql_num_rows( $featured_res ) > 0 )
		{
			$j=1;
			while( $featured_arr = mysql_fetch_assoc( $featured_res ) )
			{
				//$ret .= print_r($featured_arr, true);
				
				$age_str = _t("_y/o", age( $featured_arr['DateOfBirth'] ));
				$y_o_sex = $age_str . '&nbsp;' . _t("_".$featured_arr['Sex']);
				
				$featured_coutry = _t("__".$prof['countries'][$featured_arr['Country']]);
				
				if( ($j % 3) != 0 )
					$ret .= '<div class="featured_block_1">';
				else 
					$ret .= '<div class="featured_block_2">';
					$ret .= get_member_thumbnail( $featured_arr['ID'], 'none' );
				$ret .= '</div>';
				
				$j++;
			}
		}
		else
		{
			$ret .= '<div class="no_result">';
				$ret .= '<div>';
					$ret .= _t("_No results found");
				$ret .= '</div>';
			$ret .= '</div>';
		}
		//$ret .= '</div>
		$ret .= '<div class="clear_both"></div>';
	}
	
	return DesignBoxContent( _t($sCaption), $ret, 1 );
}

function PageCompTopMembers( $sCaption )
{
	ob_start();
	?>
		<div id="show_members"><?= PageCompTopMembersContent( $sCaption ) ?></div>
	<?
	
	return ob_get_clean();
}

function PageCompTopMembersContent( $sCaption )
{
	global $tmpl;

	// number of profiles
	$max_num	= (int) getParam( "top_members_max_num" );
		
	//	Get Sex from GET data
	if ( $_GET['Sex'] && $_GET['Sex'] != "all" )
	{
		$sex = process_db_input( $_GET['Sex'] );
		$query_add = " AND `Sex` = '$sex'";
	}
	else
	{
		$sex = "all";
		$query_add = "";
	}

	$query = "
		SELECT
			`Profiles`.*
		";

	if ( $_GET['Mode'] == 'online' or
		 $_GET['Mode'] == 'rand' or
		 $_GET['Mode'] == 'last' or
		 $_GET['Mode'] == 'top' )
			$mode = $_GET['Mode'];
	else
		$mode = 'last';
	
	$menu = '<div class="dbTopMenu">';
	foreach( array( 'last', 'top', 'online', 'rand' ) as $myMode )
	{
		switch ( $myMode )
		{
			case 'online':
				if( $mode == $myMode )
					$filter = " FROM `Profiles` WHERE `LastNavTime` > SUBDATE(NOW(), INTERVAL ".(int)getParam( "member_online_time" )." MINUTE) AND `Status` = 'Active' AND `PrimPhoto` != 0 $query_add";
				$modeTitle = _t('_Online');
			break;
			case 'rand':
				if( $mode == $myMode )
					$filter = " FROM `Profiles` WHERE `Status` = 'Active' AND `PrimPhoto` != 0 $query_add ORDER BY RAND()";
				$modeTitle = _t('_Random');
			break;
			case 'last':
				if( $mode == $myMode )
					$filter = " FROM `Profiles` WHERE `Status` = 'Active' $query_add ORDER BY `LastReg` DESC";
				$modeTitle = _t('_Latest');
			break;
			case 'top':
				if( $mode == $myMode )
				{
					$oVotingView = new BxTemplVotingView ('profile', 0, 0);
	
					$aSql        = $oVotingView->getSqlParts('`Profiles`', '`ID`');
					$sqlOrderBy  = $oVotingView->isEnabled() ? " ORDER BY (`pr_rating_sum`/`pr_rating_count`) DESC, `pr_rating_count` DESC, `LastReg` DESC" : $sqlOrderBy ;
					$sqlFields   = $aSql['fields'];
					$sqlLJoin    = $aSql['join'];
					$filter      = "$sqlFields FROM `Profiles` $sqlLJoin WHERE `Status` = 'Active' AND `pr_rating_count` > 2 $query_add $sqlOrderBy";
					$filter2      = " FROM `Profiles` $sqlLJoin WHERE `Status` = 'Active' AND `pr_rating_count` > 2 $query_add $sqlOrderBy";
				}	
				$modeTitle = _t('_Top');
			break;
		}
		
		if( $myMode == $mode )
			$menu .= "<div class=\"active\">$modeTitle</a></div>";
		else
			$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?Mode=$myMode&amp;Sex=$sex\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_members', this.href+'&amp;show_only=members'); return false;\">$modeTitle</a></div>";
	}
		$menu .= '<div class="clear_both"></div>';
	$menu .= '</div>';
	
	$ret = '';
	
	$aNum = db_arr( "SELECT COUNT(`Profiles`.`ID`) " . (isset($filter2) ? $filter2 : $filter) );
	$num = (int)$aNum[0];
	
	if( $num )
	{
		$pages = ceil( $num / $max_num );
		$page = (int)$_GET['page'];
		
		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $pages )
			$page = $pages;
		
		$sqlFrom = ( $page - 1 ) * $max_num;
		$limit = " LIMIT $sqlFrom, $max_num";
		
		$templ_search = file_get_contents( "{$dir['root']}templates/tmpl_{$tmpl}/topmebers_index.html" );
		
		$result = db_res( $query.$filter.$limit );
		
		$iCounter = 1;
		$ret .= '<div class="clear_both"></div>';
		while ( $p_arr = mysql_fetch_array( $result ) )
		{
			if( ($iCounter % 3) != 0 )
				$ret .= PrintSearhResult( $p_arr, $templ_search, 1 );
			else
				$ret .= PrintSearhResult( $p_arr, $templ_search, 2 );
			$iCounter++;
		}
		
		$ret .= '<div class="clear_both"></div>';
		
		if( $pages > 1 )
		{
			$ret .= '<div class="dbBottomMenu">';
		
			if( $page > 1 )
			{
				$prevPage = $page - 1;
				$ret .= "
					<a href=\"{$_SERVER['PHP_SELF']}?Mode=$mode&amp;page=$prevPage\"
					  class=\"backMembers\"
					  onclick=\"getHtmlData( 'show_members', this.href+'&amp;show_only=members'); return false;\">"._t('_Back')."</a>
				";
			}
			
			if( $page < $pages )
			{
				$nextPage = $page + 1;
				$ret .= "
					<a href=\"{$_SERVER['PHP_SELF']}?Mode=$mode&amp;page=$nextPage\"
					  class=\"moreMembers\"
					  onclick=\"getHtmlData( 'show_members', this.href+'&amp;show_only=members'); return false;\">"._t('_Next')."</a>
				";
			}
			
			$ret .= '</div>';
		}
	}
	else
	{
		$ret .= '<div class="no_result">';
			$ret .= '<div>';
				$ret .= _t("_No results found");
			$ret .= '</div>';
		$ret .= '</div>';
	}
	
	$ret .= '<div class="clear_both"></div>';
	
	return DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
}

function PageCompTopPhotos( $sCaption )
{
	ob_start();
	?>
		<div id="show_photos"><?= PageCompTopPhotosContent( $sCaption ) ?></div>
	<?
	
	return ob_get_clean();
}


function PageCompTopPhotosContent( $sCaption )
{
    global $site;
	global $tmpl;

	// number of photos
	$max_num	= (int)getParam("top_photos_max_num");
		
	$sqlSelect = "
		SELECT
			`media`.`med_id`,
			`med_prof_id`,
			`med_file`,
			`med_title`";
	
	$sqlFrom = "
		FROM `media`
		INNER JOIN `Profiles` ON
			( `Profiles`.`ID` = `media`.`med_prof_id` )";
	
	$sqlWhere = "
		WHERE
			`med_type` = 'photo' AND
			`med_status` = 'active' AND
			`Profiles`.`Status` = 'Active'";

	
	if ( $_GET['Mode_p'] == 'rand' or
		 $_GET['Mode_p'] == 'last' or
		 $_GET['Mode_p'] == 'top' )
			$mode = $_GET['Mode_p'];
	else
		$mode = 'last';
	
	$menu = '<div class="dbTopMenu">';
	foreach( array( 'last', 'top', 'rand' ) as $myMode )
	{
		switch ( $myMode )
		{
			case 'last':
				if( $mode == $myMode )
					$sqlOrder = "
		ORDER BY `med_date` DESC";
				$modeTitle = _t('_Latest');
			break;
			case 'rand':
				if( $mode == $myMode )
					$sqlOrder = "
		ORDER BY RAND()";
				$modeTitle = _t('_Random');
			break;
			case 'top':
				if( $mode == $myMode )
				{
					$sqlSelect .= ",
		(`med_rating_sum`/`med_rating_count`) AS `avg_mark`";
					$sqlFrom .= "
		INNER JOIN `media_rating` ON
			( `media`.`med_id` = `media_rating`.`med_id` ) ";
					$sqlOrder = "
		ORDER BY `avg_mark` DESC";
				}
				$modeTitle = _t('_Top');
			break;
		}
		
		if( $myMode == $mode )
			$menu .= "<div class=\"active\">$modeTitle</a></div>";
		else
			$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?Mode_p=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_photos', this.href+'&amp;show_only=photos'); return false;\">$modeTitle</a></div>";
	}
		$menu .= '<div class="clear_both"></div>';
	$menu .= '</div>';
	
	$ret = '';
	
	$aNum = db_arr( "SELECT COUNT(`media`.`med_id`) $sqlFrom $sqlWhere" );
	$num = (int)$aNum[0];
	if( $num )
	{
		$pages = ceil( $num / $max_num );
		$page = (int)$_GET['page_p'];
		
		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $pages )
			$page = $pages;
		
		$sqlLimitFrom = ( $page - 1 ) * $max_num;
		$sqlLimit = "
		LIMIT $sqlLimitFrom, $max_num";
		
		$max_thumb_width  = (int)getParam( 'max_thumb_width' );
		$max_thumb_height = (int)getParam( 'max_thumb_height' );
		
		$result = db_res( $sqlSelect.$sqlFrom.$sqlWhere.$sqlOrder.$sqlLimit );
		$iCounter = 1;
		$ret .= '<div class="clear_both"></div>';
		while ( $ph_arr = mysql_fetch_assoc( $result ) )
		{
			$urlImg = "{$site['profileImage']}{$ph_arr['med_prof_id']}/thumb_{$ph_arr['med_file']}";
			$urlSpacer = getTemplateIcon( 'spacer.gif' );
			
			$ph_arr['med_title'] = htmlspecialchars_adv( $ph_arr['med_title'] );
			$memNickName = getNickName( $ph_arr['med_prof_id'] );
			$sProfileLink = getProfileLink($ph_arr['med_prof_id']);
			if( ($iCounter % 3) != 0 )
				$ret .= '<div class="topmembers_block_1">';
			else
				$ret .= '<div class="topmembers_block_2">';
			$ret .= <<<EOJ
				<div class="thumbnail_block">
					<a href="{$site['url']}photos_gallery.php?ID={$ph_arr['med_prof_id']}&amp;photoID={$ph_arr['med_id']}"
					  title="{$ph_arr['med_title']}">
						<img style="width:{$max_thumb_width}px;height:{$max_thumb_height}px;background-image:url($urlImg);"
						  src="$urlSpacer" />
					</a>
					<div class="topmembers_nickname">
						<a href="{$sProfileLink}">$memNickName</a>
					</div>
				</div>
			</div>
EOJ;
			$iCounter++;
		}
		
		$ret .= '<div class="clear_both"></div>';
		
		if( $pages > 1 )
		{
			$ret .= '
			<div class="dbBottomMenu">';
		
			if( $page > 1 )
			{
				$prevPage = $page - 1;
				$ret .= "
					<a href=\"{$_SERVER['PHP_SELF']}?Mode_p=$mode&amp;page_p=$prevPage\"
					  class=\"backMembers\"
					  onclick=\"getHtmlData( 'show_photos', this.href+'&amp;show_only=photos'); return false;\">"._t('_Back')."</a>
				";
			}
			
			if( $page < $pages )
			{
				$nextPage = $page + 1;
				$ret .= "
					<a href=\"{$_SERVER['PHP_SELF']}?Mode_p=$mode&amp;page_p=$nextPage\"
					  class=\"moreMembers\"
					  onclick=\"getHtmlData( 'show_photos', this.href+'&amp;show_only=photos'); return false;\">"._t('_Next')."</a>
				";
			}
			
			$ret .= '</div>';
		}
	}
	else
	{
		$ret .= '<div class="no_result">';
			$ret .= '<div>';
				$ret .= _t("_No results found");
			$ret .= '</div>';
		$ret .= '</div>';
	}
	$ret .= '<div class="clear_both"></div>';
	return DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
}

function PageCompProfilePoll( $sCaption )
{
	ob_start();
	?>
		<div id="profile_poll_cont"><?= PageCompProfilePollContent( $sCaption ) ?></div>
	<?
	return ob_get_clean();
}

function PageCompProfilePollContent($sCaption)
{
    
    global $PageCompProfilePoll_db_num;
	global $oTemplConfig;
    global $prof;

    $query = "
		SELECT
			`id_poll`,
			`id_profile`,
			`Profiles`.*
		FROM `ProfilesPolls`
		LEFT JOIN `Profiles` ON
			`id_profile` = `Profiles`.`ID`
		WHERE
			`poll_status` = 'active'
			AND `poll_approval`
		";
    
    $mode = strlen($_GET['ppoll_mode']) ? $_GET['ppoll_mode'] : 'last';
	
	$menu = '<div class="dbTopMenu">';
	foreach( array( 'admin', 'last', 'top', 'rand' ) as $sMyMode )
	{
	    switch ( $sMyMode )
	    {
			//admin polls
			case 'admin':
				$sModeTitle = _t( '_Admin' );
			break;
			
			// random polls
			case 'rand':
				if( $mode == $sMyMode )
					$query .= " ORDER BY RAND() LIMIT 2";
				$sModeTitle = _t( '_Random' );
			break;

			// latest polls
			case 'last':
				if( $mode == $sMyMode )
					$query .= " ORDER BY id_poll DESC LIMIT 2";
				$sModeTitle = _t( '_Latest' );
			break;

			// top polls
			case 'top':
				if( $mode == $sMyMode )
					$query .= " ORDER BY poll_total_votes DESC LIMIT 2";
				$sModeTitle = _t( '_Top' );
			break;
	    }
		if( $sMyMode == $mode )
			$menu .= "<div class=\"active\">$sModeTitle</a></div>";
		else
			$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?ppoll_mode=$sMyMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'profile_poll_cont', this.href+'&amp;show_only=ppoll');return false;\">$sModeTitle</a></div>";
	}
		$menu .= '<div class="clear_both"></div>';
	$menu .= '</div>';

	
	if( $mode == 'admin' )
	{
		$ret = PageCompSitePolls();
	}
	else
	{
	$ret = '';
	
	$poll_res = db_res( $query );
	if ( mysql_num_rows($poll_res) == 0 )
	{
		$ret .= '<div class="no_result"><div>';
			$ret .= _t("_No profile polls available.");
		$ret .= '</div></div>';
	}
	else while ( $poll_arr = mysql_fetch_array( $poll_res ) )
	{
		$age_str = _t("_y/o", age( $poll_arr['DateOfBirth'] ));
		$y_o_sex = $age_str . '&nbsp;' . _t("_".$poll_arr['Sex']);
			
		$poll_coutry = _t("__".$prof['countries'][$poll_arr['Country']]);
    	
    	$NickName = "<b><a href=\"".getProfileLink($poll_arr['ID'])."\">{$poll_arr['NickName']}</a></b>";
		
		$ret .= '<div class="pollInfo">';
			$ret .= get_member_icon( $poll_arr['ID'], 'left' );
			$ret .= '<div class="featured_info">';
				$ret .= '<div class="featured_nickname">';
						$ret .= $NickName;
		$ret .= '</div>';
		
		$ret .= '<div class="pollBody">';
			$ret .= ShowPoll( $poll_arr['id_poll'] );
		$ret .= '</div>';
				$ret .= '</div>';
		$ret .= '<div class="clear_both"></div>';
			$ret .= '</div>';
			
			$ret .= '<div class="clear_both"></div>';
		}
	}

	return DesignBoxContent ( _t($sCaption), $ret, 1, $menu );

}

function PageCompTags( $sCaption )
{
	$ret = '<div id="show_tags">' . PageCompTagsContent( $sCaption ) . '</div>';
	
	return $ret;
}

function PageCompTagsContent( $sCaption )
{
	global $oTemplConfig;
	global $site;
	
	$mode = $_REQUEST['tags_mode'];
	
	if(
		$mode == 'profile' or
		$mode == 'blog'  or
		$mode == 'event' or
		$mode == 'photo' or
		$mode == 'video' or
		$mode == 'music' or
		$mode == 'ad'
	)
	;
	else
		$mode = 'profile';
	
	$sCrtHrefTmpl = '';
	
	$menu = '<div class="dbTopMenu">';
	foreach( array( 'profile', 'blog', 'event', 'photo', 'video', 'music', 'ad' ) as $myMode )
	{
		switch ( $myMode )
		{
			case 'profile':
				$hrefTmpl  = 'search_result.php?tag={tag}';
				$modeTitle = _t('_Profiles');
			break;
			case 'blog':
				$hrefTmpl  = 'blogs.php?action=search_by_tag&tagKey={tag}';
				$modeTitle = _t('_Blogs');
			break;
			case 'event':
				$hrefTmpl  = 'events.php?action=search_by_tag&tagKey={tag}';
				$modeTitle = _t('_Events');
			break;
			case 'photo':
				$hrefTmpl  = 'browsePhoto.php?tag={tag}';
				$modeTitle = _t('_Photos');
			break;
			case 'video':
				$hrefTmpl  = 'browseVideo.php?tag={tag}';
				$modeTitle = _t('_Videos');
			break;
			case 'music':
				$hrefTmpl  = 'browseMusic.php?tag={tag}';
				$modeTitle = _t('_Music');
			break;
			case 'ad':
				$hrefTmpl  = 'classifieds_tags.php?tag={tag}';
				$modeTitle = _t('_Ads');
			break;
		}
		
		if( $myMode == $mode )
		{
			$menu .= "<div class=\"active\">$modeTitle</a></div>";
			$sCrtHrefTmpl = $hrefTmpl;
		}
		else
			$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?tags_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_tags', this.href+'&amp;show_only=tags'); return false;\">$modeTitle</a></div>";
	}
		$menu .= '<div class="clear_both"></div>';
	$menu .= '</div>';
	
	
	$rTags = db_res( "
		SELECT
			`Tag`,
			COUNT(`ID`) AS `count`
		FROM `Tags`
		WHERE `Type` = '$mode'
		GROUP BY `Tag`
		ORDER BY `count` DESC
		LIMIT 50
	" );
	
	if( !mysql_num_rows( $rTags ) )
		$ret = '<div class="no_result"><div>' . _t( '_No tags found here' ) . '</div></div>';
	else
	{
	
		$aTotalTags = array();
		while( $aTag = mysql_fetch_assoc( $rTags ) )
			$aTotalTags[ $aTag['Tag'] ] = $aTag['count'];
		
		ksort( $aTotalTags );
		
		$iMinFontSize = $oTemplConfig -> iTagsMinFontSize;
		$iMaxFontSize = $oTemplConfig -> iTagsMaxFontSize;
		$iFontDiff = $iMaxFontSize - $iMinFontSize;
		
		$iMinRating = min( $aTotalTags );
		$iMaxRating = max( $aTotalTags );
		
		$iRatingDiff = $iMaxRating - $iMinRating;
		$iRatingDiff = ($iRatingDiff==0)? 1:$iRatingDiff;
		
		
		$ret = '<div class="tags_wrapper">';
		
		foreach( $aTotalTags as $sTag => $iCount )
		{
			$iTagSize = $iMinFontSize + round( $iFontDiff * ( ( $iCount - $iMinRating ) / $iRatingDiff ) );
			
			$href = str_replace( '{tag}', urlencode($sTag), $sCrtHrefTmpl );
			
			$ret .= '<span class="one_tag" style="font-size:' . $iTagSize . 'px;">
				<a href="' . $href . '" title="' . _t( '_Count' ) . ':' . $iCount . '">' . htmlspecialchars_adv( $sTag ) .'</a>
			</span>';
		}
		
		$ret .= '</div>';
		
		$ret .= '<div class="clear_both"></div>';
	}
	
	return DesignBoxContent ( _t( $sCaption ), $ret, 1, $menu );
}

function PageCompBlogs( $sCaption )
{
	ob_start();
	?>
		<div id="show_blogs"><?= PageCompBlogsContent( $sCaption )?></div>
	<?
	
	return ob_get_clean();
}

function PageCompBlogsContent( $sCaption ) {
    global $site;
	global $date_format;
	$php_date_format = getParam( 'php_date_format' );
	
	$mode = $_REQUEST['blogs_mode'];
	
	if( $mode != 'rand' and $mode != 'latest' and $mode != 'top' )
		$mode = 'latest';
	
	$menu = '<div class="dbTopMenu">';
	foreach( array( 'latest', 'top', 'rand' ) as $myMode ) {
		switch( $myMode ) {
			case 'top':
				$OrderBy = '`num_com` DESC';
				$sTabTitle  = _t( '_Top' );
			break;
			case 'latest':
				$OrderBy = '`PostDate` DESC';
				$sTabTitle  = _t( '_Latest' );
			break;
			case 'rand':
				$OrderBy = 'RAND()';
				$sTabTitle  = _t( '_Random' );
			break;
		}
		
		if( $mode == $myMode ) {
			$sqlOrderBy = $OrderBy;
			$menu .= "
			<div class=\"active\">$sTabTitle</div>";
		} else {
			$menu .= "
			<div class=\"notActive\">
				<a href=\"{$_SERVER['PHP_SELF']}?blogs_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_blogs', this.href+'&amp;show_only=blogs'); return false;\">$sTabTitle</a>
			</div>";
		}
	}
	$menu .= '
			<div class="clear_both"></div>
		</div>';
	
	$aTotalNum = db_arr( "
		SELECT
			COUNT(*) FROM `BlogPosts`
		WHERE
			`PostStatus` = 'approval' AND
			`PostReadPermission` = 'public'
		" );
	
	$iTotalNum   = $aTotalNum[0];
	
	if( $iTotalNum ) {
		$iResPerPage = (int)getParam("max_blogs_on_home");
		$iTotalPages = ceil( $iTotalNum / $iResPerPage );
		
		$page = (int)$_REQUEST['blogs_page'];
		
		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $iTotalPages )
			$page = $iTotalPages;
		$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;
		
		$iBlogLimitChars = (int)getParam("max_blog_preview");

		$sBlogQuery = "
			SELECT
				DISTINCT `BlogCategories`.`CategoryID` AS `CatID`,
				`BlogCategories`.`OwnerID`,
				`BlogCategories`.`CategoryName`,
				/*`BlogCategories`.`CategoryDesc`,*/
				`BlogCategories`.`CategoryPhoto`,
				`BlogPosts`.`PostID` AS `PostID`,
				`BlogPosts`.`PostCaption` as `PostCaption`,
				LEFT(`BlogPosts`.`PostText`, $iBlogLimitChars) as `PostText`, 
				UNIX_TIMESTAMP( `BlogPosts`.`PostDate` ) as `PostDate_f`,
				`BlogPosts`.`PostReadPermission` as `PostReadPermission`, 
				`BlogPosts`.`PostStatus` as `PostStatus`,
				`BlogPosts`.`PostPhoto`,
				`Profiles`.`ID` AS `ProfID`,
				`Profiles`.`NickName` AS `NickName`,
				COUNT(`BlogPostComments`.`CommentID`) as `num_com`
			FROM `BlogCategories`
			LEFT JOIN `BlogPosts`
				ON `BlogPosts`.`CategoryID` = `BlogCategories`.`CategoryID`
			LEFT JOIN `BlogPostComments`
				USING (`PostID`)
			INNER JOIN `Profiles`
				ON `BlogCategories`.`OwnerID` = `Profiles`.`ID`
			WHERE
				`PostStatus` = 'approval' AND
				`PostReadPermission` = 'public'
			GROUP BY `BlogPosts`.`PostID`
			ORDER BY $sqlOrderBy
			LIMIT $sqlLimitFrom, $iResPerPage
		";

		$rBlog = db_res($sBlogQuery);
		$ret = '';
		$ret .= '<div class="clear_both"></div>';
		while ($arr = mysql_fetch_array($rBlog)) {
			$sLinkMore = '';
			if ( strlen($arr['PostText']) == $iBlogLimitChars ) 
				$sLinkMore = "... <a href=\"".$site['url']."blogs.php?action=show_member_post&ownerID=".$arr['OwnerID']."&post_id=".$arr['PostID']."\">"._t('_Read more')."</a>";
				//blogs.php?action=show_member_post&post_id=5
				//?action=show_member_blog&ownerID=2&category=19

				//<a href="' . $site['url'] . 'blog.php?owner='.$arr['OwnerID'].'&show=blog&blogID=' . $arr['PostID'] . '" class="bottom_text">'.
			$ret .= '
			<div class="blog_wrapper">
				<div class="blog_subject">
					<a href="' . $site['url'] . 'blogs.php?action=show_member_post&ownerID='.$arr['OwnerID'].'&post_id=' . $arr['PostID'] . '" class="bottom_text">'.
						process_line_output( $arr['PostCaption'] ).
					'</a>
				</div>
				<div class="blog_author">'.
					'<span>' . _t( '_By Author', $arr['NickName'], $arr['NickName'] ).'</span>'.
					'<span><img src="' . getTemplateIcon( 'clock.gif' ) . '" />' . date( $php_date_format, $arr['PostDate_f'] ) . '</span>' .
					'<span>' . _t( '_in Category', getTemplateIcon( 'folder_small.png' ), $site['url'].'blogs.php?action=show_member_blog&ownerID='.$arr['OwnerID'].'&category='.$arr['CatID'], process_line_output($arr['CategoryName']) ) . '</span>' . 
					'<span>'. _t( '_comments N', getTemplateIcon( 'add_comment.gif' ), (int)$arr['num_com'] ) . '</span>' . 
				'</div>
				<div class="blog_text">'.
					strip_tags(process_html_output( $arr['PostText'] )).$sLinkMore.
				'</div>
			</div>';
		}
		$ret .= '<div class="clear_both"></div>';
	} else
		$ret .= '<div class="no_result"><div>'._t("_No blogs available").'</div></div>';
	
	if( $iTotalPages > 1 ) {
		$ret .= '
		<div class="dbBottomMenu">';
	
		if( $page > 1 ) {
			$prevPage = $page - 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?blogs_mode=$mode&amp;blogs_page=$prevPage\"
				  class=\"backMembers\"
				  onclick=\"getHtmlData( 'show_blogs', this.href+'&amp;show_only=blogs'); return false;\">"._t('_Back')."</a>
			";
		}
		
		if( $page < $iTotalPages ) {
			$nextPage = $page + 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?blogs_mode=$mode&amp;blogs_page=$nextPage\"
				  class=\"moreMembers\"
				  onclick=\"getHtmlData( 'show_blogs', this.href+'&amp;show_only=blogs'); return false;\">"._t('_Next')."</a>
			";
		}
		
		$ret .= '
			<div class="clear_both"></div>
		</div>';
	}
	
	return DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
}

function PageCompClassifieds( $sCaption )
{
	ob_start();
	?>
		<div id="show_classifieds"><?= PageCompClassifiedsContent( $sCaption )?></div>
	<?
	
	return ob_get_clean();
}

function PageCompClassifiedsContent( $sCaption ) {
    global $site;
	global $date_format;
	$php_date_format = getParam( 'php_date_format' );

	$mode = $_REQUEST['classifieds_mode'];

	if( $mode != 'rand' and $mode != 'latest' and $mode != 'top' )
		$mode = 'latest';

	$menu = '<div class="dbTopMenu">';
	foreach( array( 'latest', 'top', 'rand' ) as $myMode ) {
		switch( $myMode ) {
			case 'top':
				$OrderBy = '`CommCount` DESC';
				$sTabTitle  = _t( '_Top' );
			break;
			case 'latest':
				$OrderBy = '`DateTime` DESC';
				$sTabTitle  = _t( '_Latest' );
			break;
			case 'rand':
				$OrderBy = 'RAND()';
				$sTabTitle  = _t( '_Random' );
			break;
		}

		if( $mode == $myMode ) {
			$sqlOrderBy = $OrderBy;
			$menu .= "
			<div class=\"active\">$sTabTitle</div>";
		} else {
			$menu .= "
			<div class=\"notActive\">
				<a href=\"{$_SERVER['PHP_SELF']}?classifieds_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_classifieds', this.href+'&amp;show_only=classifieds'); return false;\">$sTabTitle</a>
			</div>";
		}
	}
	$menu .= '
			<div class="clear_both"></div>
		</div>';

	$aTotalNum = db_arr( "
		SELECT
			COUNT(*) FROM `ClassifiedsAdvertisements`
		WHERE
			`Status` = 'active'
		" );

	$iTotalNum   = $aTotalNum[0];

	if( $iTotalNum ) {
		//$iResPerPage = (int)getParam("max_classifieds_on_home");
		$iResPerPage = (int)getParam("max_blogs_on_home");
		//$iResPerPage = 3;
		$iTotalPages = ceil( $iTotalNum / $iResPerPage );

		$page = (int)$_REQUEST['classifieds_page'];

		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $iTotalPages )
			$page = $iTotalPages;
		$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;

		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		//$iBlogLimitChars = 50;

		$sBlogQuery = "
			SELECT DISTINCT
			`ClassifiedsAdvertisements`.`ID`,
			`ClassifiedsAdvertisements`.`Subject`,
			`ClassifiedsAdvertisements`.`Media`,
			`Profiles`.`NickName`,
			UNIX_TIMESTAMP( `ClassifiedsAdvertisements`.`DateTime` ) as `DateTime_f`,
			`ClassifiedsAdvertisements`.`DateTime`,
			`Classifieds`.`Name`, `Classifieds`.`ID` AS `CatID`,
			`ClassifiedsSubs`.`NameSub`, `ClassifiedsSubs`.`ID` AS `SubCatID`,
			LEFT(`ClassifiedsAdvertisements`.`Message`, $iBlogLimitChars) as 'Message', 
			COUNT(`ClsAdvComments`.`ID`) AS 'CommCount'
			FROM `ClassifiedsAdvertisements`
			LEFT JOIN `ClassifiedsSubs`
			ON `ClassifiedsSubs`.`ID`=`ClassifiedsAdvertisements`.`IDClassifiedsSubs`
			LEFT JOIN `Classifieds`
			ON `Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`
			LEFT JOIN `Profiles` ON `Profiles`.`ID`=`ClassifiedsAdvertisements`.`IDProfile`
			LEFT JOIN `ClsAdvComments` ON `ClsAdvComments`.`IDAdv`=`ClassifiedsAdvertisements`.`ID`
			GROUP BY `ClassifiedsAdvertisements`.`ID`
			ORDER BY $sqlOrderBy
			LIMIT $sqlLimitFrom, $iResPerPage
		";

		$rBlog = db_res($sBlogQuery);
		$ret = '';

		$oClassifieds = new BxDolClassifieds();

		$ret .= '<div class="clear_both"></div>';
		while ($arr = mysql_fetch_array($rBlog)) {
			if ($mode == 'top' && $arr['CommCount'] == 0)
				continue;
			$sPic = $oClassifieds->getImageCode($arr['Media'],TRUE);

			$sLinkMore = '';
			if ( strlen($arr['Message']) == $iBlogLimitChars ) 
				$sLinkMore = "... <a href=\"".$site['url']."classifieds.php?ShowAdvertisementID=".$arr['ID']."\">"._t('_Read more')."</a>";
			$ret .= '
			<div class="icon_block">
				<div  class="thumbnail_block" style="float:left;">
					<a href="' . $site['url'] . 'classifieds.php?ShowAdvertisementID=' . $arr['ID'] . '" class="bottom_text">
				'.$sPic.'
					</a>
				</div>
			</div>
			<div class="blog_wrapper_n">
				<div class="blog_subject_n">
					<a href="' . $site['url'] . 'classifieds.php?ShowAdvertisementID=' . $arr['ID'] . '" class="bottom_text">'.
						process_line_output( $arr['Subject'] ).
					'</a>
				</div>
				<div class="blog_author_n">'.
					'<span>' . _t( '_By Author', $arr['NickName'], $arr['NickName'] ) . '</span>' .
					'<span><img src="' . getTemplateIcon( 'clock.gif' ) . '" />' . date( $php_date_format, $arr['DateTime_f'] ) . '</span>' .
					'<span>' . _t( '_in Category', getTemplateIcon( 'folder_small.png' ), 'classifieds.php?bClassifiedID='.$arr['CatID'], process_line_output($arr['Name']) ) . 
					' / <a href="'.'classifieds.php?bSubClassifiedID=' . $arr['SubCatID'].'">'.process_line_output($arr['NameSub']).'</a></span>'.
					'<span>' . _t( '_comments N', getTemplateIcon( 'add_comment.gif' ), (int)$arr['CommCount'] ) . '</span>' .
				'</div>
				<div class="blog_text_n">'.
					strip_tags(process_html_output( $arr['Message'] )).$sLinkMore.
				'</div>
			</div>';
		}
		$ret .= '<div class="clear_both"></div>';
	} else
		$ret .= '<div class="no_result"><div>'._t("_No classifieds available").'</div></div>';

	if( $iTotalPages > 1 ) {
		$ret .= '
		<div class="dbBottomMenu">';

		if( $page > 1 ) {
			$prevPage = $page - 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?classifieds_mode=$mode&amp;classifieds_page=$prevPage\"
				  class=\"backMembers\"
				  onclick=\"getHtmlData( 'show_classifieds', this.href+'&amp;show_only=classifieds'); return false;\">"._t('_Back')."</a>
			";
		}

		if( $page < $iTotalPages ) {
			$nextPage = $page + 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?classifieds_mode=$mode&amp;classifieds_page=$nextPage\"
				  class=\"moreMembers\"
				  onclick=\"getHtmlData( 'show_classifieds', this.href+'&amp;show_only=classifieds'); return false;\">"._t('_Next')."</a>
			";
		}

		$ret .= '
			<div class="clear_both"></div>
		</div>';
	}

	return DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
}

function PageCompEvents( $sCaption )
{
	ob_start();
	?>
		<div id="show_events"><?= PageCompEventsContent( $sCaption )?></div>
	<?
	
	return ob_get_clean();
}

function PageCompEventsContent( $sCaption ) {
    global $site;
	global $date_format;
	$php_date_format = getParam( 'php_date_format' );

	$mode = $_REQUEST['events_mode'];

	if( $mode != 'rand' and $mode != 'latest' )
		$mode = 'latest';

	$menu = '<div class="dbTopMenu">';
	foreach( array( 'latest', 'rand' ) as $myMode ) {
		switch( $myMode ) {
			case 'latest':
				$OrderBy = '`EventStart` DESC';
				$sTabTitle  = _t( '_Latest' );
			break;
			case 'rand':
				$OrderBy = 'RAND()';
				$sTabTitle  = _t( '_Random' );
			break;
		}

		if( $mode == $myMode ) {
			$sqlOrderBy = $OrderBy;
			$menu .= "
			<div class=\"active\">$sTabTitle</div>";
		} else {
			$menu .= "
			<div class=\"notActive\">
				<a href=\"{$_SERVER['PHP_SELF']}?events_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_events', this.href+'&amp;show_only=events'); return false;\">$sTabTitle</a>
			</div>";
		}
	}
	$menu .= '
			<div class="clear_both"></div>
		</div>';

	$aTotalNum = db_arr( "
		SELECT
			COUNT(*) FROM `SDatingEvents`
		WHERE
			`Status` = 'Active'
		" );

	$iTotalNum   = $aTotalNum[0];

	if( $iTotalNum ) {
		//$iResPerPage = (int)getParam("max_events_on_home");
		$iResPerPage = (int)getParam("max_blogs_on_home");
		//$iResPerPage = 3;
		$iTotalPages = ceil( $iTotalNum / $iResPerPage );

		$page = (int)$_REQUEST['events_page'];

		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $iTotalPages )
			$page = $iTotalPages;
		$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;

		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		//$iBlogLimitChars = 50;

		$sBlogQuery = "
			SELECT DISTINCT `SDatingEvents`. * , `Profiles`.`NickName`,
			UNIX_TIMESTAMP( `SDatingEvents`.`EventStart` ) as `DateTime_f`,
			LEFT(`SDatingEvents`.`Description`, $iBlogLimitChars) as 'Description_f'
			FROM `SDatingEvents` 
			LEFT JOIN `Profiles` ON `Profiles`.`ID` = `SDatingEvents`.`ResponsibleID` 
			WHERE
			`SDatingEvents`.`Status` = 'Active'
			ORDER BY $sqlOrderBy
			LIMIT $sqlLimitFrom, $iResPerPage
		";

		$rBlog = db_res($sBlogQuery);
		$ret = '';

		$oEvents = new BxDolEvents();

		$ret .= '<div class="clear_both"></div>';
		while ($arr = mysql_fetch_array($rBlog)) {
			$sPic = $oEvents->GetEventPicture($arr['ID']);

			$sLinkMore = '';
			if ( strlen($arr['Description']) == $iBlogLimitChars ) 
				$sLinkMore = "... <a href=\"".$site['url']."events.php?action=show_info&event_id=".$arr['ID']."\">"._t('_Read more')."</a>";
			$ret .= '
			<div class="icon_block">
				'.$sPic.'
			</div>
			<div class="blog_wrapper_n">
				<div class="blog_subject_n">
					<a href="' . $site['url'] . 'events.php?action=show_info&event_id=' . $arr['ID'] . '" class="bottom_text">'.
						process_line_output( $arr['Title'] ).
					'</a>
				</div>
				<div class="blog_author">'.
					'<span>' . _t( '_By Author', $arr['NickName'], $arr['NickName'] ) . '</span>' .
					'<span><img src="' . getTemplateIcon( 'clock.gif' ) . '" />' . date( $php_date_format, $arr['DateTime_f'] ) . '</span>' .
				'</div>
				<div class="blog_text">'.
					strip_tags(process_html_output( $arr['Description_f'] )).$sLinkMore.
				'</div>
			</div>';
		}
		$ret .= '<div class="clear_both"></div>';
	} else
		$ret .= '<div class="no_result"><div>'._t("_No events available").'</div></div>';

	if( $iTotalPages > 1 ) {
		$ret .= '
		<div class="dbBottomMenu">';

		if( $page > 1 ) {
			$prevPage = $page - 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?events_mode=$mode&amp;events_page=$prevPage\"
				  class=\"backMembers\"
				  onclick=\"getHtmlData( 'show_events', this.href+'&amp;show_only=events'); return false;\">"._t('_Back')."</a>
			";
		}

		if( $page < $iTotalPages ) {
			$nextPage = $page + 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?events_mode=$mode&amp;events_page=$nextPage\"
				  class=\"moreMembers\"
				  onclick=\"getHtmlData( 'show_events', this.href+'&amp;show_only=events'); return false;\">"._t('_Next')."</a>
			";
		}

		$ret .= '
			<div class="clear_both"></div>
		</div>';
	}

	return DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
}

function PageCompGroups( $sCaption )
{
	ob_start();
	?>
		<div id="show_groups"><?= PageCompGroupsContent( $sCaption )?></div>
	<?
	
	return ob_get_clean();
}

function PageCompGroupsContent( $sCaption ) {
    global $site;
	global $date_format;
	$php_date_format = getParam( 'php_date_format' );

	$mode = $_REQUEST['groups_mode'];

	if( $mode != 'rand' and $mode != 'latest' )
		$mode = 'latest';

	$menu = '<div class="dbTopMenu">';
	foreach( array( 'latest', 'rand' ) as $myMode ) {
		switch( $myMode ) {
			case 'latest':
				$OrderBy = '`created` DESC';
				$sTabTitle  = _t( '_Latest' );
			break;
			case 'rand':
				$OrderBy = 'RAND()';
				$sTabTitle  = _t( '_Random' );
			break;
		}

		if( $mode == $myMode ) {
			$sqlOrderBy = $OrderBy;
			$menu .= "
			<div class=\"active\">$sTabTitle</div>";
		} else {
			$menu .= "
			<div class=\"notActive\">
				<a href=\"{$_SERVER['PHP_SELF']}?groups_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_groups', this.href+'&amp;show_only=groups'); return false;\">$sTabTitle</a>
			</div>";
		}
	}
	$menu .= '
			<div class="clear_both"></div>
		</div>';

	$aTotalNum = db_arr( "
		SELECT
			COUNT(*) FROM `Groups`
		WHERE
			`status` = 'Active'
		" );

	$iTotalNum   = $aTotalNum[0];

	if( $iTotalNum ) {
		//$iResPerPage = (int)getParam("max_groups_on_home");
		$iResPerPage = (int)getParam("max_blogs_on_home");
		//$iResPerPage = 3;
		$iTotalPages = ceil( $iTotalNum / $iResPerPage );

		$page = (int)$_REQUEST['groups_page'];

		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $iTotalPages )
			$page = $iTotalPages;
		$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;

		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		//$iBlogLimitChars = 50;

		$sBlogQuery = "
			SELECT DISTINCT `Groups`.`ID`, `Groups`.`Name`,
			LEFT(`Groups`.`Desc`, $iBlogLimitChars) as 'Desc_f', 
			UNIX_TIMESTAMP( `Groups`.`created` ) as `DateTime_f`,
			`Profiles`.`NickName`,
			`GroupsCateg`.`Name` AS 'CategName', `GroupsCateg`.`ID` AS `CategID`
			FROM `Groups`
			LEFT JOIN `GroupsCateg` ON `GroupsCateg`.`ID` = `Groups`.`categID` 
			LEFT JOIN `Profiles` ON `Profiles`.`ID` = `Groups`.`creatorID` 
			WHERE
			`Groups`.`Status` = 'Active'
			ORDER BY $sqlOrderBy
			LIMIT $sqlLimitFrom, $iResPerPage
		";

		$rBlog = db_res($sBlogQuery);
		$ret = '';

		$oEvents = new BxDolEvents();

		$ret .= '<div class="clear_both"></div>';
		while ($arr = mysql_fetch_array($rBlog)) {
			$sPic = $oEvents->GetGroupPicture($arr['ID']);

			$sLinkMore = '';
			if ( strlen($arr['Desc']) == $iBlogLimitChars ) 
				$sLinkMore = "... <a href=\"".$site['url']."group.php?ID=".$arr['ID']."\">"._t('_Read more')."</a>";
			$ret .= '
			<div class="icon_block">
				'.$sPic.'
			</div>
			<div class="blog_wrapper_n">
				<div class="blog_subject_n">
					<a href="' . $site['url'] . 'group.php?ID=' . $arr['ID'] . '" class="bottom_text">'.
						process_line_output( $arr['Name'] ).
					'</a>
				</div>
				<div class="blog_author">'.
					'<span>' . _t( '_By Author', $arr['NickName'], $arr['NickName'] ) . '</span>' .
					'<span><img src="' . getTemplateIcon( 'clock.gif' ) . '" />' . date( $php_date_format, $arr['DateTime_f'] ) . '</span>' .
					'<span>' . _t( '_in Category', getTemplateIcon( 'folder_small.png' ), 'groups_browse.php?categID='.$arr['CategID'], process_line_output($arr['CategName']) ) . '</span>' .
				'</div>
				<div class="blog_text">'.
					strip_tags(process_html_output( $arr['Desc_f'] )).$sLinkMore.
				'</div>
			</div>';
		}
		$ret .= '<div class="clear_both"></div>';
	} else
		$ret .= '<div class="no_result"><div>'._t("_No groups available").'</div></div>';

	if( $iTotalPages > 1 ) {
		$ret .= '
		<div class="dbBottomMenu">';

		if( $page > 1 ) {
			$prevPage = $page - 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?groups_mode=$mode&amp;groups_page=$prevPage\"
				  class=\"backMembers\"
				  onclick=\"getHtmlData( 'show_groups', this.href+'&amp;show_only=groups'); return false;\">"._t('_Back')."</a>
			";
		}

		if( $page < $iTotalPages ) {
			$nextPage = $page + 1;
			$ret .= "
				<a href=\"{$_SERVER['PHP_SELF']}?groups_mode=$mode&amp;groups_page=$nextPage\"
				  class=\"moreMembers\"
				  onclick=\"getHtmlData( 'show_groups', this.href+'&amp;show_only=groups'); return false;\">"._t('_Next')."</a>
			";
		}

		$ret .= '
			<div class="clear_both"></div>
		</div>';
	}

	return DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
}

function PageCompQuickSearchIndex( $sCaption )
{
	global $site;
	//global $oTemplConfig;
	global $search_start_age;
	global $search_end_age;

    $gl_search_start_age    = (int)$search_start_age;
    $gl_search_end_age      = (int)$search_end_age;

    if ( (int)$_COOKIE['memberID'] > 0 )
    {
        $arr_sex = getProfileInfo( (int)$_COOKIE['memberID'] ); //db_arr("SELECT Sex FROM Profiles WHERE ID = ".(int)$_COOKIE['memberID']);
        $member_sex = $arr_sex['Sex'];
    }
    else
        $member_sex = 'male';


	$ret = '<div class="qsi_wrapper">';
		$ret .= '<form action="search_result.php" method="get">';
			$ret .= '<div class="qsi_line">';
				$ret .= '<div class="qsi_first">';
					$ret .= _t("_I am a");
				$ret .= '</div>';
				$ret .= '<div class="qsi_second">';
					$ret .= '<select name="Sex">';
						$ret .= SelectOptions("Sex", $member_sex);
					$ret .= '</select>';
				$ret .= '</div>';
			$ret .= '</div>';

			$ret .= '<div class="qsi_line">';
				$ret .= '<div class="qsi_first">';
					$ret .= _t("_seeking a");
				$ret .= '</div>';
				$ret .= '<div class="qsi_second">';
					$ret .= '<select name="LookingFor">';
						$ret .= SelectOptions("LookingFor", ($member_sex=='male' ? 'female':'male') );
					$ret .= '</select>';
				$ret .= '</div>';
			$ret .= '</div>';

			$ret .= '<div class="qsi_line">';
				$ret .= '<div class="qsi_first">';
					$ret .= _t("_aged");
				$ret .= '</div>';
				$ret .= '<div class="qsi_second">';
					$ret .= '<span style="position:absolute; top:0px; left:0px;">';
						$ret .= '<select name="DateOfBirth_start">';
						for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
						{
							$sel = ($i == $gl_search_start_age) ? 'selected="selected"' : '';
							$ret .= '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
						}
						$ret .= '</select>';
					$ret .= '</span>';
					$ret .= '<div style="position:absolute; top:2px; left:60px;">';
						$ret .= _t("_to");
					$ret .= '</div>';
					$ret .= '<span style="position:absolute; top:0px; left:80px;">';
						$ret .= '<select name="DateOfBirth_end">';
						$i = 0;
						for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
						{
							$sel = ($i == $gl_search_end_age) ? 'selected="selected"' : '';
							$ret .= '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
							$ret .= "\n";
						}
						$ret .= '</select>';
					$ret .= '</span>';
				$ret .= '</div>';
			$ret .= '</div>';

			$ret .= '<div class="qsi_line">';
				$ret .= '<div class="qsi_first">';
					$ret .= _t("_within");
				$ret .= '</div>';
				$ret .= '<div class="qsi_second">';
					$ret .= '<span style="position:absolute; top:0px; left:0px;">';
						$ret .= '<input type="text" name="distance" style="width:46px;" />';
					$ret .= '</span>';
					$ret .= '<span style="position:absolute; top:0px; left:80px;" >';
						$ret .= '<select name="metric" style="position:relative;float:left;width:50px;left:0px;">';
							$ret .= '<option selected="selected" value="miles">' . _t("_miles") . '</option>';
							$ret .= '<option value="km">' . _t("_km") . '</option>';
						$ret .= '</select>';
					$ret .= '</span>';
				$ret .= '</div>';
			$ret .= '</div>';

			$ret .= '<div class="qsi_line">';
				$ret .= '<div class="qsi_first">';
					$ret .= _t("_from ZIP");
				$ret .= '</div>';
				$ret .= '<div class="qsi_second">';
					$ret .= '<input type="text" name="zip" />';
				$ret .= '</div>';
			$ret .= '</div>';

			$ret .= '<div class="qsi_line" style="text-align:center; margin-top:3px;">';
				$ret .= '<input type="checkbox" name="photos_only" id="qsi_photos_only" style="width:15px; height:15px;" /> ';
				$ret .= '<label for="qsi_photos_only">' . _t("_With photos only") . '</label>';
			$ret .= '</div>';

			$ret .= '<div class="qsi_line" style="text-align:center; margin-top:3px;">';
				$ret .= '<input type="submit" value=' . _t( '_Search' ) . ' />';
			$ret .= '</div>';
		$ret .= '</form>';
	$ret .= '</div>';

	return DesignBoxContent( _t($sCaption), $ret, 1 );
}

function PageCompShoutbox()
{
	return DesignBoxContent ( _t("_shout_box_title"), loadShoutBox( 300, 400 ), 1);
}

function PageCompLoginSection($sCaption)
{
	global $logged;
	global $site;
    global $memberID;
    global $tmpl;
	$ret = '';
	
	if( $logged['member'] )
	{
		$ret .= '<div class="logged_member_block">';
			$ret .= get_member_icon( $memberID, 'none' );
			$ret .= '<div class="hello_member">';
				$ret .= _t( '_Hello member', getNickName( $memberID ) );
				$ret .= "<br>";
				$ret .= '<a href="' . $site['url'] . 'member.php" class="logout">' . _t("_Control Panel") . '</a>';
				$ret .= ' &nbsp; ';
				$ret .= '<a href="' . $site['url'] . 'logout.php?action=member_logout" class="logout">' . _t("_Log Out") . '</a>';
			$ret .= '</div>';
		$ret .= '</div>';
	}
	elseif( $logged['admin'])
	{
		$ret .= '<div class="logged_section_block">';
			$ret .= '<span>';
				$ret .= '<a href="' . $site['url_admin'] . 'index.php" class="logout">Admin Panel</a>';
			$ret .= '</span>';
			$ret .= '<span>';
				$ret .= '|&nbsp;|';
			$ret .= '</span>';
			$ret .= '<span>';
				$ret .= '<a href="' . $site['url'] . 'logout.php?action=admin_logout" class="logout">' . _t("_Log Out") . '</a>';
			$ret .= '</span>';
		$ret .= '</div>';
	}
	elseif($logged['aff'])
	{
		$ret .= '<div class="logged_section_block">';
			$ret .= '<span>';
				$ret .= '<a href="' . $site['url'] . 'aff/index.php" class="logout">Affiliate Panel</a>';
			$ret .= '</span>';
			$ret .= '<span>';
				$ret .= '|&nbsp;|';
			$ret .= '</span>';
			$ret .= '<span>';
				$ret .= '<a href="' . $site['url'] . 'logout.php?action=aff_logout" class="logout">' . _t("_Log Out") . '</a>';
			$ret .= '</span>';
		$ret .= '</div>';
	}
	elseif($logged['moderator'])
	{
		$ret .= '<div class="logged_section_block">';
			$ret .= '<span>';
				$ret .= '<a href="' . $site['url'] . 'moderators/index.php" class="logout">Moderator Panel</a>';
			$ret .= '</span>';
			$ret .= '<span>';
				$ret .= '|&nbsp;|';
			$ret .= '</span>';
			$ret .= '<span>';
				$ret .= '<a href="' . $site['url'] . 'logout.php?action=moderator_logout" class="logout">' . _t("_Log Out") . '</a>';
			$ret .= '</span>';
		$ret .= '</div>';
	}
	else
	{
		$text = '';
		$mem         = _t("_Member");
		$table       = "Profiles";
		$login_page  = "{$site['url']}member.php";
		$join_page   = "{$site['url']}join_form.php";
		$forgot_page = "{$site['url']}forgot.php";
		$template    = "{$dir['root']}templates/tmpl_{$tmpl}/login_form.html";

		$ret = PageCompLoginForm($text,$member,$mem,$table,$login_page,$join_page,$forgot_page,$template);
	}
	return DesignBoxContent( _t($sCaption), $ret, 1 );
}

function PageCompRSS( $sCaption, $sContent )
{
    global $site;

	list( $sUrl, $iNum ) = explode( '#', $sContent );
	$iNum = (int)$iNum;

    $sUrl = str_replace(array('{SiteUrl}'),array($site['url']), $sUrl);

	$ret = genRSSHtmlOut( $sUrl, $iNum );
	
	return DesignBoxContent( _t($sCaption), $ret, 1 );
}

function PageCompArticles( $sCaption )
{
	global $site;
	
	$php_date_format = getParam( 'php_date_format' );
	
	$sQuery = "
		SELECT
			`ArticlesID`,
			`Articles`.`CategoryID`,
			`Date`,
			`Title`,
			`Text`,
			`CategoryName`
		FROM `Articles`
		INNER JOIN `ArticlesCategory` USING( `CategoryID` )
		ORDER BY `Date` DESC
		LIMIT 5
	";
	
	$rArticles = db_res( $sQuery );
	
	$ret = '';
	
	if( mysql_num_rows( $rArticles ) )
	{
		while( $aArticle = mysql_fetch_assoc( $rArticles ) )
		{
			$sDate = date( $php_date_format, strtotime( $aArticle['Date'] ) );
			$sCategUrl = $site['url'] . 'articles.php?action=viewcategory&amp;catID=' . $aArticle['CategoryID'];
			$sArticleUrl = $site['url'] . 'articles.php?action=viewarticle&articleID=' . $aArticle['ArticlesID'];
			
			$sText = strip_tags( $aArticle['Text'] );
			if( strlen( $sText ) > 200 )
				$sText = mb_substr( $sText, 0, 200 ) . '[...]';
//			$sText = htmlspecialchars_adv( $sText );
			
			$ret .= '<div class="rss_item_wrapper">';
				$ret .= '<div class="rss_item_header">';
					$ret .= '<a href="' . $sArticleUrl . '">';
						$ret .= htmlspecialchars_adv( $aArticle['Title'] );
					$ret .= '</a>';
				$ret .= '</div>';
				$ret .= '<div class="rss_item_info">';
					$ret .= '<span><img src="' . getTemplateIcon( 'clock.gif' ) . '" />' . date( $php_date_format, strtotime( $sDate ) ) . '</span><span>' . _t( '_in Category', getTemplateIcon( 'folder_small.png' ), $sCategUrl, htmlspecialchars_adv( $aArticle['CategoryName'] ) ) . '</span>';
				$ret .= '</div>';
				$ret .= '<div class="rss_item_desc">';
					$ret .= $sText;
				$ret .= '</div>';
			$ret .= '</div>';
		}
		
		$ret .= '<div class="rss_read_more">';
			$ret .= '<a href="' . $site['url'] .'articles.php">';
				$ret .= _t( '_Read All Articles' );
			$ret .= '</a>';
		$ret .= '</div>';
	}
	else
	{
		$ret .= '<div class="no_result"><div>';
			$ret .= _t("_No articles available");
		$ret .= '</div></div>';
	}
	
	return DesignBoxContent( _t($sCaption), $ret, 1 );
}

function PageCompSharePhotos( $sCaption )
{
	return '<div id="show_sharePhotos">' . PageCompSharePhotosContent( $sCaption ) . '</div>';
}

function PageCompShareVideos( $sCaption )
{
	return '<div id="show_shareVideos">' . PageCompShareVideosContent( $sCaption ) . '</div>';
}

function PageCompShareMusic( $sCaption )
{
	return '<div id="show_shareMusic">' . PageCompShareMusicContent( $sCaption ) . '</div>';
}

// --------------- [END] page components functions

?>