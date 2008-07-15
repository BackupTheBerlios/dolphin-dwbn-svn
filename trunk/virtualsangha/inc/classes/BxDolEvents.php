<?

//require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
//require_once( BX_DIRECTORY_PATH_INC . 'sdating.inc.php' );

require_once( BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

/*
 * class for Events
 */
class BxDolEvents {
	//variables

	//div element with spacer image
	//var $sRowSpacerDiv;

	//count of events per page
	var $iEventsPerPage; // count of events to show per page

	//var $iNewEvPicWidth = 200;
	//var $iNewEvPicHeight = 150;

	//max sizes of pictures for resizing during upload
	var $iIconSize;
	var $iThumbSize;
	var $iImgSize;

	//admin mode, can All actions
	var $bAdminMode;

	//path to image pic_not_avail.gif
	var $sPicNotAvail;
	//path to spacer image
	var $sSpacerPath;

	var $iLastInsertedID = -1;

	//use permalink
	var $bUseFriendlyLinks;

	/**
	 * constructor
	 */
	function BxDolEvents($bAdmMode = FALSE) {
		global $site;

		$this->sSpacerPath = getTemplateIcon( 'spacer.gif' );
		$this->sPicNotAvail = 'images/icons/group_no_pic.gif';
		$this->bAdminMode = $bAdmMode;

		// $this->sRowSpacerDiv = <<<EOF
// <table cellspacing="0" cellpadding="0" height="10" border="0">
	// <td><img src="{$this->sSpacerPath}"></td>
// </table>
// EOF;
		$this->iEventsPerPage = 10;

		$this->iIconSize = 45;
		$this->iThumbSize = 110;
		$this->iImgSize = 340;

		$this->bUseFriendlyLinks = getParam('permalinks_events') == 'on' ? true : false;
	}

	/**
	 * Compose Array of posted data before validating (post ad or edit)
	 *
	 * @return Array of posted variables
	 */
	function FillPostEventArrByPostValues() {
		$sEventTitle			= process_db_input( $_POST['event_title'] );
		$sEventDesc				= process_db_input( $_POST['event_desc'] );
		$sEventStatusMessage	= process_db_input( $_POST['event_statusmsg'] );
		$sTags					= process_db_input( $_POST['event_tags'] );

		$sEventCountry	= process_db_input( $_POST['event_country'] );
		$sEventCity		= process_db_input( $_POST['event_city'] );
		$sEventPlace	= process_db_input( $_POST['event_place'] );

		$sEventStart	= (isset($_POST['event_start']) && $_POST['event_start'] != '') ? strtotime( $_POST['event_start'] ) : -1;
		if ($this->bAdminMode) {
			$sEventEnd			= strtotime( $_POST['event_end'] );
			$sEventSaleStart	= strtotime( $_POST['event_sale_start'] );
			$sEventSaleEnd		= strtotime( $_POST['event_sale_end'] );
			$iEventCountF = (int)$_POST['event_count_female'];
			$iEventCountM = (int)$_POST['event_count_male'];
		}

		$aElements = array('Title' => $sEventTitle, 'Description' => $sEventDesc, 'Status message' => $sEventStatusMessage,
			'City' => $sEventCity, 'Country' => $sEventCountry, 'Place' => $sEventPlace, 'Event start' => $sEventStart,
			'Event end' => $sEventEnd, 'Ticket Sale Start' => $sEventSaleStart, 'Ticket Sale End' => $sEventSaleEnd,
			'Female Ticket Count' => $iEventCountF, 'Male Ticket Count' => $iEventCountM, 
			'Tags' => $sTags);

		return $aElements;
	}

	/**
	 * Generate common forms and includes js
	 *
	 * @return HTML presentation of data
	 */
	function PrintCommandForms() {
		$sJSPath = $site['url'] . 'inc/';

		$sRetHtml = <<<EOF
<script src="{$sJSPath}js/dynamic_core.js.php" type="text/javascript"></script>
<form action="{$_SERVER['PHP_SELF']}" method="post" name="command_edit_event">
	<input type="hidden" name="action" id="action" value="edit_event" />
	<input type="hidden" name="EditEventID" id="EditEventID" value="" />
</form>
<form action="{$_SERVER['PHP_SELF']}" method="post" name="command_delete_event">
	<input type="hidden" name="action" id="action" value="delete_event" />
	<input type="hidden" name="DeleteEventID" id="DeleteEventID" value="" />
</form>
EOF;
		return $sRetHtml;
	}

	function CheckLogged() {
		global $logged;
		if( !$logged['member'] && !$logged['admin'] ) {
			member_auth(0);
		}
	}

	function DeleteProfileEvents($iProfileID) {
		if ($this->bAdminMode==true) {
			$vDelSQL = db_res("SELECT `ID` FROM `SDatingEvents` WHERE `ResponsibleID` = {$iProfileID}");
			while( $aEnent = mysql_fetch_assoc($vDelSQL) ) {
				$this->PageSDatingDeleteEvent($aEnent['ID']);
			}
		}
	}

	function PageSDatingDeleteEvent($iDelEventID = -1) {
		$this->CheckLogged();
		global $dir;
		$sEventID = ($iDelEventID>0) ? $iDelEventID : process_db_input( (int)$_POST['DeleteEventID'] );

		$aOwner = db_arr("SELECT `ResponsibleID` FROM `SDatingEvents` WHERE `ID` = {$sEventID} LIMIT 1");
		if ($aOwner['ResponsibleID']==(int)$_COOKIE['memberID'] || $this->bAdminMode) {
			$vPosts = db_res( "DELETE FROM `SDatingParticipants` WHERE `IDEvent`={$sEventID}" );

			$aPicSQL = db_arr("SELECT `PhotoFilename` FROM `SDatingEvents` WHERE `ID` = {$sEventID}");
			$sMediaFileName = $aPicSQL['PhotoFilename'];
			if ($sMediaFileName != '') {
				if (unlink ( $dir['sdatingImage'] . $sMediaFileName ) == FALSE) {
					$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
				}
				if (unlink ( $dir['sdatingImage'] . 'thumb_'.$sMediaFileName ) == FALSE) {
					$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
				}
				if (unlink ( $dir['sdatingImage'] . 'icon_'.$sMediaFileName ) == FALSE) {
					$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
				}
			}
			$sQuery = "DELETE FROM `SDatingEvents` WHERE `SDatingEvents`.`ID` = {$sEventID} LIMIT 1";
			db_res( $sQuery );
			return MsgBox(_t('_event_deleted'));
		} elseif ($aOwner['ResponsibleID']!=(int)$_COOKIE['memberID']) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_event_delete_failed'));
		}
	}

	/**
	 * Compose Array of errors during filling (validating)
	 *
	 * @param $aElements	Input Array with data
	 * @return Array with errors
	 */
	function CheckEventErrors( $aElements ) {
		$aErr = array();
		foreach( $aElements as $sFieldName => $sFieldValue ) {
			switch( $sFieldName ) {
				// case 'Status message':
				case 'Title':
				case 'Country':
					if( !mb_strlen($sFieldValue) )
						$aErr[ $sFieldName ] = "{$sFieldName} is required";
				break;
				case 'Description':
					if( mb_strlen($sFieldValue) < 50 )
						$aErr[ $sFieldName ] = "{$sFieldName} must be 50 symbols at least";
				break;
				case 'City':
				case 'Place':
					if( !mb_strlen($sFieldValue) && $this->bAdminMode==TRUE )
						$aErr[ $sFieldName ] = "{$sFieldName} is required";
				break;

				case 'Event start':
					if( $sFieldValue > 0 ) {
						$sEventStart = strtotime($_REQUEST['event_start']);
						$sNow = strtotime("now");
						if ($sEventStart < $sNow)
							$aErr[ $sFieldName ] = "_event_post_wrong_time";
					} else {
						$aErr[ $sFieldName ] = "{$sFieldName} is required";
					}
				break;
				case 'Event end':
				case 'Ticket Sale Start':
				case 'Ticket Sale End':
					if( $sFieldValue == -1 && $this->bAdminMode==TRUE )
						$aErr[ $sFieldName ] = "{$sFieldName} is required";
				break;

				case 'Female Ticket Count':
				case 'Male Ticket Count':
					if( $sFieldValue < 1 && $this->bAdminMode==TRUE )
						$aErr[ $sFieldName ] = "{$sFieldName} must be positive";
				break;
			}
		}
		return $aErr;
	}

	/**
	 * function for New/Edit event
	 * @return Text Result
	 */
	function SDAddEvent( $iEventID = -1 ) {//for update event
		//print $iEventID;
		global $dir;
		global $logged;
		global $site;

		if( !$logged['member'] && !$logged['admin'] ) {
			member_auth(0);
		}

		// collect information about current member
		$aMember['ID'] = (int)$_COOKIE['memberID'];
		$aMemberData = getProfileInfo( $aMember['ID'] );

		// common
		$sEventTitle          = process_db_input( $_POST['event_title'] );
		$sEventDesc           = $this->process_html_db_input($_POST['event_desc']);
		$sEventStatusMessage  = process_db_input( $_POST['event_statusmsg'] );

		// event place
		$sEventCountry = process_db_input( $_POST['event_country'] );
		$sEventCity    = process_db_input( $_POST['event_city'] );
		$EventPlace    = process_db_input( $_POST['event_place'] );
		$sTags    = process_db_input( $_POST['event_tags'] );
		$aTags = explodeTags($sTags);
		$sTags = implode(",", $aTags);

		$sPictureName = $sBaseName;
		$aScan = getimagesize($_FILES['event_photo']['tmp_name']);

		if ( in_array($aScan[2], array(1,2,3,6)) && 0 < strlen( $_FILES['event_photo']['name']) ) {

			$sCurrentTime = time();
			if ($iEventID == -1) {
				$sBaseName = 'g_'. $sCurrentTime .'_1';
			} else {
				$sBaseName = db_value("SELECT `PhotoFilename` FROM `SDatingEvents` WHERE `ID`={$iEventID} LIMIT 1");
				if ($sBaseName!="") {
					if (ereg ("([a-z0-9_]+)\.", $sBaseName, $aRegs)) {
						$sBaseName = $aRegs[1];
					}
				} else {
					$sBaseName = ($sBaseName!="") ? $sBaseName : 'g_'. $sCurrentTime .'_1';
				}
			}

			$sExt = moveUploadedImage( $_FILES, 'event_photo', $dir['tmp'] . $sBaseName, '', false );

			$sBaseName .= $sExt;

			$sPictureName = $sBaseName;
			$sThumbName   = 'thumb_'.$sBaseName;
			$sIconName	  = 'icon_'.$sBaseName;

			// resize for thumbnail
			$vRes = imageResize( $dir['tmp'] . $sBaseName, $dir['sdatingImage'] . $sThumbName, $this->iThumbSize, $this->iThumbSize );
			if ( $vRes != IMAGE_ERROR_SUCCESS )
				return SDATING_ERROR_PHOTO_PROCESS;
			$vRes = imageResize( $dir['tmp'] . $sBaseName, $dir['sdatingImage'] . $sPictureName, $this->iImgSize, $this->iImgSize );
			if ( $vRes != IMAGE_ERROR_SUCCESS )
				return SDATING_ERROR_PHOTO_PROCESS;
			$vRes = imageResize( $dir['tmp'] . $sBaseName, $dir['sdatingImage'] . $sIconName, $this->iIconSize, $this->iIconSize );
			if ( $vRes != IMAGE_ERROR_SUCCESS )
				return SDATING_ERROR_PHOTO_PROCESS;
			unlink( $dir['tmp'] . $sBaseName );
			chmod( $dir['sdatingImage'] . $sPictureName, 0644 );
			chmod( $dir['sdatingImage'] . $sThumbName, 0644 );
			chmod( $dir['sdatingImage'] . $sIconName, 0644 );
			$sEventPhotoFilename = process_db_input( $sPictureName );
		} else
			$sEventPhotoFilename = '';

		$sPictureSQL = '';
		if ($iEventID>0 && $sEventPhotoFilename!='') {
			$sPictureSQL = "`PhotoFilename` = '{$sEventPhotoFilename}',";
		}
		// event date
		$sEventStart = strtotime( $_REQUEST['event_start'] );
		if ( $sEventStart == -1 )
			return SDATING_ERROR_WRONG_DATE_FORMAT;

		if ($this->bAdminMode) {
			$sEventEnd = strtotime( $_POST['event_end'] );
			//if ( $sEventEnd == -1 )
			//	return SDATING_ERROR_WRONG_DATE_FORMAT;
			$sEventSaleStart = strtotime( $_POST['event_sale_start'] );
			//if ( $sEventSaleStart == -1 )
			//	return SDATING_ERROR_WRONG_DATE_FORMAT;
			$sEventSaleEnd = strtotime( $_POST['event_sale_end'] );
			//if ( $sEventSaleEnd == -1 )
			//	return SDATING_ERROR_WRONG_DATE_FORMAT;
			//if ( $sEventEnd < $sEventStart || $sEventSaleEnd < $sEventSaleStart || $sEventStart < $sEventSaleStart )
			//	return SDATING_ERROR_WRONG_DATE_FORMAT;
			$sEventEndVal = "FROM_UNIXTIME( {$sEventEnd} )";
			$sEventSaleStartVal = "FROM_UNIXTIME( {$sEventSaleStart} )";
			$sEventSaleEndVal = "FROM_UNIXTIME( {$sEventSaleEnd} )";
		} else {
			$sEventEndVal = 'NOW()';
			$sEventSaleStartVal = 'NOW()';
			$sEventSaleEndVal = 'NOW()';
		}

		// event responsible
		$sEventRespId    = ($this->bAdminMode) ? 0 : process_db_input( $aMemberData['ID'], 0, 1 );
		$sEventRespName  = ($this->bAdminMode) ? _t('Admin') : process_db_input( $aMemberData['NickName'], 0, 1 );
		$sEventRespEmail = ($this->bAdminMode) ? $site['email'] : process_db_input( $aMemberData['Email'], 0, 1 );
		$sEventRespPhone = ($this->bAdminMode) ? '666' : process_db_input( $aMemberData['Phone'], 0, 1 );

		$iEventAgeLowerFilter = (int)getParam( 'search_start_age' );
		$iEventAgeUpperFilter = (int)getParam( 'search_end_age' );

		$sEventMembershipFilter = "\'all\'";

		$iEventCountF = (int)$_POST['event_count_female'];
		$iEventCountM = (int)$_POST['event_count_male'];

		$dEventPriceF = '0.00';
		$dEventPriceM = '0.00';
		$dEventPriceC = '0.00';

		// choose options
		$iEventChoosePeriod = 5;

		// allow to view participants
		$iEventAllowView = '1';

		if ($iEventID==-1) {
			$sNewUri = uriGenerate($sEventTitle, 'SDatingEvents', 'EntryUri', 100);
			//Commented elements
			/*`EventEnd` = FROM_UNIXTIME( {$sEventEnd} ),*/
			/*`TicketSaleStart` = FROM_UNIXTIME( {$sEventSaleStart} ),*/
			/*`TicketSaleEnd` = FROM_UNIXTIME( {$sEventSaleEnd} ),*/
			
			$vRes = db_res( "INSERT INTO `SDatingEvents` SET
						`Title` = '{$sEventTitle}',
						`EntryUri` = '{$sNewUri}',
						`Description` = '{$sEventDesc}',
						`Status` = 'Active',
						`StatusMessage` = '{$sEventStatusMessage}',
						`Country` = '{$sEventCountry}',
						`City` = '{$sEventCity}',
						`Place` = '{$EventPlace}',
						`PhotoFilename` = '{$sEventPhotoFilename}',
						`EventStart` = FROM_UNIXTIME( {$sEventStart} ),
						`EventEnd` = {$sEventEndVal},
						`TicketSaleStart` = {$sEventSaleStartVal},
						`TicketSaleEnd` = {$sEventSaleEndVal},
						`ResponsibleID` = '{$sEventRespId}',
						`ResponsibleName` = '{$sEventRespName}',
						`ResponsibleEmail` = '{$sEventRespEmail}',
						`ResponsiblePhone` = '{$sEventRespPhone}',
						`EventSexFilter` = 'female,male',
						`EventAgeLowerFilter` = {$iEventAgeLowerFilter},
						`EventAgeUpperFilter` = {$iEventAgeUpperFilter},
						`EventMembershipFilter` = '{$sEventMembershipFilter}',
						`TicketCountFemale` = {$iEventCountF},
						`TicketCountMale` = {$iEventCountM},
						`TicketPriceFemale` = {$dEventPriceF},
						`TicketPriceMale` = {$dEventPriceM},
						`ChoosePeriod` = {$iEventChoosePeriod},
						`AllowViewParticipants` = {$iEventAllowView},
						`Tags` = '{$sTags}'
						" );
			$iLastID = mysql_insert_id();
			if ($iLastID>0) {
				$this->iLastInsertedID = $iLastID;
				reparseObjTags( 'event', $iLastID );
			}
		} else {
			//$sNewUri = uriGenerate($sEventTitle, 'SDatingEvents', 'EntryUri', 100);
			//Commented elements
			/*`EventEnd` = FROM_UNIXTIME( {$sEventEnd} ),*/
			/*`TicketSaleStart` = FROM_UNIXTIME( {$sEventSaleStart} ),*/
			/*`TicketSaleEnd` = FROM_UNIXTIME( {$sEventSaleEnd} ),*/

			$vRes = db_res( "UPDATE `SDatingEvents` SET
						`Title` = '{$sEventTitle}',
						`Description` = '{$sEventDesc}',
						`Status` = 'Active',
						`StatusMessage` = '{$sEventStatusMessage}',
						`Country` = '{$sEventCountry}',
						`City` = '{$sEventCity}',
						`Place` = '{$EventPlace}',
						{$sPictureSQL}
						`EventStart` = FROM_UNIXTIME( {$sEventStart} ),
						`EventEnd` = {$sEventEndVal},
						`TicketSaleStart` = {$sEventSaleStartVal},
						`TicketSaleEnd` = {$sEventSaleEndVal},
						`ResponsibleID` = '{$sEventRespId}',
						`ResponsibleName` = '{$sEventRespName}',
						`ResponsibleEmail` = '{$sEventRespEmail}',
						`ResponsiblePhone` = '{$sEventRespPhone}',
						`EventSexFilter` = 'female,male',
						`EventAgeLowerFilter` = {$iEventAgeLowerFilter},
						`EventAgeUpperFilter` = {$iEventAgeUpperFilter},
						`EventMembershipFilter` = '{$sEventMembershipFilter}',
						`TicketCountFemale` = {$iEventCountF},
						`TicketCountMale` = {$iEventCountM},
						`TicketPriceFemale` = {$dEventPriceF},
						`TicketPriceMale` = {$dEventPriceM},
						`ChoosePeriod` = {$iEventChoosePeriod},
						`AllowViewParticipants` = {$iEventAllowView},
						`Tags` = '{$sTags}'
						WHERE `ID` = {$iEventID}
						" );
			reparseObjTags( 'event', $iEventID );
		}
		return SDATING_ERROR_SUCCESS;
	}

	/**
	 * page show event list function
	 * @return HTML presentation of data
	 */
	function PageSDatingShowEvents() {
		global $site;
		global $dir;
		global $aPreValues;
		global $oTemplConfig;
		//global $date_format;
		global $tmpl;

		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sSureC = _t("_Are you sure");
		$sTagsC = _t('_Tags');

		// collect information about current member
		$aMember['ID'] = (int)$_COOKIE['memberID'];
		$aMemberData = getProfileInfo( $aMember['ID'] );
		$sMemberSex = $aMemberData['Sex'];
		$aMembership = getMemberMembershipInfo( $aMember['ID'] );
		$bShowFrom = (int)$_REQUEST['from'];

		//$sDateWhereCheck = ($this->bAdminMode) ? "AND NOW() < DATE_ADD(`SDatingEvents`.`EventEnd`, INTERVAL `SDatingEvents`.`ChoosePeriod` DAY)" : '';
		$sDateWhereCheck = '';//'A' commented like bug
		$sCommonSelectSQL = "DISTINCT `SDatingEvents`.`ID`, `Title`, `EntryUri`, `Description`, `StatusMessage`, `Country`, `City`, `Place`, `PhotoFilename`, `Tags`, ";
		$sCommonSelectSQL  .= "`EventStart`, UNIX_TIMESTAMP(`EventStart`) AS 'EventStart_UTS', ";
		$sCommonSelectSQL  .= "(UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`EventStart`)) AS `sec`, `ResponsibleID`, ";
		$sCommonSelectSQL  .= "(NOW() > `EventEnd` AND NOW() < DATE_ADD(`EventEnd`, INTERVAL `ChoosePeriod` DAY)) AS `ChooseActive`, ";
		$sCommonSelectSQL  .= "`AllowViewParticipants`, (`SDatingParticipants`.`ID` IS NOT NULL) AS `IsParticipant` ";
		$sLeftJoinAddonSQL = "LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID` AND `SDatingParticipants`.`IDMember` = {$aMember['ID']}";
		$sStatusActiveSQL = "`SDatingEvents`.`Status` = 'Active'";
		$sOrderBySQL = "ORDER BY `SDatingEvents`.`EventStart` DESC";

		// build SQL query for event listing
		$sShowQuery = '';
		switch ( $_REQUEST['show_events'] ) {
			case 'country':
				// queries for showing 'by country'
				$sShowQuery = "
					SELECT
						{$sCommonSelectSQL }
					FROM `SDatingEvents`
					{$sLeftJoinAddonSQL}
					WHERE
						{$sStatusActiveSQL}
						{$sDateWhereCheck}
						AND `SDatingEvents`.`Country` = '". process_db_input($_REQUEST['show_events_country']) ."'
					{$sOrderBySQL} {$sLimitSQL}
				";
				$sCountSQL = "
					SELECT COUNT(*) FROM `SDatingEvents`
					WHERE
						{$sStatusActiveSQL}
						{$sDateWhereCheck}
						AND `Country` = '". process_db_input($_REQUEST['show_events_country']) ."'
				";
				break;
			case 'my':
				// queries for showing my events
				$sShowQuery = "
					SELECT
						{$sCommonSelectSQL }
					FROM `SDatingEvents`
					{$sLeftJoinAddonSQL}
					WHERE
						`ResponsibleID` = {$aMember['ID']}
					{$sOrderBySQL} {$sLimitSQL}
				";
				$sCountSQL = "
					SELECT COUNT(*) FROM `SDatingEvents`
				";
				break;
			case 'all':
			default:
				// queries for showing all available events
				$sShowQuery = "
					SELECT
						{$sCommonSelectSQL }
					FROM `SDatingEvents`
					{$sLeftJoinAddonSQL}
					WHERE
						{$sStatusActiveSQL}
						{$sDateWhereCheck}
					{$sOrderBySQL} {$sLimitSQL}
				";
				$sCountSQL = "
					SELECT COUNT(*) FROM `SDatingEvents`
					WHERE
						{$sStatusActiveSQL}
						{$sDateWhereCheck}
					";
		}


		////////////////////////////
		$iTotalNum = db_value( $sCountSQL );
		if( !$iTotalNum ) {
			return MsgBox(_t( '_Sorry, nothing found' ));
		}

		$iPerPage = (int)$_GET['per_page'];
		if( !$iPerPage )
			$iPerPage = 10;

		$iTotalPages = ceil( $iTotalNum / $iPerPage );

		$iCurPage = (int)$_GET['page'];

		if( $iCurPage > $iTotalPages )
			$iCurPage = $iTotalPages;

		if( $iCurPage < 1 )
			$iCurPage = 1;

		$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;
		$sqlLimit = "LIMIT {$sLimitFrom}, {$iPerPage}";

		$sShowQuery .= $sqlLimit;

		// process database queries
		$vShowRes = db_res( $sShowQuery );
		////////////////////////////

		$iShowNum = mysql_num_rows( $vShowRes );

		if ( $iShowNum == 0 ) {
			// show if no events available
			$sRetHtml .= DesignBoxContent( _t('_SpeedDating events'), '<center>'. _t('_No events available') .'</center>', $oTemplConfig -> PageSDatingShowEvents_db_num );
		}
		else {
			$sSpacerName = $this -> sSpacerPath;

			// print list of events
			while ( $aResSQL = mysql_fetch_assoc($vShowRes) ) {

				$sImgEL = ( strlen(trim($aResSQL['PhotoFilename'])) && file_exists($dir['sdatingImage'] . $aResSQL['PhotoFilename']) )
					? "<img class=\"photo1\" style=\"width:{$this->iThumbSize}px;height:{$this->iThumbSize}px;background-image:url({$site['sdatingImage']}thumb_{$aResSQL['PhotoFilename']});\" src=\"{$sSpacerName}\" alt=\"{$aResSQL['Title']}\" />"
					: "<img class=\"photo1\" style=\"width:{$this->iThumbSize}px;height:{$this->iThumbSize}px;background-image:url({$site['url']}templates/tmpl_{$tmpl}/{$this->sPicNotAvail});\" src=\"{$sSpacerName}\" />";

				$sShowInfoC = _t('_Show info');
				$sParticipantsC = _t('_Participants');
				$sStatusMessageC = _t('_Status message');
				$sDateC = _t('_Date');
				$sPlaceC = _t('_Place');
				$sDescriptionC = _t('_Description');
				$sTitleC = _t('_Title');
				$sActionsC = _t('_Actions');
				$sListOfParticipantsC = _t('_List').' '._t('_of').' '._t('_Participants');
				$sPostedByC = _t('_Posted by');
				if ($aResSQL['ResponsibleID'] == 0) {
					$sPostedByHref = _t('_Admin');
				} else {
					$aPostedBy = $this->GetProfileData($aResSQL['ResponsibleID']);
					//$sPostedBy = $aPostedBy['NickName'];
					$sPostedByHref = getProfileLink($aResSQL['ResponsibleID']);
					$sPostedByHref = '<a href="'.$sPostedByHref.'">'.$aPostedBy['NickName'].'</a>';
				}

				$sGenUrlP = $this->genUrl($aResSQL['ID'], $aResSQL['EntryUri'], 'part');
				$sViewParticipantsVal = <<<EOF
<a href="{$sGenUrlP}">
	{$sListOfParticipantsC}
</a>
EOF;
				$sViewParticipants = ($aResSQL['AllowViewParticipants'] == '1') ? $sViewParticipantsVal : '';

				$sStatusMessage = process_line_output($aResSQL['StatusMessage']);
				$date_format_php = getParam('php_date_format');
				//$sDateTime = date( $date_format_php, strtotime( $aResSQL['EventStart'] ) );
				$sDateTime = LocaledDataTime($aResSQL['EventStart_UTS']);
				$sEventsStart = $sDateTime." ("._format_when($aResSQL['sec']).")";
				$sCountry = ($aResSQL['Country']!='') ? _t($aPreValues['Country'][$aResSQL['Country']]['LKey']) : '';
				$sCity = ($aResSQL['City']!='') ? ', '.process_line_output($aResSQL['City']) : '';
				$sPlace = ($aResSQL['Place']!='') ? ', '.process_line_output($aResSQL['Place']) : '';
				$sDescription = $aResSQL['Description'];

				$sTagsCommas = $aResSQL['Tags'];
				$aTags = split(',', $sTagsCommas);
				$sTagsHrefs = '';
				foreach( $aTags as $sTagKey ) {
					$sTagHrefGen = $this->genUrl(0, $sTagKey, 'search');
					$sTagsHrefs .= <<<EOF
<a href="{$sTagHrefGen}" >{$sTagKey}</a>&nbsp;
EOF;
				}
				$sTags = <<<EOF
<div class="cls_res_info_p">
	<!--<span style="vertical-align:middle;">
		<img src="{$site['icons']}tag_small.png" class="marg_icon" alt="" />
	</span>-->{$sTagsC}:&nbsp;{$sTagsHrefs}
</div>
EOF;

				$sActions = '';
				if ($aResSQL['ResponsibleID']==(int)$_COOKIE['memberID'] && $aResSQL['ResponsibleID'] > 0) {
					$sActions = <<<EOF
<div class="cls_res_info_p">
	<a href="{$_SERVER['PHP_SELF']}" onclick="UpdateField('EditEventID','{$aResSQL['ID']}');document.forms.command_edit_event.submit();return false;" style="text-transform:none;">{$sEditC}</a>&nbsp;
	<a href="{$_SERVER['PHP_SELF']}" onclick="if (confirm('{$sSureC}')) {UpdateField('DeleteEventID','{$aResSQL['ID']}');document.forms.command_delete_event.submit(); } return false;" style="text-transform:none;">{$sDeleteC}</a>
</div>
EOF;
				}

				$sGenUrl = $this->genUrl($aResSQL['ID'], $aResSQL['EntryUri']);
				$sImgEl = $this->GetEventPicture($aResSQL['ID'], $aResSQL['PhotoFilename']);
				$sMainContent = <<<EOF
<div class="cls_result_row">
	<div class="clear_both"></div>
	{$sImgEl}
	<div class="cls_res_info_nowidth" {$sDataStyleWidth}>
		<div class="cls_res_info_p">
			<a class="actions" href="{$sGenUrl}">{$aResSQL['Title']}</a>
		</div>
		{$sTags}
		<!-- <div class="cls_res_info_p">
			{$sStatusMessageC}: <div class="clr3">{$sStatusMessage}</div>
		</div> -->
		<div class="cls_res_info_p">
			{$sDateC}: <div class="clr3">{$sEventsStart}</div>
		</div>
		<div class="cls_res_info_p">
			{$sPostedByC}: <div class="clr3">{$sPostedByHref}</div>
		</div>
		<div class="cls_res_info_p">
			{$sPlaceC}: <div class="clr3">{$sCountry}{$sCity}{$sPlace}</div>
		</div>
		<div class="cls_res_info_p">
			{$sDescriptionC}: <div class="clr3">{$sDescription}</div>
		</div>
		<div class="cls_res_info_p">
			{$sViewParticipants}
		</div>
		{$sActions}
	</div>
	<div class="clear_both"></div>
</div>
EOF;

				$sRetHtml .= $sMainContent;
			}

			$iVar = 2;
			///////////////////////////
			if ($this->bUseFriendlyLinks==false || $_GET['show_events']!='all') { //old variant
				if( $iTotalPages > 1)
				{
					$sRequest = $_SERVER['PHP_SELF'] . '?';
					$aFields = array('show_events','action');
					
					foreach( $aFields as $field )
						if( isset( $_GET[$field] ) )
							$sRequest .= "&amp;{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );
					
					$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
							<select name="per_page" onchange="window.location=\'' . $sRequest . '&amp;per_page=\' + this.value;">
								<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
								<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
								<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
								<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
							</select></div>' .
						genPagination( $iTotalPages, $iCurPage, ( $sRequest . '&amp;page={page}&amp;per_page='.$iPerPage ) );
				}
				else
					$pagination = '';
			}
			else if ($this->bUseFriendlyLinks && $_GET['show_events']=='all') { //new vatiant
				if( $iTotalPages > 1) {
					//$sFriendlyAction = 'show_events=all&action=show';

					$sRequest = $site['url'] . 'events/all/';
					/*$aFields = array('show_events','action');

					foreach( $aFields as $field )
						if( isset( $_GET[$field] ) )
							$sFriendlyAction .= "{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) ) . '&amp;';*/

					$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
							<select name="per_page" onchange="window.location=\'' . $sRequest . '\' + this.value + \'/1\';">
								<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
								<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
								<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
								<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
							</select></div>' .
						genPagination( $iTotalPages, $iCurPage, ( $sRequest.$iPerPage . '/{page}' ) );
				} else $pagination = '';
			}
			///////////////////////////
		}

		$sRetHtml = $this->DecorateAsTable(_t('_All Events'), $sRetHtml.$pagination);
		return $sRetHtml;
	}

	/**
	 * Compose result into Wrapper class
	 *
	 * @param $sCaption	caption of Box
	 * @param $sValue	inner text of box
	 * @return HTML presentation of data
	 */
	function DecorateAsTable($sCaption, $sValue) {
		$sValueF = <<<EOF
<div class="cls_result_wrapper">
	{$sValue}
</div>
EOF;
		$sDecTbl = DesignBoxContent ( _t($sCaption), $sValueF, 1 );
		return $sDecTbl;
	}

	/**
	 * page show information about specified event
	 * @return HTML presentation of data
	 */
	function PageSDatingShowInfo() {
		global $site;
		global $tmpl;
		global $dir;
		global $aPreValues;
		global $doll;
		global $oTemplConfig;
		global $date_format;
		global $logged;

		// collect information about current member
		if( $logged['member'] ) {
			$aMember['ID'] = (int)$_COOKIE['memberID'];
			$aMemberData = getProfileInfo( $aMember['ID'] );
		} else
			$aMember['ID'] = 0;

		$sNoPhotoC = _t('_No photo');
		$sChangeC = _t('_Change');
		$sCanBuyTicketC = _t('_You can buy the ticket');
		$sBuyTicketC = _t('_Buy ticket');
		$sCountryC = _t('_Country');
		$sCityC = _t('_City');
		$sPlaceC = _t('_Place');
		$sEventStartC = _t('_Event start');
		$sDateC = _t('_Date');
		$sEventEndC = _t('_Event end');
		$sTicketSaleStartC = _t('_Ticket sale start');
		$sTicketSaleEndC = _t('_Ticket sale end');
		$sResponsiblePersonC = _t('_Responsible person');
		$sPostedByC = _t('_Posted by');
		$sTicketsLeftC = _t('_Tickets left');
		$sTicketPriceC = _t('_Ticket price');
		$sDescriptionC = _t('_Description');
		$sSaleStatusC = _t('_Sale status');
		$sEventC = _t('_Event');
		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sSureC = _t("_Are you sure");
		$sPictureC = _t('_Picture');
		$sStatusC = _t('_Status');
		$sPhoneC = _t('_Phone');
		$sEmailC = _t('_E-mail');
		$sActionsC = _t('_Actions');
		$sJoinC = _t('_Join');
		$sUnsubscribeC = _t('_Unsubscribe');
		$sParticipantsC = _t('_Participants');
		$sListOfParticipantsC = _t('_List').' '._t('_of').' '._t('_Participants');
		$sTagsC = _t('_Tags');
		$sYOC = _t('_y/o');

		/*$iEventID = (int)$_REQUEST['event_id'];*/
		$iEventID = ($this->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `SDatingEvents` WHERE `EntryUri`='" . $this->process_html_db_input($_REQUEST['eventUri']) . "' LIMIT 1") : (int)$_REQUEST['event_id'];

		if ($this->iLastInsertedID > 0) {
			$iEventID = $this->iLastInsertedID;
			$this->iLastInsertedID = -1;
		}

		$sQuery =  "
			SELECT
				`SDatingEvents`.`ID` AS `EventIDN`,
				`Title`,
				`EntryUri`,
				`Description`,
				`PhotoFilename`,
				`StatusMessage`,
				`Country`,
				`City`,
				`Place`,
				`Tags`,
				`EventStart`,
				UNIX_TIMESTAMP(`EventStart`) AS 'EventStart_UTS',
				DATE_FORMAT(`EventEnd`, '{$date_format}' ) AS EventEnd,
				(NOW() > `EventStart`) AS `EventBegan`,
				(NOW() < `EventEnd`) AS `EventNotFinished`,
				DATE_FORMAT(`TicketSaleStart`, '{$date_format}' ) AS 'TicketSaleStart',
				DATE_FORMAT(`TicketSaleEnd`, '{$date_format}' ) AS 'TicketSaleEnd',
				(NOW() > `TicketSaleStart`) AS `SaleBegan`,
				(NOW() < `TicketSaleEnd`) AS `SaleNotFinished`,
				(UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`EventStart`)) AS `sec`,
				`ResponsibleID`,
				`ResponsibleName`,
				`ResponsibleEmail`,
				`ResponsiblePhone`,
				`TicketPriceFemale`,
				`TicketPriceMale`,
				`TicketCountFemale`,
				`TicketCountMale`,
				(NOW() > `EventEnd` AND NOW() < DATE_ADD(`EventEnd`, INTERVAL `ChoosePeriod` DAY)) AS `ChooseActive`,
				(`SDatingParticipants`.`ID` IS NOT NULL) AS `IsParticipant`
			FROM `SDatingEvents`
			LEFT JOIN `SDatingParticipants` ON
				`SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID`
				AND `SDatingParticipants`.`IDMember` = {$aMember['ID']}
			WHERE
				`SDatingEvents`.`ID` = {$iEventID}
				AND `SDatingEvents`.`Status` = 'Active'
			";

		$aEventData = db_arr( $sQuery );
		if ( !is_array($aEventData) || count($aEventData) == 0 )
			return DesignBoxContent( '', '<center>'. _t('_Event is unavailable') .'</center>', $oTemplConfig -> PageSDatingShowInfo_db_num );

		$sQuery = "
			SELECT COUNT(*)
			FROM `SDatingParticipants`
			LEFT JOIN `Profiles` ON
				`SDatingParticipants`.`IDMember` = `Profiles`.`ID`
			WHERE
				`SDatingParticipants`.`IDEvent` = {$iEventID}
				AND `Profiles`.`Sex` = '{$sMemberSex}'
			";
		
		$aPartNum = db_arr( $sQuery );
		$iPartNum = (int)$aPartNum[0];
		$iTicketsLeft = ( $aMemberData['Sex'] == 'male' ? $aEventData['TicketCountMale'] - $iPartNum : $aEventData['TicketCountFemale'] - $iPartNum );
		$iTicketPrice = (float)( $aMemberData['Sex'] == 'male' ? $aEventData['TicketPriceMale'] : $aEventData['TicketPriceFemale'] );

		// change participant UID
		$sErrorMessage = '';
		if ( isset($_POST['change_uid']) && $_POST['change_uid'] == 'on' ) {
			// check if this UID doesn't exist for this event
			$sNewUid = process_db_input($_POST['participant_uid']);
			$aExistUid = db_arr( "SELECT `ID` FROM `SDatingParticipants`
									WHERE `IDEvent` = {$iEventID}
									AND `IDMember` <> {$aMember['ID']}
									AND LOWER(`ParticipantUID`) = LOWER('$sNewUid')" );
			if ( !$aExistUid['ID'] ) {
				$vRes = db_res( "UPDATE `SDatingParticipants`
									SET `ParticipantUID` = '$sNewUid'
									WHERE `IDEvent` = $iEventID
									AND `IDMember` = {$aMember['ID']}" );
				if ( !$vRes )
					$sErrorMessage = _t('_Cant change participant UID');
			} else {
				$sErrorMessage = _t('_UID already exists');
			}
		}

		// if ticket is free then buy it here without any checkouts
		if ( isset($_POST['purchase_ticket']) && $_POST['purchase_ticket'] == 'on' && !$aEventData['IsParticipant'] and $logged['member'] ) {
			if ( $aEventData['SaleBegan'] && $aEventData['SaleNotFinished'] && $iTicketsLeft > 0 && $iTicketPrice <= 0.0 ) {
				// insert into participants table
				$iParticipantUID = $aMemberData['NickName'] . $iEventID . rand(100, 999);
				$vRes = db_res( "INSERT INTO `SDatingParticipants` SET `IDEvent` = {$iEventID}, `IDMember` = {$aMember['ID']}, `ParticipantUID` = '{$iParticipantUID}'", 0 );
				if ( !$vRes ) {
					$sErrorMessage = _t('Error: Participant subscription error');
				} else {
					$sSubject = getParam( 't_SDatingCongratulation_subject' );
					$sMessage = getParam( 't_SDatingCongratulation' );

					$aPlus = array();
					$aPlus['NameSDating'] = $aEventData['Title'];
					$aPlus['PlaceSDating'] = $aEventData['Place'];
					$aPlus['WhenStarSDating'] = $aEventData['EventStart'];
					$aPlus['PersonalUID'] = $iParticipantUID;
					$sGenUrl = $this->genUrl($iEventID, $aEventData['EntryUri']);
					$aPlus['LinkSDatingEvent'] = $sGenUrl;

					$vMailRes = sendMail( $aMemberData['Email'], $sSubject, $sMessage, $aMember['ID'], $aPlus );

					if ( !$vMailRes )
						$_POST['result'] = 3;
					else
						$_POST['result'] = 1;
				}
			} else {
				$_POST['result'] = -1;
			}
		}
		elseif ( isset($_POST['join_event']) && $_POST['join_event'] == 'on' && $logged['member'] ) {
			// insert into participants table
			$iParticipantUID = $aMemberData['NickName'] . $iEventID . rand(100, 999);
			$vRes = db_res( "INSERT INTO `SDatingParticipants` SET `IDEvent` = {$iEventID}, `IDMember` = {$aMember['ID']}, `ParticipantUID` = '{$iParticipantUID}'", 0 );
			if ( !$vRes ) {
				$sErrorMessage = _t('Error: Participant subscription error');
				$sRetHtml .= '<script type="text/javascript">alert("'._t( '_Sorry, you\'re already joined' ).'");</script>';
			} else {
				$sSubject = getParam( 't_SDatingCongratulation_subject' );
				$sMessage = getParam( 't_SDatingCongratulation' );

				$aPlus = array();
				$aPlus['NameSDating'] = $aEventData['Title'];
				$aPlus['PlaceSDating'] = $aEventData['Place'];
				$aPlus['WhenStarSDating'] = $aEventData['EventStart'];
				$aPlus['PersonalUID'] = $iParticipantUID;
				$sGenUrl = $this->genUrl($iEventID, $aEventData['EntryUri']);
				$aPlus['LinkSDatingEvent'] = $sGenUrl;

				$vMailRes = sendMail( $aMemberData['Email'], $sSubject, $sMessage, $aMember['ID'], $aPlus );

				$sRetHtml .= '<script type="text/javascript">alert("'._t('_You have successfully joined this Event').'");</script>';

				if ( !$vMailRes )
					$_POST['result'] = 3;
				else
					$_POST['result'] = 1;
			}
		} elseif ( isset($_POST['unsubscribe_event']) && $_POST['unsubscribe_event'] == 'on' && $logged['member'] ) {
			// remove from participants table
			$vRes = db_res("DELETE FROM `SDatingParticipants` WHERE `IDEvent` = {$iEventID} AND `IDMember` = {$aMember['ID']} LIMIT 1");
			if (mysql_affected_rows() == 0) {
				$sErrorMessage = _t('Error: Participant unsubscription error');
				$sRetHtml .= '<script type="text/javascript">alert("'._t( '_Error Occured' ).'");</script>';
			} else {
				$sRetHtml .= '<script type="text/javascript">alert("'._t('_You have successfully unsubscribe from Event').'");</script>';
			}
		} elseif ( isset($_POST['join_event']) && $_POST['join_event'] == 'on' && $logged['member']==false ) {
			$this->CheckLogged();
		}

		$aMemberPart = db_arr( "SELECT `ID`, `ParticipantUID` FROM `SDatingParticipants`
										WHERE `IDEvent` = $iEventID
										AND `IDMember` = {$aMember['ID']}" );

		$sErrElems = '';
		if ( isset($_POST['result']) ) {
			$sResult = '';
			switch ( $_POST['result'] ) {
				case '-1':   $sResult = _t('_RESULT-1'); break;
				case '0':    $sResult = _t('_RESULT0'); break;
				case '1':    $sResult = _t('_RESULT1_THANK', $aEventData['Title'] , $aEventData['EventStart']); break;
				case '3':    $sResult = _t('_RESULT_SDATING_MAIL_NOT_SENT'); break;
				case '1000': $sResult = _t('_RESULT1000'); break;
			}
			$sErrElems .= '<div class="err">'.$sResult.'</div>';
		}

		$sPicElement = '';
		$sSpacerName = $this -> sSpacerPath;
		if ( strlen(trim($aEventData['PhotoFilename'])) && file_exists($dir['sdatingImage'] . $aEventData['PhotoFilename']) ) {
			$sPicName = $site['sdatingImage'].$aEventData['PhotoFilename'];
			$sPicElement .= "<img class=\"photo\" alt=\"\" style=\"width:{$this -> iImgSize}px;height:{$this -> iImgSize}px;background-image:url({$sPicName});\" src=\"{$sSpacerName}\" border=\"0\" />";
			$sPicElement1 .= '
				<img src="'.$site['sdatingImage'].$aEventData['PhotoFilename'].'" border="0" alt="'._t('_SDating photo alt', $aEventData['Title']).'" />';
		} else {
			$sPicNaName = "{$site['url']}templates/tmpl_{$tmpl}/{$this -> sPicNotAvail}";
			$sPicElement .= "<img class=\"photo\" alt=\"\" style=\"width:{$this -> iImgSize}px;height:{$this -> iImgSize}px;background-image:url({$sPicNaName});\" src=\"{$sSpacerName}\" border=\"0\" />";
			$sPicElement1 .= '
				<div align="center" class="text" style="width: 200px; height: 150px; vertical-align: middle; line-height: 150px; border: 1px solid silver;">'.$sNoPhotoC.'</div>';
		}

		/*$sInnerData = '';
		if ( $aMemberPart['ID'] ) {
			if ( $aEventData['EventBegan'] && $aEventData['EventNotFinished'] ) {
				$sInnerData .= _t('_Event started');
			}
			elseif ( $aEventData['ChooseActive'] ) {
				$sInnerData .= _t('_Event finished') .". <a href=\"{$_SERVER['PHP_SELF']}?action=select_match&amp;event_id={$iEventID}\">". _t('_Choose participants you liked') ."</a>";
			} else {
				if ( strlen($sErrorMessage) )
					$sInnerData .= "<div align=\"center\" class=\"err\" style=\"width: 100%;\">{$sErrorMessage}</div>\n";
					$sInnerData .= _t('_You are participant of event').'<br />';
					$sParticipantUID = htmlspecialchars($aMemberPart['ParticipantUID']);
					$sInnerData .= <<<EOF
<center>
	<form id="changeUIDForm" action="{$_SERVER['PHP_SELF']}?action=show_info&event_id={$iEventID}" method="post" style="margin: 2px;">
		<input type="hidden" name="change_uid" value="on" />
		<input type="text" class="no" name="participant_uid" value="{$sParticipantUID}" size="20" maxlength="30" style="vertical-align: middle;" />&nbsp;
		<input type="submit" class="no" value="{$sChangeC}" style="width: 80px; vertical-align: middle" />
	</form>
</center>
EOF;
			}
		}
		elseif ( $aEventData['SaleBegan'] and $aEventData['SaleNotFinished'] and $logged['member'] ) {
			if ( $iTicketsLeft > 0 ) {
				if ( $iTicketPrice > 0.0 ) {
					$sInnerData .= <<<EOF
{$sCanBuyTicketC}<br />
<center>
	<form id="buyTicketForm" action="{$site['url']}checkout.php" method="post" style="margin: 2px;">
		<input type="hidden" name="action" value="collect" />
		<input type="hidden" name="checkout_action" value="speeddating" />
		<input type="hidden" name="data" value="{$iEventID}" />
		<input type="hidden" name="amount" value="{$iTicketPrice}" />
		<input type="submit" class="no" value="{$sBuyTicketC}" style="width: 100px; vertical-align: middle;" />
	</form>
</center>
EOF;
				}
				else {
					$sInnerData .= <<<EOF
{$sCanBuyTicketC}<br />
<center>
	<form id="buyTicketForm" action="{$_SERVER['PHP_SELF']}?action=show_info&event_id={$iEventID}" method="post" style="margin: 2px;">
		<input type="hidden" name="purchase_ticket" value="on" />
		<input type="submit" class="no" value="{$sBuyTicketC}" style="width: 100px; vertical-align: middle;" />
	</form>
</center>
EOF;
				}
			} else {
				$sInnerData .=  _t('_No tickets left');
			}
		} elseif ( $aEventData['SaleBegan'] ) {
			$sInnerData .=  _t('_Sale finished');
		} else {
			$sInnerData .=  _t('_Sale not started yet');
		}*/

		$sStatusMessage = process_line_output($aEventData['StatusMessage']);
		$sCountryPic = _t($aPreValues['Country'][$aEventData['Country']]['LKey']);
		$sCity = process_line_output($aEventData['City']);
		$sPlace = process_line_output($aEventData['Place']);
		$sResponsiblePerson = process_line_output($aEventData['ResponsibleName']);
		if ($aEventData['ResponsibleID'] == 0) {
			$sPostedByHref = _t('_Admin');
		} else {
			$aPostedBy = $this->GetProfileData($aEventData['ResponsibleID']);
			//$sPostedBy = $aPostedBy['NickName'];
			$sPostedByHref = getProfileLink($aEventData['ResponsibleID']);
			$sPostedByHref = '<a href="'.$sPostedByHref.'">'.$aPostedBy['NickName'].'</a>';
		}
		$sPhone = process_line_output($aEventData['ResponsiblePhone']);
		$sEmail = process_line_output($aEventData['ResponsibleEmail']);
		$sTicketPrice = ($iTicketPrice > 0.0) ? $doll . $iTicketPrice : _t('_free');
		$sDescription = /*process_text_withlinks_output*/ $aEventData['Description'];
		$sTitle = process_line_output($aEventData['Title']);

		$sTagsVals = '';
		$sTagsCommas = $aEventData['Tags'];
		$aTags = split(',', $sTagsCommas);
		foreach( $aTags as $sTagKey ) {
			if ($sTagKey != '') {
				if( isset($aTagsPost[$sTagKey] ) )
					$aTagsPost[$sTagKey]++;
				else
					$aTagsPost[$sTagKey] = 1;
			}
		}

		if (count($aTagsPost)) {
			foreach( $aTagsPost as $varKey => $varValue ) {
				$sTagHrefGen = $this->genUrl(0, $varKey, 'search');
				$sTagsVals .= <<<EOF
<span style="vertical-align:middle;"><img src="{$site['icons']}tag.png" class="marg_icon" alt="" /></span>
<a class="actions" href="{$sTagHrefGen}" style="text-transform:capitalize;" >{$varKey}</a>&nbsp;({$varValue})
<br />
EOF;
			}
		}

		$sActions = '';
		if ($aEventData['ResponsibleID']==(int)$_COOKIE['memberID']) {
			$sActions = <<<EOF
<div class="padds">
	<img src="{$site['icons']}categ_edit.png" alt="{$sEditC}" title="{$sEditC}" class="marg_icon" />
	<a class="actions" href="{$_SERVER['PHP_SELF']}" onclick="UpdateField('EditEventID','{$aEventData['EventIDN']}');document.forms.command_edit_event.submit();return false;" style="text-transform:none;">{$sEditC}</a>&nbsp;
</div>
<div class="padds">
	<img src="{$site['icons']}action_block.gif" alt="{$sEditC}" title="{$sEditC}" class="marg_icon" />
	<a class="actions" href="{$_SERVER['PHP_SELF']}" onclick="if (confirm('{$sSureC}')) {UpdateField('DeleteEventID','{$aEventData['EventIDN']}');document.forms.command_delete_event.submit(); } return false;" style="text-transform:none;">{$sDeleteC}</a>
</div>
EOF;
		}
		$sUsersActions = '';
		$sPartProfSQL = "SELECT * FROM `SDatingParticipants` WHERE `IDEvent`={$iEventID} AND `IDMember`={$aMember['ID']}";
		$aPartProfSQL = db_arr($sPartProfSQL);
		if (mysql_affected_rows() == 0) { //no matches
			$sUsersActions = <<<EOF
<div class="padds">
	<img src="{$site['icons']}_membership.jpg" alt="{$sJoinC}" title="{$sJoinC}" class="marg_icon" />
	<a class="actions" href="{$_SERVER['PHP_SELF']}" onclick="document.forms.JoinEventForm.submit(); return false;" >
		{$sJoinC}
	</a>
</div>
EOF;
		} else {
			$sUsersActions = <<<EOF
<div class="padds">
	<img src="{$site['icons']}action_block.gif" alt="{$sUnsubscribeC}" title="{$sUnsubscribeC}" class="marg_icon" />
	<a class="actions" href="{$_SERVER['PHP_SELF']}" onclick="document.forms.UnsubscribeEventForm.submit(); return false;" >
		{$sUnsubscribeC}
	</a>
</div>
EOF;
		}

		$vPartProfilesRes = db_res( "
			SELECT `Profiles`.*, `SDatingParticipants`.`ParticipantUID` AS `UID` FROM `SDatingParticipants`
			INNER JOIN `Profiles` ON `SDatingParticipants`.`IDMember` = `Profiles`.`ID`
			WHERE `SDatingParticipants`.`IDEvent` = {$iEventID}
			ORDER BY RAND() LIMIT 2" );
		$sParticipants = '';
		while ( $aPartProfiles = mysql_fetch_assoc($vPartProfilesRes) ) {
			$iUserIsOnline = get_user_online_status($aPartProfiles[ID]);
			$sCont = get_member_thumbnail($aPartProfiles['ID'], 'none' ) . '<br /><center>' . getProfileOnlineStatus( $iUserIsOnline ) . '</center>';
			$sThumb = get_member_thumbnail($aPartProfiles['ID'], 'none' );
			$sProfLink = getProfileLink($aPartProfiles['ID']);
			$sAge = age( $aPartProfiles['DateOfBirth'] ). $sYOC;
			$sParticipants .= <<<EOF
<div style="float:left;text-align:center;margin-right:10px;position:relative;">
	{$sThumb}
	<div class="browse_nick" style="width:{$this->iThumbSize}px;">
		<a href="{$sProfLink}">{$aPartProfiles['NickName']}</a>: {$sAge}
	</div>
</div>
EOF;
		}

		$sAdminTicketsPart = '';$sAdminTicketsPart2='';
		$sStatusSect = '';
		if ($aEventData['ResponsibleID']==0) {
			$sAdminTicketsPart = <<<EOF
<div class="cls_res_info">
	{$sEventEndC}: <div class="clr3">{$aEventData['EventEnd']}</div>
</div>
<div class="cls_res_info">
	{$sTicketSaleStartC}: <div class="clr3">{$aEventData['TicketSaleStart']}</div>
</div>
<div class="cls_res_info">
	{$sTicketSaleEndC}: <div class="clr3">{$aEventData['TicketSaleEnd']}</div>
</div>
EOF;
			$sAdminTicketsPart2 = <<<EOF
<div class="cls_res_info">
	{$sTicketsLeftC}: <div class="clr3">{$iTicketsLeft}</div>
</div>
<div class="cls_res_info">
	{$sTicketPriceC}: <div class="clr3">{$sTicketPrice}</div>
</div>
<tr class="panel">
	<td colspan="2" align="center" class="profile_header"><b>{$sSaleStatusC}</b></td>
</tr>
<!-- <tr>
	<td colspan="2" align="left" class="profile_td_2">
		{$sInnerData}
	</td>
</tr> -->
EOF;

			$sStatusSectFDB = <<<EOF
<div class="cls_res_info">
	<div class="clr3">{$sStatusMessage}</div>
</div>
<div class="clear_both"></div>
EOF;

			$sStatusSect = DesignBoxContent($sStatusC, $sStatusSectFDB, 1);
			/*$sStatusSect = <<<EOF
<div class="disignBoxFirst">
	<div class="boxFirstHeader">
		{$sStatusC}
	</div>
	<div class="boxContent">
		<div class="cls_res_info">
			<div class="clr3">{$sStatusMessage}</div>
		</div>
		<div class="clear_both"></div>
	</div>
</div>
EOF;*/
		}

		$sImageSectFDB = <<<EOF
<div class="photoBlock">
	{$sPicElement}
</div>
<div class="clear_both"></div>
EOF;
		$sImageSect = DesignBoxContent($sEventC.' '.$sPictureC, $sImageSectFDB, 1);
		/*$sImageSect = <<<EOF
<div class="disignBoxFirst">
	<div class="boxFirstHeader">
		{$sEventC} {$sPictureC}
	</div>
	<div class="boxContent">
		<div class="photoBlock">
			{$sPicElement}
		</div>
		<div class="clear_both"></div>
	</div>
</div>
EOF;*/

		$sActionsSectFDB = <<<EOF
{$sUsersActions}
{$sActions}
<div class="clear_both"></div>
EOF;
		$sActionsSect = DesignBoxContent($sActionsC, $sActionsSectFDB, 1);
		/*$sActionsSect = <<<EOF
<div class="disignBoxFirst">
	<div class="boxFirstHeader">
		{$sActionsC}
	</div>
	<div class="boxContent">
		{$sUsersActions}
		{$sActions}
		<div class="clear_both"></div>
	</div>
</div>
EOF;*/

		$sEventsStart = _format_when($aEventData['sec']);
		$date_format_php = getParam('php_date_format');
		//$sDateTime = date( $date_format_php, strtotime( $aEventData['EventStart'] ) );
		$sDateTime = LocaledDataTime($aEventData['EventStart_UTS']);

		$sSubjectSectFDB = <<<EOF
<div class="cls_res_info">
	{$sCountryC}: <div class="clr3">{$sCountryPic}</div>
</div>
<div class="cls_res_info">
	{$sCityC}: <div class="clr3">{$sCity}</div>
</div>
<div class="cls_res_info">
	{$sPlaceC}: <div class="clr3">{$sPlace}</div>
</div>
<div class="cls_res_info">
	{$sDateC}: <div class="clr3">{$sDateTime} ({$sEventsStart})</div>
</div>
{$sAdminTicketsPart}
<div class="cls_res_info">
	{$sPostedByC}: <div class="clr3">{$sPostedByHref}</div>
</div>
{$sAdminTicketsPart2}
<div class="clear_both"></div>
EOF;
		$sSubjectSect = DesignBoxContent($sTitle, $sSubjectSectFDB, 1);
		/*$sSubjectSect = <<<EOF
<div class="disignBoxFirst">
	<div class="boxFirstHeader">
		{$sTitle}
	</div>
	<div class="boxContent">
		<div class="cls_res_info">
			{$sCountryC}: <div class="clr3">{$sCountryPic}</div>
		</div>
		<div class="cls_res_info">
			{$sCityC}: <div class="clr3">{$sCity}</div>
		</div>
		<div class="cls_res_info">
			{$sPlaceC}: <div class="clr3">{$sPlace}</div>
		</div>
		<div class="cls_res_info">
			{$sDateC}: <div class="clr3">{$sDateTime} ({$sEventsStart})</div>
		</div>
		{$sAdminTicketsPart}
		<div class="cls_res_info">
			{$sPostedByC}: <div class="clr3">{$sPostedByHref}</div>
		</div>
		{$sAdminTicketsPart2}
		<div class="clear_both"></div>
	</div>
</div>
EOF;*/

		$sDescriptionSectFDB = <<<EOF
<div class="cls_res_info">
	<div class="clr3">{$sDescription}</div>
</div>
<div class="clear_both"></div>
EOF;
		$sDescriptionSect = DesignBoxContent($sDescriptionC, $sDescriptionSectFDB, 1);
		/*$sDescriptionSect = <<<EOF
<div class="disignBoxFirst">
	<div class="boxFirstHeader">
		{$sDescriptionC}
	</div>
	<div class="boxContent">
		<div class="cls_res_info">
			<div class="clr3">{$sDescription}</div>
		</div>
		<div class="clear_both"></div>
	</div>
</div>
EOF;*/

		$sGenUrlP = $this->genUrl($iEventID, $aEventData['EntryUri'], 'part');

		$sParticipantsSectFDB = <<<EOF
{$sParticipants}
<div class="clear_both"></div>
<div class="padds" style="height:23px;vertical-align:middle;">
	<span style="vertical-align: middle;">
		<img src="{$site['icons']}grs.gif" alt="" title="" class="marg_icon" />
	</span>
	<span>
		<a class="actions" href="{$sGenUrlP}">
			{$sListOfParticipantsC}
		</a>
	</span>
</div>
EOF;
		$sParticipantsSect = DesignBoxContent($sParticipantsC, $sParticipantsSectFDB, 1);
		/*$sParticipantsSect = <<<EOF
<div class="disignBoxFirst">
	<div class="boxFirstHeader">
		{$sParticipantsC}
	</div>
	<div class="boxContent">
		{$sParticipants}
		<div class="clear_both"></div>
		<div class="padds" style="height:23px;vertical-align:middle;">
			<span style="vertical-align: middle;">
				<img src="{$site['icons']}grs.gif" alt="" title="" class="marg_icon" />
			</span>
			<span>
				<a class="actions" href="{$sGenUrlP}">
					{$sListOfParticipantsC}
				</a>
			</span>
		</div>
	</div>
</div>
EOF;*/

		$sTagsSectFDB = <<<EOF
{$sTagsVals}
<div class="clear_both"></div>
EOF;
		$sTagsSect = ($sTagsVals=='') ? '' : DesignBoxContent("<div class=\"cls_res_thumb\">{$sTagsC}</div>", $sTagsSectFDB, 1);
		/*$sTagsSect = <<<EOF
<div class="disignBoxFirst">
	<div class="boxFirstHeader">
		<div class="cls_res_thumb">
			{$sTagsC}
		</div>
	</div>
	<div class="boxContent">
		{$sTagsVals}
		<div class="clear_both"></div>
	</div>
</div>
EOF;*/

		$sGenUrlJ = $this->genUrl($iEventID, $aEventData['EntryUri']);

		$sRetHtml .= <<<EOF
<form id="JoinEventForm" action="{$sGenUrlJ}" method="post">
	<input type="hidden" name="join_event" value="on" />
</form>
<form id="UnsubscribeEventForm" action="{$sGenUrlJ}" method="post">
	<input type="hidden" name="unsubscribe_event" value="on" />
</form>
<!--{$sBreadCrumbs}-->
{$sErrElems}
<div>
	<div class="clear_both"></div>
	<div class="cls_info_left">
		{$sImageSect}
		{$sActionsSect}
	</div>
	<div class="cls_info">
		{$sSubjectSect}
		{$sDescriptionSect}
		{$sStatusSect}
		{$sParticipantsSect}
		{$sTagsSect}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;

		return $sRetHtml;
	}

	/**
	 * page show participants function
	 * @return HTML presentation of data
	 */
	function PageSDatingShowParticipants() {
		global $site;
		global $oTemplConfig;

		$sYOC = _t('_y/o');

		$sRetHtml = '';
		$sEventParticipantsC = _t('_Event participants');
		$sListOfParticipantsC = _t('_List').' '._t('_of').' '._t('_Participants');

		// collect information about current member
		if( $logged['member'] ) {
			$aMember['ID'] = (int)$_COOKIE['memberID'];
			$aMemberData = getProfileInfo( $aMember['ID'] );
		} else
			$aMember['ID'] = 0;

		$aMembership = getMemberMembershipInfo( $aMember['ID'] );

		/*$iEventID = (int)$_REQUEST['event_id'];*/
		$iEventID = ($this->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `SDatingEvents` WHERE `EntryUri`='" . $this->process_html_db_input($_REQUEST['eventUri']) . "' LIMIT 1") : (int)$_REQUEST['event_id'];

		$sQuery = "
			SELECT `ID`, `Title`,
				(NOW() > `EventEnd` AND NOW() < DATE_ADD(`EventEnd`, INTERVAL `ChoosePeriod` DAY)) AS `ChooseActive`
			FROM `SDatingEvents`
			WHERE
				`ID` = {$iEventID} AND `Status` = 'Active' AND `AllowViewParticipants` = 1";
		$aEventData = db_arr( $sQuery );
		if ( !$aEventData['ID'] )
			return DesignBoxContent( '', '<center>'. _t('_Event is unavailable') .'</center>', $oTemplConfig -> PageSDatingShowParticipants_db_num );

		$sRetHtml .= '<div class="text">'.process_line_output($aEventData['Title']).'</div>';

		// list of participants
		$aSelfPart = db_arr( "SELECT `ID` FROM `SDatingParticipants`
									WHERE `IDEvent` = $iEventID
									AND `IDMember` = {$aMember['ID']}" );
		$iPartPage = (isset($_REQUEST['part_page'])) ? (int)$_REQUEST['part_page'] : 1;
		$iPartPPerPage = (isset($_REQUEST['part_p_per_page'])) ? (int)$_REQUEST['part_p_per_page'] : 30;
		$iLimitFirst = (int)($iPartPage - 1) * $iPartPPerPage;
		$vPartProfilesRes = db_res( "SELECT `Profiles`.*, `SDatingParticipants`.`ParticipantUID` AS `UID`
										FROM `SDatingParticipants`
										INNER JOIN `Profiles` ON `SDatingParticipants`.`IDMember` = `Profiles`.`ID`
										WHERE `SDatingParticipants`.`IDEvent` = $iEventID
										ORDER BY `Profiles`.`NickName`
										LIMIT $iLimitFirst, $iPartPPerPage" );
		$aTotal = db_arr( "SELECT COUNT(*) FROM `SDatingParticipants`
									WHERE `SDatingParticipants`.`IDEvent` = $iEventID" );

		$iPartProfilesTotal = (int)$aTotal[0];
		$iPagesNum = ceil( $iPartProfilesTotal / $iPartPPerPage );
		$sGenUrlP = $this->genUrl($iEventID, '', 'part', true);
		$sPartGetUrl = $sGenUrlP . (isset($_REQUEST['part_p_per_page']) ? '&amp;part_p_per_page='. (int)$_REQUEST['part_p_per_page'] : '');

		if ( $iPartProfilesTotal == 0 ) {
			$sRetHtml .= _t('_There are no participants for this event');
		} else {
			if ( $iPagesNum > 1 ) {
				$sRetHtml .= '<div class="text">'._t('_Pages').':&nbsp;';

				for ( $i = 1; $i <= $iPagesNum; $i++ ) {
					if ( $i == $iPartPage )
						$sRetHtml .= "[{$i}]&nbsp;";
					else
						$sRetHtml .= "<a href=\"{$sPartGetUrl}&amp;part_page={$i}\">{$i}</a>&nbsp;";
				}
				$sRetHtml .= '</div><br />';
			}

			$sProfilesTrs = '';
			while ( $part_profiles_arr = mysql_fetch_assoc($vPartProfilesRes) ) {
				$iUserIsOnline = get_user_online_status($part_profiles_arr[ID]);
				$sCont = get_member_thumbnail($part_profiles_arr['ID'], 'none' ) . '<br /><center>' . getProfileOnlineStatus( $iUserIsOnline ) . '</center>';
				//$sProfilesTrs .= DesignBoxContentBorder( process_line_output( strmaxtextlen( $part_profiles_arr['NickName'], 11 ) ) . ': ' . age( $part_profiles_arr['DateOfBirth'] ) . ' ' . _t('_y/o'), $sCont );
				$sThumb = get_member_thumbnail($part_profiles_arr['ID'], 'none' );
				$sProfLink = getProfileLink($part_profiles_arr['ID']);
				$sAge = age( $part_profiles_arr['DateOfBirth'] ). $sYOC;
				$sProfilesTrs .= <<<EOF
<div style="float:left;text-align:center;margin-right:10px;position:relative;">
	{$sThumb}
	<div class="browse_nick" style="width:{$this->iThumbSize}px;">
		<a href="{$sProfLink}">{$part_profiles_arr['NickName']}</a>: {$sAge}
	</div>
</div>
EOF;
			}

			$sNicknameC = _t('_Nickname');
			$sDateOfBirthC = _t('_DateOfBirth');
			$sSexC = _t('_Sex');
			$sEventUIDC = _t('_Event UID');

			$sChooseParts = '';
			// show 'choose participants' link only during choose period and if member is participant of this event
			// if ( $this->bAdminMode==FALSE || ($aEventData['ChooseActive'] && $aSelfPart['ID'] )) {
				// $sChooseParts = '<div class="text" align="center"><a href="'.$_SERVER['PHP_SELF'].'?action=select_match&amp;event_id='.$iEventID.'">'._t('_Choose participants you liked').'</a></div><br />';
			// }

			$sPagesHref = '';
			if ( $iPagesNum > 1 ) {
				$sPagesHref .= '<div class="text">'._t('_Pages').':&nbsp;';
				for ( $i = 1; $i <= $iPagesNum; $i++ ) {
					if ( $i == $iPartPage )
						$sPagesHref .= "[{$i}]&nbsp;";
					else
						$sPagesHref .= "<a href=\"{$sPartGetUrl}&amp;part_page={$i}\">{$i}</a>&nbsp;";
				}
				$sPagesHref .= '</div><br />';
			}

			$sRetHtml .= $sProfilesTrs . $sPagesHref;
		}
		$sRetHtml .= '<div class="clear_both"></div>';

		return DesignBoxContent( $sListOfParticipantsC, $sRetHtml, $oTemplConfig -> PageSDatingShowParticipants_db_num );
	}

	/**
	 * page show filer form function
	 * @return HTML presentation of data
	 */
	function PageSDatingShowForm() {
		global $aPreValues;
		global $oTemplConfig;
		global $enable_event_creating;
		global $logged;

		$sShowCalendarC = _t('_Show calendar');
		$sAddNewEventC = _t('_Add new event');
		$sShowAllEventsC = _t('_Show all events');
		$sShowEventsByCountryC = _t('_Show events by country');
		$sShowC = _t('_Show');

		// collect information about current member
		if( $logged['member'] ) {
			$aMember['ID'] = (int)$_COOKIE['memberID'];
			$aMemberData = getProfileInfo( $aMember['ID'] );
		}
		
		$aShow = array();
		$sCountryDisabled = 'disabled="disabled"';

		if ( $_REQUEST['show_events'] == 'country' ) {
			$aShow['country'] = process_pass_data($_REQUEST['show_events_country']);
			$sCountryDisabled = '';
		}

		$sBlockForCalendarAndEventDiv = '';
		if( $oTemplConfig -> customize['events']['showTopButtons'] ) {
			$sBlockForCalendarAndEventDiv .= <<<EOF
<div align="center" class="blockForCalendarAndEvent">
	<a href="{$_SERVER['PHP_SELF']}?action=calendar">{$sShowCalendarC}</a>
EOF;
			if( $enable_event_creating and $logged['member'] ) {
				$sBlockForCalendarAndEventDiv .= "| <a href=\"{$_SERVER['PHP_SELF']}?action=new\">{$sAddNewEventC}</a>";
			}
			$sBlockForCalendarAndEventDiv .= '</div>';
		}

		$sShowEventsChk = ($_REQUEST['show_events'] == 'all') ? 'checked="checked"' : '';
		$sCountryChk = ($_REQUEST['show_events'] == 'country') ? 'checked="checked"' : '';

		$sCountryOptions = '';
		$sSelCountry = isset($aShow['country']) ? $aShow['country'] : $aMemberData['Country'];
		foreach ( $aPreValues['Country'] as $key => $value ) {
			$sCountrySelected = ( $sSelCountry == $key ) ? 'selected="selected"' : '';
			$sCountryOptions .= "<option value=\"{$key}\" ". $sCountrySelected ." >". _t($value['LKey']) ."</option>";
		}

	$sRetHtml = <<<EOF
<center>
	<script language="JavaScript" type="text/javascript">
	<!--
		function updateShowControls()
		{
			document.getElementById('show_events_select_id').disabled = !(document.getElementById('show_events_country_id').checked);
		}
	-->
	</script>
	{$sBlockForCalendarAndEventDiv}
	<form id="showEventsForm" action="{$_SERVER['PHP_SELF']}" method="get">
		<table cellpadding="4" cellspacing="0" border="0" class="text" width="600">
			<tr>
				<td align="left" colspan="2" class="text">
					<input type="radio" name="show_events" id="show_events_all_id" value="all" style="vertical-align: middle;" onClick="javascript: updateShowControls();" {$sShowEventsChk} />
					&nbsp;<label for="show_events_all_id">{$sShowAllEventsC}</label>
				</td>
			</tr>
			<tr>
				<td align="left" width="300" class="text">
					<input type="radio" name="show_events" id="show_events_country_id" value="country" style="vertical-align: middle;" onClick="javascript: updateShowControls();"  {$sCountryChk} />
					&nbsp;<label for="show_events_country_id">{$sShowEventsByCountryC}</label>
				</td>
				<td align="left" class="text">
					<select class="no" name="show_events_country" id="show_events_select_id" {$sCountryDisabled} >{$sCountryOptions}</select>
				</td>
			</tr>
		</table>
		<br />
		<input type="submit" class="no" value="{$sShowC}" />
		<input type="hidden" name="action" value="show" />
		<input type="hidden" name="from" value="0" />
	</form>
</center>
EOF;

		return DesignBoxContent( _t('_Select events to show'), $sRetHtml, $oTemplConfig -> PageSDatingShowForm_db_num );
	}

	/**
	 * page show filer form function
	 * @return HTML presentation of data
	 */
	function PageSDatingCalendar() {
		global $dir;
		global $site;
		global $sdatingThumbWidth;
		global $sdatingThumbHeight;
		global $aPreValues;
		global $oTemplConfig;

		$iPicSize = $this->iIconSize + 15;

		// collect information about current member
		$aMember['ID'] = (int)$_COOKIE['memberID'];
		$aMemberData = getProfileInfo( $aMember['ID'] );
		$sMemberSex = $aMemberData['Sex'];
		$aMembership = getMemberMembershipInfo( $aMember['ID'] );

		// now year, month and day
		list($iNowYear, $iNowMonth, $iNowDay) = explode( '-', date('Y-m-d') );
		// current year, month, month name, day, days in month
		if ( isset($_REQUEST['month']) ) {
			list($iCurMonth, $iCurYear) = explode( '-', $_REQUEST['month'] );
			$iCurMonth = (int)$iCurMonth;
			$iCurYear = (int)$iCurYear;
		}
		else {
			list($iCurMonth, $iCurYear) = explode( '-', date('n-Y') );
		}
		list($sCurMonthName, $iCurDaysInMonth) = explode( '-', date('F-t', mktime( 0, 0, 0, $iCurMonth, $iNowDay, $iCurYear )) );
		// previous month year, month
		$iPrevYear = $iCurYear;
		$iPrevMonth = $iCurMonth - 1;
		if ( $iPrevMonth <= 0 ) {
			$iPrevMonth = 12;
			$iPrevYear--;
		}
		// next month year, month
		$iNextYear = $iCurYear;
		$iNextMonth = $iCurMonth + 1;
		if ( $iNextMonth > 12 ) {
			$iNextMonth = 1;
			$iNextYear++;
		}
		// days in previous month
		$iPrevDaysInMonth = (int)date( 't', mktime( 0, 0, 0, $iPrevMonth, $iNowDay, $iPrevYear ) );
		// days-of-week of first day in current month
		$iFirstDayDow = (int)date( 'w', mktime( 0, 0, 0, $iCurMonth, 1, $iCurYear ) );
		// from which day of previous month calendar starts
		$iPrevShowFrom = $iPrevDaysInMonth - $iFirstDayDow + 1;

		// select events array
		$aCalendarEvents = array();
		$sCountryFilter = 'all';
		$sRCalendarCountry = isset($_REQUEST['calendar_country']) ? $_REQUEST['calendar_country'] : $aMemberData['Country'];
		$sRCalendarCountry = ($sRCalendarCountry=='') ? 'all' : $sRCalendarCountry ;
		if ( $sRCalendarCountry == 'all' ) {
			$sCountryFilter = '';
		}
		else {
			$sCountryFilter = 'AND `Country` = \''. process_db_input($sRCalendarCountry) . '\'';
		}

		//old WHERE data`s
		/*
		AND FIND_IN_SET('{$sMemberSex}', `EventSexFilter`)
		AND ( TO_DAYS('{$aMemberData['DateOfBirth']}')
		BETWEEN TO_DAYS(DATE_SUB(NOW(), INTERVAL `EventAgeUpperFilter` YEAR))
		AND TO_DAYS(DATE_SUB(NOW(), INTERVAL `EventAgeLowerFilter` YEAR)) )
		AND ( INSTR(`EventMembershipFilter`, '\'all\'') OR INSTR(`EventMembershipFilter`, '\'{$aMembership['ID']}\'') )
		*/
		$sRequest = "SELECT `ID`, `Title`, `PhotoFilename`, DAYOFMONTH(`EventStart`) AS `EventDay`, MONTH(`EventStart`) AS `EventMonth` FROM `SDatingEvents`
							WHERE ( MONTH(`EventStart`) = {$iCurMonth} AND YEAR(`EventStart`) = {$iCurYear} OR
									MONTH( DATE_ADD(`EventStart`, INTERVAL 1 MONTH) ) = {$iCurMonth} AND YEAR( DATE_ADD(`EventStart`, INTERVAL 1 MONTH) ) = {$iCurYear} OR
									MONTH( DATE_SUB(`EventStart`, INTERVAL 1 MONTH) ) = {$iCurMonth} AND YEAR( DATE_SUB(`EventStart`, INTERVAL 1 MONTH) ) = {$iCurYear} )
							{$sCountryFilter}
							AND `Status` = 'Active'
							";

		$vEventsRes = db_res( $sRequest );
		while ( $aEventData = mysql_fetch_assoc($vEventsRes) ) {
			$aCalendarEvents["{$aEventData['EventDay']}-{$aEventData['EventMonth']}"][$aEventData['ID']]['Title'] = $aEventData['Title'];
			$aCalendarEvents["{$aEventData['EventDay']}-{$aEventData['EventMonth']}"][$aEventData['ID']]['PhotoFilename'] = $aEventData['PhotoFilename'];
		}

		// make calendar grid
		$bPreviousMonth = ($iFirstDayDow > 0 ? true : false);
		$bNextMonth = false;
		$iCurrentDay = ($bPreviousMonth) ? $iPrevShowFrom : 1;

		for ($i = 0; $i < 6; $i++) {
			for ($j = 0; $j < 7; $j++) {
				$aCalendarGrid[$i][$j]['day'] = $iCurrentDay;
				$aCalendarGrid[$i][$j]['month'] = ($bPreviousMonth ? $iPrevMonth : ($bNextMonth ? $iNextMonth : $iCurMonth));
				$aCalendarGrid[$i][$j]['current'] = (!$bPreviousMonth && !$bNextMonth);
				$aCalendarGrid[$i][$j]['today'] = ($iNowYear == $iCurYear && $iNowMonth == $iCurMonth && $iNowDay == $iCurrentDay && !$bPreviousMonth && !$bNextMonth);
				// make day increment
				$iCurrentDay++;
				if ( $bPreviousMonth && $iCurrentDay > $iPrevDaysInMonth ) {
					$bPreviousMonth = false;
					$iCurrentDay = 1;
				}
				if ( !$bPreviousMonth && !$bNextMonth && $iCurrentDay > $iCurDaysInMonth ) {
					$bNextMonth = true;
					$iCurrentDay = 1;
				}
			}
		}

		$sShowEventsByCountryC = _t('_Show events by country');
		$sAllC = _t('_All');
		$sPrevC = _t('_Prev');
		$sNextC = _t('_Next');
		$sSundaySC = _t('_Sunday_short');
		$sMondaySC = _t('_Monday_short');
		$sTuesdaySC = _t('_Tuesday_short');
		$sWednesdaySC = _t('_Wednesday_short');
		$sThursdaySC = _t('_Thursday_short');
		$sFridaySC = _t('_Friday_short');
		$sSaturdaySC = _t('_Saturday_short');
		$sNoPhotoC = _t('_No photo');
		$sCalendarC = _t('_Calendar');

		$sCalendarOptions = '';
		$sCalSel = ( $sRCalendarCountry == 'all' ) ? 'selected="selected"' : '';
		$sCalendarOptions .= '<option value="all" '. $sCalSel ." >{$sAllC}</option>";
		foreach ( $aPreValues['Country'] as $key => $value ) {
			$sCalKeySel = ( $sRCalendarCountry == "{$key}" ) ? 'selected="selected"' : '';
			$sCuontryVal = _t($value['LKey']);
			$sCalendarOptions .= "<option value=\"{$key}\" {$sCalKeySel} >{$sCuontryVal}</option>";
		}

		$sCalendarCountry = (isset($_REQUEST['calendar_country'])) ? '&amp;calendar_country='. process_pass_data($_REQUEST['calendar_country']) : '';
		$sCalendarPrevHref = $_SERVER['PHP_SELF']."?action=calendar&amp;month={$iPrevMonth}-{$iPrevYear}".$sCalendarCountry;
		$sCurMonYear = _t('_'.$sCurMonthName) .', '. $iCurYear;
		$sCalendarNextHref = $_SERVER['PHP_SELF']."?action=calendar&amp;month={$iNextMonth}-{$iNextYear}".$sCalendarCountry;

		$sCalTableTrs = '';
		for ($i = 0; $i < 6; $i++) {
			$sCalTableTrs .= '<tr>';
			for ($j = 0; $j < 7; $j++) {
				if ( $aCalendarGrid[$i][$j]['today'] )
					$sCellClass = 'calendar_today';
				elseif ( $aCalendarGrid[$i][$j]['current'] )
					$sCellClass = 'calendar_current';
				else
					$sCellClass = 'calendar_non_current';

				$sCalTableTrs .= <<<EOF
<td style="width:100px;height:100px;" class="{$sCellClass}">{$aCalendarGrid[$i][$j]['day']}
EOF;

				$vDayMonthValue = $aCalendarGrid[$i][$j]['day'] .'-'.  $aCalendarGrid[$i][$j]['month'];
				if ( isset($aCalendarEvents[$vDayMonthValue]) && is_array($aCalendarEvents[$vDayMonthValue]) ) {
					foreach ( $aCalendarEvents[$vDayMonthValue] as $eventID => $eventArr ) {
						$sEventThumbname = getThumbNameByPictureName($eventArr['PhotoFilename'], true);
						$sGenUrl = $this->genUrl($eventID, '', 'entry', true);

						if ( strlen(trim($sEventThumbname)) && file_exists($dir['sdatingImage'] . $sEventThumbname) ) {
							$sCalTableTrs .= <<<EOF
<div>
<a href="{$sGenUrl}">
	<img src="{$site['sdatingImage']}icon_{$eventArr['PhotoFilename']}" border="0" alt="{$eventArr['Title']}" title="{$eventArr['Title']}" style="margin: 2px;" />
</a>
</div>
EOF;
						} else {
							global $tmpl;
							$sSpacerName = $this -> sSpacerPath;
							$sNaname = $site['url'].'templates/tmpl_'.$tmpl.'/'.$this -> sPicNotAvail;
							$sCalTableTrs .= <<<EOF
<!-- <div align="center" class="small" title="{$eventArr['Title']}" style="width: {$sdatingThumbWidth}px; height: {$sdatingThumbHeight}px; vertical-align: middle; line-height: {$sdatingThumbHeight}px; border: 1px solid silver; background-color: #FFFFFF; font-weight: normal; margin: 2px; font-size: 80%; cursor: pointer;"> -->
<div>
	<a href="{$sGenUrl}">
		<img src="{$sSpacerName}" style="width:64px;height:64px; background-image: url({$sNaname});" class="photo1" alt="" />
		<!--<nobr>{$sNoPhotoC}</nobr>-->
	</a>
</div>
EOF;
						}
					}
				}

				$sCalTableTrs .= '</td>';
			}
			$sCalTableTrs .= '</tr>';
		}

		$sRetHtml = <<<EOF
<br />
<div align="center" style="margin-bottom: 10px;">
	<form id="calendarCountryForm" action="{$_SERVER['PHP_SELF']}" method="get" style="margin: 0px;">
		<input type="hidden" name="action" value="calendar" />
		<input type="hidden" name="month" value="{$iCurMonth}-{$iCurYear}" />
		{$sShowEventsByCountryC}&nbsp;
		<select class="no" name="calendar_country" onchange="javascript: document.forms['calendarCountryForm'].submit();" style="vertical-align: middle;">{$sCalendarOptions}</select>
	</form>

	<table cellpadding="1" cellspacing="1" border="0" width="100%" class="text" style="text-align:center;margin-top:10px;">
		<tr>
			<td class="calendar_current" style="padding: 3px;">
				<a href="{$sCalendarPrevHref}">{$sPrevC}</a>
			</td>
			<td colspan="5" class="calendar_current">{$sCurMonYear}</td>
			<td class="calendar_current" style="padding: 3px;">
				<a href="{$sCalendarNextHref}">{$sNextC}</a>
			</td>
		</tr>
		<tr>
			<td style="width:{$iPicSize}px;" class="calendar_non_current">{$sSundaySC}</td>
			<td style="width:{$iPicSize}px;" class="calendar_non_current">{$sMondaySC}</td>
			<td style="width:{$iPicSize}px;" class="calendar_non_current">{$sTuesdaySC}</td>
			<td style="width:{$iPicSize}px;" class="calendar_non_current">{$sWednesdaySC}</td>
			<td style="width:{$iPicSize}px;" class="calendar_non_current">{$sThursdaySC}</td>
			<td style="width:{$iPicSize}px;" class="calendar_non_current">{$sFridaySC}</td>
			<td style="width:{$iPicSize}px;" class="calendar_non_current">{$sSaturdaySC}</td>
		</tr>
	{$sCalTableTrs}
	</table>
</div>
<br />
EOF;

		return DesignBoxContent( $sCalendarC, $sRetHtml, $oTemplConfig -> PageSDatingCalendar_db_num );
}

	function GenerateZapatecCode($sEl1, $sEl2) {
		$iCurYear = date("Y");
		$iCurMonth = date("m");
		$iCurDay = date("d");
		$sRes = <<<EOF
Zapatec.Calendar.setup({
	firstDay          : 1,
	weekNumbers       : true,
	showOthers        : true,
	showsTime         : true,
	timeFormat        : "24",
	step              : 2,
	range             : [{$iCurYear}.{$iCurMonth}, 2099.12],
	electric          : false,
	singleClick       : true,
	inputField        : "{$sEl1}",
	button            : "{$sEl2}",
	ifFormat          : "%Y-%m-%d %H:%M:%S",
	daFormat          : "%Y/%m/%d",
	align             : "Br"
});
EOF;
		return $sRes;
	}

	/**
	 * page show add new event form function
	 * @return HTML presentation of data
	 */
	function PageSDatingNewEventForm($iEventID=0, $arrErr = NULL) {
		$this->CheckLogged();
		global $site;
		global $aPreValues;
		global $new_result_text;
		global $oTemplConfig;
		global $date_format;
		global $logged;

		// collect information about current member
		$aMember['ID'] = (int)$_COOKIE['memberID'];
		$aMemberData = getProfileInfo( $aMember['ID'] );
		$sMemberCountry = ($this->bAdminMode) ? getParam( 'default_country' ) : $aMemberData['Country'];
		$sPleaseFillAllFieldsC = _t('_Please fill up all fields');
		$sTitleC = _t('_Title');
		$sDescriptionC = _t('_Description');
		$sStatusMessageC = _t('_Status message');
		$sCountryC = _t('_Country');
		$sCityC = _t('_City');
		$sPlaceC = _t('_Place');
		$sVenuePhotoC = _t('_Venue photo');
		$sEventStartC = _t('_Event start');
		$sEventEndC = _t('_Event end');
		$sTicketSaleStartC = _t('_Ticket sale start');
		$sTicketSaleEndC = _t('_Ticket sale end');
		$sFemaleTicketCountC = _t('_Female ticket count');
		$sMaleTicketCountC = _t('_Male ticket count');
		//$sSaveChangesC = _t('_Save Changes');
		$sPostEventC = _t('_Post Event');
		$sAddNewEventC = _t('_Add new event');
		$sTagsC = _t('_Tags');
		$sCommitC = _t('_Apply Changes');
		$sChooseC = _t('_Choose');
		$sClearC = _t('_Clear');

		$sEventURL = $_SERVER['PHP_SELF'];

		if ($iEventID>0) {
			$sEventSQL = "SELECT * FROM `SDatingEvents` WHERE `ID` = {$iEventID} LIMIT 1";
			$aEvent = db_arr( $sEventSQL );
			$sEventTitle = $aEvent['Title'];
			$sEventTags = $aEvent['Tags'];
			
			$sEventDesc = $aEvent['Description'];
			$sEventStatusMsg = $aEvent['StatusMessage'];
			$sSelectedCountry = $aEvent['Country'];
			$sSitySrc = $site['flags'].strtolower($sSelectedCountry);
			$sEventCity = $aEvent['City'];
			$sEventPlace = $aEvent['Place'];
			$sEventEnd = $aEvent['TicketSaleEnd'];
			$sEventStart = $aEvent['EventStart'];
			$sFemaleTicketCount = $aEvent['TicketCountFemale'];
			$sMaleTicketCount = $aEvent['TicketCountMale'];
			//$sEventTags = $aEvent['Tags'];


			//$sPostPicture = $aEvent['PostPhoto'];
			//if ($sPostImage != '') 
			//	$sPostPictureTag = '<img src="'.$site['blogImage'].'big_'.$sPostImage.'" style="position:static;" />';
			$sEditedIdElement = '<input type="hidden" name="EditedEventID" id="EditedEventID" value="'.$iEventID.'" />';

			$sPostEventC = $sCommitC;
			$sPostAction = 'event_updated';
			$sAddNewEventC = _t('_Edit event');
			$sANEventAction = "event_save";
			//$sJSIns = "UpdateField('EditEventID','{$iEventID}');";
			//$sEditIdStr = '<input type="hidden" name="EditedEventID" value="'.$iEventID.'">';
			$sEventURL = $this->genUrl($iEventID, $aEvent['EntryUri']);
			//$sEventURL = "?action=show_info&amp;event_id={$iEventID}";
		} else {
			$sEventTitle = isset($_POST['event_title']) ? htmlspecialchars( process_pass_data($_POST['event_title']) ) : '';
			$sEventTags = isset($_POST['event_tags']) ? htmlspecialchars( process_pass_data($_POST['event_tags']) ) : '';
			$sEventDesc = isset($_POST['event_desc']) ? htmlspecialchars( process_pass_data($_POST['event_desc']) ) : '';
			$sEventStatusMsg = isset($_POST['event_statusmsg']) ? htmlspecialchars( process_pass_data($_POST['event_statusmsg']) ) : '';
			$sSelectedCountry = isset($_POST['event_country']) ? $_POST['event_country'] : $sMemberCountry;
			$sSitySrc = $site['flags'].strtolower($sSelectedCountry);
			$sEventCity = isset($_POST['event_city']) ? htmlspecialchars( process_pass_data($_POST['event_city']) ) : '';
			$sEventPlace = isset($_POST['event_place']) ? htmlspecialchars( process_pass_data($_POST['event_place']) ) : '';
			$sEventEnd = isset($_POST['event_end']) ? htmlspecialchars( process_pass_data($_POST['event_end']) ) : strftime( str_replace('%i','%M', $date_format) );
			$sFemaleTicketCount = isset($_POST['event_count_female']) ? htmlspecialchars( process_pass_data($_POST['event_count_female']) ) : '';
			$sMaleTicketCount = isset($_POST['event_count_male']) ? htmlspecialchars( process_pass_data($_POST['event_count_male']) ) : '';
			$sPostAction = 'event_save';
			$sANEventAction = "new";
		}

		$sCountriesOptions = '';
		
		foreach ( $aPreValues['Country'] as $key => $value ) {
			$sSC = ($sSelectedCountry == $key) ? 'selected="selected"' : '';
			$sVal = _t($value['LKey']);
			$sCountriesOptions .= "<option value=\"{$key}\" {$sSC}>{$sVal}</option>";
		}

		$sNewResText = ( strlen($new_result_text) ) ? '<div class="err" style="margin: 10px;"><div>'.$new_result_text.'</div></div>' : '';

		$sJSCode = <<<EOF
<script type="text/javascript">
<!--
	function AutoSelectFlag() {
		var vElem = document.getElementById('event_country_id');
		changeFlag(vElem.value);
	}

	function changeFlag(flagISO)
	{
		flagImage = document.getElementById('flagImageId');
		flagImage.src = '{$site['flags']}' + flagISO.toLowerCase() + '.gif';
	}
//-->
</script>
EOF;

		$sZapatecCalendar = '';
		//if ( $_REQUEST['action'] == 'new' ) {
			$sAdminCalendars = '';
			if ($this->bAdminMode) {
				$sAdminCalendars = $this->GenerateZapatecCode('event_end_id','end_choose_id') .
					$this->GenerateZapatecCode('event_sale_start_id','sale_start_choose_id') .
					$this->GenerateZapatecCode('event_sale_end_id','sale_end_choose_id');
			}
			$sZapatecCalendar = $this->GenerateZapatecCode('event_start_id','start_choose_id') . $sAdminCalendars;
		//}

		////php validating fields
		$sTstyle = ($arrErr['Title'] ? 'block' : 'none');
		$sDstyle = ($arrErr['Description'] ? 'block' : 'none');
		$sSMstyle = ($arrErr['Status message'] ? 'block' : 'none');
		$sCstyle = ($arrErr['City'] ? 'block' : 'none');
		$sPstyle = ($arrErr['Place'] ? 'block' : 'none');
		$sESstyle = ($arrErr['Event start'] ? 'block' : 'none');
		$sEEstyle = ($arrErr['Event end'] ? 'block' : 'none');
		$sTSSstyle = ($arrErr['Ticket Sale Start'] ? 'block' : 'none');
		$sTSEstyle = ($arrErr['Ticket Sale End'] ? 'block' : 'none');
		$sFTCstyle = ($arrErr['Female Ticket Count'] ? 'block' : 'none');
		$sMTCstyle = ($arrErr['Male Ticket Count'] ? 'block' : 'none');
		//error messages
		$sTmsg = ($arrErr['Title'] ? _t( '_'.$arrErr['Title'] ) : '' );
		$sDmsg = ($arrErr['Description'] ? _t( '_'.$arrErr['Description'] ) : '' );
		$sSMmsg = ($arrErr['Status message'] ? _t( '_'.$arrErr['Status message'] ) : '' );
		$sCmsg = ($arrErr['City'] ? _t( '_'.$arrErr['City'] ) : '' );
		$sPmsg = ($arrErr['Place'] ? _t( '_'.$arrErr['Place'] ) : '' );
		$sESmsg = ($arrErr['Event start'] ? _t( '_'.$arrErr['Event start'] ) : '' );
		$sEEmsg = ($arrErr['Event end'] ? _t( '_'.$arrErr['Event end'] ) : '' );
		$sTSSmsg = ($arrErr['Ticket Sale Start'] ? _t( '_'.$arrErr['Ticket Sale Start'] ) : '' );
		$sTSEmsg = ($arrErr['Ticket Sale End'] ? _t( '_'.$arrErr['Ticket Sale End'] ) : '' );
		$sFTCmsg = ($arrErr['Female Ticket Count'] ? _t( '_'.$arrErr['Female Ticket Count'] ) : '' );
		$sMTCmsg = ($arrErr['Male Ticket Count'] ? _t( '_'.$arrErr['Male Ticket Count'] ) : '' );


		$sAdminSalesPart='';
		$sStatusMess='';
		if ($this->bAdminMode) {
			$sStatusMess = <<<EOF
<tr class="vc">
	<td class="form_label">{$sStatusMessageC}:</td>
	<td class="form_value">
		<div class="edit_error" style="display:{$sSMstyle}">
			{$sSMmsg}
		</div>
		<input class="form_input" type="text" name="event_statusmsg" id="event_statusmsg_id" value="{$sEventStatusMsg}" />
	</td>
</tr>
EOF;

			$sAdminSalesPart = <<<EOF
<tr class="vc">
	<td class="form_label">{$sEventEndC}:</td>
	<td class="form_value">
		<!-- <input type="text" class="no" name="event_end" id="event_end_id" size="20" value="{$sEventEnd}" />-->
		<div class="edit_error" style="display:{$sEEstyle}">
			{$sEEmsg}
		</div>
		<input type="text" class="form_input_date" name="event_end" id="event_end_id" value="" />
		<input type="button" id="end_choose_id" value="{$sChooseC}" />
		<input type="button" id="end_clear_id" onClick="document.getElementById('event_end_id').value = ''; " value="{$sClearC}" />
	</td>
</tr>
<tr class="vc">
	<td class="form_label">{$sTicketSaleStartC}:</td>
	<td class="form_value">
		<div class="edit_error" style="display:{$sTSSstyle}">
			{$sTSSmsg}
		</div>
		<input type="text" class="form_input_date" name="event_sale_start" id="event_sale_start_id" value="" />
		<input type="button" id="sale_start_choose_id" value="{$sChooseC}" />
		<input type="button" id="sale_start_clear_id" onClick="document.getElementById('event_sale_start_id').value = ''; " value="{$sClearC}" />
	</td>
</tr>
<tr class="vc">
	<td class="form_label">{$sTicketSaleEndC}:</td>
	<td class="form_value">
		<div class="edit_error" style="display:{$sTSEstyle}">
			{$sTSEmsg}
		</div>
		<input type="text" class="form_input_date" name="event_sale_end" id="event_sale_end_id" value="" />
		<input type="button" id="sale_end_choose_id" value="{$sChooseC}" />
		<input type="button" id="sale_end_clear_id" onClick="document.getElementById('event_sale_end_id').value = ''; " value="{$sClearC}" />
	</td>
</tr>
<tr class="vc">
	<td class="form_label">{$sFemaleTicketCountC}:</td>
	<td class="form_value">
		<div class="edit_error" style="display:{$sFTCstyle}">
			{$sFTCmsg}
		</div>
		<input type="text" class="form_input_count" name="event_count_female" id="event_count_female_id" value="{$sFemaleTicketCount}" />
	</td>
</tr>
<tr class="vc">
	<td class="form_label">{$sMaleTicketCountC}:</td>
	<td class="form_value">
		<div class="edit_error" style="display:{$sMTCstyle}">
			{$sMTCmsg}
		</div>
		<input type="text" class="form_input_count" name="event_count_male" id="event_count_male_id" value="{$sMaleTicketCount}" />
	</td>
</tr>
EOF;
		}

		$sRetHtml = <<<EOF
{$sJSCode}
{$sNewResText}
<form id="newEventForm" action="{$sEventURL}" method="post" enctype="multipart/form-data">
<table class="addEventForm">
	<tr class="vc">
		<td class="form_label">{$sTitleC}:</td>
		<td class="form_value">
			<div class="edit_error" style="display:{$sTstyle}">
				{$sTmsg}
			</div>
			<input class="form_input" type="text" name="event_title" id="event_title_id" value="{$sEventTitle}" />
		</td>
	</tr>
	<tr class="vc">
		<td class="form_label">{$sTagsC}:</td>
		<td class="form_value">
			<input class="form_input" type="text" name="event_tags" value="{$sEventTags}" />
		</td>
	</tr>
	<tr class="vc">
		<td class="form_label">{$sDescriptionC}:</td>
		<td class="form_value">
			<div class="edit_error" style="display:{$sDstyle}">
				{$sDmsg}
			</div>
			<textarea class="classfiedsTextArea" name="event_desc" id="event_desc_id" style="width:700px;height:400px;">{$sEventDesc}</textarea>
		</td>
	</tr>
	{$sStatusMess}
	<tr class="vc">
		<td class="form_label">{$sCountryC}:</td>
		<td class="form_value">
			<select class="form_select" name="event_country" id="event_country_id" onchange="javascript: changeFlag(this.value);">
				{$sCountriesOptions}
			</select>
			<img id="flagImageId" src="{$sSitySrc}.gif" alt="flag" />
		</td>
	</tr>
	<tr class="vc">
		<td class="form_label">{$sCityC}:</td>
		<td align="left">
			<div class="edit_error" style="display:{$sCstyle}">
				{$sCmsg}
			</div>
			<input type="text" class="form_input" name="event_city" id="event_city_id" value="{$sEventCity}" />
		</td>
	</tr>
	<tr class="vc">
		<td class="form_label">{$sPlaceC}:</td>
		<td class="form_value">
			<div class="edit_error" style="display:{$sPstyle}">
				{$sPmsg}
			</div>
			<input type="text" class="form_input" name="event_place" id="event_place_id" value="{$sEventPlace}" />
		</td>
	</tr>
	<tr class="vc">
		<td class="form_label">{$sVenuePhotoC}:</td>
		<td class="form_value">
			<input type="file" class="form_file" name="event_photo" id="event_photo_id" />
		</td>
	</tr>
	<tr class="vc">
		<td class="form_label">{$sEventStartC}:</td>
		<td class="form_value">
			<div class="edit_error" style="display:{$sESstyle}">
				{$sESmsg}
			</div>
			<input type="text" class="form_input_date" name="event_start" id="event_start_id" value="{$sEventStart}" readonly="readonly" />
			<input type="button" id="start_choose_id" value="{$sChooseC}" />
			<input type="button" id="start_clear_id" onClick="document.getElementById('event_start_id').value = ''; " value="{$sClearC}" />
		</td>
	</tr>
	{$sAdminSalesPart}
	<tr class="vc">
		<td class="form_colspan" colspan="2">
			<input type="hidden" name="action" value="{$sANEventAction}" />
			{$sEditedIdElement}
			<input type="submit" class="form_submit" name="{$sPostAction}" value="{$sPostEventC}"
			  style="vertical-align: middle;margin-top:10px;" />
		</td>
	</tr>
</table>
</form>
<!-- Loading Calendar JavaScript files -->
    <script type="text/javascript" src="{$site['plugins']}calendar/calendar_src/utils.js"></script>
    <script type="text/javascript" src="{$site['plugins']}calendar/calendar_src/calendar.js"></script>
    <script type="text/javascript" src="{$site['plugins']}calendar/calendar_src/calendar-setup.js"></script>

<!-- Loading language definition file -->
    <script type="text/javascript" src="{$site['plugins']}calendar/calendar_lang/calendar-en.js"></script>

<script type="text/javascript">
//<![CDATA[
	{$sZapatecCalendar}
//]]>
onload=AutoSelectFlag();
</script>
EOF;

		return DesignBoxContent( $sAddNewEventC, $sRetHtml, $oTemplConfig -> PageSDatingNewEvent_db_num );
	}

	function ShowSearchResult() {
		$sRetHtml = '';
		$sSearchedTag = process_db_input( $_REQUEST['tagKey'] );
		//$iMemberID = $_REQUEST['ownerID'];
		global $site;
		global $aPreValues;
		global $tmpl;
		global $dir;

		$date_format_php = getParam('php_date_format');
		$sTagsC = _t('_Tags');
		$sTagC = _t('_Tag');
		$sShowInfoC = _t('_Show info');
		$sParticipantsC = _t('_Participants');
		$sStatusMessageC = _t('_Status message');
		$sDateC = _t('_Date');
		$sPlaceC = _t('_Place');
		$sDescriptionC = _t('_Description');
		$sTitleC = _t('_Title');
		$sActionsC = _t('_Actions');
		$sListOfParticipantsC = _t('_List').' '._t('_of').' '._t('_Participants');

		$sSpacerName = $this -> sSpacerPath;

		//$sCategoryAddon = ($iCategoryID>0) ? "AND `BlogPosts`.`CategoryID` = {$iCategoryID}" : '';
		$sBlogPosts = '';
		$sEventSQL = "SELECT * FROM `SDatingEvents`";
		$vBlogPosts = db_res( $sEventSQL );
		while ( $aResSQL = mysql_fetch_assoc($vBlogPosts) ) {
			$sDateTime = date( $date_format_php, strtotime( $aResSQL['EventStart'] ) );

			$sCountry = ($aResSQL['Country']!='') ? _t($aPreValues['Country'][$aResSQL['Country']]['LKey']) : '';
			$sCity = ($aResSQL['City']!='') ? ', '.process_line_output($aResSQL['City']) : '';
			$sPlace = ($aResSQL['Place']!='') ? ', '.process_line_output($aResSQL['Place']) : '';
			$sDescription = /*process_text_withlinks_output*/($aResSQL['Description']);

			$sImgEL = ( strlen(trim($aResSQL['PhotoFilename'])) && file_exists($dir['sdatingImage'] . $aResSQL['PhotoFilename']) )
				? "<img class=\"photo1\" alt=\"\" style=\"width:{$this->iThumbSize}px;height:{$this->iThumbSize}px;background-image:url({$site['sdatingImage']}thumb_{$aResSQL['PhotoFilename']});\" src=\"{$sSpacerName}\" />"
				: "<img class=\"photo1\" alt=\"\" style=\"width:{$this->iThumbSize}px;height:{$this->iThumbSize}px;background-image:url({$site['url']}templates/tmpl_{$tmpl}/{$this->sPicNotAvail});\" src=\"{$sSpacerName}\" />";

			$sTagsCommas = $aResSQL['Tags'];
			$aTags = split(',', $sTagsCommas);

			$sTagsHrefs = '';
			foreach( $aTags as $sTagKey ) {
				$sTagHrefGen = $this->genUrl(0, $sTagKey, 'search');
				$sTagsHrefs .= <<<EOF
<a href="{$sTagHrefGen}" >{$sTagKey}</a>&nbsp;
EOF;
			}

			$sGenUrl = $this->genUrl($aResSQL['ID'], $aResSQL['EntryUri']);

			if (in_array($sSearchedTag,$aTags)) {
				$sBlogPosts .= <<<EOF
<div class="cls_result_row">
	<div class="thumbnail_block" style="float:left;">
		<a href="{$sGenUrl}">
			{$sImgEL}
		</a>
	</div>
	<div class="cls_res_info_nowidth" {$sDataStyleWidth}>
		<div class="cls_res_info_p">
			<a class="actions" href="{$sGenUrl}">{$aResSQL['Title']}</a>
		</div>
		<div class="cls_res_info_p">
			<!-- <span style="vertical-align:middle;">
				<img src="{$site['icons']}tag_small.png" class="marg_icon" alt="" />
			</span>-->{$sTagsC}:&nbsp;{$sTagsHrefs}
		</div>
		<!-- <div class="cls_res_info_p">
			{$sStatusMessageC}: <div class="clr3">{$sStatusMessage}</div>
		</div> -->
		<div class="cls_res_info_p">
			{$sDateC}: <div class="clr3">{$sDateTime}</div>
		</div>
		<div class="cls_res_info_p">
			{$sPlaceC}: <div class="clr3">{$sCountry}{$sCity}{$sPlace}</div>
		</div>
		<div class="cls_res_info_p">
			{$sDescriptionC}: <div class="clr3">{$sDescription}</div>
		</div>
		<div class="cls_res_info_p">
			{$sViewParticipants}
		</div>
	</div>
	<div class="clear_both"></div>
</div>
EOF;
			}
		}

		$sBlogPosts = ($sBlogPosts == '') ? MsgBox(_t( '_Sorry, nothing found' )) : $sBlogPosts;
		return $this->DecorateAsTable($sTagC.' - '.$sSearchedTag, $sBlogPosts);
	}

	/**
	 * SQL Get all Profiles data by Profile Id
	 *
	  * @param $iProfileId
	 * @return SQL data
	 */
	function GetProfileData($iProfileId) {
		return getProfileInfo( $iProfileId );
	}

	function GetEventPicture($iEventID, $sEventPicName='DOLPHIN') {
		global $dir;
		global $site;
		global $tmpl;
		$sSpacerName = $this -> sSpacerPath;
		if ($sEventPicName=='DOLPHIN') {
			$sRequest = "SELECT `PhotoFilename`,`Title` FROM `SDatingEvents` WHERE `ID` = {$iEventID} LIMIT 1";
			$aResPic = db_arr($sRequest);
			$sEventPicName = $aResPic['PhotoFilename'];
			$this->iThumbSize = 45;
			$sAlt = $aResPic['Title'];
			$sTypePic = "icon_";
		} else {
			$sTypePic = "thumb_";
			$sAlt = $sEventPicName;
		}

		$sGenUrl = $this->genUrl($iEventID, '', 'entry', true);

		$sEventPicName = ( strlen(trim($sEventPicName)) && file_exists($dir['sdatingImage'] . $sEventPicName) )
				? "<img class=\"photo1\" style=\"width:{$this->iThumbSize}px;height:{$this->iThumbSize}px;background-image:url({$site['sdatingImage']}{$sTypePic}{$sEventPicName});\" src=\"{$sSpacerName}\" alt=\"{$sAlt}\" />"
				: "<img class=\"photo1\" style=\"width:{$this->iThumbSize}px;height:{$this->iThumbSize}px;background-image:url({$site['url']}templates/tmpl_{$tmpl}/{$this->sPicNotAvail});\" src=\"{$sSpacerName}\" alt=\"{$sAlt}\" />";
		$sEventPic = <<<EOF
<div  class="thumbnail_block" style="float:left;">
	<a href="{$sGenUrl}">
		{$sEventPicName}
	</a>
</div>
EOF;
		return $sEventPic;
	}

	function genUrl($iEntryId, $sEntryUri, $sType='entry', $bForce = false) { //sType - entry / part/search
		global $site;

		if ($bForce) {
			$sEntryUri = db_value("SELECT `EntryUri` FROM `SDatingEvents` WHERE `ID`='{$iEntryId}' LIMIT 1");
		}

		$sMainUrl = $site['url'];

		if ($this->bUseFriendlyLinks) {
			$sUrl = $sMainUrl."events/{$sType}/{$sEntryUri}";
		} else {
			$sUrl = '';
			switch ($sType) {
				case 'entry':
					$sUrl = "{$sMainUrl}events.php?action=show_info&amp;event_id={$iEntryId}";
					break;
				case 'part':
					$sUrl = "{$sMainUrl}events.php?action=show_part&amp;event_id={$iEntryId}";
					break;
				case 'search':
					$sUrl = "{$sMainUrl}events.php?action=search_by_tag&amp;tagKey={$sEntryUri}";
					break;
			}
		}
		return $sUrl;
	}

	function GenAnyBlockContent($sOrder='last', $iProfileID=0, $sLimit="LIMIT 5" ) {
		global $site;
		global $short_date_format;

		$php_date_format = getParam( 'php_date_format' );
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$sClockIcon = getTemplateIcon( 'clock.gif' );

		$sOrderS = '';
		switch ($sOrder) {
			case 'last':
				$sOrderS = "ORDER BY `EventStart` DESC";
				break;
			case 'latest':
				$sOrderS = "ORDER BY `EventStart` DESC";
				break;
			case 'rand':
				$sOrderS = "ORDER BY RAND()";
				break;
			case 'first':
				$sOrderS = "ORDER BY `EventStart` ASC";
				break;
		}
		$sProfileS = ($iProfileID>0) ? "(`SDatingEvents`.`ResponsibleID` = '{$iProfileID}' OR `SDatingParticipants`.`IDMember` = '{$iProfileID}')" : '1';

		$sQuery = "
			SELECT DISTINCT `SDatingEvents`. * , `Profiles`.`NickName` ,
			UNIX_TIMESTAMP( `SDatingEvents`.`EventStart` ) AS `DateTime_f` 
			FROM `SDatingEvents` 
			INNER JOIN `Profiles` ON `Profiles`.`ID` = `SDatingEvents`.`ResponsibleID` 
			LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID` 
			WHERE {$sProfileS}
			AND `SDatingEvents`.`Status` = 'Active'
			{$sOrderS}
			{$sLimit}
		";

		$rBlogs = db_res( $sQuery );

		if( !mysql_num_rows( $rBlogs ) )
			return MsgBox(_t('_Sorry, nothing found'));

		$sBlocks = '';
		while( $aBlog = mysql_fetch_assoc( $rBlogs ) ) {
			$sPic = $this->GetEventPicture($aBlog['ID']);

			$sGenUrl = $this->genUrl($aBlog['ID'], $aBlog['EntryUri']);

			$sLinkMore = '';
			if( strlen( $aBlog['Description']) > $iBlogLimitChars ) 
				$sLinkMore = "... <a href=\"{$sGenUrl}\">"._t('_Read more')."</a>";

			$sBlogSnippet = mb_substr( strip_tags( $aBlog['Description'] ), 0, $iBlogLimitChars ) . $sLinkMore;
			//$sDataTimeFormatted = date( $php_date_format, $aBlog['DateTime_f'] );
			$sDataTimeFormatted = LocaledDataTime($aBlog['DateTime_f']);
			$sBlocks .= <<<EOF
<div class="blog_block">
	<div class="icon_block">
		{$sPic}
	</div>
	<div class="blog_wrapper_n">
		<div class="blog_subject_n">
			<a href="{$sGenUrl}" class="bottom_text">
				{$aBlog['Title']}
			</a>
		</div>
		<div class="blogInfo">
			<span><img src="{$sClockIcon}" alt="" />{$sDataTimeFormatted} </span>
		</div>
		<div class="blogSnippet">
			{$sBlogSnippet}
		</div>
	</div>
</div>
<div class="clear_both"></div>
EOF;
		}

		if ($sBlocks == '') $sBlocks = MsgBox(_t('_Sorry, nothing found'));
		return $sBlocks;
	}

	function process_html_db_input( $sText ) {
		return addslashes( clear_xss( trim( process_pass_data( $sText ))));
	}
}

?>