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
$GLOBALS['TL_LANG']['LEAGUEMANAGER']['title'] = 'lokal-sports.de Ligamanager';
$GLOBALS['TL_LANG']['LEAGUEMANAGER']['alias'] = array
(
  'male' => 'herren',
  'female' => 'damen',
  'mixed' => 'mixed'
);

$GLOBALS['TL_LANG']['LEAGUETYPE']['L'] = 'Liga';
$GLOBALS['TL_LANG']['LEAGUETYPE']['T'] = 'Turnier';

$GLOBALS['TL_LANG']['tl_leagues'] = array
(
  // Fields
  'mode' => array('Wettbewerbstyp', 'Wählen Sie bitte den Wettbewerbstyp aus.'),
  'name' => array('Name', 'Geben Sie den vollständigen Namen des Wettbewerbs ein.'),
  'shortname' => array('Kurzname', 'Hier können Sie einen Kurznamen eingeben.'),
  'sportart' => array('Sportart auswählen', 'In welcher Sportart wird dieser Wettbewerb ausgetragen?'),
  'gender' => array('Geschlecht auswählen', 'Bestimmen Sie das Geschlecht der Liga. Damen/Herren'),
  
  'date_start' => array('Startdatum', 'Geben Sie bitte das Startdatum des Wettbewerbs ein'),
  'date_end' => array('Enddatum', 'Geben Sie bitte das Enddatum des Wettbewerbs ein'),
  'teams' => array('Mannschaften', 'Wählen Sie die teilnehmenden Mannschaften aus'),
  
  'home_wins_points_home' => array('Heimsieg: Punkte Heimmannschaft', 'Punkte für die Heimmannschaft im Falle eines Heimsieges'),
  'home_wins_points_away' => array('HeimSieg: Punkte Gastmannschaft', 'Punkte für die Gastmannschaft im Falle eines Heimsieges'),
  'draw_points_home' => array('Unentschieden: Punkte Heimmannschaft', 'Punkte für die Heimmannschaft im Falle eines Unentschieden'),
  'draw_points_away' => array('Unentschieden: Punkte Gastmannschaft', 'Punkte für die Gastmannschaft im Falle eines Unentschieden'),
  'away_wins_points_home' => array('Auswärtssieg: Punkte Heimmannschaft', 'Punkte für die Heimmannschaft im Falle eines Auswärtssieges'),
  'away_wins_points_away' => array('Auswärtssieg: Punkte Gastmannschaft', 'Punkte für die Gastmannschaft im Falle eines Auswärtssieges'),
  
  'showLittlePoints' => array('Kleine Punkte anzeigen', 'Wählen Sie diese Option, wenn die "kleinen Punkte" für diesen Wettbewerb angezeigt werden sollen.'),
  
  'create_round' => array('Spieltage/Runden hinzufügen', 'Geben Sie die Anzahl der Spieltage/Runden ein, die Sie hinzufügen möchten'),
  
  'published' => array('Veröffentlichen', 'Wählen Sie diese Option um diesen Wettbewerb auf der Webseite anzuzeigen'),
  'start' => array('Anzeigen ab', 'Den Wettbewerb erst ab diesem Tag auf der Webseite anzeigen.'),
  'stop' => array('Anzeigen bis', 'Den Beitrag nur bis zu diesem Tag auf der Webseite anzeigen.'),
  
  // legends
  'title_legend' => 'Bezeichnung',
  'settings_legend' => 'Ligaeinstellungen',
  'rules_legend' => 'Ligaregeln, Punktverteilung',
  'rounds_legend' => 'Spieltage/Runden hinzufügen',
  'publish_legend' => 'Veröffentlichen',
  
  // references
  'create_rounds' => array
  (
    'title_tournament' => 'Runde',
    'title_league' => 'Spieltag',
  ),
  
  // buttons
  'new' => array('Neuen Wettbewerb anlegen', 'Einen neuen Wettbewerb anlegen'),
  'show' => array('Details anzeigen', 'Details des Wettbewerbs (%s) anzeigen'),
  'edit' => array('Wettbewerb bearbeiten', 'Diesen Wettbewerb (%s) bearbeiten'),
  'delete' => array('Wettbewerb löschen', 'Diesen Wettbewerb (%s) löschen'),
  'editheader' => array('Wettbewerbseinstellungen bearbeiten', 'Die Einstellungen dieses Wettbewerbs bearbeiten')
);
