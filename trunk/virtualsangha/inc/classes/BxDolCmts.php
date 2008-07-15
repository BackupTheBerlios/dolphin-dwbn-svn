<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolCmtsQuery.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMistake.php' );

class BxDolCmts extends BxDolMistake
{
    var $_iId = 0;  // obect id to be commented

    var $_sSystem = 'profile';  // current comment system name	

	var $_aSystem = array ();   // current comments system array

	var $_aSystems = array (    // array of supported comment systems

		'profile' => array (
			'system_id' => 1,							// this system id
			'table_cmts' => 'CmtsProfile',			    // table with comments
			'table_track' => 'CmtsTrack',	    		// table to track duplicate ratings
			'allow_tags' => 0,							// allow tags in comments or not
			'nl2br' => 1,								// convert all new line caracters to <br /> tags
			'sec_to_edit' => 90,						// number of seconds to allow edit comment after submit, 0 - do not allow edit
			'per_view' => 5,							// comments per view, like paginate
			'is_ratable' => 1,							// allow rate comments or not
			'viewing_threshold' => -3,					// below this value comment is hidden by default
			'animation_effect' => 'slide',				// animation effect : slide, fade or default
			'animation_speed' => '2000',				// speed of animation effect in ms (1000 == 1 second)
			'is_on' => 1								// is voting enabled or not
		),
		
		'sharedPhoto' => array (
			'system_id' => 2,							// this system id
			'table_cmts' => 'CmtsSharedPhoto',			// table with comments
			'table_track' => 'CmtsTrack',	    		// table to track duplicate ratings
			'allow_tags' => 0,							// allow tags in comments or not
			'nl2br' => 1,								// convert all new line caracters to <br /> tags
			'sec_to_edit' => 90,						// number of seconds to allow edit comment after submit, 0 - do not allow edit
			'per_view' => 5,							// comments per view, like paginate
			'is_ratable' => 1,							// allow rate comments or not
			'viewing_threshold' => -3,					// below this value comment is hidden by default
			'animation_effect' => 'slide',				// animation effect : slide, fade or default
			'animation_speed' => '2000',				// speed of animation effect in ms (1000 == 1 second)
			'is_on' => 1								// is voting enabled or not
		),
			
		'sharedMusic' => array (
			'system_id' => 3,							// this system id
			'table_cmts' => 'CmtsSharedMusic',			// table with comments
			'table_track' => 'CmtsTrack',	    		// table to track duplicate ratings
			'allow_tags' => 0,							// allow tags in comments or not
			'nl2br' => 1,								// convert all new line caracters to <br /> tags
			'sec_to_edit' => 90,						// number of seconds to allow edit comment after submit, 0 - do not allow edit
			'per_view' => 5,							// comments per view, like paginate
			'is_ratable' => 1,							// allow rate comments or not
			'viewing_threshold' => -3,					// below this value comment is hidden by default
			'animation_effect' => 'slide',				// animation effect : slide, fade or default
			'animation_speed' => '2000',				// speed of animation effect in ms (1000 == 1 second)
			'is_on' => 1								// is voting enabled or not
		),
		
		'sharedVideo' => array (
			'system_id' => 4,							// this system id
			'table_cmts' => 'CmtsSharedVideo',			// table with comments
			'table_track' => 'CmtsTrack',	    		// table to track duplicate ratings
			'allow_tags' => 0,							// allow tags in comments or not
			'nl2br' => 1,								// convert all new line caracters to <br /> tags
			'sec_to_edit' => 90,						// number of seconds to allow edit comment after submit, 0 - do not allow edit
			'per_view' => 5,							// comments per view, like paginate
			'is_ratable' => 1,							// allow rate comments or not
			'viewing_threshold' => -3,					// below this value comment is hidden by default
			'animation_effect' => 'slide',				// animation effect : slide, fade or default
			'animation_speed' => '2000',				// speed of animation effect in ms (1000 == 1 second)
			'is_on' => 1								// is voting enabled or not
		),
		
		'classifieds' => array (
			'system_id' => 5,							// this system id
			'table_cmts' => 'CmtsClassifieds',			// table with comments
			'table_track' => 'CmtsTrack',	    		// table to track duplicate ratings
			'allow_tags' => 0,							// allow tags in comments or not
			'nl2br' => 1,								// convert all new line caracters to <br /> tags
			'sec_to_edit' => 90,						// number of seconds to allow edit comment after submit, 0 - do not allow edit
			'per_view' => 5,							// comments per view, like paginate
			'is_ratable' => 1,							// allow rate comments or not
			'viewing_threshold' => -3,					// below this value comment is hidden by default
			'animation_effect' => 'slide',				// animation effect : slide, fade or default
			'animation_speed' => '2000',				// speed of animation effect in ms (1000 == 1 second)
			'is_on' => 1								// is voting enabled or not
		),
		
		'blogposts' => array (
			'system_id' => 6,							// this system id
			'table_cmts' => 'CmtsBlogPosts',			// table with comments
			'table_track' => 'CmtsTrack',	    		// table to track duplicate ratings
			'allow_tags' => 0,							// allow tags in comments or not
			'nl2br' => 1,								// convert all new line caracters to <br /> tags
			'sec_to_edit' => 90,						// number of seconds to allow edit comment after submit, 0 - do not allow edit
			'per_view' => 5,							// comments per view, like paginate
			'is_ratable' => 1,							// allow rate comments or not
			'viewing_threshold' => -3,					// below this value comment is hidden by default
			'animation_effect' => 'slide',				// animation effect : slide, fade or default
			'animation_speed' => '2000',				// speed of animation effect in ms (1000 == 1 second)
			'is_on' => 1								// is voting enabled or not
		),
	);

