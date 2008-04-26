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
require_once( BX_DIRECTORY_PATH_INC . 'groups.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );

// --------------- page variables and login


$_page['name_index']	= 78;
$_page['css_name']		= 'groups.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t( "_Search Groups" );
$_page['header_text'] = _t( "_Search Groups" );

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = '';


//begin main code

// get search params
$keyword  = $_REQUEST['keyword'];
$searchby = $_REQUEST['searchby'];
$categID  = $_REQUEST['categID'];
$Country  = $_REQUEST['Country'];
$City     = $_REQUEST['City'];
$sortby   = $_REQUEST['sortby'];
// [END] get search params

// check search params
unset( $keyword_db );
unset( $searchby_db );
unset( $categID_db );
unset( $Country_db );
unset( $City_db );
unset( $sortby_db );

if( isset($keyword) and strlen($keyword) )
{
	$keyword = trim( $keyword );
	if( strlen( $keyword ) )
		$keyword_db = strtoupper( process_db_input( $keyword ) );
	$keyword = process_pass_data( $keyword );
}

if( $searchby == 'name' or $searchby == 'keyword' )
	$searchby_db = $searchby;
else
	$searchby_db = $searchby = 'keyword';

$categID_db = $categID = (int)$categID;

if( isset( $Country ) and isset( $prof['countries'][$Country] ) )
	$Country_db = $Country;
else
	$Country = '';

if( isset($City) and strlen($City) )
{
	$City = trim( $City );
	if( strlen( $City ) )
		$City_db = strtoupper( process_db_input( $City ) );
	$City = process_pass_data( $City );
}

if( $sortby == 'Name' or $sortby == 'membersCount' or $sortby == 'created' )
	$sortby_db = $sortby;
else
	$sortby_db = $sortby = 'membersCount';
// [END] check search params

$_page_cont[$_ni]['groups_search_form'] = PageCompGroupsSearchForm( $keyword, $searchby, $categID, $Country, $City, $sortby );

if( $keyword_db or $categID_db or $Country_db or $City )
	$_page_cont[$_ni]['groups_search_results'] = PageCompGroupsSearchResults( $keyword_db, $searchby_db, $categID_db, $Country_db, $City_db, $sortby_db );
else
	$_page_cont[$_ni]['groups_search_results'] = '';

// --------------- [END] page components

PageCode();

// --------------- page components functions

function PageCompGroupsSearchForm( $keyword, $searchby, $categID, $Country, $City, $sortby )
{
	ob_start();
?>
		<script type="text/javascript">
			var keyword  = '<?=unbreak_js( str_replace( '\'','\\\'', str_replace( '\\','\\\\',$keyword ) ) )?>';
			var searchby = '<?=$searchby?>';
			var categID  = '<?=$categID?>';
			var Country  = '<?=$Country?>';
			var City     = '<?=unbreak_js( str_replace( '\'','\\\'', str_replace( '\\','\\\\',$City ) ) )?>';
			var sortby   = '<?=$sortby?>';
			
			function checkSearchForm( )
			{
				_form = document.forms.groups_search_form;
				if( !_form )
					return false;
				
				if( !_form.keyword.value && !_form.categID.value && !_form.Country.value && !_form.City.value )
				{
					alert( '<?=_t('_Please select at least one search parameter')?>' );
					return false;
				}
			}
			
			function switchGroupsSearchPage(page)
			{
				_form = document.forms.groups_search_form;
				if( !_form )
					return false;
				
				_form.keyword.value = keyword;
				_form.categID.value = categID;
				_form.Country.value = Country;
				_form.City.value    = City;
				
				for( i = 0; i < _form.searchby.length; i ++ )
					if( _form.searchby[i].value == searchby )
						_form.searchby[i].checked = true;
				
				for( i = 0; i < _form.sortby.length; i ++ )
					if( _form.sortby[i].value == sortby )
						_form.sortby[i].checked = true;
				
				_form.page.value = page;
				
				_form.submit();
				return true;
			}
		</script>
