<?php
set_time_limit(6000000); 
//$link = mysql_connect('140.112.233.123', 'root', 'lapy110');
$link = mysql_connect('127.0.0.1', 'websysS15GB4', 'websysS15GB4!!');
mysql_query("SET NAMES utf8");
mysql_select_db("websysS15GB4",$link);

$TICKER = array_key_exists('TICKER',$_GET)?$_GET['TICKER']:"AMZN:US";
//$TICKER = $_GET['TICKER'];

$query = "SELECT * FROM  `companyb4_unique` WHERE MAJOR_TICKER = '".$TICKER."';";
$result = mysql_query($query);     
$row = mysql_fetch_array($result);
$CompanyName = $row['CompanyName'];

$array_counter = 0;
$data = array();
$data[0] = array();
$data[0][$array_counter++] = array('SUB_INDUSTRY', '# of News');

$query3 = "select distinct(SUB_INDUSTRY) from companyb4_unique where INDUSTRY ='$row[INDUSTRY]';";
$result3 =mysql_query($query3,$link);
$ii=0;

for($i=0;$i<mysql_num_rows($result3);$i++)
{
	$row3 = mysql_fetch_array($result3);
	$query  = "SELECT * FROM `news_info_".(2013)."` WHERE SUB_INDUSTRY = '$row3[SUB_INDUSTRY]';";

	//echo $query;
	$result = mysql_query($query);								
	$row2 = mysql_fetch_array($result);

	if($row3['SUB_INDUSTRY'] == $row['SUB_INDUSTRY'])
		$ii=$i;

	$S = 0;
	for($j=1;$j<=12;$j++)
		$S+= $row2[strval($j)];

	$data[0][$array_counter++] = array($row2['SUB_INDUSTRY'],$S);
}
$data[1] = array($ii,$row['INDUSTRY']);
echo json_encode($data);
?>