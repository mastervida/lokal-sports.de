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
 * Table tl_leagues 
 */
$GLOBALS['TL_DCA']['tl_leagues'] = array
(

	// Config
	'config' => array
	(
		'label'                       => $GLOBALS['TL_LANG']['LEAGUEMANAGER']['title'],
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'ctable'                      => array('tl_leagues_rounds'),
		'onload_callback' => array
		(
			array('tl_leagues', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('tl_leagues', 'generateAlias')
			//array('tl_league', 'fillRounds')
		),
		'ondelete_callback' => array
		(
		  array('tl_leagues', 'deleteEvents')
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
			'fields'                  => array('mode','name'),
			'flag'                    => 12,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name', 'gender'),
			'format'                  => '%s <span style="color:#b3b3b3;">[%s]</span>',
			'label_callback'          => array('tl_leagues', 'showContestTitle')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues']['edit'],
				'href'                => 'table=tl_leagues_rounds',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_leagues', 'editHeader'),
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
  			'button_callback'     => array('tl_leagues', 'copyLeagues')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_leagues', 'deleteLeagues')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_leagues', 'toggleIcon')
			),
			'feature' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues']['feature'],
				'icon'                => 'featured.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleFeatured(this,%s)"',
				'button_callback'     => array('tl_leagues', 'iconFeatured')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'gDefaults' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_categorys']['defaults'],
				'href'                => 'vi=getDefaults',
				'icon'                => 'article.gif',
				'button_callback'     => array('tl_leagues', 'generateDefaults')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
	  'default' => '{title_legend},name,shortname,sportart;'
	              .'{settings_legend},mode,gender,date_start,date_end,teams;'
	              .'{rules_legend},home_wins_points_home,home_wins_points_away,draw_points_home,draw_points_away,'
	                .'away_wins_points_home,away_wins_points_away,showLittlePoints;'
	              .'{rounds_legend},create_rounds;'
	              .'{publish_legend},published,start,stop;'
	),

	// Fields
	'fields' => array
	(
		'id' => array( 'sql' => "int(10) unsigned NOT NULL auto_increment" ),
		'tstamp' => array( 'sql' => "int(10) unsigned NOT NULL default '0'" ),
		'alias' => array( 'sql' => "varbinary(128) NOT NULL default ''" ),
		'pageid' => array( 'sql' => "int(10) unsigned NOT NULL default '0'" ),
		
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['name'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'shortname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['shortname'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>20, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(20) NOT NULL default ''",
		),
		
		'sportart' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['sportart'],
    	'exclude'                 => true,
    	'inputType'               => 'select',
    	'options_callback'        => array('tl_leagues', 'getSports'),
    	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    	'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		
		// league settings
		'mode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['mode'],
			'exclude'                 => false,
			'filter'				          => true,
			'inputType'               => 'select',
			'options'				          => array('L','T'),
			'reference'				        => &$GLOBALS['TL_LANG']['LEAGUETYPE'],
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'gender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['gender'],
			'exclude'                 => true,
			'filter'									=> true,
			'inputType'               => 'select',
			'options'                 => array('male', 'female', 'mixed'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC']['teammanager']['gender'],
			'eval'                    => array('mandatory' => true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(7) NOT NULL default ''"
		),
		'date_start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['date_start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'date_end' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['date_end'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'teams' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['teams'],
			'exclude'                 => false,
			'inputType'               => 'select',
			'options_callback'        => array('tl_leagues', 'setTeams'),
			'eval'                    => array('chosen'=>true, 'multiple' => true, 'tl_class' => 'long', 'mandatory' => true),
			'sql'                     => "longtext NOT NULL"
		),
		
		// Rules legend
		'home_wins_points_home' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['home_wins_points_home'],
			'exclude'                 => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "smallint(4) NULL default '0'"
		),
		'home_wins_points_away' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['home_wins_points_away'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "smallint(4) NULL default '0'"
		),
		'draw_points_home' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['draw_points_home'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "smallint(4) NULL default '0'"
		),
		'draw_points_away' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['draw_points_away'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "smallint(4) NULL default '0'"
		),
		'away_wins_points_home' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['away_wins_points_home'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "smallint(4) NULL default '0'"
		),
		'away_wins_points_away' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['away_wins_points_away'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "smallint(4) NULL default '0'"
		),
		'showLittlePoints' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['showLittlePoints'],
			'exclude'                 => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class' => 'long'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		
		// create rounds
		'create_rounds' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['create_round'],
			'exclude'                 => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>10, 'rgxp'=>digit),
			'sql'                     => "int(10) NOT NULL default '0'",
			'save_callback' => array
			(
				array('tl_leagues', 'save_create_rounds')
			),
			'load_callback' => array
			(
				array('tl_leagues', 'load_create_rounds')
			)
		),
		
		// publish
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''",
			'save_callback' => array
			(
				array('tl_leagues', 'checkEvents')
			),
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_leageus
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Agentur VIDA 2012
 * @author     Maik Helsing <http://www.agentur-vida.de>
 * @package    Controller
 */
