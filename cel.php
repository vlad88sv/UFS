<?php
require_once ('php/vital.php');
require_once('php/mensajitos.us.php');

$c = 'SELECT usuario, nombre, correo, telefono, carrier FROM '.db_prefijo.'usuarios WHERE nivel="agente_us"';
$r = db_consultar($c);

while ($f =  mysql_fetch_assoc($r))
{
    EnviarMensajitosUS($f['telefono'],$f['carrier'],'Nuevas aplicaciones en el sistema.');
}

?>
