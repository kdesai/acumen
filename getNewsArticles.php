<?php

set_time_limit(600000);
$link = mysql_connect('127.0.0.1', 'websysS15GB4', 'websysS15GB4!!');
mysql_query("SET NAMES utf8");
mysql_select_db("websysS15GB4",$link);

// Grab post variables
$ticker = array_key_exists('ticker',$_GET)?$_GET['ticker']:"AAPL:US";
if (strpos($ticker,':US') === false) {
	$ticker = $ticker . ":US";
}

$month  = $_GET['month'];
$year   = $_GET['year'];

$table = "news_" . $year . $month;
$query = "SELECT * FROM `$table` WHERE `RELTEAD_TICKERS_JSON` LIKE '%$ticker%';";
$result = mysql_query($query,$link);

$num_articles = mysql_num_rows($result);
$news_articles = array();
for ($i=0; $i<$num_articles; $i++) {
	$row = mysql_fetch_array($result);
	$ymd = date_parse($row['DATE']);
	$news_articles[$i] = array($row['TITLE'],strip_tags($row['CONTENT']),$ymd['year'],$ymd['month']-1,$ymd['day'],is_null($row['IMPORTANCE'])?1:$row['IMPORTANCE'],$row['hour']);
}

echo json_encode($news_articles);
?>
