<?
require_once(BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'db.inc.php');
require_once( BX_DIRECTORY_PATH_CLASSES . "BxDolSharedMedia.php" );
require_once( BX_DIRECTORY_PATH_CLASSES . "BxDolPageView.php" );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplCmtsView.php" );


class BxDolSharedMediaView extends BxDolPageView  {
	
	// file ID
	var $iFile;
	// file type
	var $sType;
	// file info array
	var $aInfo;
	// object sharing manipulation
	var $oShared;
	
	var $oCmtsView;
	
	/*
		constructor
	*/
	
	function BxDolSharedMediaView($iFile, $sMediaType, &$aSite, &$aDir, &$aMember) {
		$this->iFile = (int)$iFile;
		$this->sType = process_db_input($sMediaType);
		
		$this->oShared = new BxDolSharedMedia($sMediaType, $aSite, $aDir, $aMember);
		$this->aInfo   = $this->oShared->getFileInfo($this->iFile);
		switch ($sMediaType) {
			case 'photo': 
				$sComms = 'sharedPhoto';
				break;
			case 'music':
				$sComms = 'sharedMusic';
				break;
			case 'video':
				$sComms = 'sharedVideo';
				break;
		}
		$this->oCmtsView = new BxTemplCmtsView ($sComms, (int)$this->iFile);
	
		parent::BxDolPageView($sMediaType);
	}
	
	function getBlockCode_ActionList() {
		if (is_array($this->aInfo))
			return $this->oShared->showActionList($this->aInfo);
	}
	
	function getBlockCode_FileInfo() {
		if (is_array($this->aInfo))
			return $this->oShared->showFileInfo($this->aInfo);
	}
	
	function getBlockCode_LastFiles() {
		if (is_array($this->aInfo))
			return $this->oShared->showLatestFiles($this->aInfo);
	}
	
	function getBlockCode_Rate() {
		return $this->oShared->showRateSection($this->iFile);
	}
	
	function getBlockCode_ViewFile() {
		return $this->oShared->showFile($this->iFile);
	}
	
	function getBlockCode_ViewComments() {		
        if (!$this->oCmtsView->isEnabled()) return '';
        return $this->oCmtsView->getCommentsFirst ();
	}
}
?>