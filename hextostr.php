<?php
header('Content-Type: text/html;charset="GBK"'); 
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}
echo hexToStr('B0D9B6C8CDF8C5CC');
?>