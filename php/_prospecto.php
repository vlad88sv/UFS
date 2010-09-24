<script type="text/javascript">
$(function($) {
    $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
    $("#nuevo_prospecto").fadeOut(4000);
});

function AprobarRecordatorio()
{
    fecha=$('#fecha').val().split('/');
    x=(Date.parse(fecha[1]+'/'+fecha[0]+'/'+fecha[2]+' '+$('#hora :selected').val()) > Date.parse(Date()));
    if(!x)
    {
        alert('La fecha del recordatorio tiene que ser en el futuro.');
        return (x);
    }
    return confirm('¿Establecer recordatorio para el '+$('#fecha').val()+' a las '+$('#hora :selected').text()+'?');

}
</script>
<?php
protegerme(false,true);
$arrCSS[] = 'css/jquery-ui/jquery-ui-1.8.4.custom';
$arrJS[] = 'jquery.ui.core';
$arrJS[] = 'jquery.ui.datepicker';

function _prospecto_tabla_botones()
{
?>
<table id="tabla-botones-prospectos">
    <tr>
        <td><input type="submit" name="si" value="Si le interesó"  onclick="return confirm('¿Esta seguro que este cliente esta interesado?.\nRecuerde haber anotado lo siguiente:\n* Deudas que tiene el cliente (tarjetas/casa)\n*Porcentaje de interés')"/><p class="mini">El prospecto demostró total interés por lo ofrecido y está listo para ser contactado por un analista financiero de UFS USA.</p></td>
        <td><input type="submit" name="no" value="No le interesó" /><p class="mini">El prospecto no mostró interés en el servicio y rechazó el analisis financiero gratuito.</p></td>
        <td><input type="submit" name="recordatorio" value="Recordatorio" onclick="return AprobarRecordatorio()" /><table class="tabla-estandar mini" style="margin:auto;"><tr><td>Fecha:</td><td><input id="fecha" name="fecha" class="datepicker" type="text" value="<?php echo date('d/m/Y'); ?>" /></td></tr><tr><td>Hora:</td><td><select id="hora" name="hora"><option value="09:00:00">9:00a.m.</option><option value="09:30:00">09:30a.m.</option><option value="10:00:00">10:00a.m.</option><option value="10:30:00">10:30a.m.</option><option value="11:00:00">11:00a.m.</option><option value="11:30:00">11:30a.m.</option><option value="12:00:00">12:00p.m.</option><option value="12:30:00">12:30p.m.</option><option value="13:00:00" selected="selected">1:00p.m.</option><option value="13:30:00">1:30p.m.</option><option value="14:00:00">2:00p.m.</option><option value="14:30:00">2:30p.m.</option><option value="15:00:00">3:00p.m.</option><option value="15:30:00">3:30p.m.</option><option value="16:00:00">4:00p.m.</option><option value="16:30:00">4:30p.m.</option><option value="17:00:00">5:00p.m.</option><option value="17:30:00">5:30p.m.</option><option value="18:00:00">6:00p.m.</option><option value="18:30:00">6:30p.m.</option><option value="19:00:00">7:00p.m.</option></select></td></tr></table><p class="mini">El <span style="font-weight:bold;color:#F00;">prospecto</span> pudo ser contactado y especificó que desea ser contactado por Ud. en la fecha y hora especificada.</p></td>
        <td><input type="submit" name="reciclar" value="No contestó" /><p class="mini">La llamada no pudo ser enlazada, mensaje de contestadora <span style="color:#F00;">o se habló con alguien que no era el OWNER y no les proporcionó hora para llamar</span>.</p></td>
        <td><input type="submit" name="descartar" value="Descartar"/><p class="mini">Número equivocado, inválido, desconectado, información incorrecta, FAX.</p></td>
    </tr>
</table>
<?php
}

