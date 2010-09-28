<?php
protegerme(false,true);
echo '<h1>Estadísticas</h1>';

// POOL
if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv))):
$c = 'SELECT situacion, COUNT(*) AS "cantidad" FROM '.db_prefijo.'prospectos GROUP BY situacion WITH ROLLUP';
$r = db_consultar($c);
if (mysql_num_rows($r))
{
    echo '<h2>Salud de la piscina de contactos</h2>';
    echo '<table class="tabla-estandar">';
    echo '<tr><th>Situación</th><th>Cantidad</th></tr>';
    while($f = mysql_fetch_assoc($r))
        echo sprintf('<tr><td>%s</td><td>%s</td></tr>',(is_null($f['situacion']) ? 'Total' : $f['situacion']),number_format($f['cantidad'],0,'.',','));
    echo '</table>';
}

$c = 'SELECT COUNT(*) AS "cantidad" FROM '.db_prefijo.'prospectos WHERE situacion="nuevo" AND ultima_presentacion < (DATE(NOW()) - INTERVAL 7 DAY) AND no_pool=0 ORDER BY RAND()';
$f = mysql_fetch_assoc(db_consultar($c));
echo '<p>Prospectos "nuevos" válidos [!no_pool+INTERVAL 7 DAY] en este momento: '.number_format($f['cantidad'],0,'.',',').'</p>';
endif;

$c = 'SELECT COUNT(*) AS "cuenta", local, (SELECT nombre FROM ufs__usuarios AS st1 WHERE st1.ID_usuario = c.ID_usuario) AS "nombre_supervisor", pais FROM ufs__prospectos_aplicados AS a LEFT JOIN ufs__usuarios AS b ON a.ID_agente_sv = b.ID_usuario LEFT JOIN ufs__supervisores AS c USING(ID_supervisor)  WHERE `ID_supervisor` GROUP BY `local` ORDER BY COUNT(*) DESC';
$r = db_consultar($c);

$c = 'SELECT COUNT(*) AS "cuenta", local, (SELECT nombre FROM ufs__usuarios AS st1 WHERE st1.ID_usuario = c.ID_usuario) AS "nombre_supervisor", pais FROM ufs__prospectos_aplicados AS a LEFT JOIN ufs__usuarios AS b ON a.ID_agente_sv = b.ID_usuario LEFT JOIN ufs__supervisores AS c USING(ID_supervisor) WHERE `ID_supervisor` AND DATE(fecha_ingresada)=DATE(NOW()) GROUP BY `local` ORDER BY COUNT(*) DESC';
$r2 = db_consultar($c);

if (mysql_num_rows($r2))
{
    echo '<h2>Número de aplicaciones ingresadas por grupo (diario)</h2>';
    echo '<table class="tabla-estandar">';
    echo '<tr><th>Grupo</th><th>País</th><th>Cantidad</th></tr>';
    while($f = mysql_fetch_assoc($r2))
        echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>',$f['local'],$f['pais'],$f['cuenta']);
    echo '</table>';
}

if (mysql_num_rows($r))
{
    echo '<h2>Número de aplicaciones ingresadas por grupo (global)</h2>';
    echo '<table class="tabla-estandar">';
    echo '<tr><th>Grupo</th><th>País</th><th>Cantidad</th></tr>';
    while($f = mysql_fetch_assoc($r))
        echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>',$f['local'],$f['pais'],$f['cuenta']);
    echo '</table>';
}


$LIMIT = '';
if (_F_usuario_cache('nivel') == _N_agente_sv)
    $LIMIT = 'LIMIT 5';

$c = 'SELECT COUNT(*) AS "cuenta", (SELECT COUNT(*) FROM ufs__prospectos_aplicados AS o WHERE o.ID_agente_sv=a.ID_agente_sv AND aplicacion_valida="valida") AS "cuenta_validas", a.ID_agente_sv, nombre, local, (SELECT nombre FROM ufs__usuarios AS st1 WHERE st1.ID_usuario = c.ID_usuario) AS "nombre_supervisor", pais FROM ufs__prospectos_aplicados AS a LEFT JOIN ufs__usuarios AS b ON a.ID_agente_sv = b.ID_usuario LEFT JOIN ufs__supervisores AS c USING(ID_supervisor) WHERE b.nivel="agente_sv" GROUP BY a.ID_agente_sv ORDER BY COUNT(*) DESC '.$LIMIT;
$r = db_consultar($c);

