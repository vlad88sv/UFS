<?php
ini_set('memory_limit', '128M');
set_time_limit(0);
if (!isset($_GET['tipo'])) $tipo = 'normal';

switch($_GET['tipo'])
{
    case 'normal':
        IMAGEN_tipo_normal();
        break;
    case 'tcredito':
        IMAGEN_tipo_tcredito();
        break;
    case 'random':
        IMAGEN_tipo_random();
        break;
}

function IMAGEN_tipo_normal()
{
    $escalado = ('IMG/i/m/'.$_GET['ancho'].'_'.$_GET['alto'].'_'.$_GET['sha1']);
    $origen = 'IMG/i/'.$_GET['sha1'];
    $ancho = $_GET['ancho'];
    $alto = $_GET['alto'];

    if(@($ancho*$alto) > 562500)
        die('La imagen solicitada excede el límite de este servicio');

    if (!file_exists($escalado))
    {
       $im=new Imagick($origen);

       $im->setCompression(Imagick::COMPRESSION_JPEG);
       $im->setCompressionQuality(85);
       $im->setImageFormat('jpeg');
       $im->stripImage();
       $im->despeckleImage();
       $im->sharpenImage(0.5,1);
       //$im->reduceNoiseImage(0);
       $im->setInterlaceScheme(Imagick::INTERLACE_PLANE);
       $im->resizeImage($ancho,$alto,imagick::FILTER_LANCZOS,1);
       $im->writeImage($escalado);
       $im->destroy();
    }

    $im=new Imagick($escalado);
    $output = $im->getimageblob();
    $outputtype = $im->getFormat();
    $im->destroy();
    header("Content-type: $outputtype");
    header("Content-length: " . filesize($escalado));
    echo $output;
}

exit;
?>