function _prospecto_guardar_encuesta()
{
    $arrDB = array_flip(array('generales_fecha_compra', 'generales_sabe_cuanto_cuesta', 'generales_cuanto_cuesta', 'generales_bancarrota_12_meses', 'generales_tipo_bancarrota_7', 'generales_tipo_bancarrota_11', 'generales_tipo_bancarrota_13', 'generales_modificacion_anterior', 'generales_modificacion_hace_cuanto', 'generales_nuevos_terminos_de_modificacion', 'generales_proceso_modificacion', 'generales_fecha_proceso_modificacion', 'generales_modificacion_pago', 'generales_hizo_auditoria', 'generales_hubo_notario', 'generales_tenia_licencia', 'generales_explicaron_terminos_prestamo', 'generales_hizo_npv', 'generales_cuantas_propiedades', 'tarjeta_tiene', 'tarjeta_cuantas', 'tarjeta_7500', 'tarjeta_fecha_fin_deudas', 'tarjeta_pago_mensual', 'tarjeta_cuanto', 'tarjeta_meta', 'empleo_tipo', 'empleo_compania', 'empleo_tiempo', 'hipoteca_banco', 'hipoteca_interes', 'hipoteca_pago_mensual', 'hipoteca_balance'));
    $DATOS = array_intersect_key($_POST,$arrDB);
    $DATOS['ID_prospecto'] = db_codex($_POST['ID_prospecto']);

    db_reemplazar_datos(db_prefijo.'prospectos_encuesta',$DATOS);
}

// Procesar POST
if (isset($_POST['ID_prospecto']))
{
    if (isset($_POST['si']))
    {

        echo '<p>Prospecto guardado</p>';
        if (in_array(_F_usuario_cache('nivel'),array(_N_agente_sv,_N_administrador_sv)))
            $arr = array('ID_prospecto' => $_POST['ID_prospecto'], 'ID_agente_sv' => _F_usuario_cache('ID_usuario'), 'fecha_ingresada' => mysql_datetime(), 'notas' => $_POST['notas']);
        elseif (in_array(_F_usuario_cache('nivel'),array(_N_agente_us,_N_agente_us_solo)))
            $arr = array('ID_prospecto' => $_POST['ID_prospecto'], 'ID_agente_us' => _F_usuario_cache('ID_usuario'), 'fecha_ingresada' => mysql_datetime(), 'fecha_aceptada' => mysql_datetime(), 'notas' => $_POST['notas']);

        $i = db_agregar_datos(db_prefijo.'prospectos_aplicados',$arr);
        unset($arr);
        db_actualizar_datos(db_prefijo.'prospectos',array('situacion' => 'aplicado'),'ID_prospecto='.$_POST['ID_prospecto']);

        // Guardemos la encuesta
        _prospecto_guardar_encuesta();

/**/
        if (in_array(_F_usuario_cache('nivel'),array(_N_agente_sv,_N_administrador_sv)));
            enviar_prospecto($i,$_POST['ID_prospecto']);
/**/
        header('Location: '.PROY_URL_ACTUAL);
        return;
    }

    if (isset($_POST['no']))
    {
        db_actualizar_datos(db_prefijo.'prospectos',array('situacion' => 'luego'),'ID_prospecto='.$_POST['ID_prospecto']);
        db_agregar_datos(db_prefijo.'notas',array('ID_prospecto' => $_POST['ID_prospecto'],  'ID_usuario' => _F_usuario_cache('ID_usuario'), 'nota' => $_POST['notas'], 'fecha' => mysql_datetime(), 'accion' => 'no'));

        // Guardemos lo que llevamos de la encuesta
        _prospecto_guardar_encuesta();
    }

    if (isset($_POST['recordatorio']))
    {
        $fecha = date('Y-m-d',strtotime(str_replace('/', '-', $_POST['fecha']))).' '.$_POST['hora'];
        db_actualizar_datos(db_prefijo.'prospectos',array('situacion' => 'recordatorio'),'ID_prospecto='.db_codex($_POST['ID_prospecto']));
        db_agregar_datos(db_prefijo.'recordatorio',array('ID_usuario' => _F_usuario_cache('ID_usuario'),'ID_prospecto' => $_POST['ID_prospecto'], 'fecha' => $fecha, 'nota' => $_POST['notas']));
        db_agregar_datos(db_prefijo.'notas',array('ID_prospecto' => $_POST['ID_prospecto'],  'ID_usuario' => _F_usuario_cache('ID_usuario'), 'nota' => $_POST['notas'].'<hr /><small>Recordatorio establecido para <b>'.$fecha.'</b></small>', 'fecha' => mysql_datetime(), 'accion' => 'recordatorio'));

        // Guardemos lo que llevamos de la encuesta
        _prospecto_guardar_encuesta();
    }

    if (isset($_POST['descartar']))
    {
        db_actualizar_datos(db_prefijo.'prospectos',array('situacion' => 'descartado'),'ID_prospecto='.$_POST['ID_prospecto']);
        db_agregar_datos(db_prefijo.'notas',array('ID_prospecto' => $_POST['ID_prospecto'],  'ID_usuario' => _F_usuario_cache('ID_usuario'), 'nota' => $_POST['notas'], 'fecha' => mysql_datetime(), 'accion' => 'descartar'));
    }
}

