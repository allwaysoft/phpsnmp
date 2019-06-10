<?php
snmp_set_quick_print(true);
snmp_set_valueretrieval(SNMP_VALUE_LIBRARY);
$ipForwarding = snmpget("127.0.0.1", "nm2013", "ipForwarding.0");
echo "SNMP_VALUE_LIBRARY: $ipForwarding\n";
snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
$ipForwarding = snmpget("127.0.0.1", "public", ".1.3.6.1.2.1.4.1.0");
echo "SNMP_VALUE_PLAIN: $ipForwarding\n";
snmp_set_valueretrieval(SNMP_VALUE_OBJECT);
$ipForwarding = snmpget("127.0.0.1", "public", ".1.3.6.1.2.1.4.1.0");
echo "SNMP_VALUE_OBJECT:\n";
print_r($ipForwarding);
//echo $ipForwarding->value;
?>