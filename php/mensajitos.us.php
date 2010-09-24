<?php
function EnviarMensajitosUS($telefono, $carrier, $mensaje)
{
    $aCarrier['virgin_mobile'] = 'vmobl.com';
    $aCarrier['beyond_gsm'] = 'txt.att.net';
    $aCarrier['cingular_att'] = 'txt.att.net';
    $aCarrier['verizon'] = 'vtext.com';
    $aCarrier['centennial'] = 'cwemail.com';
    $aCarrier['cellularsouth'] = 'csouth1.com';
    $aCarrier['cincinnati_bell'] = 'gocbw.com';
    $aCarrier['boost_mobile'] = 'myboostmobile.com';
    $aCarrier['nextel'] = 'messaging.nextel.com';
    $aCarrier['sprint'] = 'messaging.sprintpcs.com';
    $aCarrier['tmobile'] = 'tmomail.net';
    $aCarrier['air_voice_gsm'] = 'txt.att.net';
    $aCarrier['air_voice_cdma'] = 'messaging.sprintpcs.com';
    $aCarrier['alltel'] = 'message.alltel.com';
    $aCarrier['qwest'] = 'qwestmp.com';
    $aCarrier['metro_pcs'] = 'mymetropcs.com';
    $aCarrier['cricket'] = 'mms.mycricket.com';

    if (!array_key_exists($carrier,$aCarrier))
        return;

    $headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: "'. PROY_NOMBRE .'" <'. PROY_MAIL_POSTMASTER . ">\r\n";

    echo $telefono.'@'.$aCarrier[$carrier].' ';
    return correoSMTP($telefono.'@'.$aCarrier[$carrier],PROY_NOMBRE,$mensaje,false);
}
?>
