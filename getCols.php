<?php
snmp_set_quick_print(true);
$host = "127.0.0.1";
$community = "public";
$oid = "ipRouteTable";
$idArr = array(1,2,3,11,8,7);
$arr = getCols($host, $community, $oid, $idArr);
htmlTable($arr);

function getCols($host, $comm, $oid, $colArr) {
	$j=1;
	foreach ($colArr as $id) {
		$ret[$j] = snmprealwalk($host, $comm, "$oid.1.$id");
		$j++;
	}
	for ($j=1; $j <= count($colArr); $j++) {
		$i=1;
		foreach ($ret[$j] as $oid => $val) {
			$oTable[$i][$j]=explode(".",$oid);
			$table[$i][$j]=$val;
			$i++;
		}
	}
	$cl = col($oTable[1][1], $oTable[1][2]);
	$table[0][0] = "Index";
	for ($j=1; $j <= count($colArr); $j++)
	   $table[0][$j] = $oTable[1][$j][$cl];
	for ($i=1;$i <= count($ret[1]);$i++) {
		$iId ="";
		for ($j=($cl+1);$j < count($oTable[$i][1]);$j++)
		   $iId = $iId . "." . $oTable[$i][1][$j];
		$table[$i][0] = substr($iId,1);
	}
	return $table;
}

function col($arr1, $arr2) {
	$i=0;
	while ($arr1[$i]==$arr2[$i])
	   $i++;
	return $i;
}

function htmlTable($arr) {
	echo "<table align=center border=2 cellpadding=4 bgColor=\"#ccccff\">\n";
	for ($i=0;$i<count($arr);$i++) {
		$bgC = ($i==0) ? "bgColor=#ffffcc" : "";
		echo "<tr $bgC>";
		for ($j=0;$j<count($arr[0]);$j++) {
			$dh = ($i==0) ? "h" : "d";
			$bgC = ($j==0) ? "bgColor=#ffffcc" : "";
			echo "<t{$dh} $bgC>{$arr[$i][$j]}</t{$dh}>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
?>