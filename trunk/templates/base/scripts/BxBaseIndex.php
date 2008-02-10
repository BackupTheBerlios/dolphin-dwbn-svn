<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

//require_once($dir['classes'] . 'BxDolDb.php');


class BxBaseIndex
{
	var $oDb;
	var $aSite;
	var $oTemplConfig;

	function BxBaseIndex()
	{
		global $site;

		$this -> oDb = new BxDolDb();
		$this -> aSite = $site;
		$this -> oTemplConfig = new BxTemplConfig();
	}

	function getMemberStatisic()
	{

	}

	function getQuickSearch()
	{

	}

	function getAdvancedQuickSeach()
	{

	}

	function getLatestNews()
	{

		$iNewsOnHome = $this -> oTemplConfig -> iMaxNewsOnIndex;

		$query = "
			SELECT
				`ID`,
				`Text`,
				`Header`,
				`Date`
			FROM
				`News`
			ORDER BY `Date` DESC

			LIMIT $iNewsOnHome
		;";
		$rNews = $this -> oDb -> getAll($query);

		if( !is_array( $rNews['0'] ) )
		{
			return _t_action( '_No news available' );
		}

		$ret = '';

		foreach($rNews as $aNews )
		{
			$ret .= '
				<div class="indexNewsBlock">
					<div class="indexNewsHeader">
						<a href="' . $this -> aSite['url'] . 'news_view.php?ID=' . process_line_output( $aNews['ID'] ) . '">
							' . strmaxtextlen( process_line_output( $aNews['Header'] ), $this ->oTemplConfig -> iNewsHeader ) . '
						</a>
					</div>
					<div class="indexNewsText">
						' . strmaxtextlen( process_html_output( $aNews['Text'] ), $this -> oTemplConfig -> iNewsPreview )  . '
					</div>
					<div class="indexNewsDate">
						' . process_line_output( $aNews['Date'] ) . '
					</div>
				</div>
			';
		}

		$ret .= '<div class="indexNewsArchive"><a href="' . $this -> aSite['url'] . 'news.php">' . _t("_Read news in archive") . '</a></div>';

		return $ret;
	}

	function getSurvey()
	{

	}

	function getNewsLetterForm()
	{

	}

	function getTopRatedProfiles()
	{

	}

	function getTopMembers()
	{

	}

	function getFeedback()
	{

	}

	function getFeaturedProfiles()
	{

	}

	function getProfilesPolls()
	{

	}

	function getProfilesBlog()
	{

	}

	function getLoginSecton( $logged )
	{

		$ret = '';
		if( $logged['admin'] )
		{
			$ret .= '
				<div class="loggedSectionBlock">
					<span>
						<a href="' . $this -> aSite['url_admin'] . 'index.php" class="logout">Admin Panel</a>
					</span>
					<span>|</span>
					<span>
						<a href="' . $this -> aSite['url'] . 'logout.php?action=admin_logout" class="logout">' . _t("_Log Out") . '</a>
					</span>
				</div>
			';
		}
		elseif( $logged['aff'] )
		{
			$ret .= '
				<div class="loggedSectionBlock">
					<span>
						<a href="' . $this -> aSite['url_aff'] . 'index.php" class="logout">Affiliate Panel</a>
					</span>
					<span>|</span>
					<span>
						<a href="' . $this -> aSite['url'] . 'logout.php?action=aff_logout" class="logout">' . _t("_Log Out") . '</a>
					</span>
				</div>
			';
		}
		elseif( $logged['moderator'] )
		{
			$ret .= '
				<div class="loggedSectionBlock">
					<span>
						<a href="' . $this -> aSite['url'] . 'moderators/index.php" class="logout">Moderator Panel</a>
					</span>
					<span>|</span>
					<span>
						<a href="' . $this -> aSite['url'] . 'logout.php?action=moderator_logout" class="logout">' . _t("_Log Out") . '</a>
					</span>
				</div>
			';
		}
		elseif( $logged['member'] )
		{

			$ret .= $this -> getLoogedMember();

		}
		else
		{
			$ret .= $this -> getLoginForm();
		}

		return $ret;
	}

	function getLoogedMember()
	{
		$ret = '';
		$ret .= '
				<div class="loggedSectionBlock">
					<span>
						<a href="' . $this -> aSite['url'] . 'member.php" class="logout">' . _t("_Control Panel") . '</a>
					</span>
					<span>|</span>
					<span>
						<a href="' . $this -> aSite['url'] . 'logout.php?action=member_logout" class="logout">' . _t("_Log Out") . '</a>
					</span>
				</div>
			';

		return $ret;
	}

	function getLoginFormAddons()
	{
		$aLoginAdd = array();
		$aLoginAdd['submit']	= '<div class="loginSubmit"><input type="image" src="' . $this -> aSite['images'] . 'login_button.jpg" style="border:0px;" /></div>';
		$aLoginAdd['headerText'] = '<div id="loginHeaderText">' . _t('_index_login_question') . '</div>';
		$aLoginAdd['forgot'] = '<div class="loginForgot"><a href="' . $this -> aSite['url'] . 'forgot.php">' . _t('_forgot_username_or_password') . '?</a></div>';
		$aLoginAdd['join'] = '<div class="loginJoin">' . _t('_not_a_member') . '<br /><strong><a href="' . $this -> aSite['url'] . 'join_form.php">' . _t('_Join now') . '</a></strong>' . '</div>';

		return $aLoginAdd;
	}


	function getLoginForm()
	{
		$aLoginAdd = $this -> getLoginFormAddons();

		$ret = '';
			$ret .= $aLoginAdd['headerText'];
			$ret .= '<div id="indexLoginForm">';
				$ret .= '<div id="loginSectionBlock">';
					$ret .= '<form method="post" action="' . $this -> aSite['url'] . 'member.php">';
						$ret .= '<div class="username">';
							$ret .= _t("_username") . ':';
						$ret .= '</div>';

						$ret .= '<div class="input" >';
							$ret .= '<input name="ID" value="" type="text" class="loginFormInput" />';
						$ret .= '</div>';

						$ret .= '<div class="username">';
							$ret .= _t("_Password") . ':';
						$ret .= '</div>';

						$ret .= '<div class="input">';
							$ret .= '<input name="Password" value="" type="password" class="loginFormInput" />';
						$ret .= '</div>';

						$ret .= $aLoginAdd['submit'];
						$ret .= $aLoginAdd['forgot'];

					$ret .= '</form>';
				$ret .= '</div>';
			$ret .= '</div>';
			$ret .= $aLoginAdd['join'];
		return $ret;
	}


}

?>