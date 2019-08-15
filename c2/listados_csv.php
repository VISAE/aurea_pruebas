<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.10 sábado, 2 de diciembre de 2014
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!file_exists('app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
//require_once '../config.php';
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libs/clsplanos.php';
if ($_SESSION['unad_id_tercero']==0){
	echo 'No hay un tercero';
	die();
	header("Location:index.php");
	die();
	}
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
$sql=str_replace("|","'",$_REQUEST['consulta']);
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
$tabla=$objdb->ejecutasql($sql);
if ($tabla==false){
	echo '<!-- '.$sql.' -->';
	die();
	}
if (isset($_REQUEST['paso'])==0){$_REQUEST['paso']=51;}
if ($_REQUEST['paso']==51){
	$cSepara=',';
	$cComplementa=';';
	if ($_REQUEST['csv_separa']!=','){
		$cSepara=';';
		$cComplementa=',';
		}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='tmp.csv';
	$sNombrePlanoFinal='listados.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$etiqueta=explode(",",$_REQUEST['titulos']);
	$campos=count($etiqueta);
	$sDato='';
	for ($i=1;$i<=$campos;$i++){
		$sTitulo='';
		if (isset($etiqueta[$i-1])!=0){$sTitulo=$etiqueta[$i-1];}
		if ($i==1){
			$sDato=$sTitulo;
			}else{
			$sDato=$sDato.$cSepara.$sTitulo;
			}
		}
	$objplano->AdicionarLinea($sDato);
	while ($fila=$objdb->sf($tabla)){
		$sDato='';
		for ($i=1;$i<=$campos;$i++){
			$sCuerpo='';
			if (isset($fila[$i-1])!=0){
				$sCuerpo=utf8_decode($fila[$i-1]);
				$sCuerpo=str_replace($cSepara, $cComplementa, $sCuerpo);
				//$sCuerpo=str_replace('', chr(13), $sCuerpo);
				}
			if ($i==1){
				$sDato=$sCuerpo;
				}else{
				$sDato=$sDato.$cSepara.$sCuerpo;
				}
			}
		$objplano->AdicionarLinea($sDato);
		}
	//descargar el resultado
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}
?>