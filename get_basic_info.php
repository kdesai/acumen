<?php

set_time_limit(6000000); 
//$link = mysql_connect('140.112.233.123', 'root', 'lapy110');
$link = mysql_connect('127.0.0.1', 'websysS15GB4', 'websysS15GB4!!');
//$link = mysql_connect('127.0.0.1', 'root', '');
mysql_query("SET NAMES utf8");
mysql_select_db("websysS15GB4",$link);

$ticker = array_key_exists('TICKER',$_GET)?$_GET['TICKER']:"AAPL:US";
$m  = $_GET['m'];
$y   = $_GET['y'];
$d   = $_GET['d'];

$query = "SELECT * FROM  `companyb4_unique` WHERE MAJOR_TICKER = '".$ticker."';";
$result = mysql_query($query);     
$row = mysql_fetch_array($result);
$CompanyName = $row['CompanyName'];
$SECTOR = $row['CompanyName'];
$INDUSTRY = $row['INDUSTRY'];
$SUB_INDUSTRY= $row['SUB_INDUSTRY'];

$data = array();

$table = "news_" . $y . $m;
$query = "SELECT COUNT(HASH_ID) as COUNT FROM `$table` WHERE `DATE` BETWEEN '$y-$m-$d 00:00:01' AND '$y-$m-$d 23:59:59' AND `RELTEAD_TICKERS_JSON` LIKE '%$ticker%'";
//echo $query;
//$query = "SELECT COUNT(*) FROM `$table` WHERE `RELTEAD_TICKERS_JSON` LIKE '%$ticker%';";
$result = mysql_query($query,$link);
$row = mysql_fetch_array($result);
$data[0] = $row['COUNT'];

$table = "news_" . $y . $m;
$query = "SELECT COUNT(HASH_ID) as COUNT FROM `$table` WHERE `DATE` BETWEEN '$y-$m-".($d-7)." 00:00:00' AND '$y-$m-$d 00:00:00' AND `RELTEAD_TICKERS_JSON` LIKE '%$ticker%'";
//echo $query;
//$query = "SELECT COUNT(*) FROM `$table` WHERE `RELTEAD_TICKERS_JSON` LIKE '%$ticker%';";
$result = mysql_query($query,$link);
$row = mysql_fetch_array($result);
$data[1] = $row['COUNT'];


$query = "SELECT COUNT(HASH_ID) as COUNT FROM `$table` WHERE `DATE` BETWEEN '$y-$m-1 00:00:00' AND '$y-$m-31 00:00:00' AND `RELTEAD_TICKERS_JSON` LIKE '%$ticker%'";
//echo $query;
//$query = "SELECT COUNT(*) FROM `$table` WHERE `RELTEAD_TICKERS_JSON` LIKE '%$ticker%';";
$result = mysql_query($query,$link);
$row = mysql_fetch_array($result);
$data[2] = $row['COUNT'];


if($m >= 10 )
	$start_m = 10;
elseif($m>=7)
	$start_m = 7;
elseif($m>=4)
	$start_m = 4;
else
	$start_m = 1;

$c = 0;
for($i = $start_m; $i <= $start_m+2 ; $i++)
{
	$table = "news_" . $y . $i;
	$query = "SELECT COUNT(HASH_ID) as COUNT FROM `$table` WHERE `DATE` BETWEEN '$y-$i-1 00:00:00' AND '$y-$i-31 00:00:00' AND `RELTEAD_TICKERS_JSON` LIKE '%$ticker%'";
	//echo $query."</br>";
	//$query = "SELECT COUNT(*) FROM `$table` WHERE `RELTEAD_TICKERS_JSON` LIKE '%$ticker%';";
	$result = mysql_query($query,$link);
	$row = mysql_fetch_array($result);
	$c += $row['COUNT'];
}
$data[3] = $c;
echo json_encode($data);

?>
