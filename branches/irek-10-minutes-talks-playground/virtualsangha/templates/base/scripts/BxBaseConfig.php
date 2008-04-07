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

require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );

class BxBaseConfig
{
	var $aSite;

	var $memberMenu_db_num						= 1;
/*
	var $customMenu_db_num						= 1;
	var $adminMenu_db_num 						= 1;
	var $affMenu_db_num							= 1;
	var $visitorMenu_db_num						= 1;
	var $moderatorMenu_db_num					= 1;
*/

	/*	index.php	*/
	var	$PageCompTopMembers_db_num				= 1;
	var $DesignQuickSearchIndex_db_num			= 1;
	var $PageCompMemberStat_db_num				= 1;
	var $PageCompSurvey_db_num					= 1;
	var	$PageCompNews_db_num					= 1;
	var	$PageCompTopRated_db_num				= 1;
	var	$PageCompFeatured_db_num				= 1;
	var	$PageCompSuccessStory_db_num			= 1;
	var	$PageCompNewsLetters_db_num				= 1;
	/**/
	var	$PageCompThird_db_num					= 0;

	/*Membership.php*/
	var	$PageCompStatus_db_num					= 1;
	var	$PageCompSubscriptions_db_num			= 1;
	var	$PageCompMemberships_db_num				= 1;
	var	$PageCompCredits_db_num					= 1;
	/*	cc.php	*/
	var	$communicator_Hot_db_num				= 1;
	var	$communicator_Friend_db_num				= 1;
	var	$communicator_Block_db_num				= 1;
	var	$communicator_Kiss_db_num				= 1;
	var	$communicator_View_db_num				= 1;
	var	$communicator_Contact_db_num			= 1;
	var	$communicator_PPhotos_db_num			= 1;
		//SpeedDating
	var	$PageSDatingShowEvents_db_num			= 1;
	var	$PageSDatingShowInfo_db_num				= 1;
	var	$PageSDatingShowParticipants_db_num		= 1;
	var	$PageSDatingSelectMatches_db_num		= 1;
	var	$PageSDatingShowForm_db_num				= 1;
	var	$PageSDatingCalendar_db_num				= 1;
	var	$PageSDatingNewEvent_db_num				= 1;
		//Checkout
	var	$PageCompCheckoutInfo_db_num			= 1;
	var	$PageCompProviderList_db_num			= 1;
	var	$PageCompErrorMessage_db_num			= 1;
		//Video
	var	$PageCompUploadVideo_db_num				= 1;
	var	$PageCompRayRecorder_db_num				= 1;

	var	$PageExplanation_db_num					= 1;

		/*	cart-pop.php	*/
	var	$PageCartPop_db_num						= 1;
		/*	sound_pop.php	*/
	var	$PageSoundPop_db_num					= 1;
		/*	freemail.php	*/
	var	$PageFreeMailPop_db_num					= 1;
		/*	greet.php	*/
	var	$PageVkiss_db_num						= 1;
		/*	compose.php	*/
	var	$PageCompose_db_num						= 0;
		/*	gallery.php	*/
	var	$PageGalleryShowObject_db_num			= 1;
		/*	inbox.php	*/
	var	$PageInbox_db_num						= 0;
		/*	list-pop.php	*/
	var	$PageListPop_db_num						= 1;
		/*	messages_inbox.php	*/
	var	$PageMessagesInboxMessageDeleted_db_num	= 0;
	var	$PageMessagesInboxMainCode_db_num		= 0;
		/*	messages_outbox.php	*/
	var	$PageMessagesOutboxMainCode_db_num		= 0;
		/*	outbox.php	*/
	var	$PageOutboxMainCode_db_num				= 0;
		/*	photos_gallery.php	*/
	var	$PagePhotosGalleryNoPics_db_num			= 1;
		/*	rate.php	*/
	var	$PageRateMainCode_db_num				= 0;
		/*	search_result.php	*/
	var	$PageSearcResultGallery_db_num			= 0;
		/*	design.inc.php	*/
	var	$PageRetIM_db_num						= 1;
	var	$loadShoutbox_db_num					= 1;
		/*	profile.php	*/
	var	$getMemberProfileComments_db_num		= 1;
		/*	index.php	*/
	var	$PageCompProfilePoll_db_num				= 1;

