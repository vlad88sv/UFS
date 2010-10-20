<script type="text/javascript">
$(function($) {
    $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
});
</script>
<?php
protegerme(false,true);
ini_set("memory_limit","128M");
$arrCSS[] = 'css/jquery-ui/jquery-ui-1.8.4.custom';
$arrJS[] = 'jquery.ui.core';
$arrJS[] = 'jquery.ui.datepicker';

/** Para selector (paginador) **/
$sufijo = (strstr(PROY_URL_ACTUAL_DINAMICA,'?')) ? '&' : '?';
$URL = preg_replace('/&?l=[0-9]*&?/','',PROY_URL_ACTUAL_DINAMICA);

function aplicacion_ingresar_nota($ID_aplicacion,$nota,$tipo_nota,$notificar=true)
{
    db_agregar_datos(db_prefijo.'historial',array('tipo' => $tipo_nota, 'fecha' => mysql_datetime(), 'cambio' => $nota, 'ID_aplicacion' => $ID_aplicacion, 'ID_usuario' => _F_usuario_cache('ID_usuario')));

    if ($notificar)
    {
        $cNotificaciones = sprintf('SELECT DISTINCT correo FROM '.db_prefijo.'historial AS h LEFT JOIN '.db_prefijo.'usuarios AS u USING(ID_usuario) WHERE h.ID_usuario<>'._F_usuario_cache('ID_usuario').' AND nivel="'._N_agente_us.'" AND h.ID_aplicacion='.$ID_aplicacion);
        $rNotificaciones = db_consultar($cNotificaciones);

        $mensaje = '
        <p><b>'._F_usuario_cache('nombre').'</b> ha ingresado una nueva nota en una aplicación:</p>
        <p><b>'.$nota.'</b></p>
        <p><a href="'.PROY_URL.'aplicaciones?ver='.$ID_aplicacion.'">Ir a la aplicación</a></p>
        <hr />
        <p>
        <small>
        Ud. ha recibido esta notificación por una de las siguientes causas:
        <ul>
        <li>Ud. es el agente que lleva el caso</li>
        <li>Ud. ha comentando en esta aplicación</li>
        </ul>
        <hr />
        <span style="color:#F00;">
        NO RESPONDA A ESTE CORREO, LOS CORREOS ENVIADOS A '.htmlentities(PROY_MAIL_POSTMASTER).' NO SON REVISADOS.<br />
        En su lugar puede comentar en la aplicación mencionada.
        </span>
        </small>
        </p>
        ';
        while (mysql_num_rows($rNotificaciones) && $f = mysql_fetch_assoc($rNotificaciones))
        {
            correoSMTP($f['correo'],'#'.microtime(true). ' - Nueva nota de '._F_usuario_cache('nombre'),$mensaje);
        }
        correoSMTP('staff@ufsonline.net','#'.microtime(true). ' - Nueva nota de '._F_usuario_cache('nombre'),$mensaje);
    }
}

if (isset($_GET['instrucciones']))
{
    require_once('php/_aplicaciones_introduccion.php');
    return;
}

if (isset($_POST['ID_prospecto']) && isset($_POST['ID_aplicacion'])):

if (isset($_POST['asignar_bono_agente_sv']) && _F_usuario_cache('nivel') == _N_administrador_sv)
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('bono_agente_sv' => (isset($_POST['bono_agente_sv']) ? '1' : '0')),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');
}

if (isset($_POST['alertar_agente_us']))
{
    $correo = db_obtener(db_prefijo.'usuarios','correo','ID_usuario = (SELECT ID_agente_us FROM '.db_prefijo.'prospectos_aplicados WHERE ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'")');

    if ($correo)
    {
        $nota = 'Se ha enviado una notificación al agente US sobre este caso';
        aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);
        $mensaje='<p>Por favor revisar la siguiente aplicación:<br /><a href="'.PROY_URL.'aplicaciones?a='.$_POST['ID_aplicacion'].'">ir a aplicación</a></p><p>Es posible que tenga nuevas notas anexas o que un administrador solicite tu atención sobre esta aplicación</p>';
        correoSMTP($correo,'#'.time().' - Atención sobre aplicación con ID. '.$_POST['ID_aplicacion'],$mensaje,true);
        echo jQuery('alert("Alerta enviada exitosamente a '.$correo.'.");');
    }
    else
    {
        echo jQuery('alert("No hay agente US asignado a este caso o el asignado no posee correo electrónico");');
    }
}

