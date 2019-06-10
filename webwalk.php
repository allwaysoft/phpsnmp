<?php
snmp_set_quick_print(true);
$host = $_POST['host'];
$comm = $_POST['comm'];  
$oid = $_POST['oid'];   // .1.3.6.1.2.1.25.3.3.1.2 cup
if ($_POST['comm']=="") $comm="public"; 
$arr = snmprealwalk($host, $comm, $oid);
echo "<h3 align=center>SNMPWALK of $host</h3>"; 
echo "<table align=center cellspacing=2 border=2>";
//print_r($arr);
foreach ($arr as $oid => $val){
    echo "<tr><td bgColor=#ffffcc>$oid</td><td bgColor=#ccffff>$val</td></tr>";
}
echo "</table>";

$host = "122.144.142.194";  
$ipForwarding = snmpget($host, "public", ".1.3.6.1.2.1.4"); // .1.3.6.1.2.1.25.2.2.0 内存 
echo "SNMP_VALUE_OBJECT:\n";
var_dump($ipForwarding);
/*
 $b = snmpget("122.144.142.194", "public", ""); 
 for (reset($b); $ii = key($b); next($b)) {
    echo "$i: $b[$ii]<br />\n";
}

echo "<br>sdfsdfsdfsdfsdfsdf=======";

 $a = snmpwalkoid("122.144.142.194", "public", ""); 
 for (reset($a); $i = key($a); next($a)) {
    echo "$i: $a[$i]<br />\n";
}
*/

?>