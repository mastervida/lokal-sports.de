<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes articles
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class ArticleModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_article';


	/**
	 * Find an article by its ID or alias and its page
	 * 
	 * @param mixed   $varId  The numeric ID or alias name
	 * @param integer $intPid The page ID
	 * 
	 * @return \Model|null The model or null if there is no article
	 */
	public static function findByIdOrAliasAndPid($varId, $intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if ($intPid)
		{
			$arrColumns[] = "$t.pid=?";
			$arrValues[] = $intPid;
		}

		return static::findOneBy($arrColumns, $arrValues);
	}


	/**
	 * Find a published article by its ID
	 * 
	 * @param integer $intId The article ID
	 * 
	 * @return \Model|null The model or null if there is no published article
	 */
	public static function findPublishedById($intId)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intId);
	}


	/**
	 * Find all published articles by their parent ID and column
	 * 
	 * @param integer $intPid    The page ID
	 * @param string  $strColumn The column name
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no articles in the given column
	 */
	public static function findPublishedByPidAndColumn($intPid, $strColumn)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.inColumn=?");
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $arrValues, array('order'=>"$t.sorting"));
	}


	/**
	 * Find all published articles with teaser by their parent ID and column
	 * 
	 * @param integer $intPid    The page ID
	 * @param string  $strColumn The column name
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no articles in the given column
	 */
	public static function findPublishedWithTeaserByPidAndColumn($intPid, $strColumn)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.inColumn=? AND $t.showTeaser=1");
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $arrValues, array('order'=>"$t.sorting"));
	}
}
