<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfile.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileFields.php' );

class BxDolProfileView extends BxDolProfile
{
	var $oTemplConfig;
	var $sColumnsOrder;
	var $oPF; // profile fields object
	var $aPFBlocks; //profile fields blocks
	var $aCoupleMutualItems;
	
	function BxDolProfileView( $ID )
	{
		global $site;
		global $logged;
		
		BxDolProfile::BxDolProfile( $ID, 0 );
		
		//$this -> ID = $this -> _iProfileID;
		
		$this -> oTemplConfig = new BxTemplConfig( $site );
		$this -> sColumnsOrder = getParam( 'profile_view_cols' );
		
		if( $this -> _iProfileID ) {
			$this -> getProfileData();
			
			if( $this -> _aProfile ) {
				if( $logged['member'] ) {
					if( (int)$_COOKIE['memberID'] == $this -> _iProfileID )
						$this -> owner = true;
					
					$iPFArea = 6;
				} elseif( $logged['admin'] )
					$iPFArea = 5;
				elseif( $logged['moderator'] )
					$iPFArea = 7;
				else
					$iPFArea = 8;
				
				$this -> oPF = new BxDolProfileFields( $iPFArea );
				if( !$this -> oPF -> aBlocks)
					return false;
				
				$this -> aPFBlocks = $this -> oPF -> aBlocks;
				//echoDbg( $this -> aPFBlocks );
				
				if( $this -> bCouple )
					$this -> aCoupleMutualItems = $this -> oPF -> getCoupleMutualFields();
				
			} else
				return false;
		} else
			return false;
	}
	
}
?>