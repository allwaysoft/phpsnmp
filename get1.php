<?php
snmp_set_quick_print(true);
$host = "122.144.142.194";
$community = "public";
echo "获取内存大小:";
$descr = snmpget($host, $community,  '.1.3.6.1.4.1.2021.4.6.0');
var_dump($descr);
echo "簇的的数目";
$descr = snmprealwalk($host, $community, ".1.3.6.1.2.1.25.2.3.1.5");

var_dump($descr);


echo "使用多少，跟总容量相除就是占用率";
$descr = snmprealwalk($host, $community, ".1.3.6.1.2.1.25.2.3.1.6");

var_dump($descr);

echo "<br><";
//$descr = snmpget($host, $community, "HOST-RESOURCES-MIB::host.hrSystem.2.0");
$descr = snmpget($host, $community, "");
var_dump($descr);
?>