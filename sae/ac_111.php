<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.14.5 sabado, 23 de julio de 2016
*/
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'libtextos.php';
if (isset($_GET['q'])==0){return;}
// Si no necesita variables de session puede quitar el inicio de sesion
session_start();
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
$sql='SELECT unad11tipodoc, unad11doc, unad11razonsocial, unad11bloqueado 
FROM unad11terceros 
WHERE CONCAT(unad11tipodoc, unad11doc, unad11razonsocial) LIKE "%'.$_GET['q'].'%" AND unad11id>0
ORDER BY unad11razonsocial LIMIT 0,10';
$tabla=$objdb->ejecutasql($sql);
if ($objdb->nf($tabla)==0){return;}
while($fila=$objdb->sf($tabla)){
	$sPref='';
	$sSuf='';
	if ($fila['unad11bloqueado']=='S'){
		$sPref='<span style="color:#FF0000">';
		$sSuf='</span>';
		}
	$campo1=$fila['unad11tipodoc'].$fila['unad11doc'].' '.texto_ParaHtml($fila['unad11razonsocial']);
	$campo2=$fila['unad11doc'].'|'.$fila['unad11tipodoc'];
	echo $campo1.'|'.$campo2."\n";
	}
echo '|';
return;
?>