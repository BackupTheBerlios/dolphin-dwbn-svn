<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileQuery.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMistake.php' );

class BxDolProfile extends BxDolMistake
{
	var $_iProfileID;
	var $_aProfile;
	var $bCouple;
	var $_iCoupleID;
	var $_aCouple;

	/**
	 * Constructor
	 *
	 * @return User
	 */
	function BxDolProfile( $vProfileID, $bWithEmail = 1 )
	{
		$this -> _iProfileID = $this -> getID( $vProfileID, $bWithEmail );
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $ID
	 * @param unknown_type $float
	 */
	function getProfileThumbnail( $float )
	{
		$ret = $this -> getProfileImageUrl( $iProfileID, 0);
	}

	/**
	 * return link to profile image only.
	 *
	 * @param unknown_type $ID
	 * @param unknown_type $imageNum
	 */
	function getProfileImageUrl( $imageNum )
	{

	}

	/**
	 *  return assoc array of all frofile fields
	 */
	function getProfileData()
	{
		global $aUser;
		global $dir;
		$oPDb = new BxDolProfileQuery();
		$sProfileCache = $dir['cache'] . 'user' . $this -> _iProfileID . '.php';
		if( file_exists( $sProfileCache ) && is_file( $sProfileCache ) ) {
			require_once($sProfileCache);
			$this -> _aProfile = $aUser[$this -> _iProfileID];
		} else
			$this -> _aProfile = $oPDb -> getProfileDataById( $this -> _iProfileID );
		
		
		//get couple data
		if( $this -> _aProfile['Couple'] ) {
			$this -> bCouple = true;
			$this -> _iCoupleID = $this -> _aProfile['Couple'];
			
			$sProfileCache = $dir['cache'] . 'user' . $this -> _iCoupleID . '.php';
			if( file_exists( $sProfileCache ) && is_file( $sProfileCache ) ) {
				require_once($sProfileCache);
				$this -> _aCouple = $aUser[$this -> _iCoupleID];
			} else
				$this -> _aCouple = $oPDb -> getProfileDataById( $this -> _iCoupleID );
		}

		return $this -> _aProfile;
	}

	/**
	 * Update profile info to database
	 *
	 *
	 * @param int $iUserID
	 * @param array $aData
	 * where the key of the array is name of database table field
	 *
	 * example:
	 * $aData['Sex'] = 'male';
	 *
	 */
	function updateProfileData( $aData )
	{
		if( is_array( $aData ) )
		{
			$sQueryAdd = '';
			foreach($aData as $key => $value )
			{
				$sQueryAdd .= " `$key` = '$value', ";
			}
		}

		$this -> updateProfileDataFile( $iProfileID );
	}

	/**
	 * function create cache data file
	 *
	 * @param int $iProfileID
	 */
	function updateProfileDataFile( $iProfileID )
	{

	}

	/**
	 * Print code for membership status
	 * $memberID - member ID
	 * $offer_upgrade - will this code be printed at [c]ontrol [p]anel
	 * $credits - will print credits status if $credits == 1
	 */
	function getMembershipStatus( $iPrifileID, $offer_upgrade = true, $credits = 0 )
	{

	}

	/**
	 * Shows how many days, hours, minutes member was onine last time
	 *
	 * @param  $lastNavTime
	 *
	 * @return int
	 */
	function  getProfileLastOnlinePeriod( $lastNavTime )
	{

	}


	function getNickName()
	{
		$oProfileQuery = new BxDolProfileQuery();
		return process_line_output( $oProfileQuery -> getNickName( $this -> _iProfileID ) );
	}

	function getPassword()
	{

	}

	function getID( $vID, $bWithEmail = 1 )
	{
		$oPDb = new BxDolProfileQuery();

		if ( $bWithEmail )
		{
			if ( eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$", $vID) )
			{
				$aMail = $oPDb -> getIdByEmail( $vID );
				if ( (int)$aMail['ID'] )
				{
					return (int)$aMail['ID'];
				}
			}
		}
		
		$iID = (int)$vID;
	    if ( strcmp("$vID", "$iID") == 0 )
	    {
			return $iID;
	    }
		else
	    {
			$aNick = $oPDb -> getIdByNickname( $vID );
			if ( (int)$aNick['ID'] )
			{
				return (int)$aNick['ID'];
			}
		}

		return false;
	}

}
?>