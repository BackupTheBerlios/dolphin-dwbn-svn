<?php

define( 'BX_DOL_TABLE_MEDIA', '`media`'  );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolDb.php' );

class BxDolMediaQuery extends BxDolDb
{
	var $oDb;

	function BxDolMediaQuery()
	{
		parent::BxDolDb();
	}


	function getMediaArray( $iProfileID, $sMediaType, &$oDolVoting )
    {
        $aSqlVoting = $oDolVoting -> getSqlParts(BX_DOL_TABLE_MEDIA, '`med_id`');
		$sQuery = "
			SELECT
				" . BX_DOL_TABLE_MEDIA . ".`med_id`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_prof_id`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_type`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_file`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_title`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_status`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_date`,
		 		" . BX_DOL_TABLE_MEDIA . ".`rate_able`,
                " . BX_DOL_TABLE_PROFILES . ".`PrimPhoto`
                {$aSqlVoting['fields']}
			FROM
				" . BX_DOL_TABLE_MEDIA . "
			INNER JOIN " . BX_DOL_TABLE_PROFILES . " ON " . BX_DOL_TABLE_MEDIA . ".`med_prof_id` = " . BX_DOL_TABLE_PROFILES . ".`ID`
			{$aSqlVoting['join']}
			WHERE
				`med_prof_id` = '$iProfileID'
			AND	`med_type` = '$sMediaType'
			ORDER BY `med_date` ASC
			";

		return $this -> getAll( $sQuery );
	}

	function getActiveMediaArray( $iProfileID, $sMediaType, &$oDolVoting  )
    {
        $aSqlVoting = $oDolVoting -> getSqlParts(BX_DOL_TABLE_MEDIA, '`med_id`');
		$sQuery = "
			SELECT
				" . BX_DOL_TABLE_MEDIA . ".`med_id`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_prof_id`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_type`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_file`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_title`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_status`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_date`,
                " . BX_DOL_TABLE_PROFILES . ".`PrimPhoto`
                {$aSqlVoting['fields']}
			FROM
				" . BX_DOL_TABLE_MEDIA . "
			INNER JOIN " . BX_DOL_TABLE_PROFILES . " ON " . BX_DOL_TABLE_MEDIA . ".`med_prof_id` = " . BX_DOL_TABLE_PROFILES . ".`ID`
			{$aSqlVoting['join']}
			WHERE
				`med_prof_id` = '$iProfileID'
			AND " . BX_DOL_TABLE_MEDIA . ".`med_status` = 'active'
			AND	`med_type` = '$sMediaType'
			ORDER BY `med_date` ASC
		";

		return $this -> getAll( $sQuery );
	}

	function setPrimaryPhoto( $iProfileID, $iPhotoID )
	{
		$iProfileID = (int)$iProfileID;
		$iPhotoID = (int)$iPhotoID;

		$sQuery = "
			UPDATE " . BX_DOL_TABLE_PROFILES . " SET `PrimPhoto` = '$iPhotoID', `Picture` = '1' WHERE `ID` = '$iProfileID' LIMIT 1
		";

		$this -> query($sQuery);
	}

	function setRablePhoto( $iPhotoID ) {
		$iPhotoID = (int)$iPhotoID;
		$sQuery = "
			UPDATE " . BX_DOL_TABLE_MEDIA . " SET `rate_able` = IF(`rate_able`='1','0','1') WHERE `med_id` = '{$iPhotoID}' LIMIT 1
		";
		$this -> query($sQuery);
	}

	function resetPrimPhoto( $iProfileId )
	{
		$sQuery = "
			UPDATE " . BX_DOL_TABLE_PROFILES . " SET `PrimPhoto` = '0', `Picture` = '0' WHERE `ID` = '$iProfileId' LIMIT 1
		";
		$this -> query( $sQuery );
	}

