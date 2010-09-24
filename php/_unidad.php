<?php
protegerme();
set_time_limit(0);
if (isset($_GET['add']))
{
    $_POST['PME_sys_sfn[0]']='0';
    $_POST['PME_sys_fl']='0';
    $_POST['PME_sys_qfn']='';
    $_POST['PME_sys_fm']='0';
    $_POST['PME_sys_rec']='1';
    $_POST['PME_sys_operation']='Add';
}

require_once('php/_unidad_PME.php');
?>
