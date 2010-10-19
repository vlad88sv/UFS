<?php
require_once ('php/vital.php');
require_once('php/mensajitos.us.php');
$mensajito = $correo = false;

if (isset($_POST['enviar']))
{
    if (!empty($_POST['email']))
        $correo = correoSMTP($_POST['email'],'Este es un correo de prueba','Mensaje de prueba');
    else
        $correo = false;

    if (!empty($_POST['phone']))
        $mensajito = EnviarMensajitosUS($_POST['phone'],$_POST['carrier'], 'SMS de prueba');
    else
        $mensajito = false;

echo "<p>Correo: ".(int)$correo."</p>";
echo "<p>Mensajito: ".(int)$mensajito."</p>";
}
?>
<form action="<?php echo PROY_URL_ACTUAL; ?>" method="post">
<label for="email">Email</label> <input type="text" name="email" id="email" /><br />
<label for="phone">Phone</label> <input type="text" name="phone" id="phone" /> <label for="carrier">Carrier</label> <select id="carrier" name="carrier"><?php echo ui_array_key_opciones(array('virgin_mobile','beyond_gsm','cingular_att','verizon','centennial','cellularsouth','cincinnati_bell','boost_mobile','nextel','sprint','tmobile','air_voice_gsm','air_voice_cdma','alltel','qwest','metro_pcs','cricket')); ?></select><br />
<input name="enviar" type="submit" value="Enviar" />
</form>