if (isset($_POST['enviar']))
{
    if(isset($_POST['aplicacion_notas']))
    {
        db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('notas' => $_POST['aplicacion_notas']),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');
    }
    enviar_prospecto($_POST['ID_aplicacion'],$_POST['ID_prospecto']);
}

if (isset($_POST['omitir_envio']))
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('enviado' => mysql_datetime()),'ID_aplicacion="'.$_POST['ID_aplicacion'].'"');
}

if (isset($_POST['eliminar']))
{
    $c = 'DELETE FROM '.db_prefijo.'prospectos_aplicados WHERE ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"';
    db_consultar($c);
}

if (isset($_POST['asignar']))
{
    // FIXME: atomizar
    $test = db_obtener(db_prefijo.'prospectos_aplicados','ID_agente_us','ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');
    if ($test)
    {
        echo jQuery('alert("Lo siento, alguien mas tomó esta aplicación antes que tu.");');
    } else {
        db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('ID_agente_us' => _F_usuario_cache('ID_usuario'), 'fecha_aceptada' => mysql_datetime()),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');

        $nota = 'El agente le dará seguimiento a este prospecto';
        aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);

        echo jQuery('alert("Ud. llevará el caso de la aplicación con ID #'.$_POST['ID_aplicacion'].'.");');
    }
}

if (isset($_POST['aplicacion_resignada']))
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('ID_agente_us' => 0, 'fecha_aceptada' => mysql_datetime()),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');

    $nota = 'El agente US <b>'._F_usuario_cache('nombre').'</b> se ha resignado con esta aplicación.';
    aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);

}
if (isset($_POST['aplicacion_valida']))
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('aplicacion_valida' => 'valida'),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');

    $nota = 'La aplicación se ha considerado como válida. **POSIBLE NEGOCIO**';
    aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);

    //...y falsificamos una nota anexa si hay algo escrito
    $_POST['anexar_nota'] = true;

    echo jQuery('alert("Aplicación #'.$_POST['ID_aplicacion'].' se ha marcado como VÁLIDA.");');
}

if (isset($_POST['aplicacion_invalida']))
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('aplicacion_valida' => 'invalida'),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');

    $nota = 'La aplicación se ha considerado como **INVALIDA**';
    aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);

    //...y falsificamos una nota anexa si hay algo escrito
    $_POST['anexar_nota'] = true;

    echo jQuery('alert("Aplicación #'.$_POST['ID_aplicacion'].' se ha marcado como INVÁLIDA.");');
}

if (isset($_POST['aplicacion_imposible']))
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('aplicacion_valida' => 'imposible'),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');

    $nota = 'La aplicación se ha considerado como **IMPOSIBLE** - El prospecto no califica para nuestros servicios';
    aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);

    //...y falsificamos una nota anexa si hay algo escrito
    $_POST['anexar_nota'] = true;
}

if (isset($_POST['aplicacion_vendida']))
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('aplicacion_valida' => 'vendida'),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');

    $nota = '**La aplicación se ha vendido**';
    aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);

    //...y falsificamos una nota anexa si hay algo escrito
    $_POST['anexar_nota'] = true;
}

if (isset($_POST['vigilar']))
{
    db_agregar_datos(db_prefijo.'prospectos_aplicados_vigilados',array('ID_aplicacion' => $_POST['ID_aplicacion'], 'ID_usuario' => _F_usuario_cache('ID_usuario')));
}

if (isset($_POST['desvigilar']))
{
    $c = 'DELETE FROM '.db_prefijo.'prospectos_aplicados_vigilados WHERE ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_usuario="'._F_usuario_cache('ID_usuario').'"';
    db_consultar($c);
}

if (isset($_POST['asignar_agente']) && isset($_POST['ID_agente_us']))
{
    $f = db_obtener_fila(db_prefijo.'usuarios','ID_usuario',$_POST['ID_agente_us']);
    $nota = 'Se ha asignado el agente UFS US <b>'.$f['nombre'].'</b> a esta aplicación';

    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('ID_agente_us' => $_POST['ID_agente_us'], 'fecha_aceptada' => mysql_datetime()),'ID_aplicacion="'.db_codex($_POST['ID_aplicacion']).'" AND ID_prospecto="'.db_codex($_POST['ID_prospecto']).'"');
    db_agregar_datos(db_prefijo.'historial',array('tipo' => _T_historial_sistema, 'fecha' => mysql_datetime(), 'cambio' => $nota, 'ID_aplicacion' => $_POST['ID_aplicacion'], 'ID_usuario' => _F_usuario_cache('ID_usuario')));

    correo($f['correo'],'Le han asignado una nueva aplicación',enviar_prospecto($_POST['ID_aplicacion'],$_POST['ID_prospecto'],true).'<hr /><a href="'.PROY_URL_ACTUAL.'?ver='.$_POST['ID_aplicacion'].'">Ir a la aplicación</a>');

    //TODO: enviar mensajito.us
}

