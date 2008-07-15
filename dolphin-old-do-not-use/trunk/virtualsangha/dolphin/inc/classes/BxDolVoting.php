<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolVotingQuery.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMistake.php' );

define( 'BX_PERIOD_PER_VOTE', 7 * 86400 );

class BxDolVoting extends BxDolMistake
{
	var $_iId = 0;	// item id to be rated
	var $_iCount = 0; // number of votes
	var $_fRate = 0; // average rate
    var $_sSystem = 'profile'; // current rating system name	

	var $_aSystem = array (); // current rating system array

	var $_aSystems = array (		// array of supported rate systems

		'profile' => array (
			'table_rating' => 'profile_rating',			// table for ratings
			'table_track' => 'profile_voting_track',	// table to track duplicate ratings
			'row_prefix' => 'pr_',						// table rows prefix
			'max_votes' => 5,							// max vote
			'post_name' => 'vote_send_result',						// post name where vote is stored
			'is_duplicate' => BX_PERIOD_PER_VOTE,					// time in seconds to prevent duplicate votes, default - 1 day
			'is_on' => 1,								// is voting enabled or not
		),

		'media' => array (
			'table_rating' => 'media_rating',
			'table_track' => 'media_voting_track',
			'row_prefix' => 'med_',
			'max_votes' => 5,
			'post_name' => 'vote_send_result',
			'is_duplicate' => BX_PERIOD_PER_VOTE, // 1 day
			'is_on' => 1,
        ),

		'gphoto' => array (
			'table_rating' => 'gphoto_rating',
			'table_track' => 'gphoto_voting_track',
			'row_prefix' => 'gal_',
			'max_votes' => 5,
			'post_name' => 'vote_send_result',
			'is_duplicate' => BX_PERIOD_PER_VOTE, // 1 day
			'is_on' => 1,
		),        

		'gmusic' => array (
			'table_rating' => 'gmusic_rating',
			'table_track' => 'gmusic_voting_track',
			'row_prefix' => 'gal_',
			'max_votes' => 5,
			'post_name' => 'vote_send_result',
			'is_duplicate' => BX_PERIOD_PER_VOTE, // 1 day
			'is_on' => 1,
        ),

		'gvideo' => array (
			'table_rating' => 'gvideo_rating',
			'table_track' => 'gvideo_voting_track',
			'row_prefix' => 'gal_',
			'max_votes' => 5,
			'post_name' => 'vote_send_result',
			'is_duplicate' => BX_PERIOD_PER_VOTE, // 1 day
			'is_on' => 1,
		),        
	);

	var $_oQuery = null;

	/**
	 * Constructor
	 */
	function BxDolVoting( $sSystem, $iId, $iInit = 1)
	{
		$this->_sSystem = $sSystem;
        if (isset($this->_aSystems[$sSystem]))
            $this->_aSystem = $this->_aSystems[$sSystem];
        else
            return;


        $this->_oQuery = new BxDolVotingQuery($this->_aSystem);

		if ($iInit) 
			$this->init($iId);

		if (!$this->isEnabled()) return;

		$iVoteResult = $this->_getVoteResult ();
		if ($iVoteResult)
		{
			if (!$this->makeVote ($iVoteResult))
			{
				exit;
			}
			$this->initVotes ();			
			echo $this->getVoteRate() . ',' . $this->getVoteCount(); 
			exit;
		}
	}

	function init ($iId)
	{
		if (!$iId) 
			$iId = $this->_iId;

		if (!$this->isEnabled()) return;

		if (!$this->iId && $iId)
		{	
			$this->setId($iId);			
		}

	}

	function initVotes ()
	{
		if (!$this->isEnabled()) return;
		if (!$this->_oQuery) return;

		$a = $this->_oQuery->getVote ($this->getId());
		if (!$a) return;
		$this->_iCount = $a['count'];
		$this->_fRate = $a['rate'];
	}
	
	function makeVote ($iVote)
	{	
		if (!$this->isEnabled()) return false;
		if ($this->isDublicateVote()) return false;
		if (!$this->checkAction()) return false;
		
		return $this->_oQuery->putVote ($this->getId(), $_SERVER['REMOTE_ADDR'], $iVote);
	}

	function checkAction ()
	{				
		$iId = $_COOKIE['memberID'];
		$check_res = checkAction( $iId, ACTION_ID_VOTE );
		return $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
	}
	
	function isDublicateVote ()
	{
		if (!$this->isEnabled()) return false;
		return $this->_oQuery->isDublicateVote ($this->getId(), $_SERVER['REMOTE_ADDR']);
	}

	function getId ()
	{
		return $this->_iId;
	}

	function isEnabled ()
	{
		return $this->_aSystem['is_on'];
	}

	function getMaxVote()
	{
		return $this->_aSystem['max_votes'];
	}

	function getVoteCount()
	{
		return $this->_iCount;
	}

	function getVoteRate()
	{
		return $this->_fRate;
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
		$this->initVotes();
	}

    function getSqlParts ($sMailTable, $sMailField)
    {
        if ($this->isEnabled())
            return $this->_oQuery->getSqlParts ($sMailTable, $sMailField);
        else
            return array();
    }


    function isValidSystem ($sSystem)
    {
        return isset($this->_aSystems[$sSystem]);
    }

    function deleteVotings ($iId)
    {        
        if (!(int)$iId) return false;
        $this->_oQuery->deleteVotings ($iId);
        return true;
    }

    function getTopVotedItem ($iDays, $sJoinTable = '', $sJoinField = '', $sWhere = '')
    {
        return $this->_oQuery->getTopVotedItem ($iDays, $sJoinTable, $sJoinField, $sWhere);
    }

	function getVotedItems ($sIp)
	{
		return $this->_oQuery->getVotedItems ($sIp);
	}

	/** private functions
	*********************************************/


	function _getVoteResult ()
	{
        $iVote = (int)$_GET[$this->_aSystem['post_name']];
		if (!$iVote) return 0;

        if ($iVote > $this->getMaxVote()) $iVote = $this->getMaxVote();
        if ($iVote < 1) $iVote = 1;
		return $iVote;
	}
	
}
?>
