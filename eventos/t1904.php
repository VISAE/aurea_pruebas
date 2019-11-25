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
$idEvento=0;
$bEntra=false;
if (isset($_REQUEST['idevento'])!=0){$idEvento=numeros_validar($_REQUEST['idevento']);}

if ($idEvento!=0){
	$cSepara=',';
	$cEvita=';';
	$cComplementa='.';
	if (isset($_REQUEST['separa1904'])!=0){
		if ($_REQUEST['separa1904']==';'){
			$cSepara=';';
			$cEvita=',';
			}
		}
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$sNombreEvento='Evento: {'.$idEvento.'}';
	$sSQL='SELECT even02nombre FROM even02evento WHERE even02id='.$idEvento;
	$tabla=$objdb->ejecutasql($sSQL);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$sNombreEvento='Evento: '.utf8_decode($fila['even02nombre']);
		}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t1904.csv';
	$sTituloRpt='plantilla_participantes_'.$idEvento;
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Eventos - Participantes lista');
	$objplano->AdicionarLinea($sDato);
	$objplano->AdicionarLinea($sNombreEvento);
	$sDato='Documento'.$cSepara.'Participante'.$cSepara.'Institucion'.$cSepara.'Cargo'.$cSepara.'Correo'.$cSepara.'Telefono'.$cSepara.'Estado asistencia';
	$objplano->AdicionarLinea($sDato);
	$sSQL='SELECT TB.even04idevento, T2.unad11razonsocial AS C2_nombre, TB.even04id, TB.even04institucion, TB.even04cargo, TB.even04correo, TB.even04telefono,
	 T8.even13nombre, TB.even04idparticipante, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.even04estadoasistencia 
FROM even04eventoparticipante AS TB, unad11terceros AS T2, even13estadoasistencia AS T8 
WHERE  TB.even04idevento='.$idEvento.' AND TB.even04idparticipante=T2.unad11id AND TB.even04estadoasistencia=T8.even13id 
ORDER BY TB.even04idparticipante';
	$tabla=$objdb->ejecutasql($sSQL);
	while($fila=$objdb->sf($tabla)){
		//$sTituloCurso=str_replace($cSepara, $cComplementa, utf8_decode($fila['unad40nombre']));
		$sDoc='';
		$sRazonSocial='';
		if ($fila['even04idparticipante']!=0){
			$sDoc=$fila['C2_td'].' '.$fila['C2_doc'];
			$sRazonSocial=str_replace($cSepara, $cComplementa, utf8_decode($fila['C2_nombre']));
			}
		$sDato=$sDoc.$cSepara.$sRazonSocial.$cSepara.$fila['even04institucion'].$cSepara.$fila['even04cargo'].$cSepara.$fila['even04correo'].$cSepara.$fila['even04telefono'].$cSepara.$fila['even13nombre'];
		$sDato=utf8_decode($sDato);
		$objplano->AdicionarLinea($sDato);
		}
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}else{
	echo 'No ha definido el evento a generar';
	}
?>