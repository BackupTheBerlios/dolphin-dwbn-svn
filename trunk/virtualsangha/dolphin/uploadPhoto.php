<?php

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

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMedia.php');


class UploadPhoto extends BxDolMedia
{
	function UploadPhoto( $iProfileID )
	{
		$this -> sMediaType = 'photo';
		$this -> iProfileID = $iProfileID;
		parent::BxDolMedia();
		
		$aMember = getProfileInfo( $this -> iProfileID );
		$sSex = $aMember['Sex'];
		
		//$sSex = db_value( "SELECT `Sex` FROM `Profiles` WHERE `ID` = '{$this -> iProfileID}'" );
		
		if( $sSex == 'male' or $sSex == 'Male' )
		{
			$this -> sSexIcon = 'man_small.gif';
			$this -> sSexPic = 'man_big.gif';
		}
		elseif( $sSex == 'female' or $sSex == 'Female' )
		{
			$this -> sSexIcon = 'woman_small.gif';
			$this -> sSexPic = 'woman_big.gif';
		}
		else
		{
			$this -> sSexIcon = 'visitor_small.gif';
			$this -> sSexPic = 'visitor_big.gif';
		}
	}


	function getMediaPage( $iMediaID = '' )
	{


		//print_r( $this -> aMedia);

		$ret = '';
		//$ret .= print_r($_POST, true);

		$bShowMenu = false;
		$bWithoutJS = false;


		if( 0 < $this -> iMediaCount )
		{
			$iMediaID = ( 0 < $iMediaID ) ? $iMediaID : $this -> aMedia['0']['PrimPhoto'];

			$aCurPhoto = $this -> getElementArrayByID($iMediaID);
			if( empty( $aCurPhoto ) )
			{
				$sPhotoUrl = getTemplateIcon( $this -> sSexPic );
				$bShowMenu = false;
				$bWithoutJS = true;
			}
			else
			{
				$ret .= '<div class="mediaTitle" id="sTitleDiv">';
					$ret .= process_line_output( $aCurPhoto['med_title'] );
				$ret .= '</div>';
				$sPhotoUrl = $this -> sMediaUrl . 'photo_' . $aCurPhoto['med_file'];
				$iPhotoRating = $this -> getRating( $aCurPhoto['med_rating_sum'], $aCurPhoto['med_rating_count'] );
				$bShowMenu = true;
			}

		}
		else
		{
			$sPhotoUrl = getTemplateIcon( $this -> sSexPic );
			$bShowMenu = false;
		}

		$ret .= $this -> getJSCode( $aCurPhoto );

		$ret .= '<div class="ratingParentBlock">';
			$ret .= '<div class="ratingBlock" style="height:' . $this -> aMediaConfig['size']['photoHeight'] . 'px; width:10px;">';
				$ret .= '<div class="ratingInner" id="sPhotoRatingDiv" style="height:' . $iPhotoRating . '%;">';
					$ret .= '<div class="ratingTextBlock">';
						$ret .= '<div id="sRatingTextDiv" style="height:0%;">';
							$iPhotoRatingSum = ( 0 < $aCurPhoto['med_rating_sum'] ) ? $aCurPhoto['med_rating_sum'] : 0 ;
							$iPhotoRatingCount = ( 0 < $aCurPhoto['med_rating_count'] ) ? $aCurPhoto['med_rating_count'] : 0;
							$ret .= _t('_votes') . '&nbsp;' . $iPhotoRatingCount . '<br>';
							$ret .= _t('_ratio') . '&nbsp;' . $iPhotoRatingSum;
						$ret .= '</div>' . "\n";
					$ret .= '</div>' . "\n";
				$ret .= '</div>' . "\n";
			$ret .= '</div>' . "\n";
		$ret .= '</div>' . "\n";

		$ret .= '<div class="photoBlock" id="photoKeeper">';
			$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['photoWidth'] . 'px; height:' . $this -> aMediaConfig['size']['photoHeight'] . 'px; background-image:url(' . $sPhotoUrl . ');" class="photo" id="temPhotoID" alt="" />';
		$ret .= '</div>';

		if( $bShowMenu )
		{
			$ret .= '<div class="photoMenu">';
				$ret .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" name="actionForm">';

					$ret .= '<input type="submit" name="deletePhoto" value="' . _t( '_Delete' ) . '" onclick="return confirm(\'' . _t('_are you sure?') . '\');" />';
					$ret .= '<input type="submit" name="makePrim" value="' . _t('_Make Primary') . '" />';
					$aMember = getProfileInfo( $this -> iProfileID );
					$ret .= ($aMember['Couple']>0) ? '<input type="submit" name="makePrimCouple" value="' . _t('_Make Primary') . ' to Couple" />' : '';
					$ret .= '<input type="hidden" id="photoID" name="photoID" value="' . $aCurPhoto['med_id'] . '" />';

					$sRatable = ($aCurPhoto['rate_able']=='1') ? _t('_disable able to rate') : _t('_enable able to rate');
					$ret .= '<input type="submit" id="makeRable" name="makeRable" value="' . $sRatable . '" />';

				$ret .= '</form>';
			$ret .= '</div>';
		}
		$ret .= '<div class="clear_both"></div>';
		$ret .= '<div class="iconBlock">';
			if( $bWithoutJS )
			{
				$ret .= $this -> getIconsList();
			}
			else
			{
				$ret .= $this -> _getIconsList();
			}
		$ret .= '</div>';

		return $ret;
	}

