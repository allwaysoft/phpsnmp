<?php
snmp_set_quick_print(true);
$sysUpTime=snmpget("127.0.0.1", "nm2013", "sysDescr.0");
$sOID=snmpget("127.0.0.1", "nm2013", "system.sysObjectID.0");
echo "$sysUpTime\n";
echo "$sOID\n";
snmp_set_quick_print(false);
$sysUpTime=snmpget("127.0.0.1", "nm2013", "system.sysUpTime.0");
$sOID=snmpget("127.0.0.1", "nm2013", "system.sysObjectID.0");
echo "$sysUpTime\n";
echo "$sOID\n";
?> 
