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


class BxBaseIndexPageView extends BxDolPageView {
	function BxBaseIndexPageView() {
		BxDolPageView::BxDolPageView( 'index' );
	}
	
	
	/**
	 * Top Rated Profiles block (Leaders)
	 */
	function getBlockCode_Leaders() {
	    global $site;
		global $max_voting_mark;
		global $index_progressbar_w;
		global $getBlockCode_TopRated_db_num;
		global $max_thumb_height;
		global $max_thumb_width;
		global $oTemplConfig;
		
		// most rated profiles
	
		// $rate_max = get_max_votes_profile();
	
	    $oVoting = new BxTemplVotingView ('profile', 0, 0);
	
	    $iIdMonth = $oVoting->getTopVotedItem(30, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active'");
	    $iIdWeek  = $oVoting->getTopVotedItem(7,  '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active' AND `ID`<>$iIdMonth");
	    $iIdDay   = $oVoting->getTopVotedItem(1,  '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active' AND `ID`<>$iIdMonth AND `ID`<>$iIdWeek");
	
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
	
		
		return $ret;
	}
	
	/**
	 * News Letters block
	 */
	function getBlockCode_Subscribe() {
		global $site;
		global $oTemplConfig;
	
		
		$ret = '';
		$ret .= '<div class="text">' . _t("_SUBSCRIBE_TEXT", $site['title']) . '</div>';
		$ret .= '<div class="email_here" style="border:0px solid red; text-align:center; margin-bottom:5px;">' . _t("_YOUR_EMAIL_HERE") . ':</div>';
		$ret .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
			$ret .= '<div class="input" style="text-align:center; margin-bottom:5px;">';
				$ret .= '<input name="subscribe" type="text" size="18"  onkeyup="if( emailCheck( this.value ) ) this.form.subscr.disabled=false; else this.form.subscr.disabled=true;" />';
			$ret .= '</div>';
			$ret .= '<center><input class="button" type=submit name=vote_submit value="' . _t( '_Subscribe' ) . '" disabled="disabled" id="subscr" /></center>';
			$ret .= '<input type="hidden" name="subscribe_submit" value="true" />';
		$ret .= '</form>';
	
		return $ret;
	}
	
	/**
	 * Success story  block
	 */
	function getBlockCode_Feedback() {
	    global $site;
		global $getBlockCode_SuccessStory_db_num;
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
		
		return $ret;
	}
	
	/**
	 * Latest News block
	 */
	function getBlockCode_News() {
		$news_limit_chars = getParam("max_news_preview");
		$max_news_on_home = (int)getParam("max_news_on_home");
		
		return printNewsPanel($max_news_on_home, $news_limit_chars);
	}
	
	/**
	 * Survey block
	 */
	function getBlockCode_SitePolls() {
	    global $site;
	    global $getBlockCode_Survey_db_num;
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
						$ret .= '<input class="button" type=submit name=vote_submit value="' . _t( '_Cast my vote' ) . '" />';
						$ret .= '</span><br />';
						$ret .= '<span style="margin-bottom:2px;">';
							$ret .= ' <a href="poll.php?ID=' . $survey_arr['ID'] . '">' . _t("_Results") . '</a> | <a href="polls.php">' . _t("_Polls") . '</a>';
						$ret .= '</span>';
					$ret .= '</div>';
				$ret .= '</form>';
				$ret .= '</div>';
			$ret .= '</div>';
		} else
			$ret .= '<div class="no_result"><div>' . _t("_No polls available") . '</div></div>';
		
		return $ret;
	}
	
	/**
	 * Featured members block
	 */
	function getBlockCode_Featured() {
		global $site;
		global $aPreValues;
		global $getBlockCode_Featured_db_num;
		global $max_thumb_width;
		global $max_thumb_height;
		global $oTemplConfig;
	
		
		$feature_num 	= getParam('featured_num');
		$feature_mode 	= getParam('feature_mode');
	
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
					
					$featured_coutry = _t($aPreValues['Country'][$featured_arr['Country']]['LKey']);
/*
					if( ($j % 3) != 0 )
						$ret .= '<div class="featured_block_1">';
					else 
						$ret .= '<div class="featured_block_2">';
*/
					$ret .= '<div class="featured_block_1">';
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
		
		return $ret;
	}

	/*function getBlockCode_Members() {
		ob_start();
		?>
			<div id="show_members"><?= $this->getBlockCode_MembersContent( $sCaption ) ?></div>
		<?
		
		return ob_get_clean();
	}*/

	function getBlockCode_Members() {
		global $tmpl;
	
		// number of profiles
		$max_num	= (int) getParam( "top_members_max_num" );
			
		//	Get Sex from GET data
		if ( $_GET['Sex'] && $_GET['Sex'] != "all" ) {
			$sex = process_db_input( $_GET['Sex'] );
			$query_add = " AND `Sex` = '$sex'";
		} else {
			$sex = "all";
			$query_add = "";
		}
		
		$query_add .= ' AND (`Couple`=0 OR `Couple`>`ID`)';
		
		$query = "
			SELECT
				`Profiles`.*
			";
	
		if ( $_GET['members_mode'] == 'online' or
			 $_GET['members_mode'] == 'rand' or
			 $_GET['members_mode'] == 'last' or
			 $_GET['members_mode'] == 'top' )
				$mode = $_GET['members_mode'];
		else
			$mode = 'last';

		$aDBTopMenu = array();
		//$menu = '<div class="dbTopMenu">';
		foreach( array( 'last', 'top', 'online', 'rand' ) as $myMode )
		{
			switch ( $myMode )
			{
				case 'online':
					if( $mode == $myMode )
						$filter = " FROM `Profiles` WHERE `DateLastNav` > SUBDATE(NOW(), INTERVAL ".(int)getParam( "member_online_time" )." MINUTE) AND `Status` = 'Active' AND `PrimPhoto` != 0 $query_add ORDER BY `Couple` ASC";
					$modeTitle = _t('_Online');
				break;
				case 'rand':
					if( $mode == $myMode )
						$filter = " FROM `Profiles` WHERE `Status` = 'Active' AND `PrimPhoto` != 0 $query_add ORDER BY `Couple` ASC, RAND()";
					$modeTitle = _t('_Random');
				break;
				case 'last':
					if( $mode == $myMode )
						$filter = " FROM `Profiles` WHERE `Status` = 'Active' $query_add ORDER BY `Couple` ASC, `DateReg` DESC";
					$modeTitle = _t('_Latest');
				break;
				case 'top':
					if( $mode == $myMode )
					{
						$oVotingView = new BxTemplVotingView ('profile', 0, 0);
		
						$aSql        = $oVotingView->getSqlParts('`Profiles`', '`ID`');
						$sqlOrderBy  = $oVotingView->isEnabled() ? " ORDER BY `Couple` ASC, (`pr_rating_sum`/`pr_rating_count`) DESC, `pr_rating_count` DESC, `DateReg` DESC" : $sqlOrderBy ;
						$sqlFields   = $aSql['fields'];
						$sqlLJoin    = $aSql['join'];
						$filter      = "$sqlFields FROM `Profiles` $sqlLJoin WHERE `Status` = 'Active' AND `pr_rating_count` > 2 $query_add $sqlOrderBy";
						$filter2      = " FROM `Profiles` $sqlLJoin WHERE `Status` = 'Active' AND `pr_rating_count` > 2 $query_add $sqlOrderBy";
					}	
					$modeTitle = _t('_Top');
				break;

			}

			$aDBTopMenu[$modeTitle] = array('href' => "{$_SERVER['PHP_SELF']}?members_mode=$myMode", 'dynamic' => true, 'active' => ( $myMode == $mode ));

			/*if( $myMode == $mode )
				$menu .= "<div class=\"active\">$modeTitle</a></div>";
			else
				$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?members_mode=$myMode&amp;Sex=$sex\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_members', this.href+'&amp;show_only=members'); return false;\">$modeTitle</a></div>";*/
		}
			/*$menu .= '<div class="clear_both"></div>';
		$menu .= '</div>';*/
		
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
				$ret .= PrintSearhResult( $p_arr, $templ_search, 3 );
				/*if( ($iCounter % 3) != 0 )
					$ret .= PrintSearhResult( $p_arr, $templ_search, 1 );
				else
					$ret .= PrintSearhResult( $p_arr, $templ_search, 2 );
				$iCounter++;*/
			}
			
			$ret .= '<div class="clear_both"></div>';

			$aDBBottomMenu = array();
			if( $pages > 1 )
			{
				//$ret .= '<div class="dbBottomMenu">';
			
				if( $page > 1 )
				{
					$prevPage = $page - 1;
					$aDBBottomMenu[ _t('_Back') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?members_mode={$mode}&amp;page={$prevPage}", 'dynamic' => true, 'class' => 'backMembers' );
					/*$ret .= "
						<a href=\"{$_SERVER['PHP_SELF']}?members_mode=$mode&amp;page=$prevPage\"
						  class=\"backMembers\"
						  onclick=\"getHtmlData( 'show_members', this.href+'&amp;show_only=members'); return false;\">"._t('_Back')."</a>
					";*/
				}
				
				if( $page < $pages )
				{
					$nextPage = $page + 1;
					$aDBBottomMenu[ _t('_Next') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?members_mode={$mode}&amp;page={$nextPage}", 'dynamic' => true, 'class' => 'moreMembers' );
					/*$ret .= "
						<a href=\"{$_SERVER['PHP_SELF']}?members_mode=$mode&amp;page=$nextPage\"
						  class=\"moreMembers\"
						  onclick=\"getHtmlData( 'show_members', this.href+'&amp;show_only=members'); return false;\">"._t('_Next')."</a>
					";*/
				}

				$sBMViewAllLink = (getParam('enable_modrewrite') == 'on') ? "{$site['url']}browse.php" : "{$site['url']}browse.php";
				$aDBBottomMenu[ _t('_View All') ] = array( 'href' => $sBMViewAllLink, 'dynamic' => false, 'class' => 'viewAllMembers' );
				//$ret .= '</div>';
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

		return array( $ret, $aDBTopMenu, $aDBBottomMenu );
		//return $ret;
	}

	/*function getBlockCode_ProfilePhotos() {
		ob_start();
		?>
			<div id="show_photos"><?= $this->getBlockCode_ProfilePhotosContent() ?></div>
		<?
		
		return ob_get_clean();
	}*/

	function getBlockCode_ProfilePhotos() {
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

		$aDBTopMenu = array();
		//$menu = '<div class="dbTopMenu">';
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

			$aDBTopMenu[$modeTitle] = array('href' => "{$_SERVER['PHP_SELF']}?Mode_p=$myMode", 'dynamic' => true, 'active' => ( $myMode == $mode ));
			/*if( $myMode == $mode )
				$menu .= "<div class=\"active\">$modeTitle</a></div>";
			else
				$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?Mode_p=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_photos', this.href+'&amp;show_only=photos'); return false;\">$modeTitle</a></div>";*/
		}
			/*$menu .= '<div class="clear_both"></div>';
		$menu .= '</div>';*/
		
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
				/*if( ($iCounter % 3) != 0 )
					$ret .= '<div class="topmembers_block_1">';
				else
					$ret .= '<div class="topmembers_block_2">';*/
				$ret .= '<div class="topmembers_block_1">';

				$ret .= <<<EOJ
					<div class="thumbnail_block">
						<a href="{$site['url']}photos_gallery.php?ID={$ph_arr['med_prof_id']}&amp;photoID={$ph_arr['med_id']}"
						  title="{$ph_arr['med_title']}">
							<img style="width:{$max_thumb_width}px;height:{$max_thumb_height}px;background-image:url($urlImg);"
							  src="{$urlSpacer}" alt="{$ph_arr['med_title']}" />
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

			$aDBBottomMenu = array();
			if( $pages > 1 )
			{
				/*$ret .= '
				<div class="dbBottomMenu">';*/
			
				if( $page > 1 )
				{
					$prevPage = $page - 1;
					$aDBBottomMenu[ _t('_Back') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?Mode_p={$mode}&amp;page_p={$prevPage}", 'dynamic' => true, 'class' => 'backMembers' );
					/*$ret .= "
						<a href=\"{$_SERVER['PHP_SELF']}?Mode_p=$mode&amp;page_p=$prevPage\"
						  class=\"backMembers\"
						  onclick=\"getHtmlData( 'show_photos', this.href+'&amp;show_only=photos'); return false;\">"._t('_Back')."</a>
					";*/
				}
				
				if( $page < $pages )
				{
					$nextPage = $page + 1;
					$aDBBottomMenu[ _t('_Next') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?Mode_p={$mode}&amp;page_p={$nextPage}", 'dynamic' => true, 'class' => 'moreMembers' );
					/*$ret .= "
						<a href=\"{$_SERVER['PHP_SELF']}?Mode_p=$mode&amp;page_p=$nextPage\"
						  class=\"moreMembers\"
						  onclick=\"getHtmlData( 'show_photos', this.href+'&amp;show_only=photos'); return false;\">"._t('_Next')."</a>
					";*/
				}
				//$sBMViewAllLink = (getParam('enable_modrewrite') == 'on') ? "{$site['url']}browse.php" : "{$site['url']}browse.php";
				//$aDBBottomMenu[ _t('_View All') ] = array( 'href' => $sBMViewAllLink, 'dynamic' => false, 'class' => 'viewAllMembers' );
				//$ret .= '</div>';
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

		return array( $ret, $aDBTopMenu, $aDBBottomMenu );
		//return $ret;
	}
	
	function getBlockCode_ProfilePoll() {
	    global $getBlockCode_ProfilePoll_db_num;
		global $oTemplConfig;
	    global $aPreValues;
	
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

		$aDBTopMenu = array();
		//$menu = '<div class="dbTopMenu">';
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
			/*if( $sMyMode == $mode )
				$menu .= "<div class=\"active\">$sModeTitle</a></div>";
			else
				$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?ppoll_mode=$sMyMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'profile_poll_cont', this.href+'&amp;show_only=ppoll');return false;\">$sModeTitle</a></div>";*/
			$aDBTopMenu[$sModeTitle] = array('href' => "{$_SERVER['PHP_SELF']}?ppoll_mode=$sMyMode", 'dynamic' => true, 'active' => ( $sMyMode == $mode ));
		}
			/*$menu .= '<div class="clear_both"></div>';
		$menu .= '</div>';*/
	
		
		if( $mode == 'admin' )
			$ret = $this->getBlockCode_SitePolls();
		else {
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
				
				$poll_coutry = _t($aPreValues['Country'][$poll_arr['Country']]['LKey']);
				
				//$NickName = "<b><a href=\"".getProfileLink($poll_arr['ID'])."\">{$poll_arr['NickName']}</a></b>";
				$sNickName = $poll_arr['NickName'];
				$sNickNameLnk = getProfileLink($poll_arr['ID']);

				///////////////////////////////////
				$sPic = get_member_icon( $poll_arr['ID'], 'left');
				$sPoll = ShowPoll( $poll_arr['id_poll'] );

				$ret .= <<<EOF
<div class="blog_block">
	<div class="icon_block">
		{$sPic}
	</div>
	<div class="blog_wrapper_n" style="width:80%;border:1px dashed #CCCCCC;">
		<div class="blog_subject_n">
			<a href="{$sNickName}" class="bottom_text">
				{$sNickName}
			</a>
		</div>
		<div class="blogSnippet">
			{$sPoll}
		</div>
	</div>
</div>
<div class="clear_both"></div>
EOF;

/*
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
				
				$ret .= '<div class="clear_both"></div>';*/
			}
		}

		return array( $ret, $aDBTopMenu );
		//return $ret;
	
	}
	
	function getBlockCode_Tags() {
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

		$aDBTopMenu = array();
		//$menu = '<div class="dbTopMenu">';
		foreach( array( 'profile', 'blog', 'event', 'photo', 'video', 'music', 'ad' ) as $myMode )
		{
			switch ( $myMode )
			{
				case 'profile':
					$bPermalinks = getParam('enable_modrewrite')=='on' ? true : false;
					$hrefTmpl  = $bPermalinks ? 'search/tag/{tag}' : 'search.php?Tags={tag}';
					$modeTitle = _t('_Profiles');
				break;
				case 'blog':
					$bPermalinks = getParam('permalinks_blogs')=='on' ? true : false;
					$hrefTmpl  = $bPermalinks ? 'blogs/tag/{tag}' : 'blogs.php?action=search_by_tag&tagKey={tag}';
					$modeTitle = _t('_Blogs');
				break;
				case 'event':
					$bPermalinks = getParam('permalinks_events')=='on' ? true : false;
					$hrefTmpl  = $bPermalinks ? 'events/search/{tag}' : 'events.php?action=search_by_tag&tagKey={tag}';
					$modeTitle = _t('_Events');
				break;
				case 'photo':
					$bPermalinks = getParam('permalinks_gallery_photos')=='on' ? true : false;
					$hrefTmpl  = $bPermalinks ? 'photo/gallery_tag/{tag}' : 'browsePhoto.php?tag={tag}';
					$modeTitle = _t('_Photos');
				break;
				case 'video':
					$bPermalinks = getParam('permalinks_gallery_videos')=='on' ? true : false;
					$hrefTmpl  = $bPermalinks ? 'video/gallery_tag/{tag}' : 'browseVideo.php?tag={tag}';
					$modeTitle = _t('_Videos');
				break;
				case 'music':
					$bPermalinks = getParam('permalinks_gallery_music')=='on' ? true : false;
					$hrefTmpl  = $bPermalinks ? 'music/gallery_tag/{tag}' : 'browseMusic.php?tag={tag}';
					$modeTitle = _t('_Music');
				break;
				case 'ad':
					$bPermalinks = getParam('permalinks_classifieds')=='on' ? true : false;
					$hrefTmpl  = $bPermalinks ? 'ads/tag/{tag}' : 'classifieds_tags.php?tag={tag}';
					$modeTitle = _t('_Ads');
				break;
			}

			$aDBTopMenu[$modeTitle] = array('href' => "{$_SERVER['PHP_SELF']}?tags_mode=$myMode", 'dynamic' => true, 'active' => ( $myMode == $mode ));
			
			if( $myMode == $mode )
				$sCrtHrefTmpl = $hrefTmpl;
			
			/*if( $myMode == $mode )
			{
				$menu .= "<div class=\"active\">$modeTitle</a></div>";
				$sCrtHrefTmpl = $hrefTmpl;
			}
			else
				$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?tags_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_tags', this.href+'&amp;show_only=tags'); return false;\">$modeTitle</a></div>";*/
		}
			/*$menu .= '<div class="clear_both"></div>';
		$menu .= '</div>';*/
		
		
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

		return array( $ret, $aDBTopMenu );
		//return $ret;
	}

	function getBlockCode_Blogs() {
	    global $site;
		
		$mode = $_REQUEST['blogs_mode'];
		if( $mode != 'rand' and $mode != 'latest' and $mode != 'top' )
			$mode = 'latest';
		
		//generate top menu
		$aDBTopMenu = array();
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
			
			$aDBTopMenu[$sTabTitle] = array('href' => "{$_SERVER['PHP_SELF']}?blogs_mode=$myMode", 'dynamic' => true, 'active' => ( $myMode == $mode ));
		}
		
		
		//generate content
		$iTotalNum = db_value("SELECT COUNT(*) AS 'Cnt' FROM `BlogPosts` WHERE `PostStatus` = 'approval' AND `PostReadPermission` = 'public'" );

		$oBlogs = new BxDolBlogs();
		if( $iTotalNum ) {
			$iResPerPage = (int)getParam("max_blogs_on_home");
			$iTotalPages = ceil( $iTotalNum / $iResPerPage );
			$page = (int)$_REQUEST['blogs_page'];
			
			if( $page < 1 or $mode == 'rand' )
				$page = 1;
			if( $page > $iTotalPages )
				$page = $iTotalPages;
			$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;
	
			//$oBlogs = new BxDolBlogs();
			$sBlocks = $oBlogs->GenAnyBlockContent($mode, 0, "LIMIT $sqlLimitFrom, $iResPerPage");
			$ret = $sBlocks;
		} else
			$ret = '<div class="no_result"><div>'._t("_No blogs available").'</div></div>';
		
		
		//generate bottom menu
		$aDBBottomMenu = array();
		if( $iTotalPages > 1 ) {
			if( $page > 1 ) {
				$prevPage = $page - 1;
				$aDBBottomMenu[ _t('_Back') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?blogs_mode={$mode}&amp;blogs_page={$prevPage}", 'dynamic' => true, 'class' => 'backMembers' );
			}
			
			if( $page < $iTotalPages ) {
				$nextPage = $page + 1;
				$aDBBottomMenu[ _t('_Next') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?blogs_mode={$mode}&amp;blogs_page={$nextPage}", 'dynamic' => true, 'class' => 'moreMembers' );
			}

			$sBMViewAllLink = ($oBlogs->isPermalinkEnabled()) ? "{$site['url']}blogs/" : "{$site['url']}blogs.php";
			$aDBBottomMenu[ _t('_View All') ] = array( 'href' => $sBMViewAllLink, 'dynamic' => false, 'class' => 'viewAllMembers' );
		}

		return array( $ret, $aDBTopMenu, $aDBBottomMenu );
	}

	/*function getBlockCode_Classifieds() {
		ob_start();
		?>
			<div id="show_classifieds"><?= $this->getBlockCode_ClassifiedsContent()?></div>
		<?
		
		return ob_get_clean();
	}*/
	
	function getBlockCode_Classifieds() {
	    global $site;
	
		$mode = $_REQUEST['classifieds_mode'];
		if( $mode != 'rand' and $mode != 'latest' and $mode != 'top' )
			$mode = 'latest';
	
		$aDBTopMenu = array();
		//$menu = '<div class="dbTopMenu">';
		foreach( array( 'latest', 'top', 'rand' ) as $myMode ) {
			switch( $myMode ) {
				case 'top':
					$sTabTitle  = _t( '_Top' );
				break;
				case 'latest':
					$sTabTitle  = _t( '_Latest' );
				break;
				case 'rand':
					$sTabTitle  = _t( '_Random' );
				break;
			}

			$aDBTopMenu[$sTabTitle] = array('href' => "{$_SERVER['PHP_SELF']}?classifieds_mode=$myMode", 'dynamic' => true, 'active' => ( $myMode == $mode ));

			/*if( $mode == $myMode ) {
				$menu .= "<div class=\"active\">$sTabTitle</div>";
			} else {
				$menu .= "
				<div class=\"notActive\">
					<a href=\"{$_SERVER['PHP_SELF']}?classifieds_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_classifieds', this.href+'&amp;show_only=classifieds'); return false;\">$sTabTitle</a>
				</div>";
			}*/
		}
		/*$menu .= '
				<div class="clear_both"></div>
			</div>';*/
	
		$iTotalNum = db_value( "SELECT COUNT(*) FROM `ClassifiedsAdvertisements`
				WHERE DATE_ADD( `ClassifiedsAdvertisements`.`DateTime` , INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY ) > NOW( )
				AND `ClassifiedsAdvertisements`.`Status` = 'active'
			" );

		$oClassifieds = new BxDolClassifieds();
		$oClassifieds->UseDefaultCF();
		if( $iTotalNum ) {
			$iResPerPage = (int)getParam("max_blogs_on_home");
			$iTotalPages = ceil( $iTotalNum / $iResPerPage );
			$page = (int)$_REQUEST['classifieds_page'];
	
			if( $page < 1 or $mode == 'rand' )
				$page = 1;
			if( $page > $iTotalPages )
				$page = $iTotalPages;
			$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;
	
			$sBlocks = $oClassifieds->GenAnyBlockContent($mode, 0, "LIMIT $sqlLimitFrom, $iResPerPage");
			$ret = $sBlocks;
		} else
			$ret .= '<div class="no_result"><div>'._t("_No classifieds available").'</div></div>';

		$aDBBottomMenu = array();
		if( $iTotalPages > 1 ) {
			//$ret .= '<div class="dbBottomMenu">';
	
			if( $page > 1 ) {
				$prevPage = $page - 1;
				$aDBBottomMenu[ _t('_Back') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?classifieds_mode={$mode}&amp;classifieds_page={$prevPage}", 'dynamic' => true, 'class' => 'backMembers' );
				//$sBackC = _t('_Back');
				/*$retR .= <<<EOF
	<a href="{$_SERVER['PHP_SELF']}?classifieds_mode={$mode}&amp;classifieds_page={$prevPage}" class="backMembers" onclick="getHtmlData( 'show_classifieds', this.href+'&amp;show_only=classifieds'); return false;">
		{$sBackC}
	</a>
EOF;*/
			}
	
			if( $page < $iTotalPages ) {
				$nextPage = $page + 1;
				$aDBBottomMenu[ _t('_Next') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?classifieds_mode={$mode}&amp;classifieds_page={$nextPage}", 'dynamic' => true, 'class' => 'moreMembers' );
				/*$sNextC = _t('_Next');
				$retR .= <<<EOF
	<a href="{$_SERVER['PHP_SELF']}?classifieds_mode={$mode}&amp;classifieds_page={$nextPage}" class="moreMembers" onclick="getHtmlData( 'show_classifieds', this.href+'&amp;show_only=classifieds'); return false;">
		{$sNextC}
	</a>
EOF;*/
			}
			$sBMViewAllLink = ($oClassifieds->bUseFriendlyLinks) ? "{$site['url']}ads/" : "{$site['url']}classifieds.php?Browse=1";
			$aDBBottomMenu[ _t('_View All') ] = array( 'href' => $sBMViewAllLink, 'dynamic' => false, 'class' => 'viewAllMembers' );
			//$ret .= '<div class="clear_both"></div></div>';
		}
	
		//return $ret;
		return array( $ret, $aDBTopMenu, $aDBBottomMenu );
	}


	/*function getBlockCode_Events()
	{
		ob_start();
		?>
			<div id="show_events"><?= $this->getBlockCode_EventsContent()?></div>
		<?
		
		return ob_get_clean();
	}*/
	
	function getBlockCode_Events() {
	    global $site;
	
		$mode = $_REQUEST['events_mode'];
		if( $mode != 'rand' and $mode != 'latest' )
			$mode = 'latest';
	
		$aDBTopMenu = array();
		//$menu = '<div class="dbTopMenu">';
		foreach( array( 'latest', 'rand' ) as $myMode ) {
			switch( $myMode ) {
				case 'latest':
					$sTabTitle  = _t( '_Latest' );
				break;
				case 'rand':
					$sTabTitle  = _t( '_Random' );
				break;
			}
	
			/*if( $mode == $myMode ) {
				$menu .= "<div class=\"active\">$sTabTitle</div>";
			} else {
				$menu .= "
				<div class=\"notActive\">
					<a href=\"{$_SERVER['PHP_SELF']}?events_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_events', this.href+'&amp;show_only=events'); return false;\">$sTabTitle</a>
				</div>";
			}*/
			$aDBTopMenu[$sTabTitle] = array('href' => "{$_SERVER['PHP_SELF']}?events_mode=$myMode", 'dynamic' => true, 'active' => ( $myMode == $mode ));
		}
		/*$menu .= '
				<div class="clear_both"></div>
			</div>';*/
	
		$iTotalNum = db_value("SELECT COUNT(*) AS 'Cnt' FROM `SDatingEvents` WHERE `Status` = 'Active'");

		$oEvents = new BxDolEvents();
		if( $iTotalNum ) {
			$iResPerPage = (int)getParam("max_blogs_on_home");
			$iTotalPages = ceil( $iTotalNum / $iResPerPage );
			$page = (int)$_REQUEST['events_page'];
	
			if( $page < 1 or $mode == 'rand' )
				$page = 1;
			if( $page > $iTotalPages )
				$page = $iTotalPages;
			$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;
	
			$sBlocks = $oEvents->GenAnyBlockContent($mode, 0, "LIMIT $sqlLimitFrom, $iResPerPage");
			$ret = $sBlocks;
		} else
			$ret .= '<div class="no_result"><div>'._t("_No events available").'</div></div>';

		$aDBBottomMenu = array();
		if( $iTotalPages > 1 ) {
			//$ret .= '<div class="dbBottomMenu">';
	
			if( $page > 1 ) {
				$prevPage = $page - 1;
				$aDBBottomMenu[ _t('_Back') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?events_mode={$mode}&amp;events_page={$prevPage}", 'dynamic' => true, 'class' => 'backMembers' );
				$sBackC = _t('_Back');
				/*$ret .= <<<EOF
	<a href="{$_SERVER['PHP_SELF']}?events_mode={$mode}&amp;events_page={$prevPage}" class="backMembers" onclick="getHtmlData( 'show_events', this.href+'&amp;show_only=events'); return false;">
		{$sBackC}
	</a>
EOF;*/
			}
	
			if( $page < $iTotalPages ) {
				$nextPage = $page + 1;
				$aDBBottomMenu[ _t('_Next') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?events_mode={$mode}&amp;events_page={$nextPage}", 'dynamic' => true, 'class' => 'moreMembers' );
				/*$sNextC = _t('_Next');
				$ret .= <<<EOF
	<a href="{$_SERVER['PHP_SELF']}?events_mode={$mode}&amp;events_page={$nextPage}" class="moreMembers" onclick="getHtmlData( 'show_events', this.href+'&amp;show_only=events'); return false;">
		{$sNextC}
	</a>
EOF;*/
			}
			$sBMViewAllLink = ($oEvents->bUseFriendlyLinks) ? "{$site['url']}events/" : "{$site['url']}events.php?show_events=all&action=show";
			$aDBBottomMenu[ _t('_View All') ] = array( 'href' => $sBMViewAllLink, 'dynamic' => false, 'class' => 'viewAllMembers' );
			//$ret .= '<div class="clear_both"></div></div>';
		}

		return array( $ret, $aDBTopMenu, $aDBBottomMenu );
		//return $ret;
	}

	/*function getBlockCode_Groups() {
		ob_start();
		?>
			<div id="show_groups"><?= $this->getBlockCode_GroupsContent()?></div>
		<?
		
		return ob_get_clean();
	}*/

	function getBlockCode_Groups() {
	    global $site;
		
		$mode = $_REQUEST['groups_mode'];
		if( $mode != 'rand' and $mode != 'latest' )
			$mode = 'latest';
	
		$aDBTopMenu = array();
		//$menu = '<div class="dbTopMenu">';
		foreach( array( 'latest', 'rand' ) as $myMode ) {
			switch( $myMode ) {
				case 'latest':
					$sTabTitle  = _t( '_Latest' );
				break;
				case 'rand':
					$sTabTitle  = _t( '_Random' );
				break;
			}

			$aDBTopMenu[$sTabTitle] = array('href' => "{$_SERVER['PHP_SELF']}?groups_mode=$myMode", 'dynamic' => true, 'active' => ( $myMode == $mode ));
			/*if( $mode == $myMode ) {
				$menu .= "<div class=\"active\">$sTabTitle</div>";
			} else {
				$menu .= "
				<div class=\"notActive\">
					<a href=\"{$_SERVER['PHP_SELF']}?groups_mode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_groups', this.href+'&amp;show_only=groups'); return false;\">$sTabTitle</a>
				</div>";
			}*/
		}
		/*$menu .= '
				<div class="clear_both"></div>
			</div>';*/

		$iTotalNum = db_value("SELECT COUNT(*) AS 'Cnt' FROM `Groups` WHERE `status` = 'Active'");

		$oGroups = new BxDolGroups();

		if( $iTotalNum ) {
			$iResPerPage = (int)getParam("max_blogs_on_home");
			$iTotalPages = ceil( $iTotalNum / $iResPerPage );
			$page = (int)$_REQUEST['groups_page'];
	
			if( $page < 1 or $mode == 'rand' )
				$page = 1;
			if( $page > $iTotalPages )
				$page = $iTotalPages;
			$sqlLimitFrom = ( $page - 1 ) * $iResPerPage;
	
			$sBlocks = $oGroups->GenAnyBlockContent($mode, 0, "LIMIT $sqlLimitFrom, $iResPerPage");
			$ret = $sBlocks;
		} else
			$ret .= '<div class="no_result"><div>'._t("_No groups available").'</div></div>';

		$aDBBottomMenu = array();
		if( $iTotalPages > 1 ) {
			//$ret .= '<div class="dbBottomMenu">';
	
			if( $page > 1 ) {
				$prevPage = $page - 1;
				$aDBBottomMenu[ _t('_Back') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?groups_mode={$mode}&amp;groups_page={$prevPage}", 'dynamic' => true, 'class' => 'backMembers' );
				/*$sBackC = _t('_Back');
				$retR .= <<<EOF
	<a href="{$_SERVER['PHP_SELF']}?groups_mode={$mode}&amp;groups_page={$prevPage}" class="backMembers" onclick="getHtmlData( 'show_groups', this.href+'&amp;show_only=groups'); return false;">
		{$sBackC}
	</a>
EOF;*/
			}
	
			if( $page < $iTotalPages ) {
				$nextPage = $page + 1;
				$aDBBottomMenu[ _t('_Next') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?groups_mode={$mode}&amp;groups_page={$nextPage}", 'dynamic' => true, 'class' => 'moreMembers' );
				/*$sNextC = _t('_Next');
				$retR .= <<<EOF
	<a href="{$_SERVER['PHP_SELF']}?groups_mode={$mode}&amp;groups_page={$nextPage}" class="moreMembers" onclick="getHtmlData( 'show_groups', this.href+'&amp;show_only=groups'); return false;">
		{$sNextC}
	</a>
EOF;*/
			}
			$sBMViewAllLink = ($oGroups->bUseFriendlyLinks) ? "{$site['url']}groups/all/" : "{$site['url']}grp.php";
			$aDBBottomMenu[ _t('_View All') ] = array( 'href' => $sBMViewAllLink, 'dynamic' => false, 'class' => 'viewAllMembers' );
			//$ret .= '<div class="clear_both"></div></div>';
		}
	
		return array( $ret, $aDBTopMenu, $aDBBottomMenu );
	}
	
	function getBlockCode_QuickSearch() {
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
			$ret .= '<form action="search.php" method="get">';
				$ret .= '<div class="qsi_line">';
					$ret .= '<div class="qsi_first">';
						$ret .= _t("_I am a");
					$ret .= '</div>';
					$ret .= '<div class="qsi_second">';
						$ret .= '<select name="LookingFor[]">';
							$ret .= SelectOptions("LookingFor", $member_sex);
						$ret .= '</select>';
					$ret .= '</div>';
				$ret .= '</div>';
	
				$ret .= '<div class="qsi_line">';
					$ret .= '<div class="qsi_first">';
						$ret .= _t("_seeking a");
					$ret .= '</div>';
					$ret .= '<div class="qsi_second">';
						$ret .= '<select name="Sex[]">';
							$ret .= SelectOptions("Sex", ($member_sex=='male' ? 'female':'male'), 'LKey2' );
						$ret .= '</select>';
					$ret .= '</div>';
				$ret .= '</div>';
	
				$ret .= '<div class="qsi_line">';
					$ret .= '<div class="qsi_first">';
						$ret .= _t("_aged");
					$ret .= '</div>';
					$ret .= '<div class="qsi_second">';
						$ret .= '<span style="position:absolute; top:0px; left:0px;">';
							$ret .= '<select name="DateOfBirth[0]">';
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
							$ret .= '<select name="DateOfBirth[1]">';
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
	
				/* $ret .= '<div class="qsi_line">';
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
				$ret .= '</div>'; */
	
				$ret .= '<div class="qsi_line" style="text-align:center; margin-top:3px;">';
					$ret .= '<input type="checkbox" name="photos_only" id="qsi_photos_only" style="width:15px; height:15px;" /> ';
					$ret .= '<label for="qsi_photos_only">' . _t("_With photos only") . '</label>';
				$ret .= '</div>';
	
				$ret .= '<div class="qsi_line" style="text-align:center; margin-top:3px;">';
					$ret .= '<input type="submit" value=' . _t( '_Search' ) . ' />';
				$ret .= '</div>';
			$ret .= '</form>';
		$ret .= '</div>';
	
		return $ret;
	}
	
	function getBlockCode_LoginSection() {
		global $logged;
		global $site;
	    global $tmpl;
		$ret = '';
		
		if( $logged['member'] )
		{
			$ret .= '<div class="logged_member_block">';
				$ret .= get_member_icon( $memberID, 'none' );
				$ret .= '<div class="hello_member">';
					$ret .= _t( '_Hello member', getNickName( $this -> iMemberID ) );
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
			$join_page   = "{$site['url']}join.php";
			$forgot_page = "{$site['url']}forgot.php";
			$template    = "{$dir['root']}templates/tmpl_{$tmpl}/login_form.html";
	
			$ret = PageCompLoginForm($text,$member,$mem,$table,$login_page,$join_page,$forgot_page,$template);
		}
		return $ret;
	}
	
	function getBlockCode_Articles() {
		$php_date_format = getParam( 'php_date_format' );
		
		$oArticles = new BxDolArticles(false);
		$iArticlesLimit = (int)getParam('number_articles');
		$iArticlesLimit = $iArticlesLimit != 0 ? $iArticlesLimit : 1;
		
		$rArticles = $oArticles->getArticlesResource($iArticlesLimit);
		
		$ret = '';
		
		$iArticlesIndex = mysql_num_rows( $rArticles );
		
		if ($iArticlesIndex)
		{
			while( $aArticle = mysql_fetch_assoc( $rArticles ) )
			{
				$sDate = date( $php_date_format, strtotime( $aArticle['Date'] ) );
				$sCategUrl = $oArticles->getArticleCatUrl($aArticle['CategoryID']);
				$sArticleUrl = $oArticles->getArticleUrl($aArticle['ArticlesID']);
				
				$sText = strip_tags( $aArticle['Text'] );
				if( strlen( $sText ) > 200 )
					$sText = mb_substr( $sText, 0, 200 ) . '[...]';
				
				$ret .= '<div class="rss_item_wrapper">';
					$ret .= '<div class="rss_item_header">';
						$ret .= '<a href="' . $sArticleUrl . '">';
							$ret .= htmlspecialchars_adv( $aArticle['Title'] );
						$ret .= '</a>';
					$ret .= '</div>';
					$ret .= '<div class="rss_item_info">';
						$ret .= '<span><img src="' . getTemplateIcon( 'clock.gif' ) . '" alt="" />' . date( $php_date_format, strtotime( $sDate ) ) . '</span><span>' . _t( '_in Category', getTemplateIcon( 'folder_small.png' ), $sCategUrl, htmlspecialchars_adv( $aArticle['CategoryName'] ) ) . '</span>';
					$ret .= '</div>';
					$ret .= '<div class="rss_item_desc">';
						$ret .= $sText;
					$ret .= '</div>';
				$ret .= '</div>';
			}
			$iArticlesCount = db_value("SELECT COUNT(*) FROM `Articles`");
			
			$sMoreLink = $oArticles->isPermalinkEnabled() ? 'articles/' : 'articles.php' ;
			
			if ($iArticlesCount > $iArticlesIndex)
			{
				$ret .= '<div class="rss_read_more">';
					$ret .= '<a href="' . $GLOBALS['site']['url'] .$sMoreLink.'">';
						$ret .= _t( '_Read All Articles' );
					$ret .= '</a>';
				$ret .= '</div>';
			}	
		}
		else
		{
			$ret .= '<div class="no_result"><div>';
				$ret .= _t("_No articles available");
			$ret .= '</div></div>';
		}
		
		return $ret;
	}
	
	function getBlockCode_ShareMusic() {
		global $site;
		global $dir;
		
		$aMem = array('ID'=>$this->iMemberID);
		$oNew = new BxDolSharedMedia('music', $site, $dir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia();

		return $aRes;
	}
	function getBlockCode_SharePhotos() {
		global $site;
		global $dir;
		
		$aMem = array('ID'=>$this->iMemberID);
		$oNew = new BxDolSharedMedia('photo', $site, $dir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia();
		
		return $aRes;
	}
	function getBlockCode_ShareVideos() {
		global $site;
		global $dir;
		
		$aMem = array('ID'=>$this->iMemberID);
		$oNew = new BxDolSharedMedia('video', $site, $dir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia();
		
		return $aRes;
	}
}