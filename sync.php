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

$admin = new LEPTON_admin('Pages', 'pages_modify');

$file_names = array(
    '/modules/foldergallery/backend.functions.php'
);
LEPTON_handle::include_files ($file_names);

$settings = getSettings($section_id);

$MOD_FOLDERGALLERY = foldergallery::getInstance()->language;

$flag = false;

/* syncDB($galerie) ist kompletter update algorithmus */
if(syncDB($settings)) {

	echo "<center>".$MOD_FOLDERGALLERY['SYNC_DATABASE']."</center><br />";

	// Wieder alle Angaben aus der DB holen um Sortierung festzulegen
	$results = array();
	$sql = "SELECT * FROM `".TABLE_PREFIX."mod_foldergallery_categories` WHERE `section_id` =".$section_id;
	$query = $database->query($sql);
	
	if ( $query->numRows() > 0 ) {
		
    	while($result = $query->fetchRow()) {
			
			$folder = $settings['root_dir'].$result['parent'].'/';//.$result['categorie'];
			$pathToFolder = foldergallery::FG_PATH.$folder;
			echo(LEPTON_tools::display($pathToFolder,'pre','ui info message'));

			if ($result['parent'] != '-1') {; //nicht die roots;
				//checken, ob es das Verzeichnis noch gibt:
				if(!is_dir($pathToFolder)){
					//$delete_sql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id="'.$result['id'].'";';
					//$database->query($delete_sql);
					
					//echo '<p>DELETE: '.$pathToFolder. '</p>';
					//continue;
				}
			}
		
    		$results[] = $result;
    	}
// die(LEPTON_tools::display($results));
    	$niveau = 0;
    	// Alle Kategorien durchlaufen zum Kinder und Parents und Level zuzuordnen
    	foreach($results as &$cat) {
    		$cat['niveau'] = substr_count($cat['parent'],'/');
    		if($cat['niveau'] > $niveau){
    			$niveau = $cat['niveau'];
    		}
    		// String bilden für Parentvergleich
    		$ast = $cat['parent']."/".$cat['categorie'];
			$cat['ast'] = $ast;
			$cat['childs'] = '';
    		// Alle Kategorien durchlaufen und auf gleichheit untersuchen
    		foreach($results as &$searchcat){
    			if($ast == $searchcat['parent']) {
    				// Falls gleich, kann bestimmt werden wer Kind und welcher Parent ist
    				$cat['has_child'] = 1;					
    				$searchcat['parent_id'] = $cat['id'];
    			}
    		}
    	}
		
		//  Find the "childs"
		foreach($results as &$cat) {		
			if ($cat['has_child'] == 0) continue;
			foreach($results as $others) {
				if ($cat['id'] == $others['id']) continue;
				
				// looking for the "parent" of the "child"
				if( $others['parent_id'] == $cat['id'] ) {
					// add the id to the "childs"
					$cat['childs'].= (($cat['childs'] != '') ? ',' : '').$others['id'];
				}			
			}
		}
		//-------------------------

    	// Sortierung festlegen
    	foreach($results as &$cat) {
    		if($cat['position'] == 0) {
    			$last = 0;
    			foreach($results as $vergleich) {
    				if($cat['parent'] == $vergleich['parent']){
    					if($last <= $vergleich['position']) {
    						$last = $vergleich['position'];
    					}
    				}
    			}
    			$cat['position'] = $last+1;
    		}
    	}

    	// Datenkank Update
    	$updatesql = 'UPDATE '.TABLE_PREFIX.'mod_foldergallery_categories SET ';
    	for($i = 0; $i<count($results); $i++){
			$childs = $results[$i]['childs'];
			//$childs=substr($childs,1,strlen($childs-1)); //Führenden Beistrich belassen, der wird in view wieder benotigt
    		$sql = $updatesql." niveau=".$results[$i]['niveau'].", parent_id=".$results[$i]['parent_id'].", has_child=".$results[$i]['has_child'].", position=".$results[$i]['position'].", childs='".$childs."' WHERE id=".$results[$i]['id'].";";
    		if($database->query($sql)){
    			$flag = true;
    		} else {
    			break;
    		}
    	}

    	// Fehler/Lücken in der Sortierung beheben
    	for($i = 0; $i<=$niveau; $i++) {
    		$last_parent = 0;
    		$counter = 1;
    		$sql = "SELECT `position`,`id`, `parent_id` FROM ".TABLE_PREFIX."mod_foldergallery_categories WHERE section_id =".$section_id." AND niveau=".$i." ORDER BY position ASC, parent_id ASC;";
    		$query = $database->query($sql);
    		while($result = $query->fetchRow()){
    			if($last_parent == $result['parent_id']) {
    				if($counter != $result['position']){
    					$sql = $updatesql." `position`=".$counter." WHERE id=".$result['id'].";";
    					$database->query($sql);
    				}
    				$counter++;
    			} else {
    				$last_parent = $result['parent_id'];
    				$counter = 1;
    				if($counter != $result['position']){
    					$sql = $updatesql." `position`=".$counter." WHERE id=".$result['id'].";";
    					$database->query($sql);
    				}
    				$counter++;
    			}
    		}
    	}

// root!
	$aTempRef = array();
	$database->execute_query(
	    "SELECT `id` from `".TABLE_PREFIX."mod_foldergallery_categories` WHERE `parent_id` =0 AND `section_id`=".$section_id." ORDER BY `id`",
	    true,
	    $aTempRef,
	    true
	);
	
	$aAllRootChilds = array();
	foreach($aTempRef as $ref)
	{
	    $aAllRootChilds[] = $ref['id'];
	}
	$database->simple_query("UPDATE `".TABLE_PREFIX."mod_foldergallery_categories` set `childs`='".implode(",", $aAllRootChilds)."' WHERE `section_id`=".$section_id." AND `parent_id`=-1");
//	
      if($flag) {
      	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
      } else {
    	  $admin->print_error("Synchronisation fehlgeschlagen", LEPTON_URL.'/modules/foldergallery/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id);
      }

    }   // keine Kategorien vorhanden
    else {
        $admin->print_error( $MOD_FOLDERGALLERY['NO_CATEGORIES'], LEPTON_URL.'/modules/foldergallery/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id );
    }

}

// Print admin footer
$admin->print_footer();
?>