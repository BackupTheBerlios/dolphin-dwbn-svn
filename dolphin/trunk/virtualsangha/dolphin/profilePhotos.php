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

require_once( BX_DIRECTORY_PATH_ROOT . 'uploadPhoto.php');    

class ProfilePhotos extends UploadPhoto
{
	function ProfilePhotos( $iProfileID )
	{
		parent::UploadPhoto( $iProfileID );
	}


	function getMediaPage( $iMediaID = 0 )
    {
        global $votes_pic;

		$ret = '';

		//$ret .= $this -> getJSCode();

		if( 0 < $this -> iMediaCount )
		{
			$iMediaID = ( 0 < $iMediaID ) ? $iMediaID : $this -> aMedia['0']['PrimPhoto'];

			$aCurPhoto = $this -> getElementArrayByID($iMediaID);
			if( empty( $aCurPhoto ) )
			{
				$sPhotoUrl = getTemplateIcon( $this -> sSexPic );
			}
			else
			{
				$ret .= '<div class="mediaTitle" id="sTitleDiv">';
					$ret .= stripcslashes( process_line_output( $aCurPhoto['med_title'] ) );
				$ret .= '</div>';
				$iPhotoID = $aCurPhoto['med_id'];
				$sPhotoUrl = $this -> sMediaUrl . 'photo_' . $aCurPhoto['med_file'];
				$iPhotoRating = $this -> getRating( $aCurPhoto['med_rating_sum'], $aCurPhoto['med_rating_count'] );
			}
		}
		else
		{
			$sPhotoUrl = getTemplateIcon( $this -> sSexPic );
		}
		
		$ret .= $this -> getJSCode( $aCurPhoto );

		$ret .= '<div class="photoBlock" id="photoKeeper">';
			$style = '
				width:' . $this -> aMediaConfig['size']['photoWidth'] . 'px;' .
				'height:' . $this -> aMediaConfig['size']['photoHeight'] . 'px;' .
				'background-image:url(' . $sPhotoUrl . ');';
			$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="' . $style . '" class="photo" alt="" id="temPhotoID" />';
			//$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="' . $style . '"                                          class="photo" alt="" onload="return setImage();" id="temPhotoID" />';
		$ret .= '</div>';

        $sJsArray = '';
        $iPhotosCount = 0;
        $sIconsList = $this -> _getIconsList($sJsArray, $iPhotosCount);

        $oVotingView = new BxTemplVotingView ('media', (int)$aCurPhoto['med_id']);
        //if( $iPhotosCount && getParam('votes_pic') == 'on' && $oVotingView->isEnabled())
        if( $iPhotosCount && $votes_pic && $oVotingView->isEnabled())
            $ret .= '<div class="votingBlock">' . $oVotingView->getBigVoting () . '</div>';

		$ret .= '<div class="iconBlock">';
		$ret .= $sIconsList;
		$ret .= '</div>';

        $ret .= '<script type="text/javascript">' . $sJsArray . '</script>';

		return $ret;
	}

