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

$query = "SELECT * FROM  `testcount` WHERE MAJOR_TICKER = '$TICKER';";
$result = mysql_query($query,$link);
$row = mysql_fetch_array($result);

$RELATED_TICKERS = json_decode($row['RELATED_COMPANIES_JSON'],true);
$CORELATION = json_decode($row['CORELATION'],true);

$array_counter = 0;
$data = array();
$data[0] = array();
$data[0][$array_counter++] = array('TICKER', 'Sub-Industrial Categories', 'Corelation', 'Sub-Industrial Categories','Exposure');

$industry_cate = array();
$industry_key = array();

$NUMS_OF_RELATED_COMPANY = 0;
for($i=1;$i<=9;$i++){

	$query = "SELECT MAJOR_TICKER,INDUSTRY,SECTOR,SUB_INDUSTRY,CompanyName FROM  `companyb4_unique` WHERE MAJOR_TICKER = '".$RELATED_TICKERS[$i]['symbol']."';";
	$result = mysql_query($query);

	if(mysql_num_rows($result) == 0)
	{

	}
	else if(mysql_num_rows($result) == 1)
	{	
		$NUMS_OF_RELATED_COMPANY++;
		$row = mysql_fetch_array($result);

		$key = array_search($row['SUB_INDUSTRY'], $industry_cate);
		if($key === FALSE)
		{
			$industry_cate[] = $row['SUB_INDUSTRY'];
			//print_r($industry_cate);
			$key = array_search($row['SUB_INDUSTRY'], $industry_cate);
			//print_r($industry_cate);
			//print_r($CORELATION);
		}
		$industry_key[] = $key;

		if($RELATED_TICKERS[$i]['counts']/$RELATED_TICKERS[0]['counts'] ==1)
			$modified_corelation = 1;
		else if ($RELATED_TICKERS[$i]['counts'] == 0)
			$modified_corelation = 0;
		else{

			$modified_corelation = 1/( 1+ exp(-1/(1-$RELATED_TICKERS[$i]['counts']/$RELATED_TICKERS[0]['counts'])));
			$modified_corelation = $RELATED_TICKERS[$i]['counts']/$RELATED_TICKERS[0]['counts'];
		}

		//echo "['".$RELATED_TICKERS[$i]['symbol']."',".($key+2).",".(100*$modified_corelation).",'$row[SUB_INDUSTRY]',".($RELATED_TICKERS[$i]['counts'])."]";
		//echo "['".$row['CompanyName'].'('.$RELATED_TICKERS[$i]['symbol'].")',".($key+2).",".(100*$modified_corelation).",'$row[SUB_INDUSTRY]',".($RELATED_TICKERS[$i]['counts'])."]";
		//echo "['".$row['CompanyName']."(".$row['MAJOR_TICKER'].")"."',".($key+1).",".(100*$modified_corelation).",'$row[SUB_INDUSTRY]',".($RELATED_TICKERS[$i]['counts'])."]";
		$data[0][$array_counter++] = array( $row['CompanyName'].'('.$row['MAJOR_TICKER'].')', ($key+1) ,(100*$modified_corelation) ,$row['SUB_INDUSTRY'], $RELATED_TICKERS[$i]['counts']);

		//if($i<9)
		//	echo ",\n";
	}
}

/////// chart 2
$array_counter = 0;
$data[1] = array();
$data[1][$array_counter++] = array('TICKER', 'Date', 'Cate index', 'Corelation','Cate');

for($i=1;$i<=5;$i++)
{
	$key = $industry_key[$i];
	for($j=0; $j <= 35 ; $j ++ )
	{
		//echo "['".$RELATED_TICKERS[$i]['symbol']."',new Date (".(2011+($j/12)).",".(($j)%12).",0),".($key).",".($CORELATION[$i]['COEF'][$j]).",'".$industry_cate[$key]."']";
		$data[1][$array_counter++] = array($RELATED_TICKERS[$i]['symbol'] , (2011+($j/12))."-".(($j)%12)."-0",($key),($CORELATION[$i]['COEF'][$j]),$industry_cate[$key]);

	}		
}
$data[2] = $TICKER;
$data[3] = $CompanyName;


echo json_encode($data);

?>