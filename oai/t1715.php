<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.12.5b sábado, 26 de marzo de 2016
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!file_exists('app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require '../config.php';
require 'app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libs/clsplanos.php';
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$idPeraca=0;
$bEntra=false;
if (isset($_REQUEST['idperaca'])!=0){$idPeraca=numeros_validar($_REQUEST['idperaca']);}
if ($idPeraca!=0){
	$cSepara=',';
	$cEvita=';';
	$cComplementa='.';
	if (isset($_REQUEST['separa'])!=0){
		if ($_REQUEST['separa']==';'){
			$cSepara=';';
			$cEvita=',';
			}
		}
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$sNombrePeraca='Periodo: {'.$idPeraca.'}';
	$sql='SELECT exte02nombre, exte02titulo FROM exte02per_aca WHERE exte02id='.$idPeraca;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$sNombrePeraca='Periodo: '.utf8_decode($fila['exte02nombre']);
		}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t1715.csv';
	$sTituloRpt='plantilla_responsables_'.$idPeraca;
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Sistema de Oferta Académica - Proceso Responables Campus Virtual');
	$objplano->AdicionarLinea($sDato);
	$objplano->AdicionarLinea($sNombrePeraca);
	$sDato='Codigo Curso'.$cSepara.'Nombre Curso'.$cSepara.'Documento'.$cSepara.'Nombre Responsable';
	$objplano->AdicionarLinea($sDato);
	$sql='SELECT TB.ofer08idresponsablepti, TB.ofer08idcurso, T1.unad40nombre, T2.unad11doc, T2.unad11razonsocial 
FROM ofer08oferta AS TB LEFT JOIN unad11terceros AS T2 ON (TB.ofer08idresponsablepti=T2.unad11id), unad40curso AS T1  
WHERE TB.ofer08idper_aca='.$idPeraca.' AND TB.ofer08estadooferta=1 AND TB.ofer08idcurso=T1.unad40id';
	$tabla=$objdb->ejecutasql($sql);
	while($fila=$objdb->sf($tabla)){
		$sTituloCurso=str_replace($cSepara, $cComplementa, utf8_decode($fila['unad40nombre']));
		$sDoc='';
		$sRazonSocial='';
		if ($fila['ofer08idresponsablepti']!=0){
			$sDoc=$fila['unad11doc'];
			$sRazonSocial=str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11razonsocial']));
			}
		$sDato=$fila['ofer08idcurso'].$cSepara.$sTituloCurso.$cSepara.$sDoc.$cSepara.$sRazonSocial;
		$objplano->AdicionarLinea($sDato);
		}
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}else{
	echo 'No ha definido el periodo a generar.';
	}
?>