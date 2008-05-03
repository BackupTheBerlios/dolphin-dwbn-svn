<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolCmts.php' );

class BxBaseCmtsView extends BxDolCmts
{
	function BxBaseCmtsView( $sSystem, $iId, $iInit = 1 )
	{
        BxDolCmts::BxDolCmts( $sSystem, $iId, $iInit );
        $this->_sJsObjName = 'oCmts' . ucfirst($sSystem);
	}

	/**
	 * get full comments block with initializations
	 */
    function getCommentsFirst ()
    {        
        $sRet  = '<div id="cmts-box-'.$this->getId().'">';
        
        $iOverflow = $iCountTotal = 0;        
        $sRet .= $this->getComments (0, $iOverflow, $iCountTotal);
        
        if (1 == $iOverflow)
        	$sRet .= '<div class="cmt-show-more"><a href="#" onclick="' . $this->_sJsObjName . '.showMore(this, ' . $this->getPerView () . '); return false;">'._t('_Show <b>N</b>-<u>N</u> of N discussions', $this->getPerView ()+1, $this->getPerView ()*2 > $iCountTotal ? $iCountTotal : $this->getPerView ()*2, $iCountTotal).'</a></div>';
        	
        if ($this->isPostReplyAllowed ())
        	$sRet .= $this->_getPostReplyBox();
        	
        $sRet .= '</div>';
        
        $sRet .= $this->getCmtsInit ();
        
        return $sRet;
    }

    /**
     * get comments list for specified parent comment
     *
     * @param int $iCmtsParentId - parent comment to get child comments from
     */
	function getComments ($iCmtsParentId = 0, &$iOverflow, &$iCountTotal)
	{
        global $site;        

        $iPerView = $this->getPerView ();
         
        $sRet = '<ul class="cmts">';

        $aCmts = $this->getCommentsArray ($iCmtsParentId);
        if (!$aCmts)
        {        	
			$sRet .= '<li class="cmt-no">' . _t('_There are no comments yet') . '</li>';        	
        }
        else 
        {
        	$i = 0;
	        for ( reset($aCmts) ; list ($k, $r) = each ($aCmts) ;  ++$i)
    	    {
        		$sClass = "";
	        	$isOwnComment = $r['cmt_author_id'] == $this->_getAuthorId();
    	    	if ($isOwnComment)
        			$sClass = ' cmt-mine';
        		
	            $sRet .= '<li id="cmt'.$r['cmt_id'].'" class="cmt' . $sClass . '" ' . ( 0 == $iCmtsParentId && $i >= $iPerView ? 'style="display:none"' : '') . '>';

	            $sRet .= $this->_getCommentHeadBox ($r);
	            
	            $sStyle = '';
        		if (-1 == $r['cmt_rated'] || $r['cmt_rate'] < $this->_aSystem['viewing_threshold'])
        			$sStyle = ' style="display:none" ';
        	
        		$sRet .= '<div class="cmt-cont" ' . $sStyle . '>';
        			    	        
    	        $sRet .= $this->_getCommentBodyBox ($r);

        	    if ($r['cmt_replies'])
            	    $sRet .= $this->_getRepliesBox($r);
            	else if ($this->isPostReplyAllowed ())
            		$sRet .= $this->_getPostReplyBoxTo($r);

            	$sRet .= '</div>';
            		
            	$sRet .= '</li>';
        	}
        }

        if ($aCmts && 0 != $iCmtsParentId && $this->isPostReplyAllowed ())
        {
            $aCmtParent = $this->getCOmmentRow($iCmtsParentId);
            $sRet .= '<li class="cmt-reply-to">';
        	$sRet .= '<a href="#" onclick="' . $this->_sJsObjName . '.toggleReply(this, \'' . $iCmtsParentId . '\'); return false;">' . _t('_Reply to Someone comment', $aCmtParent['cmt_author_name']) . '</a>';
            $sRet .= '</li>';
        }

        $sRet .= '</ul>';

        if (null !== $iOverflow && $i > $iPerView)
        	$iOverflow = 1;
        
        if (null !== $iCountTotal)
        	$iCountTotal = count($aCmts);
        
		return $sRet;
	}

