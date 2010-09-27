<?php
$c = sprintf('SELECT `ID_prospecto`, `generales_fecha_compra`, `generales_sabe_cuanto_cuesta`, `generales_cuanto_cuesta`, `generales_bancarrota_12_meses`, `generales_tipo_bancarrota_7`, `generales_tipo_bancarrota_11`, `generales_tipo_bancarrota_13`, `generales_modificacion_anterior`, `generales_modificacion_hace_cuanto`, `generales_nuevos_terminos_de_modificacion`, `generales_proceso_modificacion`, `generales_fecha_proceso_modificacion`, `generales_modificacion_pago`, `generales_hizo_auditoria`, `generales_hubo_notario`, `generales_tenia_licencia`, `generales_explicaron_terminos_prestamo`, `generales_hizo_npv`, `generales_cuantas_propiedades`, `tarjeta_tiene`, `tarjeta_cuantas`, `tarjeta_7500`, `tarjeta_fecha_fin_deudas`, `tarjeta_pago_mensual`, `tarjeta_cuanto`, `tarjeta_meta`, `empleo_tipo`, `empleo_compania`, `empleo_tiempo`, `hipoteca_banco`, `hipoteca_interes`, `hipoteca_pago_mensual`, `hipoteca_balance` FROM `%s` WHERE ID_prospecto=%s',db_prefijo.'prospectos_encuesta',db_codex($ID_prospecto));
$rrr = db_consultar($c);

if (mysql_num_rows($rrr))
    $fEncuesta = mysql_fetch_assoc($rrr);
?>
<ol id="ol-encuesta">

<fieldset>
    <legend>Preguntas generales</legend>
    <li>¿En que año compro su propiedad? <?php echo ui_input('generales_fecha_compra',@$fEncuesta['generales_fecha_compra'],'text'); ?></li>
    <li>¿Tiene idea de lo que actualmente cuesta? <?php echo ui_optionbox_nosi('generales_sabe_cuanto_cuesta','no','si','Si','No',@$fEncuesta['generales_sabe_cuanto_cuesta']); ?></li>
    <ul>
    <li>¿Cuanto cuesta aproximadamente? <?php echo ui_input('generales_cuanto_cuesta',@$fEncuesta['generales_cuanto_cuesta'],'text'); ?></li>
    </ul>
    <li>¿Ha hecho bancarrota en los pasados 12 meses? <?php echo ui_optionbox_nosi('generales_bancarrota_12_meses','no','si','Si','No',@$fEncuesta['generales_bancarrota_12_meses']); ?></li>
    <ul>
    <li>¿Que tipo de bancarrota ha sido? - Chapter (s) <?php echo ui_input('generales_tipo_bancarrota_7','1','checkbox','','',(@$fEncuesta['generales_tipo_bancarrota_7'] ? 'checked="checked"' : '')) . '7 ' . ui_input('generales_tipo_bancarrota_11','1','checkbox','','',(@$fEncuesta['generales_tipo_bancarrota_11'] ? 'checked="checked"' : '')) . '11 ' . ui_input('generales_tipo_bancarrota_13','1','checkbox','','',(@$fEncuesta['generales_tipo_bancarrota_13'] ? 'checked="checked"' : '')) . '13' ?></li>
    </ul>
    <li>¿Ha hecho una modificacion anteriormente? <?php echo ui_optionbox_nosi('generales_modificacion_anterior','no','si','Si','No',@$fEncuesta['generales_modificacion_anterior']); ?></li>
    <ul>
    <li>¿Hace cuanto fue hecha esa modificación? <?php echo ui_input('generales_modificacion_hace_cuanto',@$fEncuesta['generales_modificacion_hace_cuanto'],'text'); ?></li>
    <li>¿Alguna vez hizo un analisis <acronym title="Net Present Value | Valor Neto Actual">NPV</acronym> para apoyar la modificación? <?php echo ui_optionbox_nosi('generales_hizo_npv','no','si','Si','No',@$fEncuesta['generales_hizo_npv']); ?></li>
    </ul>
    <li>¿Cuales son los nuevos terminos de su prestamo despues de la modificacion?</li>
    <?php echo ui_textarea('generales_nuevos_terminos_de_modificacion',@$fEncuesta['generales_nuevos_terminos_de_modificacion']); ?>
    <li>¿Esta en proceso de modificacion? <?php echo ui_optionbox_nosi('generales_proceso_modificacion','no','si','Si','No',@$fEncuesta['generales_proceso_modificacion']); ?></li>
    <ul>
    <li>¿Hace cuanto comenzo el proceso de modificacion? <?php echo ui_input('generales_fecha_proceso_modificacion',@$fEncuesta['generales_fecha_proceso_modificacion'],'text'); ?></li>
    <li>¿Cuanto pago por el proceso de modificacion? <?php echo ui_input('generales_modificacion_pago',@$fEncuesta['generales_modificacion_pago'],'text'); ?></li>
    <li>¿Alguna vez hizo una auditoria para apoyar a la modificacion? <?php echo ui_optionbox_nosi('generales_hizo_auditoria','no','si','Si','No',@$fEncuesta['generales_hizo_auditoria']); ?></li>
    </ul>
    <li>¿Hubo un notario presente a la hora de firmar la documentacion del prestamo? <?php echo ui_optionbox_nosi('generales_hubo_notario','no','si','Si','No',@$fEncuesta['generales_hubo_notario']); ?></li>
    <li>¿La persona que le ayudo tenia licencia para hacer prestamos? <?php echo ui_optionbox_nosi('generales_tenia_licencia','no','si','Si','No',@$fEncuesta['generales_tenia_licencia']); ?></li>
    <li>¿Le explicaron bien los terminos de su prestamo? <?php echo ui_optionbox_nosi('generales_explicaron_terminos_prestamo','no','si','Si','No',@$fEncuesta['generales_explicaron_terminos_prestamo']); ?></li>
    <li>¿Cuantas propiedades tiene bajo su nombre? <?php echo ui_input('generales_cuantas_propiedades',@$fEncuesta['generales_cuantas_propiedades'],'text'); ?></li>
