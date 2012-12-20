<?

/**
 * 1. Nach Punkten
 * 2. Nach Spielen
 * 3. Nach Sätzen gewonnen
 * 4. Nach Sätzen verloren
 */
function cmp($a, $b)
{
	// Erster Versuch => Punkte
	if ($a['points'] == $b['points']) 
	{
		// weniger Spiele zuerst
		if( $a['games'] == $b['games'] )
		{
			// gleiche Gewinnsätze
			if( $a['set_win'] == $b['set_win'] )
			{
				if( $a['set_lose'] == $b['set_loss'] )
				{
					// FUCK YOU
					return 0; 
				}// end gleiche Verlustssätze
				return ($a['set_lose'] > $b['set_lose']) ? 1 : -1;
			
			}// end gleiche Gewinnsätze
			return ($a['set_win'] < $b['set_win']) ? 1 : -1;
		
		}// end gleiche Spiele
		return ($a['games'] > $b['games']) ? 1 : -1;
		
	}// end gleiche Punkte
	return ($a['points'] < $b['points']) ? 1 : -1;
}
		
?>