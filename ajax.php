<?php
require_once ('php/vital.php');

if ($_GET['noticias'])
{
    $c = 'SELECT fecha, mensaje, url FROM ufs__noticias BETWEEN  ';
    $r = db_consultar($c);
    $f = mysql_fetch_assoc($c);
    echo '<b>';
}
?>