	//Width of Votes scale at profilr view page
	var	$iProfileViewVotesWidth					= 200;
	var	$iProfileViewProgressBar					= 67;

	// width of progress bar in search result ( for match % )
	var	$search_progressbar_w					= 67;

		// width of progress bar on home page
	var	$index_progressbar_w					= 120;


		// show text link "view as photogallery" in the page navigation of search result page
	var	$show_gallery_link_in_page_navigation	= 1;

	// default value for number of search results per page
	var	$def_p_per_page							= 10;

	// Instant Messanger ( Private Messages Panel ) Settings
	var	$im_width								= 187; // width of IM
	var	$im_height								= 206; // height of IM
	var	$im_input_height						= 25; // Height of input panel
	var	$im_input								= 18; // size of IM input text edit text control

		// Shout Box Settings
	var	$framewidth								= 197; //width of Shout Box Frame
	var	$frameheight							= 250; //Height Of Shout Box Frame

	var	$maxwordlength							= 20; //Max namber of charachters in word
	var	$maxtextlength							= 60; //Max namber of charachters in line without white space
	var	$maxrecords								= 16; //Max number of lines
	var	$classes								= 2; //Number of classes

	var	$popUpWindowWidth						= 500;
	var	$popUpWindowHeight						= 200;

	var $iMaxNewsOnMemberPanel					= 2;
	var $iMaxNewsOnIndex						= 2;
	var $iNewsPreview							= 128;
	var $iNewsHeader							= 27;
	var $iSearchResultGalleryCols				= 4;
	
		// Groups
	var $iGroupMembersPreNum					= 8; //number of random members shown in main page of group
	var $iGroupMembersResPerPage				= 8;
	
	var $iGroupsSearchResPerPage				= 10;
	var $iGroupsSearchResults_dbnum				= 1;
	
	var $iQSearchWindowWidth                    = 400;
	var $iQSearchWindowHeight                   = 400;
	
	
	var $paginationDifference					= 2; //look design.inc.php, function genPagination
	
	
	var $iTagsPerPage							= 50; //number of tags show on index page
	var $iTagsMinFontSize						= 10;  //Minimal font size of tag
	var $iTagsMaxFontSize						= 30; //Maximal font size of tag
	
	var $iRateBigRatingBar                      = 350; //width of rating bar in rate.php
	var $iRateBigRatingBarNum                   = 2;   //number of rating bar in rate.php
	
	var $iRateSmallRatingBar                    = 160; //width of small rating bar in rate.php
	var $iRateSmallRatingBarNum                 = 1; //number of small rating bar in rate.php
	
	var $sTinyMceEditorJS;
	var $sCalendarCss;

	var $bFreeMode, $bEnableCustomization, $bEnableGallery, $bEnablePoll, $bEnableIm, $bEnableComments, $bEnableGuestbook, $bEnableBlog, $bEnableSdating, $bEnableVideoUpload, $bEnableAudioUpload, $bAnonymousMode;
	
	var $customize;
	
