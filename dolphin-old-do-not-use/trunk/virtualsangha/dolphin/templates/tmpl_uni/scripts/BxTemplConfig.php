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

require_once( BX_DIRECTORY_PATH_BASE . 'scripts/BxBaseConfig.php' );

/***
 javascript variables:
***/

$site['js_init'] = '
<script type="text/javascript" language="javascript">
	dpoll_progress_bar_color = "#88C86A";
</script>
';


/***
 template variables
***/

// path to the images used in the template
$site['images']		= $site['url'] . "templates/tmpl_uni/images/";
$site['zodiac']		= "templates/tmpl_uni/images/zodiac/";
$site['icons']		= $site['url'] . "templates/tmpl_uni/images/icons/";
$site['css_dir']	= "templates/tmpl_uni/css/";
$site['smiles']		= $site['smiles'] . 'default/';


class BxTemplConfig extends BxBaseConfig 
{
	var $DesignQuickSearchIndex_db_num 		= 0;
	var $PageCompThird_db_num				= 0;
	var $PageCompTopMembers_db_num			= 0;
	var $iProfileViewVotesWidth             = 170;
	
	var $s_width = 140; // width of ShoutBox
	var $framewidth	= 170; //width of Shout Box Frame
	//var $frameheight = 173; //height of Shout Box Frame
	
	// initial variables for mailbox table sorting
	var $sMailBoxSortingInit = '';
	
	function BxTemplConfig($site)
	{
		BxBaseConfig::BxBaseConfig($site);
		
		$this -> customize['media_gallery']['showMediaTabs'] = false;
		$this -> customize['upload_media']['showMediaTabs']  = false;
		$this -> customize['upload_media']['showAddButton']  = false;
		$this -> customize['upload_media']['addNewBlock_display'] = 'block';
		$this -> customize['rate']['showSexSelector']        = false;
		$this -> customize['rate']['showProfileInfo']        = false;
		$this -> customize['events']['showTopButtons']       = false;
		$this -> customize['blog']['showBreadCrumbs']        = false;
		$this -> customize['blog']['showEditLinks']          = false;
		
		$this -> sMailBoxSortingInit = '
<script type="text/javascript">
<!--
var aSortImgs = new Array(); // array containing sort indicator images
aSortImgs[0] = \''.$this -> aSite['icons'].'sort_up.gif\';
aSortImgs[1] = \''.$this -> aSite['icons'].'sort_down.gif'.'\';

var sort_case_sensitive = false; // sorting type (case-sensitive or not)

//initial sorting
var initial_sort_id = 3; //number of column beginning from 0
var initial_sort_up = 1; //0 - ascendant order, 1 - descendant
//-->
</script>
';
	//print_r( $this );
	}
}

?>
