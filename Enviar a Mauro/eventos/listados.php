<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 1.0.0 martes, 29 de julio de 2014
--- Miercoles 22 de Octubre de 2014 - Se arregla problema con la falta de campos o de etiquetas.
--- Martes 5 de Abril de 2016 - Se agrega el parametro titulo lista y se usa el err_contro.php
*/
if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
//require_once '../config.php';
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
if ($_SESSION['unad_id_tercero']==0){
	echo 'No hay un tercero';
	die();
	header("Location:index.php");
	die();
	}
set_time_limit(0);
$sql=str_replace("|","'",$_REQUEST['consulta']);
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
$result=$objdb->ejecutasql($sql);
if ($result==false){
	echo '<!-- '.$sql.' -->';
	die();
	}
if (isset($_REQUEST['paso'])==0){$_REQUEST['paso']=51;}
if ($_REQUEST['paso']==51){
	if (isset($_REQUEST['formato'])==0){$_REQUEST['formato']='listados.xlsx';}
	if (isset($_REQUEST['titulolista'])==0){
		if (isset($_REQUEST['nombrearchivo'])==0){
			$_REQUEST['titulolista']='listados.xlsx';
			}else{
			$_REQUEST['titulolista']=$_REQUEST['nombrearchivo'].'.xlsx';
			}
		}
	if (!file_exists($_REQUEST['formato'])){
		echo 'No se encuentra el formato '.$_REQUEST['formato'];
		die();
		}
	$sArchivo=$_REQUEST['formato'];
	$sTituloLista=$_REQUEST['titulolista'];
	require $APP->rutacomun.'excel/PHPExcel.php';
	require $APP->rutacomun.'excel/PHPExcel/Writer/Excel2007.php';
	$objReader=@PHPExcel_IOFactory::createReader('Excel2007');
	if (!is_object($objReader)){
		echo 'No fue posible iniciar el generador de archivos, por favor comuniquese con el administrador del sistema.';
		die();
		}
	$objPHPExcel=@$objReader->load($sArchivo);
	if (!is_object(@$objPHPExcel->getActiveSheet())){
		echo 'El formato se cargo en forma correcta, pero no fue posible leerlo.';
		die();
		}
	$objPHPExcel->getProperties()->setCreator("Mauro Avellaneda - http://www.mauroavellaneda.com/");
	$objPHPExcel->getProperties()->setLastModifiedBy("Mauro Avellaneda - http://www.unad.edu.co/");
	$objPHPExcel->getProperties()->setTitle('Listados');
	$objPHPExcel->getProperties()->setSubject('Listados');
	$objPHPExcel->getProperties()->setDescription("Listados en http://campus.unad.edu.co/");
	$ifila=6;
	$etiqueta=explode(",",$_REQUEST['titulos']);
	$campos=count($etiqueta);
	for ($i=1;$i<=$campos;$i++){
		$sTitulo='';
		if (isset($etiqueta[$i-1])!=0){$sTitulo=$etiqueta[$i-1];}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i-1,5, $sTitulo);
		}
	while($row=$objdb->sf($result)) {
		for ($i=1;$i<=$campos;$i++){
			$sDato='';
			if (isset($row[$i-1])!=0){$sDato=utf8_decode($row[$i-1]);}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i-1,$ifila, utf8_encode($sDato));
			}
		$ifila++;
		}
	//descargar el resultado
	header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); //la pagina expira en una fecha pasada
	header('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT'); //ultima actualizacion ahora cuando la cargamos
	header('Cache-Control: no-cache, must-revalidate'); //no guardar en CACHE
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$sTituloLista.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output'); 
	die();
	}
?>