	/**
	 * get one just posted comment 
	 *
	 * @param int $iCmtId - comment id
	 * @return string
	 */
	function getComment ($iCmtId)
	{
        $r = $this->getCommentRow ($iCmtId);
        
        $sRet = '<li id="cmt'.$r['cmt_id'].'" class="cmt cmt-mine cmt-just-posted">';
        
        $sRet .= $this->_getCommentHeadBox ($r, true);
        
	    $sStyle = '';
        if (-1 == $r['cmt_rated'] || $r['cmt_rate'] < $this->_aSystem['viewing_threshold'])
        	$sStyle = ' style="display:none" ';        
        $sRet .= '<div class="cmt-cont" ' . $sStyle . '>';
        
        $sRet .= $this->_getCommentBodyBox ($r);

        if ($r['cmt_replies'])
        	$sRet .= $this->_getRepliesBox($r);

        $sRet .= '</div>';
        
        $sRet .= '</li>';

		return $sRet;
	}
		
	/**
	 * Get comments css file string
	 *
	 * @return string
	 */
	function getExtraCss ()
	{
		global $site;
		global $tmpl;
		return '<link href="'.$site['url'].'templates/tmpl_'.$tmpl.'/css/cmts.css" rel="stylesheet" type="text/css" />';
    }

	/**
	 * Get comments js file string
	 *
	 * @return string
	 */
	function getExtraJs ()
	{
		global $site;
		return '<script src="'.$site['url'].'inc/js/classes/BxDolCmts.js" type="text/javascript" language="javascript"></script>';
    }
        
