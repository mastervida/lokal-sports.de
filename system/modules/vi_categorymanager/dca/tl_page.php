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
 
$GLOBALS['TL_DCA']['tl_page']['palettes']['dependTo'] = '{title_legend},title,alias,type;'.
                                                        '{protected_legend:hide},protected;'.
                                                        '{layout_legend:hide},includeLayout;'.
                                                        '{cache_legend:hide},includeCache;'.
                                                        '{chmod_legend:hide},includeChmod;'.
                                                        '{expert_legend:hide},cssClass,sitemap,hide,guests;'.
                                                        '{ls_settings:hide},categoryType;'.
                                                        '{tabnav_legend:hide},tabindex,accesskey;'.
                                                        '{publish_legend},published,start,stop';

$GLOBALS['TL_DCA']['tl_page']['fields']['categoryType'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_page']['categoryType'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'select',
	'options'                 => array('land', 'sportart'),
	'eval'                    => array('mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'),
	'reference'               => &$GLOBALS['TL_LANG']['categorymanager']['types'],
	'sql'                     => "varchar(8) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['title']['eval']['allowHtml'] = true;