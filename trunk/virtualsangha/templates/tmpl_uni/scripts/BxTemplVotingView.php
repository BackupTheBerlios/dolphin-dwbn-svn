<?

require_once( BX_DIRECTORY_PATH_BASE . 'scripts/BxBaseVotingView.php' );

class BxTemplVotingView extends BxBaseVotingView
{
	function BxTemplVotingView( $sSystem, $iId, $iInit = 1 )
	{
		BxBaseVotingView::BxBaseVotingView( $sSystem, $iId, $iInit );
	}
}
?>
