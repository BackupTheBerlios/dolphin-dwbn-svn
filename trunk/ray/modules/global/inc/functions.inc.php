<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

/**
 * get error
 * @param sError - error template
 * @param ... - variable amount of incoming parameters with information to be used in the process of parsing.
 */
function getError($sError)
{
        $iNumArgs = func_num_args();

        for($i = 1; $i < $iNumArgs; $i++)
        {
                $sValue = func_get_arg($i);
                $sError = str_replace("#" . $i. "#", $sValue, $sError);
        }
        return $sError;
}

/**--------------- Installation Functions ---------------**/
/**
 * Checks permissions of files and directories.
 * @param sFileName - file's name.
 */
function checkPermissions($sFileName)
{
        $sPermissions = "";
        $sFilePath = trim($sFileName);
        clearstatcache();
        if(!file_exists($sFilePath))
                $sResult = "";
        else
        {
                clearstatcache();
                $iPermissions = fileperms($sFilePath);
                for($i=0, $offset = 0; $i<3; $i++, $offset += 3)
                {
                        $iPerm = 0;
                        for($j=0; $j<3; $j++) ($iPermissions >> ($j+$offset)) & 1 ? $iPerm += pow(2, $j) : "";
                        $sPermissions = $iPerm . $sPermissions;
                }
                $sResult = $sPermissions;
        }
    return $sResult;
}

/**
 * Execute MySQL queries for specified widget.
 * @param sWidget - the name of the widget.
 */
function createDataBase($sWidget) {
        global $sModulesPath;
        global $aErrorCodes;

        //--- Connect to database ---//
        $sDbHost = strlen(DB_PORT) ? DB_HOST . ":" . DB_PORT : DB_HOST;
        if(!($rSock = mysql_connect($sDbHost, DB_USER, DB_PASSWORD))) return $aErrorCodes[3];
        if(!mysql_select_db(DB_NAME, $rSock)) return $aErrorCodes[4];

        //--- Add info in the database ---//
        $sWidgetFile = $sWidget . "/install/install.sql";
        $sFileName = $sModulesPath . $sWidgetFile;
        $sModuleDBPrefix = DB_PREFIX . strtoupper(substr($sWidget, 0, 1)) . substr($sWidget, 1);

        if(!file_exists($sFileName)) return getError($aErrorCodes[1], $sWidgetFile);
        $rHandler = fopen($sFileName, "r");
        while(!feof($rHandler))
        {
                $str = fgets($rHandler);
                if($str[0]=="" || $str[0]=="#" || ($str[0] == "-" && $str[1] == "-")) continue;
                $str = str_replace("[module_db_prefix]", $sModuleDBPrefix, $str);
                if( (strlen($str) > 5 ? strpos($str, ";", strlen($str) - 4) : strpos($str, ";")) )
                        $sQuery .= $str;
                else
                {
                        $sQuery .= $str;
                        continue;
                }
                if(!($res = mysql_query($sQuery, $rSock))) return $aErrorCodes[5];
                $sQuery = "";
        }
        fclose($rHandler);
        mysql_close($rSock);
        return "";
}

/**
 * Recompile global/js/integration.js file - (re)define given widget
 * @param sWidget - the name of the widget.
 */