    /**
     * Get initialization section of comments box 
     *
     * @return string
     */
    function getCmtsInit ()
    {        
        global $site;

        $ret = "
            <script>            
                var " . $this->_sJsObjName . " = new BxDolCmts({
                	sObjName : '".$this->_sJsObjName."',
                	sBaseUrl : '" . $site['url'] . "',
                	sSystem : '" . $this->getSystemName() . "', 
                	iObjId : '" . $this->getId () . "', 
                	sDefaultErrMsg : '"._t('_Error occured')."', 
                	sConfirmMsg : '"._t('_Are you sure?')."', 
                	sAnimationEffect : '" . $this->_aSystem['animation_effect'] . "',
                	sAnimationSpeed : '" . $this->_aSystem['animation_speed'] . "',
                	isEditAllowed : ".( $this->isEditAllowed() || $this->isEditAllowedAll() ? 1 : 0).", 
                	isRemoveAllowed : ".( $this->isRemoveAllowed() || $this->isRemoveAllowedAll() ? 1 : 0).", 
                	iSecsToEdit : ".(int)$this->getAllowedEditTime()."});
                " . $this->_sJsObjName . ".oCmtElements = {";
                
		for (reset($this->_aCmtElements); list($k,$r) = each ($this->_aCmtElements) ; )
		{
			$ret .= "\n'$k' : { 'reg' : '{$r['reg']}', 'msg' : '{$r['msg']}' },";
		}
        $ret = substr($ret, 0, -1);
		$ret .= "\n};
            </script>";
            
		return $this->getExtraJs() . $ret;
    }

	/** private functions
	*********************************************/
     
    function _getCommentHeadBox (&$a, $isJustPosted = false)
    {
    	if ($a['cmt_author_id'] && $a['cmt_author_name'])
    		$sAuthor = '<a href="' . getProfileLink($a['cmt_author_id']) . '">' . $a['cmt_author_name'] . '</a>';
    	else
    		$sAuthor = _t('_Anonymous');
    	
        $sRet = '<div class="cmt-head">' . $this->_getAuthorIcon ($a) . _t('_By') . ' ' . $sAuthor . ' ' . $a['cmt_ago'];
        if ($this->isRatable())
        	$sRet .= $this->_getRateBox($a);
        $sRet .= '</div>';
        
        if ($isJustPosted || $a['cmt_author_id'] == $this->_getAuthorId() || $this->isEditAllowedAll() || $this->isRemoveAllowedAll())
        	$sRet .= $this->_getActionsBox ($a, $isJustPosted);
        
        return $sRet;
    }

    function _getCommentBodyBox (&$a)
    {        	
        return '<div class="cmt-body">' . $a['cmt_text'] . '</div>';
    }
        
    function _getRateBox(&$a)
    {    	
    	$sClass = '';
    	if ($a['cmt_rated'] || $a['cmt_rate'] < $this->_aSystem['viewing_threshold']) 
    		$sClass = ' cmt-rate-disabled';
    		
    	$sHidden = '';
    	if (-1 == $a['cmt_rated'] || $a['cmt_rate'] < $this->_aSystem['viewing_threshold'])
    		$sHidden = '<u>' . _t ('_buried') . ' (<a href="#" id="cmt-hid-'.$a['cmt_id'].'" class="cmt-hid">' . _t('_toggle') . '</a>)</u>';
    	
		return '<div class="cmt-rate'.$sClass.'"> ' . $sHidden . '
			'._t( (1 == $a['cmt_rate'] || -1 == $a['cmt_rate'])  ? '_N point' : '_N points', $a['cmt_rate']).'
			<a title="'._t('_Thumb Up').'" href="#" id="cmt-pos-'.$a['cmt_id'].'" class="cmt-pos">&#160;</a>
			<a title="'._t('_Thumb Down').'" href="#" id="cmt-neg-'.$a['cmt_id'].'" class="cmt-neg">&#160;</a>
			</div>';
    }
    
    function _getActionsBox (&$a, $isJustPosted)
    {    	
    	$n = $this->getAllowedEditTime();
    	$isEditAllowedPermanently = $this->isEditAllowed() || $this->isEditAllowedAll();
    	$isRemoveAllowedPermanently = $this->isRemoveAllowed() || $this->isRemoveAllowedAll();
    	if (!($n && $isJustPosted) && !$isEditAllowedPermanently) return '';
    	
    	$sRet  = '<div id="cmt-jp-'.$a['cmt_id'].'" class="cmt-jp">';
    	
    	if ($isEditAllowedPermanently || ($isJustPosted && $n))
    		$sRet .= '<a title="'._t('_Edit').'" href="#" onclick="' . $this->_sJsObjName . '.cmtEdit(this, \'' . $a['cmt_id'] . '\'); return false;">'._t('_Edit').'</a>';
    	
    	if ($isRemoveAllowedPermanently || ($isJustPosted && $n))
    		$sRet .= '<a title="'._t('_Remove').'" href="#" onclick="' . $this->_sJsObjName . '.cmtRemove(this, \'' . $a['cmt_id'] . '\'); return false;">'._t('_Remove').'</a>';
    	
    	if ($isJustPosted && $n && !$isEditAllowedPermanently) $sRet .= _t('_(available for <span>N</span> seconds)', $n);
    	
    	$sRet .= '</div>';
    	
    	return $sRet;
    }
    
    function _getRepliesBox (&$a)
    {
        $sRet  = '<div class="cmt-replies">';
        $sRet .= '<a class="cmt-replies-show" href="#" onclick="' . $this->_sJsObjName . '.toggleCmts(this, \'' . $a['cmt_id'] . '\'); return false;">' . _t('_Show N replies', $a['cmt_replies']) . '</a>';
        $sRet .= '<a class="cmt-replies-hide" href="#" onclick="' . $this->_sJsObjName . '.toggleCmts(this, \'' . $a['cmt_id'] . '\'); return false;">' . _t('_Hide N replies', $a['cmt_replies']) . '</a>';
        return ($sRet .= '</div>');
    }

    function _getPostReplyBoxTo (&$a)
    {
        return '<div class="cmt-post-reply-to">
        			<a href="#" onclick="' . $this->_sJsObjName . '.toggleReply(this, \''.$a['cmt_id'].'\'); return false;">' . _t('_Reply to this comment') . '</a>
        		</div>';
    }
        
    function _getPostReplyBox ()
    {
		return '
        		<div class="cmt-post-reply">
        			<div class="cmt-reply-head">
        				<a href="#" onclick="' . $this->_sJsObjName . '.toggleReply(this, \'0\'); return false;">' . _t('_Add Your Comment') . '</a>
        			</div>
					' . $this->_getFormBox() . '
        		</div>';
    }

    function _getFormBox ($sText = '', $iCmtParentId = 0, $sFunc = 'submitComment(this)')
    {
    	return '<form name="cmt-post-reply" onsubmit="' . $this->_sJsObjName . '.' . $sFunc . '; return false;">
        			<textarea name="CmtText">'.$sText.'</textarea>
        			<input type="hidden" name="CmtParent" value="'.$iCmtParentId.'" />
        			<br />
        			<input type="submit" value="'._t('_Submit Comment').'" />
        		</form>';
    	
    }
    
	function _getAuthorIcon ($a)
	{		
        global $site, $tmpl;
		if ($a['cmt_author_icon'])
			return '<img class="cmt-icon" alt="'.$a['cmt_author_name'].'" src="' . $site['profileImage'] . $a['cmt_author_id'] . '/' . 'icon_' . $a['cmt_author_icon'] . '" />';
		else
			return '<img class="cmt-icon" alt="'.$a['cmt_author_name'].'" src="' . $site['url'] . "templates/tmpl_{$tmpl}/images/icons/cmt-male.gif\" />";
	}
}
?>
