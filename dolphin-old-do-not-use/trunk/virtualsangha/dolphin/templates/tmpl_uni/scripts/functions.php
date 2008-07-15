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
/*
$_page_cont[0]['dol_orca_ray'] = getParam( 'enable_boonex_footers' ) ? '
<div class="bottomImages">
	<img src="__images__small_dol.png" alt="" title="" class="dolphinLogo" />
	<img src="__images__small_orca.png" alt="" title="" class="dolphinLogo" />
	<img src="__images__small_ray.png" alt="" title="" class="dolphinLogo" />
</div>' : '';
*/
/**
 * Return code for the login section for frt
 **/
function LoginSection($logged)
{
        global $site;
        global $memberID;
        global $logged;

        $ret = '';

        if( $logged['member'] )
        {
                $ret .= '<div class="logged_member_block">';
                        $ret .= get_member_icon( $memberID, 'none' );
                        $ret .= '<div class="hello_member">';
                                $ret .= _t( '_Hello member', getNickName( $memberID ) );
                                $ret .= "<br>";
                                $ret .= '<a href="' . $site['url'] . 'member.php" class="logout">' . _t("_Control Panel") . '</a>';
                                $ret .= ' &nbsp; ';
                                $ret .= '<a href="' . $site['url'] . 'logout.php?action=member_logout" class="logout">' . _t("_Log Out") . '</a>';
                        $ret .= '</div>';
                $ret .= '</div>';
        }
        elseif( $logged['admin'])
        {
                $ret .= '<div class="logged_section_block">';
                        $ret .= '<span>';
                                $ret .= '<a href="' . $site['url_admin'] . 'index.php" class="logout">Admin Panel</a>';
                        $ret .= '</span>';
                        $ret .= '<span>';
                                $ret .= '|&nbsp;|';
                        $ret .= '</span>';
                        $ret .= '<span>';
                                $ret .= '<a href="' . $site['url'] . 'logout.php?action=admin_logout" class="logout">' . _t("_Log Out") . '</a>';
                        $ret .= '</span>';
                $ret .= '</div>';
        }
        elseif($logged['aff'])
        {
                $ret .= '<div class="logged_section_block">';
                        $ret .= '<span>';
                                $ret .= '<a href="' . $site['url'] . 'aff/index.php" class="logout">Affiliate Panel</a>';
                        $ret .= '</span>';
                        $ret .= '<span>';
                                $ret .= '|&nbsp;|';
                        $ret .= '</span>';
                        $ret .= '<span>';
                                $ret .= '<a href="' . $site['url'] . 'logout.php?action=aff_logout" class="logout">' . _t("_Log Out") . '</a>';
                        $ret .= '</span>';
                $ret .= '</div>';
        }
        elseif($logged['moderator'])
        {
                $ret .= '<div class="logged_section_block">';
                        $ret .= '<span>';
                                $ret .= '<a href="' . $site['url'] . 'moderators/index.php" class="logout">Moderator Panel</a>';
                        $ret .= '</span>';
                        $ret .= '<span>';
                                $ret .= '|&nbsp;|';
                        $ret .= '</span>';
                        $ret .= '<span>';
                                $ret .= '<a href="' . $site['url'] . 'logout.php?action=moderator_logout" class="logout">' . _t("_Log Out") . '</a>';
                        $ret .= '</span>';
                $ret .= '</div>';
        }
        else
        {
                $ret .= '<div class="login_block">';
                        $ret .= '<form method="post" action="' . $site['url'] . 'member.php">';
                                $ret .= '<div class="clear_both"></div>';

                                $ret .= '<div class="login_line">';
                                        $ret .= _t('_Nickname') . ':';
                                        $ret .= '<input  name="ID" value="" type="text"class="login_area" />';
                                $ret .= '</div>';
                                $ret .= '<div class="login_line">';
                                        $ret .= _t('_Password') . ':';
                                        $ret .= '<input name="Password" value="" type="password" class="login_area" />';
                                $ret .= '</div>';
                                $ret .= '<div class="forgotDiv">';
                                        $ret .= '<a href="' . $site['url'] . 'forgot.php">' . _t('_forgot_your_password') . '?</a>';
                                $ret .= '</div>';
                                $ret .= '<input class="login_button" type="image" src="' . $site['images'] . 'button_login_index.gif" />';
                                $ret .= '<div class="join_now">' . _t('_or') . ' <a href="' . $site['url'] . 'join.php">' . _t( '_Join now' ) . '</a></div>';

                                $ret .= '<div class="clear_both"></div>';
                        $ret .= '</form>';
                $ret .= '</div>';
        }

        return DesignBoxContent( _t('_Member Login'), $ret, 1 );
}


function getProfileOnlineStatus( $user_is_online, $bDrawMargin=false, $bCouple = false ) {
        global $site;

		$iMargR = ($bCouple==false) ? '5' : '10';
        $sMarginsAddon = ($bDrawMargin) ? ' style="margin-right:'.$iMargR.'px;margin-bottom:10px;" ' : '';
        if( $user_is_online ) {
                $sRet .= '<img src="' . $site['icons'] . 'online.gif" alt="' . _t("_Online") . '" title="' . _t("_Online") . '" class="online_offline_bulb" '. $sMarginsAddon .' />';
        } else {
                $sRet .= '<img src="' . $site['icons'] . 'offline.gif" alt="'. _t("_Offline") . '" title="'. _t("_Offline") . '" class="online_offline_bulb" '. $sMarginsAddon .' />';
        }
        return $sRet;
}

