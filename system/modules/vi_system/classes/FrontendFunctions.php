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
 * Namespace
 */
namespace Contao;


/**
 * Class FrontendFunctions 
 *
 * @copyright  Agentur-VIDA 
 * @author     Maik Helsing 
 * @package    Devtools
 */
class FrontendFunctions extends \System 
{
  /**
   * Checkt die pageId
   * prüft alle Links auf seine Ausnahmen
   * gibt die richtige pageId zurück
   * Setzt dann die richtigen Werte für Bundesland & Sportart
   * gilt für Landesstartseite, Sportart-Startseite, Sportnews-Seiten
   */
  public static function checkPageId(&$index, $pageId)
	{
		// Datenbank importieren
		$db = &$index->Database;
		$input = &$index->Input;
	}
	
	
	public static function checkPageModel(&$index, $objPage)
	{
	  $db = &$index->Database;
		$input = &$index->Input;
		
	  if( $objPage === NULL )
	  {
	    $temp = str_replace(".html", "", $_SERVER['REQUEST_URI']);
  		$laenge = strlen($temp)-1; 
  		$temp = substr($temp,1,$laenge);
  		$domain = explode("/", trim($temp) );
  		
	    switch( count($domain) )
	    {
	      // Startpage or no page
	      case 0:
	        return $objPage;
	        break;
	      
	      // landstartseiten oder einzelne seiten
	      case 1:
	        $objCat = $db->prepare("SELECT id, title, alias FROM tl_categorys WHERE alias=? AND type=? AND published=?")
	                     ->execute($temp, 'land', 1);
	        if( !$objCat->numRows )
	        {
	          return $objPage;
	        }
	        
	        $objPage = PageModel::findPublishedByIdOrAlias(4);
	        $objPage->pageTitle = str_replace("lsBLand", $objCat->title, $objPage->pageTitle);
	        $objPage->title = $objCat->title;
	        $objPage->alias = $objCat->alias;
	        $objPage->category = 'land';
	        $objPage->category_title = $objCat->title;
	        $objPage->category_alias = $objCat->alias;
	        return $objPage;
	        break;
	      
	      // Sportstartseite oder erste ebene
	      default:
	        $objCat = $db->prepare("SELECT id, title, alias FROM tl_categorys WHERE alias=? AND type=? AND published=?")
	                     ->execute($domain[0], 'land', 1);
	        if( !$objCat->numRows )
	        {
	          return $objPage;
	        }
	        
	        $temp = str_replace($domain[0], 'landstart', $temp);
	        $objPage = PageModel::findPublishedByIdOrAlias($temp);
	        $objPage->title = $objCat->title;
	        $objPage->pageTitle = str_replace("lsBLand", $objCat->title, $objPage->pageTitle);
	        $objPage->category = 'land';
	        $objPage->category_title = $objCat->title;
	        $objPage->category_alias = $objCat->alias;
	    }
	  }
	  
	  return $objPage;
	}
}