function recompileIntegrator($sWidget)
{
        global $sModulesPath;
        global $sGlobalDir;
        global $aModules;
        global $aErrorCodes;

        if(!isset($sWidget))return;
        $sBegin = "\n" . '//' . $sWidget . ' begin' . "\n";
        $sBegin .= 'aRayApps["' . $sWidget . '"] = new Array();' . "\n";
        $sAppTmpl = 'aRayApps["' . $sWidget . '"]["#app#"] = {"params": new Array(#params#), "top": #top#, "left": #left#, "width": #width#, "height": #height#, "resizable": #resizable#};' . "\n";
        $sEnd = '//' . $sWidget . ' end' . "\n";

        //getting integrator contents
        $sFile = $sGlobalDir . "js/integration.js";
        $sFileName = $sModulesPath . $sFile;
        if(!file_exists($sFileName)) return parseXml($aXmlTemplates['result'], getError($aErrorCodes[1], $sFile), FAILED_VAL);
        $rHandle = fopen($sFileName, "rt");
        $sJSContents = fread($rHandle, filesize($sFileName)) ;
        fclose($rHandle);

        //creating insert string
        $sInsert = $sBegin;
        foreach($aModules as $sAppName => $aModule)
                if(!$aModule['inline'])
                {
                        $sApp = $sAppTmpl;
                        $sApp = str_replace("#app#", $sAppName, $sApp);
                        $sParams = "'" . implode("', '", $aModule['parameters']) . "'";
                        $sApp = str_replace("#params#", $sParams, $sApp);
                        $sApp = str_replace("#top#", $aModule['layout']['top'], $sApp);
                        $sApp = str_replace("#left#", $aModule['layout']['left'], $sApp);
                        $sApp = str_replace("#width#", $aModule['layout']['width'], $sApp);
                        $sApp = str_replace("#height#", $aModule['layout']['height'], $sApp);
                        $sResizable = ($aModule['vResizable'] || $aModule['hResizable']) ? "1" : "0";
                        $sApp = str_replace("#resizable#", $sResizable, $sApp);
                        $sInsert .= $sApp;
                }
        $sInsert .= $sEnd;

        //inserting javascript code
        $iInsertBegin = strpos($sJSContents, $sBegin);
        $iInsertEnd = strpos($sJSContents, $sEnd) + strlen($sEnd);
        if($iInsertBegin === false)
                $sJSContents .= $sInsert;
        else
                $sJSContents = substr($sJSContents, 0, $iInsertBegin) . $sInsert . substr($sJSContents, $iInsertEnd);

        //--- Save changes to the file---//
        $bResult = true;
        if(($rHandle = @fopen($sFileName, "wt")) !== false)
        {
                $bResult = (fwrite($rHandle, $sJSContents) !== false);
                fclose($rHandle);
        }
        $sValue = $bResult && $rHandle ? "" : getError($aErrorCodes[2], $sFile);

        return array('value' => $sValue, 'status' => $bResult ? SUCCESS_VAL : FAILED_VAL);
}

/**
 * refresh extra file (skins.xml/langs.xml)
 * @param sWidget - the name of the widget.
 * @param sCase - skins/langs
 */
function refreshExtraFile($sWidget, $sCase, $bReset = false, $sDefaultFile = "", $aEnabledFiles = array())
{
        global $sModulesPath;
        global $aXmlTemplates;
        global $aErrorCodes;

        //--- Get folder contents ---//
        $sDir = $sWidget . "/" . $sCase;
        $sDirName = $sModulesPath . $sDir;
        if( !(file_exists($sDirName) && is_dir($sDirName)) ) return parseXml($aXmlTemplates['result'], getError($aErrorCodes[6], $sDir), FAILED_VAL);

        $aFiles = getExtraFiles($sWidget, $sCase, false);
        $iFilesCount = count($aFiles['files']);
        if($iFilesCount == 0) return array('value' => getError($aErrorCodes[7], $sDir), 'status' => FAILED_VAL, 'contents' => "");

        //--- Get XML file contents ---//
        $aFileContents = getFileContents($sWidget, "/xml/" . $sCase . ".xml", true);
        if($aFileContents['status'] == FAILED_VAL) return array('value' => $aFileContents['value'], 'status' => FAILED_VAL, 'contents' => "");
        $aContents = $aFileContents['contents'];

        //--- Merge folder and file contents ---//
        $sCurrent = isset($aContents[FILE_DEFAULT_KEY]) && in_array($aContents[FILE_DEFAULT_KEY], $aFiles["files"]) ? $aContents[FILE_DEFAULT_KEY] : $aFiles["current"];
        $sCurrent = $bReset && in_array($sDefaultFile, $aFiles["files"]) ? $sDefaultFile : $sCurrent;
        $aEnabledFiles[] = $sCurrent;
        $sContents = parseXml($aXmlTemplates["item"], FILE_DEFAULT_KEY, $sCurrent);

        for($i=0; $i<$iFilesCount; $i++)
        {
            $sEnabled = isset($aContents[$aFiles["files"][$i]]) ? $aContents[$aFiles["files"][$i]] : TRUE_VAL;
            if($bReset)
               $sEnabled = in_array($aFiles["files"][$i], $aEnabledFiles) ? TRUE_VAL : FALSE_VAL;
            $sContents .= parseXml($aXmlTemplates["item"], $aFiles["files"][$i], $sEnabled);
        }
        $sContents = makeGroup($sContents, "items");

        //--- Save changes to the file---//
        $sFile = $sWidget . "/xml/" . $sCase . ".xml";
        $sFileName = $sModulesPath . $sFile;
        $bResult = false;
        if(($rHandle = @fopen($sFileName, "wt")) !== false)
        {
                $bResult = (fwrite($rHandle, $sContents) !== false);
                fclose($rHandle);
        }
        $bResult = $bResult && $rHandle;
        $sValue = $bResult ? "" : getError($aErrorCodes[2], $sFile);

        return array('value' => $sValue, 'status' => $bResult ? SUCCESS_VAL : FAILED_VAL, 'contents' => $sContents);
}

