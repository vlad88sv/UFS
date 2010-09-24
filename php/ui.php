<?php
function ui_destruir_vacios($cadena)
{
return preg_replace("/(\s)?\w+=\"\"/","",$cadena);
}
function ui_img ($id_gui, $src,$alt="[Imagen no puso ser cargada]"){
	return ui_destruir_vacios('<img id="'.$id_gui.'" alt="'.$alt.'" src="'.$src.'" />');
}
function ui_href ($id_gui, $href, $texto, $clase="", $extra=""){
	return ui_destruir_vacios('<a id="'.$id_gui.'" href="'.$href.'" class="' . $clase . '" ' . $extra . '>'.$texto.'</a>');
}
function ui_A ($id_gui, $texto, $clase="", $extra=""){
	return '<a id="'.$id_gui.'" class="' . $clase . '" ' . $extra . '>'.$texto.'</a>';
}
function ui_combobox ($id_gui, $opciones, $selected = "", $clase="", $estilo="") {
	$opciones = str_replace('value="'.$selected.'"', 'selected="selected" value="'.$selected.'"', $opciones);
	return '<select id="' . $id_gui . '" name="' . $id_gui . '" style="' . $estilo . '">'. $opciones . '</select>';
}
function ui_input ($id_gui, $valor="", $tipo="text", $clase="", $estilo="", $extra ="") {
	$tipo = empty($tipo) ? "text" : $tipo;
	return '<input type="'.$tipo.'" id="' . $id_gui . '" name="' . $id_gui . '" class="' . $clase . '" style="' . $estilo . '" value="' . $valor .'" '.$extra.'></input>';
}
function ui_textarea ($id_gui, $valor="", $clase="", $estilo="") {
	return "<textarea id='$id_gui' name='$id_gui' class='$clase' style='$estilo'>$valor</textarea>";
}
function ui_th ($valor, $clase="") {
	return "<th class='$clase'>$valor</td>";
}
function ui_td ($valor, $clase="", $estilo="") {
	return "<td class='$clase' style='$estilo'>$valor</td>";
}
function ui_tr ($valor) {
	return "<tr>$valor</tr>";
}
function ui_optionbox_nosi ($id_gui, $valorNo = 0, $valorSi = 1, $TextoSi = "Si", $TextoNo = "No", $default) {
	$default_no = ($default == $valorNo ? 'checked="checked"' : '');
	$default_si = ($default == $valorSi ? 'checked="checked"' : '');
	return "<input id='$id_gui' name='$id_gui' type='radio' $default_no value='$valorNo'>$TextoNo</input>" . '&nbsp;&nbsp;&nbsp;&nbsp;'."<input id='$id_gui' name='$id_gui' type='radio' $default_si value='$valorSi'>$TextoSi</input>";
}
function ui_combobox_o_meses (){
	$opciones = '';
	for ($i = 1; $i < 13; $i++) {
		$opciones .= '<option value=$i>'.strftime('%B', mktime (0,0,0,$i,1,2009)).'</option>';
	}
	return $opciones;
}
function ui_combobox_o_anios (){
	$opciones = '';
	for ($i = 0; $i < 13; $i++) {
		$opciones .= '<option value=$i>'.(date('Y') - $i).'</option>';
	}
	return $opciones;
}

function ui_array_a_opciones($array)
{
	$buffer = '';
	foreach ($array as $valor => $texto)
	{
		$buffer .= '<option value="'.$valor.'">'.$texto.'</option>'."\n";
	}

	return $buffer;
}

function HEAD_JS()
{
    global $arrJS;
    $arrJS = array_unique($arrJS);
    require_once ('php/jsmin-1.1.1.php');
    echo "\n";
    $buffer = '';
    foreach ($arrJS as $JS)
    {
        //$buffer .= '<script type="text/javascript">'.JSMin::minify(file_get_contents("js/".$JS.".js"))."</script>\n";
        $buffer .= '<script type="text/javascript" src="js/'.$JS.'.js"></script>'."\n";
    }

    echo $buffer;
    echo "\n";
}

function HEAD_CSS()
{
    global $arrCSS;
    $arrCSS = array_unique($arrCSS);
    $buffer = '';
    foreach ($arrCSS as $CSS)
    {
        $buffer .= '<style type="text/css">'.file_get_contents($CSS.".css")."</style>\n";
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

        //$buffer .= '<link rel="stylesheet" type="text/css" href="'.$CSS.'.css" />'."\n";

    }
    echo $buffer;
    echo "\n";
}

function HEAD_EXTRA()
{
    global $arrHEAD;
    echo "\n";
    echo implode("\n",$arrHEAD);
    echo "\n";
}

?>
