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
 
$GLOBALS['TL_DCA']['tl_news_archive']['palettes'] = str_replace
(
  '{comments_legend',
  '{ls_settings:hide},sportart;{comments_legend',
  $GLOBALS['TL_DCA']['tl_news_archive']['palettes']
);


$GLOBALS['TL_DCA']['tl_news_archive']['fields']['sportart'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_news_archive']['sportart'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_ls_news_archive', 'getSports'),
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);


/**
 * Class tl_ls_news_archive
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright		Agentur-VIDA 2011
 * @author			Maik Helsing <info@agentur-vida.de>
 * @package			Controller
 */
class tl_ls_news_archive extends tl_news_archive
{
	 /**
 	 * Sportarten anzeigen
 	 * Team muss einer Sportart zugeordnet werden
 	 */
 	public function getSports()
 	{
 	  $objCats = $this->Database->prepare("SELECT id, title FROM tl_categorys WHERE type=? AND published=?")
 	                            ->execute('sportart', 1);

 	  if( !$objCats->numRows )
 	  {
 	    return false;
 	  }

 	  $arrCats = array();
 	  while($objCats->next())
 	  {
 	    $arrCats[$objCats->id] = $objCats->title;
 	  }

 	  return $arrCats;
 	}
}