<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Comments
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Add content element
 */
$GLOBALS['TL_CTE']['includes']['comments'] = 'ContentComments';


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['application']['comments'] = 'ModuleComments';


/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 5, array
(
	'comments' => array
	(
		'tables'     => array('tl_comments'),
		'icon'       => 'system/modules/comments/public/icon.gif',
		'stylesheet' => 'system/modules/comments/public/style.css'
	)
));
