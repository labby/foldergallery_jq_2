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


// release 3.0.0. : change table names and directory name to foldergallery
$result = $database->get_one("SELECT page_id FROM ".TABLE_PREFIX."mod_foldergallery_jq_settings");
$table_exists = count($result);

if($table_exists != 0) {
	// save current database
	LEPTON_handle::create_sik_table('mod_foldergallery_jq_settings');
	LEPTON_handle::create_sik_table('mod_foldergallery_jq_files');
	LEPTON_handle::create_sik_table('mod_foldergallery_jq_categories');	

	// create new tables
	$database->simple_query( "RENAME TABLE ".TABLE_PREFIX."mod_foldergallery_jq_settings TO ".TABLE_PREFIX."mod_foldergallery_settings ");
	$database->simple_query( "RENAME TABLE ".TABLE_PREFIX."mod_foldergallery_jq_files TO ".TABLE_PREFIX."mod_foldergallery_files ");
	$database->simple_query( "RENAME TABLE ".TABLE_PREFIX."mod_foldergallery_jq_categories TO ".TABLE_PREFIX."mod_foldergallery_categories ");	
	
	// modify addon entry
	$database->simple_query('UPDATE `' . TABLE_PREFIX . 'addons` SET `directory` =\'foldergallery\' WHERE `guid` =\'c362eb43-878d-492f-906f-57a07da6d0f6\'');
	
	// modify section entries
	$database->simple_query('UPDATE `' . TABLE_PREFIX . 'sections` SET `module` =\'foldergallery\' WHERE `module` =\'foldergallery_jq\'');		
}

// save current backend.css
$oldname = LEPTON_PATH."/modules/foldergallery_jq/css/backend.css";
$newname = LEPTON_PATH."/modules/foldergallery/css/backend_sik.css";
rename ($oldname, $newname);

// delete old directory
LEPTON_handle::delete_obsolete_directories('/modules/foldergallery_jq');

?>