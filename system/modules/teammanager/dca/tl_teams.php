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
 * Table tl_teams 
 */
$GLOBALS['TL_DCA']['tl_teams'] = array
(

	// Config
	'config' => array
	(
	  'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_teams', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('tl_teams', 'generateAlias'),
			//array('tl_teams', 'setCoordinates')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'gender' => 'index',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('city','title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;limit'
		),
		'label' => array
		(
			'fields'                  => array('gender', 'title'),
			'format'                  => '<span style="color:#b3b3b3; width:55px; float:left;">[%s]</span> %s'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_teams']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_teams']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_teams', 'deleteTeam')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_teams']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_teams', 'toggleIcon')
			),
			'feature' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_teams']['feature'],
				'icon'                => 'featured.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleFeatured(this,%s)"',
				'button_callback'     => array('tl_teams', 'iconFeatured')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_teams']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addImage'),
		'default'                     => '{title_legend},title,shortname,gender,sportart;{adress_legend},street,number,postal,city,coordinates;{contact_legend:hide},contact,phone,email,website;{photo_legend:hide},addImage;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addImage'               => 'singleSRC,caption'
	),

	// Fields
	'fields' => array
	(
		'id' => array( 'sql' => "int(10) unsigned NOT NULL auto_increment" ),
		'tstamp' => array( 'sql' => "int(10) unsigned NOT NULL default '0'" ),
		'alias' => array( 'sql' => "varbinary(128) NOT NULL default ''" ),
		
		// Titlelegend
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'shortname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['shortname'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>50, 'tl_class' => 'w50'),
			'sql'                     => "varchar(50) NOT NULL default ''"
		),
		'gender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['gender'],
			'exclude'                 => true,
			'filter'										=> true,
			'inputType'               => 'select',
			'options'                 => array('male', 'female'), //, 'mixed'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_teams']['gender'],
			'eval'                    => array('includeBlankOption'=>true, 'mandatory' => true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(7) NOT NULL default ''"
		),
		'sportart' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['sportart'],
			'exclude'                 => true,
			'filter'									=> true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_teams', 'getSports'),
			'eval'                    => array('includeBlankOption'=>true, 'mandatory' => true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		
		// Adresslegend
		'street' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['street'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'number' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['number'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>15, 'tl_class'=>'w50'),
			'sql'                     => "varchar(15) NOT NULL default ''"
		),
		'postal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['postal'],
			'exclude'                 => true,
			'search'									=> true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'maxlength' => 6, 'tl_class' => 'w50', 'mandatory' => true),
			'sql'                     => "char(6) NOT NULL default ''"
		),
		'city' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['city'],
			'exclude'                 => true,
			'filter'									=> true,
			'search'                  => true,
			'sorting'								  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50', 'mandatory' => true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'coordinates' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['coordinates'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'										=> array('tl_class' => 'tl_long'),
			'sql'                     => "varchar(255) NOT NULL default ''",
			'save_callback' => array
			(
				array('tl_teams', 'setCoordinates')
			)
		),
		
		// contactlegend
		'contact' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['contact'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>150, 'tl_class' => 'w50'),
			'sql'                     => "varchar(150) NOT NULL default ''"
		),
		'phone' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['phone'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'phone', 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['email'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'website' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['website'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		
		// Photolegend
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['addImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true, 'path' => 'files/teams'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'caption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['caption'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;', 'allowHtml'=>true),
			'sql'                     => "text NULL"
		),
		
		// THINK ABOUT IT
		/*
		'showIn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['showIn'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_teams', 'getStates'),
			'eval'                    => array('doNotCopy'=>true, 'multiple' => true, 'mandatory' => true, 'tl_class' => 'w50'),
			'sql'                     => "longtext NOT NULL"
		),
		*/
		
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class' => 'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'featured' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_teams']['featured'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class' => 'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);

/**
 * Class tl_teams
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_teams extends Backend
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
	 * Check permissions to edit table tl_teams
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'teams'))
		{
			$GLOBALS['TL_DCA']['tl_teams']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				// Allow
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
		$gender = array('male' => 'herren', 'female' => 'damen');
		$objTeam = $this->Database->prepare("SELECT title, alias, gender FROM tl_teams WHERE id=?")->execute($dc->id);
		
		if( $objTeam->alias )
		{
		  return;
		}
		
		// generate Alias
		$varValue = $gender[$objTeam->gender].'/'.standardize($objTeam->title).'_'.$dc->id;
		
		// check Alias
		$objCheck = $this->Database->prepare("SELECT count(id) FROM tl_teams WHERE alias=? AND id!=?")
		                           ->execute($varValue, $dc->id);
		
		if( $objCheck->numRows )
		{
		  $varValue .= '_'.$dc->id;
		}
		
		$this->Database->prepeare("UPDATE tl_teams SET alias=? WHERE id=?")->execute($varValue, $dc->id);
	}
	


	/**
	 * Check for Address & generate MapsCoordinates
	 */
	public function setCoordinates($varValue, DataContainer $dc)
	{
		$location = false;
		$location = $dc->activeRecord->postal.",".$dc->activeRecord->city.($dc->activeRecord->street ? ",".$dc->activeRecord->street : '').($dc->activeRecord->number ? ",".$dc->activeRecord->number : '');
		$location = str_replace(",", "+", $location);
		
		// API - URL
		$import_url = 'http://maps.google.com/maps/geo?output=xml&q='.urlencode($location);
		
		// Import starten
		$url_start			= @file_get_contents($import_url);
		$coo_start		= strpos($url_start, "<Point>");
		$coo_end			= strpos($url_start, "</Point>");
		$coordinates		= substr($url_start, $coo_start, ($coo_end-$coo_start));
		
		$arrSearch = array
		(
			"<Point>", "</Point>",
			"<coordinates>", "</coordinates>"
		);
		
		$arrReplace = array('', '', '', '');
		$coordinates = str_replace($arrSearch, $arrReplace, trim($coordinates));
		
		$arrCoordinates = explode(",", $coordinates);
		if( is_array($arrCoordinates) )
		{
			$coordinates = $arrCoordinates[0] .','. $arrCoordinates[1];
			return $coordinates;
		}
		
		return $dc->activeRecord->coordinates;
	}
	
	
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
	public function deleteTeam($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'newp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	
	
	/**
	 * Return the "feature/unfeature element" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function iconFeatured($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('fid')))
		{
			$this->toggleFeatured(Input::get('fid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the fid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_teams::featured', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;fid='.$row['id'].'&amp;state='.($row['featured'] ? '' : 1);

		if (!$row['featured'])
		{
			$icon = 'featured_.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
	
	
	/**
	 * Feature/unfeature a news item
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	public function toggleFeatured($intId, $blnVisible)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'feature');
		$this->checkPermission();

		// Check permissions to feature
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_teams::featured', 'alexf'))
		{
			$this->log('Not enough permissions to feature/unfeature team item ID "'.$intId.'"', 'tl_teams toggleFeatured', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_teams']['fields']['featured']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_teams']['fields']['featured']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_teams SET tstamp=". time() .", featured='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_teams::published', 'alexf'))
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_teams::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish team ID "'.$intId.'"', 'tl_teams toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_teams SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_teams', $intId);
	}
}