<?php
if (empty($_GET['tarea']))
    exit;

require_once ('php/vital.php');

switch ($_GET['tarea']):
    case 'recuperar_no_contestados':
        $c = 'UPDATE '.db_prefijo.'prospectos SET situacion="nuevo" WHERE situacion="reciclar" AND ultima_presentacion<=(DATE(NOW())-INTERVAL 7)';
        db_consultar($c);
        break;
    case 'recuperar_no_interesados':
        $c = 'UPDATE '.db_prefijo.'prospectos SET situacion="nuevo" WHERE situacion="reciclar" AND ultima_presentacion<=(DATE(NOW())-INTERVAL 7)';
        db_consultar($c);
        break;
    case 'notificar_aplicaciones_olvidadas':
        break;
    case 'notificar_recordatorios_us':
        $c = sprintf('SELECT `apellido`, `nombre`, `ID_aplicacion`,  FROM (%s LEFT JOIN %s USING (ID_prospecto)) LEFT JOIN %s USING (ID_usuario) WHERE fecha_visto="0000-00-00 00:00:00" AND DATE(`fecha`) <= (DATE(NOW()) + INTERVAL 15 MINUTE) ORDER BY `fecha` ASC', db_prefijo.'prospectos_aplicados', db_prefijo.'prospectos', db_prefijo.'prospectos_aplicados_recordatorio');
        break;

endswitch;
?>
