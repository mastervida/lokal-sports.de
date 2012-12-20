<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Leaguemanager
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'lokal-sports.de',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'lokal-sports.de\LeaguesMatchesModelModel' => 'system/modules/leaguemanager/models/LeaguesMatchesModelModel.php',
	'lokal-sports.de\LeaguesModelModel'        => 'system/modules/leaguemanager/models/LeaguesModelModel.php',
	'lokal-sports.de\LeaguesRoundsModelModel'  => 'system/modules/leaguemanager/models/LeaguesRoundsModelModel.php',

	// Modules
	'lokal-sports.de\ModuleLeagues'            => 'system/modules/leaguemanager/modules/ModuleLeagues.php',
	'lokal-sports.de\ModuleLeaguesMatchDay'    => 'system/modules/leaguemanager/modules/ModuleLeaguesMatchDay.php',
	'lokal-sports.de\ModuleLeaguesOverview'    => 'system/modules/leaguemanager/modules/ModuleLeaguesOverview.php',

	// Scripts
	'cmp'                                      => 'system/modules/leaguemanager/scripts/cmp.php',
	'Contao\ScoreWizard'                       => 'system/modules/leaguemanager/scripts/ScoreWizard.php',
));