// Obtener un prospecto a la suerte.
// Solo que debe cumplir las siguientes condiciones:
// * Situacion = nuevo
// * ultima_presentacion = núnca o 7 días antes
// * preferencia horaria: east, central, west

/* FIX: usar transacción para evitar condición de carrera */
if (empty($_GET['p']) && empty($_GET['r']))
{

    /*
    switch (0)
    {
        case 13:
        case 14:
        case 15:
        case 16:
            $PREFERENCIA_HORARIA = 'SELECT ';
            break;
        case 17:
        case 18:
        case 19:

        break;
        default:

    }
    */
    $WHERE = 'situacion="nuevo" AND ultima_presentacion < (DATE(NOW()) - INTERVAL 7 DAY) AND no_pool=0 ORDER BY RAND()';
}
// Esta viendo un recordatorio
elseif (isset($_GET['p']) && isset($_GET['r']))
{
    $cRecordatorio = 'SELECT `ID_recordatorio`, `ID_usuario`, `ID_prospecto`, `fecha`, `nota` FROM `'.db_prefijo.'recordatorio` WHERE ID_recordatorio = "'.db_codex($_GET['r']).'" AND ID_prospecto = "'.db_codex($_GET['p']).'"';
    $rRecordatorio = db_consultar($cRecordatorio);
    if (mysql_num_rows($rRecordatorio) && $fRecordatorio = mysql_fetch_assoc($rRecordatorio))
    {
        db_consultar('DELETE FROM '.db_prefijo.'recordatorio WHERE ID_recordatorio="'.$fRecordatorio['ID_recordatorio'].'" AND ID_prospecto="'.db_codex($fRecordatorio['ID_prospecto']).'"');
        db_actualizar_datos(db_prefijo.'prospectos',array('situacion' => 'nuevo'),'ID_prospecto='.$fRecordatorio['ID_prospecto']);
        $WHERE = 'ID_prospecto="'.$fRecordatorio['ID_prospecto'].'"';
    }
    else
    {
        header('Location: '.PROY_URL_ACTUAL);
    }
}
// Viendo un prospecto numerado
elseif (isset($_GET['p']))
{
    $WHERE = 'ID_prospecto="'.db_codex($_GET['p']).'"';
}
else
{
    header('Location: '.PROY_URL_ACTUAL);
}

$c = 'SELECT `ID_prospecto`, `situacion`, IF(`ultima_presentacion`,`ultima_presentacion`,"Nunca") AS "ultima_presentacion", `intentos`, `apellido`, `nombre`, `direccion1`, `direccion2`, `ciudad`, `estado`, `zip`, `telefono`, `especial2`, `especial3`, `especial5`, `especial6`, `especial7`, `interes` FROM `'.db_prefijo.'prospectos` WHERE '.$WHERE;
$r = db_consultar($c);

if (!mysql_num_rows($r))
{
    echo '<p>No hay prospectos disponibles en este momento.</p>';
    return;
}

$f = mysql_fetch_assoc($r);

// Configuracion de _prospecto_encuesta
$ID_prospecto = $f['ID_prospecto'];

// Tocar para que no le salga a nadie mas por este dia
db_actualizar_datos(db_prefijo.'prospectos',array('ultima_presentacion' => mysql_date()),'ID_prospecto='.$f['ID_prospecto']);


// Obtengamos los recordatorios de esta hora...
$c = 'SELECT `ID_recordatorio`, `ID_usuario`, `ID_prospecto`, `fecha`, `nota`, `nombre`, `apellido` FROM `'.db_prefijo.'recordatorio` LEFT JOIN `'.db_prefijo.'prospectos` USING(ID_prospecto) WHERE ID_usuario='._F_usuario_cache('ID_usuario').' AND DATE(`fecha`)=DATE(NOW()) AND HOUR(`fecha`)=HOUR(NOW()) AND MINUTE(NOW()) BETWEEN (MINUTE(`fecha`)-20) AND  (MINUTE(`fecha`)+60)';
$rr = db_consultar($c);