	var $_aCmtElements = array (
    	'CmtParent'	=> array ( 'reg' => '/^[0-9]+$/', 'msg' => 'bad comment parent id' ),
		'CmtText' 	=> array ( 'reg' => '/^.{3,2048}$/m', 'msg' => 'Please enter 3-2048 characters' ),
	);
	
	var $_oQuery = null;

	/**
     * Constructor
     * $sSystem - comments system name
     * $iId - obect id to be commented
	 */
	function BxDolCmts( $sSystem, $iId, $iInit = 1)
	{
		$this->_sSystem = $sSystem;
        if (isset($this->_aSystems[$sSystem]))
            $this->_aSystem = $this->_aSystems[$sSystem];
        else
            return;

        $this->_oQuery = new BxDolCmtsQuery($this->_aSystem);

		if ($iInit) 
			$this->init($iId);
	}

	function init ($iId)
	{
		if (!$this->isEnabled()) return;

		if (!$this->iId && $iId)
		{	
			$this->setId($iId);			
		}

	}
        
    /**
     * check if user can post/edit or delete own comments
     */ 
	function checkAction ($iAction)
	{				
		$iId = $this->_getAuthorId();
		$check_res = checkAction( $iId, $iAction );
		return $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
	}

	function getId ()
	{
		return $this->_iId;
	}

	function isEnabled ()
	{
		return $this->_aSystem['is_on'];
	}

	function getSystemName()
	{
		return $this->_sSystem;
	}

	/**
	 * set id to operate with votes
	 */
	function setId ($iId)
	{
		if ($iId == $this->getId()) return;
		$this->_iId = $iId;
	}

    function isValidSystem ($sSystem)
    {
        return isset($this->_aSystems[$sSystem]);
    }
    
    function isTagsAllowed ()
    {
        return $this->_aSystem['allow_tags'];
    }
        
    function isNl2br ()
    {
        return $this->_aSystem['nl2br'];
    }
     
    function isRatable ()
    {
        return $this->_aSystem['is_ratable'];
    }
        
    function getAllowedEditTime ()
    {
        return $this->_aSystem['sec_to_edit'];
    }
       
    function getPerView ()
    {
        return $this->_aSystem['per_view'];
    }
        
    function getSystemId ()
    {
        return $this->_aSystem['system_id'];
    }

    
	/** comments functions
	*********************************************/
	    
    function getCommentsArray ($iCmtParentId)
    {
        return $this->_oQuery->getComments ($this->getId(), $iCmtParentId, $this->_getAuthorId());
    }

    function getCommentRow ($iCmtId)
    {
        return $this->_oQuery->getComment ($this->getId(), $iCmtId, $this->_getAuthorId());
    }

