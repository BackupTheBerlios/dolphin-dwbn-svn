<?php

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMediaQuery.php');
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfile.php');
require_once( BX_DIRECTORY_PATH_CLASSES . "BxDolVoting.php" );

class BxDolMedia extends BxDolMistake
{
	var $sMediaType;
	var $iProfileID;
	var $aMediaConfig;

	var $sMediaDir;
	var $sMediaUrl;

	var $aMedia;
	var $iMediaCount;

	var $oMediaQuery;

	var $sSupportedExt;

    var $oDolVoting = NULL;

	function BxDolMedia()
	{
		$this -> oMediaQuery = new BxDolMediaQuery();
		$this -> aMediaConfig = $this -> getMediaConfigArray();

        $this -> sMediaDir = $this -> aMediaConfig['dir'][$this -> sMediaType] . $this -> iProfileID . '/';
        $this -> sMediaUrl = $this -> aMediaConfig['url'][$this -> sMediaType] . $this -> iProfileID . '/';        

        $this -> oDolVoting = new BxDolVoting ('media', 0, 0);
	}

	function getMediaArray()
    {
        $aMedia = $this -> oMediaQuery -> getMediaArray( $this -> iProfileID, $this -> sMediaType,  $this -> oDolVoting);
        $this -> aMedia = $this -> validateMediaArray( $aMedia );
		$this -> iMediaCount = count( $this -> aMedia );
	}

	function getActiveMediaArray()
	{
		$aMedia = $this -> oMediaQuery -> getActiveMediaArray( $this -> iProfileID, $this -> sMediaType,  $this -> oDolVoting );
		$this -> aMedia = $this -> validateMediaArray( $aMedia );
		$this -> iMediaCount = count( $this -> aMedia );
	}

	function validateMediaArray( &$aMedia )
	{
		foreach($aMedia as $iKey => $aValue)
		{
			$sMediaFile = $this -> sMediaDir . $aValue['med_file'];
			if( !extFileExists( $sMediaFile ) )
				unset( $aMedia[$iKey]);
		}

		return array_values( $aMedia );
	}

	function getMediaPage()
	{
		global $dir;
		global $tmpl;
		
		require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
		$this -> getMediaArray();

		$ret = '<div class="clear_both"></div>';
		
		$tmplBlock = file_get_contents( "{$dir['root']}templates/tmpl_{$tmpl}/media_page_block.html" );
		
		$i = 1;
		foreach($this -> aMedia as $aValue )
		{
			$block = $tmplBlock;
			
			$aReplace['media_title'] = process_line_output( $aValue['med_title'] );
			$aReplace['media_icon']  = '<img src="'.getTemplateIcon( $this -> sMediaType . '.jpg' ).'" />';
			$aReplace['nickname']    = '';
			$aReplace['download']    = '<a href="' . $this -> getMediaLink( $aValue ) . '">'._t('_download').'</a>';
			$aReplace['delete']      = '<a href="' . $_SERVER['PHP_SELF'] . '?show=' . $this -> sMediaType . '&amp;action=delete&amp;mediaID='.
				$aValue['med_id'] . '" onclick="return confirm(\''._t('_are you sure?').'\');">'._t('_delete').'</a>';
			
			foreach( $aReplace as $key => $val )
				$block = str_replace( "__{$key}__", $val, $block );
			
			$ret .= $block;
			
			if( $i++ >= $this -> aMediaConfig['max'][$this -> sMediaType] )
				break;
		}
		$ret .= '<div class="clear_both"></div>';
		
		return $ret;
	}

	function getMediaLink( $aMedia )
	{
		$sFile = $this -> aMediaConfig['dir']['tmp'] . $aMedia['med_file'];

		if( extFileExists( $sFile ) )
			return $this -> aMediaConfig['site']['tmp'] . $aMedia['med_file'];
		else
			if( copy($this -> sMediaDir . $aMedia['med_file'], $sFile ) )
				return $this -> aMediaConfig['site']['tmp'] . $aMedia['med_file'];

		return false;
	}

	function uploadMedia()
	{

		$sMediaDir = $this -> getProfileMediaDir();
		if( !$sMediaDir )
			return 'failed to open profile directory';

		$aSupoortedExt = split(',', strtolower($this -> sSupportedExt) );
		$sFileName = time();
		$aExt = pathinfo( $_FILES[$this -> sMediaType]['name'] );
		$ext = strtolower( $aExt['extension'] );

		//print_r( $_FILES);

		if( ( 0 == $_FILES[$this -> sMediaType]['error'] ) )
		{
			$iMaxFileSize = ( ( ( $this -> aMediaConfig['max'][$this -> sMediaType . 'File'] ) * 1024 ) * 1024 );
			if( $iMaxFileSize >= $_FILES[$this -> sMediaType]['size'] )
			{
				if( in_array( $ext, $aSupoortedExt) )
				{
					if( move_uploaded_file($_FILES[$this -> sMediaType]['tmp_name'], $sMediaDir . $sFileName . '.' . $ext ) )
					{
						$sFileTitle = process_db_input( htmlspecialchars_adv($_POST['title'] ));
						if( $this -> oMediaQuery -> insertMedia( $this -> iProfileID, $this -> sMediaType, $sFileName . '.' . $ext, $sFileTitle ) )
							$ret .= 'faile successfully uploaded <br>';
					}
					else
						$ret .= 'failed to upload file <br>';
				}
				else
					$ret .= 'you tried to upload not supported format <br>';
			}
			else
				$ret .= 'file to big <br>';
		}
		else
			$ret .= 'you have an error while uploading the file <br>';

		return $ret;
	}

