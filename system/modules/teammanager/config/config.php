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
 * BACK END MODULES
 *
 * Back end modules are stored in a global array called "BE_MOD". You can add
 * your own modules by adding them to the array.
 */
array_insert($GLOBALS['BE_MOD'], 1, array
(
 'categorymanager' => array
 (
   'teammanager' => array
		(
			'tables'	=> array('tl_teams'),
			'icon'   => 'system/modules/teammanager/html/team.gif',
		),
 )
));


/**
 * FRONT END MODULES
 *
 * Front end modules are stored in a global array called "FE_MOD". You can add
 * your own modules by adding them to the array.
 *
 * $GLOBALS['FE_MOD'] = array
 * (
 *    'group_1' => array
 *    (
 *       'module_1' => 'ModuleClass1',
 *       'module_2' => 'ModuleClass2'
 *    )
 * );
 *
 * The keys (like "module_1") are the module names, which are e.g. stored in the
 * database and used to find the corresponding translations. The values (like
 * "ModuleClass1") are the names of the classes, which will be loaded when the
 * module is rendered. The class "ModuleClass1" has to be stored in a file
 * named "ModuleClass1.php" in your module folder.
 */


/**
 * CONTENT ELEMENTS
 *
 * Content elements are stored in a global array called "TL_CTE". You can add
 * your own content elements by adding them to the array.
 *
 * $GLOBALS['TL_CTE'] = array
 * (
 *    'group_1' => array
 *    (
 *       'cte_1' => 'ContentClass1',
 *       'cte_2' => 'ContentClass2'
 *    )
 * );
 *
 * The keys (like "cte_1") are the element names, which are e.g. stored in the
 * database and used to find the corresponding translations. The values (like
 * "ContentClass1") are the names of the classes, which will be loaded when the
 * element is rendered. The class "ContentClass1" has to be stored in a file
 * named "ContentClass1.php" in your module folder.
 */


/**
 * BACK END FORM FIELDS
 *
 * Back end form fields are stored in a global array called "BE_FFL". You can
 * add your own form fields by adding them to the array.
 *
 * $GLOBALS['BE_FFL'] = array
 * (
 *    'input'  => 'FieldClass1',
 *    'select' => 'FieldClass2'
 * );
 *
 * The keys (like "input") are the field names, which are e.g. stored in the
 * database and used to find the corresponding translations. The values (like
 * "FieldClass1") are the names of the classes, which will be loaded when the
 * field is rendered. The class "FieldClass1" has to be stored in a file named
 * "FieldClass1.php" in your module folder.
 */


/**
 * FRONT END FORM FIELDS
 *
 * Front end form fields are stored in a global array called "TL_FFL". You can
 * add your own form fields by adding them to the array.
 *
 * $GLOBALS['TL_FFL'] = array
 * (
 *    'input'  => 'FieldClass1',
 *    'select' => 'FieldClass2'
 * );
 *
 * The keys (like "input") are the field names, which are e.g. stored in the
 * database and used to find the corresponding translations. The values (like
 * "FieldClass1") are the names of the classes, which will be loaded when the
 * field is rendered. The class "FieldClass1" has to be stored in a file named
 * "FieldClass1.php" in your module folder.
 */


/**
 * PAGE TYPES
 *
 * Page types are stored in a global array called "TL_PTY". You can add your own
 * page types by adding them to the array.
 *
 * $GLOBALS['TL_PTY'] = array
 * (
 *    'type_1' => 'PageType1',
 *    'type_2' => 'PageType2'
 * );
 *
 * The keys (like "type_1") are the field names, which are e.g. stored in the
 * database and used to find the corresponding translations. The values (like
 * "PageType1") are the names of the classes, which will be loaded when the
 * page is rendered. The class "PageType1" has to be stored in a file named
 * "PageType1.php" in your module folder.
 */


/**
 * MAINTENANCE MODULES
 *
 * Maintenance modules are stored in a global array called "TL_MAINTENANCE". You
 * can add your own maintenance modules by adding them to the array.
 *
 * $GLOBALS['TL_MAINTENANCE'] = array
 * (
 *    'ClearCache',
 *    'RebuildSearchIndex'
 * );
 *
 * Take a look at the system/modules/core/PurgeData.php file to see how
 * maintenance modules are set up. The class "ClearCache" has to be stored in a
 * file named "ClearCache.php" in your module folder.
 */


/**
 * PURGE JOBS
 *
 * Purge jobs are stored in a global array called "TL_PURGE". You can add your
 * own purge jobs by adding them to the array.
 *
 * $GLOBALS['TL_PURGE'] = array
 * (
 *    'job_1' => array
 *    (
 *       'tables' => array
 *       (
 *          'index' => array
 *          (
 *             'callback' => array('Automator', 'purgeSearchTables'),
 *             'affected' => array('tl_search', 'tl_search_index')
 *          ),
 *       )
 *   );
 *
 * There are three categories: "tables" stores jobs which truncate database
 * tables, "folders" stores jobs which purge folders and "custom" stores jobs
 * which only trigger a callback function.
 */


/**
 * CRON JOBS
 *
 * Cron jobs are stored in a global array called "TL_CRON". You can add your own
 * cron jobs by adding them to the array.
 *
 * $GLOBALS['TL_CRON'] = array
 * (
 *    'monthly' => array
 *    (
 *       array('Automator', 'purgeImageCache')
 *    ),
 *    'weekly' => array(),
 *    'daily' => array(),
 *    'hourly' => array()
 * );
 *
 * Note that this is rather a command scheduler than a cron job, which does not
 * guarantee an exact execution time. You can replace the command scheduler with
 * a real cron job though.
 */


/**
 * HOOKS
 *
 * Hooks are stored in a global array called "TL_HOOKS". You can register your
 * own functions by adding them to the array.
 *
 * $GLOBALS['TL_HOOKS'] = array
 * (
 *    'hook_1' => array
 *    (
 *       array('MyClass', 'myPostLogin'),
 *       array('MyClass', 'myPostLogout')
 *    )
 * );
 *
 * Hooks allow you to add functionality to the core without having to modify the
 * source code by registering callback functions to be executed on a particular
 * event. For more information see http://www.contao.org/de/hooks.html.
 */


/**
 * AUTO ITEMS
 *
 * Auto items are stored in a global array called "TL_AUTO_ITEM". You can
 * register your own auto items by adding them to the array.
 *
 * $GLOBALS['TL_AUTO_ITEM'] = array('items', 'events');
 *
 * Auto items are keywords, which are used as parameters by certain modules.
 * When rebuilding the search index URLs, Contao needs to know about these
 * keywords so it can handle them properly.
 */
