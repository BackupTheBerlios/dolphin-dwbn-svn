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

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
{
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
	{
		if ( !( $logged['aff'] = member_auth( 2, false )) )
		{
			$logged['moderator'] = member_auth( 3, false );
		}
	}
}




if( $logged['admin'] )
{
	$sUrl = $site['url_admin'];
}
else
{
	$sUrl = $site['url'];
}





function getArticlesCategiriesList( $resurs = false )
{
	global $site;
	global $logged;
	global $sUrl;

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

	if( $resurs )
	{
		return $rCategory;
	}
	else
	{

		$ret = '';
		//$ret .= print_r($logged, true) . '<hr>';
		//$ret .= print_r($site, true) . "\n";

		if( $logged['admin'] )
		{
			$ret .= '<div class="addLink">' . "\n";
				$ret .= '<a href="' . $site['url_admin'] . 'articles.php?action=addcategory">' . "\n";
					$ret .= 'Add New Category' . "\n";
				$ret .= '</a>' . "\n||";
				$ret .= '<a href="' . $site['url_admin'] . 'articles.php?action=addarticle">' . "\n";
					$ret .= 'Add New Article' . "\n";
				$ret .= '</a>' . "\n";
			$ret .= '</div>' . "\n";
		}
		
		$i = '';
		while ($aCategory = mysql_fetch_assoc( $rCategory ))
		{
			if ( ($i%2) == 0 )
			{
				$sStyleAdd = '1';
			}
			else
			{
				$sStyleAdd = '2';
			}


			$ret .= '<div class="categoryBlock' . $sStyleAdd . '">' . "\n";
				$ret .= '<div class="categoryCaption">' . "\n";
					$ret .= '<a href="' . $sUrl . 'articles.php?catID=' . $aCategory['CategoryID'] . '&amp;action=viewcategory">' . "\n";
						$ret .= process_line_output( $aCategory['CategoryName'] ) . "\n";
					$ret .= '</a>' . "\n";

				$ret .= '</div>' . "\n";
				$ret .= '<div class="clear_both"></div>' . "\n";
				
				$ret .= '<div class="clear_both"></div>' . "\n";
				$ret .= '<div class="categoryDescription">' . "\n";
					$ret .= process_html_output( $aCategory['CategoryDescription'] ) . "\n";
				$ret .= '</div>' . "\n";
				
					if( $logged['admin'] )
					{
						$ret .= '<div class="categoryEdit"><a href="' . $sUrl . 'articles.php?catID=' . $aCategory['CategoryID'] . '&amp;action=categoryedit">Edit</a>' . "\n||";
						$ret .= '<a href="' . $sUrl . 'articles.php?catID=' . $aCategory['CategoryID'] . '&amp;action=categorydelete" onclick="javascript: return confirm(\'Do you realy want to delete \n category ' . $aCategory['CategoryName'] . ' \n and all its articles \')">Delete</a></div>' . "\n";
					}

			$ret .= '</div>' . "\n";

			$i++;
		}

		return $ret;
	}

}