	function getJSCode( $aCurPhoto )
	{
		$ret = '';

		$ret .= '<script type="text/javascript">
			function setImage()
			{
				var imgCode;
				var oOldImg = document.getElementById("temPhotoID");
				oOldImg.style.backgroundImage = "url(' . $this -> sMediaUrl . 'photo_' . $aCurPhoto['med_file'] . ')";
				return false;
			}

			function changePhoto( sFile, iMediaID, sTitle, iRate, rateSum, rateCount, bRable )
			{
				var oOldImg = document.getElementById("temPhotoID");
				oOldImg.style.backgroundImage = "url(' . $this -> sMediaUrl . 'photo_"+sFile+")";
				changeTitle( sTitle );
				changeRate( iRate, rateSum, rateCount );
				changeMediaID( iMediaID );
				changeRable( bRable );
				return false;
			}

			function changeTitle( sTitle )
			{
				var oTitleDiv = document.getElementById("sTitleDiv");
				oTitleDiv.innerHTML = stripSlashes(sTitle);
			}

			function changeRate( iRate, rateSum, rateCount )
			{
				var oRateDiv = document.getElementById("sPhotoRatingDiv");
				var oRateTextDiv = document.getElementById("sRatingTextDiv");

				oRateDiv.style.position = "relative";
				oRateDiv.style.height = iRate + "%";
				oRateDiv.style.top =  100 - iRate + "%";

				oRateTextDiv.innerHTML = "' . _t('_votes') . ' "+rateCount+"<br>' . _t('_ratio') . ' "+rateSum;
			}

			function changeMediaID( iMediaID )
			{
				var oPhotoID = document.getElementById("photoID");
				oPhotoID.value = iMediaID;
			}

			function changeRable( iChecked )
			{
				var oRable = document.getElementById("makeRable");
				var sRableCapt = (iChecked == 1) ? "'._t('_disable able to rate').'" : "'._t('_enable able to rate').'";
				oRable.value = sRableCapt;
			}

		</script>';

		return $ret;
	}