if (isset($_POST['recordatorio']) && isset($_POST['notas']))
{
    $fecha = date('Y-m-d',strtotime(str_replace('/', '-', $_POST['fecha']))).' '.$_POST['hora'];

    db_agregar_datos(db_prefijo.'prospectos_aplicados_recordatorio',array('ID_usuario' => _F_usuario_cache('ID_usuario'),'ID_aplicacion' => $_POST['ID_aplicacion'], 'fecha' => $fecha, 'nota' => $_POST['notas']));

    // Un historial para decir que puso un recordatorio
    $nota = "Recordatorio establecido para $fecha";
    aplicacion_ingresar_nota($_POST['ID_aplicacion'],$nota,_T_historial_sistema);

    //...y falsificamos una nota anexa si hay algo escrito
    $_POST['anexar_nota'] = true;
}

if (isset($_POST['anexar_nota']) && !empty($_POST['notas']))
{
    aplicacion_ingresar_nota($_POST['ID_aplicacion'],$_POST['notas'],_T_historial_comentario);
    echo jQuery('alert("Se anexó correctamente la nota a la aplicación con ID #'.$_POST['ID_aplicacion'].'.");');
}

endif; // IF ID_prospecto+ID_aplicacion

$buffer = '';
$total = $comision = $i = 0;
$WHERE = '';

if(isset($_GET['pendientes']))
    $WHERE = 'AND ID_agente_us=0';

if(isset($_GET['ver']))
    $WHERE = sprintf('AND pa.ID_aplicacion="%s"',db_codex($_GET['ver']));

if(isset($_GET['a']))
    $WHERE = sprintf('AND pa.ID_aplicacion="%s"',db_codex($_GET['a']));

if(isset($_GET['a']) && isset($_GET['r']))
{
    // Dejamos constancia del recordatorio
    db_consultar('UPDATE '.db_prefijo.'prospectos_aplicados_recordatorio SET fecha_visto=NOW() WHERE ID_recordatorio="'.db_codex($_GET['r']).'" AND ID_aplicacion="'.db_codex($_GET['a']).'"');
    // Un historial para decir que vió su recordatorio
    $nota = "Recordatorio fue visto";
    db_agregar_datos(db_prefijo.'historial',array('tipo' => _T_historial_sistema, 'fecha' => mysql_datetime(), 'cambio' => $nota, 'ID_aplicacion' => $_GET['a'], 'ID_usuario' => _F_usuario_cache('ID_usuario')));
    // Para evitar loops
    header('Location: '.PROY_URL.'aplicaciones?a='.db_codex($_GET['a']));
    return;
}

if(isset($_GET['positivas']))
    $WHERE .= sprintf(' AND (aplicacion_valida="desconocido" OR aplicacion_valida="valida" OR aplicacion_valida="vendida")');

if(isset($_GET['asv']))
    $WHERE .= sprintf(' AND ID_agente_sv="%s"', db_codex($_GET['asv']));

if(isset($_GET['aus']))
    $WHERE .= sprintf(' AND ID_agente_us="%s"', db_codex($_GET['aus']));

if(isset($_GET['asignadas']))
    $WHERE .= sprintf(' AND ID_agente_us="%s"', _F_usuario_cache('ID_usuario'));

if(isset($_GET['vigiladas']))
    $WHERE = 'AND pav.ID';

if(isset($_GET['desactualizadas']))
    $WHERE .= ' AND aplicacion_valida="desconocido" AND fecha_ingresada < (DATE(NOW()) - INTERVAL 3 DAY) AND pa.ID_aplicacion NOT IN (SELECT h.ID_aplicacion FROM '.db_prefijo.'historial AS h LEFT JOIN '.db_prefijo.'usuarios USING (ID_usuario) WHERE fecha > (DATE(NOW()) - INTERVAL 3 DAY) AND nivel="'._N_agente_us.'" )';

if(isset($_GET['validas']))
    $WHERE .= ' AND aplicacion_valida="valida"';

