<?php
snmp_set_quick_print(true);
$host = "127.0.0.1";
$community = "public";
$oid = "ip.ipNetToMediaTable";
$arr = snmptable($host, $community, $oid, 4);
for ($i=1; $i<=count($arr); $i++)
     echo "{$arr[$i][1]}\t{$arr[$i][2]}\t{$arr[$i][3]}\t{$arr[$i][4]}\n";

function snmptable($host, $comm, $oid, $numCols) {
	for ($i=1;$i<=$numCols;$i++)
		$ret[$i] = snmpwalk($host, $comm, "$oid.1.$i");
	$numRows = count($ret[1]);
	for ($i=1; $i<=$numRows; $i++) {
		for ($j=1;$j<=$numCols;$j++) 
			$table[$i][$j]= $ret[$j][$i-1];
	}
	return $table;
}
?>