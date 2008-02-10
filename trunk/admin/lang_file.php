<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/


require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// DECLARATIONS

define('SEARCH_COMBINE_WORDS_AS_AND',			0);
define('SEARCH_COMBINE_WORDS_AS_OR',			1);
define('SEARCH_COMBINE_WORDS_AS_EXACT',			2);

// find
// words
// settings
// keys
// strings
// params
// sCats
// sLangs

function messageTemplate($message)
{
	ob_start();
?>
<div style="text-align:center;font-family:Arial;font-size:12px;height:100%;width:100%;margin:0px;padding:0px;margin-top:40%;">
	<?= $message ?>
</div>
<?
	$contents = ob_get_clean();

	return $contents;
}

/**
 * Validates an array, moving all integer values to keys.
 *
 * @param array $array
 * @return	array( 0 => true) if the original array contained non-integers
 * 			array( int_key => true) if the original array was OK
 */
function validateListArray($array)
{
	if(is_array($array))
	{
		$tmp = array();
		foreach ($array as $value)
		{
			$iVal = (int)$value;
			if($iVal != $value) return array(0 => true);
			$tmp[$iVal] = true;
		}
		$array = $tmp;
	}

	return count($array) <= 0 || isset($array[0]) ? array(0 => true) : $array;
}

function findLangStrings(
	$searchText,
	$groupWords = SEARCH_COMBINE_WORDS_AS_EXACT,
	$searchInKeys = true,
	$searchInStrings = true,
	$searchInParams = true,
	$arrCategoryIDs = null,
	$arrLanguageIDs = null
	)
{
	function sqlLikeClause($fieldName, $arrWords, $groupWords)
	{
		if (!is_array($arrWords) || strlen($fieldName) <= 0) return '';

		switch ($groupWords)
		{
			case SEARCH_COMBINE_WORDS_AS_AND: $sql = implode("%' AND $fieldName LIKE '%", $arrWords);
				break;
			case SEARCH_COMBINE_WORDS_AS_OR: $sql = implode("%' OR $fieldName LIKE '%", $arrWords);
				break;
			default: $sql = implode(' ', $arrWords);
		}

		$sql = "($fieldName LIKE '%$sql%')";

		return $sql;
	}

	function sqlInClause($fieldName, $arrIDs)
	{
		$sql = '';

		$sqlValid = false;

		if(is_array($arrIDs))
		{
			$sql = "($fieldName IN(";

			foreach ($arrIDs as $ID => $val)
			{
				$ID = (int)$ID;

				if($ID <= 0) continue;

				$sqlValid = true;

				$sql .= $ID.',';
			}
			$sql = trim($sql,',');
			$sql .= '))';
		}

		return $sqlValid ? $sql : '';
	}

	function implodeNonEmpty($strGlue, $arr)
	{
		foreach ($arr as $key => $val)
		{
			if (strlen($val) <= 0) unset($arr[$key]);
		}

		return implode($strGlue, $arr);
	}

	if ( strlen($searchText) > 0 )
	{
		$searchText = addslashes($searchText);

		$likeWildcards = array ('%', '_');
		$likeWildcardsEscaped = array('\%', '\_');

		$searchText = str_replace($likeWildcards, $likeWildcardsEscaped, $searchText);

		$arrWords = preg_split("/\s+/", $searchText, -1, PREG_SPLIT_NO_EMPTY);
		
		foreach( $arrWords as $key => $val )
			$arrWords[$key] = strtoupper( $val );
		
		$sqlSearchInKeys = $searchInKeys ? sqlLikeClause('UPPER(`LocalizationKeys`.`Key`)', $arrWords, $groupWords) : '';
		$sqlSearchInStrings = $searchInStrings ? sqlLikeClause('UPPER(`LocalizationStrings`.`String`)', $arrWords, $groupWords) : '';
		$sqlSearchInParams = $searchInParams ? sqlLikeClause('UPPER(`LocalizationStringParams`.`Description`)', $arrWords, $groupWords) : '';

		$sqlSearchClause = implodeNonEmpty(' OR ', array($sqlSearchInKeys, $sqlSearchInStrings, $sqlSearchInParams));

		$sqlSearchClause = "($sqlSearchClause)";
	}
	else
	{
		$sqlSearchClause = '';
	}

	$sqlSearchInCategories = sqlInClause('`LocalizationCategories`.`ID`', $arrCategoryIDs);
	$sqlSearchInLanguages = sqlInClause('`LocalizationLanguages`.`ID`', $arrLanguageIDs);

	$sqlWhereClause = implodeNonEmpty(' AND ', array($sqlSearchClause, $sqlSearchInCategories, $sqlSearchInLanguages));

	if (	strlen($sqlWhereClause) > 0 )
	{
		$sqlWhereClause = " WHERE $sqlWhereClause ";
	}
	else
	{
		$sqlWhereClause = '';
	}

	$sql = "
		SELECT	DISTINCT
				`LocalizationStrings`.`IDKey`		AS `IDKey`,
				`LocalizationStrings`.`IDLanguage`	AS `IDLanguage`,
				`LocalizationKeys`.`Key`			AS `Key`,
				`LocalizationStrings`.`String`		AS `String`,
				`LocalizationLanguages`.`Name`		AS `Language`,
				`LocalizationCategories`.`Name`		AS `Category`

		FROM	`LocalizationStringParams`
				RIGHT JOIN `LocalizationKeys`
					ON (`LocalizationStringParams`.`IDKey` = `LocalizationKeys`.`ID`)

				LEFT JOIN `LocalizationStrings`
					ON (`LocalizationStrings`.`IDKey` = `LocalizationKeys`.`ID`)

				LEFT JOIN `LocalizationCategories`
					ON (`LocalizationKeys`.`IDCategory` = `LocalizationCategories`.`ID`)

				LEFT JOIN `LocalizationLanguages`
					ON (`LocalizationStrings`.`IDLanguage` = `LocalizationLanguages`.`ID`)

		$sqlWhereClause

		ORDER BY `Language`, `Category`, `Key`";

	$resSearchResult = mysql_query($sql);

	return $resSearchResult;
}

