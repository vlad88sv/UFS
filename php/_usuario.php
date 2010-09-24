<?php
$opts['dbh'] = $db_link;
$opts['page_name'] = PROY_URL_ACTUAL;
$opts['tb'] = db_prefijo.'usuarios';

// Name of field which is the unique key
$opts['key'] = 'ID_usuario';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('ID_usuario');

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

$opts['language'] = 'ES-AR-UTF8';

$opts['fdd']['ID_usuario'] = array(
  'name'     => 'User ID',
  'select'   => 'T',
  'options'  => 'AVCPDR', // auto increment
  'maxlen'   => 11,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['razon_social'] = array(
  'name'     => 'Razon social',
  'select'   => 'T',
  'maxlen'   => 750,
  'sort'     => true
);
$opts['fdd']['nivel'] = array(
  'name'     => 'Nivel',
  'select'   => 'T',
  'maxlen'   => 39,
  'values'   => array(
                  "cliente",
                  "administrador"),
  'sort'     => true
);
$opts['fdd']['correo'] = array(
  'name'     => 'Correo',
  'select'   => 'T',
  'maxlen'   => 600,
  'sort'     => true
);
$opts['fdd']['usuario'] = array(
  'name'     => 'Usuario',
  'select'   => 'T',
  'maxlen'   => 600,
  'sort'     => true
);
$opts['fdd']['clave'] = array(
  'name'     => 'Password',
  'select'   => 'T',
  'maxlen'   => 120,
  'options'  => 'ACPDV',
  'sort'     => false
);
$opts['fdd']['clave']['sqlw'] = 'IF(clave = $val_qas, $val_qas, SHA1($val_qas))';

$opts['fdd']['permiso_rescates'] = array(
  'name'     => 'Permiso rescates',
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['permiso_contenedores'] = array(
  'name'     => 'Permiso contenedores',
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['permiso_chasis'] = array(
  'name'     => 'Permiso chasis',
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['permiso_gensets'] = array(
  'name'     => 'Permiso gensets',
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['permiso_reefer'] = array(
  'name'     => 'Permiso reefer',
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);

// Now important call to phpMyEdit
new phpMyEdit($opts);
?>


