<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Faq
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes FAQs
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class FaqModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_faq';


	/**
	 * Find a published FAQ from one or more categories by its ID or alias
	 * 
	 * @param mixed $varId   The numeric ID or alias name
	 * @param array $arrPids An array of parent IDs
	 * 
	 * @return \Model|null The FaqModel or null if there is no FAQ
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		return static::findOneBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId));
	}


	/**
	 * Find all published FAQs by their parent IDs
	 * 
	 * @param array $arrPids An array of FAQ category IDs
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no FAQs
	 */
	public static function findPublishedByPids($arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")"), null, array('order'=>"$t.pid, $t.sorting"));
	}
}