    function onObjectDelete ($iObjectId = 0)
    {
        return $this->_oQuery->deleteObjectComments ($iObjectId ? $iObjectId : $this->getId());
    }

    // delete all profiles comments in all systems, if some replies exist, set this comment to anonymous
    function onAuthorDelete ($iAuthorId)
    {
        for ( reset($this->_aSystems) ; list ($sSystem, $aSystem) = each ($this->_aSystems) ; )
        {
            $oQuery = new BxDolCmtsQuery($aSystem);
            $oQuery->deleteAuthorComments ($iAuthorId);
        }
        return true;
    }

    function getCommentsTableName ()
    {
        return $this->_oQuery->getTableName ();   
    }

    function getObjectCommentsCount ($iObjectId = 0)
    {
        return $this->_oQuery->getObjectCommentsCount ($iObjectId ? $iObjectId : $this->getId());
    }

	/** permissions functions
	*********************************************/

    // is rate comment allowed
    function isRateAllowed () 
    {
        if ($this->_sSystem == 'blogposts') 
            return $this->_checkBlogPermission(ACTION_ID_COMMENTS_VOTE);

    	return $this->checkAction (ACTION_ID_COMMENTS_VOTE);
    }

    // is post comment allowed
    function isPostReplyAllowed () 
    {
        if ($this->_sSystem == 'blogposts') 
            return $this->_checkBlogPermission(ACTION_ID_COMMENTS_POST);

    	return $this->checkAction (ACTION_ID_COMMENTS_POST);
    }

    function _checkBlogPermission ($iAction)
    {        
		$iPostID = $this->getId();
		$sBlogPostFr = db_value("SELECT `PostCommentPermission` FROM `BlogPosts` WHERE `BlogPosts`.`PostID` = '{$iPostID}'");
        if ($sBlogPostFr == 'friends') 
        {
			$iOwnerID = db_value("SELECT `BlogCategories`.`OwnerID` FROM `BlogPosts` INNER JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID` WHERE `BlogPosts`.`PostID` = '{$iPostID}'");
			return is_friends( $this->_getAuthorId(), $iOwnerID );
        }
        return $this->checkAction ($iAction);
    }

    // is edit own comment allowed
    function isEditAllowed ()
    {
        if(getParam("free_mode") == "on") return false;
    	return $this->checkAction (ACTION_ID_COMMENTS_EDIT_OWN);
    }

    // is removing own comment allowed
    function isRemoveAllowed ()
    {
        if(getParam("free_mode") == "on") return false;
    	return $this->checkAction (ACTION_ID_COMMENTS_REMOVE_OWN);
    }

    // is edit any comment allowed
    function isEditAllowedAll ()
    {
        global $logged;
    	return $logged['admin'] ? true : false;
    }

    // is removing any comment allowed
    function isRemoveAllowedAll ()
    {
        global $logged;
    	return $logged['admin'] ? true : false;
    }   
        
	/** actions functions
	*********************************************/

    function actionCmtsGet ()
    {
    	if (!$this->isEnabled()) return '';
        $iCmtParentId = (int)$_GET['CmtParent'];
        return $this->getComments ($iCmtParentId);
    }
    
    function actionCmtGet ()
    {
    	if (!$this->isEnabled()) return '';
        $iCmtId = (int)$_GET['Cmt'];
        return $this->getComment ($iCmtId);
    }
        
    function actionCmtPost ()
    {    	
    	if (!$this->isEnabled()) return '';
    	
    	if (!$this->isPostReplyAllowed ()) return '';
    	
    	$iCmtParentId = (int)$_REQUEST['CmtParent'];
    	$sText = process_db_input($_REQUEST['CmtText'], !$this->isTagsAllowed());

    	if ($this->isNl2br())
    		$sText = nl2br($sText);
    	
    	$iCmtNewId = $this->_oQuery->addComment ($this->getId(), $iCmtParentId, $this->_getAuthorId(), $sText);
    	if(false === $iCmtNewId)
    		return '';
    	
    	return $iCmtNewId;
    }

