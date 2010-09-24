<?php
$ID_prospecto = $_GET['p'];

if (isset($_POST['guardar']))
{
    $arrDB = array_flip(array('generales_fecha_compra', 'generales_sabe_cuanto_cuesta', 'generales_cuanto_cuesta', 'generales_bancarrota_12_meses', 'generales_tipo_bancarrota_7', 'generales_tipo_bancarrota_11', 'generales_tipo_bancarrota_13', 'generales_modificacion_anterior', 'generales_modificacion_hace_cuanto', 'generales_nuevos_terminos_de_modificacion', 'generales_proceso_modificacion', 'generales_fecha_proceso_modificacion', 'generales_modificacion_pago', 'generales_hizo_auditoria', 'generales_hubo_notario', 'generales_tenia_licencia', 'generales_explicaron_terminos_prestamo', 'generales_hizo_npv', 'generales_cuantas_propiedades', 'tarjeta_tiene', 'tarjeta_cuantas', 'tarjeta_7500', 'tarjeta_fecha_fin_deudas', 'tarjeta_pago_mensual', 'tarjeta_cuanto', 'tarjeta_meta', 'empleo_tipo', 'empleo_compania', 'empleo_tiempo', 'hipoteca_banco', 'hipoteca_interes', 'hipoteca_pago_mensual', 'hipoteca_balance'));
    $DATOS = array_intersect_key($_POST,$arrDB);
    $DATOS['ID_prospecto'] = db_codex($_POST['ID_prospecto']);
    db_reemplazar_datos(db_prefijo.'prospectos_encuesta',$DATOS);
}
?>
<form action="<?php echo PROY_URL_ACTUAL_DINAMICA; ?>" method="post">
<input type="hidden" name="ID_prospecto" value="<?php echo $_GET['p']; ?>" />
<div class="encuesta"><?php require_once('php/_prospecto_encuesta.php'); ?></div>
<input type="submit" name="guardar" value="Guardar" />
</form>