	function insertMediaToDb( $sFileName )
	{
		$sMediaType = process_db_input($this -> sMediaType);
		$sFileName = process_db_input( $sFileName );
		$sFileTitle = process_db_input( htmlspecialchars_adv($_POST['title']));
		
		$sStatus = getParam("autoApproval_Photo") == 'on' ? 'active' : 'passive' ;
		$iResult = $this -> oMediaQuery -> insertMedia( $this -> iProfileID, $sMediaType, $sFileName, $sFileTitle, $sStatus );

		if( 0 < $iResult )
		{
			if (getParam("autoApproval_ifPhoto") != 'on' )
			{
				$sqlQuery = "UPDATE `Profiles` set `Status`='Approval' WHERE `ID`={$this -> iProfileID}";
				db_res($sqlQuery);
				createUserDataFile( $this -> iProfileID );
				reparseObjTags( 'profile', $this -> iProfileID );
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	function deleteMedia( $iMediaID )
	{
		$aMediaFile = $this -> getElementArrayByID( $iMediaID );

		$this -> oMediaQuery -> deleteMedia( $this -> iProfileID, $aMediaFile['med_id'], $this -> sMediaType );

		$sFileSrc = $this -> sMediaDir . $aMediaFile['med_file'];
		@unlink($sFileSrc);
	}

	function getProfileMediaDir()
	{
		if( extDirExists( $this -> sMediaDir ) )
		{
			return $this -> sMediaDir;
		}
		else
		{
			if( mkdir( $this -> sMediaDir ) )
			{
				chmod ($this -> sMediaDir, 0777);
				return $this -> sMediaDir;
			}
		}

		return false;
	}

	function getElementArrayByID( $iMediaID )
	{
		foreach($this -> aMedia as $aValue)
		{
			if( $iMediaID == $aValue['med_id'] )
			{
				return $aValue;
			}
		}
	}

	function getRating( $iSum, $iCount )
	{
		$iPossibleMax = $this -> aMediaConfig['max']['voting'] * $iCount;
		$iPossibleMin = $this -> aMediaConfig['min']['voting'] * $iCount;

		if( !$iSum || !$iCount )
		{
			return 0;
		}

		$iMediaRate = $iSum * 100 / $iPossibleMax;
		$iMediaRate = round( $iMediaRate );
		return $iMediaRate;
	}

	function getMediaConfigArray()
	{
		global $site, $dir;
		global $max_voting_mark;
		global $min_voting_mark;
		global $max_voting_period;

		global $max_icon_width;
		global $max_icon_height;
		global $max_thumb_width;
		global $max_thumb_height;
		global $max_photo_width;
		global $max_photo_height;

		global $max_photo_files;
		global $max_photo_size;
		global $max_media_title;
		global $min_media_title;


		$aMediaConfig = array();

		$aMediaConfig['enable']['photo'] = 1;

		$aMediaConfig['profile']['ID'] = (int)$this -> iProfileID;
		$aMediaConfig['url']['site'] = $site['url'];
		$aMediaConfig['url']['media'] = $aMediaConfig['url']['site'] . 'upload_media.php';
		$aMediaConfig['max']['mediaTitle'] = (int)$max_media_title ? $max_media_title : 150; //Max numbers of chars in media title
		$aMediaConfig['min']['mediaTitle'] = (int)$min_media_title ? $min_media_title : 3; //Min numbers of chars in media title
		$aMediaConfig['enable']['voting'] = true;
		if( $aMediaConfig['enable']['voting'] )
		{
			$aMediaConfig['min']['voting'] = (int)$min_voting_mark ? (int)$min_voting_mark : 1;
			$aMediaConfig['max']['voting'] = (int)$max_voting_mark ? (int)$max_voting_mark : 5;
			$aMediaConfig['max']['votingPeriod'] = (int)$max_voting_period ? (int)$max_voting_period : 24; //in hours
		}
		$aMediaConfig['dir']['tmp'] = $dir['tmp'];

		switch( $this -> sMediaType )
		{
			case 'photo':
				$aMediaConfig['size']['iconWidth'] = (int)$max_icon_width ? (int)$max_icon_width : 45;
				$aMediaConfig['size']['iconHeight'] = (int)$max_icon_height ? (int)$max_icon_height : 45;
				$aMediaConfig['size']['thumbWidth'] = (int)$max_thumb_width ? (int)$max_thumb_width : 110;
				$aMediaConfig['size']['thumbHeight'] = (int)$max_thumb_height ? (int)$max_thumb_height : 110;
				$aMediaConfig['size']['photoWidth'] = (int)$max_photo_width ? (int)$max_photo_width : 400;
				$aMediaConfig['size']['photoHeight'] = (int)$max_photo_height ? (int)$max_photo_height : 400;
				$aMediaConfig['dir']['photo'] = $dir['profileImage'];
				$aMediaConfig['url']['photo'] = $site['profileImage'];
				$aMediaConfig['max']['photo'] = (int)$max_photo_files ? (int)$max_photo_files : 20; // Max number of photos
				$aMediaConfig['max']['photoFile'] = $max_photo_size * 1024 * 1024; // in MB
			break;
		}

		$aMediaConfig['site'] = $site;

		return $aMediaConfig;
	}


}

?>
