<?php
$host = "127.0.0.1";
$community = "public";
$oid = ".1.3.6.1.2.1.2.2.1.ifAdminStatus.65539";
$isSet = snmpset($host, $community, $oid, "i", 2);
if ($isSet) {
   $adminStatus = snmpget($host, $community, $oid);
   echo "$adminStatus\n";
}
?>