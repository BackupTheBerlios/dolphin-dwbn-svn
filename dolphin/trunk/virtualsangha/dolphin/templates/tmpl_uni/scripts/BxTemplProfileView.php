<?

require_once( BX_DIRECTORY_PATH_BASE . 'scripts/BxBaseProfileView.php' );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplVotingView.php" );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplCmtsView.php" );

class BxTemplProfileView extends BxBaseProfileView
{
	function BxTemplProfileView( $ID )
    {
        $this -> oVotingView = new BxTemplVotingView ('profile', (int)$ID);
        $this -> oCmtsView = new BxTemplCmtsView ('profile', (int)$ID);
		BxBaseProfileView::BxBaseProfileView( $ID );
	}
}
?>