<?php

/**
 *  @module         foldergallery
 *  @version        see info.php of this module
 *  @author         Jürg Rast, schliffer, Bianka Martinovic, Chio, Pumpi, Aldus, erpe
 *  @copyright      2009-2018 Jürg Rast, schliffer, Bianka Martinovic, Chio, Pumpi, Aldus, erpe 
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

// Modul Description
$module_description = 'Vollautomatische Bildergalerie mit Ordner als Kategorien erstellen.';

$MOD_FOLDERGALLERY = array(
// Variables for the Frontend
   'VIEW_TITLE'		=> 'Bildergalerie',
   'CATEGORIES_TITLE' 	=> 'Kategorien',
   'BACK_STRING'		=> 'Zur Übersicht',
   'FRONT_END_ERROR' 	=> 'Diese Kategorie existiert nicht oder enthält keine Bilder und Unterkategorien!',
   'PAGE'             => 'Seite',

// Variables for the Backend
   'PICS_PP'  => 'Bilder pro Seite',
   'LIGHTBOX'  => 'Lightbox',

   'MODIFY_CAT_TITLE' 	=> 'Kategorie und Bilddetailes bearbeiten',
   'MODIFY_CAT' 			=> 'Kategoriedetailes bearbeiten:',
   'CAT_NAME' 			=> 'Kategoriename/Titel:',
   'CAT_DESCRIPTION' 	=> 'Kategoriebeschrieb:',
   'MODIFY_IMG' 			=> 'Bilder bearbeiten:',
   'IMAGE' 				=> 'Bild',
   'IMAGE_NAME' 			=> 'Bildname',
   'IMG_CAPTION'		=> 'Bildbeschreibung',

   'REDIRECT'   			=> 'Sie müssen zuerst die Grundeinstellungen vornehmen.'
											. ' Sie werden in zwei Sekunden weitergeleitet! (Funktioniert nur wenn JavaScript aktiviert!',
   'TITEL_BACKEND'  		=> 'Foldergallery Verwaltung',
   'TITEL_MODIFY'  		=> 'Kategorien und Bilder bearbeiten:',
   'SETTINGS'  			=> 'Allgemeine Einstellungen',
   'ROOT_DIR'  			=> 'Stammverzeichnis',
   'EXTENSIONS' 			=> 'Erlaubte Dateien',
   'INVISIBLE' 			=> 'Unsichtbare Ordner',
   'NEW_SCANN_INFO'		=> 'Durch diese Aktion wurden erst die Datenbankeintr&auml;ge erstellt. Die Vorschaubilder werden automatisch beim ersten Aufruf der Kategorie erzeugt!',
   'FOLDER_NAME'		=> 'Ordnername im Dateisystem',
   'DELETE' 				=> 'Löschen?',
   'ERROR_MESSAGE'		=> 'Keine Daten zum verarbeiten Erhalten!',
   'DB_ERROR' 			=> 'Datenbank Fehler!',
   'FS_ERROR' 			=> 'Fehler beim löschen des Ordners!',
   'NO_FILES_IN_CAT' 	=> 'Diese Kategorie enth&auml;lt keine Bilder!',
   'SYNC' 				=> 'Datenbank mit Filesystem synchronisieren',
   'EDIT_CSS' 			=> 'CSS bearbeiten',
   'FOLDER_IN_FS'		=> 'Ordner im Dateisystem:',
   'CAT_TITLE' 			=> 'Kategorietitel:',
   'ACTION' 				=> 'Aktionen:',
   'NO_CATEGORIES'  		=> 'Keine Kategorien (=Unterverzeichnisse) vorhanden.<br /><br />Die Galerie funktioniert trotzdem, zeigt aber keine Kategorien an.',
   'EDIT_THUMB'  		=> 'Thumbnail bearbeiten',
   'EDIT_THUMB_DESCRIPTION'		=> '<strong>Bitte neuen Bildausschnitt wählen</strong>',
   'EDIT_THUMB_BUTTON' 			=> 'Thumbnail erstellen',
   'THUMB_SIZE' 			=> 'Thumbnail Größe',
   'THUMB_RATIO'		=> 'Thumbnail Verhältniss',
   'THUMB_NOT_NEW'		=> 'Keine neuen Thumbnails erzeugen',
   'CHANGING_INFO'		=> 'Das ändern von <strong>Thumbnail Größe</strong> oder <strong>Thumbnail Verhältniss</strong> bewirkt das löschen (und neu erstellen) aller Thumbnails.',
   'SYNC_DATABASE'		=> 'Synchronisiere Dateisystem mit Datenbank...',
   'SAVE_SETTINGS'		=> 'Einstellungen werden gespeichert...',
   'SORT_IMAGE' 			=> 'Bilder sortieren',
   'BACK' 				=> 'Zurück',
   'REORDER_INFO_STRING'    => 'Der Erfolg der Neuanordnung wird hier angezeigt.',
   'HELP_INFORMATION'       => 'Hilfe / Info',

   'Ration_square'       => 'quadratisch',

// Tooltips
   'ROOT_FOLDER_STRING_TT' 	=> 'Dieser Ordner legt den Stammordner fest, in welchem rekursiv nach Bilder gesucht wird. Bitte nur beim installieren ändern, sonst gehen alle Infos zu den Bilder verloren!',
   'EXTENSIONS_STRING_TT' 	=> 'Legen sie hier die erlaubten Dateierweiterungen fest. Verwenden sie das Koma als Trennzeichen. Auf Gross-/Kleinschreibung wird nicht geachtet.',
   'INVISIBLE_STRING_TT' 	=> 'Ordner die sie hier eintragen werden nicht durchsucht.',
   'DELETE_TITLE_TT'		=> 'Achtung, es werden ALLE Bilder und Unterkategorien mitsamt den Bilder vom Server gelöscht!',
   
// Helppage
    'TITLE'            => 'Foldergallery-jQuery: Hilfe- und Infoseite',
    'VERSION'          => 'Versionsinfo',
    'YOUR_VERSION'     => 'Sie verwenden Version %s.',
    'NOUPDATES'        => 'Dies ist momentan die aktuellste Version!',
    'UPDATE'           => 'Version %s ist verfügbar, es wird empfohlen ein Update durchzuführen.',
    'VERSION_TEXT'     => 'Die aktuellste Version ist immer auf <a href="http://www.lepton-cms.org/deutsch/addons/freie-addons.php" target="_blank">der LEPTON Homepage</a>'
                            .' oder bei <a href="http://cms-lab.com/lab/de/module/standard-module/foldergallery-jquery.php" target="_blank" >CMS-LAB</a> zu finden!',
    'HOMEPAGE_TEXT'    => 'Auf <a href="https://github.com/labby/foldergallery" target="_blank">GITHUB</a> finden sie den gesamten Changelog sowie ältere Versionen und den aktuellen Entwicklungsstand.',
                           
    'HELP_TITLE'       => 'Hilfe und Support',
    'HELP_TEXT'        => 'Support wird im <a href="http://forum.lepton-cms.org" target="_blank">LEPTON CMS Addons Forum</a> angeboten.',
    'BUG_TITLE'        => 'Problem melden',
    'BUG_TEXT'         => 'Fehler können ebenso im  <a href="http://forum.lepton-cms.org/" target="_blank">LEPTON CMS Addons Forum</a> oder auf <a href="https://github.com/labby/foldergallery" target="_blank">GITHUB</a> gemeldet werden!',

    'BACK_STRING'      => 'Zurück' 
);
?>