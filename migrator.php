<?

$dbOld = 'db_volleyball_neu';
$dbNew = 'db_lokalsports';
$showIn = array(0 => 46);
$arrGender = array('female' => 'damen', 'male' => 'herren');

$sql = mysql_connect('localhost', 'root', 'vida$01');
if( !$sql ){ die("Verbindung fehlgeschlagen"); }

mysql_select_db($dbOld);

// get some leagues, rounds, matches
$sql = mysql_query("SELECT * FROM tl_news WHERE pid=3 ORDER BY date DESC LIMIT 10");

?>