$c = 'SELECT COUNT(*) AS "cuenta", a.ID_agente_sv, nombre, local, (SELECT nombre FROM ufs__usuarios AS st1 WHERE st1.ID_usuario = c.ID_usuario) AS "nombre_supervisor", pais FROM ufs__prospectos_aplicados AS a LEFT JOIN ufs__usuarios AS b ON a.ID_agente_sv = b.ID_usuario LEFT JOIN ufs__supervisores AS c USING(ID_supervisor) WHERE b.nivel="agente_sv" AND DATE(fecha_ingresada)=DATE(NOW()) GROUP BY a.ID_agente_sv ORDER BY COUNT(*) DESC '.$LIMIT;
$r2 = db_consultar($c);

if (mysql_num_rows($r2))
{
    echo '
    <h2>Los 5 agentes que mas aplicaciones han ingresado (este día)</h2>
    <table class="tabla-estandar cebra">
        <tr><th>Nombre de agente</th><th>Grupo</th><th>País</th><th>Cantidad</th></tr>
    ';
    while($f = mysql_fetch_assoc($r2)) {
    if (in_array(_F_usuario_cache('nivel'), array(_N_agente_sv,_N_agente_us,_N_agente_us_solo)))
        echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',$f['nombre'],$f['local'],$f['pais'],$f['cuenta']);
    else
        echo sprintf('<tr><td><a href="%s">%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>',PROY_URL.'aplicaciones_agente_sv_'.$f['ID_agente_sv'].'?fecha_ingresada=now', $f['nombre'],$f['local'],$f['pais'],$f['cuenta']);
    }
    echo '</table>';
}
echo '
<h2>Los 5 agentes que mas aplicaciones han ingresado (a nivel global)</h2>
<table class="tabla-estandar cebra">
    <tr><th>Nombre de agente</th><th>Grupo</th><th>País</th><th>Cantidad</th></tr>
';

while($f = mysql_fetch_assoc($r)) {
    if (in_array(_F_usuario_cache('nivel'), array(_N_agente_sv,_N_agente_us,_N_agente_us_solo)))
        echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',$f['nombre'],$f['local'],$f['pais'],$f['cuenta']);
    else
        echo sprintf('<tr><td><a href="%s">%s</a></td><td>%s</td><td>%s</td><td>%s (<a href="%s">%s</a>) %s%%</td></tr>',PROY_URL.'aplicaciones_agente_sv_'.$f['ID_agente_sv'], $f['nombre'],$f['local'],$f['pais'],$f['cuenta'],PROY_URL.'aplicaciones_agente_sv_validas_'.$f['ID_agente_sv'],$f['cuenta_validas'], number_format(($f['cuenta_validas']/$f['cuenta'])*100,2));
}
echo '</table>';



if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv))):
/***************/
// Estadísticas de agentes US
$c = 'SELECT COUNT(*) AS "cuenta",(SELECT COUNT(*) FROM ufs__prospectos_aplicados AS o WHERE o.ID_agente_us=a.ID_agente_us AND aplicacion_valida="valida") AS "cuenta_validas",a.ID_agente_us, nombre, local, (SELECT nombre FROM ufs__usuarios AS st1 WHERE st1.ID_usuario = c.ID_usuario) AS "nombre_supervisor", pais FROM ufs__prospectos_aplicados AS a LEFT JOIN ufs__usuarios AS b ON a.ID_agente_us = b.ID_usuario LEFT JOIN ufs__supervisores AS c USING(ID_supervisor) WHERE b.nivel="agente_us" GROUP BY a.ID_agente_us ORDER BY COUNT(*) DESC '.$LIMIT;
$r = db_consultar($c);

echo '
<h2>Aplicaciones por agente US (a nivel global)</h2>
<table class="tabla-estandar cebra">
    <tr><th>Nombre de agente</th><th>Grupo</th><th>País</th><th>Cantidad</th></tr>
';

while($f = mysql_fetch_assoc($r)) {
    echo sprintf('<tr><td><a href="%s">%s</a></td><td>%s</td><td>%s</td><td>%s (<a href="%s">%s</a>) %s%%</td></tr>',PROY_URL.'aplicaciones_agente_us_'.$f['ID_agente_us'], $f['nombre'],$f['local'],$f['pais'],$f['cuenta'],PROY_URL.'aplicaciones_agente_us_validas_'.$f['ID_agente_us'],$f['cuenta_validas'], number_format(($f['cuenta_validas']/$f['cuenta'])*100,2));
}
echo '</table>';
/***************/
endif;
?>
