<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright	Agentur-Vida 2010
 * @author 		Maik Helsing <info@agentur-vida.de>
 * @package		SCOREWIZARD
 * @license		LGPL
 * @filesource
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ScoreWizard
 *
 * Provide methods to handle input field "scoreWizard".
 * @copyright	Agentur VIDA 2011
 * @author		Maik Helsing <http://www.agentur-vida.de>
 * @package		Controller
 */
class ScoreWizard extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';

	/**
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'mandatory':
				if ($varValue)
				{
					$this->arrAttributes['required'] = 'required';
				}
				else
				{
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
				break;

			case 'size':
				if ($this->multiple)
				{
					$this->arrAttributes['size'] = $varValue;
				}
				break;

			case 'multiple':
				if ($varValue)
				{
					$this->arrAttributes['multiple'] = 'multiple';
				}
				break;

			case 'options':
				$this->arrOptions = deserialize($varValue);
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
			if( !is_array($this->varValue) )
			{
				$this->varValue = array();
				$this->setResult = false;
			}
			
			$return = '
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="font-weight:bold; padding:3px;">
    <td width="240">Teamname:</td>
    <td>1. Satz</td>
    <td>2. Satz</td>
    <td>3. Satz</td>
    <td>4. Satz</td>
    <td>5. Satz</td>
  </tr>
  <tr>
    <td id="score_hometeam">hometeam</td>';
		for($i = 0; $i < 5; $i++)
		{
    	$return .= '<td><input id="ctrl_score_home_'.$i.'" name="scoring[home_'.$i.']" type="text" value="'.$this->varValue['home_'.$i].'" class="tl_text" style="width:40px;" /></td>';
		}
		$return .= '
  </tr>
  <tr>
    <td id="score_awayteam">awayteam</td>';
		for($i = 0; $i < 5; $i++)
		{
    	$return .= '<td><input id="ctrl_score_away_'.$i.'" name="scoring[away_'.$i.']" type="text" value="'.$this->varValue['away_'.$i].'" class="tl_text" style="width:40px;" /></td>';
		}
    $return .= '
  </tr>
</table>
<script type="text/javascript">
window.addEvent("domready", League.setTeams);
</script>

			';
			
			return $return;
	}
	
	protected function generateSet()
	{
	
	}
}

?>