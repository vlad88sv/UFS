<?php
if (!S_iniciado())
{
    include('php/inicio.php');
    return;
}

echo '<div id="portada">';
switch (_F_usuario_cache('nivel'))
{
    case _N_administrador_us:
    case _N_administrador_sv:
        mostrar_recordatorios();
        portada_mostrar_notas();
        break;
    case _N_agente_us:
        echo '<p><span style="font-weight:bold;">Instrucciones rápidas</span></p><p>Para ver todas las aplicaciones (libres y suyas) ir a Menú -> Aplicaciones.<br /><p>Para ver las aplicaciones libres ir a Menú -> Aplicaciones libres.<br /><b style="color:#F00;">Para ver las aplicaciones que he tomado ir a Menú -> <a href="'.PROY_URL.'aplicaciones?asignadas">Mis aplicaciones</a>.</b></p>';
        mostrar_recordatorios();
        portada_mostrar_notas();
        break;
    case _N_agente_sv:
        echo '<p><span style="font-weight:bold;">Instrucciones rápidas</span></p><p>Para obtener un nuevo prospecto ir a Menú -> Prospecto.<br />Para ver las aplicaciones tomadas ir a Menú -> Aplicaciones.</p>';
        mostrar_recordatorios();
        portada_mostrar_notas();
        break;
}

echo '</div>';
/******************************************************************************/

function mostrar_recordatorios()
{
    echo '<h1>Recordatorios</h1>';
    $c = sprintf('SELECT `ID_prospecto`, `situacion`, `ultima_presentacion`, `intentos`, `apellido`, `nombre`, `direccion1`, `direccion2`, `especial1`, `ciudad`, `estado`, `zip`, `telefono`, `especial2`, `especial3`, `especial4`, `especial5`, `especial6`, `especial7`, `especial8`, `especial9`, `especial10`, `especial11`, `especial13`, `especial14`, `especial15`, `especial16`, `especial17`, `especial18`, `especial19`, `especial20`, `especial21`, `ID_aplicacion`, `ID_agente_sv`, `ID_agente_us`, `enviado`, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_sv`) AS nombre_agente_sv, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_us`) AS nombre_agente_us, `fecha_ingresada`, `fecha_aceptada`, `fecha_cerrada`, `comision_agente_sv`, `comision_agente_us`, `comsion_ufs_sv`, `comision_ufs_us`, `notas`, `interes`, `ID_recordatorio`, `ID_usuario`, `ID_aplicacion`, DATE_FORMAT(fecha,"%%e-%%b-%%Y<br />%%r") AS "fecha", `nota`, `fecha_visto` FROM %s LEFT JOIN %s USING (ID_prospecto) LEFT JOIN %s USING (ID_aplicacion) WHERE fecha_visto="0000-00-00 00:00:00" AND ID_usuario=%s ORDER BY `fecha` ASC', db_prefijo.'prospectos_aplicados', db_prefijo.'prospectos', db_prefijo.'prospectos_aplicados_recordatorio', _F_usuario_cache('ID_usuario'));
    $r = db_consultar($c);

    if (mysql_num_rows($r))
    {
        echo '<table class="tabla-estandar cebra">';
        echo '<tr><th>Fecha y hora<acronym title="Fecha para la cúal se estableció el recordatorio">*</acronym></th><th>Owner</th><th>Dirección</th><th>Acción</th></tr>';
        while ($f = mysql_fetch_assoc($r))
        {
            echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $f['fecha'], $f['apellido'].', '.$f['nombre'],$f['direccion2'],'<a href="'.PROY_URL.'aplicaciones?a='.$f['ID_aplicacion'].'&r='.$f['ID_recordatorio'].'">ver</a>');
        }
        echo '</table>';
    }
    else
    {
        echo '<p>No hay recordatorios de aplicación pendientes</p>';
    }
}

/*
portada_mostrar_notas(VOID)
    Muestra las últimas notas ingresadas por aplicación
 */
function portada_mostrar_notas()
{
    /*
        Alcance: Portada
        Permisos: Iniciados
        Logica:
        * Mostrar las aplicaciones a las que se les halla ingresado una nota recientemente.
        * Maximo 3 días anteriores
    */

    echo '<h1>Aplicaciones con notas en los últimos 3 días</h1>';

    if (in_array(_F_usuario_cache('nivel'), array(_N_agente_sv,_N_agente_us,_N_agente_us_solo)) || isset($_GET['forzar_agente_sv']))
        $where = 'AND ID_usuario = '._F_usuario_cache('ID_usuario');
    else
        $where = '';


    /**************/
    // MySQL
    // Encontrar las aplicaciones que tengan notas en los ultimos 3 días, por orden de aplicacion con nota mas reciente
    $cA3dias = 'SELECT `ID_aplicacion`, nombre, apellido, direccion2 FROM '.db_prefijo.'historial LEFT JOIN '.db_prefijo.'prospectos_aplicados USING(ID_aplicacion) LEFT JOIN '.db_prefijo.'prospectos USING(ID_prospecto) WHERE fecha > (DATE(NOW()) - INTERVAL 3 DAY) '.$where.' GROUP BY `ID_aplicacion` ORDER BY MAX(`fecha`) DESC';
    $rA3dias = db_consultar($cA3dias);

    //,'<a href="'.PROY_URL.'aplicaciones?a='.$f['ID_aplicacion'].'">ver</a>'

    if (mysql_num_rows($rA3dias))
    {
        while ($fA3Dias = mysql_fetch_assoc($rA3dias))
        {
            // Mostrar que aplicación es.
            echo '<table style="table-layout:fixed;" class="tabla-estandar">';
            echo '<tr><th><a href="'.PROY_URL.'aplicaciones?a='.$fA3Dias['ID_aplicacion'].'">ver aplicación</a></th><th>'.$fA3Dias['apellido'].', '.$fA3Dias['nombre']. '</th><th>'.$fA3Dias['direccion2'].'</th></tr>';
            echo '</table>';
            // Sacar todas las notas de esas aplicaciones
            $cHistoria = sprintf('SELECT DATE_FORMAT(fecha,"%%e-%%b-%%Y<br />%%r") AS "fecha_formato", (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = h.`ID_usuario`) AS nombre_usuario, `cambio` FROM '.db_prefijo.'historial AS h WHERE ID_aplicacion='.$fA3Dias['ID_aplicacion']);
            $rHistoria = db_consultar($cHistoria);

            echo '<table class="tabla-estandar cebra">';
            echo '<tr><th>Fecha</th><th>Usuario</th><th>Anotación</th></tr>';
            while ($fHistoria = mysql_fetch_assoc($rHistoria))
            {
                echo sprintf('<tr><td class="historia-fecha">%s</td><td class="historia-por">%s</td><td class="historia-nota">%s</td></tr>',$fHistoria['fecha_formato'],$fHistoria['nombre_usuario'],$fHistoria['cambio']);
            }
            echo '</table><hr style="border:1px solid #F00;"/>';
        }
    }
    else
    {
        echo '<p>No se encontraron notas anexas de aplicación</p>';
    }
}
?>
