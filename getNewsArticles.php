<?php
set_time_limit(600000);
$link = mysql_connect('140.112.233.123', 'root', 'lapy110');
mysql_query("SET NAMES utf8");
mysql_select_db("finance_ms",$link);

// Grab post variables
$ticker = $_GET['ticker'];
$month  = $_GET['month'];
$year   = $_GET['year'];

$table = "news_" . $year . $month;
$query = "SELECT * FROM `$table` WHERE `RELTEAD_TICKERS_JSON` LIKE '%$ticker%';";
$result = mysql_query($query,$link);

$num_articles = mysql_num_rows($result);
$news_articles = array();
for ($i=0; $i<$num_articles; $i++) {
	$row = mysql_fetch_array($result);
	$news_articles[$i] = array($row['TITLE'],strip_tags($row['CONTENT']));
}

echo json_encode($news_articles);
?>