	function getJSCode( $aCurPhoto, $bUseFeature = 'false' )
	{
		global $site;
		$ret = '';

		$ret .= <<<EOF
		<script type="text/javascript">
			
			if (window.attachEvent)
				window.attachEvent( "onload", onloadPhotos );
			else
				window.addEventListener( "load", onloadPhotos, false);
			
			function onloadPhotos()
            {
                hideScroll();
                if (window.oVotingmediasmall)
                {
                    oVotingmediasmall.onvote = function (fRate, iCount) 
                    {
                        oIcons[this._iObjId]["rate"] = fRate;
                        oIcons[this._iObjId]["count"] = iCount;
                    }                    
                }
                if (window.oVotingmediabig)
                {
                    oVotingmediabig.onvote = function (fRate, iCount) 
                    {
                        oPhotos[this._iObjId]["rate"] = fRate;
                        oPhotos[this._iObjId]["count"] = iCount;
                    }                    
                }
			}
			
			//hide scrollers if needed
			function hideScroll()
			{
				b = document.getElementById( "iconBlock" );
				s = document.getElementById( "scrollCont" );
				
				if( !b || !s )
					return false;
				
				if( b.parentNode.clientWidth >= b.clientWidth ) {
					s.style.display = "none";
					$( '#iconBlock' ).css( { float: 'none', position: 'static', marginRight: 'auto', marginLeft: 'auto' } );
				}
				else
					s.style.display = "block";
			}
			
			function setImage()
			{
				var imgCode;
				var oOldImg = document.getElementById("temPhotoID");
				oOldImg.style.backgroundImage = "url({$this -> sMediaUrl}photo_{$aCurPhoto['med_file']})";
				return false;
			}

			function setThumb()
			{
				if ('{$bUseFeature}' == 'true')
					return false;
				var imgCode;
				var oOldImg = document.getElementById("temThumbID");
				var oLink = document.getElementById("temThumbLink");
				
				oOldImg.style.backgroundImage = "url({$this -> sMediaUrl}photo_{$aCurPhoto['med_file']})";
				oLink.href = "{$site['url']}photos_gallery.php?ID={$this -> iProfileID}&photoID={$aCurPhoto['med_id']}";
				return false;
			}

			function changePhoto(iMediaID)
			{			
                var oOldImg = document.getElementById("temPhotoID");

				oOldImg.style.backgroundImage = "url({$this -> sMediaUrl}photo_"+oPhotos[iMediaID]["file"]+")";
                changeTitle( oPhotos[iMediaID]["title"] );

                if (oVotingmediabig)
                {
    				oVotingmediabig._iObjId = iMediaID;
	    			oVotingmediabig.setCount(oPhotos[iMediaID]["count"]);
		    		oVotingmediabig.setRate(oPhotos[iMediaID]["rate"]);
                }


				return false;
            }

			function changeThumb( iMediaID )
			{
				if ('{$bUseFeature}' == 'false') {
					var oOldImg = document.getElementById("temThumbID");
	                var oLink = document.getElementById("temThumbLink");
					oOldImg.style.backgroundImage = "url({$this -> sMediaUrl}photo_"+oIcons[iMediaID]["file"]+")";
				}
                var oTitle = document.getElementById("temPhotoTitle"); 

                oTitle.innerHTML = oIcons[iMediaID]["title"];

                if (oVotingmediasmall)
                {
    				oVotingmediasmall._iObjId = iMediaID;
	    			oVotingmediasmall.setCount(oIcons[iMediaID]["count"]);
		    		oVotingmediasmall.setRate(oIcons[iMediaID]["rate"]);
                }

				if ('{$bUseFeature}' == 'false') {
					oLink.href = "{$site['url']}photos_gallery.php?ID={$this -> iProfileID}&photoID=" + iMediaID;
				}

				return false;
			}
						
			function changeTitle( sTitle )
			{
				var oTitlDiv = document.getElementById("sTitleDiv");
				oTitlDiv.innerHTML = stripSlashes(sTitle);
            }

		</script>
EOF;

		return $ret;
	}

	function _getIconsList(&$sJsArray, &$iCountPhotos)
	{
		$ret = '';
        $sJsArray = 'var oPhotos = {';
        $iCountPhotos = 0;

		for( $i = 0; $i < $this -> aMediaConfig['max']['photo']; $i++ )
		{
			$sIconSrc = $this -> sMediaDir . 'icon_' . $this -> aMedia[$i]['med_file'];
			if( extFileExists( $sIconSrc ) )
            {
				$iPhotoRatingCount = $this -> aMedia[$i]['voting_count'] ? $this -> aMedia[$i]['voting_count'] : 0;
				$iPhotoRating = $this -> aMedia[$i]['voting_rate'] ? $this -> aMedia[$i]['voting_rate'] : 0; 
                
                $sIconUrl = $this -> sMediaUrl . 'icon_' . $this -> aMedia[$i]['med_file'];

                $atrib = "'{$this -> aMedia[$i]['med_id']}'";

                $ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['iconWidth'] . 'px; height:' . $this -> aMediaConfig['size']['iconHeight'] . 'px; background-image:url(' . $sIconUrl . '); cursor:pointer;"  alt="" class="icons" onmouseover="this.className=\'iconsHover\'" onmouseout="this.className=\'icons\'" onclick="return changePhoto(' . $atrib . ');" />';

                $sJsArray .= "'{$this -> aMedia[$i]['med_id']}' : {" . 
                    "'title' : '{$this -> aMedia[$i]['med_title']}'," . 
                    "'file' : '{$this -> aMedia[$i]['med_file']}'," . 
                    "'rate' : '{$iPhotoRating}'," . 
                    "'count' : '{$iPhotoRatingCount}'},\n";

                ++$iCountPhotos;
			}
			else
			{
				$sIconUrl = getTemplateIcon( $this -> sSexIcon );
				$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['iconWidth'] . 'px; height:' . $this -> aMediaConfig['size']['iconHeight'] . 'px; background-image:url(' . $sIconUrl . ');" alt="" class="icons" />';
			}

		}
		
		if( $iCountPhotos )
			$sJsArray = substr($sJsArray,0,-2);
		
        $sJsArray .= '}';

		return $ret;
	}
	
