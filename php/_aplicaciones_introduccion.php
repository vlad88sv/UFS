<h1>Instrucciones de uso de listado de aplicaciones</h1>
<p>Seleccione una categoría para continuar:</p>
<ul class="instrucciones">
<li><a href="<?php echo PROY_URL; ?>aplicaciones?fecha_ingresada=now" title="">Todas</a> – Todas las aplicaciones sin importar su estado.</li>
<li><a href="<?php echo PROY_URL; ?>aplicaciones_positivas?fecha_ingresada=now" title="">Positivas</a> – aplicaciones que NO son <b>inválidas</b> y que NO son <b>imposibles</b>. Por defecto muestra las del día actual.</li>
<li><a href="<?php echo PROY_URL; ?>aplicaciones_validas?fecha_ingresada=now" title="">Válidas</a> – aplicaciones <b>válidas</b>. Por defecto muestra las del día actual.</li>
<li><a href="<?php echo PROY_URL; ?>aplicaciones_invalidas?fecha_ingresada=now" title="">Inválidas</a> – aplicaciones <b>inválidas</b>. Por defecto muestra las del día actual.</li>
<li><a href="<?php echo PROY_URL; ?>aplicaciones_vendidas?fecha_ingresada=now" title="">Vendidas</a> – aplicaciones <b>vendidas</b>. Por defecto muestra las del día actual.</li>
<li><a href="<?php echo PROY_URL; ?>aplicaciones_desactualizadas" title="">Desactualizadas</a> – aplicaciones que  no han sido marcadas como válidas o inválidas; y que no han sido actualizadas por el agente US que lleva el caso en los últimos 2 días.</li>
<li><a href="<?php echo PROY_URL; ?>aplicaciones_pendientes" title="">Sin tomar (libres)</a> – aplicaciones que no han sido tomadas por ningún agente US</li>
<li><a href="<?php echo PROY_URL; ?>aplicaciones_esperando" title="">Sin procesar</a> – aplicaciones que no han sido marcadas con algun estado (válida/inválida/imposible/vendida)</li>
</ul>

<?php if (in_array(_F_usuario_cache('nivel'), array(_N_administrador_sv,_N_administrador_us))):?>
<p class="subtitulo">Los siguientes grupos de aplicaciones se muestran por que Ud. esta en el grupo de adminsitradores</p>
<ul id="id">
    <li><a href="<?php echo PROY_URL; ?>aplicaciones_grupo?fecha_ingresada=now" title="">Solo de mi grupo</a> – Por defecto muestra las del día actual.</li>
    <li><a href="<?php echo PROY_URL; ?>aplicaciones_vigiladas" title="">Vigiladas</a></li>
</ul>
<?php endif; ?>
<p class="medio-oculto">NOTA: Debe seleccionar una opción para continuar.</p>
