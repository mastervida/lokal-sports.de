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
 * Table tl_leagues_matches 
 */
$GLOBALS['TL_DCA']['tl_leagues_matches'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_leagues_rounds',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_leagues_matches', 'checkPermission'),
			array('tl_leagues_matches', 'checkSportsType')
		),
		'onsubmit_callback' => array
		(
		  array('tl_leagues_matches', 'generateDateEntry')
		),
		'ondelete_callback' => array
		(
		  array('tl_leagues_matches', 'updateEvents')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'team_home' => 'index',
				'team_away' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('start_date'),
			'headerFields'            => array('contestname','name'),
			'flag'                    => 12,
			'panelLayout'             => 'filter,limit',
			'child_record_callback'   => array('tl_leagues_matches', 'listMatch')
		),
		'label' => array
		(
			'fields'                  => array('team_home','team_away','score_home','score_away'),
			'format'                  => '%s vs. %s  <strong>(%s : %s)</strong>',
			'group_callback'		      => array('tl_leagues_matches','getGrouplabel')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_matches']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_matches']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
  			'button_callback'     => array('tl_leagues_matches', 'copyMatch')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_matches']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
  			'button_callback'     => array('tl_leagues_matches', 'deleteMatch')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_matches']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			/*
			'reports' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_matches']['reports'],
				'href'                => 'table=tl_lm_match_reports',
				'icon'                => 'system/modules/league-manager/images/report.png'
			),
			'events' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_matches']['events'],
				'href'                => 'table=tl_lm_match_events',
				'icon'                => 'taskcenter.gif'
			)
			*/
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('different_points', 'addLocation', 'events'),
		'default'                     => '{datetime_legend},start_date;'
		                                .'{result_legend},team_home,team_away,scoring;'//score_home,score_away,different_points;'
		                                .'{event_legend:hide},events;'
		                                .'{location_legend:hide},addLocation;'
		                                .'{confirm_legend},result_confirmed'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'different_points'    => 'points_home,points_away',
		'addLocation'         => 'location,street,zip,city',
		'events'              => 'reason,nextdate'
	),

	// Fields
	'fields' => array
	(
		'id' => array( 'sql' => "int(10) unsigned NOT NULL auto_increment" ),
		'pid' => array
		(
			'foreignKey'              => 'tl_leagues_rounds.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array( 'sql' => "int(10) unsigned NOT NULL default '0'"),
		
		'start_date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['start_date'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'team_home' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['team_home'],
			'filter'				          => true,
			'inputType'               => 'select',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50','chosen'=>true,'includeBlankOption'=>true),
			'options_callback'        => array('tl_leagues_matches', 'getTeams'),
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'team_away' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['team_away'],
			'filter'				          => true,
			'inputType'               => 'select',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50','chosen'=>true,'includeBlankOption'=>true),
			'options_callback'        => array('tl_leagues_matches', 'getTeams'),
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		
		// score_legend
		// SCORING IS ONLY FOR VOLLEYBALL NEEDED YET <= Parent showLittlePoints = true
		'scoring' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['scoring'],
			'inputType'               => 'scoreWizard',
			'sql'                     => "blob NULL",
			'save_callback' => array
			(
				array('tl_leagues_matches', 'generateEndResult')
			)
		),
		
		// FOR THE REST OF IT
		'score_home' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['score_home'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(20) NOT NULL default ''"
		),
		'score_away' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['score_away'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(20) NOT NULL default ''"
		),
		
		
		'different_points' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['different_points'],
			'inputType'               => 'checkbox',
			'eval'                    => array('mandatory'=>false,'submitOnChange'=>true, 'doNotCopy' => true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'points_home' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['points_home'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "varchar(20) NOT NULL default ''"
		),
		'points_away' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['points_away'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "varchar(20) NOT NULL default ''"
		),
		'result_confirmed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['result_confirmed'],
			'inputType'               => 'checkbox',
			'filter'				          => true,
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'addLocation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['addLocation'],
			'inputType'               => 'checkbox',
			'eval'                    => array('mandatory'=>false,'submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'location' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['location'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'street' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['street'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255),
			'sql'                     => "varchar(20) NOT NULL default ''"
		),
		'zip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['zip'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>10, 'rgxp'=>digit, 'tl_class'=>'w50'),
			'sql'                     => "varchar(20) NOT NULL default ''"
		),
		'city' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['city'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>30, 'tl_class'=>'w50'),
			'sql'                     => "varchar(155) NOT NULL default ''"
		),
		/*
		// event_legend
		'events' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['events'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'doNotCopy' => true)
		),
		'reason' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['reason'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array(1,2,3,4),
			'reference'               => &$GLOBALS['LEAGUEMANAGER']['reasons'],
			'eval'                    => array('includeBlankOption'=>true, 'mandatory' => true, 'tl_class'=>'w50', 'doNotCopy' => true)
		),
		'nextdate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_matches']['nextdate'],
			'exclude'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard', 'doNotCopy' => true)
		)
		*/
	)
);


