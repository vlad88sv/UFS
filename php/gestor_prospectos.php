<?php

/*
 * IMPORTANT NOTE: This generated file contains only a subset of huge amount
 * of options that can be used with phpMyEdit. To get information about all
 * features offered by phpMyEdit, check official documentation. It is available
 * online and also for download on phpMyEdit project management page:
 *
 * http://platon.sk/projects/main_page.php?project_id=5
 *
 * This file was generated by:
 *
 *                    phpMyEdit version: 5.7.1
 *       phpMyEdit.class.php core class: 1.204
 *            phpMyEditSetup.php script: 1.50
 *              generating setup script: 1.50
 */

// MySQL host name, user name, password, database, and table
$opts['dbh'] = $db_link;
$opts['page_name'] = PROY_URL_ACTUAL;
$opts['tb'] = db_prefijo.'producto_contenedor';

// Name of field which is the unique key
$opts['key'] = 'ID_prospecto';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('ID_prospecto');

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
	'time'  => true,
	'tabs'  => true
);

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

/* Get the user's default language and use it if possible or you can
   specify particular one you want to use. Refer to official documentation
   for list of available languages. */
$opts['language'] = 'ES-AR-UTF8';

/* Table-level filter capability. If set, it is included in the WHERE clause
   of any generated SELECT statement in SQL query. This gives you ability to
   work only with subset of data from table.

$opts['filters'] = "column1 like '%11%' AND column2<17";
$opts['filters'] = "section_id = 9";
$opts['filters'] = "PMEtable0.sessions_count > 200";
*/

/* Field definitions

Fields will be displayed left to right on the screen in the order in which they
appear in generated list. Here are some most used field options documented.

['name'] is the title used for column headings, etc.;
['maxlen'] maximum length to display add/edit/search input boxes
['trimlen'] maximum length of string content to display in row listing
['width'] is an optional display width specification for the column
          e.g.  ['width'] = '100px';
['mask'] a string that is used by sprintf() to format field output
['sort'] true or false; means the users may sort the display on this column
['strip_tags'] true or false; whether to strip tags from content
['nowrap'] true or false; whether this field should get a NOWRAP
['select'] T - text, N - numeric, D - drop-down, M - multiple selection
['options'] optional parameter to control whether a field is displayed
  L - list, F - filter, A - add, C - change, P - copy, D - delete, V - view
            Another flags are:
            R - indicates that a field is read only
            W - indicates that a field is a password field
            H - indicates that a field is to be hidden and marked as hidden
['URL'] is used to make a field 'clickable' in the display
        e.g.: 'mailto:$value', 'http://$value' or '$page?stuff';
['URLtarget']  HTML target link specification (for example: _blank)
['textarea']['rows'] and/or ['textarea']['cols']
  specifies a textarea is to be used to give multi-line input
  e.g. ['textarea']['rows'] = 5; ['textarea']['cols'] = 10
['values'] restricts user input to the specified constants,
           e.g. ['values'] = array('A','B','C') or ['values'] = range(1,99)
['values']['table'] and ['values']['column'] restricts user input
  to the values found in the specified column of another table
['values']['description'] = 'desc_column'
  The optional ['values']['description'] field allows the value(s) displayed
  to the user to be different to those in the ['values']['column'] field.
  This is useful for giving more meaning to column values. Multiple
  descriptions fields are also possible. Check documentation for this.
*/

$opts['fdd']['ID_prospecto'] = array(
  'name'     => 'ID prospecto',
  'select'   => 'T',
  'options'  => 'AVCPDR', // auto increment
  'maxlen'   => 10,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['situacion'] = array(
  'name'     => 'Situacion',
  'select'   => 'T',
  'maxlen'   => 36,
  'values'   => array(
                  "nuevo",
                  "aplicado",
                  "luego",
                  "recordatorio",
                  "descartado"),
  'sort'     => true
);
$opts['fdd']['ultima_presentacion'] = array(
  'name'     => 'Ultima presentacion',
  'select'   => 'T',
  'maxlen'   => 10,
  'sort'     => true
);
$opts['fdd']['intentos'] = array(
  'name'     => 'Intentos',
  'select'   => 'T',
  'maxlen'   => 11,
  'sort'     => true
);
$opts['fdd']['apellido'] = array(
  'name'     => 'Apellido',
  'select'   => 'T',
  'maxlen'   => 300,
  'sort'     => true
);
$opts['fdd']['nombre'] = array(
  'name'     => 'Nombre',
  'select'   => 'T',
  'maxlen'   => 300,
  'sort'     => true
);
$opts['fdd']['direccion1'] = array(
  'name'     => 'Direccion1',
  'select'   => 'T',
  'maxlen'   => 196605,
  'textarea' => array(
    'rows' => 5,
    'cols' => 50),
  'sort'     => true
);
$opts['fdd']['direccion2'] = array(
  'name'     => 'Direccion2',
  'select'   => 'T',
  'maxlen'   => 196605,
  'textarea' => array(
    'rows' => 5,
    'cols' => 50),
  'sort'     => true
);
$opts['fdd']['especial1'] = array(
  'name'     => 'Especial1',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['ciudad'] = array(
  'name'     => 'Ciudad',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['estado'] = array(
  'name'     => 'Estado',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['zip'] = array(
  'name'     => 'Zip',
  'select'   => 'T',
  'maxlen'   => 45,
  'sort'     => true
);
$opts['fdd']['telefono'] = array(
  'name'     => 'Telefono',
  'select'   => 'T',
  'maxlen'   => 45,
  'sort'     => true
);
$opts['fdd']['especial2'] = array(
  'name'     => 'Especial2',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial3'] = array(
  'name'     => 'Especial3',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial4'] = array(
  'name'     => 'Especial4',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial5'] = array(
  'name'     => 'Especial5',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial6'] = array(
  'name'     => 'Especial6',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial7'] = array(
  'name'     => 'Especial7',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial8'] = array(
  'name'     => 'Especial8',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial9'] = array(
  'name'     => 'Especial9',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial10'] = array(
  'name'     => 'Especial10',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial11'] = array(
  'name'     => 'Especial11',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial13'] = array(
  'name'     => 'Especial13',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial14'] = array(
  'name'     => 'Especial14',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial15'] = array(
  'name'     => 'Especial15',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial16'] = array(
  'name'     => 'Especial16',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial17'] = array(
  'name'     => 'Especial17',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial18'] = array(
  'name'     => 'Especial18',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial19'] = array(
  'name'     => 'Especial19',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial20'] = array(
  'name'     => 'Especial20',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['especial21'] = array(
  'name'     => 'Especial21',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['interes'] = array(
  'name'     => 'Interes',
  'select'   => 'T',
  'maxlen'   => 45,
  'sort'     => true
);

// Now important call to phpMyEdit
new phpMyEdit($opts);

?>
