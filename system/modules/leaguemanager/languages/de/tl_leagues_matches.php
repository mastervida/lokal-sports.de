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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_leagues_matches'] = array
(
  // Fields
  'start_date' => array('Ansetzungsdatum', 'Geben Sie das Ansetzungsdatum des Spiels ein.'),
  'team_home' => array('Heimmannschaft', 'Wählen Sie die Heimmannschaft aus.'),
  'score_home' => array('Ergebnis: Heimmannschaft', 'Geben Sie das Ergebnis für die Heimmannschaft ein.'),
  'team_away' => array('Auswärtsmannschaft', 'Wählen Sie die Auswärtsmannschaft aus.'),
  'score_away' => array('Ergebnis: Auswärtsmannschaft', 'Geben Sie das Ergebnis für die Auswärtsmannschaft ein.'),
  'scoreing' => array('Ergebnisse eintragen', 'Geben Sie die Ergebnisse der Spielsätze ein. <strong>Lassen Sie nicht gespielte Sätze frei</strong>'),
  
  'events' => array('Spielausfall', 'Wählen Sie diese Option, wenn das Spiel nicht stattgefunden hat'),
  'reason' => array('Ausfallgrund', 'Wählen Sie den Grund des Spielausfalls aus.'),
  'nextdate' => array('Spielwiederholung', 'Geben Sie den Termin für die Spielwiederholung ein.'),
  
  'different_points' => array('Abweichende Punktevergabe', 'Die Punkte weichen von denen im Wettbewerb eingestellten Punkten ab'),
  'points_home' => array('Punkte: Heimmannschaft', 'Geben Sie die Punkte für die Heimmannschaft ein.'),
  'points_away' => array('Punkte: Auswärtsmannschaft', 'Geben Sie die Punkte für die Auswärtsmannschaft ein.'),
  'result_confirmed' => array('Ergebnis bestätigt', 'Wählen Sie diese Option, wenn das Ergebnis offiziell bestätigt wurde.'),
  
  'addLocation' => array('Spielort hinzufügen', 'Wählen Sie diese Option, wenn Sie diesem Spiel einen Ort hinzufügen möchten.'),
  'location' => array('Location', 'Geben Sie den Ort des Spiels ein.'),
  'street' => array('Straße', 'Straße des Spielorts'),
  'zip' => array('PLZ', 'Postleitzahl des Spielorts'),
  'city' => array('Stadt', 'Ort des Spielorts'),
  
  // legends
  'datetime_legend' => 'Ansetzungsdatum',
  'result_legend' => 'Ergebnis-Einstellungen',
  'location_legend' => 'Spielort hinzufügen',
  'event_legend' => 'Spiel hat nicht am besagten Termin stattgefunden',
  'confirm_legend' => 'Ergebnis bestätigen',
  
  // buttons
  'new' => array('Neues Spiel anlegen', 'Eine neues Spiel anlegen'),
  'show' => array('Details anzeigen', 'Details des Spiels (%s) anzeigen'),
  'edit' => array('Spiel bearbeiten', 'Dieses Spiel (%s) bearbeiten'),
  'delete' => array('Spiel löschen', 'Dieses Spiel (%s) löschen')
);
