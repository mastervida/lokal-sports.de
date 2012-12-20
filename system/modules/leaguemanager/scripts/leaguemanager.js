/**
 * Javascipt for LEAGUEMANAGER
 * @author: Maik H.
 * @date: 2012/06/03
 */

 var League =
 {
 	checkTeam: function( el )
 	{
 			if( el.id == 'ctrl_team_home')
 			{
 				var element = $('ctrl_team_away');
 			}
 			if( el.id == 'ctrl_team_away')
 			{
 				var element = $('ctrl_team_home');
 			}

 			var optionen = $(element).options;

 			for( var i = 0; i < optionen.length; i++ )
 			{
 				$(element).options[i].disabled = false;
 				if( el.value == optionen[i].value )
 				{
 					$(element).options[i].disabled = true;
 				}
 			}

 			League.setTeams();
 	},

 	setTeams: function()
 	{
 		$('score_hometeam').innerHTML = $('ctrl_team_home').options[$('ctrl_team_home').selectedIndex].text;
 		$('score_awayteam').innerHTML = $('ctrl_team_away').options[$('ctrl_team_away').selectedIndex].text;
 		
 		$('ctrl_team_home').addEvent("change",function()
 		{
 		  $('score_hometeam').innerHTML = $('ctrl_team_home').options[$('ctrl_team_home').selectedIndex].text;
 		});
 		
 		$('ctrl_team_away').addEvent("change",function()
 		{
 		  $('score_awayteam').innerHTML = $('ctrl_team_away').options[$('ctrl_team_away').selectedIndex].text;
 		});
 	},

 	setResult: function()
 	{
 		var ResultHome = 0;
 		var ResultAway = 0;

 		for( var i = 0; i < 5; i++)
 		{
 			if( $('ctrl_score_home_'+i).value && $('ctrl_score_away_'+i).value )
 			{
 				if( $('ctrl_score_home_'+i).value > $('ctrl_score_away_'+i).value )
 				{
 					ResultHome = ResultHome + 1;
 				}
 				else
 				{
 					ResultAway = ResultAway + 1;
 				}

 				alert(i+': '+$('ctrl_score_home_'+i).value +':'+ $('ctrl_score_away_'+i).value);
 			}
 		}

 		$('score_home_result').innerHTML = ResultHome;
 		$('score_away_result').innerHTML = ResultAway;
 	}
 }