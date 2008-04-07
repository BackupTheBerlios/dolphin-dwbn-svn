<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

/**
 * Checks if given widget exists
 * @param sWidget - widget name
 * @return bExists - true/false
 */
function widgetExists($sWidget)
{
	global $sModulesPath;
	
	$sFilePath = $sModulesPath . $sWidget . "/xml/main.xml";
	$bExists = file_exists($sFilePath) && filesize($sFilePath) > 0;
	return $bExists;
}

/**
 * Gets the embed code of necessary widget's application.
 * @param sModule - module(widget) name.
 * @param sApp - application name in the widget.
 * @param aParamValues - an associative array of parameters to be passed into the Flash object.
 */
function getEmbedCode($sModule, $sApp, $aParamValues)
{
	return getApplicationContent($sModule, $sApp, $aParamValues, true, true);
}

/**
 * Gets the content of necessary widget's application.
 * @param sModule - module(widget) name.
 * @param sApp - application name in the widget.
 * @param aParamValues - an associative array of parameters to be passed into the Flash object.
 * @param bInline - whether you want to have it with the full page code(for opening in a new window)
 * or only DIV with flash object (for embedding into the existing page).
 */
function getApplicationContent($sModule, $sApp, $aParamValues = array(), $bInline = false, $bEmbedCode = false)
{
	global $sGlobalUrl;
	global $sHomeUrl;
	global $sRayHomeDir;
	global $sModulesUrl;
	global $sModulesPath;
		
	$sModule = isset($sModule) ? $sModule : $_REQUEST['module'];
	$sApp = isset($sApp) ? $sApp : $_REQUEST['app'];
		
	if(isset($aModules))unset($aModules);
	require($sModulesPath . $sModule . "/inc/header.inc.php");
	require($sModulesPath . $sModule . "/inc/constants.inc.php");
		
	//--- Print container ---//
	$sRayAppBaseDir = $sModulesUrl . $sModule . "/";
	$sRayHolderBaseDir = $sModulesUrl . "global/";
		
	if(!isset($bInline))$bInline = $aModules[$sApp]['inline'];
	$iWidth = $aModules[$sApp]['layout']['width'];
	$iHeight = $aModules[$sApp]['layout']['height'];
		
	//--- Parameters for container's div ---//
	$sDivId = !empty($aModules[$sApp]['div']['id']) ? ' id="' . $aModules[$sApp]['div']['id'] . '"' : '';
	$sDivName = !empty($aModules[$sApp]['div']['name']) ? ' name="' . $aModules[$sApp]['div']['name'] . '"' : '';
	if(count($aModules[$sApp]['div']['style']))
	{
		$sDivStyle = ' style="';
		foreach($aModules[$sApp]['div']['style'] as $sKey => $sValue)
			$sDivStyle .= $sKey . ':' . $sValue . ';';
		$sDivStyle .= '"';
	}
	else $sDivStyle='';
		
	//--- Parameters for SWF object and reloading ---//
	$aParametersReload = array();
	if(!isset($_GET["module"]))$aParametersReload[] = "module=" . $sModule;
	if(!isset($_GET["app"]))$aParametersReload[] = "app=" . $sApp;
	$sParameters = "module=" . $sModule . "&app=" . $sApp;
	foreach($aModules[$sApp]['parameters'] as $sParameter)
	{
		$sParameters .= "&" . $sParameter . "=" . (isset($aParamValues[$sParameter]) ? $aParamValues[$sParameter] : $_REQUEST[$sParameter]);
		if(!isset($_GET[$sParameter]))$aParametersReload[] = $sParameter . "=" . (isset($aParamValues[$sParameter]) ? $aParamValues[$sParameter] : $_REQUEST[$sParameter]);
	}
	$sParameters .= "&url=" . $sHomeUrl . "XML.php";
	$sHolder = $aInfo['mode'] == "paid" ? "holder.swf" : "holder_free.swf";
	ob_start();
	if(!$bInline)
	{
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
		<head>
			<title><?=$aModules[$sApp]['caption']; ?></title>
			<meta http-equiv=Content-Type content="text/html;charset=UTF-8" />
		</head>
		<body style="margin:0; padding:0;"  <?=$aModules[$sApp]['hResizable'] || $aModules[$sApp]['vResizable'] ? 'onLoad="resizeWindow()" onResize="resizeWindow()"' : ''; ?> >
		<script type="text/javascript" language="javascript">
				var sRayUrl = '<?= $sHomeUrl ?>';
		</script>		
		<script src="<?= $sGlobalUrl; ?>js/integration.js" type="text/javascript" language="javascript"></script>
<?
	}
	if(!$bEmbedCode)
		foreach($aModules[$sApp]['js'] as $sJSUrl)
			echo "\t\t<script src=\"" . $sJSUrl . "\" type=\"text/javascript\" language=\"javascript\"></script>\n";
		
	if(!$bEmbedCode && ($aModules[$sApp]['hResizable'] || $aModules[$sApp]['vResizable']))
	{
		$iMinWidth = (int)$aModules[$sApp]['minSize']['width'];
		$iMinHeight = (int)$aModules[$sApp]['minSize']['height'];
?>
	<script type="text/javascript" language="javascript">
	<!--
		function resizeWindow()
		{
			var frameWidth = 0;
			var frameHeight = 0;
				
			if(window.innerWidth)
			{
				frameWidth = window.innerWidth;
				frameHeight = window.innerHeight;
			}
			else if (document.documentElement)
			{
				if(document.documentElement.clientHeight)
				{
					frameWidth = document.documentElement.clientWidth;
					frameHeight = document.documentElement.clientHeight;
				}
			}
			else if (document.body)
			{
				frameWidth = document.body.offsetWidth;
				frameHeight = document.body.offsetHeight;
			}
				
			var o = document.getElementById('ray_<?=$sApp; ?>_object');
			var e = document.getElementById('ray_<?=$sApp; ?>_embed');
				
			frameWidth = (frameWidth < <?=$iMinWidth?>) ? <?=$iMinWidth?> : frameWidth;
			frameHeight = (frameHeight < <?=$iMinHeight?>) ? <?=$iMinHeight?> : frameHeight;
				
<?
	$sRet = $aModules[$sApp]['hResizable'] ? "o.width = frameWidth;\n" : "";
	$sRet .= $aModules[$sApp]['vResizable'] ? "o.height = frameHeight;\n" : "";
	$sRet .= "if(e != null){";
	$sRet .= $aModules[$sApp]['hResizable'] ? "e.width = frameWidth;\n" : "";
	$sRet .= $aModules[$sApp]['vResizable'] ? "e.height = frameHeight;\n" : "";
	$sRet .= "}";
	echo $sRet;
?>
		}
	-->
	</script>
<?
	}
	if(!$bEmbedCode && $aModules[$sApp]['reloadable'])
	{
		if(!$bInline) echo getRedirectForm($sModule, $sApp, array_merge($_GET, $_POST));
?>
	<script type="text/javascript" language="javascript">
	<!--
		function reload()
		{
<?
			$sGet = $_SERVER['QUERY_STRING'];
			$sExtraGet = implode("&", $aParametersReload);
			if(!empty($sGet) && !empty($sExtraGet)) $sGet .= "&";
?>
<?= !$bInline ? "redirect();" : "location.href='" . $_SERVER['PHP_SELF'] . "?" . $sGet . $sExtraGet . "';" ?>
		}
	-->
	</script>
<?
	}
?>
<div<?=$sDivId . $sDivName . $sDivStyle?>><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" <?='width="' . $iWidth . '" height="' . $iHeight . '"'; ?> id="ray_<?=$sApp; ?>_object" align="middle"><param name="allowScriptAccess" value="always" /><param name="movie" value="<?=$sRayHolderBaseDir . "app/" . $sHolder; ?>" /><param name="quality" value="high" /><param name="allowFullScreen" value="true" /><param name="base" value="<?=$sRayAppBaseDir; ?>" /><param name="FlashVars" value="<?=$sParameters; ?>" /><embed id="ray_<?=$sApp; ?>_embed"	name="ray_<?=$sApp; ?>"	src="<?=$sRayHolderBaseDir . "app/" . $sHolder; ?>" quality="high" allowFullScreen="true" <?='width="' . $iWidth . '" height="' . $iHeight . '"'; ?> align="middle" allowScriptAccess="always" base="<?=$sRayAppBaseDir; ?>" FlashVars="<?=$sParameters; ?>" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object></div>
<?
/*	if(!$bEmbedCode)
	{
?>
	<script type="text/javascript" language="javascript">
	<!--
		theObjects = document.getElementsByTagName("object");
		for (var i = 0; i < theObjects.length; i++) 
			theObjects[i].outerHTML = theObjects[i].outerHTML;
	-->
	</script>
<?
	}*/
	if(!$bInline)
	{
?>
		</body>
	</html>
<?
	}
	$sWidgetContent = ob_get_contents();
	ob_end_clean();
		
	return $sWidgetContent;
}

/**
 * Make redirect and send necessary parameters using POST method.
 */
function getRedirectForm($sModule, $sApp, $aRequest)
{
	ob_start();
?>
	<form name="<?= $sModule . "-" . $sApp; ?>" method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="module" value="<?= $sModule; ?>" />
		<input type="hidden" name="app" value="<?= $sApp; ?>" />
<?
		foreach($aRequest as $sKey => $sValue)
		{
?>
			<input type="hidden" name="<?= $sKey; ?>" value="<?= $sValue; ?>" />
<?
		}
?>
	</form>
	<script>
	<!--
		function redirect()
		{
			document.forms['<?= $sModule . "-" . $sApp; ?>'].submit();
		}
	-->
	</script>
<?
	$sReturn = ob_get_contents();
	ob_end_clean();
	return $sReturn;
}
?>