<?php
require_once ('php/vital.php');
require_once('php/mensajitos.us.php');

$c = 'SELECT usuario, nombre, correo, telefono, carrier FROM '.db_prefijo.'usuarios WHERE nivel="agente_us"';
$r = db_consultar($c);

$plantilla = '<p><a href="'.PROY_URL.'">'.PROY_URL.'</a></p><p>Sr./Srta. %s, a continuación sus datos de acceso.</p><p><b>Usuario:</b> %s<br /><b>Clave:</b> ufs2010<br /></p>';

while ($f =  mysql_fetch_assoc($r))
{
    $cuerpo = sprintf($plantilla,$f['nombre'],$f['usuario']);
    correo($f['correo'],'Datos de acceso sistema UFS',$cuerpo);
    EnviarMensajitosUS($f['telefono'],$f['carrier'],'Se le han enviado los datos de acceso a su correo.');
}
?>
