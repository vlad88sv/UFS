<?php
function curPageURL($stripArgs=false,$friendly=false) {
$pageURL = '';
if (!$friendly)
{
   $pageURL = 'http';
   if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
   $pageURL .= "://";
}

if ($_SERVER["SERVER_PORT"] != "80") {
   $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
   $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}

if ($stripArgs) {$pageURL = preg_replace("/\?.*/", "",$pageURL);}

if ($friendly)
{
    $pageURL = preg_replace('/www\./', '',$pageURL);
    $pageURL = "www.$pageURL";
}

return $pageURL;
}


// Wrapper de envío de correo electrónico. HTML/utf-8
function correo($para, $asunto, $mensaje, $exHeaders=null)
{
   $headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
   $headers .= 'From: "'. PROY_MAIL_POSTMASTER_NOMBRE.PROY_MAIL_POSTMASTER . "\r\n";
   if (!empty($exHeaders))
   {
       $headers .= $exHeaders;
   }
   $mensaje = sprintf('<html><head><title>%s</title></head><body>%s</body>',PROY_NOMBRE,$mensaje);

   $x = mail($para,'=?UTF-8?B?'.base64_encode($asunto).'?=',$mensaje,$headers);
   return $x;
}

//Wrapper de envío de correo usando PHPMailer
function correoSMTP($para, $asunto, $mensaje,$html=true)
{
   require_once('php/class.phpmailer.php');
   $Mail = new PHPMailer();
   $Mail->IsHTML($html) ;
   $Mail->SetLanguage("es", 'php/language/');
   $Mail->PluginDir	= 'php/';
   $Mail->Mailer	= 'smtp';
   $Mail->Host		= "smtp.gmail.com";
   $Mail->SMTPSecure    = "ssl";
   $Mail->Port		= 465;
   $Mail->SMTPAuth	= true;
   $Mail->Username	= "info@ufsonline.net";
   $Mail->Password	= "22436017";
   $Mail->CharSet	= "utf-8";
   $Mail->Encoding	= "quoted-printable";
   $Mail->FromName	= PROY_MAIL_POSTMASTER_NOMBRE.PROY_MAIL_POSTMASTER;
   $Mail->Subject	= $asunto;
   $Mail->Body		= $mensaje;
   $Mail->AddAddress($para);
   $x = $Mail->Send();

   if ($x)
      return $x;
   else
      return correo($para, $asunto, $mensaje);
}

function enviar_prospecto($ID_aplicacion, $ID_prospecto, $lote=false)
{
    db_actualizar_datos(db_prefijo.'prospectos_aplicados',array('enviado' => mysql_datetime()),'ID_aplicacion="'.$ID_aplicacion.'"');
    $c = sprintf('SELECT `ID_prospecto`, `situacion`, `ultima_presentacion`, `intentos`, `apellido`, `nombre`, `direccion1`, `direccion2`, `especial1`, `ciudad`, `estado`, `zip`, `telefono`, `especial2`, `especial3`, `especial4`, `especial5`, `especial6`, `especial7`, `especial8`, `especial9`, `especial10`, `especial11`, `especial13`, `especial14`, `especial15`, `especial16`, `especial17`, `especial18`, `especial19`, `especial20`, `especial21`, `ID_aplicacion`, `ID_agente_sv`, `ID_agente_us`, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_sv`) AS nombre_agente_sv, (SELECT nombre FROM '.db_prefijo.'usuarios WHERE ID_usuario = `ID_agente_us`) AS nombre_agente_us, `fecha_ingresada`, `fecha_cerrada`, `comision_agente_sv`, `comision_agente_us`, `comsion_ufs_sv`, `comision_ufs_us`, `enviado`, `notas` FROM %s LEFT JOIN %s USING (ID_prospecto) WHERE ID_prospecto="%s" ORDER BY fecha_ingresada ASC', db_prefijo.'prospectos_aplicados', db_prefijo.'prospectos', db_codex($ID_prospecto));
    $r = db_consultar($c);
    $f = mysql_fetch_assoc($r);

   if ($lote)
   {
   $buffer = '
   <table>
   <tr><th style="text-align:right;border-right:1px solid #000;">Agent SV</th><td>'.$f['nombre_agente_sv'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Agent US</th><td>'.$f['nombre_agente_us'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Prospect ID</th><td>'.$f['ID_prospecto'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Application date</th><td>'.$f['fecha_ingresada'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Name</th><td>'.$f['apellido'].', '.$f['nombre'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Phone</th><td>'.$f['telefono'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Address</th><td>'.$f['direccion2'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">City</th><td>'.$f['ciudad'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Zip Code</th><td>'.$f['zip'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Mortage Date</th><td>'.$f['especial2'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Mortage Amount</th><td>$'.@number_format(preg_replace('/[^\d]/','',$f['especial5']),2,'.',',').'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Mortage rate</th><td>'.$f['especial7'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Sales Price</th><td>'.$f['especial3'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Lender</th><td>'.$f['especial6'].'</td></tr>
   <tr><th style="text-align:right;border-right:1px solid #000;">Notas</th><td>'.$f['notas'].'</td></tr>
   </table>
   <a href="'.PROY_URL.'aplicaciones?ver='.$f['ID_aplicacion'].'&correo">Ir a la aplicación</a>
   ';
   } else {
   $buffer = '
   <p>El agente <b>'.$f['nombre_agente_sv'].'</b> ha ingresado una nueva aplicación.</p>
   <a href="'.PROY_URL.'aplicaciones?ver='.$f['ID_aplicacion'].'&correo">Ir a la aplicación</a>
   <hr />
   <p>
   <small>
   Ud. ha recibido esta notificación por una de las siguientes causas:
   <ul>
   <li>Ud. es un agente US registrado en el sistema UFS Online Network</li>
   <li>Ud. esta en la lista de administradores/supervisores</li>
   </ul>
   <hr />
   <span style="color:#F00;">
   NO RESPONDA A ESTE CORREO, LOS CORREOS ENVIADOS A '.htmlentities(PROY_MAIL_POSTMASTER).' NO SON REVISADOS.<br />
   En su lugar puede comentar en la aplicación mencionada.
   </span>
   </small>
   </p>
   ';
   }

   if ($lote)
      return $buffer;
   else
   {
      require_once('php/mensajitos.us.php');
      $c = 'SELECT usuario, nombre, correo, telefono, carrier FROM '.db_prefijo.'usuarios WHERE nivel="agente_us"';
      $r = db_consultar($c);

      while (mysql_num_rows($r) && $f =  mysql_fetch_assoc($r))
         EnviarMensajitosUS($f['telefono'],$f['carrier'],'Nueva aplicacion disponible en el sistema');

      correoSMTP('broadcast@ufsonline.net','Nueva aplicación - '.$ID_aplicacion.'+'.$ID_prospecto, $buffer);
   }
}

function enviar_lote($r)
{
   $buffer = '';
   $i = 0;
   while($f = mysql_fetch_assoc($r))
   {
      $i++;
      $buffer .= '<hr />#'.$i.'<br />'.enviar_prospecto($f['ID_aplicacion'],$f['ID_prospecto'],true);
   }
   correoSMTP(_F_usuario_cache('correo'),'Aplicaciones en lote', $buffer);
   echo '<div>Lote enviado a '. _F_usuario_cache('correo').' - '.strlen($buffer).' Bytes</div>';
   mysql_data_seek($r,0);
}
?>
