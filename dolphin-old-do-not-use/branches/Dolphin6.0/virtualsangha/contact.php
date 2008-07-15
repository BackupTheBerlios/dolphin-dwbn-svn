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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login

$_page['name_index'] 	= 16;
$_page['css_name']		= 'contact.css';

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

$_page['header'] = _t( "_CONTACT_H" );
$_page['header_text'] = _t( "_CONTACT_H1" );

// --------------- page components

$showForm = getParam('enable_contact_form') == 'on' ? true : false ;

$_ni = $_page['name_index'];

if( $showForm )
{
	$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCodeWithForm();
}
else
{
	$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();
}


// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $oTemplConfig;
	return DesignBoxContent( _t( "_CONTACT_H1" ), _t( "_CONTACT" ), $oTemplConfig -> PageCompThird_db_num);
}

function PageCompPageMainCodeWithForm()
{
	global $oTemplConfig;
	global $site;

	$sActionText = '';
	if( isset($_POST['do_submit']) )
	{
		if (!isset($_POST['securityImageValue']) || !isset($_COOKIE['strSec']) || md5($_POST['securityImageValue']) != $_COOKIE['strSec'])
		{
			$sActionText = _t_err( '_SIMG_ERR' );
		}
		else
		{
			$sSenderName	= process_db_input( $_POST['name'] );
			$sSenderEmail	= process_db_input( $_POST['email'] );
			$sLetterSubject = process_db_input( $_POST['subject'] );
			$sLetterBody	= process_db_input( $_POST['body'] );

			$sLetterBody = $sLetterBody . "\r\n" . '============' . "\r\n" . _t('_from') . ' ' . $sSenderName . "\r\n" . 'with email ' .  $sSenderEmail;

			if( sendMail( $site['email'], $sLetterSubject, $sLetterBody ) )
			{
				$sActionText = _t_action('_ADM_PROFILE_SEND_MSG');
			}
			else
			{
				$sActionText = _t_err( '_Email sent failed' );
			}
		}
	}
	
	ob_start();
	
    echo $sActionText;
	?>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
		<table class="messageBlock">
			<tr>
				<td class="contact_label"><?= _t('_Your name') ?>:</td>
				<td class="contact_value"><input type="text" name="name" value="" class="inputText" /></td>
			</tr>
			<tr>
				<td class="contact_label"><?= _t('_Your email') ?>:</td>
				<td class="contact_value"><input type="text" name="email" value="" class="inputText"  onkeyup="if( emailCheck( this.value ) ) this.form.do_submit.disabled=false; else this.form.do_submit.disabled=true;" /></td>
			</tr>
			<tr>
				<td class="contact_label"><?= _t('_message_subject') ?>:</td>
				<td class="contact_value"><input type="text" name="subject" value="" class="inputText" /></td>
			</tr>
			<tr>
				<td class="contact_label"><?= _t('_Message text') ?>:</td>
				<td class="contact_value"><textarea name="body" class="inputTextarea"></textarea></td>
			</tr>
			<tr>
				<td class="contact_label"><?= _t( "_Enter what you see:" ) ?></div>
				<td class="contact_value">
					<img alt="Security Image" src="<?= $site['url'] ?>simg/simg.php" /><br />
					<input name="securityImageValue" type="text" size="15" />
				</td>
			</tr>
			<tr>
				<td colspan="2" class="contact_submit"><input type="submit" name="do_submit" value="<?= _t('_Submit') ?>" disabled="disabled"/></td>
			</tr>
		</table>
	</form>
	<?
	$ret = ob_get_clean();

    return DesignBoxContent( _t( "_CONTACT_H1" ), $ret, $oTemplConfig -> PageCompThird_db_num);
}

?>
