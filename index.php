<?php
require_once ('php/vital.php');

// Auxiliar para HEAD
$arrHEAD = array();
$arrJS = array();

$HEAD_titulo = '';

// Inclusiones JS
$arrJS[] = 'jquery-1.3.2.min';
$arrJS[] = 'jquery.jgrowl';
$arrJS[] = 'jquery.jclock-1.2.0';

// Inclusiones CSS
$arrCSS[] = 'estilo';
$arrCSS[] = 'css/jquery.jgrowl';

?>
<?php ob_start(); ?>
<?php require_once('php/traductor.php'); ?>
<?php $BODY = ob_get_clean(); ?>

<?php ob_start(); ?>
<body>

<?php if(!isset($GLOBAL_IMPRESION)) { ?>
    <div id="wrapper">
    <div id="header"><?php GENERAR_CABEZA(); ?></div>
    <div id="secc_general">
    <?php echo $BODY; ?>
    </div> <!-- secc_general !-->
    </div> <!-- wrapper !-->
<?php } else { ?>
    <style>
    body{background:none !important;background-image:none !important;}
    #wrapper{border:none !important;margin:0 !important;padding:1em !important;}
    .medio-oculto{font-size:11pt;}
    </style>
    <div id="wrapper">
    <div id="secc_general">
    <?php echo $BODY; ?>
    </div> <!-- secc_general !-->
    </div> <!-- wrapper !-->
<?php } ?>
</body>
</html>
<?php $BODY = ob_get_clean(); ?>

<?php if (!empty($_LOCATION)) header ("Location: $_LOCATION"); ?>

<?php
/* CAPTURAR <head> */
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
    <title><?php echo $HEAD_titulo; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Style-type" content="text/css" />
    <meta http-equiv="Content-Script-type" content="text/javascript" />
    <meta http-equiv="Content-Language" content="es" />
    <meta name="robots" content="index, follow" />
    <link href="favicon.ico" rel="icon" type="image/x-icon" />
<?php HEAD_CSS(); ?>
<?php HEAD_JS(); ?>
<?php HEAD_EXTRA(); ?>
<?php if (0): ?>
<script>
$(document).ready(function(){ $.jGrowl("TEST", { sticky: true }); });
</script>
<?php endif;?>
</head>
<?php $HEAD = ob_get_clean(); ?>
<?php

/* MOSTRAR TODO */
if(isset($GLOBAL_TIDY_BREAKS))
    echo $HEAD.$BODY;
else
{
    $tidy_config = array('output-xhtml' => true);
    $tidy = tidy_parse_string($HEAD.$BODY,$tidy_config,'UTF8');
    $tidy->cleanRepair();
    echo  trim($tidy);
}
?>
<?php
/* ---------------------------------------------------------------------------*/
/* Funciones adicionales */
function GENERAR_CABEZA()
{
    require_once('php/cabecera.php');
    require_once('php/menu.php');
}
