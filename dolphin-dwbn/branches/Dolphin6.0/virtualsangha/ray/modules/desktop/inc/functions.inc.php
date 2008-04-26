<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

function getAvailableFiles($sModule, $sFolder = "langs")
{
	global $sIncPath;
	global $sModulesUrl;

	//--- Get info which was set by admin in the Ray Base ---//
	$aFileContents = getFileContents($sModule, "/xml/" . $sFolder . ".xml", true);
	$aFiles = $aFileContents['contents'];

	//--- Get info from file system ---//
	$aRealFiles = getExtraFiles($sModule, $sFolder, false, true);

	//--- Merge info from these arrays ---//
	$aResult = array();
	foreach($aFiles as $sFile => $bEnabled)	
		if($bEnabled == TRUE_VAL && ($sKey = array_search($sFile, $aRealFiles['files'])) !== false)
		{
			$aResult['files'][] = $sFile;
			$aResult['dates'][] = $aRealFiles['dates'][$sKey];
		}
	$aResult['default'] = $aFiles['_default_'];
	return $aResult;
}
?>