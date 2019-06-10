<?php
snmp_set_quick_print(true);
$host = "127.0.0.1";
$community = "public";
$initOid = "interfaces.ifTable";
$arr = snmprealwalk($host, $community, $initOid);
/*
foreach ($arr as $oid => $val) {
   echo "$oid = $val\n";
}
*/
print_r($arr);
?>