	// for thumbs switching
	function _getIconsList2(&$sJsArray, &$iCountPhotos)
	{

		$ret = '';
        $sJsArray = 'var oIcons = {';
        $iCountPhotos = 0;

		for( $i = 0; $i < $this -> aMediaConfig['max']['photo']; $i++ )
		{
			$sIconSrc = $this -> sMediaDir . 'icon_' . $this -> aMedia[$i]['med_file'];
			if( extFileExists( $sIconSrc ) )
            {
				$iPhotoRatingCount = $this -> aMedia[$i]['voting_count'] ? $this -> aMedia[$i]['voting_count'] : 0;
				$iPhotoRating = $this -> aMedia[$i]['voting_rate'] ? $this -> aMedia[$i]['voting_rate'] : 0; 

				$sIconUrl = $this -> sMediaUrl . 'icon_' . $this -> aMedia[$i]['med_file'];
				
                $atrib = "'{$this -> aMedia[$i]['med_id']}'";

                $ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['iconWidth'] . 'px; height:' . $this -> aMediaConfig['size']['iconHeight'] . 'px; background-image:url(' . $sIconUrl . ');"  alt="" class="icons" onclick="return changeThumb(' . $atrib . ');" />';
                $sJsArray .= "'{$this -> aMedia[$i]['med_id']}' : {" . 
                    "'title' : '{$this -> aMedia[$i]['med_title']}'," . 
                    "'file' : '{$this -> aMedia[$i]['med_file']}'," . 
                    "'rate' : '{$iPhotoRating}'," . 
                    "'count' : '{$iPhotoRatingCount}'},\n";

                ++$iCountPhotos;
			}
		}
		if( $iCountPhotos )
			$sJsArray = substr($sJsArray,0,-2);
		
        $sJsArray .= '}';

		return $ret;
	}



	function getPrimaryPhotoArray($iCouplePID = 0)
	{
		$iDestID = ($iCouplePID>0) ? $iCouplePID : $this -> aMedia['0']['PrimPhoto'];
		$aPrimPhoto = $this -> getElementArrayByID( $iDestID );
		return $aPrimPhoto;
	}
	
