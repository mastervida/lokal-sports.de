<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Vi_system
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
	// Classes
	'lokal-sports.de\AddStyleSheet'     => 'system/modules/vi_system/classes/AddStyleSheet.php',
	'Contao\FrontendFunctions' => 'system/modules/vi_system/classes/FrontendFunctions.php',
	'lokal-sports.de\SocialGrabber'     => 'system/modules/vi_system/classes/SocialGrabber.php',
));
