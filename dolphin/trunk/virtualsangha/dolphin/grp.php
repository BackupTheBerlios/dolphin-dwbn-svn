<?

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolGroups.php' );

// --------------- page variables and login

$_page['css_name']		= 'groups.css';

check_logged();

$oGroups = new BxDolGroups(false);


///changing parts by type
{

	switch ( $_REQUEST['action'] ) {
		//print functions
		case 'categ':
			$_page['header'] = _t( "_Search Groups" );
			$_page['header_text'] = _t( "_Search Groups" );

			$_page['name_index']	= 78;
			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = '';

			list($sForm, $sResults) = $oGroups->GenCategoryPage();

			//$sFormRes = (isset($_REQUEST['categUri']) || isset($_REQUEST['categID'])) ? '' : $sForm;

			$_page_cont[$_ni]['groups_search_form'] = '';
			$_page_cont[$_ni]['groups_search_results'] = $sResults;

			break;

		case 'search':
			$_page['header'] = _t( "_Search Groups" );
			$_page['header_text'] = _t( "_Search Groups" );

			$_page['name_index']	= 78;
			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = '';

			list($sForm, $sResults) = $oGroups->GenCategoryPage();

			$_page_cont[$_ni]['groups_search_form'] = $sForm;
			$_page_cont[$_ni]['groups_search_results'] = $sResults;

			break;

		case 'group':

			if ( $logged['member'] = member_auth( 0, false ) )
				$memberID = (int)$_COOKIE['memberID'];
			else
				$memberID = 0;

			$logged['admin'] = member_auth( 1, false );

			if (isset($_REQUEST['groupUri'])) {
				$groupID = $oGroups->getGroupIdByUri($_REQUEST['groupUri']);
			} else {
				$groupID = (int)$_REQUEST['ID'];
			}

			if ( !$groupID ) {
				$sGroupsUrl = $bPermalink ? 'groups/all' : $oGroups->sCurrFile;
				Header( "Location: {$site['url']}{$sGroupsUrl}" );
				exit;
			}

			//ShowGroup($groupID, $_ni);

			list($iNameIndex, $sHeader, $sHeaderT, $sMainCode, $sGrpBrd, $sGrpLCat, $sGrpLCreated, $sGrpLLocation, $sGrpLMemberCount, $sGrpLCreator, $sGrpLAbout, $sGrpLType, $sGrpLTypeHelp, $sGrpVImage, $sGrpVGalLink, $sGrpVCreatorThumb, $sGrpVCreatorLink, $sGrpVCat, $sGrpVCatLink, $sGrpVType, $sGrpVCreated, $sGrpVCountry, $sGrpVCity, $sGrpVMCount, $sGrpVAbout, $sGrpVDesc, $sGrpVStatus, $sGrpVActions, $sGrpVMembers, $sGrpVForum) = $oGroups->GenGroupMainPage($groupID, $memberID);

			$_page['name_index'] = $iNameIndex;
			$_page['header'] = $sHeader;
			$_page['header_text'] = $sHeaderT;
			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = $sMainCode;
			$_page_cont[$_ni]['groups_breadcrumbs'] = $sGrpBrd;

			$_page_cont[$_ni]['category_l'] = $sGrpLCat;
			$_page_cont[$_ni]['created_l'] = $sGrpLCreated;
			$_page_cont[$_ni]['location_l'] = $sGrpLLocation;
			$_page_cont[$_ni]['members_count_l'] = $sGrpLMemberCount;
			$_page_cont[$_ni]['group_creator_l'] = $sGrpLCreator;
			$_page_cont[$_ni]['group_about_l'] = $sGrpLAbout;
			$_page_cont[$_ni]['group_type_l'] = $sGrpLType;
			$_page_cont[$_ni]['group_type_help'] = $sGrpLTypeHelp;
			$_page_cont[$_ni]['group_image'] = $sGrpVImage;
			$_page_cont[$_ni]['group_gallery_link'] = $sGrpVGalLink;
			$_page_cont[$_ni]['group_creator_thumb'] = $sGrpVCreatorThumb;
			$_page_cont[$_ni]['group_creator_link'] = $sGrpVCreatorLink;
			$_page_cont[$_ni]['category'] = $sGrpVCat;
			$_page_cont[$_ni]['category_link'] = $sGrpVCatLink;
			$_page_cont[$_ni]['group_type'] = $sGrpVType;
			$_page_cont[$_ni]['created'] = $sGrpVCreated;
			$_page_cont[$_ni]['country'] = $sGrpVCountry;
			$_page_cont[$_ni]['city'] = $sGrpVMCity;
			$_page_cont[$_ni]['members_count'] = $sGrpVMCount;
			$_page_cont[$_ni]['group_about'] = $sGrpVAbout;
			$_page_cont[$_ni]['group_description'] = $sGrpVDesc;
			$_page_cont[$_ni]['group_status'] = $sGrpVStatus;
			$_page_cont[$_ni]['group_actions'] = $sGrpVActions;
			$_page_cont[$_ni]['group_members'] = $sGrpVMembers;
			$_page_cont[$_ni]['group_forum'] = $sGrpVForum;

			break;

		case 'group_members':

			list($sHeaderT, $sHeader, $sPageMainCode, $iNameIndex, $sBreadCrumb, $sPagination, $sShowingResults) = $oGroups->GenMembersPage();

			$_page['name_index'] = $iNameIndex;

			$_page['header_text'] = $sHeaderT;
			$_page['header'] = $sHeader;
			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = $sPageMainCode;

			$_page_cont[$_ni]['bread_crumbs']    = $sBreadCrumb;
			$_page_cont[$_ni]['pagination']      = $sPagination;
			$_page_cont[$_ni]['showing_results'] = $sShowingResults;

			break;

		case 'mygroups':

			$_page['name_index']	= 70;

			// $logged['member'] = member_auth( 0, true );
			$memberID = (int)$_COOKIE['memberID'];

			$_page['header'] = _t( "_My Groups" );
			$_page['header_text'] = _t( "_My Groups" );

			// --------------- page components

			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = $oGroups->showMyGroups( $memberID );

			break;

		case 'help':

			$_page['name_index']	= 80;
			$_page['css_name']		= 'groups.css';

			$_page['header'] = _t( "_Groups help" );

			// --------------- page components

			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['close_window'] = "<a href=\"javascript:window.close();\">"._t('_close window')."</a>";

			$_page['header_text']               = _t('_Groups help');
			$_page_cont[$_ni]['page_main_code'] = _t('_Groups help_'.$_GET['i']);

			break;

		case 'gallery':
			list($iNameIndex, $sHeader, $sHeaderT, $sMainCode) = $oGroups->ShowGroupGalleryPage();

			$_page['name_index']	= $iNameIndex;
			$_page['header_text'] = $sHeaderT;
			$_page['header'] = $sHeader;
			$_ni = $_page['name_index'];

			$_page_cont[$_ni]['page_main_code'] = $sMainCode;

			break;

		case 'edit':
			$_page['name_index']	= 73;

			$memberID = (int)$_COOKIE['memberID'];
			$groupID = (int)$_REQUEST['ID'];

			if ( !$groupID ) {
				Header( "Location: {$site['url']}{$oGroups->sCurrFile}" );
				exit;
			}

			$_page['header'] = _t( "_Edit Group" );
			$_page['header_text'] = _t( "_Edit Group" );
			$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorCompactJS;

			$_ni = $_page['name_index'];

			$_page_cont[$_ni]['page_main_code'] = $oGroups->PCEditGroupFormPage($groupID, $memberID);

			break;

		case 'create':
			$_page['name_index']	= 72;

			$memberID = (int)$_COOKIE['memberID'];

			$_page['header'] = _t( "_Create Group" );
			$_page['header_text'] = _t( "_Create Group" );
			$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorCompactJS;

			// --------------- page components
			$_ni = $_page['name_index'];

			$arrMember = getProfileInfo( $memberID ); //db_arr( "SELECT `Status` FROM `Profiles` WHERE `ID`=$memberID" );

			if( $arrMember['Status'] == 'Active' )
				$_page_cont[$_ni]['page_main_code'] = $oGroups->PCCreateForm($memberID);
			else
				$_page_cont[$_ni]['page_main_code'] = _t( '_You must be active member to create groups' );

			break;

		//forms of editing
		case 'add_category':
			break;

		//non safe functions
		case 'create_blog':
			break;

		default:
			$_page['header'] = _t( "_Groups Home" );
			$_page['header_text'] = _t( "_Groups categories" );

			$_page['name_index'] = 74;
			$_ni = $_page['name_index'];
			list($sCategories, $sAllNewGroups) = $oGroups->GenIndexPageOfGroups();
			$_page_cont[$_ni]['page_main_code'] = $sCategories;
			$_page_cont[$_ni]['page_top_groups'] = $sAllNewGroups;
			break;
	}

}

PageCode();

?>