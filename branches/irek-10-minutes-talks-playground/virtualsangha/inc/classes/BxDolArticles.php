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
	function BxDolArticles( $bAdminMode, $sCurrBrowsedFile='' ) {
		global $site;
		$this->bAdminMode = $bAdminMode;
		$this->iVisitorID = (int)$_COOKIE['memberID'];
		$this->sCurrBrowsedFile = ($sCurrBrowsedFile=='') ? $_SERVER['PHP_SELF'] : $sCurrBrowsedFile;

		if( $logged['admin'] ) {
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
	function getArticlesCategiriesList( $resurs = false ) {
		global $site;
		global $logged;
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

			if( $logged['admin'] ) {
				$ret .= <<<EOF
<div class="addLink">
	<a href="{$site['url_admin']}articles.php?action=addcategory">{$sAddCategoryC}</a>&nbsp;
	<a href="{$site['url_admin']}articles.php?action=addarticle">{$sAddNewArticleC}</a>
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
				$sAdminActions = '';
				if( $logged['admin'] ) {
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
		<a href="{$sUrl}articles.php?catID={$aCategory['CategoryID']}&amp;action=viewcategory">{$sCategoryName}</a>
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
		global $logged;
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
					SELECT `Title`, `Text`, DATE_FORMAT( `Date`, '{$short_date_format}' ) AS Date, `ArticlesID`, `ArticleFlag`
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

			$sArticleTitle = process_line_output( $aArticles['Title'] );

			$sArticleText = '';
			if( $aArticles['ArticleFlag'] == 'HTML' ) {
				$sArticleText = process_html_output( strmaxtextlen( strip_tags($aArticles['Text']), 200 ) ) . "\n";
			} else {
				$sArticleText = strmaxtextlen( $aArticles['Text'], 200 );
				//$sArticleText = process_text_output( strmaxtextlen( $aArticles['Text'], 200 ) ) . "\n";
			}

			$sAdminActions = '';
			if( $logged['admin'] ) {
				$sAdminActions .= <<<EOF
<div class="categoryEdit">
	<a href="{$site['url_admin']}articles.php?articleID={$aArticles['ArticlesID']}&amp;action=editarticle">{$sEditC}</a>&nbsp;
	<a href="{$site['url_admin']}articles.php?articleID={$aArticles['ArticlesID']}&amp;action=deletearticle" onclick="javascript: return confirm('{$sSureC}')">{$sDeleteC}</a>
</div>
EOF;
			}

			$ret .= <<<EOF
<div class="articleBlock{$sStyleAdd}">
	<div class="title">
		<a href="{$sUrl}articles.php?articleID={$aArticles['ArticlesID']}&amp;action=viewarticle">{$sArticleTitle}</a>
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
							`ArticlesID`, `CategoryName`, `ArticleFlag`
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
		fild += "\n Category Title ";
	} else {
		el.style.backgroundColor = "#fff";
	}

	el = document.getElementById("description");
	if( el.value.length < 3 ) {
		el.style.backgroundColor = "pink";
		hasErr = true;
		fild += "\n Category Description";
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
	<form method="post" action="{$site['url_admin']}articles.php" onsubmit="return checkForm();">
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
		global $site;
		global $site;

		$sEditArticleC = _t('_Edit Article');
		$sArticlesC = _t('_Articles');
		$sArticleC = _t('_Article');
		$sArticleTitleC = _t('_Article Title');
		$sSelectCategoryC = _t('_Select Category');
		$sPrintAsC = _t('_Print As');

		$rCatories = $this->getArticlesCategiriesList( true );

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
<span><a href="{$site['url_admin']}articles.php?catID={$aArticle['CategoryID']}&amp;action=viewcategory">{$aArticle['CategoryName']}</a></span>
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
	<span><a href="{$site['url_admin']}articles.php">{$sArticlesC}</a></span>
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
		fild += "\n Article Title";
	} else {
		el.style.backgroundColor = "#fff";
	}

	el = document.getElementById("articleBody");
	if( el.value.length < 3 ) {
		if (typeof tinyMCE != 'undefined') {//here Tiny
			if( tinyMCE.selectedElement.innerHTML.length < 3 ) {
				el.style.backgroundColor = "pink";
				hasErr = true;
				fild += "\n Article text";
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
		fild += "\n Category ";
	} else {
		el.style.backgroundColor = "#fff";
	}

	el = document.getElementById("flag");
	if( el.value.length < 1 ) {
		el.style.backgroundColor = "pink";
		hasErr = true;
		fild += "\n Text type ";
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
	<form method="post" action="{$site['url_admin']}articles.php" onsubmit="return checkForm();">
		<div>{$sArticleTitleC}</div>
		<div>
			<input type="text" name="title" id="articleTitle" class="catCaption" value="{$sTitle}" />
		</div>
		<div>{$sArticleC}</div>
		<div style="margin-bottom:7px;">
			<textarea name="article" id="articleBody"  class="articl">{$aArticle['Text']}</textarea>
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
		global $logged;

		$sADS = _t('_Articles Deleted Successfully');
		$sADF = _t('_Articles are not deleted');
		$sCDS = _t('_Category Deleted Successfully');
		$sCDF = _t('_Category are not deleted');

		$sRetVal = _t('_Error Occured');
		if( $logged['admin'] ) {
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
		global $logged;

		$sADS = _t('_Article Deleted Successfully');
		$sADF = _t('_Article are not deleted');

		$sRetVal = _t('_Error Occured');
		if( $logged['admin'] ) {
			$sArticleDeleteQuery = "DELETE FROM `Articles` WHERE `ArticlesID` = '{$iArticleID}';";
			if( db_res( $sArticleDeleteQuery ) ) {
				$sRetVal = $sADS;
			} else {
				$sRetVal = $sADF;
			}
		} else {
		}
		return MsgBox($sRetVal);
	}

	function ActionAddUpdateElements() {
		$sActionText = '';
		if( $_POST['add_category'] ) {
			$sCategorySubject = process_db_input( $_POST['caption'] );
			$sCategoryDesc = process_db_input( $_POST['description'] );

			$sAddQuery = "INSERT INTO `ArticlesCategory` SET `CategoryName` = '{$sCategorySubject}', `CategoryDescription` = '{$sCategoryDesc}';";
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

			$sAddQuery = "INSERT INTO `Articles` SET `Title` = '{$sArticleTitle}', `Text` = '{$sArticle}', `CategoryID` = '{$iCategoryID}', `Date` = NOW(), `ArticleFlag` = '{$sFlag}';";
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
			if( $_POST['flag'] == 'HTML') {
				$sFlag = 'HTML';
			} else {
				$sFlag = 'Text';
			}

			$sAddQuery = "UPDATE `Articles` SET `Title` = '{$sArticleTitle}', `Text` = '{$sArticle}', `CategoryID` = '{$iCategoryID}', `Date` = NOW(), `ArticleFlag` = '{$sFlag}' WHERE `ArticlesID` = '{$iArticleID}';";
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
}

?>