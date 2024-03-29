<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Comments
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes comments
 * 
 * @package   Comments
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class CommentsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_comments';


	/**
	 * Find published comments by their source table and parent ID
	 * 
	 * @param string  $strSource The source element
	 * @param integer $intParent The parent ID
	 * @param boolean $blnDesc   If true, comments will be sorted descending
	 * @param integer $intLimit  An optional limit
	 * @param integer $intOffset An optional offset
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no comments
	 */
	public static function findPublishedBySourceAndParent($strSource, $intParent, $blnDesc=false, $intLimit=0, $intOffset=0)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.source=? AND $t.parent=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		$arrOptions = array
		(
			'order'  => ($blnDesc ? "$t.date DESC" : "$t.date"),
			'limit'  => $intLimit,
			'offset' => $intOffset
		);

		return static::findBy($arrColumns, array($strSource, $intParent), $arrOptions);
	}


	/**
	 * Count published comments by their source table and parent ID
	 * 
	 * @param string  $strSource The source element
	 * @param integer $intParent The parent ID
	 * 
	 * @return integer The number of comments
	 */
	public static function countPublishedBySourceAndParent($strSource, $intParent)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.source=? AND $t.parent=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		return static::countBy($arrColumns, array($strSource, $intParent));
	}
}
