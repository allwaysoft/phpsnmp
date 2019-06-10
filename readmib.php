<?php
snmp_set_quick_print(true);
snmp_read_mib("LanMgr-Mib-II-MIB");
$host = "127.0.0.1";
$community = "public";
$oid = "LanMgr-Mib-II-MIB::svSvcTable";
$arr = realtable($host, $community, $oid, 5);
htmlTable($arr);

function realtable($host, $comm, $oid, $numCols) {
	for ($j=1;$j<=$numCols;$j++)
		$ret[$j] = snmprealwalk($host, $comm, "$oid.1.$j");
	for ($j=1;$j<=$numCols;$j++) {
		$i=1;
		foreach ($ret[$j] as $oid => $val) {
			$oTable[$i][$j]=explode(".",$oid);
			$table[$i][$j]=$val;
			$i++;
		}
	}
	$cl = col($oTable[1][1], $oTable[1][2]);
	$table[0][0] = "Index";
	for ($j=1;$j <= $numCols;$j++)
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
