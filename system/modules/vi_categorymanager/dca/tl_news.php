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
 
$GLOBALS['TL_DCA']['tl_news']['palettes'] = str_replace
(
  '{expert_legend',
  '{ls_settings:hide},land;{expert_legend',
  $GLOBALS['TL_DCA']['tl_news']['palettes']
);

$GLOBALS['TL_DCA']['tl_news']['fields']['land'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_news']['land'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_ls_news', 'getParentLand'),
	'eval'                    => array('doNotCopy'=>true, 'multiple' => true, 'tl_class' => 'w50'),
	'sql'                     => "longtext NOT NULL"
);


/**
 * Class tl_ls_news
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright		Agentur-VIDA 2011
 * @author			Maik Helsing <info@agentur-vida.de>
 * @package			Controller
 */
class tl_ls_news extends tl_news
{
  /**
   * Bundesland für News wählbar machen
	 */
	public function getParentLand($dc)
	{
	  $objArchiv = $this->Database->prepare("SELECT sportart FROM tl_news_archive WHERE id=?")->execute($dc->activeRecord->pid);
	  $objCategory = $this->Database->prepare("SELECT id, title FROM tl_categorys WHERE type=? AND showIn LIKE '%".$objArchiv->sportart."%' AND published=?")->execute('land', 1);
	  
	  if( !$objCategory->numRows )
	  {
	    return false;
	  }
	  
	  $arrCats = array();
	  while($objCategory->next())
	  {
	    $arrCats[$objCategory->id] = $objCategory->title;
	  }
	  
	  return $arrCats;
	}
}