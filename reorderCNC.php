<?php

/**
 *  @module         foldergallery
 *  @version        see info.php of this module
 *  @author         cms-lab (initiated by Jürg Rast)
 *  @copyright      2010-2018 cms-lab 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 * 
 */
 
// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php


// IS THIS FILE NEEDED ANYMORE?




if(!isset($_POST['action']) OR !isset($_POST['recordsArray'])) {
	header( 'Location: ../../index.php' ) ;
} else 
{	
	$admin = new LEPTON_admin('Modules', 'module_view', false, false);  // prevent double header!
	$oFG = foldergallery::getInstance();

	// Sanitized variables
	$action = addslashes($_POST['action']);
	$updateRecordsArray = isset($_POST['recordsArray']) ? $_POST['recordsArray'] : array();

	// only "updateRecordsListings" is allowed
	if ($action == "updateRecordsListings"){
	 
		$listingCounter = 1;
		$output = "";
		foreach ($updateRecordsArray as $recordIDValue) {
			
			$oFG->database->query("UPDATE `".TABLE_PREFIX."mod_foldergallery_files` SET position = ".$listingCounter." WHERE `id` = ".$recordIDValue);

			$listingCounter ++;
		}
		echo '<span><i class="large green crop icon"></i>Sucessfully reorderd</span>';
//		echo 'You successfuly reordered';

	}
} 
?>