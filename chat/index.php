<?php
setlocale                   (LC_ALL, 'es_AR.UTF-8', 'es_ES.UTF-8');
date_default_timezone_set   ('America/El_Salvador');
$base = dirname(__FILE__).'/../php';
require_once ("$base/stubs.php"); // Constantes
require_once ("$base/const.php"); // Constantes
require_once ("$base/seguridad.php"); // Constantes
require_once ("$base/sesion.php"); // Sesión
require_once ("$base/usuario.php"); // Gestión de nombre_completos

protegerme(false,true);
require_once dirname(__FILE__)."/src/phpfreechat.class.php";
$params = array();
$params["serverid"] = md5(PROY_NOMBRE_CORTO); // calculate a unique id for this chat
$params["title"] = "UFS SV";
$params["nick"] = _F_usuario_cache('usuario');
$params["frozen_nick"] = true;

if (in_array(_F_usuario_cache('nivel'),array(_N_administrador_sv,_N_administrador_us)))
$params["isadmin"] = true;

$params["serverid"] = md5(__FILE__); // calculate a unique id for this chat
$params["debug"] = false;
$params["language"] = "es_ES";
$params["container_type"] = 'mysql';
$params["container_cfg_mysql_host"]     = 'localhost';
$params["container_cfg_mysql_port"]     = 3306;
$params["container_cfg_mysql_database"] = db__db;
$params["container_cfg_mysql_table"]    = db_prefijo.'phpfreechat';
$params["container_cfg_mysql_username"] = db__usuario;
$params["container_cfg_mysql_password"] = db__clave;
$params["channels"] = array('UFS CHAT');

$chat = new phpFreeChat( $params );
$chat->printChat();
?>
