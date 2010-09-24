<?php
protegerme(false,true);
if (empty($_GET['q']))
{
    echo 'ERROR. Los términos de búsqueda estaban vacios.';
    return;
}

$aq = explode(':',$_GET['q']);

if (!count($aq)){
    echo 'ERROR. Los términos son inválidos.';
    return;
}
switch ($_GET['q'][0])
{
    // prospecto
    case 'p':
        if (count($aq) == 2 && is_numeric($aq[1]))
        {
            header('Location: '.PROY_URL.'prospecto?p='.$aq[1]);
            return;
        }
        break;

    // aplicación
    case 'a':
        if (count($aq) == 2 && is_numeric($aq[1]))
        {
            header('Location: '.PROY_URL.'aplicaciones?a='.$aq[1]);
            return;
        }
        break;

    case 'pb:':
        break;

    default:
        echo 'ERROR. Modificador inválido.';
        return;
}
?>
