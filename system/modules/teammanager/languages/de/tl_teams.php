<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   lokal-sports.de 
 * @author    Maik Helsing 
 * @license   GNU 
 * @copyright Agentur-VIDA 
 */

$GLOBALS['TL_LANG']['tl_teams'] = array
(
  /**
   * Fields
   */
	'title' => array('Teamname', 'Geben Sie den Namen des Teams ein'),
	'shortname' => array('Kurzname', 'Geben Sie zur besseren Darstellung einen möglichst kurzen Teamnamen ein'),
	'gender' => array
	(
		0 => 'Geschlecht', 
		1 => 'Wählen Sie das Geschlecht des Teams aus',
		'male' => 'Herren',
		'female' => 'Damen'
	),
	'alias' => array('Teamalias', 'Der Teamalias ist eine eindeutige Referenz, die anstelle der numerischen Team-ID aufgerufen werden kann'),
	'sportart' => array('Sportart', 'Ordnen Sie diesem Team eine Sportart zu.'),

	'street' => array('Straße', 'Hier können Sie die Straße des Teamsitzes eingeben'),
	'number' => array('Hausnummer', 'Hier können Sie die Hausnummer des Teamsitzes eingeben'),
	'postal' => array('Postleitzahl', 'Geben Sie die Postleitzahl des Teamsitzes ein'),
	'city' => array('Ort', 'Geben Sie den Ort des Teamsitzes ein'),
	'coordinates' => array('Koordinaten', 'Hier können Sie die Google-Maps-Koordinaten des Teamsitzes eingeben. Diese werden aber auch automatisch erzeugt'),

	'contact' => array('Ansprechpartner', 'Hier können Sie einen Ansprechpartner für dieses Team eingeben'),
	'phone' => array('Telefonnummer', 'Hier können Sie eine Telefonnummer eingeben'),
	'email' => array('E-Mail Adresse', 'Hier können Sie eine E-Mail Adresse eingeben'),
	'website' => array('Webseite', 'Hier können Sie die Webseite des Teams eintragen'),

	'addImage' => array('Teamfoto hinzufügen', 'Wählen Sie diese Option um diesem Team ein Foto hinzuzufügen'),
	'singleSRC' => array('Teamfoto auswählen', 'Wählen Sie das Teamfoto aus: ACHTUNG: die Teamfotos müssen im Ordner /teams/ hinterlegt sein'),
	'caption' => array('Bildunterschrift', 'Hier können Sie einen kurzen Text eingeben, der unterhalb des Bildes angezeigt wird.'),

	'published' => array('Veröffentlichen', 'Erst wenn Sie diese Option wählen, wird dieses Team auf der Webseite angezeigt'),
	'featured' => array('Team markieren', 'Wenn Sie diese Option anzeigen, wird dieses Team in der Hauptnavigation angezeigt. ACHTUNG: Es werden maximal 10 Team ausgegeben'),
	'showIn' => array('Anzeigen in', 'Wählen Sie aus, in welchem Bereich der Webseite dieses Team angezeigt werden soll.'),

	/**
	 * Legends
	 */
	'title_legend' => 'Teamname &amp; Identifizierung',
	'adress_legend' => 'Adresse &amp; Koordinaten',
	'contact_legend' => 'Ansprechpartner &amp; Kontaktmöglichkeiten',
	'photo_legend' => 'Teamfoto',
	'publish_legend' => 'Veröffentlichung',

	/**
	 * Buttons
	 */
	'new' => array('Neues Team', 'neues Team anlegen'),
	'show' => array('Teamdetails', 'Die Details des Teams (ID %s) anzeigen'),
	'edit' => array('Team bearbeiten', 'Team (ID %s) bearbeiten'),
	'delete' => array('Team löschen', 'Team (ID %s) löschen'),
	'toggle' => array('Team veröffentlichen/unveröffentlichen', 'Team (ID %s) veröffentlichen/unveröffentlichen')
);
