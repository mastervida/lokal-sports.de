<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package News
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['topNav'] = '{title_legend},name,headline,type;{nav_legend},'.
                                                        'levelOffset,showLevel,hardLimit,showProtected;'.
                                                        '{reference_legend:hide},defineRoot;'.
                                                        '{template_legend:hide},navigationTpl;'.
                                                        '{protected_legend:hide},protected;'.
                                                        '{expert_legend:hide},guests,cssID,space';