function getProfileMatch( $memberID, $profileID )
{
        global $oTemplConfig;

        $match_n = getProfilesMatch($memberID, $profileID); // impl
        $ret = '';
        $ret .= DesignProgressPos ( _t("_XX match", $match_n), $oTemplConfig->iProfileViewProgressBar, 100, $match_n );;

        return $ret;
}

function getProfileZodiac( $profileDate )
{
        $ret = '';
                        $ret .= ShowZodiacSign( $profileDate );

        return $ret;
}

function TemplPageAddComponent( $sKey ) {
	switch( $sKey ) {
		case 'something':
			return false; // return here additional components
		
		default:
			return false; // if you have not such component, return false!
	}
}

function HelloMemberSection()
{
        global $logged;
        global $site;

        ob_start();

        if( $logged['member'] )
        {
                $memberID = (int)$_COOKIE['memberID'];
                $iLet = getNewLettersNum($memberID);
                $sNewLet = $iLet > 0 ? '<b>('.$iLet.')</b>' : '' ;
                ?>
                <div class="topMemberBlock">
                        <?= get_member_icon( $memberID, 'right' ) ?>
                        <div class="hello_member"><?= _t( '_Hello member', getNickName( $memberID ) ) ?></div>

                        <div class="hello_actions">
                                <span><a href="<?= $site['url'] ?>member.php"><?= _t('_My account') ?></a></span>
                                <span><a href="<?= $site['url'] ?>mail.php?mode=inbox"><?= _t('_My Mail') ?></a><?=' '.$sNewLet;?></span>
                                <span><a href="<? echo getProfileLink( $memberID ) ?>"><?= _t('_My Profile') ?></a></span>
                                <span><a href="javascript:void(0);"
                                  onclick="window.open( '<?= $site['url'] ?>presence_pop.php' , 'Presence', 'width=224,height=600,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1');"
                                  ><?= _t('_RayPresence') ?></a></span>
                                <span><a href="<?= $site['url'] ?>logout.php?action=member_logout"><?= _t('_Log Out2') ?></a></span>
                        </div>
                </div>
                <?
        }
		elseif( $logged['admin'] )
		{
                ?>
                <div class="topMemberBlock">
						<div class="thumbnail_block" style="float:right;position:relative;">
	                        <img style="width: 45px; height: 45px; background-image: url(<?= getTemplateIcon( 'man_small.gif' ) ?>);"
							  src="<?= getTemplateIcon( 'spacer.gif' ) ?>" alt="" />
						</div>
                        <div class="hello_member"><?= _t( '_Hello member', 'admin' ) ?></div>

                        <div class="hello_actions">
                                <span><a href="<?= $site['url_admin'] ?>index.php"><?= _t('_Admin Panel') ?></a></span>
                                <span><a href="<?= $site['url'] ?>logout.php?action=admin_logout"><?= _t('_Log Out2') ?></a></span>
                        </div>
                </div>
                <?
		}
        else
        {
				//<a href="<_?= $site['url'] ?_>member.php"><_?= _t( '_Member Login' ) ?_></a>
                ?>
                <div class="topMemberBlock">
                        <div class="no_hello_actions">
                                <a href="<?= $site['url'] ?>join.php"><?= _t( '_Join Now Top' ) ?></a>
                                <a href="<?= $site['url'] ?>member.php" onclick="showItemEditForm('login_div'); $( '#login_div' ).show().load( 'member.php?action=show_login_form&relocate=' + encodeURIComponent( window.location )  );return false;"><?= _t( '_Member Login' ) ?></a>
                        </div>
                </div>
                <?
        }

        return ob_get_clean();
}

function MsgBox( $text )
{
        global $site;
        global $tmpl;

        ob_start();
        ?>
                <table class="MsgBox" cellpadding="0" cellspacing="0">
                        <tr>
                                <td class="corder"><img src="<?= "{$site['url']}templates/tmpl_$tmpl/images/msgbox_cor_lt.png" ?>" alt="" /></td>
                                <td class="top_side"><img src="<?= getTemplateIcon( 'spacer.gif' ) ?>" alt="" /></td>
                                <td class="corder"><img src="<?= "{$site['url']}templates/tmpl_$tmpl/images/msgbox_cor_rt.png" ?>" alt="" /></td>
                        </tr>
                        <tr>
                                <td class="left_side"><img src="<?= getTemplateIcon( 'spacer.gif' ) ?>" alt="" /></td>
                                <td class="msgbox_content"><div class="msgbox_text"><?= $text ?></div></td>
                                <td class="right_side"><img src="<?= getTemplateIcon( 'spacer.gif' ) ?>" alt="" /></td>
                        </tr>
                        <tr>
                                <td class="corner"><img src="<?= "{$site['url']}templates/tmpl_$tmpl/images/msgbox_cor_lb.png" ?>" alt="" /></td>
                                <td class="bottom_side"><img src="<?= getTemplateIcon( 'spacer.gif' ) ?>" alt="" /></td>
                                <td class="corner"><img src="<?= "{$site['url']}templates/tmpl_$tmpl/images/msgbox_cor_rb.png" ?>" alt="" /></td>
                        </tr>
                </table>
        <?

        return ob_get_clean();
}

?>
