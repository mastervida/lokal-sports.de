<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Vi_categorymanager
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
	'lokal-sports.de\CalendarEventsModelModel' => 'system/modules/vi_categorymanager/models/CalendarEventsModelModel.php',
	'lokal-sports.de\CategoryModelModel'       => 'system/modules/vi_categorymanager/models/CategoryModelModel.php',
	'lokal-sports.de\NewsModelModel'           => 'system/modules/vi_categorymanager/models/NewsModelModel.php',

	// Modules
	'lokal-sports.de\ModuleCategoryManager'    => 'system/modules/vi_categorymanager/modules/ModuleCategoryManager.php',
	'lokal-sports.de\ModuleCategoryNavigation' => 'system/modules/vi_categorymanager/modules/ModuleCategoryNavigation.php',
	'Contao\ModuleTopNavigation'               => 'system/modules/vi_categorymanager/modules/ModuleTopNavigation.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'nav_lokalSports' => 'system/modules/vi_categorymanager/templates',
));
