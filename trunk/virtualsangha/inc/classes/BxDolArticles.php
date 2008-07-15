<?
require_once(BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'admin.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

/*
 * class for Events
 */
class BxDolArticles {

	//admin mode, can All actions
	var $bAdminMode;

	var $sCurrBrowsedFile;

	var $iVisitorID;

	var $sUrl;
	
	//constructor
	function BxDolArticles( $logged, $sCurrBrowsedFile='' ) {
		global $site;

		$this->bAdminMode = $logged['admin'] ? true : false;
		$this->iVisitorID = (int)$_COOKIE['memberID'];
		$this->sCurrBrowsedFile = ($sCurrBrowsedFile=='') ? $_SERVER['PHP_SELF'] : $sCurrBrowsedFile;

		if( $this->bAdminMode ) {
			$this->sUrl = $site['url_admin'];
		} else {
			$this->sUrl = $site['url'];
		}
	}

	/**
	 * 
	 *
	 * @param 
	 * @return HTML presentation of data
	 */
	function getArticlesCategoriesList( $resurs = false ) {
		global $site;
		global $sUrl;

		$sAddCategoryC = _t('_Add Category');
		$sAddNewArticleC = _t('_Add New Article');
		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sSureC = _t('_are you sure?');

		$sCategQuery = "
				SELECT
						`CategoryID`,
						`CategoryName`,
						`CategoryDescription`
				FROM
						`ArticlesCategory`
				ORDER BY `CategoryID` ASC
		";
		$rCategory = db_res( $sCategQuery );

		if( $resurs ) {
			return $rCategory;
		} else {
			$ret = '';

			$sActions = '';
			if( $this->bAdminMode ) {
				$sActions .= <<<EOF
<a href="{$this->sUrl}articles.php?action=addcategory">{$sAddCategoryC}</a>&nbsp;
EOF;
			}
			if ($this->bAdminMode || $this->iVisitorID) {
			$ret .= <<<EOF
<div class="addLink">
	{$sActions}
	<a href="{$this->sUrl}articles.php?action=addarticle">{$sAddNewArticleC}</a>
</div>
EOF;
}
			$i = '';
			while ($aCategory = mysql_fetch_assoc( $rCategory )) {
				if ( ($i%2) == 0 ) {
					$sStyleAdd = '1';
				} else {
					$sStyleAdd = '2';
				}

				$sCategoryName = process_line_output( $aCategory['CategoryName'] );
				$sCategoryDescription = process_html_output( $aCategory['CategoryDescription'] );
				$sCategUrl = $this->getArticleCatUrl($aCategory['CategoryID']);

				$sAdminActions = '';
				if( $this->bAdminMode) {
					$sAdminActions .= <<<EOF
<div class="categoryEdit">
	<a href="{$sUrl}articles.php?catID={$aCategory['CategoryID']}&amp;action=categoryedit">{$sEditC}</a>&nbsp;
	<a href="{$sUrl}articles.php?catID={$aCategory['CategoryID']}&amp;action=categorydelete" onclick="javascript: return confirm('Do you realy want to delete category {$aCategory['CategoryName']} and all its articles ')">{$sDeleteC}</a>
</div>
EOF;
				}

				$ret .= <<<EOF
<div class="categoryBlock{$sStyleAdd}">
	<div class="categoryCaption">
		<a href="{$sCategUrl}">{$sCategoryName}</a>
	</div>
	<div class="clear_both"></div>
	<div class="categoryDescription">
		{$sCategoryDescription}
	</div>
	{$sAdminActions}
</div>
EOF;

				$i++;
			}
			return $ret;
		}
	}

	function getArticlesList( $iCategoryID ) {
		global $sUrl;
		global $site;
		global $short_date_format;

		$sArticlesC = _t('_Articles');
		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sSureC = _t('_are you sure?');

		$iCategoryID = (int)$iCategoryID;
		if( !(int)$iCategoryID ) {
			return '';
		} else {
			$sCategoryQuery = "
					SELECT `CategoryName`, `CategoryDescription`
					FROM `ArticlesCategory`
					WHERE `CategoryID` = '{$iCategoryID}'
					LIMIT 1;
			";
			$aCategory = db_arr( $sCategoryQuery );
		}

		$sArticlesQuery = "
					SELECT `Title`, `Text`, DATE_FORMAT( `Date`, '{$short_date_format}' ) AS Date, `ArticlesID`, `ArticleFlag`, `ownerID`
					FROM `Articles`
					WHERE `CategoryID` = '{$iCategoryID}'
		";
		$rArticles = db_res( $sArticlesQuery );

		$sCategoryName = process_line_output( $aCategory['CategoryName'] );
		$sCategoryDescription = process_text_output( $aCategory['CategoryDescription'] );


		$ret = <<<EOF
<div class="navigationLinks">
	<span><a href="{$sUrl}articles.php">{$sArticlesC}</a></span>
	<span>&gt;</span>
	<span>{$sCategoryName}</span>
</div>
<div class="categoryHeader">
	<div class="artCaption">{$sCategoryName}</div>
	<div>
		{$sCategoryDescription}
	</div>
</div>
EOF;

		$i = '';
		while ( $aArticles = mysql_fetch_assoc( $rArticles )) {
			if ( ($i%2) == 0 ) {
				$sStyleAdd = '1';
			} else {
				$sStyleAdd = '2';
			}

			$sArticleUrl = $this->getArticleUrl($aArticles['ArticlesID']);
			$sArticleTitle = process_line_output( $aArticles['Title'] );

			$sArticleText = '';
			if( $aArticles['ArticleFlag'] == 'HTML' ) {
				$sArticleText = process_html_output( strmaxtextlen( strip_tags($aArticles['Text']), 200 ) ) . "\n";
			} else {
				$sArticleText = strmaxtextlen( $aArticles['Text'], 200 );
				//$sArticleText = process_text_output( strmaxtextlen( $aArticles['Text'], 200 ) ) . "\n";
			}

			$sAdminActions = '';
			if( $this->bAdminMode || ($aArticles['ownerID'] != 0 && $aArticles['ownerID'] == $this->iVisitorID )) {
				$sAdminActions .= <<<EOF
<div class="categoryEdit">
	<a href="{$this->sUrl}articles.php?articleID={$aArticles['ArticlesID']}&amp;action=editarticle">{$sEditC}</a>&nbsp;
	<a href="{$this->sUrl}articles.php?articleID={$aArticles['ArticlesID']}&amp;action=deletearticle" onclick="javascript: return confirm('{$sSureC}')">{$sDeleteC}</a>
</div>
EOF;
			}

			$ret .= <<<EOF
<div class="articleBlock{$sStyleAdd}">
	<div class="title">
		<a href="{$sArticleUrl}">{$sArticleTitle}</a>
	</div>
	<div class="date">{$aArticles['Date']}</div>
	<div class="preview">
		{$sArticleText}
	</div>
	{$sAdminActions}
</div>
EOF;
			$i++;
		}
		return $ret;
	}

	function getArticle( $iArticleID ) {
		global $short_date_format;

		$sArticlesC = _t('_Articles');

		if( !(int)$iArticleID ) {
			return '';
		} else {
			$sArticleQuery = "
					SELECT `Title`, `Text`, `Articles`.`CategoryID`, DATE_FORMAT( `Date`, '{$short_date_format}' ) AS Date,
							`ArticlesID`, `CategoryName`, `ArticleFlag`, `ownerID`
					FROM `Articles`
					INNER JOIN `ArticlesCategory` ON `Articles`.`CategoryID` = `ArticlesCategory`.`CategoryID`
					WHERE `ArticlesID` = '{$iArticleID}'
					LIMIT 1;
			";
		}
		$aArticle = db_arr( $sArticleQuery );

		$sCategoryName = process_line_output( $aArticle['CategoryName'] );
		$sTitle = process_line_output( $aArticle['Title']);
		$sText = '';
		if( $aArticle['ArticleFlag'] == 'HTML' ) {
			$sText = process_html_output( $aArticle['Text'] ) . "\n";
		} else {
			$sText = process_text_output( $aArticle['Text'] ) . "\n";
		}

	$ret = <<<EOF
<div class="navigationLinks">
	<span><a href="articles.php">{$sArticlesC}</a></span>
	<span>&gt;</span>
	<span><a href="articles.php?catID={$aArticle['CategoryID']}&amp;action=viewcategory">{$sCategoryName}</a></span>
	<span>&gt;</span>
	<span>{$sTitle}</span>
</div>
<div class="articleBlock">
	<div class="mainTitle">{$sTitle}</div>
	<div class="date">{$aArticle['Date']}</div>
	<div>{$sText}</div>
</div>
EOF;
		return $ret;
	}

	function getArticlesCategoryEditForm( $iCategoryID = '' ) {
		global $site;

		$sArticlesC = _t('_Articles');
		$sCategoryCaptionC = _t('_Category Caption');
		$sCategoryDescriptionC = _t('_category_description');

		$ret = '';

		if( (int)$iCategoryID ) {
			$sEditCategoryQuery = "
				SELECT `CategoryID`, `CategoryName`, `CategoryDescription`
				FROM `ArticlesCategory`
				WHERE `CategoryID` = '{$iCategoryID}'
				LIMIT 1;
			";
			$aCategory = db_arr( $sEditCategoryQuery );
		}

		$ret .= <<<EOF
<div class="navigationLinks">
	<span><a href="articles.php">{$sArticlesC}</a></span>
</div>
EOF;

		$ret .= <<<EOF
<script type="text/javascript">
function checkForm() {
	var el;
	var hasErr = false;
	var fild = "";

	el = document.getElementById("caption");
	if( el.value.length < 3 ) {
		el.style.backgroundColor = "pink";
		hasErr = true;
		fild += " Category Title ";
	} else {
		el.style.backgroundColor = "#fff";
	}

	el = document.getElementById("description");
	if( el.value.length < 3 ) {
		el.style.backgroundColor = "pink";
		hasErr = true;
		fild += " Category Description";
	} else {
		el.style.backgroundColor = "#fff";
	}

	if (hasErr) {
		alert( "Please fill next fields first!" + fild )
		return false;
	} else {
		return true;
	}
}
</script>
EOF;

		$sFormInputs = '';
		if( (int)$iCategoryID ) {
			$sFormInputs .= '<input type="hidden" name="edit_category" value="true" />' . "\n";
			$sFormInputs .= '<input type="hidden" name="categoryID" value="' . $iCategoryID . '" />' . "\n";
		} else {
			$sFormInputs .= '<input type="hidden" name="add_category" value="true" />' . "\n";
		}

		$sCategoryName = process_line_output( $aCategory['CategoryName'] );
		$sCategoryDescription = process_text_output( $aCategory['CategoryDescription'] );

		$ret .= <<<EOF
<div class="articlesFormBlock">
	<form method="post" action="{$this->sUrl}articles.php" onsubmit="return checkForm();">
		<div>{$sCategoryCaptionC}</div>
		<div>
			<input type="text" name="caption" id="caption" class="catCaption" value="{$sCategoryName}" />
		</div>
		<div>{$sCategoryDescriptionC}</div>
		<div>
			<textarea name="description"  id="description" class="catDesc">{$sCategoryDescription}</textarea>
		</div>
		<div>
			<input type="submit" value="Submit">
			{$sFormInputs}
		</div>
	</form>
</div>
EOF;

		return $ret;
	}

	function getArticleEditForm( $iArticleID = '' ) {
		$sEditArticleC = _t('_Edit Article');
		$sArticlesC = _t('_Articles');
		$sArticleC = _t('_Article');
		$sArticleTitleC = _t('_Article Title');
		$sSelectCategoryC = _t('_Select Category');
		$sPrintAsC = _t('_Print As');

		$rCatories = $this->getArticlesCategoriesList( true );

		if( (int)$iArticleID ) {
			$articleQuery = "
				SELECT `Articles`.`ArticlesID`, `Articles`.`CategoryID`, `Articles`.`Date`,
						`Articles`.`Title`, `Articles`.`Text`, `Articles`.`ArticleFlag`, `ArticlesCategory`.`CategoryName`
				FROM `Articles`
				INNER JOIN `ArticlesCategory` ON  `ArticlesCategory`.`CategoryID` = `Articles`.`CategoryID`
				WHERE `Articles`.`ArticlesID` = '{$iArticleID}';
			";
			$aArticle = db_arr( $articleQuery );
		}

		$sCategoryNameAd = '';
		if( $iArticleID && strlen( $aArticle['CategoryName'] ) ) {
			$sCategoryNameAd = <<<EOF
<span>&gt;</span>
<span><a href="{$this->sUrl}articles.php?catID={$aArticle['CategoryID']}&amp;action=viewcategory">{$aArticle['CategoryName']}</a></span>
<span>&gt;</span>
<span>{$sEditArticleC}</span>
EOF;
		}

		$sRetCateg = '';
		while ( $aCategory = mysql_fetch_assoc( $rCatories ) ) {
			if( $aArticle['CategoryID'] == $aCategory['CategoryID'] ) {
				$sSelectedCategory = ' selected="selected"';
			} else {
				$sSelectedCategory = '';
			}
			$sRetCateg .= '<option value="' . $aCategory['CategoryID'] . '"' . $sSelectedCategory . '>' . process_line_output( strmaxtextlen( $aCategory['CategoryName'], 50 ) ) . '</option>' . "\n";
		}

		$sArticleActions = '';
		if( (int)$iArticleID ) {
			$sArticleActions .= '<input type="hidden" name="edit_article" value="true" />' . "\n";
			$sArticleActions .= '<input type="hidden" name="articleID" value="' . $iArticleID . '" />' . "\n";
		} else {
			$sArticleActions .= '<input type="hidden" name="add_article" value="true" />' . "\n";
		}

		$sTitle = process_line_output( $aArticle['Title'] );

		$textSelected = ( $aArticle['ArticleFlag'] == 'Text' ) ? ' selected="selected"' : '';
		$htmlSelected = ( $aArticle['ArticleFlag'] == 'HTML' ) ? ' selected="selected"' : '';

		$ret = <<<EOF
<div class="navigationLinks">
	<span><a href="{$this->sUrl}articles.php">{$sArticlesC}</a></span>
	{$sCategoryNameAd}
</div>

<script type="text/javascript">
function checkForm() {
	var el;
	var hasErr = false;
	var fild = "";
	el = document.getElementById("articleTitle");
	if( el.value.length < 3 ) {
		el.style.backgroundColor = "pink";
		hasErr = true;
		fild += " Article Title";
	} else {
		el.style.backgroundColor = "#fff";
	}

	el = document.getElementById("articleBody");
	if( el.value.length < 3 ) {
		if (typeof tinyMCE != 'undefined') {//here Tiny
			if( tinyMCE.selectedElement.innerHTML.length < 3 ) {
				el.style.backgroundColor = "pink";
				hasErr = true;
				fild += " Article text";
			} else {
				el.style.backgroundColor = "#fff";
			}
		}
	} else {
		el.style.backgroundColor = "#fff";
	}

	el = document.getElementById("categoryID");
	if( el.value.length < 1 ) {
		el.style.backgroundColor = "pink";
		hasErr = true;
		fild += " Category ";
	} else {
		el.style.backgroundColor = "#fff";
	}

	el = document.getElementById("flag");
	if( el.value.length < 1 ) {
		el.style.backgroundColor = "pink";
		hasErr = true;
		fild += " Text type ";
	} else {
		el.style.backgroundColor = "#fff";
	}

	if (hasErr) {
		alert( "Please fill next fields first!" + fild )
		return false;
	} else {
		return true;
	}
}
</script>

<div class="articlesFormBlock">
	<form method="post" action="{$this->sUrl}articles.php" onsubmit="return checkForm();">
		<div>{$sArticleTitleC}</div>
		<div>
			<input type="text" name="title" id="articleTitle" class="catCaption" value="{$sTitle}" />
		</div>
		<div>{$sArticleC}</div>
		<div style="margin-bottom:7px;">
			<textarea name="article" id="articleBody" class="classfiedsTextArea articl">{$aArticle['Text']}</textarea>
		</div>
		<div style="margin-bottom:7px;">
			<select name="categoryID" id="categoryID">
				<option value="">{$sSelectCategoryC}</option>
				{$sRetCateg}
			</select>
		</div>
		<div style="margin-bottom:7px;">
			<input type="hidden" name="flag" value="HTML" />
			<!-- <select name="flag" id="flag">
				<option value="">{$sPrintAsC}</option>
				<option value="Text"{$textSelected}>Text</option>
				<option value="HTML"{$htmlSelected}>HTML</option>
			</select> -->
		</div>
		<div>
			<input type="submit" value="Submit">
			{$sArticleActions}
		</div>
	</form>
</div>
EOF;
		return $ret;
	}

	function deleteCategory( $iCategoryID ) {

		$sADS = _t('_Articles were deleted successfully');
		$sADF = _t('_Articles are not deleted');
		$sCDS = _t('_category_deleted');
		$sCDF = _t('_category_delete_failed');

		$sRetVal = _t('_Error Occured');
		if( $this->bAdminMode ) {
			$sCategoryDeleteQuery = "DELETE FROM `ArticlesCategory` WHERE `CategoryID` = '{$iCategoryID}' LIMIT 1;";
			$sCategoriesArticlesDeleteQuery = "DELETE FROM `Articles` WHERE `CategoryID` = '{$iCategoryID}';";

			if( db_res( $sCategoriesArticlesDeleteQuery ) ) {
				$sRetVal = $sADS;
			} else {
				$sRetVal = $sADF;
			}

			if( db_res( $sCategoryDeleteQuery ) ) {
				$sRetVal = $sCDS;
			} else {
				$sRetVal = $sCDF;
			}
		} else {
		}
		return MsgBox($sRetVal);
	}

	function deleteArticle( $iArticleID ) {

		$sADS = _t('_Article was deleted successfully');
		$sADF = _t('_Article was not deleted');

		$sRetVal = _t('_Error Occured');
		if( $this->bAdminMode )
			$sArticleDeleteQuery = "DELETE FROM `Articles` WHERE `ArticlesID` = '{$iArticleID}'";
		else
			$sArticleDeleteQuery = "DELETE FROM `Articles` WHERE `ArticlesID` = '{$iArticleID}' AND `ownerID`='{$this->iVisitorID}'";
		
		if( db_res( $sArticleDeleteQuery ) )
			$sRetVal = $sADS;
		else
			$sRetVal = $sADF;

		return MsgBox($sRetVal);
	}

	function ActionAddUpdateElements() {
		$sActionText = '';
		if( $_POST['add_category'] ) {
			$sCategorySubject = process_db_input( $_POST['caption'] );
			$sCategoryDesc = process_db_input( $_POST['description'] );
			$sCategoryUri = uriGenerate(process_db_input( $sCategorySubject ), 'ArticlesCategory', 'CategoryUri', 255);

			$sAddQuery = "INSERT INTO `ArticlesCategory` SET `CategoryName` = '{$sCategorySubject}', `CategoryDescription` = '{$sCategoryDesc}', `CategoryUri`='{$sCategoryUri}'";
			if ($sCategorySubject=='' || $sCategoryDesc=='') {
				$sActionText = 'Category didn\'t add';
			}
			elseif( db_res( $sAddQuery ) ) {
				$sActionText = 'Category Added';
			} else {
				$sActionText = 'Category didn\'t add';
			}
		} elseif( $_POST['edit_category'] ) {
			$sCategorySubject = process_db_input( $_POST['caption'] );
			$sCategoryDesc = process_db_input( $_POST['description'] );
			$iCategoryID = (int)$_POST['categoryID'];

			$sAddQuery = "UPDATE `ArticlesCategory` SET `CategoryName` = '{$sCategorySubject}', `CategoryDescription` = '{$sCategoryDesc}' WHERE `CategoryID` = '{$iCategoryID}' LIMIT 1;";
			if ($sCategorySubject=='' || $sCategoryDesc=='') {
				$sActionText = 'Category didn\'t add';
			}
			elseif( db_res( $sAddQuery ) ) {
				$sActionText = 'Category Udated';
			} else {
				$sActionText = 'Category didn\'t updated';
			}
		} elseif( $_POST['add_article'] ) {
			$sArticleTitle = process_db_input( $_POST['title'] );
			$sArticle = process_db_input( $_POST['article'] );
			$iCategoryID = (int)$_POST['categoryID'];
			if( $_POST['flag'] == 'HTML') {
				$sFlag = 'HTML';
			} else {
				$sFlag = 'Text';
			}
			
			$sArticleUri = uriGenerate($sArticleTitle, 'Articles', 'ArticleUri', 100);
			
			$sOwner = $this->bAdminMode ? "" : ", `ownerID` = '{$this->iVisitorID}'";
			$sAddQuery = "INSERT INTO `Articles` SET `Title` = '{$sArticleTitle}', `Text` = '{$sArticle}', `CategoryID` = '{$iCategoryID}', `Date` = NOW(), `ArticleFlag` = '{$sFlag}', `ArticleUri`='{$sArticleUri}' $sOwner";
			if ($sArticleTitle=='' || $sArticle=='') {
				$sActionText = 'Article Not Added';
			}
			elseif( db_res( $sAddQuery ) ) {
				$sActionText = 'Article Added';
			} else {
				$sActionText = 'Article Not Added';
			}
		} elseif( $_POST['edit_article'] ) {
			$sArticleTitle = process_db_input( $_POST['title'] );
			$sArticle = process_db_input( $_POST['article'] );
			$iCategoryID = (int)$_POST['categoryID'];
			$iArticleID = (int)$_POST['articleID'];
			$sArticleUri = uriGenerate($sArticleTitle, 'Articles', 'Title', 100);
			if( $_POST['flag'] == 'HTML') {
				$sFlag = 'HTML';
			} else {
				$sFlag = 'Text';
			}

			if ($this->bAdminMode) {
				$sOwner = ", `ownerID`='0'";
				$sOwnerCond = "";
			}
			else {
				$sOwner = ", `ownerID`='{$this->iVisitorID}'";
				$sOwnerCond = " AND `ownerID`='{$this->iVisitorID}'";
			}
			$sAddQuery = "UPDATE `Articles` SET `Title` = '{$sArticleTitle}', `Text` = '{$sArticle}', `CategoryID` = '{$iCategoryID}', `Date` = NOW(), `ArticleFlag` = '{$sFlag}', `ArticleUri`='{$sArticleUri}' $sOwner WHERE `ArticlesID` = '{$iArticleID}' $sOwnerCond";
			if ($sArticleTitle=='' || $sArticle=='') {
				$sActionText = 'Article Updated';
			}
			elseif( db_res( $sAddQuery ) ) {
				$sActionText = 'Article Updated';
			} else {
				$sActionText = 'Article Not Updated';
			}
		}
		return ($sActionText!='') ? MsgBox($sActionText) : '';
	}
	
	function getArticleIdByUri($sName)
	{
	    $sName = process_db_input($sName);
		return  db_value( "SELECT `ArticlesID` FROM `Articles` WHERE `ArticleUri` = '$sName'" );
	}
	
	function getArticleCatIdByUri($sName)
	{
	    $sName = process_db_input($sName);
		return  db_value( "SELECT `CategoryID` FROM `ArticlesCategory` WHERE `CategoryUri` = '$sName'" );
	}
	
	function getArticleUrl($iArticleId)
	{
		global $sUrl;

		$iArticleId = (int)$iArticleId;
		if ($this->isPermalinkEnabled() && $this->bAdminMode == false) {
			$sArticleUri = db_value("SELECT `ArticleUri` FROM `Articles` WHERE `ArticlesID`='{$iArticleId}'");
			$sLinkUrl = $sUrl.'articles/entry/'.$sArticleUri;
		}
		else
			$sLinkUrl = $sUrl.'articles.php?action=viewarticle&articleID='.$iArticleId;
		
		return $sLinkUrl;
	}
	
	function getArticleCatUrl($iCategoryId)
	{
		global $sUrl;

		$iArticleId = (int)$iArticleId;
		if ($this->isPermalinkEnabled() && $this->bAdminMode == false) {
			$sCategoryUri = db_value("SELECT `CategoryUri` FROM `ArticlesCategory` WHERE `CategoryID`='{$iCategoryId}'");
			$sLinkUrl = $sUrl.'articles/category/'.$sCategoryUri;
		}
		else
			$sLinkUrl = $sUrl.'articles.php?action=viewcategory&catID='.$iCategoryId;
		
		return $sLinkUrl;
	}

    function isPermalinkEnabled()
    {
        return isset($this->_isPermalinkEnabled) ? $this->_isPermalinkEnabled : ($this->_isPermalinkEnabled = (getParam('permalinks_articles') == 'on'));
    }
    
    function getArticlesResource($iLimit = 1)
    {
    	$iLimit = (int)$iLimit;
    	
    	$sQuery = "
		SELECT
			`ArticlesID`,
			`Articles`.`CategoryID`,
			`Date`,
			UNIX_TIMESTAMP(`Date`) AS 'Date_UTS',
			`Title`,
			`Text`,
			`CategoryName`,
			`ownerID`
		FROM `Articles`
		INNER JOIN `ArticlesCategory` USING( `CategoryID` )
		ORDER BY `Date` DESC
		LIMIT $iLimit
	";
	
		$rArticles = db_res($sQuery);
	    
		return $rArticles;
    }
    
}

?>