<?php
	$sRetJS = ob_get_clean();

	//if (isset($_REQUEST['categID']) && isset($_REQUEST['searchby'])==FALSE) return $sRetJS;
	$bNoFilter = false;
	if (isset($_REQUEST['categID']) && isset($_REQUEST['nf']) && (int)$_REQUEST['nf'] == 1) $bNoFilter = true;
	if ($bNoFilter == true) $sDisplayStyle='style="display:none"';
	if ($bNoFilter == true) $sNFelement = '<input type="hidden" name="nf" value="1" />';

	global $prof;
	ob_start();
	
	$ch = 'checked="checked"';
	?>
		<div class="groups_search_adv" <?=$sDisplayStyle?> >
			<div class="clear_both"></div>
			<form action="<?=$site['url']?>groups_browse.php" method="GET" name="groups_search_form" onsubmit="return checkSearchForm();">
				
				<div class="groups_search_row">
					<div class="groups_search_label"><?=_t('_Keyword')?>:</div>
					<div class="groups_search_value">
						<input type="text" id="keyword" name="keyword" class="groups_search_text" value="<?=htmlspecialchars_adv($keyword)?>" />
					</div>
					<div class="clear_both"></div>
				</div>
				
				<div class="groups_search_row">
					<div class="groups_search_label"><?=_t('_Search by')?>:</div>
					<div class="groups_search_value">
						<input type="radio" name="searchby" class="groups_search_radio" value="name" id="searchby_name" <?=($searchby == 'name' ? $ch : '')?> />
						<label for="searchby_name" class="groups_search_labelfor"><?=_t('_by group name')?></label>
						
						<input type="radio" name="searchby" class="groups_search_radio" value="keyword" id="searchby_keyword" <?=($searchby == 'keyword' ? $ch : '')?> />
						<label for="searchby_keyword" class="groups_search_labelfor"><?=_t('_by keyword')?></label>
					</div>
					<div class="clear_both"></div>
				</div>
				
				<div class="groups_search_row">
					<div class="groups_search_label"><?=_t('_Category')?>:</div>
					<div class="groups_search_value">
						<select id="categID" name="categID" class="groups_search_select" />
							<option value=""><?=_t('_Any')?></option>
	<?php
	$resVals = db_res( "SELECT * FROM `GroupsCateg` ORDER BY `Name`" );
	while ( $arr = mysql_fetch_assoc( $resVals ) )
		echo "<option value=\"{$arr['ID']}\"".($categID == $arr['ID'] ? ' selected="selected"' : '').">".
		  htmlspecialchars_adv( $arr['Name'] )."</option>\n";
	?>
						</select>
					</div>
					<div class="clear_both"></div>
				</div>
				
				<div class="groups_search_row">
					<div class="groups_search_label"><?=_t('_Country')?>:</div>
					<div class="groups_search_value">
						<select id="Country" name="Country" class="groups_search_select" />
							<option value=""><?=_t('_Any')?></option>
	<?php
	foreach( $prof['countries'] as $key => $val )
		echo "<option value=\"$key\"".(strcmp($Country, $key) ? '' : ' selected="selected"').">".
		  _t("__$val")."</option>\n";
	?>
						</select>
					</div>
					<div class="clear_both"></div>
				</div>
				
				<div class="groups_search_row">
					<div class="groups_search_label"><?=_t('_City')?>:</div>
					<div class="groups_search_value">
						<input type="text" id="City" name="City" class="groups_search_text" value="<?=htmlspecialchars_adv($City)?>" />
					</div>
					<div class="clear_both"></div>
				</div>
				
				<div class="groups_search_row">
					<div class="groups_search_label"><?=_t('_Sort by')?>:</div>
					<div class="groups_search_value">
						<input type="radio" name="sortby" class="groups_search_radio" value="Name" id="sortby_Name" <?=($sortby == 'Name' ? $ch : '')?> />
						<label for="sortby_Name" class="groups_search_labelfor"><?=_t('_by group name')?></label>
						
						<input type="radio" name="sortby" class="groups_search_radio" value="membersCount" id="sortby_membersCount" <?=($sortby == 'membersCount' ? $ch : '')?> />
						<label for="sortby_membersCount" class="groups_search_labelfor"><?=_t('_by popular')?></label>
						
						<input type="radio" name="sortby" class="groups_search_radio" value="created" id="sortby_created" <?=($sortby == 'created' ? $ch : '')?> />
						<label for="sortby_created" class="groups_search_labelfor"><?=_t('_by newest')?></label>
					</div>
					<div class="clear_both"></div>
				</div>
				
				<input type="hidden" name="page" value="1" />
				<?=$sNFelement?>
				<div class="groups_search_row_center">
					<input type="submit" value="<?=_t('_Search')?>" class="groups_search_labelfor" />
					<div class="clear_both"></div>
				</div>
			</form>
			<div class="clear_both"></div>
		</div>
	<?php
	$sRetHtml = ob_get_clean();

	return ($bNoFilter == true) ? $sRetJS . $sRetHtml : DesignBoxContent ( _t('_Search Groups'), $sRetJS . $sRetHtml, 1);
	//return DesignBoxContent ( _t('_Search Groups'), $sRetJS . $sRetHtml, 1);
}

// Ma-an it is crazy. I don't know what they'll enter in search form =)
// Our testers entered <script>alert(1)</script> it has broken everything
function unbreak_js( $text )
{
	return str_replace( '</script>', "</scr'+'ipt>", $text );
}

?>