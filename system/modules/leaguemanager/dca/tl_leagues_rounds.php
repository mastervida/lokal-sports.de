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
 * Table tl_leagues_rounds 
 */
$GLOBALS['TL_DCA']['tl_leagues_rounds'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_leagues_matches'),
		'ptable'                      => 'tl_leagues',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_leagues_rounds', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('tl_leagues_rounds', 'processOnsubmit')
		),
		'ondelete_callback' => array
		(
		  array('tl_leagues_rounds', 'deleteEvents')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('round_no'),
			'flag'                    => 12,
			'headerFields'            => array('name'),
			'disableGrouping'		      => true,
			'panelLayout'             => 'limit',
			'child_record_callback'   => array('tl_leagues_rounds', 'listRound')
		),
		'label' => array
		(
			'fields'                  => array('name'),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_rounds']['edit'],
				'href'                => 'table=tl_leagues_matches',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_rounds']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_leagues_rounds', 'editHeader'),
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_rounds']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
  			'button_callback'     => array('tl_leagues_rounds', 'copyRound')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_rounds']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
  			'button_callback'     => array('tl_leagues_rounds', 'copyRound')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leagues_rounds']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name'
	),

	// Fields
	'fields' => array
	(
		'id' => array( 'sql' => "int(10) unsigned NOT NULL auto_increment" ),
		'pid' => array
		(
			'foreignKey'              => 'tl_leagues.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array( 'sql' => "int(10) unsigned NOT NULL default '0'" ),
		
		'round_no' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_rounds']['round_no'],
			'exclude'                 => false,
			'inputType'               => 'text',
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leagues_rounds']['name'],
			'exclude'                 => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		
		'contestname' => array( 'sql' => "varchar(255) NOT NULL default ''" )
	)
);


class tl_leagues_rounds extends Backend
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
		  $objLeagueStmt = $this->Database->prepare("SELECT pid FROM tl_leagues_rounds WHERE id=?")->execute($this->Input->get('id'));
		  $this->leagueID = $objLeagues->pid;
		}
		else
		{
		  $this->leagueID = $this->Input->get('id');
		}
		
		// Check permissions to add matches
		if (!$this->User->hasAccess('create', 'leaguesp') || !in_array($this->leagueID, $this->User->leagues))
		{
			$GLOBALS['TL_DCA']['tl_leagues_rounds']['config']['closed'] = true;
		}
	}
	
	/**
	 * Return the delete button
	 */
	public function deleteRound($row, $href, $label, $title, $icon, $attributes)
	{
    return ($this->User->isAdmin || $this->User->hasAccess('delete', 'leaguesp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	
	/**
	 * Return copy-button
	 */
	public function copyRound($row, $href, $label, $title, $icon, $attributes)
	{
    return ($this->User->isAdmin || ($this->User->hasAccess('create', 'leaguesp') && in_array($this->leagueID, $this->User->leagues) ) ) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
	
	
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
	  return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
	
	public function listRound($arrRow)
	{
		return '<div>' . $arrRow['name'] . '</div>' . "\n";
	}
	
	public function processOnsubmit(DataContainer $dc)
	{
		if (!$dc->activeRecord)
		{
			return;
		}
		if($dc->activeRecord->round_no=='0')
		{
			$arrRounds = $this->Database->prepare("SELECT max(round_no) AS max_round FROM tl_leagues_rounds WHERE pid=?")->execute($dc->activeRecord->pid);
			if ($arrRounds->numRows==0){
				$roundno=1;
			}
			else{
				$round_no= $arrRounds->max_round + 1;
			}
			$this->Database->prepare("UPDATE tl_leagues_rounds SET round_no=? WHERE id=?")->execute($round_no, $dc->id);
		}
		
		// get contestname for overview
		$objContest = $this->Database->prepare("SELECT name FROM tl_leagues WHERE id=?")->execute($dc->activeRecord->pid);
		$this->Database->prepare("UPDATE tl_leagues_rounds SET contestname=? WHERE id=?")->execute($objContest->name, $dc->id);
	}//public function processOnsubmit
	
	// delete event from calendar
	public function deleteEvents()
	{
	  if( !$this->Input->get('id') || !is_numeric($this->Input->get('id')) )
	  {
	    return;
	  }
	  
	  $this->Database->prepare("DELETE FROM tl_calendar_events WHERE matchday_id=?")->execute($this->Input->get('id'));
	}
}