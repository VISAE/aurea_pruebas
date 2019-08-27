<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo 2.23.5 Tuesday, August 27, 2019
*/
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'libtextos.php';
if (isset($_GET['q'])==0){return;}
/* Si no necesita variables de session puede quitar el inicio de sesion */
session_start();
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
$sSQL='SELECT unad40consec, unad40nombre FROM unad40curso WHERE CONCAT(unad40consec, unad40nombre) LIKE "%'.$_GET['q'].'%" LIMIT 0,20';
$tabla=$objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla)==0){return;}
while($fila=$objDB->sf($tabla)){
	$campo1=$fila['unad40consec'].' '.texto_ParaHtml($fila['unad40nombre']).'';
	$campo2=$fila['unad40consec'];
	echo $campo1.'|'.$campo2."\n";
	}
$objDB->CerrarConexion();
echo '|';
return;
?>