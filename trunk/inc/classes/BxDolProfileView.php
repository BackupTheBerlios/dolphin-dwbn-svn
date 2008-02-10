<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfile.php' );

class BxDolProfileView extends BxDolProfile
{
	var $oTemplConfig;
	
	var $sColumnsOrder;
	
	function BxDolProfileView( $ID )
	{
		global $site;
		global $logged;
		
		BxDolProfile::BxDolProfile( $ID, 0 );
		
		//$this -> ID = $this -> _iProfileID;
		
		$this -> oTemplConfig = new BxTemplConfig( $site );
		$this -> sColumnsOrder = getParam( 'profile_view_cols' );
		
		if( $this -> _iProfileID )
		{
			$this -> getProfileData();
			
			if( $this -> _aProfile )
			{
				if( $logged['member'] )
				{
					if( (int)$_COOKIE['memberID'] == $this -> _iProfileID )
						$this -> owner = true;
				}
			}
			else
				return false;
		}
		else
			return false;
	}
	
}
?>