	function _getIconsList()
	{
		for( $i = 0; $i < $this -> aMediaConfig['max']['photo']; $i++ )
		{
			$sIconSrc = $this -> sMediaDir . 'icon_' . $this -> aMedia[$i]['med_file'];
			if( extFileExists( $sIconSrc ) )
			{
				$iPhotoRatingSum = ( 0 < $this -> aMedia[$i]['med_rating_sum'] ) ? $this -> aMedia[$i]['med_rating_sum'] : 0 ;
				$iPhotoRatingCount = ( 0 < $this -> aMedia[$i]['med_rating_count'] ) ? $this -> aMedia[$i]['med_rating_count'] : 0 ;
				$iPhotoRating = $this -> getRating( $iPhotoRatingSum, $iPhotoRatingCount );
				$sIconUrl = $this -> sMediaUrl . 'icon_' . $this -> aMedia[$i]['med_file'];

				//function changePhoto( sFile, imgDiv, sTitle, titleDiv, iRate, rateDiv)
				$sRableVal = ($this -> aMedia[$i]['rate_able']=='1') ? '1' : '0';

				$atrib = '
					\'' . $this -> aMedia[$i]['med_file']  . '\',
					' . $this -> aMedia[$i]['med_id'] . ',
					\'' . process_line_output( addslashes($this -> aMedia[$i]['med_title'] ) ) . '\',
					' . $iPhotoRating . ',
					' . $iPhotoRatingSum .  ',
					'  . $iPhotoRatingCount . ',
					\''  . $sRableVal . '\'
				';
				$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['iconWidth'] . 'px; height:' . $this -> aMediaConfig['size']['iconHeight'] . 'px; background-image:url(' . $sIconUrl . '); cursor:pointer;"  alt="" class="icons" onmouseover="this.className=\'iconsHover\'" onmouseout="this.className=\'icons\'" onclick="return changePhoto(' . $atrib . ');" />';
			}
			else
			{
				$sIconUrl = getTemplateIcon( $this -> sSexIcon );
				$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['iconWidth'] . 'px; height:' . $this -> aMediaConfig['size']['iconHeight'] . 'px; background-image:url(' . $sIconUrl . ');" alt="" class="icons" />';
			}
		}
		return $ret;
	}

	function getIconsList()
	{
		$ret = '';

		for( $i = 0; $i < $this -> aMediaConfig['max']['photo']; $i++ )
		{
			$sIconSrc = $this -> sMediaDir . 'icon_' . $this -> aMedia[$i]['med_file'];
			if( extFileExists( $sIconSrc ) )
			{
				$sIconUrl = $this -> sMediaUrl . 'icon_' . $this -> aMedia[$i]['med_file'];
				$ret .= '<a href="' . $this -> aMediaConfig['url']['media'] . '?photoID=' . $this -> aMedia[$i]['med_id'] . '">';
					$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['iconWidth'] . 'px; height:' . $this -> aMediaConfig['size']['iconHeight'] . 'px; background-image:url(' . $sIconUrl . '); cursor:pointer;"  alt="" class="icons" onmouseover="this.className=\'iconsHover\'" onmouseout="this.className=\'icons\'" />';
				$ret .= '</a>';
			}
			else
			{
				$sIconUrl = getTemplateIcon( $this -> sSexIcon );
				$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['iconWidth'] . 'px; height:' . $this -> aMediaConfig['size']['iconHeight'] . 'px; background-image:url(' . $sIconUrl . ');" alt="" class="icons" />';
			}
		}
		return $ret;
	}

	function validateMediaArray( $aMedia )
	{
		foreach( $aMedia as $iKey => $aValue )
		{
			$sIconSrc = $this -> sMediaDir . 'icon_' . $aValue['med_file'];
			$sThumbSrc = $this -> sMediaDir . 'thumb_' . $aValue['med_file'];
			$sPhotoSrc = $this -> sMediaDir . 'photo_' . $aValue['med_file'];

			if( !extFileExists( $sIconSrc ) || !extFileExists( $sThumbSrc ) || !extFileExists( $sPhotoSrc ) )
			{
				if( $aValue['med_id'] == $aValue['PrimPhoto'] )
				{
					$this -> oMediaQuery -> resetPrimPhoto( $this -> iProfileID );
				}
				unset( $aMedia[$iKey]);
			}

		}

		return array_values( $aMedia );
	}