/**
 * creates widget's main config file
 * @param sWidget - the name of the widget.
 */
function createMainFile($sWidget)
{
        global $sModulesPath;
        global $aXmlTemplates;
        global $aErrorCodes;
        require($sModulesPath . $sWidget . "/inc/constants.inc.php");

        $sCode = $aInfo['code'];
        $sContents = parseXml($aXmlTemplates["item"], "status", WIDGET_STATUS_NOT_REGISTERED);
        $sContents .= parseXml($aXmlTemplates["item"], "license", "");
        $sContents .= parseXml($aXmlTemplates["item"], "code", $sCode);
        $sContents .= parseXml($aXmlTemplates["item"], "updated", TRUE_VAL);
        $sContents .= parseXml($aXmlTemplates["item"], "updateLast", "");
        $sContents .= parseXml($aXmlTemplates["item"], "updateUrl", "");
        $sContents = makeGroup($sContents, "items");

        //--- Save changes to the file---//
        $sFile = $sWidget . "/xml/main.xml";
        $sFileName = $sModulesPath . $sFile;
        $bResult = false;
        if(($rHandle = @fopen($sFileName, "wt")) !== false)
        {
                $bResult = (fwrite($rHandle, $sContents) !== false);
                fclose($rHandle);
        }
        $bResult = $bResult && $rHandle;
        $sValue = $bResult ? "" : getError($aErrorCodes[2], $sFile);

        return array('value' => $sValue, 'status' => $bResult ? SUCCESS_VAL : FAILED_VAL);
}

/**
 * gets file contents
 * @param sWidget - the name of the widget.
 * @param sCase - skins/langs
 */
function getFileContents($sWidget, $sFile, $bArray = false)
{
        global $sModulesPath;
        global $aErrorCodes;

        $sFile = $sWidget . $sFile;
        $sFileName = $sModulesPath . $sFile;
        if(!file_exists($sFileName)) return array('value' => getError($aErrorCodes[1], $sFile), 'status' => FAILED_VAL, 'contents' => $bArray ? array() : "");
        $rHandle = fopen($sFileName, "rt");
        $iFileSize = filesize($sFileName);
        $sContents = $iFileSize > 0 ? fread($rHandle, filesize($sFileName)) : makeGroup("", "items");
        fclose($rHandle);

        $aContents = xmlGetValues($sContents, "item");
        return array('value' => "", 'status' => SUCCESS_VAL, 'contents' => $bArray ? $aContents : $sContents);
}

/**
 * gets file extension by given widget name and folder
 * @param sWidget - the name of the widget.
 * @param sFolder
 */
function getFileExtension($sWidget, $sFolder)
{
        global $sModulesPath;

        $aWrongExtensions = array("php", "asp", "php5");
        $sFolderPath = $sModulesPath . $sWidget . "/" . $sFolder . "/";
        if($rDirHandle = opendir($sFolderPath))
                while (false !== ($sFile = readdir($rDirHandle)))
                {
                      $aPathInfo = pathinfo($sFolderPath . $sFile);
                      if(is_file($sFolderPath . $sFile) && $sFile != "." && $sFile != ".." && !in_array($aPathInfo['extension'], $aWrongExtensions))
                             return $aPathInfo['extension'];
                }
        return "";
}
?>