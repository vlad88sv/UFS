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
        echo '
        <p>
        <span style="font-weight:bold;">Instrucciones rápidas</span>
        </p>
        <p>
        <ul>
        <li>Para buscar una aplicación por ID de aplicación, ingrese a:#_de_aplicacion en el cuadro de búsqueda.</li>
        <li>Para buscar una prospecto por ID de prospecto, ingrese p:#_de_prospecto en el cuadro de búsqueda.</li>
        </ul>
        </p>';
        portada_mostrar_desactualizados();
        echo '<br />';
        mostrar_recordatorios();
        break;
    case _N_agente_us:
        echo '
        <p>
        <span style="font-weight:bold;">Instrucciones rápidas</span>
        </p>
        <p>
        <ul>
        <li>Para buscar una aplicacion por ID de aplicación, ingrese a:#_de_aplicacion en el cuadro de búsqueda.</li>
        <li>Para ver todas las aplicaciones a su alcance ir a Menú -> Aplicaciones.</li>
        <li>Para ver todas las aplicaciones que estan pendientes de actualizar ir a Menú -> Desactualizadas.</li>
        <li>Para ver las aplicaciones libres ir a Menú -> Sin tomar (libres)</li>
        <li>Para ver las aplicaciones vendidas (negocio cerrado) ir a Menú -> Sin tomar (libres)</li>
        <li>Para ver las aplicaciones que he tomado ir a Menú -> <a href="'.PROY_URL.'aplicaciones?asignadas">Mis aplicaciones</a>.</li>
        </ul>';
        portada_mostrar_desactualizados();
        echo '<br />';
        mostrar_recordatorios();
        echo '<br />';
        portada_mostrar_notas();
        break;
    case _N_agente_sv:
        echo '
        <p>
        <span style="font-weight:bold;">Instrucciones rápidas</span>
        </p>
        <p>
        <ul>
        <li>Para buscar una aplicación por ID de aplicación, ingrese a:#_de_aplicacion en el cuadro de búsqueda.</li>
        <li>Para buscar una prospecto por ID de prospecto, ingrese p:#_de_prospecto en el cuadro de búsqueda.</li>
        <li>Para obtener un nuevo prospecto ir a Menú -> Prospecto.</li>
        <li>Para ver las aplicaciones tomadas ir a Menú -> Aplicaciones.</li>
        </ul>
        </p>';
        portada_mostrar_desactualizados();
        echo '<br />';
        mostrar_recordatorios();
        echo '<br />';
        portada_mostrar_notas();
        break;
}

echo '</div>';
/******************************************************************************/