if(isset($_GET['invalidas']))
    $WHERE .= ' AND aplicacion_valida="invalida"';

if(isset($_GET['esperando']))
    $WHERE .= ' AND aplicacion_valida="desconocido"';

if(isset($_GET['vendidas']))
    $WHERE .= ' AND aplicacion_valida="vendida"';

if(isset($_GET['cerradas']))
    $WHERE = 'AND fecha_cerrada';

if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_final']))
    $WHERE .= sprintf(' AND DATE(fecha_ingresada) BETWEEN "%s" AND "%s"',mysql_date(str_replace('/','-',$_GET['fecha_inicio'])),mysql_date(str_replace('/','-',$_GET['fecha_final'])));

if(isset($_GET['fecha_ingresada']))
    $WHERE .= sprintf(' AND DATE(fecha_ingresada)="%s"',mysql_date(str_replace('/','-',$_GET['fecha_ingresada'])));

if (isset($_GET['grupo']))
    $WHERE .= sprintf(' AND ID_agente_sv IN (SELECT ID_usuario FROM '.db_prefijo.'usuarios WHERE ID_supervisor=(SELECT ID_supervisor FROM '.db_prefijo.'supervisores WHERE ID_usuario=%s)) ', _F_usuario_cache('ID_usuario'));

if (isset($_GET['supervisor']))
    $WHERE .= sprintf(' AND ID_agente_sv IN (SELECT ID_usuario FROM '.db_prefijo.'usuarios WHERE ID_supervisor='.$_GET['supervisor'].') ', _F_usuario_cache('ID_usuario'));

switch (_F_usuario_cache('nivel'))
{
    /*
    case _N_administrador_sv:
        $WHERE .= sprintf(' AND ID_agente_sv IN (SELECT ID_usuario FROM '.db_prefijo.'usuarios WHERE ID_supervisor=(SELECT ID_supervisor FROM '.db_prefijo.'supervisores WHERE ID_usuario=%s)) ', _F_usuario_cache('ID_usuario'));
        break;
    */
    case _N_administrador_sv:
        $WHERE .= sprintf(' AND ID_agente_sv<>0');
        break;
    case _N_agente_sv:
        $WHERE .= sprintf(' AND ID_agente_sv="%s"', _F_usuario_cache('ID_usuario'));
        break;
    case _N_agente_us:
        $WHERE .= sprintf(' AND (ID_agente_us=0 OR ID_agente_us="%s")', _F_usuario_cache('ID_usuario'));
        break;
    case _N_agente_us_solo:
        $WHERE .= sprintf(' AND ID_agente_us="%s"', _F_usuario_cache('ID_usuario'));
        break;
}

if (!isset($_GET['l']) || !is_numeric($_GET['l']))
    $_GET['l'] = 0;

$LIMIT = 'LIMIT '.$_GET['l'].',1';

$c = 'SELECT SQL_CALC_FOUND_ROWS `ID_prospecto`, `situacion`, `ultima_presentacion`, `intentos`, `apellido`, `nombre`, `direccion2`, `ciudad`, `estado`, `zip`, `telefono`, `especial2`, `especial3`,  `especial5`, `especial6`, `especial7`, pa.`ID_aplicacion`, `ID_agente_sv`, `ID_agente_us`, `enviado`, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_sv`) AS nombre_agente_sv, (SELECT local FROM '.db_prefijo.'supervisores WHERE ID_supervisor = (SELECT ID_supervisor FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_sv`)) AS grupo, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_us`) AS nombre_agente_us, DATE_FORMAT(`fecha_ingresada`,"%e-%b-%Y<br />%r") AS "fecha_ingresada_formato", DATE_FORMAT(`fecha_aceptada`,"%e-%b-%Y<br />%r") AS "fecha_aceptada", DATE_FORMAT(`fecha_cerrada`,"%e-%b-%Y<br />%r") AS "fecha_cerrada", `comision_agente_sv`, `comision_agente_us`, `comsion_ufs_sv`, `comision_ufs_us`, `notas`, `interes`, IF(pav.`ID`, "si", "no") AS "vigilado", pa.`aplicacion_valida`, pa.`bono_agente_sv`, p.`tipo` FROM ('.db_prefijo.'prospectos_aplicados AS pa LEFT JOIN '.db_prefijo.'prospectos_aplicados_vigilados AS pav ON pav.ID_usuario='._F_usuario_cache('ID_usuario').' AND pav.`ID_aplicacion`=pa.`ID_aplicacion`) LEFT JOIN '.db_prefijo.'prospectos AS p USING (ID_prospecto) WHERE 1 '.$WHERE.' ORDER BY `fecha_ingresada` ASC '.$LIMIT;
$r = db_consultar($c);

