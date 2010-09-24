<?php

if (isset($view_mode))
{
    $ID_orden = db_obtener(db_prefijo.'ordenes','ID_orden','ID_seguimiento="'.$_GET['track_id'].'"');
    $_POST['PME_sys_sfn[0]']='0';
    $_POST['PME_sys_fl']='0';
    $_POST['PME_sys_qfn']='';
    $_POST['PME_sys_fm']='0';
    $_POST['PME_sys_rec']=$ID_orden;
    $_POST['PME_sys_operation']='View';
    $_POST['PME_sys_navfmdown']='0';
}

$opts['dbh'] = $db_link;
$opts['page_name'] = PROY_URL_ACTUAL;
$opts['tb'] = db_prefijo.'ordenes';

// Name of field which is the unique key
$opts['key'] = 'ID_orden';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('ID_orden');

// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = 15;

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'ACPVDF';

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => false,
	'tabs'  => true
);

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

$opts['language'] = 'EN';

$opts['fdd']['ID_orden'] = array(
  'name'     => 'Order ID',
  'select'   => 'T',
  'options'  => 'AVCPDR', // auto increment
  'maxlen'   => 10,
  'default'  => '0',
  'sort'     => true
);

$opts['fdd']['ID_seguimiento'] = array(
  'name'     => 'Tracking ID',
  'select'   => 'T',
  'maxlen'   => 60,
  'options'  => 'AVLCPDR', // auto increment
  'sort'     => true
);
$opts['fdd']['ID_seguimiento']['sqlw'] = 'IF(ID_seguimiento, $val_qas, '.time().')';

$opts['fdd']['fecha_solicitado'] = array(
  'name'     => 'Date of order',
  'select'   => 'T',
  'maxlen'   => 750,
  'options'  => 'AVCPDR', // auto increment
  'sort'     => true
);

$opts['fdd']['fecha_solicitado']['sqlw'] = 'IF(fecha_solicitado, $val_qas, NOW())';

$opts['fdd']['ID_usuario'] = array(
  'name'     => 'Client',
  'select'   => 'T',
  'maxlen'   => 10,
  'sort'     => true
);
$opts['fdd']['ID_usuario']['values']['table']       = db_prefijo.'usuarios';
$opts['fdd']['ID_usuario']['values']['column']      = 'ID_usuario';
$opts['fdd']['ID_usuario']['values']['description'] = 'razon_social'; // optional

$opts['fdd']['tipo'] = array(
  'name'     => 'Type',
  'select'   => 'T',
  'maxlen'   => 54,
  'values'   => array(
                  "Dry container",
                  "Reefer",
                  "Open top container",
                  "Gen set",
                  "Trailer",
                  "Chassis",
                  "Rescue"),
  'sort'     => true
);
$opts['fdd']['identificador'] = array(
  'name'     => 'Identifier',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['identificador2'] = array(
  'name'     => 'Identifier 2',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['identificador3'] = array(
  'name'     => 'Identifier 3',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['dias_predio'] = array(
  'name'     => 'Days on depot',
  'select'   => 'T',
  'maxlen'   => 10,
  'sort'     => true
);
$opts['fdd']['Origen'] = array(
  'name'     => 'Origin',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['procedencia'] = array(
  'name'     => 'Procedence',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['caracteristicas'] = array(
  'name'     => 'Specifications',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['notas_estado'] = array(
  'name'     => 'Repair details',
  'select'   => 'T',
  'maxlen'   => 750,
  'options'  => 'AVCPD',
  'sort'     => true
);
$opts['fdd']['fecha_entrega'] = array(
  'name'     => 'Delivery date',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['precio'] = array(
  'name'     => 'Price',
  'select'   => 'T',
  'maxlen'   => 12,
  'sort'     => true
);
$opts['fdd']['imagen'] = array(
  'name'     => 'Image',
  'select'   => 'T',
  'maxlen'   => -1,
  'sort'     => true,
  'options'  => 'AVCPD',
  'input'    =>  'F'
);
$opts['fdd']['imagen2'] = array(
  'name'     => 'Image 2',
  'select'   => 'T',
  'maxlen'   => -1,
  'sort'     => true,
  'options'  => 'AVCPD',
  'input'    =>  'F'
);
$opts['fdd']['imagen3'] = array(
  'name'     => 'Image 3',
  'select'   => 'T',
  'maxlen'   => -1,
  'sort'     => true,
  'options'  => 'AVCPD',
  'input'    =>  'F'
);
$opts['fdd']['imagen4'] = array(
  'name'     => 'Image 4',
  'select'   => 'T',
  'maxlen'   => -1,
  'sort'     => true,
  'options'  => 'AVCPD',
  'input'    =>  'F'
);
// Now important call to phpMyEdit
new phpMyEdit($opts);
?>
