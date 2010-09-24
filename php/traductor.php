<?php
if (!isset($_GET['peticion']))
{
    require_once ('php/portada.php');
    return;
}

switch ($_GET['peticion'])
{
    case 'inicio':
        require_once('php/inicio.php');
    break;
    case 'final':
        _F_sesion_cerrar();
    break;
    case 'buscar':
        require_once('php/_busqueda.php');
    break;
    case 'prospecto':
        require_once('php/_prospecto.php');
    break;
    case 'aplicaciones':
        require_once('php/_aplicaciones.php');
    break;
    case 'recordatorios':
        require_once('php/_recordatorios.php');
    break;
    case 'estadisticas':
        require_once('php/_estadisticas.php');
    break;
    case 'encuesta':
        require_once('php/_prospecto_encuesta_wrapper.php');
    break;
    case 'faq':
        require_once('php/_faq.php');
    break;
    default:
        echo 'Petición errónea: '. $_GET['peticion'] .'. Abortando.';
}
?>