$rPagAplicaciones = mysql_fetch_assoc(db_consultar("SELECT FOUND_ROWS() AS cuenta"));
$nAplicaciones = $rPagAplicaciones['cuenta'];


if (!mysql_num_rows($r) && isset($_GET['correo'])) {
    echo '<p style="font-size:1.1em;color:#00F;">Lo siento, alguien mas ha tomado ya esta aplicación.</p>';
    return;
} elseif (!mysql_num_rows($r)) {
    echo '<p>No se encontraron aplicaciones que coincidieran con el filtro de búsqueda especificado.</p>';
}

if (isset($_POST['lote']))
    enviar_lote($r);

while($f = mysql_fetch_assoc($r))
{
    // Obtener todo el historial para este aplicación
    $bHistorial = '';
    $cHistorial = 'SELECT DATE_FORMAT(fecha,"%e-%b-%Y<br />%r") AS "fecha_formato", ID_usuario,  nombre, cambio, tipo, nivel FROM '.db_prefijo.'historial LEFT JOIN '.db_prefijo.'usuarios USING (ID_usuario) WHERE ID_aplicacion="'.$f['ID_aplicacion'].'" ORDER BY `fecha` ASC';
    $rHistorial = db_consultar($cHistorial);

    if(mysql_num_rows($rHistorial))
    {
        $bHistorial = '<div class="subtitulo">Notas anexas a esta aplicación</div><table class="tabla-estandar mini"><tr><th>Fecha</th><th>Agente/Supervisor</th><th>Cambio</th></tr>';
        while($fHistorial = mysql_fetch_assoc($rHistorial))
        {
            if (in_array($fHistorial['nivel'],array(_N_administrador_sv,_N_administrador_us)))
                $fHistorial['nombre'] = '<span style="font-weighadmit:bolder;color:#F00;">'.$fHistorial['nombre'].'</span>';
            $bHistorial .= sprintf('<tr class="historial-tipo-'.$fHistorial['tipo'].'"><td>%s</td><td>%s</td><td>%s</td></tr>',$fHistorial['fecha_formato'],$fHistorial['nombre'],$fHistorial['cambio']);
        }
        $bHistorial .= '</table>';
    }

    $i++;

    switch ($f['aplicacion_valida'])
    {
        case "valida":
            $clase_aplicacion = 'tabla-aplicacion-valida';
            break;
        case "invalida":
            $clase_aplicacion = 'tabla-aplicacion-invalida';
            break;
        case "imposible":
            $clase_aplicacion = 'tabla-aplicacion-imposible';
            break;
        case "vendida":
            $clase_aplicacion = 'tabla-aplicacion-vendida';
            break;
        default:
        $clase_aplicacion = 'tabla-aplicacion';
    }

    // Formateemos el telefono segun locale
    if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_us,_N_agente_us,_N_agente_us_solo)))
        $f['telefono'] = preg_replace(array('/[^\d]/','/^(\d{3})(\d{3})(\d{4})$/'),array('','$1-$2-$3'),$f['telefono']);
    else
        $f['telefono'] = preg_replace(array('/[^\d]/','/^(\d{10})$/','/^(\d{1})(\d{3})(\d{3})(\d{4})$/'),array('','1$1','$1-$2-$3-$4'),$f['telefono']);

    $datos = '
    <table class="tabla-estandar '.$clase_aplicacion.'">
    <tr><th>Agente [SV]</th><th>Agente [US]</th><th>Fecha ingresada</th><th>Fecha aceptada</th><th>Fecha cerrada</th></tr>
    <tr>
    <td>'.$f['nombre_agente_sv'].'<br />['.$f['grupo'].']</td>
    <td>'.$f['nombre_agente_us'].'</td>
    <td>'.$f['fecha_ingresada_formato'].'</td>
    <td>'.$f['fecha_aceptada'].'</td>
    <td>'.$f['fecha_cerrada'].'</td>
    </tr>
    <tr>
        <th>Owner</th><th>Phone</th><th>Address</th><th>Zip Code</th><th>City</th>
    </tr>
    <tr>
        <td>'.$f['apellido'] . ', ' . $f['nombre'].'</td>
        <td>'.$f['telefono'].'</td>
        <td>'.$f['direccion2'].'</td>
        <td>'.$f['zip'].'</td>
        <td>'.$f['ciudad'].'</td>
    </tr>
    ';

    if ($f['tipo'] == 'full')
    $datos .= '
    <tr>
        <th>Mrtg date</th><th>Sales price</th><th>Mrtg Amt</th><th>Lender</th><th>Mrtg Rate</th>
    </tr>
    <tr>
        <td>'.$f['especial2'].'</td>
        <td>'.'$'. @number_format(preg_replace('/[^\d]/','',$f['especial3']),2,'.',',').'</td>
        <td>'.'$'. @number_format(preg_replace('/[^\d]/','',$f['especial5']),2,'.',',').'</td>
        <td>'.$f['especial6'].'</td>
        <td>'.$f['interes'] . '% / ' . $f['especial7'].'</td>
    </tr>
    ';

    $datos .= '
    <tr>
    <th colspan="5">Nota ingresada por el agente</th>
    </tr>
    <tr>
    <td colspan="5" style="height:11em;">
    <textarea style="width:100%;height:10em;display:block;" readonly="readonly" name="aplicacion_notas">'.$f['notas'].'</textarea>
    </td>
    </tr>

    </table>
    '.$bHistorial.'
    ';

    $buffer .= '<hr style="border:1px dotted #F00;"/>';
    $buffer .= '<form action="'.PROY_URL_ACTUAL_DINAMICA.'#a'.$f['ID_aplicacion'].'" method="post"><div class="unit">';
    $buffer .= '
    <input name="ID_aplicacion" value="'.$f['ID_aplicacion'].'" type="hidden"/>
    <input name="ID_prospecto" value="'.$f['ID_prospecto'].'" type="hidden"/>
    ';

    $vigilar = '';
    if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv,_N_administrador_us)))
    {
        if ($f['vigilado'] == 'no')
            $vigilar = '<input name="vigilar" type="submit" value="Establecer vigilancia" />';
        else
            $vigilar = '<input name="desvigilar" type="submit" value="Quitar vigilancia" />';
    }

    if (!isset($_GET['aplicaciones_mostrar_incrustada'])):
    $mini_aplicacion = '<a target="_blank" href="'.PROY_URL.'aplicaciones_miniapp?p='.$f['ID_prospecto'].'&a='.$f['ID_aplicacion'].'">Ver aplicación preliminar</a>';
    $buffer .= '<div class="mini">Enlace permanente a esta aplicación: <a name="a'.$f['ID_aplicacion'].'" id="a'.$f['ID_aplicacion'].'" href="'.PROY_URL_ACTUAL.'?ver='.$f['ID_aplicacion'].'">ID de aplicación: '.$f['ID_aplicacion'].'</a> - <a href="'.PROY_URL.'prospectos?p='.$f['ID_prospecto'].'">ID de prospecto: ' . $f['ID_prospecto'] . '</a><div style="float:right;">' . $vigilar .'&nbsp;' . $mini_aplicacion . '</div></div>';
    endif;
    $buffer .= $datos;
    if (_F_usuario_cache('nivel') == _N_administrador_sv && $f['enviado'] == "0000-00-00 00:00:00" && empty($_GET['export']))
    {
        $buffer .= '<table class="tabla-estandar">';
        $buffer .= '<tr><td><input name="enviar" type="submit" value="Enviar" /> - Último envío: '.$f['enviado'].'</td><td style="text-align:center;"><input name="omitir_envio" type="submit" value="Omitir envío" /></td><td style="text-align:right;"><input name="eliminar" type="submit" value="Eliminar" /></td></tr>';
        $buffer .= '</table>';
    }

    // Si es agente_sv o agente_us_solo, las que esta viendo solo pueden ser de el, por lo que puede comentar en todas.
    // Si es agente_us permitirle anexar notas solo si ya lo tomó, si no solo darle la opción de tomarlo.
    // Si es administrador permitir siempre
    if (in_array(_F_usuario_cache('nivel'), array(_N_agente_sv,_N_agente_us,_N_administrador_sv,_N_administrador_us,_N_agente_us_solo)))
    {
        if ( ((_F_usuario_cache('nivel') == _N_agente_us)) && !$f['ID_agente_us'])
            $buffer .= '<input name="asignar" type="submit" value="Tomar aplicación" />';
        elseif (_F_usuario_cache('nivel') != _N_agente_us || ((_F_usuario_cache('nivel') == _N_agente_us)) && $f['ID_agente_us'] )
        {
            $buffer .= '<div style="background-color:#EEE;font-size:0.8em;text-align:center;">Anexar nota a esta aplicación</div>
            <textarea name="notas" style="width:99%;margin:auto;display:block"></textarea>
            <input type="submit" name="anexar_nota" value="Anexar Nota" /> - o - <input type="submit" name="recordatorio" value="Anexar nota y establecer recordatorio" /> <span class="mini">Fecha:</span> <input name="fecha" class="datepicker" type="text" value="'.date('d/m/Y').'" /> <span class="mini">Hora:</span> <select name="hora"><option value="09:00:00">9:00a.m.</option><option value="09:30:00">09:30a.m.</option><option value="10:00:00">10:00a.m.</option><option value="10:30:00">10:30a.m.</option><option value="11:00:00">11:00a.m.</option><option value="11:30:00">11:30a.m.</option><option value="12:00:00">12:00p.m.</option><option value="12:30:00">12:30p.m.</option><option value="13:00:00" selected="selected">1:00p.m.</option><option value="13:30:00">1:30p.m.</option><option value="14:00:00">2:00p.m.</option><option value="14:30:00">2:30p.m.</option><option value="15:00:00">3:00p.m.</option><option value="15:30:00">3:30p.m.</option><option value="16:00:00">4:00p.m.</option><option value="16:30:00">4:30p.m.</option><option value="17:00:00">5:00p.m.</option><option value="17:30:00">5:30p.m.</option><option value="18:00:00">6:00p.m.</option><option value="18:30:00">6:30p.m.</option><option value="19:00:00">7:00p.m.</option><option value="19:30:00">7:30p.m.</option><option value="20:00:00">8:00p.m.</option><option value="20:30:00">8:30p.m.</option><option value="21:00:00">9:00p.m.</option></select>';

            if (!isset($_GET['aplicaciones_mostrar_incrustada']) && in_array(_F_usuario_cache('nivel'), array(_N_agente_us,_N_administrador_sv,_N_administrador_us)))
            {
                $buffer .= '<hr /><div style="background-color:#EEE;font-size:0.8em;text-align:center;">Control de aplicación</div>';
                $buffer .= '<input type="submit" name="aplicacion_valida" value="Marcar como aplicación válida" title="Se logró hablar con el prospecto y estaba interesado realmente en nuestros servicios" />';
                $buffer .= '<input type="submit" name="aplicacion_invalida" value="Marcar como aplicación **NO** válida" title="Se logró hablar con el prospecto pero fue una aplicación no productiva o la información ingresada por el agente no es correcta" />';
                $buffer .= '<input type="submit" name="aplicacion_imposible" value="El prospecto no califica para nuestro servicio" title="La aplicación es válida pero el prospecto no califica para nuestros servicios" />';
                $buffer .= '<input type="submit" name="aplicacion_vendida" value="Se logro realizar la venta" title="La venta de algun producto se realizó" />';
                if (in_array(_F_usuario_cache('nivel'), array(_N_agente_us,_N_agente_us_solo)))
                {
                    $buffer .= '<br />';
                    $buffer .= '<input type="submit" name="aplicacion_resignada" value="Resignar aplicación" title="HAN LLAMADO 4-5 VECES A UN PROSPECTO ENVIADO DE E.S. Y NO HAN TENIDO EXITO EN CONTACTARSE CON LA PERSONA INDICADA"/>';
                }
            }
        }

        /**********************************************************************/
        /*
            Control de agentes US
            Permisos:sus+ssv
            Logica:
                Permitir asignar o cambiar un agente US para llevar la aplicación.
                Ofrecer un medio por el cual alertar al agente US de que la aplicación ha cambiado y necesita su atención
        */
        if (!isset($_GET['aplicaciones_mostrar_incrustada']) && in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv,_N_administrador_us)))
        {
            $ops = db_ui_opciones('ID_usuario','nombre',db_prefijo.'usuarios','WHERE nivel="'._N_agente_us.'"','ORDER BY nombre ASC');
            $buffer .= '<hr />';
            $buffer .= '<div style="background-color:#EEE;font-size:0.8em;text-align:center;">Operaciones con agente US</div>';
            $buffer .= 'Asignar o cambiar agente US '.ui_combobox('ID_agente_us',$ops).'<input name="asignar_agente" type="submit" value="Asignar" />';
            $buffer .= '<input style="float:right;" name="alertar_agente_us" type="submit" value="Alertar agente US actual sobre esta aplicación" />';
        }
        /**********************************************************************/

        /**********************************************************************/
        /*
            Control de agentes SV
            Permisos:ssv+asv
            Logica:
                Permitir marcar si el agente SV recibió ya bono por esta aplicación.
                Permitirle al agente SV conocer si ya recibió su bono pero no poder cambiar el estado.
        */
        if (!isset($_GET['aplicaciones_mostrar_incrustada']) && in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv,_N_agente_sv)))
        {
            $ops = db_ui_opciones('ID_usuario','nombre',db_prefijo.'usuarios','WHERE nivel="'._N_agente_us.'"','ORDER BY nombre ASC');
            $buffer .= '<hr />';
            $buffer .= '<div style="background-color:#EEE;font-size:0.8em;text-align:center;">Operaciones con agente SV</div>';
            $buffer .= 'El agente ha recibido bono de esta aplicación '.ui_input('bono_agente_sv','1','checkbox','','',($f['bono_agente_sv'] ? 'checked="checked"' : ''));
            if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv)))
                $buffer .= '<input name="asignar_bono_agente_sv" type="submit" value="Guardar" />';
        }
        /**********************************************************************/

    }
    $buffer .= '</div>';
    $buffer .= '</form>';
}

