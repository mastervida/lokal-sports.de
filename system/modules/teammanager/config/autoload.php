<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Teammanager
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
	'lokal-sports.de\\TeamsModelModel'  => 'system/modules/teammanager/models/TeamsModelModel.php',

	// Modules
	'lokal-sports.de\\ModuleTeamList'   => 'system/modules/teammanager/modules/ModuleTeamList.php',
	'lokal-sports.de\\ModuleTeamViewer' => 'system/modules/teammanager/modules/ModuleTeamViewer.php',
	'lokal-sports.de\\ModuleTeams'      => 'system/modules/teammanager/modules/ModuleTeams.php',
));