</fieldset>

<fieldset>
    <legend>Tarjetas de crédito</legend>
        <li>¿Tiene tarjetas de credito? <?php echo ui_optionbox_nosi('tarjeta_tiene','no','si','Si','No',@$fEncuesta['tarjeta_tiene']); ?></li>
        <li>¿Cuantas tarjetas de crédito tiene? <?php echo ui_input('tarjeta_cuantas',@$fEncuesta['tarjeta_cuantas'],'text'); ?></li>
        <li>¿Debe alrededor de $7,500.00 en tarjetas de créditos? <?php echo ui_optionbox_nosi('tarjeta_7500','no','si','Si','No',@$fEncuesta['tarjeta_7500']); ?></li>
        <li>¿Le gustaria saber una fecha mas exacta de cuando va a terminar de pagar sus deudas? <?php echo ui_optionbox_nosi('tarjeta_fecha_fin_deudas','no','si','Si','No',@$fEncuesta['tarjeta_fecha_fin_deudas']); ?></li>
        <li>¿Cuanto dinero al mes gasta en pagar sus cuentas abiertas de tarjetas de credito? <?php echo ui_input('tarjeta_pago_mensual',@$fEncuesta['tarjeta_pago_mensual'],'text'); ?></li>
        <li>¿Cuanto debe en total de sus tarjetas de crédito? <?php echo ui_input('tarjeta_cuanto',@$fEncuesta['tarjeta_cuanto'],'text'); ?></li>
        <li>¿Cual es la meta que tiene con su propiedad y sus deudas?</li>
        <?php echo ui_textarea('tarjeta_meta',@$fEncuesta['tarjeta_meta']); ?>
</fieldset>

<fieldset>
    <legend>Empleo</legend>
    <li>¿A que se dedica? <?php echo ui_input('empleo_tipo',@$fEncuesta['empleo_tipo'],'text'); ?></li>
    <li>¿Con que compañía/empresa trabaja? <?php echo ui_input('empleo_compania',@$fEncuesta['empleo_compania'],'text'); ?></li>
    <li>¿Cuantos años tienen de trabajar ahi? <?php echo ui_input('empleo_tiempo',@$fEncuesta['empleo_tiempo'],'text'); ?></li>
</fieldset>

<fieldset>
    <legend>Hipoteca / Mortgage</legend>
    <li>¿Quien es su banco actualmente? <?php echo ui_input('hipoteca_banco',@$fEncuesta['hipoteca_banco'],'text'); ?></li>
    <li>¿Cual es su interes? <?php echo ui_input('hipoteca_interes',@$fEncuesta['hipoteca_interes'],'text'); ?></li>
    <li>¿Cual es su pago mensual? <?php echo ui_input('hipoteca_pago_mensual',@$fEncuesta['hipoteca_pago_mensual'],'text'); ?></li>
    <li>¿Cual es su balance? <?php echo ui_input('hipoteca_balance',@$fEncuesta['hipoteca_balance'],'text'); ?></li>
</fieldset>

</ol>