class tl_leagues extends Backend
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
	 * Check permissions to edit table tl_page
	 */
	public function checkPermission()
	{ 
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->leagues) || empty($this->User->leagues))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->leagues;
		}
		
		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'leaguesp'))
		{
			$GLOBALS['TL_DCA']['tl_leagues']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array($this->Input->get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_leagues']) && in_array($this->Input->get('id'), $arrNew['tl_leagues']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT leagues, leaguesp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrLeaguesp = deserialize($objUser->leaguesp);

							if (is_array($arrLeaguesp) && in_array('create', $arrLeaguesp))
							{
								$arrLeagues = deserialize($objUser->leagues);
								$arrLeagues[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET leageus=? WHERE id=?")
											   ->execute(serialize($arrLeagues), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT leagues, leaguesp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrNewp = deserialize($objGroup->leaguesp);

							if (is_array($arrNewp) && in_array('create', $arrNewp))
							{
								$arrLeagues = deserialize($objGroup->leagues);
								$arrLeagues[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET leagues=? WHERE id=?")
											   ->execute(serialize($arrLeagues), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = $this->Input->get('id');
						$this->User->leagues = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'leaguesp')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' leauge ID "'.$this->Input->get('id').'"', 'tl_leagues checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'leaguesp'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' leagues', 'tl_leagues checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}
	
	
	/**
	 * Get all Sports
	 */
	public function getSports()
	{
	  $objSports = $this->Database->prepare("SELECT id, title FROM tl_categorys WHERE type=? AND published=?")
	                              ->execute('sportart', 1);
	                              
	  if( !$objSports->numRows )
	  {
	    $this->log('No sports defined in tl_categorys', 'tl_leagues checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
	  }
	  
	  $arrCategorys = array();
	  while($objSports->next())
	  {
	    $arrCategorys[$objSports->id] = $objSports->title;
	  }
	  
	  return $arrCategorys;
	}
	
	
	/**
	 * Show the real name of contenst
	 */
	public function showContestTitle($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false)
	{
	  $timespace = date('Y', $row['date_start']).'/'.date('Y', $row['date_end']);
	  
	  if( date('Y', $row['date_start']) == date('Y', $row['date_end']) )
	  {
	    if( date('mY', $row['date_start']) == date('mY', $row['date_end']) )
  	  {
  	    $timespace = date('Y', $row['date_start']);
  	  }
  	  else
  	  {
  	    $timespace = date('m.Y', $row['date_start']).'/'.date('m.Y', $row['date_end']);
  	  }
	  }
	  
	  // Sportart holen
	  $objCategory = $this->Database->prepare("SELECT title FROM tl_categorys WHERE id=?")->execute($row['sportart']);
	  
	  // return the new label
		return str_replace($row['name'], $objCategory->title.': '.$row['name'].' '.$timespace.' ', $label);
	}
	
	
	/**
	 * Auto-generate a page alias if it has not been set yet
	 * @param mixed
	 * @param DataContainer
	 * @return string
	 */
	public function generateAlias(DataContainer $dc)
	{
	  $objContest = $this->Database->prepare("SELECT * FROM tl_leagues WHERE id=?")->execute($dc->id);
	  if( $objContest->alias )
	  {
	    return;
	  }
	  
	  // sinnvollen Zeitraum generieren
	  $timespace = date('Y', $objContest->date_start).'-'.date('Y', $objContest->date_end);
	  if( date('Y', $objContest->date_start) == date('Y', $objContest->date_end) )
	  {
	    if( date('mY', $objContest->date_start) == date('mY', $objContest->date_end) )
  	  {
  	    $timespace = date('Y', $objContest->date_start);
  	  }
  	  else
  	  {
  	    $timespace = date('m-Y', $objContest->date_start).'-'.date('m-Y', $objContest->date_end);
  	  }
	  }
	  
	  // Sportart holen
	  $objCategory = $this->Database->prepare("SELECT alias FROM tl_categorys WHERE id=?")->execute($objContest->sportart);
	  
	  $varValue = $objCategory->alias.'/'
	              .standardize($objContest->name).'-'
	              .$GLOBALS['TL_LANG']['LEAGUEMANAGER']['alias'][$objContest->gender].'-'
	              .$timespace.'_'.$objContest->id;
	  
	  $this->Database->prepare("UPDATE tl_leagues SET alias=? WHERE id=?")->execute($varValue, $dc->id);
	}
	
	
	/**
	 * Generate the default Content
	 */
	public function generateDefaults($row, $href, $label, $title, $icon, $attributes)
	{
	  if( $row['pageid'] != 0 )
	  {
	    return;
	  }
	  
	  if( $row['id'] == $this->Input->get('id') && $this->Input->get('vi') == 'getDefaults' )
	  {
	    $timespace = date('Y', $row['date_start']).'/'.date('Y', $row['date_end']);

  	  if( date('Y', $row['date_start']) == date('Y', $row['date_end']) )
  	  {
  	    if( date('mY', $row['date_start']) == date('mY', $row['date_end']) )
    	  {
    	    $timespace = date('Y', $row['date_start']);
    	  }
    	  else
    	  {
    	    $timespace = date('m.Y', $row['date_start']).'/'.date('m.Y', $row['date_end']);
    	  }
  	  }
  	  
	    $obj = $this->Database->prepare("SELECT * FROM tl_leagues WHERE id=?")->execute($this->Input->get('id'));
    	  
      $objPid = $this->Database->prepare("SELECT title,alias,pageid FROM tl_categorys WHERE id=?")->execute($obj->sportart);
      $objLastPage = $this->Database->prepare("SELECT p.sorting FROM tl_page AS p LEFT JOIN tl_categorys AS c ON p.pid = c.pageid WHERE c.id=?")->execute($obj->sportart);
  	  $sorting = ($objLastPage->numRows ? $objLastPage->sorting : 0) + 128;

  	  // Eintrag in tl_page erzeugen
  	  $page = $this->Database->execute("INSERT INTO tl_page (pid, sorting, tstamp, title, alias, type, pageTitle, robots, hide, published, categoryType) VALUES ('".$objPid->pageid."', '".$sorting."', '".time()."', '".$obj->name.' '.$timespace."', 'landstart/".$objPid->alias.'/'.$obj->alias."', 'regular', '".$objPid->title.': '.$obj->name.' '.$timespace."', 'index,follow', 1, 1, 'sportart')");
  	  $newPageId=$page->insertId;
  	  $this->Database->prepare("UPDATE tl_leagues SET pageid=? WHERE id=?")->execute($newPageId, $this->Input->get('id'));

  	  // Eintrag in tl_article (main) erzeugen
  	  $article_main = $this->Database->execute("INSERT INTO tl_article (pid, sorting, tstamp, title, alias, inColumn, cssID, published) VALUES ('".$newPageId."', 128, '".time()."', 'Hauptspalte', 'maincollum_".time()."', 'main', '".serialize(array("ContentPrimary", ""))."', 1)");
  	  $newArticleId = $article_main->insertId;

  	  // Eintrag in tl_content (main) erzeugen
  	  $content_main = $this->Database->execute("INSERT INTO tl_content (pid, sorting, tstamp, type, articleAlias, ptable) VALUES ('".$newArticleId."', 128, '".time()."', 'article', 20, 'tl_article')");

  	  // -----------  //

  	  // Eintrag in tl_article (right) erzeugen
  	  $article_right = $this->Database->execute("INSERT INTO tl_article (pid, sorting, tstamp, title, alias, inColumn, cssID, published) VALUES ('".$newPageId."', 256, '".time()."', 'Rechte Spalte', 'rightcollum_".time()."', 'right', '".serialize(array("ContentSuplementary", ""))."', 1)");
  	  $articleRightId = $article_right->insertId;

  	  // Eintrag in tl_content (right) erzeugen
  	  $content_right = $this->Database->execute("INSERT INTO tl_content (pid, sorting, tstamp, type, articleAlias, ptable) VALUES ('".$articleRightId."', 256, '".time()."', 'article', 21, 'tl_article')");
	    
	    $this->redirect($this->getReferer());
	  }
	  
	  return ($this->User->isAdmin || $this->User->hasAccess('delete', 'um_gallery')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	

	/**
	 * Return the delete page button
	 */
	public function deleteLeagues($row, $href, $label, $title, $icon, $attributes)
	{
    return ($this->User->isAdmin || $this->User->hasAccess('delete', 'leaguesp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	
	/**
	 * Return copy-button
	 */
	public function copyLeagues($row, $href, $label, $title, $icon, $attributes)
	{
    return ($this->User->isAdmin || ($this->User->hasAccess('create', 'leaguesp') && in_array($row['id'], $this->User->leagues) ) ) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	
	
	/**
	 * EditHeader
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
	
	
	/**
	 * Futter für den TeamWizard
	 */
	public function setTeams()
	{
		$teams = array();
		
		$objTeams = $this->Database->execute("SELECT id, title FROM tl_teams ORDER BY title");
		
		if( !$objTeams->numRows )
		{
			return array();
		}
		
		while($objTeams->next())
		{
			$teams[$objTeams->id] = $objTeams->title;
		}
		
		return $teams;
	}
	
	/**
	 * Load generated Rounds
	 */
	public function load_create_rounds()
	{
		return "0";
	}
	
	public function save_create_rounds($varValue, DataContainer $dc)
	{
		switch ($dc->activeRecord->mode)
		{
			case "T":
				$name = $GLOBALS['TL_LANG']['tl_leagues']['create_rounds']['title_tournament'];
				break;
			case "L":
				$name = $GLOBALS['TL_LANG']['tl_leagues']['create_rounds']['title_league'];
				break;
			default:
				$name = $GLOBALS['TL_LANG']['tl_leagues']['create_rounds']['title_tournament'];
		}
		
		$round_count = array();
		$max_round = array();
		$round_count = $this->Database->prepare("SELECT count(id) as cnt FROM tl_leagues_rounds WHERE pid=?")->execute($dc->activeRecord->id);
		$rnd_cnt = $round_count->cnt;
		$max_round = $this->Database->prepare("SELECT max(round_no) as rnd FROM tl_leagues_rounds WHERE pid=?")->execute($dc->activeRecord->id);
		$max_rnd = $max_round->rnd;
		if($varValue>0)
		{
			for($i=1;$i<=$varValue;$i++)
			{
				$ret=$this->Database->prepare("INSERT INTO tl_leagues_rounds (name,round_no,pid,contestname) VALUES (?,?,?,?)")->execute($rnd_cnt+$i . " . " . $name,$max_rnd+$i,$dc->activeRecord->id, $dc->activeRecord->name);
			}
		}
		
		$dc->activeRecord->create_rounds=0;
		return "0";
	}
	
	/**
	 * Return the "toggle visibility" button
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin)
		{
		  if(!$this->User->hasAccess('tl_leagues::published', 'alexf') || !in_array($row['id'], $this->User->leagues))
		  {
		    return '';
		  }
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_leagues::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish leauge ID "'.$intId.'"', 'tl_leagues toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_leagues SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);
		
		$this->checkEvents($blnVisible, $intId, true);
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_leagues::featured', 'alexf'))
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_leagues::featured', 'alexf'))
		{
			$this->log('Not enough permissions to feature/unfeature league item ID "'.$intId.'"', 'tl_leagues toggleFeatured', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_leagues']['fields']['featured']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_leagues']['fields']['featured']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_leagues SET tstamp=". time() .", featured='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);
	}
	
	// check events publish/unpublish
	public function checkEvents($varValue, $dc, $todo = false)
	{
	  $id = (!$todo ? $dc->activeRecord->id : $dc);
	  
	  // getAllMatchDays
	  $objMatchDays = $this->Database->prepare("SELECT id FROM tl_leagues_rounds WHERE pid=?")->execute($id);
	                                
	  if( !$objMatchDays->numRows )
	  {
	    return false;
	  }
	  
	  while($objMatchDays->next())
	  {
	    $this->Database->prepare("UPDATE tl_calendar_events SET published=? WHERE matchday_id=?")
	                   ->execute($varValue, $objMatchDays->id);
	  }
	}
	
	
	// delete event from calendar
	public function deleteEvents()
	{
	  if( !$this->Input->get('id') || !is_numeric($this->Input->get('id')) )
	  {
	    return;
	  }
	  
	  // getAllMatchDays
	  $objMatchDays = $this->Database->prepare("SELECT id FROM tl_leagues_rounds WHERE pid=?")
	                                  ->execute($this->Input->get('id'));
	                                
	  if( !$objMatchDays->numRows )
	  {
	    return false;
	  }
	  
	  while($objMatchDays->next())
	  {
	    $this->Database->prepare("DELETE FROM tl_calendar_events WHERE matchday_id=?")->execute($objMatchDays->id);
	  }
	}
}
