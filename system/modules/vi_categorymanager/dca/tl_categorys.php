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
 * Table tl_categorys 
 */
$GLOBALS['TL_DCA']['tl_categorys'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_categorys', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('tl_categorys', 'generateAlias'),
			//array('tl_categorys', 'generateDefaults'),
			array('tl_categorys', 'mergeCategory'),
			array('tl_categorys', 'generateNewsArchive')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('type','title'),
			'flag'                    => 12,
			'panelLayout'             => 'filter,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_categorys']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_categorys']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_categorys', 'deleteGallery')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_categorys']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_categorys', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_categorys']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'gDefaults' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_categorys']['defaults'],
				'href'                => 'vi=getDefaults',
				'icon'                => 'article.gif',
				'button_callback'     => array('tl_categorys', 'generateDefaults')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'    => array('type', 'addImage'),
		'default'         => '{title_legend},type;',
		'land'            => '{title_legend},type,title;{image_legend:hide},addImage;{config_legend},showIn;{publish_legend},published,featured,start,stop',
		'sportart'        => '{title_legend},type,title;{image_legend:hide},addImage;{config_legend},showIn,useLeaguesManager;{publish_legend},published,featured,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addImage'               => 'singleSRC'
	),

	// Fields
	'fields' => array
	(
	  'id' => array( 'sql' => "int(10) unsigned NOT NULL auto_increment" ),
		'tstamp' => array( 'sql' => "int(10) unsigned NOT NULL default '0'" ),
		'alias' => array( 'sql' => "varbinary(128) NOT NULL default ''" ),
		'pageid' => array( 'sql' => "int(10) unsigned NOT NULL default '0'" ),
		
		// Type
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['type'],
			'exclude'                 => true,
			'filter'									=> true,
			'inputType'               => 'select',
			'options'                 => array('land', 'sportart'),
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50', 'includeBlankOption'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['categorymanager']['types'],
			'sql'                     => "varchar(8) NOT NULL default ''"
		),

		// Titlelegend
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		
		/**
		 * Show In => gibt an welche Sportarten in welche Länder gehören
		 * bzw welche Sportart zu welchen Land gehört :)
		 */
		'showIn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['showIn'],
			'exclude'                 => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_categorys', 'getParents'),
			'eval'                    => array('doNotCopy'=>true, 'multiple' => true),
			'sql'                     => "longtext NOT NULL"
		),
		
		/**
		 * useLeaguesManager => gibt an ob diese Sportart im LeaguesManager genutzt werden kann
		 */
	  'useLeaguesManager' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['useLeaguesManager'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),

		// image legend
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['addImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),

    // publish legend
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_categorys']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_categorys
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_categorys extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_categorys
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'um_gallery'))
		{
			//$GLOBALS['TL_DCA']['tl_categorys']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				break;

			case 'delete':
				break;

			default:
				break;
		}
	}


	/**
	 * Autogenerate a news alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias(DataContainer $dc)
	{
		$objCategory = $this->Database->prepare("SELECT title, alias FROM tl_categorys WHERE id=?")->execute($dc->id);
	  if( $objCategory->alias )
	  {
	    return;
	  }

	  $varValue = standardize($objCategory->title);
	  $this->Database->prepare("UPDATE tl_categorys SET alias=? WHERE id=?")->execute($varValue, $dc->id);
	}
	
	
	/**
	 * Generate the default Content
	 */
	public function generateDefaults($row, $href, $label, $title, $icon, $attributes)
	{
	  if( $row['type'] == 'land' || $row['pageid'] != 0 )
	  {
	    return;
	  }
	  
	  if( $row['id'] == $this->Input->get('id') && $this->Input->get('vi') == 'getDefaults' )
	  {
	    $obj = $this->Database->prepare("SELECT * FROM tl_categorys WHERE id=?")->execute($this->Input->get('id'));
	    $objLastPage = $this->Database->execute("SELECT sorting FROM tl_page WHERE pid=3 ORDER BY sorting DESC LIMIT 1");
    	$sorting = ($objLastPage->numRows ? $objLastPage->sorting : 0) + 128;

  	  // Eintrag in tl_page erzeugen
  	  $page = $this->Database->execute("INSERT INTO tl_page (pid, sorting, tstamp, title, alias, type, pageTitle, robots, hide, published, categoryType) VALUES (3, '".$sorting."', '".time()."', '".$obj->title."', 'landstart/".$obj->alias."', 'regular', '".$obj->title." in lsBLand', 'index,follow', 1, 1, 'sportart')");
  	  $newPageId=$page->insertId;
  	  $this->Database->prepare("UPDATE tl_categorys SET pageid=? WHERE id=?")->execute($newPageId, $this->Input->get('id'));

  	  // Eintrag in tl_article (main) erzeugen
  	  $article_main = $this->Database->execute("INSERT INTO tl_article (pid, sorting, tstamp, title, alias, inColumn, cssID, published) VALUES ('".$newPageId."', 128, '".time()."', 'Hauptspalte', 'maincollum_".time()."', 'main', '".serialize(array("ContentPrimary", ""))."', 1)");
  	  $newArticleId = $article_main->insertId;

  	  // Eintrag in tl_content (main) erzeugen
  	  $content_main = $this->Database->execute("INSERT INTO tl_content (pid, sorting, tstamp, type, articleAlias, ptable) VALUES ('".$newArticleId."', 128, '".time()."', 'article', 13, 'tl_article')");

  	  // -----------  //

  	  // Eintrag in tl_article (right) erzeugen
  	  $article_right = $this->Database->execute("INSERT INTO tl_article (pid, sorting, tstamp, title, alias, inColumn, cssID, published) VALUES ('".$newPageId."', 256, '".time()."', 'Rechte Spalte', 'rightcollum_".time()."', 'right', '".serialize(array("ContentSuplementary", ""))."', 1)");
  	  $articleRightId = $article_right->insertId;

  	  // Eintrag in tl_content (right) erzeugen
  	  $content_right = $this->Database->execute("INSERT INTO tl_content (pid, sorting, tstamp, type, articleAlias, ptable) VALUES ('".$articleRightId."', 256, '".time()."', 'article', 14, 'tl_article')");
	    
	    $this->redirect($this->getReferer());
	  }
	  
	  return ($this->User->isAdmin || $this->User->hasAccess('delete', 'um_gallery')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteGallery($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'um_gallery')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_categorys::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_categorys::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish team ID "'.$intId.'"', 'tl_categorys toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_categorys SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);
	}
	
	/**
	 * Die jeweils anderen Typen bereitstellen
	 */
	public function getParents($dc)
	{
	  $objCats = $this->Database->prepare("SELECT id, title FROM tl_categorys WHERE type!=? AND id!=?")->execute($dc->activeRecord->type, $dc->id);
	  
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
	 * Fügt den aktuelle Typ dem jeweils anderen hinzu
	 */ 
	public function mergeCategory($dc)
	{
	  $showIn = deserialize($dc->activeRecord->showIn);
	  
	  if( count($showIn) < 1 || !is_array($showIn) )
	  {
	    return;
	  }
	  
	  $objCat = $this->Database->execute("SELECT id, showIn FROM tl_categorys WHERE id IN(".implode(",", $showIn).")");
	  if( !$objCat->numRows )
	  {
	    return;
	  }
	  
	  while($objCat->next())
	  {
	    $show = deserialize($objCat->showIn);
	    if( !in_array($dc->id, $show) )
	    {
	      $show[] = $dc->id;
	      $this->Database->prepare("UPDATE tl_categorys SET showIn = '".serialize($show)."' WHERE id=?")->execute($objCat->id);
	    }
	  }
	}
	
	
	/**
	 * Generiert für diese Sportart ein Newsarchiv
	 * NUR FÜR SPORTARTEN
	 */
	public function generateNewsArchive($dc)
	{
	  if( $dc->activeRecord->title == "" || $dc->activeRecord->type != "sportart" )
	  {
	    return;
	  }
	  
	  $objArchives = $this->Database->prepare("SELECT id FROM tl_news_archive WHERE sportart=?")->execute($dc->id);
	  if( $objArchives->numRows )
	  {
	    return;
	  }
	  
	  // create new archive
	  $newArchiv = $this->Database->execute("INSERT INTO tl_news_archive (tstamp, title, jumpTo, allowComments, notify, sortOrder, perPage, requireLogin, sportart) VALUES ('".time()."', '".$dc->activeRecord->title."', 12, 1, 'notify_both', 'ascending', 3, 1, '".$dc->id."')");
	  
	  // get archiv id and set the users???
	  
	}
}