	function BxBaseConfig($site)
	{
		global $enable_guestbook, $enable_blog, $en_sdating, $enable_video_upload, $enable_audio_upload, $anon_mode;

		$this -> customMenu_db_num						= $this -> memberMenu_db_num;
		$this -> adminMenu_db_num 						= $this -> memberMenu_db_num;
		$this -> affMenu_db_num							= $this -> memberMenu_db_num;
		$this -> visitorMenu_db_num						= $this -> memberMenu_db_num;
		$this -> moderatorMenu_db_num					= $this -> memberMenu_db_num;

		$this -> aSite = $site;

		$this -> bFreeMode						= getParam("free_mode") == 'on' ? 1 : 0;
		$this -> bEnableCustomization 			= getParam('enable_customization') == 'on' ? 1 : 0;
		$this -> bEnableGallery 				= getParam('enable_gallery') == 'on' ? 1 : 0;
		$this -> bEnablePoll					= getParam('enable_poll') == 'on' ? 1 : 0;
		$this -> bEnableIm						= getParam("enable_im") == "on" ? 1 : 0;
		$this -> bEnableComments				= getParam("enable_profileComments") == "on" ? 1 : 0;
		$this -> bEnableGuestbook				= $enable_guestbook;
		$this -> bEnableBlog					= $enable_blog;
		$this -> bEnableSdating					= $en_sdating;
		$this -> bEnableVideoUpload 			= $enable_video_upload;
		$this -> bEnableAudioUpload 			= $enable_audio_upload;
		$this -> bAnonymousMode					= $anon_mode;

		$this -> sTinyMceEditorJS = '
	<!-- tinyMCE gz -->	
	<script type="text/javascript" src="' . $site['plugins'] . 'tiny_mce/tiny_mce_gzip.js"></script>
	<script type="text/javascript">
	tinyMCE_GZ.init({
		plugins : 			     	"style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
		themes : "simple,advanced",
		languages : "en",
		disk_cache : true,
		debug : false
	});
	</script>
	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		content_css : "' . $site['css_dir'] . 'editor.css",
		editor_selector : "blogText|guestbookTextArea|story_edit_area|comment_textarea|classfiedsTextArea",


		plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen",

		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,image,separator,search,replace,separator",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,separator,forecolor,backcolor",
		theme_advanced_buttons3_add : "emotions",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_disable : "link,unlink,insertanchor,image,subscript,superscript,help,anchor,code,styleselect",
		plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		paste_use_dialog : false,
		theme_advanced_resizing : false,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false

		});
	</script>
	<!-- /tinyMCE -->
	';
	
		$this -> sTinyMceEditorCompactJS =
'<!-- tinyMCE gz -->
<script type="text/javascript" src="' . $site['plugins'] . 'tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript">
	tinyMCE_GZ.init({
		plugins : 			     	"style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
		themes : "simple,advanced",
		languages : "en",
		disk_cache : true,
		debug : false
	});
</script>

<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		
		editor_selector : "group_edit_html|guestbookTextArea|story_edit_area|comment_textarea|classfiedsTextArea|blogText",
		
		plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,visualchars,nonbreaking,xhtmlxtras",
		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "forecolor,backcolor,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,hr,|,sub,sup,|,insertdate,inserttime,|,styleprops",
		theme_advanced_buttons3 : "charmap,emotions,|,cite,abbr,acronym,attribs,|,preview,removeformat",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "center",
		extended_valid_elements : "a[name|href|title],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
	});
</script>';

		$this -> sTinyMceEditorMiniJS =
'	<!-- tinyMCE gz -->	
	<script type="text/javascript" src="' . $site['plugins'] . 'tiny_mce/tiny_mce_gzip.js"></script>
	<script type="text/javascript">
	tinyMCE_GZ.init({
		plugins : 			     	"style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
		themes : "simple,advanced",
		languages : "en",
		disk_cache : true,
		debug : false
	});
	</script>
	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		content_css : "' . $site['css_dir'] . 'editor.css",
		editor_selector : "comment_textarea|classfiedsTextArea",


		plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,visualchars,nonbreaking,xhtmlxtras",

		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor",
		theme_advanced_buttons2 : "link,unlink,image,hr,insertdate,inserttime,|,charmap,emotions,|,cite,preview,removeformat",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_disable : "insertanchor,image,help,anchor,code,styleselect",
		plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		paste_use_dialog : false,
		theme_advanced_resizing : false,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false

		});
	</script>
	<!-- /tinyMCE -->
	';
	
		$this -> sCalendarCss = '<link rel="stylesheet" href="' . $site['plugins'] . 'calendar/calendar_themes/aqua.css" type="text/css" />';


		$this -> customize                  = array(); //pages customization array
		
		$this -> customize['media_gallery']['showMediaTabs'] = true;
		$this -> customize['upload_media']['showMediaTabs']  = true;
		$this -> customize['upload_media']['showAddButton']  = true;
		$this -> customize['upload_media']['addNewBlock_display'] = 'none';
		$this -> customize['join_page']['showPageText']      = true;
		$this -> customize['join_page']['show_3rd_col']      = true;
		$this -> customize['rate']['showSexSelector']        = true;
		$this -> customize['rate']['showProfileInfo']        = true;
		$this -> customize['events']['showTopButtons']       = true;
		$this -> customize['blog']['showBreadCrumbs']        = true;
		$this -> customize['blog']['showEditLinks']          = true;
	}

}

?>