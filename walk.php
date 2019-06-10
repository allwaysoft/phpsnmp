<?php
snmp_set_quick_print(true);
$host = "122.144.142.194";
$community = "public";
$oid = ".1.3.6.1.2.1.25.2.3.1.6";
$arr = snmpwalk($host, $community, $oid);
var_dump($arr);
?>