<?php

/**
 *  @module         foldergallery_jq
 *  @version        see info.php of this module
 *  @author         Jürg Rast, schliffer, Bianka Martinovic, Chio, Pumpi, Aldus, erpe
 *  @copyright      2009-2017 Jürg Rast, schliffer, Bianka Martinovic, Chio, Pumpi, Aldus, erpe 
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

require(LEPTON_PATH . '/modules/admin.php');
require_once (LEPTON_PATH.'/modules/foldergallery_jq/info.php');

// load template engine
if(!class_exists('Template')) {
	require_once LEPTON_PATH .'/include/phplib/template.inc';
}

// check if module language file exists for the language set by the user (e.g. DE, EN)
if (!file_exists(LEPTON_PATH . '/modules/foldergallery_jq/languages/' . LANGUAGE . '.php')) {
// no module language file exists for the language set by the user, include default module language file EN.php
    require_once(LEPTON_PATH . '/modules/foldergallery_jq/languages/EN.php');
} else {
// a module language file exists for the language defined by the user, load it
    require_once(LEPTON_PATH . '/modules/foldergallery_jq/languages/' . LANGUAGE . '.php');
}

//Template
$t = new Template(dirname(__FILE__).'/templates', 'remove');
$t->set_file('help', 'help.htt');
// clear the comment-block, if present
$t->set_block('help', 'CommentDoc'); $t->clear_var('CommentDoc');

$t->set_var(array(
    'TITLE'                 => $FG_HELP['TITLE'],
    'VERSION'               => $FG_HELP['VERSION'],
    'YOUR_VERSION_TEXT'     => sprintf($FG_HELP['YOUR_VERSION'],$module_version),
    'VERSION_TEXT'          => $FG_HELP['VERSION_TEXT'],
    'HOMEPAGE_TEXT'         => $FG_HELP['HOMEPAGE_TEXT'],
    'HELP_TITLE'            => $FG_HELP['HELP_TITLE'],
    'HELP_TEXT'             => $FG_HELP['HELP_TEXT'],
    'BUG_TITLE'             => $FG_HELP['BUG_TITLE'],
    'BUG_TEXT'              => $FG_HELP['BUG_TEXT'],
    'BACK_STRING'           => $FG_HELP['BACK_STRING'],
    'BACK_ONCLICK'          => 'javascript: window.location = \''.ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'\';'
));

$t->pparse('output', 'help');

$admin->print_footer();
?>