if (mysql_num_rows($rr))
{
    echo '<h2>Hay recordatorios programados para esta hora</h2>';
    echo '<table class="tabla-estandar">';
    echo '<tr><th width="30%">Fecha</th><th>Prospecto</th><th width="50%">Nota</th><th>Enlace</th></tr>';
    while ($ff = mysql_fetch_assoc($rr))
    {
        echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',$ff['fecha'],$ff['apellido'].' , '.$ff['nombre'],$ff['nota'],'<a href="'.PROY_URL_ACTUAL.'?p='.$ff['ID_prospecto'].'&r='.$ff['ID_recordatorio'].'">ver</a>');
    }
    echo '</table><hr style="border:1px solid #F00;"/>';
}

// Obtengamos las notas...
$c = 'SELECT `ID_nota`, `ID_prospecto`, `ID_usuario`, `nota`, `fecha`, `accion`, `nombre` FROM '.db_prefijo.'notas LEFT JOIN '.db_prefijo.'usuarios USING(`ID_usuario`) WHERE ID_prospecto='.$f['ID_prospecto'];
$rr = db_consultar($c);

if (mysql_num_rows($rr))
{
    echo '<h2>Notas que dejaron otros agentes sobre este prospecto</h2>';
    echo '<table class="tabla-estandar">';
    echo '<tr><th width="15%">Fecha</th><th width="20%">Agente</th><th width="45%">Nota</th><th width="20%">Acción</th></tr>';
    while ($ff = mysql_fetch_assoc($rr))
    {
        echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',$ff['fecha'],$ff['nombre'],$ff['nota'],$ff['accion']);
    }
    echo '</table><hr style="border:1px solid #F00;"/>';
}

?>
<form action="<?php echo PROY_URL_ACTUAL; ?>" method="post">
    <input type="hidden" name="ID_prospecto" value="<?php echo $f['ID_prospecto']; ?>" />
    <table id="tabla_prospecto">
        <tr>
            <th>ID de prospecto</th><th>Último vez mostrado</th>
        </tr>
        <tr>
            <td><?php echo $f['ID_prospecto']; ?></td>
            <td><?php echo $f['ultima_presentacion']; ?></td>
        </tr>

        <tr>
            <th>Owner</th>
            <th>Phone</th>
        </tr>
        <tr>
            <td style="color:#F00;"><?php echo $f['nombre'].' '.$f['apellido']; ?></td>
            <td style="color:#F00;font-weight:bold;"><?php echo preg_replace(array('/[^\d]/','/(\d{10})/','/(\d{1})(\d{3})(\d{7})/'),array('','1$1','$1-$2-$3'),$f['telefono']); ?></td>
        </tr>

        <tr>
            <th>Address</th><th>State / City / Zip Code</th>
        </tr>
        <tr>
            <td><?php echo $f['direccion2']; ?></td>
            <td><?php echo $f['estado'] , ' / ', $f['ciudad'] , ' / ' , $f['zip']; ?></td>
        </tr>

        <tr>
            <th>Mortage date</th><th>Mortage Amount</th>
        </tr>
        <tr>
            <td><?php echo strftime('%e de %B de %Y', strtotime($f['especial2'])); ?></td>
            <td style="font-weight:bold;"><?php echo '$', @number_format(preg_replace('/[^\d]/','',$f['especial5']),2,'.',','); ?></td>
        </tr>

        <tr>
            <th>Lender</th><th>Mortage Rate</th>
        </tr>
        <tr>
            <td><?php echo $f['especial6']; ?></td>
            <td><?php echo $f['interes'] . '% / ' . $f['especial7']; ?></td>
        </tr>
    </table>

    <?php if (1 || $f['situacion'] == 'nuevo'): ?>
    <table class="tabla-estandar">
        <tr>
            <th colspan="4">Notas</th>
        </tr>
        <tr>
            <td colspan="4"><textarea name="notas" style="width:100%"></textarea></td>
        </tr>
    </table>
    <div id="nuevo_prospecto" style="color:#F00;background-color:#000;font-size:2em;text-align:center;">Prospecto cargado</div>
    <?php echo _prospecto_tabla_botones(); ?>

    <div class="subtitulo">Aplicación preliminar</div>
    <div class="encuesta"><?php require_once('php/_prospecto_encuesta.php'); ?></div>
    <p class="mini" style="color:#F00;">NOTA: ahora los botones de acción solo estan disponibles en la parte usual de la pagina (parte superior). Esto evitará los problemas con los recordatorios siempre a las 13:00p.m.. Disculpen las molestias.</p>
    <?php endif; ?>

</form>
