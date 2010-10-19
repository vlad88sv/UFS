<?php

//$x = mysql_fetch_assoc(db_consultar('SELECT @@session.time_zone AS "timezone";'));

$banner  = '';
if (S_iniciado())
{
    $c = 'SELECT `usuario`, `clave`, `nombre`, `nivel`, `ID_supervisor`, `local` FROM `ufs__usuarios` AS a LEFT JOIN `ufs__supervisores` USING(`ID_supervisor`) WHERE a.ID_usuario=(SELECT ID_agente_sv FROM ufs__prospectos_aplicados AS c WHERE DATE(fecha_ingresada)=DATE(NOW()) GROUP BY c.ID_agente_sv ORDER BY COUNT(*) DESC LIMIT 1)';
    $r = db_consultar($c);

    if (mysql_num_rows($r))
    {
        $f = mysql_fetch_assoc($r);
        $banner .= '<div style="background-color:#EEE;font-size:0.8em;text-align:center;text-decoration:blink;color:#F00;">¡El agente <b>'.$f['nombre'].'</b> tiene por el momento el mayor número de aplicaciones ingresadas este día!</div>';
    } else {
        $banner .= '<div style="background-color:#EEE;font-size:0.8em;text-align:center;">Hey! hey! UFS!, I\'m here \'cause I\'m the BEST</div>';
    }
}
?>
<div id="menu_superior">
    <ul id="" class="dropdown dropdown-horizontal">

    <li class="dir lidestacado"><a href="<?php echo PROY_URL; ?>" title="">Inicio</a></li>

    <?php if (S_iniciado()):?>
        <?php if (in_array(_F_usuario_cache('nivel'), array(_N_agente_sv,_N_agente_us_solo,_N_agente_us))):?>
            <li class="dir"><a href="<?php echo PROY_URL; ?>prospecto" title="">Prospecto</a></li>
        <?php endif; ?>
        <li class="dir"><a href="<?php echo PROY_URL; ?>aplicaciones?instrucciones" title="">Aplicaciones</a>
        <ul>
        <li><a href="<?php echo PROY_URL; ?>aplicaciones_positivas" title="">Positivas</a></li>
        <?php if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv,_N_administrador_us))):?>
            <li><a href="<?php echo PROY_URL; ?>aplicaciones_grupo?fecha_ingresada=now" title="">Solo de mi grupo</a></li>
            <li><a href="<?php echo PROY_URL; ?>aplicaciones_vigiladas" title="">Vigiladas</a></li>
        <?php endif; ?>
        <li><a href="<?php echo PROY_URL; ?>aplicaciones_desactualizadas" title="">Desactualizadas</a></li>
        <li><a href="<?php echo PROY_URL; ?>aplicaciones_pendientes" title="">Sin tomar (libres)</a></li>
        <li><a href="<?php echo PROY_URL; ?>aplicaciones_esperando" title="">Sin procesar</a></li>
        <li><a href="<?php echo PROY_URL; ?>aplicaciones_validas?fecha_inicio=-3 day&fecha_final=now" title="">Válidas</a></li>
        <li><a href="<?php echo PROY_URL; ?>aplicaciones_invalidas?fecha_inicio=-3 day&fecha_final=now" title="">Inválidas</a></li>
        <li><a href="<?php echo PROY_URL; ?>aplicaciones_vendidas?fecha_inicio=-3 day&fecha_final=now" title="">Vendidas</a></li>
        </ul>
        </li>

    <?php endif; ?>

    <?php if (in_array(_F_usuario_cache('nivel'), array(_N_agente_us,_N_agente_us_solo))):?>
        <li class="dir lidestacado"><a href="<?php echo PROY_URL; ?>aplicaciones_asignadas" title="">Mis aplicaciones</a></li>
    <?php endif; ?>

    <?php if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv,_N_administrador_us,_N_agente_sv))):?>
        <li class="dir"><a href="<?php echo PROY_URL; ?>estadisticas" title="">Estadísticas</a></li>
    <?php endif; ?>


    <?php if (S_iniciado()): ?>
    <li class="dir"><a href="" title="">FAQ</a>
    <ul>
        <li><a href="<?php echo PROY_URL; ?>FAQ?d=listado_bancos">Listado de bancos</a></li>
        <li><a href="<?php echo PROY_URL; ?>FAQ?d=script">Script</a></li>
    </ul>
    </li>

    <li class="dir"><a href="" title=""><?php echo _F_usuario_cache('nombre'); ?></a></li>
    <?php if (0): ?>
    <li class="dir"><a href="<?php echo PROY_URL; ?>chat" title="Chat UFS Online Network" target="_blank">CHAT</a></li>
    <?php endif; ?>
    <li class="dir busqueda">
    <form action="<?php echo PROY_URL; ?>buscar" class="buscar">
    <?php echo ui_input('q');?> <input type="submit" value="Búscar" class="btnlnk btnlnk-mini" />
    </form>
    </li>
    <?php endif; ?>

    </ul> <!-- nav !-->
</div> <!-- menu_superior !-->
<?php if (in_array(_F_usuario_cache('nivel'), array(_N_agente_sv,_N_administrador_sv))) echo $banner; ?>