function getArticlesList( $iCategoryID )
{
	global $sUrl;
	global $site;
	global $logged;
	global $short_date_format;

	$iCategoryID = (int)$iCategoryID;
	if( !(int)$iCategoryID )
	{
		return '';
	}
	else
	{
		$sCategoryQuery = "
				SELECT
						`CategoryName`,
						`CategoryDescription`
				FROM
						`ArticlesCategory`
				WHERE
						`CategoryID` = '$iCategoryID'
				LIMIT 1;
		";
		$aCategory = db_arr( $sCategoryQuery );
	}

	$sArticlesQuery = "
				SELECT
						`Title`,
						`Text`,
						DATE_FORMAT( `Date`, '$short_date_format' ) AS Date,
						`ArticlesID`,
						`ArticleFlag`
				FROM
						`Articles`
				WHERE
						`CategoryID` = '$iCategoryID'
	";
	$rArticles = db_res( $sArticlesQuery );


	$ret = '';
	$ret .= '<div class="navigationLinks">' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= '<a href="' . $sUrl . 'articles.php">' . "\n";
				$ret .= 'Articles' . "\n";
			$ret .= '</a>' . "\n";
		$ret .= '</span>' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= '&gt;' . "\n";
		$ret .= '</span>' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= process_line_output( $aCategory['CategoryName'] ) . "\n";
		$ret .= '</span>' . "\n";
	$ret .= '</div>' . "\n";
	$ret .= '<div class="categoryHeader">' . "\n";
		$ret .= '<div class="artCaption">' . "\n";
			$ret .= process_line_output( $aCategory['CategoryName'] ) . "\n";
		$ret .= '</div>' . "\n";
		$ret .= '<div>' . "\n";
			$ret .= process_text_output( $aCategory['CategoryDescription'] ) . "\n";
		$ret .= '</div>' . "\n";
	$ret .= '</div>' . "\n";

	$i = '';
	while ( $aArticles = mysql_fetch_assoc( $rArticles ))
	{
		if ( ($i%2) == 0 )
		{
			$sStyleAdd = '1';
		}
		else
		{
			$sStyleAdd = '2';
		}

		$ret .= '<div class="articleBlock' . $sStyleAdd . '">' . "\n";
			$ret .= '<div class="title">' . "\n";
				$ret .= '<a href="' . $sUrl . 'articles.php?articleID=' . $aArticles['ArticlesID'] . '&amp;action=viewarticle">' . "\n";
					$ret .= process_line_output( $aArticles['Title'] ) . "\n";
				$ret .= '</a>' . "\n";
			$ret .= '</div>' . "\n";
			$ret .= '<div class="date">' . "\n";
				$ret .= $aArticles['Date'] . "\n";
			$ret .= '</div>' . "\n";
			$ret .= '<div class="preview">' . "\n";
				if( $aArticles['ArticleFlag'] == 'HTML' )
				{
					$ret .= process_html_output( strmaxtextlen( strip_tags($aArticles['Text']), 200 ) ) . "\n";
				}
				else
				{
					$ret .= process_text_output( strmaxtextlen( $aArticles['Text'], 200 ) ) . "\n";
				}
			$ret .= '</div>' . "\n";
			if( $logged['admin'] )
			{
				$ret .= '<div class="categoryEdit">' . "\n";
					$ret .= '<a href="' . $site['url_admin'] . 'articles.php?articleID=' . $aArticles['ArticlesID'] . '&amp;action=editarticle">' . "\n";
						$ret .= 'Edit' . "\n";
					$ret .= '</a>' . "\n||";
					$ret .= '<a href="' . $site['url_admin'] . 'articles.php?articleID=' . $aArticles['ArticlesID'] . '&amp;action=deletearticle" onclick="javascript: return confirm(\' Are you sure ?\')">' . "\n";
						$ret .= 'Delete' . "\n";
					$ret .= '</a>' . "\n";
				$ret .= '</div>' . "\n";
			}
		$ret .= '</div>' . "\n";
		$i++;
	}


	return $ret;
}

function getArticle( $iArticleID )
{
	global $short_date_format;

	if( !(int)$iArticleID )
	{
		return '';
	}
	else
	{
		$sArticleQuery = "
				SELECT
						`Title`,
						`Text`,
						`Articles`.`CategoryID`,
						DATE_FORMAT( `Date`, '$short_date_format' ) AS Date,
						`ArticlesID`,
						`CategoryName`,
						`ArticleFlag`
				FROM
						`Articles`
				INNER JOIN `ArticlesCategory` ON `Articles`.`CategoryID` = `ArticlesCategory`.`CategoryID`
				WHERE
						`ArticlesID` = '$iArticleID'
				LIMIT 1;

		";
	}
	$aArticle = db_arr( $sArticleQuery );

	$ret = '';
	$ret .= '<div class="navigationLinks">' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= '<a href="articles.php">' . "\n";
				$ret .= 'Articles' . "\n";
			$ret .= '</a>' . "\n";
		$ret .= '</span>' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= '&gt;' . "\n";
		$ret .= '</span>' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= '<a href="articles.php?catID=' . $aArticle['CategoryID'] . '&amp;action=viewcategory">' . "\n";
				$ret .= process_line_output( $aArticle['CategoryName'] ) . "\n";
			$ret .= '</a>' . "\n";
		$ret .= '</span>' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= '&gt;' . "\n";
		$ret .= '</span>' . "\n";
		$ret .= '<span>' . "\n";
			$ret .= process_line_output( $aArticle['Title']) . "\n";
		$ret .= '</span>' . "\n";
	$ret .= '</div>' . "\n";
	$ret .= '<div class="articleBlock">' . "\n";
		$ret .= '<div class="mainTitle">' . "\n";
			$ret .= process_line_output( $aArticle['Title'] ) . "\n";
		$ret .= '</div>' . "\n";
		$ret .= '<div class="date">' . "\n";
			$ret .=	$aArticle['Date'] . "\n";
		$ret .= '</div>' . "\n";
		$ret .= '<div>' . "\n";
			if( $aArticle['ArticleFlag'] == 'HTML' )
			{
				$ret .= process_html_output( $aArticle['Text'] ) . "\n";
			}
			else
			{
				$ret .= process_text_output( $aArticle['Text'] ) . "\n";
			}
		$ret .= '</div>' . "\n";
	$ret .= '</div>' . "\n";

	return $ret;
}


?>