class tl_leagues_matches extends Backend
{
  public $leagueID = false;
  
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
		
		// Get the league-ID and store it
		if( $this->Input->get('act') == 'edit' )
		{
		  $objLeagueStmt = $this->Database->prepare
		  (
		    "SELECT r.pid FROM tl_leagues_matches AS m INNER JOIN tl_leagues_rounds AS r ON r.id = m.pid WHERE m.id=?"
		  );
		}
		else
		{
		  $objLeagueStmt = $this->Database->prepare("SELECT pid FROM tl_leagues_rounds WHERE id=?");
		}
		
		$objLeagues = $objLeagueStmt->execute($this->Input->get('id'));
		$this->leagueID = $objLeagues->pid;
		
		// Check permissions to add matches
		if (!$this->User->hasAccess('create', 'leaguesp') || !in_array($this->leagueID, $this->User->leagues))
		{
			$GLOBALS['TL_DCA']['tl_leagues_matches']['config']['closed'] = true;
		}
	}
	
	
	/**
	 * Wenn in der Liga showLittlePoints true ist
	 * das Punkte-Array anzeigen
	 */
	public function checkSportsType($dc)
	{
	  $objSports = $this->Database->prepare("SELECT l.showLittlePoints FROM tl_leagues_rounds AS r 
    LEFT JOIN tl_leagues AS l ON l.id = r.pid
    RIGHT JOIN tl_leagues_matches AS m ON r.id = m.pid
    WHERE m.id=?")->execute($dc->id);
	  
	  if( !$objSports->showLittlePoints )
	  {
	    return;
	  }
	  
	  $GLOBALS['TL_DCA']['tl_leagues_matches']['palettes']['default'] = str_replace
	  (
	    'score_home,score_away',
	    'scoring',
	    $GLOBALS['TL_DCA']['tl_leagues_matches']['palettes']['default']
    );
	}
	
	/**
	 * Return the delete button
	 */
	public function deleteMatch($row, $href, $label, $title, $icon, $attributes)
	{
    return ($this->User->isAdmin || $this->User->hasAccess('delete', 'leaguesp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	
	/**
	 * Return copy-button
	 */
	public function copyMatch($row, $href, $label, $title, $icon, $attributes)
	{
    return ($this->User->isAdmin || ($this->User->hasAccess('create', 'leaguesp') && in_array($this->leagueID, $this->User->leagues) ) ) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	
	/**
	 * get teams from tl_leagues
	 */
	public function getTeams($dc)
	{
	  $objTeams = $this->Database->prepare
	  (
	    "SELECT l.teams FROM tl_leagues_rounds AS r INNER JOIN tl_leagues AS l ON l.id = r.pid WHERE r.id=?"
	  )
	  ->execute( ($dc->activeRecord ? $dc->activeRecord->pid : $this->Input->get('id') ) );
	  
	  $arrReturn = array();
	  $arrTeams = unserialize($objTeams->teams);
	  $objTeamNames = $this->Database->execute("SELECT id, title FROM tl_teams WHERE id IN(".implode(",", $arrTeams).") ORDER BY title");
	  
	  while($objTeamNames->next())
	  {
	    $arrReturn[$objTeamNames->id] = $objTeamNames->title;
	  }
	  
	  return $arrReturn;
	}

	
	public function listMatch($arrRow)
	{
	  // get home-team
	  $objTeamNames = $this->Database->prepare("SELECT title FROM tl_teams WHERE id=?")->execute($arrRow['team_home']);
	  $return = $objTeamNames->title.' vs. ';
	  
	  // get away team
	  $objTeamNames = $this->Database->prepare("SELECT title FROM tl_teams WHERE id=?")->execute($arrRow['team_away']);
	  $return .= $objTeamNames->title;
	  
	  // add result if exists
	  if( $arrRow['score_home'] || $arrRow['score_away'] )
	  {
	    $return .= ' <b>('.$arrRow['score_home'].':'.$arrRow['score_away'].')</b>';
	    $return .= ($arrRow['result_confirmed'] ? ' *' : '');
	    $return .= ($arrRow['different_points'] ? ' <b>P</b>' : '');
	  }
	  
	  return $return;
	}
	
	
	public function getGrouplabel($label)
	{
		$label = date($GLOBALS['TL_CONFIG']['dateFormat'],(int)$label);
		return $label;
	}
	
	/**
	 * Endergebnis errechnen
	 */
	public function generateEndResult($varValue, DataContainer $dc)
	{		
		$arrResult = unserialize($varValue);
		$ResultHome = 0;
		$ResultAway = 0;
		$setDB = false;
		
		for($i = 0; $i < 5; $i++)
		{
			if($arrResult['home_'.$i] && $arrResult['away_'.$i])
			{
				if($arrResult['home_'.$i] > $arrResult['away_'.$i])
				{
					$ResultHome++;
				}
				else
				{
					$ResultAway++;
				}
				
				$setDB = true;
			}
		}
		
		if( $setDB )
		{
			$this->Database->prepare('UPDATE tl_leagues_matches SET score_home=?, score_away=? WHERE id=?')
															->execute($ResultHome, $ResultAway, $dc->activeRecord->id);
		}
		return $varValue;
	}
	
	
	public function generateDateEntry(DataContainer $dc)
	{
	  $objTime = $this->Database->prepare("SELECT min(start_date) AS mindate, max(start_date) AS maxdate FROM tl_leagues_matches WHERE pid=?")->execute($dc->activeRecord->pid);
	  
	  $objMatchDay = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE (pid=? OR pid=?) AND matchday_id=?")
	                                ->execute(2, 4, $dc->activeRecord->pid);

	  // Nur die Zeit des Events aktualisieren
	  if( $objMatchDay->numRows )
	  {
	    $this->Database->prepare("UPDATE tl_calendar_events SET startTime=?, endTime=?, startDate=?, endDate=? WHERE id=?")
	                   ->execute($objTime->mindate, $objTime->maxdate, $objTime->mindate, $objTime->maxdate, $objMatchDay->id);
	    return false;
	  }
	  
	  // Neue Eintrag erzeugen
	  $objData = $this->Database->prepare
    (
      "SELECT min(m.start_date) AS mindate, max(m.start_date) AS maxdate, r.*, l.name AS league, l.alias AS league_alias "
      ."FROM tl_leagues_rounds AS r "
      ."LEFT JOIN tl_leagues_matches AS m ON m.pid = r.id "
      ."RIGHT JOIN tl_leagues AS l ON l.id = r.pid "
      ."WHERE r.id=? GROUP BY r.id"
    )
    ->execute($dc->activeRecord->pid);
	  
	  $title = $objData->name.' '.$objData->league;
    $alias = $objData->id.'_'.$objData->tstamp.'_'.$objData->round_no;
    $url = 'spieltag-ergebnisse/'.$objData->league_alias.'.html?round='.$objData->round_no;
    $showIn = unserialize($objData->showIn);
    
    // enter to barnim
    if( in_array(55, $showIn) )
    { 
      $this->Database->execute
      (
        "INSERT INTO tl_calendar_events "
        ."(pid, tstamp, title, alias, addTime, startTime, endTime, startDate, endDate, source, url, published, matchday_id) VALUES "
        ."('4', '".time()."', '".$title."', '".$alias."', '1', '".$objData->mindate."', '".$objData->maxdate."', '".$objData->mindate."', '".$objData->maxdate."', 'external', '".$url."', '1', '".$objData->id."')"
      );
    }
    
    // enter to uckermark
    if( in_array(46, $showIn) )
    {
      $this->Database->execute
      (
        "INSERT INTO tl_calendar_events "
        ."(pid, tstamp, title, alias, addTime, startTime, endTime, startDate, endDate, source, url, published, matchday_id) VALUES "
        ."('2', '".time()."', '".$title."', '".$alias."', '1', '".$objData->mindate."', '".$objData->maxdate."', '".$objData->mindate."', '".$objData->maxdate."', 'external', '".$url."', '1', '".$objData->id."')"
      );
    }
	}
	
	// Eventzeit beim löschen von Spielen aktualisieren
	public function updateEvents()
	{
	  $objMatch = $this->Database->prepare("SELECT pid FROM tl_leagues_matches WHERE id=?")->execute($this->Input->get('id'));
	  $objTime = $this->Database->prepare
	  (
	    "SELECT min(start_date) AS mindate, max(start_date) AS maxdate FROM tl_leagues_matches "
	    ."WHERE pid=? AND id!=?"
	  )
	  ->execute( $objMatch->pid, $this->Input->get('id') );
	  
	  if( $objTime->mindate == '' && $objTime->maxdate == '' )
	  {
	    $this->Database->prepare("DELETE FROM tl_calendar_events WHERE matchday_id=?")->execute($objMatch->pid);
	    return false;
	  }
	  
	  $this->Database->prepare("UPDATE tl_calendar_events SET startTime=?, endTime=?, startDate=?, endDate=? WHERE id=?")
                   ->execute($objTime->mindate, $objTime->maxdate, $objTime->mindate, $objTime->maxdate, $objMatch->pid);
	}
}