	function uploadMedia()
	{
		global $dir;
		$sMediaDir = $this -> getProfileMediaDir();

		if( !$sMediaDir )
		{
			return false;
		}
		$sFileName = time();

		$ext = moveUploadedImage( $_FILES, 'photo', $sMediaDir . $sFileName, $this -> aMediaConfig['max']['photoFile'], false );
		
		if( ( 0 == $_FILES[$this -> sMediaType]['error'] ) )
		{
			if ( getParam( 'enable_watermark' ) == 'on' )
			{
				$iTransparent = getParam( 'transparent1' );
				$sWaterMark = $dir['profileImage'] . getParam( 'Water_Mark' );
										
				if (  strlen(getParam( 'Water_Mark' )) && file_exists($sWaterMark) )
				{
					$sFile = $sMediaDir . $sFileName . $ext;			
					applyWatermark( $sFile, $sFile, $sWaterMark, $iTransparent );
				}
			}
			
			if( strlen( $ext ) && !(int)$ext )
			{
				imageResize( $sMediaDir . $sFileName . $ext, $sMediaDir . 'icon_' . $sFileName . $ext, $this -> aMediaConfig['size']['iconWidth'], $this -> aMediaConfig['size']['iconHeight'], true );
				imageResize( $sMediaDir . $sFileName . $ext, $sMediaDir . 'thumb_' . $sFileName . $ext, $this -> aMediaConfig['size']['thumbWidth'], $this -> aMediaConfig['size']['thumbHeight'], true );
				imageResize( $sMediaDir . $sFileName . $ext, $sMediaDir . 'photo_' . $sFileName . $ext, $this -> aMediaConfig['size']['photoWidth'], $this -> aMediaConfig['size']['photoHeight'], true );
				
				chmod( $sMediaDir . 'icon_'  . $sFileName . $ext, 0644 );
				chmod( $sMediaDir . 'thumb_' . $sFileName . $ext, 0644 );
				chmod( $sMediaDir . 'photo_' . $sFileName . $ext, 0644 );
				
				$this -> insertMediaToDb( $sFileName . $ext );
				if( 0 == $this -> iMediaCount || $this -> aMedia['0']['PrimPhoto'] == 0  )
				{
					$iLastID = mysql_insert_id();
					$this -> oMediaQuery -> setPrimaryPhoto( $this -> iProfileID, $iLastID );
				}
	
	
				@unlink( $sMediaDir . $sFileName . $ext );
			}
		}
	}

	function deleteMedia( $iPhotoID )
	{
		$aPhotos = $this -> getElementArrayByID( $iPhotoID );
		$sIconSrc = $this -> sMediaDir . 'icon_' . $aPhotos['med_file'];
		$sThumbSrc = $this -> sMediaDir . 'thumb_' . $aPhotos['med_file'];
		$sPhotoSrc = $this -> sMediaDir . 'photo_' . $aPhotos['med_file'];

		$this -> oMediaQuery -> deleteMedia( $this -> iProfileID, $iPhotoID, $this -> sMediaType );
		if( $aPhotos['med_id'] == $aPhotos['PrimPhoto'] )
		{
			$this -> oMediaQuery -> resetPrimPhoto( $this -> iProfileID );
		}

		@unlink( $sIconSrc );
		@unlink( $sThumbSrc );
		@unlink( $sPhotoSrc );
	}

	function makePrimPhoto( $iPhotoID, $bCouple = false )
	{
		if ($bCouple>0) {
			$aMember = getProfileInfo( $this -> iProfileID );
			$sDestID = $aMember['Couple'];
		} else {
			$sDestID = $this->iProfileID;
		}
		if ($sDestID>0)
			$this -> oMediaQuery -> setPrimaryPhoto( $sDestID, $iPhotoID );
		createUserDataFile( $sDestID );
	}

	function makeRablePhoto( $iPhotoID ) {
		$this->oMediaQuery->setRablePhoto($iPhotoID);
	}

}

?>