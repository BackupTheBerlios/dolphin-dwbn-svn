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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login


$_page['name_index']	= 79;
$_page['css_name']		= 'qsearch.css';


$logged['member'] = member_auth( 0, false );

$memberID = (int)$_COOKIE['memberID'];

$_page['header'] = $site['title'].". ". _t( "_Quick Search Members" );
//$_page['header_text'] = _t( "_Quick Search Members" );

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['close_window'] = "<a href=\"javascript:window.close();\">"._t('_close window')."</a>";

if( $_REQUEST['do_submit'] and $_REQUEST['keyword'] )
{
	$_page['header_text']               = _t('_Quick search results');
	$_page['extra_js']                  = <<<EOJ
<script type="text/javascript">
	hMemberAction = window.opener.{$_REQUEST['handler']}; //Member Action Handler
</script>
EOJ;

	$_page_cont[$_ni]['page_main_code'] = PageCompSearchResults();
}
else
{
	$_page['header_text']               = _t('_Enter search parameters');
	$_page_cont[$_ni]['page_main_code'] = PageCompSearchForm();
}

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompSearchForm()
{
	ob_start();
	?>
		<form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
			<input type="hidden" name="handler" value="<?=$_REQUEST['handler']?>" />
			<div class="qsearch_form">
				<div class="qsearch_label"><?=_t('_Enter member NickName or ID')?>:</div>
				<input type="text" name="keyword" />
				<input type="submit" name="do_submit" value="<?=_t('_Search')?>" />
			</div>
		</form>
	<?php
	return ob_get_clean();
}

function PageCompSearchResults()
{
	global $dir;
	global $tmpl;
	
	$keyword = process_db_input($_REQUEST['keyword']);
	
	$sMembersQuery = "
		SELECT *
		FROM `Profiles`
		WHERE
		" .
		( is_numeric( $keyword ) ?
		  "`ID` = '$keyword'" :
		  "`NickName` LIKE '%$keyword%'" ) .
		"";
	
	$rMembers = db_res( $sMembersQuery );
	
	if( !mysql_num_rows( $rMembers ) )
		return '<div class="qsearch_notfound">'._t('_Sorry, no members found').'</div>';
	
	$sRowTmpl = file_get_contents( "{$dir['root']}templates/tmpl_$tmpl/qsearch_row.html" );
	
	$ret = '';
	while( $aMember = mysql_fetch_assoc( $rMembers ) )
	{
		$aRowTmpl = array();
		
		$aRowTmpl['thumbnail'] = get_member_thumbnail( $aMember['ID'], 'none' );
		$aRowTmpl['NickName']  = "<a href=\"".getProfileLink($aMember['ID'])."\" target=\"_blank\">".htmlspecialchars_adv( $aMember['NickName'] )."</a>";
		$aRowTmpl['actions']   = "<a href=\"javascript:void(0);\" onclick=\"hMemberAction({$aMember['ID']}, '{$aMember['NickName']}')\">"._t('_Add member')."</a>";
		
		$sRow = $sRowTmpl;
		foreach( $aRowTmpl as $what => $to )
			$sRow = str_replace( "__{$what}__", $to, $sRow );
		
		$ret .= $sRow;
	}
	return $ret;
}

?>