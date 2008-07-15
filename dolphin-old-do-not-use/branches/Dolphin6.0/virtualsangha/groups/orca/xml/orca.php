<?
/**
*                            Orca Interactive Forum Script
*                              ---------------
*     Started             : Mon Mar 23 2006
*     Copyright           : (C) 2007 BoonEx Group
*     Website             : http://www.boonex.com
* This file is part of Orca - Interactive Forum Script
* GPL
**/
    

/**
 * Main integration file
 */ 

        error_reporting(E_ALL & ~E_NOTICE);

        chdir ('..');        
		
        $_COOKIE['memberID'] = $_GET['memberID'];
        $_COOKIE['memberPassword'] = $_GET['memberPassword'];

        $_COOKIE['adminID'] = $_GET['adminID'];
        $_COOKIE['adminPassword'] = $_GET['adminPassword'];

        require_once( './inc/header.inc.php'); // orca config file
        require_once( './../../inc/header.inc.php'); // dolphin config file

        require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
        require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
        require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
        require_once( BX_DIRECTORY_PATH_INC . 'groups.inc.php' );

        if ( !( $logged['admin'] = member_auth( 1, false ) ) )
        	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		        if ( !( $logged['aff'] = member_auth( 2, false )) )
                    $logged['moderator'] = member_auth( 3, false );

       if( $logged['admin'] )
            $who = 'admin';
        elseif( $logged['member'] )
            $who = 'member';
        else
            $who = '';

		header ("content-type: text/xml");

		echo '<?xml version="1.0" encoding="utf-8"?>';

		echo '<bxforum>';
		echo '<time>' . time () . '</time>';
		
		$action   = $_GET['action'];
		$user     = $_GET['user'];
						
		switch ($action)
		{
			/**
			 * get logged in user (initial information about logged in user must be provided in url)
			 */ 
			case 'login_user':

				//  check login and password

                $user = '';

                if ($logged['member'])
                    $user = getNickName(getID((int)$_COOKIE['memberID']));
                elseif ($logged['admin'])
                    $user = 'admin';


				echo <<<EOF
            <login_user>$user</login_user>
EOF;
				break;

			
			/**
			 * get user info
			 */
			case 'user_info':

				if (!$user) echo '<false />' ;

				$special_user = isSpecialOrcaUser ($user);
                    $orca_admin = ( $who == 'admin' ) ? 1 : 0;

                    require_once( BX_DIRECTORY_PATH_ROOT . 'inc/utils.inc.php' );
                    require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );                    
					
                    $oProfile = new BxDolProfile ($user);
                    $userID = $oProfile->_iProfileID;
				
                     $av = $gConf['url']['icon'] .'basic.gif';
                     $profile_url = $gConf['url']['base'] . "?action=goto&amp;amp;user=$user";
	
				if ($orca_admin)
				{
                    $av = $gConf['url']['icon'] . 'admin.gif';
				}
				elseif ($userID)
				{					
                    $oPhoto = new ProfilePhotos( $userID );
                    $oPhoto -> getMediaArray();
                    $aFile = $oPhoto -> getPrimaryPhotoArray();
					
                    if( extFileExists( $oPhoto -> sMediaDir . 'icon_' . $aFile['med_file'] ) )
                        $av = $oPhoto -> sMediaUrl . 'icon_' . $aFile['med_file'];
                    elseif($special_user )
                        $av = $gConf['url']['icon'] .'special.gif';                    
                    
					$profile_url = $site['url'] . 'profile.php?ID=' . $userID;
				}
					
                // Ray integration [begin]

                $iId = $userID;
                $sPassword = md5(getPassword($iId));
                $bEnableRay = (getParam( 'enable_ray' ) == 'on');
                $check_res = checkAction ($iId, ACTION_ID_USE_RAY_IM);

                $aRay = '<ray_on>0</ray_on>';
                if ($bEnableRay && $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)                    
                    $aRay = '<ray_on>1</ray_on><ray_id>' . $iId . '</ray_id><ray_pwd>' . $sPassword . '</ray_pwd>';
                
                // Ray integration [ end ]
				
				echo <<<EOF
			<user_info name="$user">				
				<avatar>$av</avatar>
				<special>$special_user</special>
				<admin>$orca_admin</admin>
				<profile_url>$profile_url</profile_url>
				<profile_onclick />
				<join_date>$join_date</join_date>
                $aRay
			</user_info>
EOF;
				break;

			/**
			 * get user permissions
			 */ 
			case 'user_perm':				

		$forum_id = (int)$_GET['forum_id'];
		$user_id  = getID( $user );
		
		$arrGroup       = getGroupInfo( $forum_id );
		$isGroupMember  = ( isGroupMember( $user_id, $forum_id ) ) ? 1 : 0;
		$isGroupCreator = ( $arrGroup['creatorID'] == $user_id ) ? 1 : 0;
		$isAdmin        = ( $who == 'admin' ) ? 1 : 0;

				$read_public  = 1;
				$post_public  = ( $isGroupMember  or $isAdmin ) ? 1 : 0;
				$edit_public  = ( $isGroupCreator or $isAdmin ) ? 1 : 0;
				$del_public   = ( $isGroupCreator or $isAdmin ) ? 1 : 0;
				
				$read_private = ( $isGroupMember  or $isAdmin ) ? 1 : 0;
				$post_private = ( $isGroupMember  or $isAdmin ) ? 1 : 0;
				$edit_private = ( $isGroupCreator or $isAdmin ) ? 1 : 0;
				$del_private  = ( $isGroupCreator or $isAdmin ) ? 1 : 0;
				
				$edit_own     = ( $isGroupMember  or $isAdmin ) ? 1 : 0;
				$del_own      = ( $isGroupMember  or $isAdmin ) ? 1 : 0;
				
				$search = 0;
                $sticky = ( $isGroupMember  or $isAdmin ) ? 1 : 0;

				echo <<<EOF
			<user_perm name="$user">
				
				<read_public>$read_public</read_public>
				<post_public>$post_public</post_public>
				<edit_public>$edit_public</edit_public>
				<del_public>$del_public</del_public>
				
				<read_private>$read_private</read_private>
				<post_private>$post_private</post_private>
				<edit_private>$edit_private</edit_private>
				<del_private>$del_private</del_private>
				
				<edit_own>$edit_own</edit_own>
				<del_own>$del_own</del_own>
				
				<search_>$search</search_>
				<sticky_>$sticky</sticky_>
				
			</user_perm>
EOF;
				break;
			default:
		}

		echo '</bxforum>';


		/**
		 * determine special user permissions
		 * @param $user username to check
		 * @param return true if user has special permissions in the forum 
		 */ 
		function isSpecialOrcaUser ($user)
		{
            return 0;
		}		

		/**
		 * determine admin user 
		 * @param $user username to check
		 * @param return true if user has special permissions in the forum 
		 */ 		
		function isOrcaAdmin ($user)
		{
			global $logged;
			return $logged['admin'];
		}				
?>