	function getMediaBlock( $iMediaID = 0, $bCouple = false )
	{
		global $site;
		global $votes_pic;

		$ret = '';

		if( $this -> iMediaCount > 0 )
		{
			$iMediaID = ( $iMediaID > 0 ) ? $iMediaID : $this -> aMedia['0']['PrimPhoto'];

			$aCurPhoto = $this -> getElementArrayByID($iMediaID);
			if( empty( $aCurPhoto ) )
				$sPhotoUrl = getTemplateIcon( $this -> sSexPic );
			else
			{
				$iPhotoID = $aCurPhoto['med_id'];
				$sPhotoUrl = $this -> sMediaUrl . 'photo_' . $aCurPhoto['med_file'];
			}
		}
		else
			$sPhotoUrl = getTemplateIcon( $this -> sSexPic );

		$bUseFeature = getParam("profile_gallery_feature") == "on" ? true : false;
		//$bUseFeature = true;
		$bSUseFeature = ($bUseFeature) ? 'true' : 'false';
		$ret .= $this -> getJSCode( $aCurPhoto, $bSUseFeature );

		$sFeature = '';
		if ($bUseFeature) {
			for( $i = 0; $i < $this -> aMediaConfig['max']['photo']; $i++ ) {
				$sIconSrc = $this -> sMediaDir . 'photo_' . $this -> aMedia[$i]['med_file']; 
				if( extFileExists( $sIconSrc ) ) {
					$sIconUrl = $this -> sMediaUrl . 'photo_' . $this -> aMedia[$i]['med_file'];
					//$atrib = "'{$this -> aMedia[$i]['med_id']}'";
					//$sFeatImgs .= '<a href="' . $site['url'] . 'photos_gallery.php?ID=' .  $this -> iProfileID . '">';
					$sFeatImgs .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $this -> aMediaConfig['size']['photoWidth'] . 'px; height:' . $this -> aMediaConfig['size']['photoHeight'] . 'px; background-image:url(' . $sIconUrl . ');position:absolute;"  alt="" class="icons" />';
					//$sFeatImgs .= '</a>';
				}
			}
			$sFeatTotalHeight = $this -> aMediaConfig['size']['photoHeight'] + 30;
			$sFeature = <<<EOF
<script type="text/javascript" src="{$site['url']}inc/js/jquery.dolPromoT.js"></script>
<script type="text/javascript">
	$(document).ready( function() {
		$( '#iiPPhoto' ).dolPromo( 3000, 1 );
	} );
</script>
<div style="position:relative;width:100%;height:{$sFeatTotalHeight}px;overflow:hidden;">
	<div id="iiPPhoto">
		{$sFeatImgs}
	</div>
</div>
EOF;
			$ret .= $sFeature;
		} else {
			$ret .= '<div class="photoBlock" id="photoKeeper">';
				$style = 
					'width:'  . $this -> aMediaConfig['size']['photoWidth'] . 'px;'.
					'height:' . $this -> aMediaConfig['size']['photoHeight'] . 'px;' .
					'background-image:url(' . $sPhotoUrl . ');';
				
				$ret .= '<a href="' . $site['url'] . 'photos_gallery.php?ID=' .  $this -> iProfileID . '" id="temThumbLink">';
					$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="' . $style . '" class="photo" alt="" id="temThumbID" />';
					//$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="' . $style . '" class="photo" alt="" onload="return setThumb();" id="temThumbID" />';
				$ret .= '</a>';
				
			$ret .= '</div>';
		}

		$ret .= '<div id="temPhotoTitle" class="photo_title">' . stripslashes( $aCurPhoto['med_title'] ) . '</div>';

		if ($bCouple)
			return $ret . '<div class="clear_both"></div>';

        $sJsIconsArray = '';
        $iCountPhotos = 0;
        $sIcons = $this -> _getIconsList2($sJsIconsArray, $iCountPhotos);

        $oVotingView = new BxTemplVotingView ('media', (int)$aCurPhoto['med_id']);
        if( $iCountPhotos && $votes_pic && $oVotingView->isEnabled() )
		    $ret .= $oVotingView->getSmallVoting ();
		$ret .= '<div class="clear_both"></div>';			
		
		if( strlen( $sIcons ) )
		{
			$ret .= '<div class="scrollIconContainer">';
				$ret .= '<div class="scrollCont" id="scrollCont">';
					$ret .= '<div class="scrollLeft"  onmouseover="moveScrollLeftAuto(  \'iconBlock\', 1 );" onmouseout="moveScrollLeftAuto(  \'iconBlock\', 0 );"><img src="' . getTemplateIcon('left_arrow.gif') . '"/></div>';
					$ret .= '<div class="scrollRight" onmouseover="moveScrollRightAuto( \'iconBlock\', 1 );" onmouseout="moveScrollRightAuto( \'iconBlock\', 0 );"><img src="' . getTemplateIcon('right_arrow.gif') . '"/></div>';
					$ret .= '<div class="clear_both"></div>';
				$ret .= '</div>';
				$ret .= '<div class="iconBlockCont">';
					$ret .= '<div id="iconBlock" class="iconBlock">';
						$ret .= $sIcons;
					$ret .= '</div>';
				$ret .= '</div>';
			$ret .= '</div>';
		}

        $ret .= '<script type="text/javascript">' . $sJsIconsArray . '</script>';

		return $ret;
	}

}


?>
