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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sdating.inc.php' );

$logged['admin'] = member_auth( 1, true, true );

$navigationStep = 10; // count of events to show per page

$_page['header'] = "Events";
$_page['header_text'] = "Manage Events";
$_page['css_name'] = 'sdating_admin.css';

/**
 * Adds new event into the database
 *
 * @return int 							- zero on success, non-zero on fail
 *
 *
 */
function SDAddEvent()
{
	global $dir;
	global $sdatingThumbWidth;
	global $sdatingThumbHeight;

	// common
	$eventTitle = process_db_input( $_POST['event_title'] );
	$eventDesc = process_db_input( $_POST['event_desc'] );
	switch ( $_POST['event_status'] )
	{
		case 'active':
			$eventStatus = 'Active';
			break;
		case 'inactive':
			$eventStatus = 'Inactive';
			break;
		case 'canceled':
			$eventStatus = 'Canceled';
			break;
		default:
			$eventStatus = 'Active';
	}
	$eventStatusMessage = process_db_input( $_POST['event_statusmsg'] );

	// event place
	$eventCountry = process_db_input( $_POST['event_country'] );
	$eventCity = process_db_input( $_POST['event_city'] );
	$eventPlace = process_db_input( $_POST['event_place'] );

	$pictureName = $_FILES['event_photo']['name'];
	$thumbName = getThumbNameByPictureName( $pictureName );

	$scan = getimagesize( $_FILES['event_photo']['tmp_name'] );

	if ( ( 1 == $scan[2] || 2 == $scan[2] || 3 == $scan[2] || 6 == $scan[2] )
	    && move_uploaded_file( $_FILES['event_photo']['tmp_name'], $dir['tmp'] . $pictureName ) )
	{
		$resizeWidth = (int)$_POST['event_photo_width'];
		$resizeHeight = (int)$_POST['event_photo_height'];
		// resize for thumbnail
		$res = imageResize( $dir['tmp'] . $pictureName, $dir['sdatingImage'] . $thumbName, $sdatingThumbWidth, $sdatingThumbHeight );
		if ( $res != IMAGE_ERROR_SUCCESS )
			return SDATING_ERROR_PHOTO_PROCESS;
		// if width and height specified then resize picture
		if ( $resizeWidth && $resizeHeight )
		{
			$res = imageResize( $dir['tmp'] . $pictureName, $dir['sdatingImage'] . $pictureName, $resizeWidth, $resizeHeight );
			if ( $res != IMAGE_ERROR_SUCCESS )
				return SDATING_ERROR_PHOTO_PROCESS;
			unlink( $dir['tmp'] . $pictureName );
		}
		else
		{
			$res = rename( $dir['tmp'] . $pictureName, $dir['sdatingImage'] . $pictureName );
			if ( !$res )
				return SDATING_ERROR_PHOTO_PROCESS;
		}
		chmod( $dir['sdatingImage'] . $pictureName, 0644 );
		$eventPhotoFilename = process_db_input( $pictureName );
	}
	else
	{
		$eventPhotoFilename = '';
	}

	// event date
	$eventStart = strtotime( $_POST['event_start'] );
	if ( $eventStart == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	$eventEnd = strtotime( $_POST['event_end'] );
	if ( $eventEnd == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	$eventSaleStart = strtotime( $_POST['event_sale_start'] );
	if ( $eventSaleStart == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	$eventSaleEnd = strtotime( $_POST['event_sale_end'] );
	if ( $eventSaleEnd == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	if ( $eventEnd < $eventStart || $eventSaleEnd < $eventSaleStart || $eventStart < $eventSaleStart )
		return SDATING_ERROR_WRONG_DATE_FORMAT;

	// event responsible
	$eventRespName = process_db_input( $_POST['event_resp_name'] );
	$eventRespEmail = process_db_input( $_POST['event_resp_email'] );
	$eventRespPhone = process_db_input( $_POST['event_resp_phone'] );

	// event participants
	$eventSexFilter = '';
	if ( $_POST['event_sex_female'] == 'on' )
		$eventSexFilter .= 'female';
	if ( $_POST['event_sex_male'] == 'on' )
		$eventSexFilter .= strlen($eventSexFilter) ? ',male' : 'male';

	$eventAgeLowerFilter = (int)$_POST['event_age_start'];
	$eventAgeUpperFilter = (int)$_POST['event_age_end'];

	$eventMembershipFilter = '';
	foreach ( $_POST['event_membership'] as $membershipID )
	{
		$eventMembershipFilter .= strlen($eventMembershipFilter) ? ",\'{$membershipID}\'" : "\'{$membershipID}\'";
	}

	$eventCountF = (int)$_POST['event_count_female'];
	$eventCountM = (int)$_POST['event_count_male'];

	// ticket prices
	if ( isset($_POST['event_price_free']) && $_POST['event_price_free'] == 'on' )
	{
		$eventPriceF = '0.00';
		$eventPriceM = '0.00';
		$eventPriceC = '0.00';
	}
	else
	{
		$eventPriceF = (float)$_POST['event_price_female'];
		$eventPriceM = (float)$_POST['event_price_male'];
	}

	// choose options
	$eventChoosePeriod = (int)$_POST['event_choose_period'];

	// allow to view participants
	$eventAllowView = $_POST['event_allow_view'] == 'on' ? '1' : '0';

	$res = db_res( "INSERT INTO `SDatingEvents` SET
					`Title` = '$eventTitle',
					`Description` = '$eventDesc',
					`Status` = '$eventStatus',
					`StatusMessage` = '$eventStatusMessage',
					`Country` = '$eventCountry',
					`City` = '$eventCity',
					`Place` = '$eventPlace',
					`PhotoFilename` = '$eventPhotoFilename',
					`EventStart` = FROM_UNIXTIME( $eventStart ),
					`EventEnd` = FROM_UNIXTIME( $eventEnd ),
					`TicketSaleStart` = FROM_UNIXTIME( $eventSaleStart ),
					`TicketSaleEnd` = FROM_UNIXTIME( $eventSaleEnd ),
					`ResponsibleName` = '$eventRespName',
					`ResponsibleEmail` = '$eventRespEmail',
					`ResponsiblePhone` = '$eventRespPhone',
					`EventSexFilter` = '$eventSexFilter',
					`EventAgeLowerFilter` = $eventAgeLowerFilter,
					`EventAgeUpperFilter` = $eventAgeUpperFilter,
					`EventMembershipFilter` = '$eventMembershipFilter',
					`TicketCountFemale` = $eventCountF,
					`TicketCountMale` = $eventCountM,
					`TicketPriceFemale` = $eventPriceF,
					`TicketPriceMale` = $eventPriceM,
					`ChoosePeriod` = $eventChoosePeriod,
					`AllowViewParticipants` = $eventAllowView" );
	if ( !$res )
		return SDATING_ERROR_QUERY_ERROR;
	if ( mysql_affected_rows() == 0 )
		return SDATING_ERROR_NOT_AFFECTED;
	return SDATING_ERROR_SUCCESS;
}

/**
 * Updates event information in the database
 *
 * @param int $eventId					- update event ID
 *
 * @return int 							- zero on success, non-zero on fail
 *
 *
 */
function SDUpdateEvent( $eventId )
{
	global $dir;
	global $sdatingThumbWidth;
	global $sdatingThumbHeight;

	$eventExistArr = db_arr( "SELECT `ID`, `Title`, `Description`, `Status`, `StatusMessage`, `Country`, `City`, `Place`, `PhotoFilename`, `EventStart`, `EventEnd`, `TicketSaleStart`, `TicketSaleEnd`, `ResponsibleName`, `ResponsibleEmail`, `ResponsiblePhone`, `EventSexFilter`, `EventAgeLowerFilter`, `EventAgeUpperFilter`, `EventMembershipFilter`, `TicketCountFemale`, `TicketCountMale`, `TicketPriceFemale`, `TicketPriceMale`, `ChoosePeriod`, `AllowViewParticipants` FROM `SDatingEvents` WHERE `ID` = $eventId" );

	// common
	$eventTitle = process_db_input( $_POST['event_title'] );
	$eventDesc = process_db_input( $_POST['event_desc'] );
	switch ( $_POST['event_status'] )
	{
		case 'active':
			$eventStatus = 'Active';
			break;
		case 'inactive':
			$eventStatus = 'Inactive';
			break;
		case 'canceled':
			$eventStatus = 'Canceled';
			break;
		default:
			$eventStatus = 'Active';
	}
	$eventStatusMessage = process_db_input( $_POST['event_statusmsg'] );

	// event place
	$eventCountry = process_db_input( $_POST['event_country'] );
	$eventCity = process_db_input( $_POST['event_city'] );
	$eventPlace = process_db_input( $_POST['event_place'] );

	$pictureName = $_FILES['event_photo']['name'];
	$thumbName = getThumbNameByPictureName( $pictureName );

	$scan = getimagesize( $_FILES['event_photo']['tmp_name'] );

	if ( ( 1 == $scan[2] || 2 == $scan[2] || 3 == $scan[2] || 6 == $scan[2] )
		&& move_uploaded_file( $_FILES['event_photo']['tmp_name'], $dir['tmp'] . $pictureName ) )
	{
		if ( strlen( $eventExistArr['PhotoFilename'] ) && file_exists( $dir['sdatingImage'] . $eventExistArr['PhotoFilename'] ) )
		{
			unlink( $dir['sdatingImage'] . $eventExistArr['PhotoFilename'] );
		}
		$resizeWidth = (int)$_POST['event_photo_width'];
		$resizeHeight = (int)$_POST['event_photo_height'];
		// resize for thumbnail
		$res = imageResize( $dir['tmp'] . $pictureName, $dir['sdatingImage'] . $thumbName, $sdatingThumbWidth, $sdatingThumbHeight );
		if ( $res != IMAGE_ERROR_SUCCESS )
			return SDATING_ERROR_PHOTO_PROCESS;
		// if width and height specified then resize picture
		if ( $resizeWidth && $resizeHeight )
		{
			$res = imageResize( $dir['tmp'] . $pictureName, $dir['sdatingImage'] . $pictureName, $resizeWidth, $resizeHeight );
			if ( $res != IMAGE_ERROR_SUCCESS )
				return SDATING_ERROR_PHOTO_PROCESS;
			unlink( $dir['tmp'] . $pictureName );
		}
		else
		{
			$res = rename( $dir['tmp'] . $pictureName, $dir['sdatingImage'] . $pictureName );
			if ( !$res )
				return SDATING_ERROR_PHOTO_PROCESS;
		}
		chmod( $dir['sdatingImage'] . $pictureName, 0644 );
		$eventPhotoFilename = process_db_input( $pictureName );
	}
	else
	{
		$eventPhotoFilename = $eventExistArr['PhotoFilename'];
	}

	// event date
	$eventStart = strtotime( $_POST['event_start'] );
	if ( $eventStart == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	$eventEnd = strtotime( $_POST['event_end'] );
	if ( $eventEnd == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	$eventSaleStart = strtotime( $_POST['event_sale_start'] );
	if ( $eventSaleStart == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	$eventSaleEnd = strtotime( $_POST['event_sale_end'] );
	if ( $eventSaleEnd == -1 )
		return SDATING_ERROR_WRONG_DATE_FORMAT;
	if ( $eventEnd < $eventStart || $eventSaleEnd < $eventSaleStart || $eventStart < $eventSaleStart )
		return SDATING_ERROR_WRONG_DATE_FORMAT;

	// event responsible
	$eventRespName = process_db_input( $_POST['event_resp_name'] );
	$eventRespEmail = process_db_input( $_POST['event_resp_email'] );
	$eventRespPhone = process_db_input( $_POST['event_resp_phone'] );

	// event participants
	$eventSexFilter = '';
	if ( $_POST['event_sex_female'] == 'on' )
		$eventSexFilter .= 'female';
	if ( $_POST['event_sex_male'] == 'on' )
		$eventSexFilter .= strlen($eventSexFilter) ? ',male' : 'male';

	$eventAgeLowerFilter = (int)$_POST['event_age_start'];
	$eventAgeUpperFilter = (int)$_POST['event_age_end'];

	$eventMembershipFilter = '';
	foreach ( $_POST['event_membership'] as $membershipID )
	{
		$eventMembershipFilter .= strlen($eventMembershipFilter) ? ",\'{$membershipID}\'" : "\'{$membershipID}\'";
	}

	$eventCountF = (int)$_POST['event_count_female'];
	$eventCountM = (int)$_POST['event_count_male'];

	// ticket prices
	if ( isset($_POST['event_price_free']) && $_POST['event_price_free'] == 'on' )
	{
		$eventPriceF = '0.00';
		$eventPriceM = '0.00';
		$eventPriceC = '0.00';
	}
	else
	{
		$eventPriceF = (float)$_POST['event_price_female'];
		$eventPriceM = (float)$_POST['event_price_male'];
	}

	// choose options
	$eventChoosePeriod = (int)$_POST['event_choose_period'];

	// allow to view participants
	$eventAllowView = $_POST['event_allow_view'] == 'on' ? '1' : '0';

	$res = db_res( "UPDATE `SDatingEvents` SET
					`Title` = '$eventTitle',
					`Description` = '$eventDesc',
					`Status` = '$eventStatus',
					`StatusMessage` = '$eventStatusMessage',
					`Country` = '$eventCountry',
					`City` = '$eventCity',
					`Place` = '$eventPlace',
					`PhotoFilename` = '$eventPhotoFilename',
					`EventStart` = FROM_UNIXTIME( $eventStart ),
					`EventEnd` = FROM_UNIXTIME( $eventEnd ),
					`TicketSaleStart` = FROM_UNIXTIME( $eventSaleStart ),
					`TicketSaleEnd` = FROM_UNIXTIME( $eventSaleEnd ),
					`ResponsibleName` = '$eventRespName',
					`ResponsibleEmail` = '$eventRespEmail',
					`ResponsiblePhone` = '$eventRespPhone',
					`EventSexFilter` = '$eventSexFilter',
					`EventAgeLowerFilter` = $eventAgeLowerFilter,
					`EventAgeUpperFilter` = $eventAgeUpperFilter,
					`EventMembershipFilter` = '$eventMembershipFilter',
					`TicketCountFemale` = $eventCountF,
					`TicketCountMale` = $eventCountM,
					`TicketPriceFemale` = $eventPriceF,
					`TicketPriceMale` = $eventPriceM,
					`ChoosePeriod` = $eventChoosePeriod,
					`AllowViewParticipants` = $eventAllowView
					WHERE `ID` = $eventId" );
	if ( !$res )
		return SDATING_ERROR_QUERY_ERROR;
	if ( mysql_affected_rows() == 0 )
		return SDATING_ERROR_NOT_AFFECTED;
	return SDATING_ERROR_SUCCESS;
}

/**
 * Deletes event from the database
 *
 * @param int $eventId					- delete event ID
 *
 * @return bool 						- true on success, false on fail
 *
 *
 */
function SDDeleteEvent( $eventId )
{
	return db_res( "DELETE FROM `SDatingEvents` WHERE `ID` = $eventId" );
}

/**
 * Prints brief information for specified event. Uses for printing events lists
 *
 * @param array $eventArr					- array with event data obtained from the database
 *
 *
 */
function SDPrintEventRow( $eventArr )
{
	global $dir;
	global $site;
	global $aPreValues;

	$aPostedBy = getProfileInfo($eventArr['ResponsibleID']);
	$sPostedBy = $aPostedBy['NickName'];
?>
<div style="padding: 2px; width: 540px; overflow: auto;">
	<table cellpadding="2" cellspacing="2" border="0" class="text" width="100%">
		<tr>
			<td align="center" rowspan="2" width="208">
<?
	if ( strlen(trim($eventArr['PhotoFilename'])) && file_exists($dir['sdatingImage'] . $eventArr['PhotoFilename']) )
	{
		echo "
				<img src=\"{$site['sdatingImage']}{$eventArr['PhotoFilename']}\" border=\"0\" alt=\"{$eventArr['Title']} photo\" />";
	}
	else
	{
		echo "
				<div align=\"center\" class=\"text\" style=\"width: 200px; height: 150px; vertical-align: middle; line-height: 150px; border: 1px solid silver;\">No photo</div>";
	}
?>
			</td>
			<td align="center" class="section_header" style="height: 6px; line-height: 10px; font-weight: bold;"><?= process_line_output($eventArr['Title']) ?></td>
		</tr>
		<tr>
			<td class="section_row" style="text-align: left; vertical-align: top;">
				<div style="text-align: right;"><a href="<?= "{$_SERVER['PHP_SELF']}?action=show_edit_form&amp;event_id={$eventArr['ID']}" ?>">Edit</a>&nbsp;|&nbsp;<a href="javascript:void(null);" onClick="javascript: if ( confirm('Are you sure you want to delete this event?') ) { location.href = '<?= "{$_SERVER['PHP_SELF']}?action=delete&amp;event_id={$eventArr['ID']}" ?>'; } return false; ">Delete</a>&nbsp;|&nbsp;<a href="<?= "{$_SERVER['PHP_SELF']}?action=show_part&amp;event_id={$eventArr['ID']}" ?>">Participants</a>&nbsp;|&nbsp;<a href="<?= "{$_SERVER['PHP_SELF']}?action=show_match&amp;event_id={$eventArr['ID']}" ?>">Matches</a></div>
				<b>Status:</b> <?= $eventArr['Status'] ?><br />
				<b>Status message:</b> <?= process_line_output($eventArr['StatusMessage']) ?><br />
				<b>Appointed date/time:</b> <?= $eventArr['EventStart'] ?><br />
				<b>Posted By:</b> <?= '<a href='.$site['url'].$sPostedBy.'>'.$sPostedBy.'</a>' ?><br />
				<b>Place:</b> <?= _t($aPreValues['Country'][$eventArr['Country']]['LKey']) .', '. process_line_output($eventArr['City']) .', '. process_line_output($eventArr['Place']) ?><br />
				<b>Description:</b> <?= $eventArr['Description'] ?><br />
			</td>
		</tr>
	</table>
</div>
<?
}

/**
 * Prints page navigation controls
 *
 * @param int $fromEvent					- zero-based event index which shows current navigation position
 * @param int $totalEvents					- total count of events in query
 * @param bool $topNavigation				- is navigation bar located in the top (in the bottom otherwise)
 *
 *
 */
function SDPrintNavigation( $fromEvent, $totalEvents, $topNavigation = true )
{
	global $navigationStep;

	$fromEvent = (int)$fromEvent;
	$totalEvents = (int)$totalEvents;
	$ret = 'Pages:&nbsp;';

	// if count of events less than page step, then page navigation isn't shown
	if ( $totalEvents <= $navigationStep )
		return '';

	// if it's not first page then show 'Prev' link
	if ( $fromEvent >= $navigationStep )
	{
		$prevFrom = (0 < ($fromEvent - $navigationStep)) ? ($fromEvent - $navigationStep) : 0;
		$ret .= "<a href=\"javascript:void(null);\" onClick=\"javascript: navigationSubmit({$prevFrom}); return false;\">Prev</a>&nbsp;";
	}

	// show page links
	$currentEvent = 0;
	$currentPage = 1;
	while ( $currentEvent < $totalEvents )
	{
		if ( $currentEvent == $fromEvent )
			$ret .= "[{$currentPage}]&nbsp;";
		else
			$ret .= "<a href=\"javascript:void(null);\" onClick=\"javascript: navigationSubmit({$currentEvent}); return false;\">{$currentPage}</a>&nbsp;";
		$currentEvent += $navigationStep;
		$currentPage++;
	}

	// if it's not last page then show 'Next' link
	if ( $totalEvents > $fromEvent + $navigationStep )
	{
		$nextFrom = $fromEvent + $navigationStep;
		$ret .= "<a href=\"javascript:void(null);\" onClick=\"javascript: navigationSubmit({$nextFrom}); return false;\">Next</a>";
	}

	// put navigation elements into the div
	$divMargin = $topNavigation ? 'margin-bottom: 4px;' : 'margin-top: 4px;';
	return "<div style=\"text-align: center; {$divMargin}\">{$ret}</div>";
}

/**
 * Prints edit form for event editing
 *
 * @param bool $newEvent				- if event already exists - then edit, create new otherwise
 * @param array $eventArr				- if event exists then this parameter should contain all event data
 *
 *
 */
function SDShowEditForm( $newEvent, $eventArr = array() )
{
	global $aPreValues;
	global $dir;
	global $site;

	if ( $newEvent )
	{
		$eventTitle = '';
		$eventDesc = '';
		$eventStatusActiveSel = '';
		$eventStatusInactiveSel = '';
		$eventStatusCanceledSel = '';
		$eventStatusMsg = '';
		$eventCity = '';
		$eventPlace = '';
		$eventStart = '';
		$eventEnd = '';
		$eventSaleStart = '';
		$eventSaleEnd = '';
		$eventRespName = '';
		$eventRespEmail = '';
		$eventRespPhone = '';
		$eventSexFemale = 'checked="checked"';
		$eventSexMale = 'checked="checked"';
		$eventCountFemale = '';
		$eventCountMale = '';
		$eventPriceFemale = '';
		$eventPriceMale = '';
		$eventPriceFree = '';
		$eventPriceFemaleDisabled = '';
		$eventPriceMaleDisabled = '';
		$eventChoosePeriod = '';
		$eventAllowView = '';
	}
	else
	{
		$eventTitle = htmlspecialchars($eventArr['Title']);
		$eventDesc = htmlspecialchars($eventArr['Description']);
		$eventStatusActiveSel = '';
		$eventStatusInactiveSel = '';
		$eventStatusCanceledSel = '';
		switch ( $eventArr['Status'] )
		{
			case 'Active':
				$eventStatusActiveSel = 'selected="selected"';
				break;
			case 'Inactive':
				$eventStatusInactiveSel = 'selected="selected"';
				break;
			case 'Canceled':
				$eventStatusCanceledSel = 'selected="selected"';
				break;
		}
		$eventStatusMsg = htmlspecialchars($eventArr['StatusMessage']);
		$eventCity = htmlspecialchars($eventArr['City']);
		$eventPlace = htmlspecialchars($eventArr['Place']);
		$eventStart = $eventArr['EventStart'];
		$eventEnd = $eventArr['EventEnd'];
		$eventSaleStart = $eventArr['TicketSaleStart'];
		$eventSaleEnd = $eventArr['TicketSaleEnd'];
		$eventRespName = htmlspecialchars($eventArr['ResponsibleName']);
		$eventRespEmail = htmlspecialchars($eventArr['ResponsibleEmail']);
		$eventRespPhone = htmlspecialchars($eventArr['ResponsiblePhone']);
		$eventSexFemale = strstr($eventArr['EventSexFilter'], 'female') ? 'checked="checked"' : '';
		$eventSexMale = strstr($eventArr['EventSexFilter'], 'male') ? 'checked="checked"' : '';
		$eventCountFemale = $eventArr['TicketCountFemale'];
		$eventCountMale = $eventArr['TicketCountMale'];
		$eventPriceFemale = sprintf("%.2f", (float)$eventArr['TicketPriceFemale']);
		$eventPriceMale = sprintf("%.2f", (float)$eventArr['TicketPriceMale']);
		$eventPriceFree = ( $eventPriceFemale == '0.00' && $eventPriceMale == '0.00' ? 'checked="checked"' : '' );
		$eventPriceFemaleDisabled = ( strlen($eventPriceFree) ? 'disabled="disabled"' : '' );
		$eventPriceMaleDisabled = ( strlen($eventPriceFree) ? 'disabled="disabled"' : '' );
		$eventChoosePeriod = $eventArr['ChoosePeriod'];
		$eventAllowView = $eventArr['AllowViewParticipants'] == 1 ? 'checked="checked"' : '';
	}
?>

<script language="JavaScript" type="text/javascript">
<!--

	function trim(inputString)
	{
		if (typeof inputString != "string")
		{
			return inputString;
		}

		var retValue = inputString;

		// Check for spaces at the beginning of the string
		var ch = retValue.substring(0, 1);
		while (ch == " ")
		{
			retValue = retValue.substring(1, retValue.length);
			ch = retValue.substring(0, 1);
		}

		// Check for spaces at the end of the string
		ch = retValue.substring(retValue.length-1, retValue.length);
		while (ch == " ")
		{
			retValue = retValue.substring(0, retValue.length-1);
			ch = retValue.substring(retValue.length-1, retValue.length);
		}

		// Note that there are two spaces in the string - look for multiple spaces within the string
		while (retValue.indexOf("  ") != -1)
		{
			// Again, there are two spaces in each of the strings
			retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
		}

		return retValue;
	}

	function validateEditForm()
	{
		// check title
		if ( trim(document.getElementById('event_title_id').value).length == 0 )
		{
			alert('Please enter event title');
			return false;
		}
		// check description
		if ( trim(document.getElementById('event_desc_id').value).length == 0 )
		{
			alert('Please enter event description');
			return false;
		}
		// check status message
		if ( trim(document.getElementById('event_statusmsg_id').value).length == 0 )
		{
			alert('Please enter event status message');
			return false;
		}
		// check city
		if ( trim(document.getElementById('event_city_id').value).length == 0 )
		{
			alert('Please enter event city');
			return false;
		}
		// check place
		if ( trim(document.getElementById('event_place_id').value).length == 0 )
		{
			alert('Please enter event place');
			return false;
		}
		// check start date
		if ( trim(document.getElementById('event_start_id').value).length == 0 )
		{
			alert('Please enter event start date');
			return false;
		}
		// check end date
		if ( trim(document.getElementById('event_end_id').value).length == 0 )
		{
			alert('Please enter event end date');
			return false;
		}
		// check sale start date
		if ( trim(document.getElementById('event_sale_start_id').value).length == 0 )
		{
			alert('Please enter event sale start date');
			return false;
		}
		// check sale end date
		if ( trim(document.getElementById('event_sale_end_id').value).length == 0 )
		{
			alert('Please enter event sale end date');
			return false;
		}
		// check if any of sex checkboxes checked
		if ( !document.getElementById('event_sex_female_id').checked
			&& !document.getElementById('event_sex_male_id').checked )
		{
			alert('At least one sex should be chosen');
			return false;
		}
		// check if age range is correct
		var ageStart = parseInt( document.getElementById('event_age_start_id').value );
		var ageEnd = parseInt( document.getElementById('event_age_end_id').value );
		if ( isNaN(ageStart) || isNaN(ageEnd) || ageEnd < ageStart )
		{
			alert('Please specify correct participants age range');
			return false;
		}
		// check if any membership level selected
		var anySelected = false;
		var selectOptions = document.getElementById('event_membership_id').options;
		for ( i = 0; i < selectOptions.length; i++ )
		{
			if ( selectOptions[i].selected )
			{
				anySelected = true;
				break;
			}
		}
		if ( !anySelected )
		{
			alert('Select membership level(s) of participants');
			return false;
		}
		// check female ticket count
		if ( isNaN( parseInt(document.getElementById('event_count_female_id').value) ) )
		{
			alert('Please enter correct female ticket count');
			return false;
		}
		// check male ticket count
		if ( isNaN( parseInt(document.getElementById('event_count_male_id').value) ) )
		{
			alert('Please enter correct male ticket count');
			return false;
		}
		// check if event is free
		var isFree = document.getElementById('event_price_free_id').checked;
		// check female ticket price
		if ( !isFree && isNaN( parseFloat(document.getElementById('event_price_female_id').value) ) )
		{
			alert('Please enter correct female ticket price');
			return false;
		}
		// check male ticket price
		if ( !isFree && isNaN( parseFloat(document.getElementById('event_price_male_id').value) ) )
		{
			alert('Please enter correct male ticket price');
			return false;
		}
		// check choose period
		if ( isNaN( parseInt(document.getElementById('event_choose_period_id').value) ) )
		{
			alert('Please enter correct choose period days');
			return false;
		}
		return true;
	}

	function updatePriceControls()
	{
		var isFree = document.getElementById('event_price_free_id').checked;
		document.getElementById('event_price_female_id').disabled = isFree;
		document.getElementById('event_price_male_id').disabled = isFree;
	}

-->
</script>

<form id="editEventForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" style="margin: 0px;">
<table cellpadding="4" cellspacing="2" border="0" width="540" class="text">
	<tr>
		<td class="section_header">Common</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="100%">
				<tr>
					<td align="left" width="120">Title <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_title" id="event_title_id" size="50" value="<?= $eventTitle ?>" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120" valign="top">Description <font color="red">*</font></td>
					<td align="left">
						<textarea class="no" name="event_desc" id="event_desc_id" cols="60" rows="10"><?= $eventDesc ?></textarea>
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Status</td>
					<td align="left">
						<select class="no" name="event_status" id="event_status_id" style="width: 100px;">
							<option value="active" <?= $eventStatusActiveSel ?> >Active</option>
							<option value="inactive" <?= $eventStatusInactiveSel ?> >Inactive</option>
							<option value="canceled" <?= $eventStatusCanceledSel ?> >Canceled</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Status message <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_statusmsg" id="event_statusmsg_id" size="60" value="<?= $eventStatusMsg ?>" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="section_header">Event place</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="100%">
				<tr>
					<td align="left" width="120">Country <font color="red">*</font></td>
					<td align="left">
						<select class="no" name="event_country" id="event_country_id">
<?
	$selectedCountry = $newEvent ? getParam( 'default_country' ) : $eventArr['Country'];
	foreach ( $aPreValues['Country'] as $key => $value )
	{
		echo "
							<option value=\"{$key}\" ". ( $selectedCountry == $key ? 'selected="selected"' : '' ) ." >". _t($value['LKey']) ."</option>";
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="left" width="120">City <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_city" id="event_city_id" size="24" value="<?= $eventCity ?>" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Place <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_place" id="event_place_id" size="60" value="<?= $eventPlace ?>" />
					</td>
				</tr>
<?
	if ( !$newEvent && strlen( $eventArr['PhotoFilename'] ) && file_exists( $dir['sdatingImage'] . $eventArr['PhotoFilename'] ) )
	{
?>
				<tr>
					<td align="left" valign="top" width="120">Venue photo</td>
					<td align="left">
						<img src="<?= $site['sdatingImage'] . $eventArr['PhotoFilename'] ?>" border="0" alt="Event photo" />
					</td>
				</tr>
				<tr>
					<td align="right" width="120" nowrap>upload new photo</td>
<?
	}
	else
	{
?>
				<tr>
					<td align="left" valign="middle" width="120">Venue photo</td>
<?
	}
?>
					<td align="left">
						<input type="file" class="no" name="event_photo" id="event_photo_id" size="40" />
					</td>
				</tr>
				<tr>
					<td align="right" width="120" nowrap>resize photo to</td>
					<td align="left">
						<input type="text" class="no" name="event_photo_width" id="event_photo_width_id" size="4" value="200" style="vertical-align: middle;" />&nbsp;x&nbsp;<input type="text" class="no" name="event_photo_height" id="event_photo_height_id" size="4" value="150" style="vertical-align: middle;" />&nbsp;pixels
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="section_header">Event date</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="100%">
				<tr>
					<td align="left" width="120">Event start <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_start" id="event_start_id" size="20" value="<?= $eventStart ?>" />
						&nbsp;<input type="button" id="start_choose_id" value="Choose" />
						&nbsp;<input type="button" id="start_clear_id" onClick="document.getElementById('event_start_id').value = ''; " value="Clear" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Event end <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_end" id="event_end_id" size="20" value="<?= $eventEnd ?>" />
						&nbsp;<input type="button" id="end_choose_id" value="Choose" />
						&nbsp;<input type="button" id="end_clear_id" onClick="document.getElementById('event_end_id').value = ''; " value="Clear" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Ticket sale start <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_sale_start" id="event_sale_start_id" size="20" value="<?= $eventSaleStart ?>" />
						&nbsp;<input type="button" id="sale_start_choose_id" value="Choose" />
						&nbsp;<input type="button" id="sale_start_clear_id" onClick="document.getElementById('event_sale_start_id').value = ''; " value="Clear" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Ticket sale end <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_sale_end" id="event_sale_end_id" size="20" value="<?= $eventSaleEnd ?>" />
						&nbsp;<input type="button" id="sale_end_choose_id" value="Choose" />
						&nbsp;<input type="button" id="sale_end_clear_id" onClick="document.getElementById('event_sale_end_id').value = ''; " value="Clear" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="section_header">Responsible person or company</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="100%">
				<tr>
					<td align="left" width="120">Name</td>
					<td align="left">
						<input type="text" class="no" name="event_resp_name" id="event_resp_name_id" size="40" value="<?= $eventRespName ?>" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">E-mail</td>
					<td align="left">
						<input type="text" class="no" name="event_resp_email" id="event_resp_email_id" size="40" value="<?= $eventRespEmail ?>" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Phone</td>
					<td align="left">
						<input type="text" class="no" name="event_resp_phone" id="event_resp_phone_id" size="16" value="<?= $eventRespPhone ?>" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="section_header">Event participants</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="100%">
				<tr>
					<td align="left" width="120">Sex</td>
					<td align="left">
						<input type="checkbox" class="no" name="event_sex_female" id="event_sex_female_id" style="vertical-align: middle;" <?= $eventSexFemale ?> />
						&nbsp;<label for="event_sex_female_id">female</label>
						&nbsp;<input type="checkbox" class="no" name="event_sex_male" id="event_sex_male_id" style="vertical-align: middle;" <?= $eventSexMale ?> />
						&nbsp;<label for="event_sex_male_id">male</label>
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Age</td>
					<td align="left">
						from&nbsp;<select class="no" name="event_age_start" id="event_age_start_id" style="vertical-align: middle;">
<?
	// if it's new event then show default values and show values from database otherwise
	$gl_search_start_age = (int)getParam( 'search_start_age' );
	$gl_search_end_age = (int)getParam( 'search_end_age' );
	$ageStartSel = $newEvent ? $gl_search_start_age : $eventArr['EventAgeLowerFilter'];
	$ageEndSel = $newEvent ? $gl_search_end_age : $eventArr['EventAgeUpperFilter'];
	for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
	{
		$sel = ($i == $ageStartSel ? 'selected="selected"' : '');
		echo "
							<option value=\"$i\" $sel>$i</option>";
	}
?>
									</select>
						to&nbsp;<select class="no" name="event_age_end" id="event_age_end_id" style="vertical-align: middle;">
<?
	for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
	{
		$sel = ($i == $ageEndSel ? 'selected="selected"' : '');
		echo "
							<option value=\"$i\" $sel>$i</option>";
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top" width="120">Membership</td>
					<td align="left">
		 				<select class="no" name="event_membership[]" id="event_membership_id" size="6" multiple style="width: 150px;">
		 					<option value="all" <?= $newEvent || strstr($eventArr['EventMembershipFilter'], '\'all\'') ? 'selected="selected"' : '' ?> >All</option>
<?
	// show all membership levels except non-member
	$memberships_arr = getMemberships();
	foreach ( $memberships_arr as $membershipID => $membershipName )
	{
		if ( $membershipID == MEMBERSHIP_ID_NON_MEMBER )
			continue;
		echo "
							<option value=\"{$membershipID}\" ". (!$newEvent && strstr($eventArr['EventMembershipFilter'], "'{$membershipID}'") ? 'selected="selected"' : '') ." >{$membershipName}</option>";
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Female ticket count <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_count_female" id="event_count_female_id" size="6" value="<?= $eventCountFemale ?>" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Male ticket count <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_count_male" id="event_count_male_id" size="6" value="<?= $eventCountMale ?>" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="section_header">Ticket prices</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="100%">
				<tr>
					<td align="left" width="120">Event is free</td>
					<td align="left">
						<input type="checkbox" class="no" name="event_price_free" id="event_price_free_id" style="vertical-align: middle;" onClick="javascript: updatePriceControls();" <?= $eventPriceFree ?> />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Female ticket price <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_price_female" id="event_price_female_id" size="8" value="<?= $eventPriceFemale ?>" <?= $eventPriceFemaleDisabled ?> />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Male ticket price <font color="red">*</font></td>
					<td align="left">
						<input type="text" class="no" name="event_price_male" id="event_price_male_id" size="8" value="<?= $eventPriceMale ?>" <?= $eventPriceMaleDisabled ?> />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="section_header">Member side options</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="100%">
				<tr>
					<td align="left" width="120">Choose period <font color="red">*</font><br />(in days)</td>
					<td align="left">
						<input type="text" class="no" name="event_choose_period" id="event_choose_period_id" size="6" value="<?= $eventChoosePeriod ?>" />
					</td>
				</tr>
				<tr>
					<td align="left" width="120">Allow members to view participants list</td>
					<td align="left">
						<input type="checkbox" class="no" name="event_allow_view" id="event_allow_view_id" <?= $eventAllowView ?> />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="middle" style="padding-top: 8px;">
			<input type="submit" class="no" name="event_save" value="Save" style="width: 60px; vertical-align: middle;" onClick="javascript: return validateEditForm();" />
<?
	if ( !$newEvent )
	{
?>
			&nbsp;<input type="checkbox" class="no" name="event_as_new" id="event_as_new_id" style="vertical-align: middle;" />
			&nbsp;<label for="event_as_new_id">as new</label>
			<input type="hidden" name="event_id" value="<?= $eventArr['ID'] ?>" />
			<input type="hidden" name="action" value="edit" />
<?
	}
	else
	{
?>
			<input type="hidden" name="action" value="new" />
<?
	}
?>
		</td>
	</tr>
</table>
</form>
<?
}

$show_query = '';
$show = array();
$show_match = array();
$between_disabled = 'disabled="disabled"';
$country_disabled = 'disabled="disabled"';

switch ( $_REQUEST['action'] )
{
	// show list of events according selected filter
	case 'show':
		$show_from = (int)$_REQUEST['from'];
		switch ( $_REQUEST['show_events'] )
		{
			// show events between specified dates
			case 'date':
				// First date parse
				if ( $_REQUEST['show_events_between1'] == 'start' )
					$between_date1 = 0;
				elseif ( $_REQUEST['show_events_between1'] == 'now' )
					$between_date1 = time();
				else
					$between_date1 = strtotime( $_REQUEST['show_events_between1'] );
				// Second date parse
				if ( $_REQUEST['show_events_between2'] == 'start' )
					$between_date2 = 0;
				elseif ( $_REQUEST['show_events_between2'] == 'now' )
					$between_date2 = time();
				else
					$between_date2 = strtotime( $_REQUEST['show_events_between2'] );
				$show['between_date1'] = process_pass_data( $_REQUEST['show_events_between1'] );
				$show['between_date2'] = process_pass_data( $_REQUEST['show_events_between2'] );
				if ( $between_date1 != -1 && $between_date2 != -1 )
				{
					$show_query = "SELECT `ID`, `Title`, `Description`, `Status`, `StatusMessage`, `Country`, `City`, `Place`, `PhotoFilename`, `EventStart`, `ResponsibleID`
									FROM `SDatingEvents`
									WHERE ( FROM_UNIXTIME($between_date1) <= `EventStart`
										AND FROM_UNIXTIME($between_date2) >= `EventStart` )
									ORDER BY `EventStart` DESC
									LIMIT {$show_from}, {$navigationStep}";
					$total_query = "SELECT COUNT(*)
									FROM `SDatingEvents`
									WHERE ( FROM_UNIXTIME($between_date1) <= `EventStart`
										AND FROM_UNIXTIME($between_date2) >= `EventStart` )";
				}
				else
				{
					$show['error_text'] = "Please specify correct dates";
				}
				$between_disabled = '';
				break;
			// show events in specified country
			case 'country':
				$show_query = "SELECT `ID`, `Title`, `Description`, `Status`, `StatusMessage`, `Country`, `City`, `Place`, `PhotoFilename`, `EventStart`, `ResponsibleID`
								FROM `SDatingEvents`
								WHERE `Country` = '". process_db_input($_REQUEST['show_events_country']) ."'
								ORDER BY `EventStart` DESC
								LIMIT {$show_from}, {$navigationStep}";
				$total_query = "SELECT COUNT(*)
									FROM `SDatingEvents`
									WHERE `Country` = '". process_db_input($_REQUEST['show_events_country']) ."'";
				$show['country'] = process_pass_data($_REQUEST['show_events_country']);
				$country_disabled = '';
				break;
			// show all events
			case 'all':
			default:
				$show_query = "SELECT `ID`, `Title`, `Description`, `Status`, `StatusMessage`, `Country`, `City`, `Place`, `PhotoFilename`, `EventStart`, `ResponsibleID`
								FROM `SDatingEvents`
								ORDER BY `EventStart` DESC
								LIMIT {$show_from}, {$navigationStep}";
				$total_query = "SELECT COUNT(*)
									FROM `SDatingEvents`";
		}

		break;

	// update existing event or add new one
	case 'edit':
		$event_id = (int)$_REQUEST['event_id'];
		if ( $event_id )
		{
			if ( $_POST['event_as_new'] == 'on' )
				$edit_res = SDAddEvent();
			else
				$edit_res = SDUpdateEvent( $event_id );
			switch ( $edit_res )
			{
				case SDATING_ERROR_SUCCESS:
					if ( $_POST['event_as_new'] == 'on' )
						$show['error_text'] = 'Event was successfully added';
					else
						$show['error_text'] = 'Event was successfully updated';
					break;
				case SDATING_ERROR_QUERY_ERROR:
					if ( $_POST['event_as_new'] == 'on' )
						$show['error_text'] = 'Insert query error';
					else
						$show['error_text'] = 'Update query error';
					break;
				case SDATING_ERROR_NOT_AFFECTED:
					if ( $_POST['event_as_new'] == 'on' )
						$show['error_text'] = 'Event wasn\'t added';
					else
						$show['error_text'] = 'Event wasn\'t updated';
					break;
				case SDATING_ERROR_WRONG_DATE_FORMAT:
					$show['error_text'] = 'Wrong date format or wrong date order';
					break;
				case SDATING_ERROR_PHOTO_PROCESS:
					$show['error_text'] = 'Error during photo resizing';
					break;
			}
		}
		break;

	// delete event
	case 'delete':
		$event_id = (int)$_REQUEST['event_id'];
		if ( $event_id )
		{
			$delete_res = SDDeleteEvent( $event_id );
			if ( $delete_res )
				$show['error_text'] = 'Event was successfully deleted';
			else
				$show['error_text'] = 'Delete query error';
		}
		break;

	// add new event
	case 'new':
		$add_res = SDAddEvent();
		switch ( $add_res )
		{
			case SDATING_ERROR_SUCCESS:
				$show['error_text'] = 'Event was successfully added';
				break;
			case SDATING_ERROR_QUERY_ERROR:
				$show['error_text'] = 'Insert query error';
				break;
			case SDATING_ERROR_NOT_AFFECTED:
				$show['error_text'] = 'Event wasn\'t added';
				break;
			case SDATING_ERROR_WRONG_DATE_FORMAT:
				$show['error_text'] = 'Wrong date format or wrong date order';
				break;
			case SDATING_ERROR_PHOTO_PROCESS:
				$show['error_text'] = 'Error during photo resizing';
				break;
		}
		break;

	// show participants of specified event
	case 'show_part':
		$event_id = (int)$_REQUEST['event_id'];
		if ( $event_id )
		{
			// delete members from event
			if ( $_POST['participant_cancel'] == 'Remove from event' )
			{
				$delete_participants = '';
				foreach ( $_POST as $key => $value )
				{
					if ( (int)$key && $value == 'on' )
					{
						$delete_participants .= strlen($delete_participants) ? ','. (int)$key : (int)$key;
					}
				}
				if ( strlen($delete_participants) )
				{
					db_res( "DELETE FROM `SDatingParticipants` WHERE `ID` IN ($delete_participants)" );
					db_res( "DELETE FROM `SDatingMatches` WHERE `IDChooser` IN ($delete_participants) OR `IDChosen` IN ($delete_participants)" );
				}
			}

			// send email to members
			if ( $_POST['send_message'] == 'Send message' )
			{
				$subject = getParam( 't_SDatingAdminEmail_subject' );
				$text = getParam( 't_SDatingAdminEmail' );

				$failed_count = 0;
				foreach ( $_POST as $key => $value )
				{
					if ( (int)$key && $value == 'on' )
					{
						$part_arr = db_arr( "SELECT `Profiles`.`ID`, `Profiles`.`NickName`, `Profiles`.`Email`, `SDatingParticipants`.`ParticipantUID`, `SDatingEvents`.`Title`, `SDatingEvents`.`Place`, `SDatingEvents`.`EventStart` FROM `SDatingParticipants`
												LEFT JOIN `Profiles` ON `SDatingParticipants`.`IDMember` = `Profiles`.`ID`
												LEFT JOIN `SDatingEvents` ON `SDatingEvents`.`ID` = `SDatingParticipants`.`IDEvent`
												WHERE `SDatingParticipants`.`ID` = ". (int)$key );

						$aPlus = array();
						$aPlus['NameSDating'] = $part_arr['Title'];
						$aPlus['PlaceSDating'] = $part_arr['Place'];
						$aPlus['WhenStarSDating'] = $part_arr['EventStart'];
						$aPlus['PersonalUID'] = $part_arr['ParticipantUID'];
						$aPlus['LinkSDatingEvent'] = $site['url'] . 'events.php?action=show_info&amp;event_id=' . $event_id;
						$aPlus['MessageText'] = process_pass_data($_POST['message']);

						$mail_res = sendMail( $part_arr['Email'], $subject, $text, $part_arr['ID'], $aPlus );

						if ( !$mail_res )
							$failed_count++;
					}
				}
				if ( $failed_count > 0 )
					$show_part['error_text'] = "Failed to send {$failed_count} messages";
				else
					$show_part['error_text'] = 'All messages were successfully sent';
			}

			// list of participants
			$part_page = isset($_REQUEST['part_page']) ? (int)$_REQUEST['part_page'] : 1;
			$part_p_per_page = isset($_REQUEST['part_p_per_page']) ? (int)$_REQUEST['part_p_per_page'] : 30;
			$limit_first = (int)($part_page - 1) * $part_p_per_page;
			$part_sortby = isset($_REQUEST['sortby']) ? process_db_input($_REQUEST['sortby']) : 'Profiles.ID';
			$part_sortorder = isset($_REQUEST['sortorder']) && $_REQUEST['sortorder'] == 'DESC' ? 'DESC' : 'ASC';
			$search_filter = '';
			// add search filter if needed
			if ( $_REQUEST['search_email'] )
				$search_filter = 'AND `Profiles`.`Email` LIKE \'%'. process_db_input($_REQUEST['search_filter']) .'%\'';
			elseif ( $_REQUEST['search_nick'] )
				$search_filter = 'AND `Profiles`.`NickName` LIKE \'%'. process_db_input($_REQUEST['search_filter']) .'%\'';
			elseif ( $_REQUEST['search_id'] )
				$search_filter = 'AND `Profiles`.`ID` = '. (int)$_REQUEST['search_filter'];
			$part_profiles_res = db_res( "SELECT `Profiles`.*, `SDatingParticipants`.`ID` AS `PartID`, `SDatingParticipants`.`ParticipantUID` AS `UID` FROM `SDatingParticipants`
											LEFT JOIN `Profiles` ON `SDatingParticipants`.`IDMember` = `Profiles`.`ID`
											WHERE `SDatingParticipants`.`IDEvent` = $event_id $search_filter
											ORDER BY $part_sortby $part_sortorder
											LIMIT $limit_first, $part_p_per_page" );
			$total_arr = db_arr( "SELECT COUNT(*) FROM `SDatingParticipants`
										LEFT JOIN `Profiles` ON `SDatingParticipants`.`IDMember` = `Profiles`.`ID`
										WHERE `SDatingParticipants`.`IDEvent` = $event_id $search_filter" );
			$part_profiles_total = (int)$total_arr[0];
			$pages_num = ceil( $part_profiles_total / $part_p_per_page );
			$part_get_url = "{$_SERVER['PHP_SELF']}?action=show_part&amp;event_id={$event_id}". (isset($_REQUEST['part_p_per_page']) ? '&amp;part_p_per_page='. (int)$_REQUEST['part_p_per_page'] : '');
			$part_per_page_array = array(10, 15, 20, 30, 50, 100);
			$part_query = "SELECT `ID`, `Title`, `Description`, `Status`, `StatusMessage`, `Country`, `City`, `Place`, `PhotoFilename`, `EventStart`, `EventEnd`, `TicketSaleStart`, `TicketSaleEnd`, `ResponsibleName`, `ResponsibleEmail`, `ResponsiblePhone`, `EventSexFilter`, `EventAgeLowerFilter`, `EventAgeUpperFilter`, `EventMembershipFilter`, `TicketCountFemale`, `TicketCountMale`, `TicketPriceFemale`, `TicketPriceMale`, `ChoosePeriod`, `AllowViewParticipants` FROM `SDatingEvents` WHERE `ID` = $event_id";
		}
		break;

	// show matches of specified event
	case 'show_match':
		$event_id = (int)$_REQUEST['event_id'];
		if ( $event_id )
		{
			// delete member from chosen list
			if ( $_POST['choose_cancel'] == 'Remove from choose list' )
			{
				$delete_chosen = '';
				$delete_chooser = (int)$_POST['part_id'];
				foreach ( $_POST as $key => $value )
				{
					if ( (int)$key && $value == 'on' )
					{
						$delete_chosen .= strlen($delete_chosen) ? ','. (int)$key : (int)$key;
					}
				}
				if ( strlen($delete_chosen) )
				{
					db_res( "DELETE FROM `SDatingMatches` WHERE `IDChooser` = $delete_chooser AND `IDChosen` IN ($delete_chosen)" );
				}
			}

			// send matchmaking emails
			if ( $_POST['send_match_emails'] == 'on' )
			{
				$match_email_res = db_res( "SELECT `ParticipantTable1`.`IDMember` AS `ChooserMemberID`, `ParticipantTable2`.`IDMember` AS `ChosenMemberID`, `SDatingEvents`.`ID` AS `EventID`, `SDatingEvents`.`Title`, `SDatingEvents`.`Place`, `SDatingEvents`.`EventStart`
											FROM `SDatingMatches` AS `MatchTable1`
											LEFT JOIN `SDatingMatches` AS `MatchTable2` ON `MatchTable2`.`IDChooser` = `MatchTable1`.`IDChosen` AND `MatchTable2`.`IDChosen` = `MatchTable1`.`IDChooser`
											LEFT JOIN `SDatingParticipants` AS `ParticipantTable1` ON `ParticipantTable1`.`ID` = `MatchTable1`.`IDChooser`
											LEFT JOIN `SDatingParticipants` AS `ParticipantTable2` ON `ParticipantTable2`.`ID` = `MatchTable1`.`IDChosen`
											LEFT JOIN `SDatingEvents` ON `SDatingEvents`.`ID` = `ParticipantTable1`.`IDEvent`
											WHERE `ParticipantTable1`.`IDEvent` = $event_id
											AND `ParticipantTable2`.`IDEvent` = $event_id
											AND `MatchTable2`.`IDChooser` IS NOT NULL" );

				$subject = getParam( 't_SDatingMatch_subject' );

				$text = getParam( 't_SDatingMatch' );

				$failed_count = 0;
				while ( $match_email_arr = mysql_fetch_assoc($match_email_res) )
				{

					$chooser_arr = getProfileInfo( $match_email_arr['ChooserMemberID'] );


					$aPlus = array();
					$aPlus['NameSDating'] = $match_email_arr['Title'];
					$aPlus['PlaceSDating'] = $match_email_arr['Place'];
					$aPlus['WhenStarSDating'] = $match_email_arr['EventStart'];
					$aPlus['MatchLink'] = $site['url'] . 'profile.php?ID=' . $match_email_arr['ChosenMemberID'];
					$aPlus['LinkSDatingEvent'] = $site['url'] . 'events.php?action=show_info&amp;event_id=' . $match_email_arr['EventID'];


					$mail_res = sendMail( $chooser_arr['Email'], $subject, $text, $chooser_arr['ID'], $aPlus );

					if ( !$mail_res )
						$failed_count++;
				}
				if ( $failed_count > 0 )
					$show_match['error_text'] = "Failed to send {$failed_count} notifications";
				else
					$show_match['error_text'] = 'All notifications were successfully sent';
			}

			$match_page = isset($_REQUEST['match_page']) ? (int)$_REQUEST['match_page'] : 1;
			$match_p_per_page = isset($_REQUEST['match_p_per_page']) ? (int)$_REQUEST['match_p_per_page'] : 30;
			$limit_first = (int)($match_page - 1) * $match_p_per_page;
			$match_profiles_res = db_res( "SELECT `Profiles`.*, `SDatingParticipants`.`ID` AS `PartID`, `SDatingParticipants`.`ParticipantUID` AS `UID` FROM `SDatingParticipants`
											LEFT JOIN `Profiles` ON `SDatingParticipants`.`IDMember` = `Profiles`.`ID`
											WHERE `SDatingParticipants`.`IDEvent` = $event_id
											ORDER BY `SDatingParticipants`.`ParticipantUID` ASC
											LIMIT $limit_first, $match_p_per_page" );
			$total_arr = db_arr( "SELECT COUNT(*) FROM `SDatingParticipants`
										WHERE `SDatingParticipants`.`IDEvent` = $event_id" );
			$match_count_arr = db_arr( "SELECT COUNT(*) / 2 FROM `SDatingMatches` AS `MatchTable1`
											LEFT JOIN `SDatingMatches` AS `MatchTable2` ON `MatchTable2`.`IDChooser` = `MatchTable1`.`IDChosen` AND `MatchTable2`.`IDChosen` = `MatchTable1`.`IDChooser`
											LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`ID` = `MatchTable1`.`IDChosen`
											WHERE `SDatingParticipants`.`IDEvent` = $event_id
											AND `MatchTable2`.`IDChooser` IS NOT NULL" );
			$match_profiles_total = (int)$total_arr[0];
			$match_count_total = (int)$match_count_arr[0];
			$pages_num = ceil( $match_profiles_total / $match_p_per_page );
			$match_get_url = "{$_SERVER['PHP_SELF']}?action=show_match&amp;event_id={$event_id}". (isset($_REQUEST['match_p_per_page']) ? '&amp;match_p_per_page='. (int)$_REQUEST['match_p_per_page'] : '');
			$match_per_page_array = array(10, 15, 20, 30, 50, 100);
		}
		break;
}

// if action is 'show' then select events from the database
if ( strlen($show_query) )
{
	$show_result = db_res( $show_query );
	$show_num = mysql_num_rows( $show_result );
	$total_num_arr = db_arr( $total_query );
	$total_num = (int)$total_num_arr[0];
}

TopCodeAdmin();
ContentBlockHead("Manage events");

if ( strlen($show['error_text']) )
	echo "
<center>
	<div class=\"err\">{$show['error_text']}</div>
</center>";
?>

<center>

<script language="JavaScript" type="text/javascript">
<!--
	function updateShowControls()
	{
		document.forms['showEventsForm'].elements['show_events_between1'].disabled = !(document.getElementById('show_events_date_id').checked);
		document.getElementById('show_events_between1_choose_id').disabled = !(document.getElementById('show_events_date_id').checked);
		document.getElementById('show_events_between1_clear_id').disabled = !(document.getElementById('show_events_date_id').checked);
		document.forms['showEventsForm'].elements['show_events_between2'].disabled = !(document.getElementById('show_events_date_id').checked);
		document.getElementById('show_events_between2_choose_id').disabled = !(document.getElementById('show_events_date_id').checked);
		document.getElementById('show_events_between2_clear_id').disabled = !(document.getElementById('show_events_date_id').checked);
		document.getElementById('show_events_select_id').disabled = !(document.getElementById('show_events_country_id').checked);
	}
-->
</script>

<div style="text-align: right; padding-right: 45px; padding-bottom: 2px;">
	<a href="<?= "{$_SERVER['PHP_SELF']}?action=show_edit_form" ?>">Add new event</a>
</div>

<form id="showEventsForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="get" style="margin: 0px;">
<table class="text" cellspacing="2" cellpadding="4" width="500" border="0">
	<tr>
		<td class="section_header" nowrap>Select events to show</td>
	</tr>
	<tr>
		<td class="section_row">
			<table cellpadding="4" cellspacing="0" border="0" class="text" width="480" align="center">
				<tr>
					<td align="left" width="160" colspan="2">
						<input type="radio" name="show_events" id="show_events_all_id" class="no" value="all" style="vertical-align: middle;" onClick="javascript: updateShowControls();" <?= $_REQUEST['show_events'] == 'all' ? 'checked="checked"' : '' ?> />
						&nbsp;<label for="show_events_all_id">Show all events</label>
					</td>
				</tr>
				<tr>
					<td align="left" width="160">
						<input type="radio" name="show_events" id="show_events_date_id" class="no" value="date" style="vertical-align: middle;" onClick="javascript: updateShowControls();" <?= $_REQUEST['show_events'] == 'date' ? 'checked="checked"' : '' ?> />
						&nbsp;<label for="show_events_date_id">Show events between</label>
					</td>
					<td align="left">
						<input type="text" class="no" name="show_events_between1" id="show_events_between1_id" size="20" value="<?= isset($show['between_date1']) ? htmlspecialchars($show['between_date1']) : '' ?>" <?= $between_disabled ?> />
						&nbsp;<input type="button" id="show_events_between1_choose_id" value="Choose" <?= $between_disabled ?> />
						&nbsp;<input type="button" id="show_events_between1_clear_id" onClick="javascript: document.getElementById('show_events_between1_id').value = ''; " value="Clear" <?= $between_disabled ?> />
					</td>
				</tr>
				<tr>
					<td align="right" width="160">and</td>
					<td align="left">
						<input type="text" class="no" name="show_events_between2" id="show_events_between2_id" size="20" value="<?= isset($show['between_date2']) ? htmlspecialchars($show['between_date2']) : '' ?>" <?= $between_disabled ?> />
						&nbsp;<input type="button" id="show_events_between2_choose_id" value="Choose" <?= $between_disabled ?> />
						&nbsp;<input type="button" id="show_events_between2_clear_id" onClick="javascript: document.getElementById('show_events_between2_id').value = ''; " value="Clear" <?= $between_disabled ?> />
					</td>
				</tr>
				<tr>
					<td align="left" width="160">
						<input type="radio" name="show_events" id="show_events_country_id" class="no" value="country" style="vertical-align: middle;" onClick="javascript: updateShowControls();"  <?= $_REQUEST['show_events'] == 'country' ? 'checked="checked"' : '' ?> />
						&nbsp;<label for="show_events_country_id">Show events by country</label>
					</td>
					<td align="left">
						<select class="no" name="show_events_country" id="show_events_select_id" <?= $country_disabled ?> >
<?
	$sel_country = isset($show['country']) ? $show['country'] : getParam( 'default_country' );
	foreach ( $aPreValues['Country'] as $key => $value )
	{
		echo "
							<option value=\"{$key}\" ". ( $sel_country == $key ? 'selected="selected"' : '') ." >". _t($value['LKey']) ."</option>";
	}
?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br />
<input type="submit" class="no" value="Show" />
<input type="hidden" name="action" value="show" />
<input type="hidden" name="from" value="0" />
</form>

</center>
<?
ContentBlockFoot();
/**
 * Shows div with results of event search form
 */
if ( strlen($show_query) )
{

ContentBlockHead("Events");
?>

<center>

<script language="JavaScript" type="text/javascript">
<!--
	function navigationSubmit( fromParam )
	{
		document.forms['showEventsForm'].elements['from'].value = fromParam;
		document.forms['showEventsForm'].submit();
	}
-->
</script>
<?
	if ( $show_num == 0 )
	{
		echo "No events available";
	}
	else
	{
		echo SDPrintNavigation( (int)$_REQUEST['from'], $total_num, true );
		while ( $show_arr = mysql_fetch_assoc($show_result) )
		{
			SDPrintEventRow( $show_arr );
		}
		echo SDPrintNavigation( (int)$_REQUEST['from'], $total_num, false );
	}
?>

</center>
<?

ContentBlockFoot();
}

/**
 * Shows div with event participants information, pages navigation, controls for
 * removing members from event
 */
if ( $_REQUEST['action'] == 'show_part' && $event_id )
{
?>
		<div class="block_head">Event participants</div>
		<div class="block_outer">
			<div class="block_inner">

<?
if ( strlen($show_part['error_text']) )
	echo "
<center>
	<div class=\"err\">{$show_part['error_text']}</div>
</center>
<br />";
?>

<center>

<form id="searchProfilesForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="get" style="margin: 0px;">
<table cellpadding="2" cellspacing="2" class="panel" border="0" width="540">
	<tr>
		<td colspan="3" align="center">
			<input type="text" class="no" name="search_filter" size="60" />
		</td>
	</tr>
	<tr>
		<td align="right">
			<input type="submit" class="no" name="search_email" value="Search by Email" style="width: 150px;" />
		</td>
		<td align="center">
			<input type="submit" class="no" name="search_nick" value="Search by Nickname" style="width: 150px;" />
		</td>
		<td align="left">
			<input type="submit" class="no" name="search_id" value="Search by ID" style="width: 150px;" />
		</td>
	</tr>
</table>
<input type="hidden" name="action" value="show_part" />
<input type="hidden" name="event_id" value="<?= $event_id ?>" />
</form>

<br />

<div style="align: center;">
	<form id="upperNavigationForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="get" style="margin: 0px;">
		<input type="hidden" name="action" value="show_part" />
		<input type="hidden" name="event_id" value="<?= $event_id ?>" />
		Results:&nbsp;<b><?= ($limit_first + 1) ?></b>-<b><?= min($limit_first + $part_p_per_page, $part_profiles_total) ?></b>&nbsp;|&nbsp;Total:&nbsp;<b><?= $part_profiles_total ?></b>&nbsp;|&nbsp;Results per page:&nbsp;
		<select name="part_p_per_page" class="no" onchange="javascript: document.forms['upperNavigationForm'].submit();" style="vertical-align: middle;">
<?
	foreach ( $part_per_page_array as $per_page_elem )
	{
		echo "
			<option value=\"{$per_page_elem}\" ". ($part_p_per_page == $per_page_elem ? 'selected="selected"' : '') .">{$per_page_elem}</option>";
	}
?>
		</select>
		<br />
		Pages:&nbsp;
<?
	for ( $i = 1; $i <= $pages_num; $i++ )
	{
		if ( $i == $part_page )
			echo "[{$i}]&nbsp;";
		else
			echo "<a href=\"{$part_get_url}&amp;part_page={$i}\">{$i}</a>&nbsp;";
	}
?>
	</form>
</div>

<br />

<form id="profilesForm" action="<?= $_SERVER['PHP_SELF'] ?>?action=show_part&amp;event_id=<?= $event_id ?>" method="post" style="margin: 0px;">
<table cellpadding="2" cellspacing="1" border="0" width="540" bgcolor="#EEEEEE" class="small">
<?
	if ( $part_profiles_total == 0 )
	{
?>
	<tr>
		<td align="center" class="panel">No participants for this event</td>
	</tr>
<?
	}
	else
	{
?>
	<tr class="panel">
		<td align="center">&nbsp;</td>
		<td align="left"><a href="<?= $part_get_url ?>&amp;sortby=Profiles.ID<?= $part_sortby == 'Profiles.ID' ? ($part_sortorder == 'ASC' ? '&amp;sortorder=DESC' : '&amp;sortorder=ASC') : '' ?>">ID</a></td>
		<td align="left"><a href="<?= $part_get_url ?>&amp;sortby=NickName<?= $part_sortby == 'NickName' ? ($part_sortorder == 'ASC' ? '&amp;sortorder=DESC' : '&amp;sortorder=ASC') : '' ?>">Nickname</a></td>
		<td align="center"><a href="<?= $part_get_url ?>&amp;sortby=Sex<?= $part_sortby == 'Sex' ? ($part_sortorder == 'ASC' ? '&amp;sortorder=DESC' : '&amp;sortorder=ASC') : '' ?>">Sex</a></td>
		<td align="left">E-Mail</td>
		<td align="left"><a href="<?= $part_get_url ?>&amp;sortby=SDatingParticipants.ParticipantUID<?= $part_sortby == 'SDatingParticipants.ParticipantUID' ? ($part_sortorder == 'ASC' ? '&amp;sortorder=DESC' : '&amp;sortorder=ASC') : '' ?>">UID</a></td>
	</tr>
<?
		while ( $part_profiles_arr = mysql_fetch_assoc($part_profiles_res) )
		{
			switch ( $part_profiles_arr['Status'] )
			{
				case 'Unconfirmed':
				case 'Approval':
				case 'Rejected':
				case 'Suspended':
					$row_class = "prof_stat_{$part_profiles_arr['Status']}";
					break;
				case 'Active':
				default:
					$row_class = 'table';
			}
?>
	<tr class="<?= $row_class ?>" bgcolor="#FFFFFF">
		<td align="center" width="20"><input type="checkbox" name="<?= $part_profiles_arr['PartID'] ?>" onclick="UpdateSubmit('profilesForm');" /></td>
		<td align="left" width="30"><a href="<?= $site['url'] ?>profile.php?ID=<?= $part_profiles_arr['ID'] ?>"><?= $part_profiles_arr['ID'] ?></a></td>
		<td align="left"><?= process_line_output($part_profiles_arr['NickName']) ?></td>
		<td align="center" width="20"><?= _t('_a_'.$part_profiles_arr['Sex']) ?></td>
		<td align="left"><?= process_line_output($part_profiles_arr['Email']) ?></td>
		<td align="left"><?= process_line_output($part_profiles_arr['UID']) ?></td>
	</tr>
<?
		}
	}
?>
</table>

<table cellpadding="4" cellspacing="0" class="text" border="0">
	<tr>
		<td>
			<a href="javascript:void(null);" onclick="javascript: setCheckboxes( 'profilesForm', true ); return false;">Check all</a>&nbsp;/&nbsp;<a href="javascript:void(null);" onclick="javascript: setCheckboxes( 'profilesForm', false ); return false;">Uncheck all</a>
		</td>
		<td>With selected:</td>
		<td>
			<input type="submit" class="no" name="participant_cancel" value="Remove from event" style="width: 150px;" />
		</td>
	</tr>
	<tr>
		<td align="center" colspan="3"><textarea name="message" class="no" rows="7" cols="50"></textarea></td>
	</tr>
	<tr>
		<td align="center" colspan="3">
			<input type="submit" class="no" name="send_message" value="Send message" />
		</td>
	</tr>
</table>
<?
	if ( isset($_REQUEST['part_page']) )
		echo "
<input type=\"hidden\" name=\"part_page\" value=\"{$_REQUEST['part_page']}\" />";
	if ( isset($_REQUEST['part_p_per_page']) )
		echo "
<input type=\"hidden\" name=\"part_p_per_page\" value=\"{$_REQUEST['part_p_per_page']}\" />";
?>
</form>

<div style="align: center;">
	<form id="lowerNavigationForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="get" style="margin: 0px;">
		<input type="hidden" name="action" value="show_part" />
		<input type="hidden" name="event_id" value="<?= $event_id ?>" />
		Results:&nbsp;<b><?= ($limit_first + 1) ?></b>-<b><?= min($limit_first + $part_p_per_page, $part_profiles_total) ?></b>&nbsp;|&nbsp;Total:&nbsp;<b><?= $part_profiles_total ?></b>&nbsp;|&nbsp;Results per page:&nbsp;
		<select name="part_p_per_page" class="no" onchange="javascript: document.forms['lowerNavigationForm'].submit();" style="vertical-align: middle;">
<?
	foreach ( $part_per_page_array as $per_page_elem )
	{
		echo "
			<option value=\"{$per_page_elem}\" ". ($part_p_per_page == $per_page_elem ? 'selected="selected"' : '') .">{$per_page_elem}</option>";
	}
?>
		</select>
		<br />
		Pages:&nbsp;
<?
	for ( $i = 1; $i <= $pages_num; $i++ )
	{
		if ( $i == $part_page )
			echo "[{$i}]&nbsp;";
		else
			echo "<a href=\"{$part_get_url}&amp;part_page={$i}\">{$i}</a>&nbsp;";
	}
?>
	</form>
</div>

</center>

			</div>
		</div>
		<div class="block_foot"></div>
<?
}

/**
 * Shows div with event match information, pages navigation, controls for matchmaking email
 * sending
 */
if ( $_REQUEST['action'] == 'show_match' && $event_id )
{
?>
		<div class="block_head">Event matches</div>
		<div class="block_outer">
			<div class="block_inner">

<?
if ( strlen($show_match['error_text']) )
	echo "
<center>
	<div class=\"err\">{$show_match['error_text']}</div>
</center>
<br />";
?>

<center>

<div class="SDInfoBox">
	Total matches: <?= $match_count_total ?>
</div>
<br />
<form id="sendMatchEmailsForm" action="<?= $_SERVER['PHP_SELF'] ?>?action=show_match&amp;event_id=<?= $event_id ?>" method="post" style="margin: 0px;">
	<input type="hidden" name="send_match_emails" value="on" />
<?
	if ( isset($_REQUEST['match_page']) )
		echo "
	<input type=\"hidden\" name=\"match_page\" value=\"{$_REQUEST['match_page']}\" />";
	if ( isset($_REQUEST['match_p_per_page']) )
		echo "
	<input type=\"hidden\" name=\"match_p_per_page\" value=\"{$_REQUEST['match_p_per_page']}\" />";
?>
	<input type="submit" class="no" value="Send matchmaking emails" style="width: 200px" />
</form>

<br />

<div style="align: center;">
	<form id="upperNavigationForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="get" style="margin: 0px;">
		<input type="hidden" name="action" value="show_match" />
		<input type="hidden" name="event_id" value="<?= $event_id ?>" />
		Results:&nbsp;<b><?= ($limit_first + 1) ?></b>-<b><?= min($limit_first + $match_p_per_page, $match_profiles_total) ?></b>&nbsp;|&nbsp;Total:&nbsp;<b><?= $match_profiles_total ?></b>&nbsp;|&nbsp;Results per page:&nbsp;
		<select name="match_p_per_page" class="no" onchange="javascript: document.forms['upperNavigationForm'].submit();" style="vertical-align: middle;">
<?
	foreach ( $match_per_page_array as $per_page_elem )
	{
		echo "
			<option value=\"{$per_page_elem}\" ". ($match_p_per_page == $per_page_elem ? 'selected="selected"' : '') .">{$per_page_elem}</option>";
	}
?>
		</select>
		<br />
		Pages:&nbsp;
<?
	for ( $i = 1; $i <= $pages_num; $i++ )
	{
		if ( $i == $match_page )
			echo "[{$i}]&nbsp;";
		else
			echo "<a href=\"{$match_get_url}&match_page={$i}\">{$i}</a>&nbsp;";
	}
?>
	</form>
</div>

<br />

<script type="text/javascript" language="JavaScript">
<!--
	function makeShowHideSwitch( showFlag, showLabel, hideLabel, switchDiv )
	{
		if ( showFlag )
		{
			if ( showLabel )
				showLabel.style.display = 'none';
			if ( hideLabel )
				hideLabel.style.display = 'inline';
			if ( switchDiv )
				switchDiv.style.display = 'block';
		}
		else
		{
			if ( showLabel )
				showLabel.style.display = 'inline';
			if ( hideLabel )
				hideLabel.style.display = 'none';
			if ( switchDiv )
				switchDiv.style.display = 'none';
		}
	}
-->
</script>

<table cellpadding="2" cellspacing="1" border="0" width="540" class="text">
<?
	while ( $match_profiles_arr = mysql_fetch_assoc($match_profiles_res) )
	{
		$chosen_res = db_res( "SELECT `Profiles`.`NickName`, `SDatingParticipants`.`ParticipantUID` AS `UID`, `SDatingParticipants`.`ID` AS `PartID`,  (`MatchTable2`.`IDChooser` IS NOT NULL) AS `ChooseMatches` FROM `SDatingMatches` AS `MatchTable1`
								LEFT JOIN `SDatingMatches` AS `MatchTable2` ON `MatchTable2`.`IDChooser` = `MatchTable1`.`IDChosen` AND `MatchTable2`.`IDChosen` = `MatchTable1`.`IDChooser`
								LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`ID` = `MatchTable1`.`IDChosen`
								LEFT JOIN `Profiles` ON `Profiles`.`ID` = `SDatingParticipants`.`IDMember`
								WHERE `MatchTable1`.`IDChooser` = {$match_profiles_arr['PartID']}" );
		$chosen_num = mysql_num_rows($chosen_res);
?>
	<tr>
		<td class="table" align="left" bgcolor="#FFFFFF">
			<table cellspacing="0" cellpadding="4" border="0" width="100%" class="text">
				<tr class="SDMatchMemberHeadRow">
					<td align="left" valign="middle"><b><?= $match_profiles_arr['UID'] ?> (Nickname: <?= $match_profiles_arr['NickName'] ?>)</b></td>
					<td align="right" width="150" valign="middle">
<?
		if ( $chosen_num > 0 )
		{
?>
						<a href="javascript:void(null);" id="show<?= $match_profiles_arr['PartID'] ?>_label_id" onClick="javascript: makeShowHideSwitch( true, this, document.getElementById('hide<?= $match_profiles_arr['PartID'] ?>_label_id'), document.getElementById('match<?= $match_profiles_arr['PartID'] ?>_box_id') );" style="display: inline;">Show chosen members</a>
						<a href="javascript:void(null);" id="hide<?= $match_profiles_arr['PartID'] ?>_label_id" onClick="javascript: makeShowHideSwitch( false, document.getElementById('show<?= $match_profiles_arr['PartID'] ?>_label_id'), this, document.getElementById('match<?= $match_profiles_arr['PartID'] ?>_box_id') );" style="display: none;">Hide chosen members</a>
<?
		}
?>
					</td>
				</tr>
			</table>
			<div id="match<?= $match_profiles_arr['PartID'] ?>_box_id" class="SDMatchBox" style="display: none;">
				<form id="match<?= $match_profiles_arr['PartID'] ?>Form" action="<?= $_SERVER['PHP_SELF'] ?>?action=show_match&amp;event_id=<?= $event_id ?>" method="post" style="margin: 0px;">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
<?
		if ( $chosen_num > 0 )
		{
			while ( $chosen_arr = mysql_fetch_assoc($chosen_res) )
			{
?>
					<tr class="<?= $chosen_arr['ChooseMatches'] ? 'SDMatchMemberMatchRow' : 'SDMatchMemberNotMatchRow' ?>">
						<td align="center" width="20" valign="middle"><input type="checkbox" name="<?= $chosen_arr['PartID'] ?>" id="choose_<?= $match_profiles_arr['PartID'] ?>_<?= $chosen_arr['PartID'] ?>_id" /></td>
						<td align="left" valign="middle"><label for="choose_<?= $match_profiles_arr['PartID'] ?>_<?= $chosen_arr['PartID'] ?>_id"><?= $chosen_arr['UID'] ?> (Nickname: <?= $chosen_arr['NickName'] ?>)</label></td>
					</tr>
<?
			}
		}
		else
		{
?>
				<tr>
					<td>&nbsp;</td>
				</tr>
<?
		}
?>
				</table>
				<center>
				<table cellpadding="0" cellspacing="0" border="0" class="text" style="padding: 0px 4px;">
					<tr>
						<td>
							<a href="javascript:void(null);" onclick="javascript: setCheckboxes( 'match<?= $match_profiles_arr['PartID'] ?>Form', true ); return false;">Check all</a>&nbsp;/&nbsp;<a href="javascript:void(null);" onclick="javascript: setCheckboxes( 'match<?= $match_profiles_arr['PartID'] ?>Form', false ); return false;">Uncheck all</a>
						</td>
						<td>With selected:</td>
						<td>
							<input type="submit" class="no" name="choose_cancel" value="Remove from choose list" style="width: 180px;" />
						</td>
					</tr>
				</table>
				</center>
				<input type="hidden" name="part_id" value="<?= $match_profiles_arr['PartID'] ?>" />
				</form>
			</div>
		</td>
	</tr>
<?
	}
?>
</table>

<br />

<div style="align: center;">
	<form id="lowerNavigationForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="get" style="margin: 0px;">
		<input type="hidden" name="action" value="show_match" />
		<input type="hidden" name="event_id" value="<?= $event_id ?>" />
		Results:&nbsp;<b><?= ($limit_first + 1) ?></b>-<b><?= min($limit_first + $match_p_per_page, $match_profiles_total) ?></b>&nbsp;|&nbsp;Total:&nbsp;<b><?= $match_profiles_total ?></b>&nbsp;|&nbsp;Results per page:&nbsp;
		<select name="match_p_per_page" class="no" onchange="javascript: document.forms['lowerNavigationForm'].submit();" style="vertical-align: middle;">
<?
	foreach ( $match_per_page_array as $per_page_elem )
	{
		echo "
			<option value=\"{$per_page_elem}\" ". ($match_p_per_page == $per_page_elem ? 'selected="selected"' : '') .">{$per_page_elem}</option>";
	}
?>
		</select>
		<br />
		Pages:&nbsp;
<?
	for ( $i = 1; $i <= $pages_num; $i++ )
	{
		if ( $i == $match_page )
			echo "[{$i}]&nbsp;";
		else
			echo "<a href=\"{$match_get_url}&match_page={$i}\">{$i}</a>&nbsp;";
	}
?>
	</form>
</div>

</center>

			</div>
		</div>
		<div class="block_foot"></div>
<?
}

/**
 * Shows div with event edit form
 */
if ( $_REQUEST['action'] == 'show_edit_form' )
{
?>
		<div class="block_head">Edit Event</div>
		<div class="block_outer">
			<div class="block_inner">

<center>

<?
	if ( isset( $_REQUEST['event_id'] ) )
	{
		$event_id = (int)$_REQUEST['event_id'];
		$event_res = db_res( "SELECT `ID`, `Title`, `Description`, `Status`, `StatusMessage`, `Country`, `City`, `Place`, `PhotoFilename`, `EventStart`, `EventEnd`, `TicketSaleStart`, `TicketSaleEnd`, `ResponsibleName`, `ResponsibleEmail`, `ResponsiblePhone`, `EventSexFilter`, `EventAgeLowerFilter`, `EventAgeUpperFilter`, `EventMembershipFilter`, `TicketCountFemale`, `TicketCountMale`, `TicketPriceFemale`, `TicketPriceMale`, `ChoosePeriod`, `AllowViewParticipants` FROM `SDatingEvents` WHERE `ID` = $event_id" );
		$event_arr = mysql_fetch_assoc( $event_res );
		SDShowEditForm( false, $event_arr );
	}
	else
	{
		SDShowEditForm( true );
	}
?>

</center>

			</div>
		</div>
		<div class="block_foot"></div>
<?
}

?>
<!-- Loading Calendar JavaScript files -->
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/utils.js"></script>
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/calendar.js"></script>
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/calendar-setup.js"></script>

<!-- Loading language definition file -->
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_lang/calendar-en.js"></script>

<script type="text/javascript">
//<![CDATA[
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2099.12],
        electric          : false,
        singleClick       : true,
        inputField        : "show_events_between1_id",
        button            : "show_events_between1_choose_id",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2099.12],
        electric          : false,
        singleClick       : true,
        inputField        : "show_events_between2_id",
        button            : "show_events_between2_choose_id",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
<?

if ( $_REQUEST['action'] == 'show_edit_form' )
{
?>
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2099.12],
        electric          : false,
        singleClick       : true,
        inputField        : "event_start_id",
        button            : "start_choose_id",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2099.12],
        electric          : false,
        singleClick       : true,
        inputField        : "event_end_id",
        button            : "end_choose_id",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2099.12],
        electric          : false,
        singleClick       : true,
        inputField        : "event_sale_start_id",
        button            : "sale_start_choose_id",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2099.12],
        electric          : false,
        singleClick       : true,
        inputField        : "event_sale_end_id",
        button            : "sale_end_choose_id",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
<?
}
?>
//]]>
</script>
<?
BottomCode();
?>