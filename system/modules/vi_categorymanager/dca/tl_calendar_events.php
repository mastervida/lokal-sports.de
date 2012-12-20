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
 
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes'] = str_replace
(
  '{expert_legend',
  '{ls_settings:hide},land,sportart;{expert_legend',
  $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['land'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['land'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_ls_events', 'getParentLand'),
	'eval'                    => array('doNotCopy'=>true, 'multiple' => true, 'tl_class' => 'w50'),
	'sql'                     => "longtext NOT NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['sportart'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['sportart'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_ls_events', 'getParentSports'),
	'eval'                    => array('doNotCopy'=>true, 'multiple' => true, 'tl_class' => 'w50'),
	'sql'                     => "longtext NOT NULL"
);


/**
 * Class tl_ls_events
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright		Agentur-VIDA 2011
 * @author			Maik Helsing <info@agentur-vida.de>
 * @package			Controller
 */
class tl_ls_events extends tl_calendar_events
{
  /**
   * Bundesland für News wählbar machen
	 */
	public function getParentLand()
	{
	  $objCats = $this->Database->prepare("SELECT id, title FROM tl_categorys WHERE type=? AND published=?")
	                            ->execute('land', 1);
	  
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
	
	/**
	 * Sportart für News wählbar machen
	 */
	public function getParentSports()
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