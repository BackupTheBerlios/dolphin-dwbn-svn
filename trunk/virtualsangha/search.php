<?php

require_once( './inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'members.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileFields.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfilesController.php' );

$bEnZipSearch = getParam("enable_zip_loc") == "on" ? 1 : 0;
if ( $bEnZipSearch )
	require_once( BX_DIRECTORY_PATH_INC . 'RadiusAssistant.inc' );

$_page['name_index'] = 4;
$_page['css_name']   = 'search.css';

check_logged();


// get search mode
if( $_REQUEST['search_mode'] )
	$sSearchMode = $_REQUEST['search_mode'];
else
	$sSearchMode = 'simple';

switch( $_REQUEST['search_mode'] ) {
	case 'quick':
		$iPFArea = 10;
		$_page['header_text'] = $sPageHeader = _t( '_Quick Search' );
	break;
	
	case 'adv':
		$iPFArea = 11;
		$_page['header_text'] = $sPageHeader = _t( '_Advanced Search' );
	break;
	
	default:
		$iPFArea = 9;
		$sSearchMode = 'simple';
		$_page['header_text'] = $sPageHeader = _t( '_Simple Search' );
}


//collect inputs
$oPF = new BxDolProfileFields($iPFArea);
$aRequestParams = $oPF -> collectSearchRequestParams();

if( isset( $_REQUEST['ID'] ) and (int)$_REQUEST['ID'] )
	$aRequestParams['ID'] = (int)$_REQUEST['ID'];

if( isset( $_REQUEST['NickName'] ) and trim( $_REQUEST['NickName'] ) )
	$aRequestParams['NickName'] = trim( process_pass_data( $_REQUEST['NickName'] ) );

if( isset( $_REQUEST['Tags'] ) and trim( $_REQUEST['Tags'] ) )
	$aRequestParams['Tags'] = trim( process_pass_data( $_REQUEST['Tags'] ) );

if( isset( $_REQUEST['distance'] ) and (int)$_REQUEST['distance'] )
	$aRequestParams['distance'] = (int)$_REQUEST['distance'];

// start page generation
ob_start();

$bShowForms = false;
//echoDbg($aRequestParams);
if( !empty( $aRequestParams ) or $_REQUEST['online_only'] )
	PageCodeSearchResult( $aRequestParams );
else {
	PageCodeSearchForm();
	$bShowForms = true;
}


$_ni = $_page['name_index'];

$_page_cont[$_ni]['page_main_code']   = ob_get_clean();

$_page_cont[$_ni]['search_by_id']     = $bShowForms ? PageCodeSearchByID()   : '';
$_page_cont[$_ni]['search_by_nick']   = $bShowForms ? PageCodeSearchByNick() : '';
$_page_cont[$_ni]['search_by_tag']    = $bShowForms ? PageCodeSearchByTag()  : '';

PageCode();


function PageCodeSearchForm() {
	global $oPF;
	global $sPageHeader;
	global $sSearchMode;
	
	ob_start();
	?>
<form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
	<table class="search_form" cellspacing="0">
	<?
	
	foreach( $oPF -> aBlocks as $aBlock ) {
		?>
		<tr class="search_form_block">
			<th colspan="2"><?= _t( $aBlock['Caption'] ) ?></th>
		</tr>
		<?
		
		foreach( $aBlock['Items'] as $aItem ) {
			?>
		<tr class="search_form_row">
			<td class="search_form_caption"><?= _t( $aItem['Caption'] ) ?>:</td>
			<td class="search_form_value">
			<?
		
			
			//draw the control
			switch( $aItem['Type'] ) {
				case 'text':
				case 'area':
					?>
					<input type="text" name="<?= $aItem['Name'] ?>" class="input_text" />
					<?
				break;
				
				case 'date':
				case 'range':
				case 'num':
					echo _t( '_From' );
					?>
					<input type="text" name="<?= $aItem['Name'] ?>[0]" class="input_date" />
					<?
					echo _t( '_To' );
					?>
					<input type="text" name="<?= $aItem['Name'] ?>[1]" class="input_date" />
					<?
				break;
				
				case 'select_one':
				case 'select_set':
					switch ( $aItem['Control'] ) {
						case 'select':
							?>
					<select name="<?= $aItem['Name'] ?>[]" multiple="multiple" class="input_select">
						<?= SelectOptions( $aItem['Name'] ) ?>
					</select>
							<?
						break;
						
						case 'radio':
						case 'checkbox':
							$aValues = getFieldValues( $aItem['Name'] );
							
							foreach( $aValues as $sKey => $sValue ) {
								?>
					<input type="checkbox" name="<?= $aItem['Name'] ?>[]" value="<?= $sKey ?>" id="<?= $aItem['Name'] ?>_<?= $sKey ?>" />
					<label for="<?= $aItem['Name'] ?>_<?= $sKey ?>"><?= _t( $sValue ) ?></label>
								<?
							}
						break;
					}
				break;
				
				case 'bool':
					?>
					<input type="checkbox" name="<? $aItem['Name'] ?>" value="1" />
					<?
				break;
				
				case 'system':
					switch( $aItem['Name'] ) {
						case 'Couple':
							?>
					<input type="checkbox" name="Couple[0]" value="1" id="Couple_0" />
					<label for="Couple_0"><?= _t( '_Single' ) ?></label>
					<input type="checkbox" name="Couple[1]" value="1" id="Couple_1" />
					<label for="Couple_1"><?= _t( '_Couple' ) ?></label>
							<?
						break;
						
						case 'Keyword':
							?>
					<input type="text" name="<?= $aItem['Name'] ?>" class="input_text" />
							<?
						break;
						
						case 'Location':
							//echo 'Not implemented yet';
							$sLivingWithinC = _t("_living within");
							$sMilesC = _t("_miles");
							$sKmC = _t("_kilometers");
							$sFromZipC = _t("_from zip/postal code");

							$sRet = <<<EOF
<table class=small cellspacing=3 cellpadding=0 border="0">
<tr>
<td>
	&nbsp;{$sLivingWithinC}&nbsp;
    <input class=no type=text name="distance"  size=12 />
<select name="metric">
	<option selected="selected" value="miles">{$sMilesC}</option>
	<option value="km">{$sKmC}</option>
</select>
	&nbsp;{$sFromZipC}&nbsp;
    <input class=no type=text name=zip size=12 />
</td>
</tr>
</table>
EOF;
						echo $sRet;

						break;
					}
				break;
				
			}

			
			?>
			</td>
		</tr>
			<?
		}
	}
	
	?>
		<tr>
			<td class="search_form_submit_row" colspan="2">
				<input type="checkbox" name="online_only" id="online_only" />
				<label for="online_only"><?= _t( '_online only' ) ?></label>
				<input type="checkbox" name="photos_only" id="photos_only" />
				<label for="photos_only"><?= _t( '_With photos only' ) ?></label>
				<input type="submit" value="<?= _t( '_Fetch' ) ?>" />
			</td>
		</tr>
	</table>
</form>
	<?
	
	echo DesignBoxContentBorder( $sPageHeader, ob_get_clean() );
}

function PageCodeSearchByID()
{
	ob_start();
	?>
    <div class="search_by_id">
		<form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
	    	<input type="text" class="input_by_id" name="ID" />
			<br />
			<input type="submit" class="input_submit" value="<?= _t( '_Fetch' ) ?>" />
	    </form>
    </div>
	<?php

	return DesignBoxContentBorder( _t( '_Search by ID' ), ob_get_clean() );
}

function PageCodeSearchByNick()
{
	ob_start();
	?>
    <div class="search_by_nick">
	    <form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
	    	<input type="text" class="input_by_nick" name="NickName" />
	    	<br />
			<input type="submit" value="<?= _t( '_Fetch' ) ?>" />
	    </form>
	</div>
	<?php

    return DesignBoxContentBorder( _t( '_Search by Nickname' ), ob_get_clean() );
}

function PageCodeSearchByTag()
{
	ob_start();
	?>
    <div class="search_by_tag">
	    <form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
	    	<input type="text" class="input_by_tag" name="Tags" />
	    	<br />
			<input type="submit" value="<?= _t( '_Fetch' ) ?>" />
	    </form>
	</div>
	<?php

    return DesignBoxContentBorder( _t( '_Search by Tag' ), ob_get_clean() );
}

function PageCodeSearchResult( $aParams ) {
	global $oPF;
	global $dir;
	global $tmpl;
	global $bEnZipSearch;

	$sQuery = 'SELECT DISTINCT IF( `Profiles`.`Couple`=0, `Profiles`.`ID`, IF( `Profiles`.`Couple`>`Profiles`.`ID`, `Profiles`.`ID`, `Profiles`.`Couple` ) ) AS `ID` FROM `Profiles` ';
	$sJoin  = '';
	$aWhere = array();
	
	
	$aMyBlocks = $oPF -> aBlocks;
	$aMyBlocks['addSpecial'] = array( 'Items' => array(
		$oPF -> aCache[100][0]['Items'][1], //add id
		$oPF -> aCache[100][0]['Items'][2], //add nickname
		$oPF -> aCache[100][0]['Items'][38] //add tags
	) );
	
	//collect where request array
	foreach( $aMyBlocks as $iBlockID => $aBlock ) {
		foreach( $aBlock['Items'] as $aItem ) {
			if( !isset( $aParams[ $aItem['Name'] ] ) )
				continue;
			
			if( $iBlockID != 'addSpecial' and ( $aItem['Name'] == 'ID' or $aItem['Name'] == 'NickName' or $aItem['Name'] == 'Tags' ) )
				continue; // skip collecting id, nick and tags for regular blocks, only in special
			
			$sItemName = $aItem['Name'];
			$mValue    = $aParams[$sItemName];
			
			switch( $aItem['Type'] ) {
				case 'text':
				case 'area':
					if( $sItemName == 'Tags' ) {
						$sJoin .= " INNER JOIN `Tags` ON (`Tags`.`Type` = 'profile' AND `Tags`.`ID` = `Profiles`.`ID`) ";
						$aWhere[] = "`Tags`.`Tag` = '" . addslashes($mValue) . "'";
					} else
						$aWhere[] = "`Profiles`.`$sItemName` LIKE '%" . addslashes($mValue) . "%'";
				break;
				
				case 'num':
					$aWhere[] = "`Profiles`.`$sItemName` >= {$mValue[0]} AND `Profiles`.`$sItemName` <= {$mValue[1]}";
				break;
				
				case 'date':
					$iMin = floor( $mValue[0] * 365.25 ); //for leap years
					$iMax = floor( $mValue[1] * 365.25 );
					
					$aWhere[] = "DATEDIFF( NOW(), `Profiles`.`$sItemName` ) >= $iMin AND DATEDIFF( NOW(), `Profiles`.`$sItemName` ) <= $iMax";
					
					//$aWhere[] = "DATE_ADD( `$sItemName`, INTERVAL {$mValue[0]} YEAR ) <= NOW() AND DATE_ADD( `$sItemName`, INTERVAL {$mValue[1]} YEAR ) >= NOW()"; //is it correct statement?
				break;
				
				case 'select_one':
					$sValue = implode( ',', $mValue );
					$aWhere[] = "FIND_IN_SET( `Profiles`.`$sItemName`, '" . addslashes($sValue) . "' )";
				break;
				
				case 'select_set':
					$aSet = array();
					
					foreach( $mValue as $sValue ) {
						$sValue = addslashes( $sValue );
						$aSet[] = "FIND_IN_SET( '$sValue', `Profiles`.`$sItemName` )";
					}
					
					$aWhere[] = '( ' . implode( ' OR ', $aSet ) . ' )';
				break;
				
				case 'range':
					//impl
				break;
				
				case 'bool':
					$aWhere[] = "`Profiles`.`$sItemName'";
				break;
				
				case 'system':
					switch( $aItem['Name'] ) {
						case 'Couple':
							if($mValue == '-1') {
							}
							elseif( $mValue )
								$aWhere[] = "`Profiles`.`Couple` > `Profiles`.`ID`";
							else
								$aWhere[] = "`Profiles`.`Couple` = 0";
						break;
						
						case 'Location':
							$aFields = explode( "\n", $aItem['Extra'] );
							$aKeyw = array();
							$sValue = addslashes( $mValue );
							
							foreach( $aFields as $sField )
								$aKeyw[] = "`Profiles`.`$sField` LIKE '%$sValue%'";
							
							$aWhere[] = '( ' . implode( ' OR ', $aKeyw ) . ')';
						break;
						
						case 'ID':
							$aWhere[] = "`ID` = $mValue";
						break;
					}
				break;
			}
		}
	}

	if ($bEnZipSearch && $aParams['distance'] > 0) {
		$sZip = htmlspecialchars_adv($_REQUEST['zip']);
		$iDistance = (int)$aParams['distance'];
		$sMetric = htmlspecialchars_adv($_REQUEST['metric']);

		$zip = process_db_input( strtoupper( str_replace(' ', '', $zip) ), 1);
		$aZipInfo = db_arr("SELECT `Latitude`, `Longitude` FROM `ZIPCodes` WHERE REPLACE(`ZIPCode`,' ','') = '{$sZip}'");
		//echoDbg($aZipInfo);
		if ( $aZipInfo ) {
			// ZIP code exists
			$miles2km = 0.7; // miles/kilometers ratio

			$Miles = $sMetric == "km" ? $iDistance * $miles2km : $iDistance;
			$Latitude = $aZipInfo["Latitude"];
			$Longitude = $aZipInfo["Longitude"];

			$zcdRadius = new RadiusAssistant( $Latitude, $Longitude, $Miles );
			//echoDbg($zcdRadius);
			$minLat = $zcdRadius->MinLatitude();
			$maxLat = $zcdRadius->MaxLatitude();
			$minLong = $zcdRadius->MinLongitude();
			$maxLong = $zcdRadius->MaxLongitude();

			$sJoin .= " LEFT JOIN `ZIPCodes` ON UPPER( REPLACE(`Profiles`.`zip`, ' ', '') ) = REPLACE(`ZIPCodes`.`ZIPCode`,' ', '') ";
			$aWhere[] = "`ZIPCodes`.`ZIPCode` IS NOT NULL AND `ZIPCodes`.`Latitude` >= {$minLat} AND `ZIPCodes`.`Latitude` <= {$maxLat} AND `ZIPCodes`.`Longitude` >= {$minLong} AND `ZIPCodes`.`Longitude` <= {$maxLong} ";
		}
	}

	// collect query string
	$aWhere[] = "`Profiles`.`Status` = 'Active'";
	
	// add online only
	if( $_REQUEST['online_only'] ) {
		$iOnlineTime = getParam( 'member_online_time' );
		$aWhere[] = "DATE_ADD( `DateLastNav`, INTERVAL $iOnlineTime MINUTE ) >= NOW()";
	}
	
	if( $_REQUEST['photos_only'] )
		$aWhere[] = "`Profiles`.`PrimPhoto`";

	$aWhere[] = "(`Profiles`.`Couple`='0' OR `Profiles`.`Couple`>`Profiles`.`ID`)";
	
	$sWhere = ' WHERE ' . implode( ' AND ', $aWhere );
	
	//collect the whole query string
	$sQuery = $sQuery . $sJoin . $sWhere;
	
	//echo $sQuery;
	
	//make search
	$rProfiles = db_res( $sQuery );
	
	$aProfiles = array();
	while ($aProfile = mysql_fetch_assoc($rProfiles)) {
		$aProfiles[] = $aProfile['ID'];
	}
	
	$iCountProfiles = count( $aProfiles );
	
	if( !$iCountProfiles ) {
		echo '<div class="no_result"><div>' .  _t("_NO_RESULTS") . '</div></div>';
	} else {
		//collect pagination
		$iCurrentPage    = isset( $_GET['page']         ) ? (int)$_GET['page']         : 1;
		$iResultsPerPage = isset( $_GET['res_per_page'] ) ? (int)$_GET['res_per_page'] : 10;
		
		if( $iCurrentPage < 1 )
			$iCurrentPage = 1;
		if( $iResultsPerPage < 1 )
			$iResultsPerPage = 10;
		
		$iTotalPages = ceil( $iCountProfiles / $iResultsPerPage );
		
		if( $iTotalPages > 1 ) {
			if( $iCurrentPage > $iTotalPages )
				$iCurrentPage = $iTotalPages;
			
			$aOutputProfiles      = array_slice( $aProfiles, ( $iCurrentPage - 1 ) * $iResultsPerPage, $iResultsPerPage );
			$iCountOutputProfiles = count( $aOutputProfiles );
			
			$iFromResults = ( ( $iCurrentPage - 1 ) * $iResultsPerPage ) + 1;
			
			$sPagination = genSearchPagination( $iTotalPages, $iCurrentPage, $iResultsPerPage );
		} else {
			$iFromResults = 1;
			$aOutputProfiles      = $aProfiles;
			$iCountOutputProfiles = $iCountProfiles;
			$sPagination = '';
		}
		
		$iToResults   = ( $iFromResults - 1 ) + $iCountOutputProfiles;
		
		$sShowingResults = '<div class="showingResults">' . _t( '_Showing results:', $iFromResults, $iToResults, $iCountProfiles ) . '</div>';
		
		echo $sPagination;
		echo $sShowingResults;
		
		//output search results
		$sTemplSearch = file_get_contents( "{$dir['root']}templates/tmpl_{$tmpl}/searchrow.html" );
		
		foreach( $aOutputProfiles as $iProfID ) {
			$aProfileInfo = getProfileInfo( $iProfID );
			
			if ($aProfileInfo['Couple'] > 0) {
				$aProfileInfoC = getProfileInfo( $aProfileInfo['Couple'] );
				echo PrintSearhResult( $aProfileInfo, $sTemplSearch, 1, true, $aProfileInfoC );
			} else {
				echo PrintSearhResult( $aProfileInfo, $sTemplSearch );
			}
		}
		
		echo $sShowingResults;
		echo $sPagination;
	}
}

function genSearchPagination( $iTotalPages, $iCurrentPage, $iResultsPerPage ) {
	$aGetParams = $_GET;
	unset( $aGetParams['page'] );
	unset( $aGetParams['res_per_page'] );
	
	$sRequestString = collectRequestString( $aGetParams );
	$sRequestString = $_SERVER['PHP_SELF'] . '?' . substr( $sRequestString, 1 );
	
	$sPaginTmpl      = $sRequestString . '&res_per_page=' . $iResultsPerPage . '&page={page}';
	$sResPerPageTmpl = $sRequestString . '&res_per_page={res_per_page}';
	
	$sPagination = genResPerPage( array(10,20,50,100), $iResultsPerPage, $sResPerPageTmpl );
	$sPagination .=  genPagination( $iTotalPages, $iCurrentPage, $sPaginTmpl );
	
	return $sPagination;
}

function collectRequestString( $aGetParams, $sKeyPref = '', $sKeyPostf = '' ) {
	if( !is_array( $aGetParams ) )
		return '';
	
	$sRet = '';
	foreach( $aGetParams as $sKey => $sValue ) {
		if( $sValue === '' )
			continue;
		
		if( !is_array($sValue) ) {
			$sRet .= '&' . urlencode( $sKeyPref . $sKey . $sKeyPostf ) . '=' . urlencode( process_pass_data( $sValue ) );
		} else {
			$sRet .= collectRequestString( $sValue, "{$sKeyPref}{$sKey}{$sKeyPostf}[", "]" ); //recursive call
		}
	}
	
	return $sRet;
}