<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Creates and queries the search index
 * 
 * The class takes the HTML markup of a page, exctracts the content and writes
 * it to the database (search index). It also provides a method to query the
 * seach index, returning the matching entries.
 * 
 * Usage:
 * 
 *     Search::indexPage($objPage->row());
 *     $result = Search::searchFor('keyword');
 * 
 *     while ($result->next())
 *     {
 *         echo $result->url;
 *     }
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class Search extends \System
{

	/**
	 * Object instance (Singleton)
	 * @var \Search
	 */
	protected static $objInstance;


	/**
	 * Index a page
	 * 
	 * @param array $arrData The data array
	 * 
	 * @return boolean True if a new record was created
	 */
	public static function indexPage($arrData)
	{
		$objDatabase = \Database::getInstance();

		$arrSet['url'] = $arrData['url'];
		$arrSet['title'] = $arrData['title'];
		$arrSet['protected'] = $arrData['protected'];
		$arrSet['filesize'] = $arrData['filesize'];
		$arrSet['groups'] = $arrData['groups'];
		$arrSet['pid'] = $arrData['pid'];
		$arrSet['language'] = $arrData['language'];

		// Get filesize from raw content
		if (!$arrSet['filesize'])
		{
			$arrSet['filesize'] = number_format((strlen($arrData['content']) / 1024 ), 2, '.', '');
		}

		// Replace special characters
		$strContent = str_replace(array("\n", "\r", "\t", '&#160;', '&nbsp;'), ' ', $arrData['content']);

		$arrOuter = array();
		$arrInner = array();

		// Strip JavaScript (thanks to Pieter Dreef)
		while (preg_match('/<script[^>]*>/i', $strContent, $arrOuter, PREG_OFFSET_CAPTURE))
		{
			if (!preg_match('/<\/script>/i', $strContent, $arrInner, PREG_OFFSET_CAPTURE, (strlen($arrOuter[0][0]) + $arrOuter[0][1])))
			{
				break;
			}

			$strContent = substr($strContent, 0, $arrOuter[0][1]) . substr($strContent, (strlen($arrInner[0][0]) + $arrInner[0][1]));
		}

		// Strip non-indexable areas (thanks to Pieter Dreef)
		while (preg_match('/<!-- indexer::stop -->/', $strContent, $arrOuter, PREG_OFFSET_CAPTURE))
		{
			if (!preg_match('/<!-- indexer::continue -->/', $strContent, $arrInner, PREG_OFFSET_CAPTURE, (strlen($arrOuter[0][0]) + $arrOuter[0][1])))
			{
				break;
			}

			$strContent = substr($strContent, 0, $arrOuter[0][1]) . substr($strContent, (strlen($arrInner[0][0]) + $arrInner[0][1]));
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['indexPage']) && is_array($GLOBALS['TL_HOOKS']['indexPage']))
		{
			foreach ($GLOBALS['TL_HOOKS']['indexPage'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($strContent, $arrData, $arrSet);
			}
		}

		// Free the memory
		unset($arrData['content']);

		// Calculate the checksum (see #4179)
		$arrSet['checksum'] = md5(preg_replace('/ +/', ' ', strip_tags($strContent)));

		// Return if the page is indexed and up to date
		$objIndex = $objDatabase->prepare("SELECT id, checksum FROM tl_search WHERE url=? AND pid=?")
								->limit(1)
								->execute($arrSet['url'], $arrSet['pid']);

		if ($objIndex->numRows && $objIndex->checksum == $arrSet['checksum'])
		{
			return false;
		}

		$arrMatches = array();
		preg_match('/<\/head>/', $strContent, $arrMatches, PREG_OFFSET_CAPTURE);
		$intOffset = strlen($arrMatches[0][0]) + $arrMatches[0][1];

		// Split page in head and body section
		$strHead = substr($strContent, 0, $intOffset);
		$strBody = substr($strContent, $intOffset);

		unset($strContent);
		$tags = array();

		// Get description
		if (preg_match('/<meta[^>]+name="description"[^>]+content="([^"]*)"[^>]*>/i', $strHead, $tags))
		{
			$arrData['description'] = trim(preg_replace('/ +/', ' ', \String::decodeEntities($tags[1])));
		}

		// Get keywords
		if (preg_match('/<meta[^>]+name="keywords"[^>]+content="([^"]*)"[^>]*>/i', $strHead, $tags))
		{
			$arrData['keywords'] = trim(preg_replace('/ +/', ' ', \String::decodeEntities($tags[1])));
		}

		// Read title and alt attributes
		if (preg_match_all('/<* (title|alt)="([^"]*)"[^>]*>/i', $strBody, $tags))
		{
			$arrData['keywords'] .= ' ' . implode(', ', array_unique($tags[2]));
		}

		// Make sure <br /> tags are always followed by a line break
		$strBody = str_ireplace(array('<br>', '<br />'), "<br>\n", $strBody);
		$strBody = strip_tags($strBody);

		// Put everything together
		$arrSet['text'] = $arrData['title'] . ' ' . $arrData['description'] . ' ' . $strBody . ' ' . $arrData['keywords'];
		$arrSet['text'] = trim(preg_replace('/ +/', ' ', \String::decodeEntities($arrSet['text'])));

		$arrSet['tstamp'] = time();

		// Update an existing old entry
		if ($objIndex->numRows)
		{
			$objDatabase->prepare("UPDATE tl_search %s WHERE id=?")
						->set($arrSet)
						->execute($objIndex->id);

			$intInsertId = $objIndex->id;
		}

		// Add a new entry
		else
		{
			// Check for a duplicate record with the same checksum
			$objDuplicates = $objDatabase->prepare("SELECT id, url FROM tl_search WHERE pid=? AND checksum=?")
										 ->limit(1)
										 ->execute($arrSet['pid'], $arrSet['checksum']);

			// Keep the existing record
			if ($objDuplicates->numRows)
			{
				// Update the URL if the new URL is shorter
				if (substr_count($arrSet['url'], '/') < substr_count($objDuplicates->url, '/') || preg_match('/page=[0-9]*$/', $objDuplicates->url))
				{
					$objDatabase->prepare("UPDATE tl_search SET url=? WHERE id=?")
								->execute($arrSet['url'], $objDuplicates->id);
				}

				return false;
			}

			// Insert the new record if there is no duplicate
			else
			{
				$objInsertStmt = $objDatabase->prepare("INSERT INTO tl_search %s")
											 ->set($arrSet)
											 ->execute();

				$intInsertId = $objInsertStmt->insertId;
			}
		}

		// Remove quotes
		$strText = str_replace(array('´', '`'), "'", $arrSet['text']);
		unset($arrSet);

		// Remove special characters
		if (function_exists('mb_eregi_replace'))
		{
			$strText = mb_eregi_replace('[^[:alnum:]\'\.:,_-]|- | -|\' | \'|\. |\.$|: |:$|, |,$', ' ', $strText);
		}
		else
		{
			$strText = preg_replace(array('/- /', '/ -/', "/' /", "/ '/", '/\. /', '/\.$/', '/: /', '/:$/', '/, /', '/,$/', '/[^\pN\pL\'\.:,_-]/u'), ' ', $strText);
		}

		// Split words
		$arrWords = preg_split('/ +/', utf8_strtolower($strText));
		$arrIndex = array();

		// Index words
		foreach ($arrWords as $strWord)
		{
			$strWord = trim($strWord);

			if (!strlen($strWord) || preg_match('/^[\.:,\'_-]+$/', $strWord))
			{
				continue;
			}

			if (preg_match('/^[\':,]/', $strWord))
			{
				$strWord = substr($strWord, 1);
			}

			if (preg_match('/[\':,\.]$/', $strWord))
			{
				$strWord = substr($strWord, 0, -1);
			}

			if (isset($arrIndex[$strWord]))
			{
				$arrIndex[$strWord]++;
				continue;
			}

			$arrIndex[$strWord] = 1;
		}

		// Remove existing index
		$objDatabase->prepare("DELETE FROM tl_search_index WHERE pid=?")
					->execute($intInsertId);

		// Create new index
		$arrKeys = array();
		$arrValues = array();

		foreach ($arrIndex as $k=>$v)
		{
			$arrKeys[] = "(?, ?, ?, ?)";
			$arrValues[] = $intInsertId;
			$arrValues[] = $k;
			$arrValues[] = $v;
			$arrValues[] = $arrData['language'];
		}

		// Insert values
		$objDatabase->prepare("INSERT INTO tl_search_index (pid, word, relevance, language) VALUES " . implode(", ", $arrKeys))
					->execute($arrValues);

		return true;
	}


	/**
	 * Search the index and return the result object
	 * 
	 * @param string  $strKeywords The keyword string
	 * @param boolean $blnOrSearch If true, the result can contain any keyword
	 * @param array   $arrPid      An optional array of page IDs to limit the result to
	 * @param integer $intRows     An optional maximum number of result rows
	 * @param integer $intOffset   An optional result offset
	 * @param boolean $blnFuzzy    If true, the search will be fuzzy
	 * 
	 * @return \Database\Result The database result object
	 * 
	 * @throws \Exception If the cleaned keyword string is empty
	 */
	public static function searchFor($strKeywords, $blnOrSearch=false, $arrPid=array(), $intRows=0, $intOffset=0, $blnFuzzy=false)
	{
		// Clean the keywords
		$strKeywords = utf8_strtolower($strKeywords);
		$strKeywords = \String::decodeEntities($strKeywords);

		if (function_exists('mb_eregi_replace'))
		{
			$strKeywords = mb_eregi_replace('[^[:alnum:] \*\+\'"\.:,_-]|\. |\.$|: |:$|, |,$', ' ', $strKeywords);
		}
		else
		{
			$strKeywords = preg_replace(array('/\. /', '/\.$/', '/: /', '/:$/', '/, /', '/,$/', '/[^\pN\pL \*\+\'"\.:,_-]/u'), ' ', $strKeywords);
		}

		// Check keyword string
		if (!strlen($strKeywords))
		{
			throw new \Exception('Empty keyword string');
		}

		// Split keywords
		$arrChunks = array();
		preg_match_all('/"[^"]+"|[\+\-]?[^ ]+\*?/', $strKeywords, $arrChunks);

		$arrPhrases = array();
		$arrKeywords = array();
		$arrWildcards = array();
		$arrIncluded = array();
		$arrExcluded = array();

		foreach ($arrChunks[0] as $strKeyword)
		{
			if (substr($strKeyword, -1) == '*' && strlen($strKeyword) > 1)
			{
				$arrWildcards[] = str_replace('*', '%', $strKeyword);
				continue;
			}

			switch (substr($strKeyword, 0, 1))
			{
				// Phrases
				case '"':
					if (($strKeyword = trim(substr($strKeyword, 1, -1))) != false)
					{
						$arrPhrases[] = '[[:<:]]' . str_replace(array(' ', '*'), array('[^[:alnum:]]+', ''), $strKeyword) . '[[:>:]]';
					}
					break;

				// Included keywords
				case '+':
					if (($strKeyword = trim(substr($strKeyword, 1))) != false)
					{
						$arrIncluded[] = $strKeyword;
					}
					break;

				// Excluded keywords
				case '-':
					if (($strKeyword = trim(substr($strKeyword, 1))) != false)
					{
						$arrExcluded[] = $strKeyword;
					}
					break;

				// Wildcards
				case '*':
					if (strlen($strKeyword) > 1)
					{
						$arrWildcards[] = str_replace('*', '%', $strKeyword);
					}
					break;

				// Normal keywords
				default:
					$arrKeywords[] = $strKeyword;
					break;
			}
		}

		// Fuzzy search
		if ($blnFuzzy)
		{
			foreach ($arrKeywords as $strKeyword)
			{
				$arrWildcards[] = '%' . $strKeyword . '%';
			}

			$arrKeywords = array();
		}

		// Count keywords
		$intPhrases = count($arrPhrases);
		$intWildcards = count($arrWildcards);
		$intIncluded = count($arrIncluded);
		$intExcluded = count($arrExcluded);

		$intKeywords = 0;
		$arrValues = array();

		// Remember found words so we can highlight them later
		$strQuery = "SELECT tl_search_index.pid AS sid, GROUP_CONCAT(word) AS matches";

		// Get the number of wildcard matches
		if (!$blnOrSearch && $intWildcards)
		{
			$strQuery .= ", (SELECT COUNT(*) FROM tl_search_index WHERE (" . implode(' OR ', array_fill(0, $intWildcards, 'word LIKE ?')) . ") AND pid=sid) AS wildcards";
			$arrValues = array_merge($arrValues, $arrWildcards);
		}

		// Count the number of matches
		$strQuery .= ", COUNT(*) AS count";

		// Get the relevance
		$strQuery .= ", SUM(relevance) AS relevance";

		// Get meta information from tl_search
		$strQuery .= ", tl_search.*"; // see #4506

		// Prepare keywords array
		$arrAllKeywords = array();

		// Get keywords
		if (!empty($arrKeywords))
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, count($arrKeywords), 'word=?'));
			$arrValues = array_merge($arrValues, $arrKeywords);
			$intKeywords += count($arrKeywords);
		}

		// Get included keywords
		if ($intIncluded)
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, $intIncluded, 'word=?'));
			$arrValues = array_merge($arrValues, $arrIncluded);
			$intKeywords += $intIncluded;
		}

		// Get keywords from phrases
		if ($intPhrases)
		{
			foreach ($arrPhrases as $strPhrase)
			{
				$arrWords = explode('[^[:alnum:]]+', utf8_substr($strPhrase, 7, -7));
				$arrAllKeywords[] = implode(' OR ', array_fill(0, count($arrWords), 'word=?'));
				$arrValues = array_merge($arrValues, $arrWords);
				$intKeywords += count($arrWords);
			}
		}

		// Get wildcards
		if ($intWildcards)
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, $intWildcards, 'word LIKE ?'));
			$arrValues = array_merge($arrValues, $arrWildcards);
		}

		$strQuery .= " FROM tl_search_index LEFT JOIN tl_search ON(tl_search_index.pid=tl_search.id) WHERE (" . implode(' OR ', $arrAllKeywords) . ")";

		// Get phrases
		if ($intPhrases)
		{
			$strQuery .= " AND (" . implode(($blnOrSearch ? ' OR ' : ' AND '), array_fill(0, $intPhrases, 'tl_search_index.pid IN(SELECT id FROM tl_search WHERE text REGEXP ?)')) . ")";
			$arrValues = array_merge($arrValues, $arrPhrases);
		}

		// Include keywords
		if ($intIncluded)
		{
			$strQuery .= " AND tl_search_index.pid IN(SELECT pid FROM tl_search_index WHERE " . implode(' OR ', array_fill(0, $intIncluded, 'word=?')) . ")";
			$arrValues = array_merge($arrValues, $arrIncluded);
		}

		// Exclude keywords
		if ($intExcluded)
		{
			$strQuery .= " AND tl_search_index.pid NOT IN(SELECT pid FROM tl_search_index WHERE " . implode(' OR ', array_fill(0, $intExcluded, 'word=?')) . ")";
			$arrValues = array_merge($arrValues, $arrExcluded);
		}

		// Limit results to a particular set of pages
		if (is_array($arrPid) && !empty($arrPid))
		{
			$strQuery .= " AND tl_search_index.pid IN(SELECT id FROM tl_search WHERE pid IN(" . implode(',', array_map('intval', $arrPid)) . "))";
		}

		$strQuery .= " GROUP BY tl_search_index.pid";

		// Make sure to find all words
		if (!$blnOrSearch)
		{
			// Number of keywords without wildcards
			$strQuery .= " HAVING count >= " . $intKeywords;

			// Dynamically add the number of wildcard matches
			if ($intWildcards)
			{
				$strQuery .= " + IF(wildcards>" . $intWildcards . ", wildcards, " . $intWildcards . ")";
			}
		}

		// Sort by relevance
		$strQuery .= " ORDER BY relevance DESC";

		// Return result
		$objResultStmt = \Database::getInstance()->prepare($strQuery);

		if ($intRows > 0)
		{
			$objResultStmt->limit($intRows, $intOffset);
		}

		return $objResultStmt->execute($arrValues);
	}


	/**
	 * Remove an entry from the search index
	 * 
	 * @param string $strUrl The URL to be removed
	 */
	public static function removeEntry($strUrl)
	{
		$objDatabase = \Database::getInstance();

		$objSearch = $objDatabase->prepare("SELECT * FROM tl_search WHERE url=?")
								 ->limit(1)
								 ->execute($strUrl);

		if ($objSearch->numRows)
		{
			$objDatabase->prepare("DELETE FROM tl_search WHERE id=?")
						->execute($objSearch->id);

			$objDatabase->prepare("DELETE FROM tl_search_index WHERE pid=?")
						->execute($objSearch->id);
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 * 
	 * @deprecated Search is now a static class
	 */
	final public function __clone() {}


	/**
	 * Return the object instance (Singleton)
	 * 
	 * @return \Search The object instance
	 * 
	 * @deprecated Search is now a static class
	 */
	public static function getInstance()
	{
		if (!is_object(static::$objInstance))
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}
}