if (!isset($_GET['aplicaciones_mostrar_incrustada'])):
echo "<h1>".PROY_NOMBRE_CORTO." - historial de aplicaciones</h1>";
echo '<table id="tabla-ventas" class="tabla-estandar">';
echo '<tr>
<th>Aplicaciones encontradas</th>
<th>Aplicaciones ingresadas en</th>
<th>Aplicaciones ingresada en rango de fecha</th></tr>';
echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $nAplicaciones, '<a href="'.PROY_URL_ACTUAL.'?fecha_ingresada=-2 day">Anteayer</a> / <a href="'.PROY_URL_ACTUAL.'?fecha_ingresada=-1 day">Ayer</a> / <a href="'.PROY_URL_ACTUAL.'?fecha_ingresada=now">Hoy</a> | Otro día: <form style="display:inline" method="get" action="'.PROY_URL_ACTUAL.'"><input name="fecha_ingresada" type="text" class="datepicker" value="'.date('j/n/Y').'" /><input type="submit" value="Ir" class="ir"/></form> | <a href="'.PROY_URL_ACTUAL.'">Todas</a>','<form style="display:inline" method="get" action="'.PROY_URL_ACTUAL.'"> Del <input name="fecha_inicio" type="text" class="datepicker" value="'.date('j/n/Y').'" /> al <input name="fecha_final" type="text" class="datepicker" value="'.date('j/n/Y').'" /><input type="submit" value="Ir" class="ir"/></form>');
echo '</table>';
endif;

