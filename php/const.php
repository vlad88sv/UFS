<?php
// Proyecto
define('PROY_URL',preg_replace(array("/\/?$/","/www./"),"","http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']))."/");
define('PROY_URL_AMIGABLE',"www.".preg_replace(array("/\/?$/","/www./"),"",$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']))."/");
define('PROY_URL_ACTUAL_DINAMICA',curPageURL(false));
define('PROY_URL_ACTUAL',curPageURL(true));
define('PROY_URL_ACTUAL_AMIGABLE',curPageURL(true,true));

define('PROY_NOMBRE','UFS - United First Solutions');
define('PROY_NOMBRE_CORTO','UFS');
define('PROY_TELEFONO','PBX (503) 2-224-2841');
define('PROY_TELEFONO_PRINCIPAL','PBX (503) 2-224-2841');
define('PROY_MAIL_POSTMASTER_NOMBRE',PROY_NOMBRE.' ');
define('PROY_MAIL_POSTMASTER','<info@'.$_SERVER['HTTP_HOST'].'>');
define('PROY_MAIL_REPLYTO_NOMBRE',PROY_NOMBRE.' ');
define('PROY_MAIL_REPLYTO','<info@'.$_SERVER['HTTP_HOST'].'>');
define('PROY_MAIL_BROADCAST_NOMBRE',PROY_NOMBRE.' ');
define('PROY_MAIL_BROADCAST','<broadcast@'.$_SERVER['HTTP_HOST'].'>');

// Niveles
define('_N_administrador_sv',   'administrador_sv');
define('_N_administrador_us',   'administrador_us');
define('_N_agente_sv',          'agente_sv');
define('_N_agente_us',          'agente_us');
define('_N_agente_us_solo',     'agente_us_solo');

// Tipos de entrada de historial
define ('_T_historial_comentario',  0);
define ('_T_historial_sistema',     1);
define ('_T_historial_advertencia', 2);
define ('_T_historial_urgente',     3);

/** DB **/
define('db__host','localhost');
define('db__usuario','mupi_ufs');
define('db__clave','007jb1');
define('db__db','mupi_ufs');
define('db_prefijo','ufs__');

define('_M_INFO','info');
define('_M_ERROR','error');
define('_M_NOTA','nota');

// Otros
define('STOPWORDS',serialize(array('para','de','la','que','el','en','y','a','los','del','se','las','por','un','con','no','una','su','al','es','lo','como','más','pero','sus','le','ya','o','fue','este','ha','sí','porque','esta','son','entre','está','cuando','muy','sin','sobre','ser','tiene','también','me','hasta','hay','donde','han','quien','están','estado','desde','todo','nos','durante','estados','todos','uno','les','ni','contra','otros','fueron','ese','eso','había','ante','ellos','e','esto','mí','antes','algunos','qué','unos','yo','otro','otras','otra','él','tanto','esa','estos','mucho','quienes','nada','muchos','cual','sea','poco','ella','estar','haber','estas','estaba','estamos','algunas','algo','nosotros','mi','mis','tú','te','ti','tu','tus','ellas','nosotras','vosotros','vosotras','os','mío','mía','míos','mías','tuyo','tuya','tuyos','tuyas','suyo','suya','suyos','suyas','nuestro','nuestra','nuestros','nuestras','vuestro','vuestra','vuestros','vuestras','esos','esas','','','estoy','estás','está','estamos','estáis','están','esté','estés','estemos','estéis','estén','estaré','estarás','estará','estaremos','estaréis','estarán','estaría','estarías','estaríamos','estaríais','estarían','estaba','estabas','estábamos','estabais','estaban','estuve','estuviste','estuvo','estuvimos','estuvisteis','estuvieron','estuviera','estuvieras','estuviéramos','estuvierais','estuvieran','estuviese','estuvieses','estuviésemos','estuvieseis','estuviesen','estando','estado','estada','estados','estadas','estad','he','has','ha','hemos','habéis','han','haya','hayas','hayamos','hayáis','hayan','habré','habrás','habrá','habremos','habréis','habrán','habría','habrías','habríamos','habríais','habrían','había','habías','habíamos','habíais','habían','hube','hubiste','hubo','hubimos','hubisteis','hubieron','hubiera','hubieras','hubiéramos','hubierais','hubieran','hubiese','hubieses','hubiésemos','hubieseis','hubiesen','habiendo','habido','habida','habidos','habidas','soy','eres','es','somos','sois','son','sea','seas','seamos','seáis','sean','seré','serás','será','seremos','seréis','serán','sería','serías','seríamos','seríais','serían','era','eras','éramos','erais','eran','fui','fuiste','fue','fuimos','fuisteis','fueron','fuera','fueras','fuéramos','fuerais','fueran','fuese','fueses','fuésemos','fueseis','fuesen','siendo','sido','tengo','tienes','tiene','tenemos','tenéis','tienen','tenga','tengas','tengamos','tengáis','tengan','tendré','tendrás','tendrá','tendremos','tendréis','tendrán','tendría','tendrías','tendríamos','tendríais','tendrían','tenía','tenías','teníamos','teníais','tenían','tuve','tuviste','tuvo','tuvimos','tuvisteis','tuvieron','tuviera','tuvieras','tuviéramos','tuvierais','tuvieran','tuviese','tuvieses','tuviésemos','tuvieseis','tuviesen','teniendo','tenido','tenida','tenidos','tenidas','tened')));
?>
