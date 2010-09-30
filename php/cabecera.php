<table id="cabecera">
    <tbody>
        <tr>
            <td id="logotipo">
		<script type="text/javascript">
		$(function($) {
		    $('#jclock').jclock({timeNotation: '12h',am_pm: true,utc: true,utc_offset: -6});
		    $('#jclock2').jclock({timeNotation: '12h',am_pm: true,utc: true,utc_offset: -7});
		    $('#jclock3').jclock({timeNotation: '12h',am_pm: true,utc: true,utc_offset: -5});
		    $('#jclock4').jclock({timeNotation: '12h',am_pm: true,utc: true,utc_offset: -4});
		});
		</script>

		<table class="mini tabla-estandar">
		    <tr><th>California</th><td><span id="jclock2"></span></td></tr>
		    <tr><th>San Salvador</th><td><span id="jclock"></span></td></tr>
		    <tr><th>Chicago</th><td><span id="jclock3"></span></td></tr>
		    <tr><th>Virginia</th><td><span id="jclock4"></span></td></tr>
		</table>
            </td>
	    <td id="centro">
                <span style="font-weight:bolder;">United First Solutions</span>
		<div class="mini">
		    <?php if (S_iniciado() && (in_array(_F_usuario_cache('nivel'), array(_N_administrador_us,_N_agente_us,_N_agente_us_solo)))): ?>
		    Headquarters | California
		    <? else: ?>
		    Call Center | El Salvador
		    <?php endif; ?>
		</div>
		<div class="tiny">UFS Online Network</div>
	    </td>
            <td id="telefonos">
		<?php if (S_iniciado() && (in_array(_F_usuario_cache('nivel'), array(_N_administrador_us,_N_agente_us,_N_agente_us_solo)))): ?>
		    <img src="img/bandera_USA.gif" alt="Bandera de Estados Unidos" /><br />
		<? else: ?>
		    <img src="img/bandera_SLV.gif" alt="Bandera de El Salvador" /><br />
		<?php endif; ?>
                <p class="medio-oculto">
                    <span style="color:#F00;font-weight:bolder;"><?php echo PROY_TELEFONO_PRINCIPAL; ?></span><br />
                    <?php if (S_iniciado()): ?>
			<a rel="nofollow" href="<?php echo PROY_URL ?>final" title="">Salir</a>
                    <?php endif; ?>
                </p>
            </td>
    </tbody>
</table>
