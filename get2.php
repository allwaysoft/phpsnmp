<?php
snmp_set_quick_print(true);
$host = $argv[1];
$community = $argv[2];
$oid=$argv[3];
$val = snmpget($host, $community, $oid);
echo "$oid = $val\n";
?>