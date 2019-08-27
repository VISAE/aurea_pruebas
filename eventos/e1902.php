<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.5 Tuesday, August 27, 2019
*/
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'excel/PHPExcel.php';
require $APP->rutacomun.'excel/PHPExcel/Writer/Excel2007.php';
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$iReporte=0;
$bEntra=false;
$bDebug=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==1902){$bEntra=true;}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$bEntra=false;
	$sTituloRpt='Reporte';
	$sFormato='formato.xlsx';
	if ($sError==''){
		if (!file_exists($sFormato)){
			$sError='Formato no encontrado {'.$sFormato.'}';
			}
		}
	/* if (isset($_REQUEST['v3'])==0){($_REQUEST['v3']='');} */
	if ($sError==''){
		$objReader=PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel=$objReader->load($sFormato);
		$objPHPExcel->getProperties()->setCreator('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setLastModifiedBy('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setTitle($sTituloRpt);
		$objPHPExcel->getProperties()->setSubject($sTituloRpt);
		$objPHPExcel->getProperties()->setDescription('Reporte de http://www.unad.edu.co');
		$objHoja=$objPHPExcel->getActiveSheet();
		$objHoja->setTitle($sTituloRpt);
		$iFila=7;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$sSQL='SELECT * FROM even02evento WHERE even02consec='.$DATA['even02consec'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($bDebug){$objHoja->setCellValueByColumnAndRow(1, 2, $sSQL);}
		while ($fila=$objDB->sf($tabla)){
			$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['even02consec']);
			$objHoja->setCellValueByColumnAndRow(1, $iFila, $fila['even02id']);
			$objHoja->setCellValueByColumnAndRow(2, $iFila, $fila['even02tipo']);
			$objHoja->setCellValueByColumnAndRow(3, $iFila, $fila['even02categoria']);
			$objHoja->setCellValueByColumnAndRow(4, $iFila, $fila['even02estado']);
			$objHoja->setCellValueByColumnAndRow(5, $iFila, $fila['even02publicado']);
			$objHoja->setCellValueByColumnAndRow(6, $iFila, $fila['even02nombre']);
			$objHoja->setCellValueByColumnAndRow(7, $iFila, $fila['even02idzona']);
			$objHoja->setCellValueByColumnAndRow(8, $iFila, $fila['even02idcead']);
			$objHoja->setCellValueByColumnAndRow(9, $iFila, $fila['even02peraca']);
			$objHoja->setCellValueByColumnAndRow(10, $iFila, $fila['even02lugar']);
			$objHoja->setCellValueByColumnAndRow(11, $iFila, $fila['even02inifecha']);
			$objHoja->setCellValueByColumnAndRow(12, $iFila, $fila['even02inihora']);
			$objHoja->setCellValueByColumnAndRow(13, $iFila, $fila['even02iniminuto']);
			$objHoja->setCellValueByColumnAndRow(14, $iFila, $fila['even02finfecha']);
			$objHoja->setCellValueByColumnAndRow(15, $iFila, $fila['even02finhora']);
			$objHoja->setCellValueByColumnAndRow(16, $iFila, $fila['even02finminuto']);
			$objHoja->setCellValueByColumnAndRow(17, $iFila, $fila['even02idorganizador']);
			$objHoja->setCellValueByColumnAndRow(18, $iFila, $fila['even02contacto']);
			$objHoja->setCellValueByColumnAndRow(19, $iFila, $fila['even02insfechaini']);
			$objHoja->setCellValueByColumnAndRow(20, $iFila, $fila['even02insfechafin']);
			$objHoja->setCellValueByColumnAndRow(21, $iFila, $fila['even02idcertificado']);
			$objHoja->setCellValueByColumnAndRow(22, $iFila, $fila['even02idrubrica']);
			$objHoja->setCellValueByColumnAndRow(23, $iFila, $fila['even02detalle']);
			$iFila++;
			}
		$objDB->CerrarConexion();
		if ($_REQUEST['clave']!=''){
			/* Bloquear la hoja. */
			$objHoja->getProtection()->setPassword($_REQUEST['clave']);
			$objHoja->getProtection()->setSheet(true);
			$objHoja->getProtection()->setSort(true);
			}
		/* descargar el resultado */
		header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
		header('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT'); /* ultima actualizacion ahora cuando la cargamos */
		header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$sTituloRpt.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
		die();
		}else{
		echo $sError;
		}
	}
?>