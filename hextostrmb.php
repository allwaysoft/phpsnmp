<?php
header('Content-Type: text/html;charset="utf-8"'); 
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return mb_convert_encoding($string, 'utf-8', 'gbk');
}
echo hexToStr('4D6963726F736F6674204F666669636520D0A3B6D4B9A4BEDF2032303133202D20BCF2CCE5D6D0CEC4');
?>
