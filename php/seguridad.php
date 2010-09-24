<?php
function protegerme($solo_salir=false,$solo_iniciado=false,$niveles=array())
{

    if (!$solo_iniciado)
    {
        if (_F_usuario_cache('nivel') == _N_administrador || in_array(_F_usuario_cache('nivel'),$niveles))
            return;
    } else {
        if (S_iniciado())
        return;
    }

    if (!$solo_salir)
        header('Location: '. PROY_URL.'inicio?ref='.curPageURL());
    ob_end_clean();
    exit;
}
?>
