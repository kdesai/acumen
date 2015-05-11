<?php
set_time_limit(6000000); 
//$link = mysql_connect('140.112.233.123', 'root', 'lapy110');
$link = mysql_connect('127.0.0.1', 'websysS15GB4', 'websysS15GB4!!');
//$link = mysql_connect('127.0.0.1', 'root', '');
mysql_query("SET NAMES utf8");
mysql_select_db("websysS15GB4",$link);

$TICKER = array_key_exists('TICKER',$_GET)?$_GET['TICKER']:"AMZN:US";
//$TICKER = $_GET['TICKER'];

//$query = "SELECT * FROM  `companyb4_unique` WHERE MAJOR_TICKER = '".$TICKER."';";
//$result = mysql_query($query);     
//$row = mysql_fetch_array($result);
//$CompanyName = $row['CompanyName'];

$query = "SELECT * FROM  `testcount` WHERE MAJOR_TICKER = '$TICKER';";
$result = mysql_query($query,$link);
$row = mysql_fetch_array($result);

$RELATED_TICKERS = json_decode($row['RELATED_COMPANIES_JSON'],true);
$CORELATION = json_decode($row['CORELATION'],true);

$array_counter = 0;
$data = array();
$data[0] = array();
$data[1] = array($TICKER);
$data[2] = array();
$data[3] = array();
$otc = 0;

$query = "SELECT * FROM  `testcount2` WHERE MAJOR_TICKER = '$TICKER';";
$result = mysql_query($query,$link);
$row2 = mysql_fetch_array($result);
$OTHER_TICKERS = json_decode($row2['OTHER_TICKERS_JSON'],true);
//print_r($OTHER_TICKERS);

foreach ($OTHER_TICKERS as $k => $v) {
    $data[0][$array_counter++] = array("source" => $TICKER, "target" => $k,"type" => "licensing");
    $data[3][$otc++] = $k;
}


for($i=1;$i<=9;$i++)
{
	$query = "SELECT * FROM  `testcount` WHERE MAJOR_TICKER = '".$RELATED_TICKERS[$i]['symbol']."';";
	//echo $query;
	$data[2][$i-1] = $RELATED_TICKERS[$i]['symbol'];
	$result = mysql_query($query,$link);
	$row = mysql_fetch_array($result);

	$RELATED_TICKERS_2 = json_decode($row['RELATED_COMPANIES_JSON'],true);
	//echo '{source: "'.$RELATED_TICKERS[0]['symbol'].'", target: "'.$RELATED_TICKERS[$i]['symbol'].'", type: "licensing"}'.",\n";
	
	$data[0][$array_counter++] = array("source" => $RELATED_TICKERS[0]['symbol'], "target" => $RELATED_TICKERS[$i]['symbol'],"type" => "licensing");

	for($j=1;$j<=9 && $j<sizeof($RELATED_TICKERS_2);$j++)
	{
		 //echo '{source: "'.$RELATED_TICKERS[$i]['symbol'].'", target: "'.$RELATED_TICKERS_2[$j]['symbol'].'", type: "licensing"}';
		 $data[0][$array_counter++] = array("source" => $RELATED_TICKERS[$i]['symbol'], "target" => $RELATED_TICKERS_2[$j]['symbol'],"type" => "licensing");
		 
	}

	$query = "SELECT * FROM  `testcount2` WHERE MAJOR_TICKER = '".$RELATED_TICKERS[$i]['symbol']."';";
	$result = mysql_query($query,$link);

	if(mysql_num_rows($result)>0)
	{
		//echo "$i  ".$RELATED_TICKERS[$i]['symbol'].mysql_num_rows($result);
		$row2 = mysql_fetch_array($result);
		$OTHER_TICKERS = json_decode($row2['OTHER_TICKERS_JSON'],true);

		foreach ($OTHER_TICKERS as $k => $v) {
		    $data[0][$array_counter++] = array("source" => $RELATED_TICKERS[$i]['symbol'], "target" => $k,"type" => "licensing");
		    $data[3][$otc++] = $k;
		}
	}

}
echo json_encode($data);
?>

																		  