function searchBlock()
{
	global	$searchString,
			$searchCombineWordsAs,
			$searchShowAdvancedSettings,
			$searchInKeys,
			$searchInStrings,
			$searchInParams,
			$searchCategories,
			$searchLanguages;

	ob_start();
?>

<style type="text/css">

input.input_chbox
{
	vertical-align: middle;
}

input.input_radio
{
	vertical-align: middle;
}

div.AdvancedSearchBody
{
}

</style>

<script type="text/javascript">

function showHideExtended(strDivID, strLabelID, strShowText, strHideText)
{
	div = document.getElementById(strDivID);
	label = document.getElementById(strLabelID);
	input = document.getElementById('ShowAdvancedSearchSettingsInput');

	if(div.style.display == '')
		div.style.display = 'none';

	if(div.style.display == 'none'){
		div.style.display = 'block';
		input.value = 'yes';
		label.innerHTML = strHideText;
	}else{
		div.style.display = 'none';
		input.value = 'no';
		label.innerHTML = strShowText;
	}
}

</script>

<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="get" style="margin: 0px">

<table width="100%" border="0" style="margin: 0px">
	<tr>
		<td>
			Look for:
			<input
				type="text"
				name="find"
				value="<?= $searchString ?>"
				style="width: 150px"
			/>
			<input
				type="radio"
				name="words"
				value="<?= SEARCH_COMBINE_WORDS_AS_AND ?>"
				id="RadioCombineWordsAs_AND"
				class="input_radio"
				<?= $searchCombineWordsAs == SEARCH_COMBINE_WORDS_AS_AND ? 'checked' : '' ?>
			/><label for="RadioCombineWordsAs_AND">and</label>
			<input
				type="radio"
				name="words"
				value="<?= SEARCH_COMBINE_WORDS_AS_OR ?>"
				id="RadioCombineWordsAs_OR"
				class="input_radio"
				<?= $searchCombineWordsAs == SEARCH_COMBINE_WORDS_AS_OR ? 'checked' : '' ?>
			/><label for="RadioCombineWordsAs_OR">or</label>
			<input
				type="radio"
				name="words"
				value="<?= SEARCH_COMBINE_WORDS_AS_EXACT ?>"
				id="RadioCombineWordsAs_EXACT"
				class="input_radio"
				<?= $searchCombineWordsAs == SEARCH_COMBINE_WORDS_AS_EXACT ? 'checked' : '' ?>
			/><label for="RadioCombineWordsAs_EXACT">exact sequence</label>
		</td>
		<td>
			<input type="submit" value="Search" style="width: 70px; height: 25px" />
		</td>
		<td align="right" width="90">
			<a
				href="javascript:void(0)"
				onclick="
					showHideExtended(
						'AdvancedSearchSettings',
						'ShowHideAdvSearchSettings',
						'more settings',
						'less settings');
					return false;"
				id="ShowHideAdvSearchSettings"
			>
				<?= $searchShowAdvancedSettings ? 'less settings' : 'more settings' ?>
			</a>
			<input
				type="hidden"
				name="settings"
				value="<?= $searchShowAdvancedSettings ? 'yes' : 'no' ?>"
				id="ShowAdvancedSearchSettingsInput"
			/>
		</td>
	</tr>
</table>

<div
	id="AdvancedSearchSettings"
	class="AdvancedSearchBody"
	style="display: <?= $searchShowAdvancedSettings ? 'block' : 'none' ?>;"
>
<table style="width: 100%" border="0" class="SearchFormTable">
	<tr>
		<td colspan="2" valign="top">
			<table border="0" width="100%">
				<tr>
					<td valign="top" colspan="2" style="height: 20px">
						Search in:
					</td>
				</tr>
				<tr>
					<td width="10"></td>
					<td valign="top">
						<input
							type="checkbox"
							name="keys"
							value="yes"
							id="ChboxSearchInKeys"
							class="input_chbox"
							<?= $searchInKeys ? 'checked' : '' ?>
						/>
						<label for="ChboxSearchInKeys">key strings (case-sensitive)</label>
						<br />
						<input
							type="checkbox"
							name="strings"
							value="yes"
							id="ChboxSearchInStrings"
							class="input_chbox"
							<?= $searchInStrings ? 'checked' : '' ?>
						/>
						<label for="ChboxSearchInStrings">language-specific strings</label>
						<br />
						<input
							type="checkbox"
							name="params"
							value="yes"
							id="ChboxSearchInParams"
							class="input_chbox"
							<?= $searchInParams ? 'checked' : '' ?>
						/>
						<label for="ChboxSearchInParams">parameter descriptions</label><br />
					</td>
				</tr>
			</table>
		</td>
		<td colspan="2" align="center">
			<table border="0" style="width: 100%">
				<tr>
					<td align="center">
						Show strings from categories:
						<br />
						<select name="sCats[]" multiple style="height: 90px; width: 170px">
<?
	$arrCats = getLocalizationCategories();
	$arrCategories[0] = '---All Categories---';
	foreach($arrCats as $key => $val)
	{
		$arrCategories[$key] = $val;
	}

	foreach ($arrCategories as $categoryID => $categoryName)
	{
?>
							<option value="<?= $categoryID ?>" <?= isset($searchCategories[$categoryID]) ? 'selected' : '' ?> ><?= $categoryName ?></option>
<?
	}
?>
						</select>
					</td>
					<td align="center">
						Show strings from languages:
						<br />
						<select name="sLangs[]" multiple style="height: 90px;  width: 170px">
<?
	$arrLangs = getLocalizationLanguages();
	$arrLanguages[0] = '---All Languages---';

	foreach($arrLangs as $key => $val)
	{
		$arrLanguages[$key] = $val;
	}

	foreach ($arrLanguages as $langID => $langName)
	{
?>
							<option value="<?= $langID ?>" <?= isset($searchLanguages[$langID]) ? 'selected' : '' ?> ><?= $langName ?></option>
<?
	}
?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
</form>
<?
	$content = ob_get_contents();
	ob_end_clean();

	return panelSection("Search for strings", $content);
}

