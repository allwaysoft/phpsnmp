<?php
$host = "127.0.0.1";
$community = "nm2013";
snmp_set_quick_print(true);
$nextObj = snmpgetnext($host, $community, "ifDescr");
echo "$nextObj\n";
?>