$selector = '';
if ($nAplicaciones > 1 && $_GET['l'] > 0)
    $selector .= ' <a href="'.$URL.$sufijo.'l='.($_GET['l']-1).'">Aplicación anterior</a> |';

if ($nAplicaciones > 1 && $_GET['l'] < $nAplicaciones-1)
    $selector .= ' <a href="'.$URL.$sufijo.'l='.($_GET['l']+1).'">Aplicación siguiente</a>';

if ($nAplicaciones>1)
    echo '<p class="medio-oculto">Páginas:'.$selector.'</p>';

echo $buffer;

$selector = '';


if ($nAplicaciones > 1 && $_GET['l'] > 0)
    $selector .= ' <a href="'.$URL.$sufijo.'l='.($_GET['l']-1).'">≪</a>';

for($i=0;$i<$nAplicaciones;$i++)
    $selector .= ' <a '.($i == $_GET['l'] ? 'style="font-weight:bolder;"' : '').' href="'.$URL.$sufijo.'l='.$i.'">'.$i.'</a>';

if ($nAplicaciones > 1 && $_GET['l'] < $nAplicaciones-1)
    $selector .= ' <a href="'.$URL.$sufijo.'l='.($_GET['l']+1).'">≫</a>';

if ($nAplicaciones>1)
    echo '<p class="medio-oculto">Páginas:'.$selector.'</p>';
/*
if (_F_usuario_cache('nivel') == _N_administrador_sv && empty($_GET['export']) && !isset($_GET['aplicaciones_mostrar_incrustada']))
{
    echo '<hr />';
    echo '<form action="'.PROY_URL_ACTUAL_DINAMICA.'" method="post"><input type="submit" name="lote" value="Enviar todo lo visible a mi correo" /></form>';
}
*/
?>