function mostrar_recordatorios()
{
    echo '<div class="subtitulo">Recordatorios pendientes de aplicación</div>';
    echo '<p>Se muestran todos recordatorios no vistos y los programados para los próximos 3 días</p>';

    $c = sprintf('SELECT `ID_prospecto`, `situacion`, `ultima_presentacion`, `intentos`, `apellido`, `nombre`, `direccion2`, `ciudad`, `estado`, `zip`, `telefono`, `especial2`, `especial3`, `especial5`, `especial6`, `especial7`, `ID_aplicacion`, `ID_agente_sv`, `ID_agente_us`, `enviado`, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_sv`) AS nombre_agente_sv, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_us`) AS nombre_agente_us, `fecha_ingresada`, `fecha_aceptada`, `fecha_cerrada`, `comision_agente_sv`, `comision_agente_us`, `comsion_ufs_sv`, `comision_ufs_us`, `notas`, `interes`, `ID_recordatorio`, `ID_usuario`, `ID_aplicacion`, DATE_FORMAT(fecha,"%%e-%%b-%%Y %%r") AS "fecha_formato", `nota`, `fecha_visto` FROM %s LEFT JOIN %s USING (ID_prospecto) LEFT JOIN %s USING (ID_aplicacion) WHERE fecha_visto="0000-00-00 00:00:00" AND ID_usuario=%s AND DATE(`fecha`) <= (DATE(NOW()) + INTERVAL 3 DAY) ORDER BY `fecha` ASC', db_prefijo.'prospectos_aplicados', db_prefijo.'prospectos', db_prefijo.'prospectos_aplicados_recordatorio', _F_usuario_cache('ID_usuario'));
    $r = db_consultar($c);

    if (mysql_num_rows($r))
    {
        echo '<table class="tabla-estandar cebra">';
        echo '<tr><th>Fecha y hora<acronym title="Fecha para la cúal se estableció el recordatorio">*</acronym></th><th>Owner</th><th>Dirección</th><th>Acción</th></tr>';
        while ($f = mysql_fetch_assoc($r))
        {
            echo sprintf('<tr><td class="mini">%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $f['fecha_formato'], $f['apellido'].', '.$f['nombre'],$f['direccion2'],'<a href="'.PROY_URL.'aplicaciones?a='.$f['ID_aplicacion'].'&r='.$f['ID_recordatorio'].'">ver</a>');
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

    echo '<div class="subtitulo">Aplicaciones actualizadas en los últimos 3 días</div>';

    if (in_array(_F_usuario_cache('nivel'), array(_N_agente_sv,_N_agente_us,_N_agente_us_solo)) || isset($_GET['forzar_agente_sv']))
        $where = 'AND ID_usuario = '._F_usuario_cache('ID_usuario');
    else
        $where = '';


    /**************/
    // MySQL
    // Encontrar las aplicaciones que tengan notas en los ultimos 3 días, por orden de aplicacion con nota mas reciente
    $cA3dias = 'SELECT `ID_aplicacion`, nombre, apellido, direccion2, DATE_FORMAT(fecha_ingresada,"%e-%b-%Y %r") AS fecha_ingresada_formato, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario=ID_agente_sv) AS nombre_agente_sv, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario=ID_agente_us) AS nombre_agente_us FROM '.db_prefijo.'historial LEFT JOIN '.db_prefijo.'prospectos_aplicados USING(ID_aplicacion) LEFT JOIN '.db_prefijo.'prospectos USING(ID_prospecto) WHERE fecha > (DATE(NOW()) - INTERVAL 3 DAY) '.$where.' GROUP BY `ID_aplicacion` ORDER BY MAX(`fecha`) DESC';
    $rA3dias = db_consultar($cA3dias);

    //,'<a href="'.PROY_URL.'aplicaciones?a='.$f['ID_aplicacion'].'">ver</a>'

    if (mysql_num_rows($rA3dias))
    {
        echo '<p>Se muestran todas las aplicaciones que han sido actualizadas con notas en los últimos 3 días</p>';
        while ($fA3Dias = mysql_fetch_assoc($rA3dias))
        {
            // Mostrar que aplicación es.
            echo '<table style="table-layout:fixed;" class="tabla-estandar">';
            echo '<tr><th>Nombre del prospecto</th><th>Dirección del prospecto</th><th>Acción</th></tr>';
            echo '<tr><td>'.$fA3Dias['apellido'].', '.$fA3Dias['nombre']. '</td><td>'.$fA3Dias['direccion2'].'</td><td><a href="'.PROY_URL.'aplicaciones?a='.$fA3Dias['ID_aplicacion'].'">Ver aplicación</a></td></tr>';
            echo '<tr><th>Fecha que se ingresó la aplicación</th><th>Agente SV que ingresó el caso</th><th>Agente US que lleva el caso</th></tr>';
            echo '<tr><td>'.$fA3Dias['fecha_ingresada_formato'].'</td><td>'.$fA3Dias['nombre_agente_sv']. '</td><td>'.$fA3Dias['nombre_agente_us'].'</td></tr>';
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
            echo '</table>';
            echo '<hr style="border:2px solid #F00;"/>';
        }
    }
    else
    {
        echo '<p>No se encontraron notas anexas de aplicación</p>';
    }
}

function portada_mostrar_desactualizados()
{
    $WHERE = 'aplicacion_valida="desconocido" AND fecha_ingresada < (DATE(NOW()) - INTERVAL 2 DAY) AND pa.ID_aplicacion NOT IN (SELECT h.ID_aplicacion FROM '.db_prefijo.'historial AS h LEFT JOIN '.db_prefijo.'usuarios USING (ID_usuario) WHERE fecha > (DATE(NOW()) - INTERVAL 2 DAY) AND nivel="'._N_agente_us.'" )';
    switch (_F_usuario_cache('nivel'))
    {
        case _N_administrador_sv:
            $WHERE .= sprintf(' AND ID_agente_sv<>0');
            break;
        case _N_agente_sv:
            $WHERE .= sprintf(' AND ID_agente_sv="%s"', _F_usuario_cache('ID_usuario'));
            break;
        case _N_agente_us:
            $WHERE .= sprintf(' AND ID_agente_us="%s"', _F_usuario_cache('ID_usuario'));
            break;
        case _N_agente_us_solo:
            $WHERE .= sprintf(' AND ID_agente_us="%s"', _F_usuario_cache('ID_usuario'));
            break;
    }
    $c = 'SELECT @fecha := DATE_FORMAT((SELECT MAX(fecha) FROM  '.db_prefijo.'historial AS h WHERE h.ID_aplicacion=pa.ID_aplicacion AND h.ID_usuario=pa.ID_agente_us),"%e-%b-%Y / %r"), IF(@fecha,@fecha,"<span style=\"color:#F00;font-weight:bolder;\">Nunca ha comentado en esta aplicación</span>") AS fecha_ultima_actualizacion, `ID_prospecto`, `apellido`, `nombre`,  pa.`ID_aplicacion`, `ID_agente_sv`, `ID_agente_us`, DATE_FORMAT(`fecha_ingresada`,"%e-%b-%Y / %r") AS "fecha_ingresada_formato" FROM '.db_prefijo.'prospectos_aplicados AS pa LEFT JOIN '.db_prefijo.'prospectos AS p USING (ID_prospecto) WHERE '.$WHERE.' ORDER BY `fecha_ingresada` ASC';
    $r = db_consultar($c);


    echo '<div class="subtitulo">Listado de aplicaciones que necesitan atención</div>';
    if (!mysql_num_rows($r)) {
        echo '<p>¡Felicidades!, ¡todas sus aplicaciones estan actualizadas!</p>';
        return;
    } else {
        echo '<p>Hay <b>'.mysql_num_rows($r).'</b> aplicaciones que no han sido marcadas como válidas o inválidas; y que no han sido actualizadas por el agente US que lleva el caso en los últimos 2 días</p>';
    }

    echo '<table class="tabla-estandar cebra">';
    echo '<tr><th>Fecha de ingreso</th><th>Fecha última actualización por agente US</th><th>Prospecto</th><th>Acción</th></tr>';
    while($f = mysql_fetch_assoc($r))
    {
        echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',$f['fecha_ingresada_formato'],$f['fecha_ultima_actualizacion'],$f['apellido']. ', '. $f['nombre'],'<a href="'.PROY_URL.'aplicaciones?ver='.$f['ID_aplicacion'].'">Ver aplicación</a>');
    }
    echo '</table>';
}
?>
