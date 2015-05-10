<?php

set_time_limit(6000000); 
//$link = mysql_connect('140.112.233.123', 'root', 'lapy110');
$link = mysql_connect('127.0.0.1', 'websysS15GB4', 'websysS15GB4!!');
mysql_query("SET NAMES utf8");
mysql_select_db("websysS15GB4",$link);

$TICKER = array_key_exists('TICKER',$_GET)?$_GET['TICKER']:"AMZN:US";

$query = "SELECT * FROM  `companyb4_unique` WHERE MAJOR_TICKER = '".$TICKER."';";
$result = mysql_query($query);     
$row = mysql_fetch_array($result);
$CompanyName = $row['CompanyName'];
$SECTOR = $row['CompanyName'];
$INDUSTRY = $row['INDUSTRY'];
$SUB_INDUSTRY= $row['SUB_INDUSTRY'];

$query = "SELECT * FROM  `testcount` WHERE MAJOR_TICKER = '$TICKER';";
$result = mysql_query($query,$link);
$row = mysql_fetch_array($result);

$RELATED_TICKERS = json_decode($row['RELATED_COMPANIES_JSON'],true);

$CORELATION = json_decode($row['CORELATION'],true);
$array_counter = 0;


$data = array();
$data[$array_counter++] = array('Month', $SECTOR, $INDUSTRY, $SUB_INDUSTRY, $CompanyName);

$c = 0;
for($y=2011;$y<=2013;$y++)
{

	for($m=1;$m<=12;$m++)
	{
		$query2 = "SELECT SUM(`$m`) FROM news_info_".($y)." WHERE SECTOR='$SECTOR';";//sector
		$query3 = "SELECT SUM(`$m`) FROM news_info_".($y)." WHERE INDUSTRY='$INDUSTRY';";//industry
		$query4 = "SELECT SUM(`$m`) FROM news_info_".($y)." WHERE SUB_INDUSTRY='$SUB_INDUSTRY';";//sub-industry
		//echo $query2."\n";
		//$query5 = "SELECT * FROM `news_".($y).(floor($m/10)).($m%10)."` WHERE `RELTEAD_TICKERS_JSON` LIKE '%".$TICKER."%';";
		//echo $query5;

		$row2 = mysql_fetch_array(mysql_query($query2));
		$row3 = mysql_fetch_array(mysql_query($query3));
		$row4 = mysql_fetch_array(mysql_query($query4));
		//$result5 = mysql_query($query5);

		$data[$array_counter++] = array(($y)."-".(floor($m/10)).($m%10),($row2["SUM(`$m`)"]),($row3["SUM(`$m`)"]),($row4["SUM(`$m`)"]),($CORELATION[0]['CORELATED_NUMBER'][$c++]));

	}
}

echo json_encode($data);
?>