function addLangKeyForm()
{
	function addLangKey()
	{
		$magic_quotes = get_magic_quotes_gpc();
		$newKeyName = $magic_quotes ? $_POST['NewKeyName'] : addslashes($_POST['NewKeyName']);
		$newCategoryName = $magic_quotes ? $_POST['NewCategoryName'] : addslashes($_POST['NewCategoryName']);
		$categoryID = (int)$_POST['NewKeyCategory'];
		$newStringParams = $_POST['NewStringParameters'];
		$langStrings = $_POST['LangStrings'];

		if (!is_array($langStrings) || count($langStrings) < 1) return '<font color="red">Error: wrong form data.</font>';

		foreach ($langStrings as $key => $value)
		{
			if (!$magic_quotes) $langStrings[$key] = addslashes($value);
		}

		if ( strlen($newKeyName) <= 0 ) return '<font color="red">Error: Key name is not specified.</font>';

		if ( strlen($newCategoryName) > 0 )
		{
			mysql_query("INSERT INTO `LocalizationCategories` (`Name`) VALUES ('$newCategoryName')");
			$categoryID = mysql_insert_id();

			if ( $categoryID <= 0 ) return '<font color="red">Error: Could not create a new category.</font>';
		}
		else
		{
			$cnt = mysql_query('SELECT COUNT(*) FROM `LocalizationCategories` WHERE `ID`='.$categoryID);
			$cnt = mysql_fetch_row($cnt);

			if ( $cnt[0] <= 0 ) return '<font color="red">Error: Wrong category specified.</font>';
		}

		mysql_query("INSERT INTO `LocalizationKeys` (`IDCategory`, `Key`) VALUES ($categoryID, '$newKeyName')");

		$newKeyID = mysql_insert_id();

		if ( $newKeyID <= 0 ) return '<font color="red">Error: Could not insert a new language key.</font>';

		// parse string params

		$arrParams = array();

		preg_match_all('/([0-9]+) - (\S+[^\\n\\r]*)/', $newStringParams, $arrParams);

		foreach ($arrParams[1] as $key => $paramID)
		{
			$paramID = (int)$paramID;
			$paramDescription = $arrParams[2][$key];

			if (!$magic_quotes) $paramDescription = addslashes($paramDescription);

			mysql_query("
				INSERT INTO `LocalizationStringParams`
				(`IDKey`, `IDParam`, `Description`)
				VALUES ($newKeyID, $paramID, '$paramDescription')"
			);

			if (mysql_affected_rows() <= 0) return '<font color="red">Could not insert a string parameter.</font>';
		}

		foreach ($langStrings as $langKey => $langString)
		{
			//NOTE:	This piece of code can potentially
			//		insert language strings that don't belong
			//		to any language in case if a client generates
			//		his POST data himself in an unusual way.
			//		As this issue is not in fact dangerous for the script's
			//		security, no checks have been added to prevent this.

			$langKey = (int)$langKey;

			mysql_query("
				INSERT INTO `LocalizationStrings`
				(`IDKey`, `IDLanguage`, `String`)
				VALUES ($newKeyID, $langKey, '$langString')
			");

			if (mysql_affected_rows() <= 0) return '<font color="red">Error: could not add a language string.</font>';
		}

		if ( $_POST['RecompileLangFiles'] == 'on' )
		{
			if ( !compileLanguage() )
			{
				return '<font color="red">Error: could not recompile language files.</font>';
			}
		}

		return 'A new language key has been successfully created.';
	}
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Add a new language key</title>
	<style type="text/css">
	table.EditStringForm
	{
		font-family: Arial;
		font-size: 12px;
	}

	table.EditStringForm td
	{
		background-color: #e6e6e6;
		padding: 3px;
	}
	
	select#NewKeyCategory
	{
		width:130px;
	}
	</style>
</head>
<body>
<?
	if ( isset($_POST['AddLangKey']) )
	{
?>
<br /><br /><br /><br /><br /><br /><br /><br />
	<div align="center" style="font-family: Arial; font-size: 12px; height: 500px">
		<?= addLangKey() ?>
		<br />
		<br />
		<a href="javascript:void(0)" onclick="javascript: window.close(); return false;">Close</a>
	</div>
</body>
</html>
<?
		exit();
	}
?>
	<form action="<?= $_SERVER['PHP_SELF'] ?>?view=addLanguageKey" method="post">
	<table width="100%" class="EditStringForm" cellspacing="2">
		<tr>
			<td width="140"><b>New language key name:</b></td>
			<td><input name="NewKeyName" style="width: 99%"/></td>
		</tr>
		<tr>
			<td><b>Category:</b></td>
			<td>
				Use existing:
				<select name="NewKeyCategory" id="NewKeyCategory">
<?
				$arrCategories = getLocalizationCategories();
				foreach ($arrCategories as $catID => $catName)
				{
?>
					<option value="<?= $catID ?>"><?= htmlspecialchars($catName) ?></option>
<?
				}
?>
				</select>
				or create a new one:
				<input name="NewCategoryName" type="text" style="width: 100px"/>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Language string parameters:</b></td>
			<td>
			Describe string parameters, each on a separate line. Follow the example below:
<pre>
0 - member's nickname
1 - current membership name
</pre>
			Note that, if you don't exactly follow this format, parameters won't be saved.
			<br />
			<textarea name="NewStringParameters" rows="5" style="width: 99%"></textarea>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>String texts for available languages:</b></td>
			<td>
<?
				$arrLanguages = getLocalizationLanguages();
				foreach ($arrLanguages as $langID => $langName)
				{
					echo '<b>'.htmlspecialchars($langName).':</b>';
?>
					<br />
					<textarea name="LangStrings[<?= $langID ?>]" style="width: 99%"></textarea>
					<hr />
<?
				}
?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input name="RecompileLangFiles" type="checkbox" checked /> Recompile language files to apply changes.</td>
		</tr>
	</table>
	<div align="center"><br /><input type="submit" name="AddLangKey" value="Save changes" /></div>
	</form>
</body>
</html>
<?
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

function editStringForm()
{
	function updateLanguageString()
	{
		$keyID = (int)$_POST['UpdateString_KeyID'];
		$langID = (int)$_POST['UpdateString_LangID'];
		$string = get_magic_quotes_gpc() ? $_POST['UpdateString_String'] : addslashes($_POST['UpdateString_String']);

		mysql_query("
			UPDATE	`LocalizationStrings`
			SET		`String` = '$string'
			WHERE	`IDKey` = $keyID AND
					`IDLanguage` = $langID");

		if (mysql_affected_rows() <= 0)
		{
			return '<font color="red">The string has <b>NOT</b> been updated.</font>';
		}

		if ( $_POST['RecompileLangFile'] == 'on' )
		{
			if ( !compileLanguage($langID) )
			{
				return '<font color="red">Error: could not recompile language file.</font>';
			}
		}

		return 'The string has been successfully updated.';
	}

	$IDKey = (int)$_GET['editStringKeyID'];
	$IDLanguage = (int)$_GET['editStringLangID'];

	ob_start();

	$script = "
	function insertParam(strTextAreaID, strSelectParamsID)
	{
		textArea = document.getElementById(strTextAreaID);
		selectParams = document.getElementById(strSelectParamsID);
		selectedOption = selectParams.options[selectParams.selectedIndex];

		textArea.value = '' + textArea.value + selectedOption.value;

		textArea.focus();
	}";

	if ( isset($_POST['UpdateLangString']) )
	{
		$body = messageTemplate(updateLanguageString().'<br /><br /><a href="javascript:void(0)" onclick="javascript: window.close(); return false;">Close</a>');

		return PopupPageTemplate('Edit Language String', $body, $script);
	}

	$sql = "
		SELECT	`LocalizationStrings`.`IDKey`		AS `IDKey`,
				`LocalizationStrings`.`IDLanguage`	AS `IDLanguage`,
				`LocalizationKeys`.`Key`			AS `Key`,
				`LocalizationStrings`.`String`		AS `String`,
				`LocalizationLanguages`.`Name`		AS `Language`,
				`LocalizationCategories`.`Name`		AS `Category`

		FROM	`LocalizationStringParams`
				RIGHT JOIN `LocalizationKeys`
					ON (`LocalizationStringParams`.`IDKey` = `LocalizationKeys`.`ID`)

				LEFT JOIN `LocalizationStrings`
					ON (`LocalizationStrings`.`IDKey` = `LocalizationKeys`.`ID`)

				LEFT JOIN `LocalizationCategories`
					ON (`LocalizationKeys`.`IDCategory` = `LocalizationCategories`.`ID`)

				LEFT JOIN `LocalizationLanguages`
					ON (`LocalizationStrings`.`IDLanguage` = `LocalizationLanguages`.`ID`)

		WHERE	`LocalizationStrings`.`IDKey` = $IDKey AND `LocalizationStrings`.`IDLanguage` = $IDLanguage";

	$resLangString = mysql_query($sql);

	if ( mysql_num_rows($resLangString) <= 0 )
	{
		return PopupPageTemplate('Edit Language String', messageTemplate('Error: specified string not found.'));
	}
	else
	{
		$arrLangString = mysql_fetch_assoc($resLangString);
		ob_start();
?>
	<form action="<?= $_SERVER['PHP_SELF'] ?>?view=editLangString" method="post">
	<input name="UpdateString_KeyID" type="hidden" value="<?= $arrLangString['IDKey'] ?>" />
	<input name="UpdateString_LangID" type="hidden" value="<?= $arrLangString['IDLanguage'] ?>" />
	<table width="100%" class="EditStringForm" cellspacing="2">
		<tr>
			<td align="right" width="60"><b>Key:</b></td>
			<td><?= htmlspecialchars($arrLangString['Key']); ?></td>
		</tr>
		<tr>
			<td align="right" rowspan="2"><b>String:</b></td>
			<td><textarea name="UpdateString_String" id='StringTextArea' rows="16" style="width: 99%"><?= htmlspecialchars($arrLangString['String']); ?></textarea></td>
		</tr>
		<tr>
			<td>
			Available string parameters (click on a parameter to insert it at the end of the string):
			<br />
			<select id='SelectParams' multiple style="width: 100%; height: 100px" onclick="insertParam('StringTextArea', 'SelectParams');">
<?
	$arrParams = getLocalizationStringParams($arrLangString['IDKey']);
	$firstSelected = false;
	foreach ($arrParams as $paramID => $paramDescription)
	{
		if ( !$firstSelected )
		{
			$firstSelected = true;
			$selected = 'selected';
		}
		else
		{
			$selected = '';
		}
?>
				<option value="{<?= $paramID ?>}" <?= $selected ?>><?= '{'.$paramID.'} - '.htmlspecialchars($paramDescription) ?></option>
<?
	}
?>
			</select>
			</td>
		</tr>
		<tr>
			<td align="right"><b>Category:</b></td>
			<td><?= htmlspecialchars($arrLangString['Category']); ?></td>
		</tr>
		<tr>
			<td align="right"><b>Language:</b></td>
			<td><?= htmlspecialchars($arrLangString['Language']); ?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input name="RecompileLangFile" type="checkbox" checked /> Recompile corresponding language file to apply changes.</td>
		</tr>
	</table>
	<div align="center"><br /><input type="submit" name="UpdateLangString" value="Save changes" /></div>
	</form>
<?
		$body = ob_get_contents();
		ob_end_clean();

		return PopupPageTemplate('Edit Language String', $body, $script);
	}
}

function stringTableBlock($showMoreThanMax = false, $maxRowsToShow = 100)
{
	function htmlProcessedPrefix($string, $prefixLen = 15)
	{
		$strLen = strlen($string);

		if( $strLen <= 0) return '&nbsp;';

		$prefix = htmlspecialchars(substr($string, 0, $prefixLen));

		if ( $strLen > $prefixLen ) $prefix .= '<font color="#ee0000">...</font>';

		return $prefix;
	}

	function deleteLanguageKey()
	{
		$langKeyID = (int)$_POST['DeleteLangKey'];

		$resKey = mysql_query('SELECT `Key` FROM `LocalizationKeys` WHERE `ID` = '.$langKeyID);

		if ( mysql_num_rows($resKey) <= 0 ) return '<font color="red">Error: language key not found.</font>';

		mysql_query('DELETE FROM `LocalizationKeys` WHERE `ID`='.$langKeyID);

		mysql_query('DELETE FROM `LocalizationStrings` WHERE `IDKey` = '.$langKeyID);

		mysql_query('DELETE FROM `LocalizationStringParams` WHERE `IDKey` = '.$langKeyID);

		if (!compileLanguage()) return '<font color="red">Error: could not recompile language files.</font>';

		$arrKey = mysql_fetch_assoc($resKey);

		return '<font color="green">The <b>'.htmlspecialchars($arrKey['Key']).'</b> language key has been successfully removed.</font>';
	}

	if ( isset($_POST['DeleteLangKey']) )
	{
		$resultMsg = deleteLanguageKey();
	}

	global
		$searchString,
		$searchCombineWordsAs,
		$searchInKeys,
		$searchInStrings,
		$searchInParams,
		$searchCategories,
		$searchLanguages;

	$res = findLangStrings(
	$searchString,
	$searchCombineWordsAs,
	$searchInKeys,
	$searchInStrings,
	$searchInParams,
	$searchCategories,
	$searchLanguages);

	ob_start();
?>
	<a href="javascript:void(0)" onclick="popupForm('<?= $_SERVER['PHP_SELF'] ?>?view=addLanguageKey', 200, 100, 750, 500, 'yes'); return false;">Add a new language key</a><br /><hr />
<?

	echo $resultMsg.'<br />';

	$numRows = mysql_num_rows($res);

	if ( $numRows > $maxRowsToShow && !$showMoreThanMax)
	{
		$getQuery = $_SERVER["QUERY_STRING"];
		if(strlen($getQuery) > 0) $getQuery .= '&';
		$getQuery .= 'showMoreThanMax=yes';
?>
<div align="center" style="padding: 20px">
	Your search criteria result in <b><?= $numRows ?></b> strings.
	<br />
	Are you sure you want to show all of them?
	<br />
	<a href="<?= $_SERVER['PHP_SELF'].'?'.$getQuery ?>">Yes</a>
</div>
<?
		$content = ob_get_contents();
		ob_end_clean();

		return panelSection("Language strings", $content);
	}
?>
<table border="0" cellpadding="0" cellspacing="0" class="LanguagesStrings">
	<tr class="HeaderRow">
		<td>Key</td>
		<td>String</td>
		<td>Category</td>
		<td>Language</td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?
	if ( mysql_num_rows($res) <= 0)
	{
?>
	<tr>
		<td colspan="5" align="center" class="Left">No strings found matching your criteria.</td>
	</tr>
<?
	}
	while($arrString = mysql_fetch_assoc($res))
	{
?>
	<tr>
	<td class="Left"><?= htmlProcessedPrefix($arrString['Key'], 20) ?></td>
	<td><?= htmlProcessedPrefix($arrString['String'], 32) ?></td>
	<td><?= htmlProcessedPrefix($arrString['Category'], 25) ?></td>
	<td><?= htmlProcessedPrefix($arrString['Language'], 20) ?></td>
	<td width="20"><a
			href="javascript: void(0)"
			onclick="popupForm('<?=
				$_SERVER['PHP_SELF'].
				'?view=editLangString&editStringKeyID='.
				$arrString['IDKey'].
				'&editStringLangID='.
				$arrString['IDLanguage'] ?>', 300, 100, 600, 570, 'no'); return false;">Edit</a>
	</td>
	<td width="30">
		<form
			id="DeleteLangKeyForm_<?= $arrString['IDKey'] ?>"
			style="margin: 0px; padding: 0px"
			action="<?= htmlspecialchars($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']) ?>"
			method="post"><input name="DeleteLangKey" value="<?= $arrString['IDKey'] ?>" type="hidden"/></form><a
			href="javascript:void(0)"
			onclick="if(confirm('This operation will delete the language key itself and all corresponding language strings from all languages. Are you sure you want to continue?')){ document.getElementById('DeleteLangKeyForm_<?= $arrString['IDKey'] ?>').submit();} return false;">Delete</a>
	</td>
	</tr>
<?
	}
?>
</table>
<br />
<?
	$content = ob_get_contents();
	ob_end_clean();
	return panelSection("Language strings", $content);
}

// following function is unused already
/*function newLanguage($newLangName, $newLangCodepage, $sNewFlag, $oldLangID)
{
	$oldLangID = (int)$oldLangID;

	if(strlen($newLangName) <= 0) return false;

	if (!get_magic_quotes_gpc()) $newLangName = addslashes($newLangName);
	if (!get_magic_quotes_gpc()) $newLangCodepage = addslashes($newLangCodepage);
	$sFlag = htmlspecialchars_adv($sNewFlag);

	$resOldStrings = mysql_query("
		SELECT	`IDKey`, `String`
		FROM	`LocalizationStrings`
		WHERE	`IDLanguage` = $oldLangID
	");

	mysql_query("INSERT INTO `LocalizationLanguages` (`Name`, `Flag`) VALUES ('$newLangName', '$sNewFlag')");

	if(mysql_affected_rows() <= 0) return false;

	$newLangID = mysql_insert_id();

	while($arr = mysql_fetch_assoc($resOldStrings))
	{
		$arr['String'] = addslashes($arr['String']);
		mysql_query("
			INSERT INTO `LocalizationStrings`
			(`IDKey`, `IDLanguage`, `String`) VALUES
			('{$arr['IDKey']}', $newLangID, '{$arr['String']}')
			");
		if(mysql_affected_rows() <= 0) return false;
	}

	return true;
}*/

function editLanguageForm()
{
	$langID = (int)$_GET['editLanguageID'];

	$resLang = mysql_query('SELECT `Name`, `Flag` FROM `LocalizationLanguages` WHERE `ID` = '.$langID);

	if ( mysql_num_rows($resLang) <= 0 ) return PopupPageTemplate('Edit Language', messageTemplate('<font color="red">Error: language does not exist.</font>'));

	function updateLanguage($langID)
	{
		$langID = (int)$langID;

		$newLanguageName = get_magic_quotes_gpc() ? $_POST['LanguageName'] : addslashes($_POST['LanguageName']);
		$sNewFlag = htmlspecialchars_adv($_POST['Flag']);

		if ( strlen($newLanguageName) <= 0) return '<font color="red">Error: language name not specified.</font>';

		mysql_query("UPDATE `LocalizationLanguages` SET `Name`='$newLanguageName', `Flag` = '$sNewFlag' WHERE `ID` = $langID");

		if ( mysql_affected_rows() > 0 ) return 'Language has been successfully updated.';

		return '<font color="red">Error: language could not be updated.</font>';

	}

	if ( isset($_POST['UpdateLanguage']) )
	{
		return PopupPageTemplate('Edit Language', messageTemplate(updateLanguage($langID)));
	}

	$arrLang = mysql_fetch_assoc($resLang);

	ob_start();

	$isLangDefault = getParam('lang_default') == $arrLang['Name'];
?>
<form action="<?= $_SERVER['PHP_SELF'] ?>?view=editLanguage&editLanguageID=<?= $langID ?>" method="post">
<table>
	<tr>
		<td>Language name:</td>
		<td><input
			name="LanguageName"
			type="text"
			value="<?= htmlspecialchars($arrLang['Name']) ?>"
			<?= getParam('lang_default')==$arrLang['Name'] ? 'disabled' : '' ?>/></td>
	</tr>
	<tr>
		<td>Flag:</td>
		<td><?= showLangIcons($arrLang['Flag']); ?></td>
	</tr>
</table>
<br />
<center><input type="submit" name="UpdateLanguage" value="Save Changes" /></center>
</form>
<?
	$contents = ob_get_contents();
	ob_end_clean();

	return PopupPageTemplate('Edit Language', $contents);
}

function manageLanguagesBlock()
{
	global $site;
	function copyLanguage()
	{
		$newLangName = get_magic_quotes_gpc() ?
			$_POST['CopyLanguage_Name'] :
			addslashes($_POST['CopyLanguage_Name']);

		$sFlag = htmlspecialchars_adv($_POST['Flag']);

		$sourceLangID = (int)$_POST['CopyLanguage_SourceLangID'];

		if(strlen($newLangName) <= 0) return '<font color="red">Error: please specify a name for the new language.</font>';

		mysql_query("
			INSERT INTO `LocalizationLanguages`
			(`Name`, `Flag`) VALUES
			('$newLangName', '$sFlag')
		");

		if(mysql_affected_rows() <= 0) return '<font color="red">Error: could not add a new language to the database.</font>';

		$newLangID = mysql_insert_id();

		$resSourceLangStrings = mysql_query("
			SELECT	`IDKey`, `String`
			FROM	`LocalizationStrings`
			WHERE	`IDLanguage` = $sourceLangID
		");

		while($arr = mysql_fetch_assoc($resSourceLangStrings))
		{
			$arr['String'] = addslashes($arr['String']);
			mysql_query("
				INSERT INTO `LocalizationStrings`
				(`IDKey`, `IDLanguage`, `String`) VALUES
				('{$arr['IDKey']}', $newLangID, '{$arr['String']}')
				");
			if(mysql_affected_rows() <= 0) return '<font color="red">Error: could not add a language string to the database.</font>';
		}

		return '<font color="green"><b>'.htmlspecialchars(stripslashes($newLangName)).'</b> language has been successfully created.</font>';
	}

	function getLangName($langID)
	{
		$langName = mysql_query('SELECT `Name` FROM `LocalizationLanguages` WHERE `ID` = '.(int)$langID);
		$langName = mysql_fetch_row($langName);
		return $langName[0];
	}

	if ( $_POST['CopyLanguage'] )
	{
		$resultMsg = copyLanguage();
	}
	else if ( isset($_POST['CompileLanguage']) )
	{
		$langName = getLangName($_POST['CompileLanguage']);

		if ( compileLanguage((int)$_POST['CompileLanguage']) )
		{
			$resultMsg = '<font color="green"><b>'.htmlspecialchars($langName).'</b> language has been successfully compiled.</font>';
		}
		else
		{
			$resultMsg = '<font color="red>Error: could not compile a language.</font>';
		}
	}
	else if ( isset($_POST['DeleteLanguage']) )
	{
		$langName = getLangName($_POST['DeleteLanguage']);

		if ( $langName == getParam('lang_default'))
		{
			$resultMsg = '<font color="red">Cannot delete the default language. You have to set another default language for the site in <b>global settings -> Change language settings</b> to be able to delete this one.</font>';
		}
		else
		{
			if ( deleteLanguage((int)$_POST['DeleteLanguage']) )
			{
				$resultMsg = '<font color="green"><b>'.htmlspecialchars($langName).'</b> language has been successfully removed.</font>';
			}
			else
			{
				$resultMsg = '<font color="red">Error: could not delete a language.</font>';
			}
		}
	}

	ob_start();

	$arrLangs = getLocalizationLanguages();
	
	displayLanguageSettings();
	
	$contents = ob_get_contents();
	ob_end_clean();

	$sCon1 = panelSection('Language', $contents);
	
	ob_start();
?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" style="margin:0px">
New language:
<input type="text" name="CopyLanguage_Name" style="width: 80px" />
Copy from:
<select name="CopyLanguage_SourceLangID" style="width: 80px">
<?
	foreach ($arrLangs as $langID => $langName)
	{
?>
	<option value="<?= $langID ?>"><?= htmlspecialchars($langName) ?></option>
<?
	}
?>
</select>
Flag: 
<?
echo showLangIcons();
?>
<br />
<br />
<center>
<input type="submit" name="CopyLanguage" value="Create" />
</center>
</form>
<br />
<?
	if ( strlen($resultMsg)>0 ) echo $resultMsg.'<br /><br />';
?>
<table class="LanguagesStrings" cellpadding="0" cellspacing="0">
	<tr class="HeaderRow">
		<td width="85%" align="left">Language</td>
		<td width="15%" align="left">Flag</td>
		<td colspan="3">&nbsp;</td>
	</tr>
<?
	$resLangs = mysql_query('
		SELECT `ID`, `Name`, `Flag`
		FROM `LocalizationLanguages`
		ORDER BY `Name`
	');

	$defaultLangName = getParam('lang_default');

	while ($arrLang = mysql_fetch_assoc($resLangs))
	{
?>
	<tr>
		<td class="Left"><?= htmlspecialchars($arrLang['Name']).($arrLang['Name'] == $defaultLangName ? '<font color="red"> (default)</font>' : '') ?></td>
		<td><img src="<?= $site['flags'].$arrLang['Flag'].'.gif'?>"></td>
		<td><a href="javascript:void(0)" onclick="popupForm('<?= $_SERVER['PHP_SELF'] ?>?view=editLanguage&editLanguageID=<?= $arrLang['ID'] ?>', 500, 300, 300, 200, 'no'); return false;">Edit</a></td>
		<td>
			<form id="CompileForm_<?= $arrLang['ID'] ?>" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" style="padding: 0px; margin: 0px">
				<input type="hidden" name="CompileLanguage" value="<?= $arrLang['ID'] ?>" />
			</form>
			<a href="javascript:void(0)" onclick="document.getElementById('CompileForm_<?= $arrLang['ID'] ?>').submit(); return false;">Compile</a>
		</td>
		<td>
			<form id="DeleteForm_<?= $arrLang['ID'] ?>" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" style="padding: 0px; margin: 0px">
				<input type="hidden" name="DeleteLanguage" value="<?= $arrLang['ID'] ?>" />
			</form>
			<a href="javascript:void(0)" onclick="document.getElementById('DeleteForm_<?= $arrLang['ID'] ?>').submit(); return false;"><font color="red">Delete</font></a>
		</td>
	</tr>
<?
	}
?>
</table>
<?
	$contents = ob_get_contents();
	ob_end_clean();

	$sCon2 = panelSection('Language Files', $contents);
	
	return $sCon1.$sCon2;
}

function panelSection($header, $content, $padding = 10)
{
	ob_start();
	
	ContentBlockHead("$header");
	echo $content;
	ContentBlockFoot();

	$ret = ob_get_contents();
	ob_end_clean();

	return $ret;
}

function showLangIcons($sCurLang = '')
{
	global $site;
	
	$sCode = '';

	$sQuery = "SELECT `ISO2`, `Country` FROM `Countries` ORDER BY `Country`";
	$rCountryList = db_res($sQuery);
	
	$sOnChange = "onChange=\"javascript: flagImage = document.getElementById('flagImageId'); flagImage.src = '{$site['flags']}' + this.value + '.gif';\"";
	
	$sCode = '<select name="Flag" id="Flag" style="width:80px;" '.$sOnChange.'>';
	if (strlen($sCurLang) > 0)
	{
		$sDefC = $sCurLang;
	}
	else
	{
		$sDefC = strtolower(getParam( 'default_country' ));
	}
		
	while($aList = mysql_fetch_array($rCountryList))
	{
		$sFlagCode = strtolower($aList['ISO2']);
		$sSelect = $sDefC == $sFlagCode ? 'selected="selected"' : '' ;
		$sCode .= '<option value="'.$sFlagCode.'" '.$sSelect.'>'.$aList['Country'].'</option>';
		$sSelect = '';
	}
	$sCode .= '</select>';
	$sImageCode = '<img id="flagImageId" src="'. ($site['flags']) .$sDefC.'.gif" alt="flag" />';
	$sCode .= '&nbsp;'.$sImageCode;

	return $sCode;
}

function displayLanguageSettings()
{
    ?>
    <center>
    <form method="post" action="<? echo $_SERVER[PHP_SELF].'?cat=ls'; ?>">
    <input type="hidden" name="save_settings" value="yes">
    <table width="100%" cellspacing="2" cellpadding="3" class="text">
        <!--<tr class="panel">
            <td colspan=2><b> Change language settings </b></td>
        </tr>-->
        <tr class="table">
            <td align="right" width="50%"> <?php echo getParamDesc("lang_default") ?>: </td>
            <td align="left">
                <select name="lang_default">
                    <?php
                    $lang_arr = getLocalizationLanguages();
                    $old_val = getParam("lang_default");
                    foreach ($lang_arr as $val)
                    {
                        if ($old_val == $val)
                        {
                            echo "<option value=\"$val\" selected>$val</option>\n";
                        }
                        else
                        {
                            echo "<option value=\"$val\">$val</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr class="table">
            <td align="right" width="30%"> <?=getParamDesc("lang_enable") ?>: </td>
            <td align="left">
                <input type="checkbox" name="lang_enable" <?=(getParam("lang_enable")==1?"checked":"")?>>
            </td>
        </tr>
    </table>
    <br />
    <input class=no type="submit" value="Save changes" name="saveLangChanges" class=text>
    </form>
    </center>
    <?php
	
	return 'Languages';
}

function saveLanguageSettings()
{
    // save default language.
    if ($_POST['lang_default'])
    {
        setparam('lang_default', $_POST['lang_default']);
    }
    //
    if ('on' == $_POST['lang_enable'])
    {
        setparam('lang_enable', '1');
    }
    else
    {
        setparam('lang_enable', '0');
    }

   // echo '<div class="succ">Language settings changed.</div><br />';
}

function quickHelp()
{
	ob_start();
?>
<h3 style="font: Arial"><font size="3" face="Arial" color="black">What you should know about the new <b>Dolphin</b> language management system.</font></h3>
<font size="2" face="Arial" color="black">
The new <b>Dolphin</b> language management system is developed to ease your work with language
strings by managing them via this specially designed web interface rather than editing them directly
in a language file, unlike it was in previous <b>Dolphin</b> versions. Note that, in <b>Dolphin</b>,
you should <b>NOT</b> edit language files manually – they’re now used by
the script as temporary files with partial language information only for the sake of performance.
All necessary language information is now stored in the database and can be managed via this web interface.
<br /><br />
You can see an option of compiling languages in the top section of the language management interface.
<b>Compiling a language</b> means creating and writing a fresh version of a corresponding language file
in the <b>langs</b> folder of the script with the updated information about language strings and keys
stored in the database. After you change/add a language string or a language key, a corresponding language
file (or files) needs to be recompiled so the changes are applied and shown on the pages where that string
is used. At the bottom of the forms for editing and adding language strings/keys there are checkboxes that
specify whether to recompile corresponding language files automatically after the changes are saved. These
checkboxes are checked by default. You can uncheck them before submitting a form not to recompile language
files every time in case you’re editing a lot of language strings one by one. This option is available
because compiling a language file is a relatively resource-intensive process, so you better recompile
language files once after you’ve finished with editing language strings rather than loading your server by
recompiling language files after editing each string.</font>

<?
	$contents = ob_get_contents();
	ob_end_clean();

	return PopupPageTemplate('Language Management System Notes', $contents);
}

$logged['admin'] = member_auth(1);

$_page['header'] = 'Manage Languages'; // Set page title.

if ($_POST['saveLangChanges'])
{
	saveLanguageSettings();
}
// add/edit a language string
if ( $_GET['view'] == 'addLangKey' )
{
	echo addLangKeyForm();
	exit();
}
else if ( $_GET['view'] == 'editLangString')
{
	echo editStringForm();
	exit();
}
else if ( $_GET['view'] == 'editLanguage' )
{
	echo editLanguageForm();
	exit();
}
else if ( $_GET['view'] == 'addLanguageKey' )
{
	echo addLangKeyForm();
	exit();
}
else if ( $_GET['view'] == 'quickHelp' )
{
	echo quickHelp();
	exit();
}

// INPUT VALIDATION

$searchString = $_GET['find'];

$searchCombineWordsAs = $_GET['words'];

if ($searchCombineWordsAs != SEARCH_COMBINE_WORDS_AS_AND &&
	$searchCombineWordsAs != SEARCH_COMBINE_WORDS_AS_OR)
{
	$searchCombineWordsAs = SEARCH_COMBINE_WORDS_AS_EXACT;
}

$searchShowAdvancedSettings = $_GET['settings'] == 'yes' ? true : false;

$searchInKeys = $_GET['keys'] == 'yes' ? true : false;
$searchInStrings = $_GET['strings'] == 'yes' ? true : false;
$searchInParams = $_GET['params'] == 'yes' ? true : false;

if (!$searchInKeys && !$searchInStrings && !$searchInParams)
{
	$searchInKeys = $searchInStrings = $searchInParams = true;
}

$searchCategories = validateListArray($_GET['sCats']);
$searchLanguages = validateListArray($_GET['sLangs']);

TopCodeAdmin();

?>
<style type="text/css">
table.LanguagesStrings
{
	width: 100%;
}

table.LanguagesStrings td
{
	border-right: 1px solid silver;
	border-bottom: 1px solid silver;
	padding: 2px;
	background-color: #f5f5f5;
}

table.LanguagesStrings td.Left
{
	border-left: 1px solid silver;
}

table.LanguagesStrings tr.HeaderRow td
{
	font-weight: bold;
	background-color: #ccccde;
	border: 1px solid silver;
}
</style>

<script type="text/javascript">
function popupForm(url, left, top, width, height, scrollbars)
{
    var winSettings = "width="+width+
    ", height="+height+
    ", left="+left+
    ", top="+top+
    ", scrollbars="+scrollbars+
    ", copyhistory=no, directories=no, menubar=no, location=no, resizable=no";
    window.open(url, 'editString', winSettings);
    return false;
}
</script>


<?
ContentBlockHead("Please Pay Attention");
?>
<b>IMPORTANT:</b> <a href="javascript:void(0)" onclick="popupForm('<?= $_SERVER['PHP_SELF'] ?>?view=quickHelp', 350, 100, 500, 600, 'yes'); return false;">What you should know about the <b>Dolphin</b> language management system.</a>

<?
ContentBlockFoot();
echo manageLanguagesBlock();

echo searchBlock();

echo stringTableBlock($_GET['showMoreThanMax']=='yes' ? true : false);

BottomCode();
?>