	function insertMedia( $iProfileID, $sMediaType, $sFileName, $sFileTitle, $sFileStatus = 'passive' )
	{
		$sFileTitle = addslashes( $sFileTitle );
		$sQuery = "
			INSERT INTO " . BX_DOL_TABLE_MEDIA . "
			SET
				`med_prof_id` = '$iProfileID',
				`med_type` = '$sMediaType',
				`med_file` = '$sFileName',
				`med_title` = '$sFileTitle',
				`med_status` = '$sFileStatus',
				`med_date` = NOW()
		";

		return $this -> query( $sQuery );
	}

	function getVideoArray( $iProfileId )
	{


		$sQuery = "
			SELECT
				" . BX_DOL_TABLE_MEDIA . ".`med_id`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_prof_id`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_type`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_file`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_title`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_status`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_date`,
		 		" . BX_DOL_TABLE_PROFILES . ".`Photos`,
		 		" . BX_DOL_TABLE_PROFILES . ".`PrimPhoto`
			FROM
				" . BX_DOL_TABLE_MEDIA . "
			INNER JOIN " . BX_DOL_TABLE_PROFILES . " ON " . BX_DOL_TABLE_MEDIA . ".`med_prof_id` = " . BX_DOL_TABLE_PROFILES . ".`ID`
			WHERE
				`med_prof_id` = '$iProfileId'
			AND	`med_type` = 'video'
			ORDER BY `med_date` ASC
		";

/*
		echo '<hr>';
		echo $sQuery;
		echo '<hr>';
*/
		$aPhoto = $this -> getAll( $sQuery );

		return $aPhoto;
	}

	function deleteMedia( $iProfileID, $iMediaID, $sMediaType )
    {        
		$sQuery = "
			DELETE FROM " . BX_DOL_TABLE_MEDIA . " WHERE
				`med_id` = '$iMediaID'
			AND	`med_type` = '$sMediaType'
			AND `med_prof_id` = '$iProfileID'
			LIMIT 1
		";
        $this -> query( $sQuery );

        // delete voting
        require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolVoting.php' ); 
        $oVotingMedia = new BxDolVoting ('media', 0, 0);
        $oVotingMedia->deleteVotings ($iMediaID);
	}


	function selectVotingItem( &$oDolVoting, $sVoted, $sSexOnly = '' )
	{
		if( $sSexOnly )
		{
			if( $sSexOnly == 'couple' )
			{
				$sQueryAdd = " AND `Profiles`.`ProfileType` = 'couple' ";
			}
			else
			{
				$sQueryAdd = " AND `Profiles`.`Sex` = '$sSexOnly' ";
			}
		}

		$aSqlVoting = $oDolVoting -> getSqlParts(BX_DOL_TABLE_MEDIA, '`med_id`');

		$sQuery = "
			SELECT
				" . BX_DOL_TABLE_MEDIA . ".`med_id`,
				" . BX_DOL_TABLE_MEDIA . ".`med_prof_id`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_type`,
				" . BX_DOL_TABLE_MEDIA . ".`med_file`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_title`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_status`,
		 		" . BX_DOL_TABLE_MEDIA . ".`med_date`,
                " . BX_DOL_TABLE_PROFILES . ".`PrimPhoto`
				{$aSqlVoting['fields']}
			FROM
			" . BX_DOL_TABLE_MEDIA . "
			INNER JOIN " . BX_DOL_TABLE_PROFILES . " ON " . BX_DOL_TABLE_MEDIA . ".`med_prof_id` = " . BX_DOL_TABLE_PROFILES . ".ID
			{$aSqlVoting['join']}
			WHERE
				" . BX_DOL_TABLE_MEDIA . ".`med_type` = 'photo'
			AND
				" . BX_DOL_TABLE_MEDIA . ".`med_id` NOT IN ($sVoted)
			AND
				" . BX_DOL_TABLE_MEDIA . ".`med_status` = 'active'
			AND
				" . BX_DOL_TABLE_PROFILES . ".`Status` = 'active'
			AND
				`rate_able`='1'
			$sQueryAdd
			ORDER BY RAND()
		";

		return $this -> getRow( $sQuery );
	}
	
}

?>