    // returns error string on error, or empty string on success
    function actionCmtRemove ()
    {
    	if (!$this->isEnabled()) return '';
    	
    	$iCmtId = (int)$_REQUEST['Cmt'];
    	
    	$aCmt = $this->_oQuery->getCommentSimple ($this->getId(), $iCmtId);
    	if (!$aCmt)
    		return _t('_No such comment');    	
        	
    	if ($aCmt['cmt_replies'] > 0)
    		return _t('_Can not delete comments with replies');
    		    	
    	$isRemoveAllowed = $this->isRemoveAllowedAll() || ($aCmt['cmt_author_id'] == $this->_getAuthorId() && $this->isRemoveAllowed());
    	if (!$isRemoveAllowed && $aCmt['cmt_secs_ago'] > ($this->getAllowedEditTime()+20))
    		return _t('_Access denied');
        	
    	if (!$this->_oQuery->removeComment ($this->getId(), $aCmt['cmt_id'], $aCmt['cmt_parent_id']))
    		return _t('_Database Error');
    		
    	return '';
    }
    
    // returns string with "err" prefix on error, or string with html form on success
    function actionCmtEdit ()
	{
    	if (!$this->isEnabled()) return '';
    	
    	$iCmtId = (int)$_REQUEST['Cmt'];
    	    	
    	$aCmt = $this->_oQuery->getCommentSimple ($this->getId(), $iCmtId);
    	if (!$aCmt)
    		return 'err'._t('_No such comment');
    		    	
    	$isEditAllowed = $this->isEditAllowedAll() || ($aCmt['cmt_author_id'] == $this->_getAuthorId() && $this->isEditAllowed());
    	if (!$isEditAllowed && $aCmt['cmt_secs_ago'] > ($this->getAllowedEditTime()+20))
    		return 'err'._t('_Access denied');
    		
    	return $this->_getFormBox ($this->_prepareTextForEdit($aCmt['cmt_text']), 0, 'updateComment(this, \''.$iCmtId.'\')');
	}
	
    
	function actionCmtEditSubmit ()
	{
    	if (!$this->isEnabled()) return '';
    	
    	$iCmtId = (int)$_REQUEST['Cmt'];
    	$sText = process_db_input($_REQUEST['CmtText'], !$this->isTagsAllowed());
    	if ($this->isNl2br())
    		$sText = nl2br($sText);		
    	$sTextRet = stripslashes($sText);
    		    	
    	$aCmt = $this->_oQuery->getCommentSimple ($this->getId(), $iCmtId);
    	if (!$aCmt)
    		return '';
    		    	
    	$isEditAllowed = $this->isEditAllowedAll() || ($aCmt['cmt_author_id'] == $this->_getAuthorId() && $this->isEditAllowed());
    	if (!$isEditAllowed && $aCmt['cmt_secs_ago'] > ($this->getAllowedEditTime()+20))
    		return '';    	
    		
    	if ($sTextRet == $aCmt['cmt_text']) 
    		return $sTextRet;
    		
    	if (!$this->_oQuery->updateComment ($this->getId(), $aCmt['cmt_id'], $sText))
    		return '';
    		
    	return $sTextRet;
	}

	function actionCmtRate ()
	{
		if (!$this->isEnabled()) return _t('_Error occured');
		if (!$this->isRatable()) return _t('_Error occured');
		if (!$this->isRateAllowed()) return _t('_Access denied');
		
		$iCmtId = (int)$_REQUEST['Cmt'];		
		$iRate = (int)$_REQUEST['Rate'];
		
		if ($iRate >= 1) 
			$iRate = 1;
		elseif ($iRate <= -1) 
			$iRate = -1;
		else
			return _t('_Error occured');
				
		if (!$this->_oQuery->rateComment ($this->getSystemId(), $iCmtId, $iRate, $this->_getAuthorId(), $this->_getAuthorIp()))
			return _t('_Duplicate vote');
		
		return '';
	}
	
	/** private functions
	*********************************************/
	
	function _getAuthorId ()
    {
        global $logged;
        if (!$logged['member']) return 0;
		return $_COOKIE['memberID'];
	}
	
	function _getAuthorIp ()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
		
	function _prepareTextForEdit ($s)
	{
		if ($this->isNl2br())
			return str_replace('<br />', "", $s);
		return $s;		
	}	
}

?>
