<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolVoting.php' );

class BxBaseVotingView extends BxDolVoting
{
	var $_iSizeStarBig = 32;
	var $_iSizeStarSmall = 16;

	function BxBaseVotingView( $sSystem, $iId, $iInit = 1 )
	{
		BxDolVoting::BxDolVoting( $sSystem, $iId, $iInit );
	}
	
	function getSmallVoting ($iCanRate = 1)
	{
		global $logged;
		$iCanRate = ($logged['member']) ? $iCanRate : 0;
		if (!$this->checkAction()) $iCanRate = 0;
		return $this->getVoting($iCanRate, $this->_iSizeStarSmall, 'small');
	}

	function getBigVoting ($iCanRate = 1)
	{
		global $logged;
		$iCanRate = ($logged['member']) ? $iCanRate : 0;
		if (!$this->checkAction()) $iCanRate = 0;
		return $this->getVoting($iCanRate, $this->_iSizeStarBig, 'big');
	}

	function getVoting($iCanRate, $iSize, $sName)
	{
		global $site;

		$iMax = $this->getMaxVote();
		$iWidth = $iSize*$iMax;
		$sSystemName = $this->getSystemName();
		$iObjId = $this->getId();
		$sDivId = $this->getSystemName() . $sName;

		$sRet = '<div class="votes_'.$sName.'" id="' . $sDivId . '">';
		
		$sRet .= <<<EOF
<script language="javascript">
	var oVoting{$sDivId} = new BxDolVoting('{$site['url']}', '{$sSystemName}', '{$iObjId}', '{$sDivId}', '{$sDivId}Slider', {$iSize}, {$iMax});
</script>
EOF;

		$sRet .= '<div class="votes_gray_'.$sName.'" style="width:'.$iWidth.'px;">';

		if ($iCanRate)
		{
			$sRet .= '<div class="votes_buttons">';
			for ($i=1 ; $i<=$iMax ; ++$i)
			{
				$sRet .= '<a href="javascript:'.$i.';void(0);" onmouseover="oVoting'.$sDivId.'.over('.$i.');" onmouseout="oVoting'.$sDivId.'.out();" onclick="oVoting'.$sDivId.'.vote('.$i.')"><img class="votes_button_'.$sName.'" src="'.$site['base'].'images/vote_star_null.gif" alt="" /></a>';
			}
			$sRet .= '</div>';
		}

		$sRet .= '<div id="'.$sDivId.'Slider" class="votes_active_'.$sName.'" style="width:'.round($this->getVoteRate()*$iWidth/$iMax).'px;"></div>';
		$sRet .= '</div>';
		$sRet .= '<b>'.$this->getVoteCount(). ' ' . _t('_votes') . '</b>';
		$sRet .= '<div class="clear_both"></div>';
		$sRet .= '</div>';

		return $sRet;
	}

	function getExtraJs ()
	{
		global $site;
		return '<script src="'.$site['url'].'inc/js/classes/BxDolVoting.js" type="text/javascript" language="javascript"></script>';
	}
}
?>
