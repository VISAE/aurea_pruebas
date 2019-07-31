<?php
session_start();
$_SESSION['unad_id_tercero']=1;
$_SESSION['unad_id_sesion']=0;
$USER=new stdclass();
$USER->id=2;
$_SESSION['USER']=$USER;

header('Location:panel/');
?>
