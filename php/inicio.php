<?php
if (isset($_POST['iniciar_proceder']))
{
    ob_start();
    $ret = _F_usuario_acceder($_POST['iniciar_campo_correo'],$_POST['iniciar_campo_clave']);
    $buffer = ob_get_clean();
    if ($ret != 1)
    {
        echo mensaje ("El usuario o ID de agente no existe o la contraseña es incorrecta.",_M_ERROR);
        echo mensaje ($buffer,_M_INFO);
    }
}

if (S_iniciado())
{
    if (!empty($_POST['iniciar_retornar']))
    {
        header("location: ".$_POST['iniciar_retornar']);
    }
    else
    {
        header("location: ./");
    }
    return;
}

$HEAD_titulo = PROY_NOMBRE . ' - Inicio de sesión';

if (isset($_GET['ref']))
    $_POST['iniciar_retornar'] = $_GET['ref'];

$retorno = empty($_POST['iniciar_retornar']) ? PROY_URL : $_POST['iniciar_retornar'];
echo '<form id="formulario_inicio" action="inicio" method="POST">';
echo ui_input("iniciar_retornar", $retorno, "hidden");
echo 'Usuario:<br />' . ui_input("iniciar_campo_correo") . '<br />';
echo 'Contraseña:<br />' . ui_input("iniciar_campo_clave","","password") . '<br />';
echo ui_input("iniciar_proceder", "Iniciar sesión", "submit");
echo "</table>";
echo "</form>";
?>
