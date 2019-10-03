<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.12.5b sábado, 25 de septiembre de 2019
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
$bperiodot='';
$bzonat='';
$bceadt='';
$bdesdet='';
$bhastat='';
$sSQL='';
$sSQLadd='';
$sSQLadd1='';
$sDescripReporte='';
//$bEntra=false;
//if (isset($_REQUEST['idevento'])!=0){$idEvento=numeros_validar($_REQUEST['idevento']);}
if (isset($_REQUEST['bperiodot'])!=''){$bperiodot=numeros_validar($_REQUEST['bperiodot']);}
if (isset($_REQUEST['bzonat'])!=''){$bzonat=numeros_validar($_REQUEST['bzonat']);}
if (isset($_REQUEST['bceadt'])!=''){$bceadt=numeros_validar($_REQUEST['bceadt']);}
if (isset($_REQUEST['bdesdet'])!=0){$bdesdet=numeros_validar($_REQUEST['bdesdet']);}
if (isset($_REQUEST['bhastat'])!=0){$bhastat=numeros_validar($_REQUEST['bhastat']);}
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	//$sNombreEvento='Evento: {'.$idEvento.'}';
	if ($bperiodot!=''){
		$sSQLadd1=$sSQLadd1.'  AND TB.even02peraca='.$bperiodot.' ';
		$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$bperiodot;
		$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$sDescripReporte=$sDescripReporte.' Peraca: '.utf8_decode($filat['exte02nombre']);
				}
		}
		
	if ($bzonat!=''){
		$sSQLadd1=$sSQLadd1.'  AND TB.even02idzona='.$bzonat.'  ';
		$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$bzonat;
		$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$sDescripReporte=$sDescripReporte.' Zona: '.utf8_decode($filat['unad23nombre']);
				}
		}
		
	if ($bceadt!=''){
		$sSQLadd1=$sSQLadd1.'  AND TB.even02idcead='.$bceadt.'  ';
		$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$bceadt;
		$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$sDescripReporte=$sDescripReporte.' Cead: '.utf8_decode($filat['unad24nombre']);
				}
		}	

//Fecha Desde Hasta
	if ($bdesdet!=0){
			$sSQLadd1=$sSQLadd1.' AND  STR_TO_DATE(TB.even02inifecha,"%d/%m/%Y")  >= STR_TO_DATE("'.fecha_desdenumero($bdesdet).'","%d/%m/%Y")';
			$sDescripReporte=$sDescripReporte.' Desde: '.fecha_desdenumero($bdesdet);
		}	
	if ($bhastat!=0){
			$sSQLadd1=$sSQLadd1.' AND  STR_TO_DATE(TB.even02finfecha,"%d/%m/%Y") <= STR_TO_DATE("'.fecha_desdenumero($bhastat).'","%d/%m/%Y")';
			$sDescripReporte=$sDescripReporte.' Hasta: '.fecha_desdenumero($bhastat);
		}	
	

if (true){
	$cSepara=',';
	$cEvita=';';
	$cComplementa='.';
	if (isset($_REQUEST['separa1904t'])!=0){
		if ($_REQUEST['separa1904t']==';'){
			$cSepara=';';
			$cEvita=',';
			}
		}
	
	
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t1904_Total.csv';
	//$sTituloRpt='plantilla_participantes_'.$idEvento;
	$sTituloRpt='plantilla_participantes_total';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Lista de Participantes ');
	$objplano->AdicionarLinea($sDato);
	$objplano->AdicionarLinea($sDescripReporte);
	$sDato='TipoDoc'.$cSepara.'Docu'.$cSepara.'Razon Social'.$cSepara.'Peraca'.$cSepara.'Zona'.$cSepara.'Sede'.$cSepara.
	'Fecha Ini'.$cSepara.'Fecha Fin'.$cSepara.'Nombre Evento'.$cSepara.'Institucion'.$cSepara.'Cargo'.$cSepara.'Correo'.$cSepara.'Telefono'.$cSepara.'Estado Asistencia'.
	$cSepara.'Lugar'.$cSepara.'Url';
	$objplano->AdicionarLinea($sDato);
	//$objplano->AdicionarLinea('bperiodot='.$_REQUEST['bperiodot']);
	/*$sSQL='SELECT TB.even04idevento, T2.unad11razonsocial AS C2_nombre, TB.even04id, TB.even04institucion, TB.even04cargo, TB.even04correo, TB.even04telefono,
	 T8.even13nombre, TB.even04idparticipante, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.even04estadoasistencia 
FROM even04eventoparticipante AS TB, unad11terceros AS T2, even13estadoasistencia AS T8 
WHERE  TB.even04idevento='.$idEvento.' AND TB.even04idparticipante=T2.unad11id AND TB.even04estadoasistencia=T8.even13id 
ORDER BY TB.even04idparticipante';
*/	
	$sSQL='SELECT
   T5.unad11tipodoc AS TipoDoc
 , T5.unad11doc AS Docu    
 , T5.unad11razonsocial AS RazonSocial
 , T4.exte02nombre AS Peraca
 , T2.unad23nombre AS Zona
 , T3.unad24nombre AS Sede
 , TB.even02inifecha AS FechaIni
 , TB.even02finfecha AS FechaFin
 , TB.even02nombre AS NomEvento
 , T1.even04institucion AS Institucion
 , T1.even04cargo AS Cargo
 , T1.even04correo AS Correo
 , T1.even04telefono AS Telefono
 , T6.even13nombre AS EstadoAsistencia
 , TB.even02lugar AS Lugar
 , TB.even02url AS Url
   
FROM
    even02evento AS TB,even04eventoparticipante AS T1,unad23zona AS T2,unad24sede AS T3,exte02per_aca AS T4,unad11terceros AS T5,even13estadoasistencia AS T6
        WHERE (T1.even04idevento = TB.even02id)
        AND (TB.even02idzona = T2.unad23id)
        AND (TB.even02idcead = T3.unad24id)
        AND (TB.even02peraca = T4.exte02id)
        AND (T1.even04idparticipante = T5.unad11id)
        AND (T1.even04estadoasistencia = T6.even13id)'.$sSQLadd1.'
        
          ORDER BY FechaIni,Peraca,Zona,Sede,Institucion,NomEvento;';
	//$objplano->AdicionarLinea($sSQL);
		
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
			
		$sDato=$fila['TipoDoc'].$cSepara.$fila['Docu'].$cSepara.$fila['RazonSocial'].$cSepara.$fila['Peraca'].$cSepara.$fila['Zona'].$cSepara.$fila['Sede'].$cSepara.$fila['FechaIni'].$cSepara.$fila['FechaFin'].$cSepara.$fila['NomEvento'].$cSepara.$fila['Institucion'].$cSepara.$fila['Cargo'].$cSepara.$fila['Correo'].$cSepara.$fila['Telefono'].$cSepara.$fila['EstadoAsistencia'].$cSepara.$fila['Lugar'].$cSepara.$fila['Url'];
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