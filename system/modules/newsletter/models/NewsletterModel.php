<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Newsletter
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes newsletters
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class NewsletterModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_newsletter';


	/**
	 * Find sent newsletters by their parent IDs and their ID or alias
	 * 
	 * @param integer $varId   The numeric ID or alias name
	 * @param array   $arrPids An array of newsletter channel IDs
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no sent newsletters
	 */
	public static function findSentByParentAndIdOrAlias($varId, $arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.sent=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId));
	}


	/**
	 * Find sent newsletters by their parent ID
	 * 
	 * @param integer $intPid The newsletter channel ID
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no sent newsletters
	 */
	public static function findSentByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.sent=1";
		}

		return static::findBy($arrColumns, $intPid, array('order'=>"$t.date DESC"));
	}


	/**
	 * Find sent newsletters by multiple parent IDs
	 * 
	 * @param array $arrPids An array of newsletter channel IDs
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no sent newsletters
	 */
	public static function findSentByPids($arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.sent=1";
		}

		return static::findBy($arrColumns, null, array('order'=>"$t.date DESC"));
	}
}
