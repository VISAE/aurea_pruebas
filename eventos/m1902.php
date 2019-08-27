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
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
$bDebug=false;
if (isset($_GET['debug'])!=0){
	if ($_GET['debug']==1){$bDebug=true;}
	}
if ($bDebug){
	$base=$_GET['data'];
	}else{
	$data=file_get_contents('php://input');
	$base=htmlspecialchars(trim($data));
	}
$datos=explode('||',$base);
$bResponde=false;
$sError='';
if (isset($datos[0])==0){$datos[0]='';}
if (isset($datos[1])==0){$datos[1]='';}
if (isset($datos[2])==0){$datos[2]='';}
if (isset($datos[3])==0){$datos[3]='';}
if (isset($datos[4])==0){$datos[4]='';}
if ($bDebug){echo 'Se ha recibido el proceso '.$datos[0].'<br>';}
switch($datos[0]){
	case '1902': /* even02evento */
	if (isset($datos[1])==0){$datos[1]='';}
	if (isset($datos[2])==0){$datos[2]='';}
	if (isset($datos[3])==0){$datos[3]='';}
	if (isset($datos[4])==0){$datos[4]='';}
	$idEntidad=numeros_validar($datos[1]);
	$sIdTercero=numeros_validar($datos[2]);
	$sIdMovil=numeros_validar($datos[3]);
	$sListaIds=htmlspecialchars(trim($datos[4]));
	if ($bDebug){echo 'Enviando even02evento a '.$idEntidad.' '.$sIdTercero.' '.$sIdMovil.' '.$sListaIds.'<br>';}
	/* Validamos que no esten intentando inyectar codigo en el usuario */
	if ($sListaIds!=$datos[4]){
		$sError='-99';
		}
	$sCondicion='';
	if (($sError=='')&&($sListaIds!=-99)){
		$sIds='';
		$aSubData=explode('|',$sListaIds);
		$iTotal=count($aSubData);
		for ($k=1;$k<=$iTotal;$k++){
			$sInfo=numeros_validar($aSubData[$k-1]);
			if ($sInfo!=''){
				if ($sIds!=''){$sIds=$sIds.',';}
				$sIds=$sIds.$sInfo;
				}
			}
		if ($sIds==''){
			$res=array();
			$res[]=-1;
			$res[]='';
			$sError='-1';
			if ($bDebug){echo 'No se ha enviado informaci&oacute;n a sincronizar <br>';}
			}else{
			$sCondicion=' WHERE even02id IN ('.$sIds.')';
			}
		}
	if ($sError==''){
		$data1=array();
		$bResponde=true;
		$iTotal=0;
		$sIdsPadre='-99';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$sSQL='SELECT even02consec, even02id, even02tipo, even02categoria, even02estado, even02publicado, even02nombre, even02idzona, even02idcead, even02peraca, even02lugar, even02inifecha, even02inihora, even02iniminuto, even02finfecha, even02finhora, even02finminuto, even02idorganizador, even02contacto, even02insfechaini, even02insfechafin, even02idcertificado, even02idrubrica, even02detalle FROM even02evento'.$sCondicion;
		if ($bDebug){echo 'Ejecutando: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$data1[]=$fila;
			$sIdsPadre=$sIdsPadre.','.$fila['even02id'];
			$iTotal++;
			}
		$iTotal1903=0;
		$sSQL='SELECT even03idevento, even03idcurso, even03id, even03vigente FROM even03eventocurso WHERE even03idevento IN ('.$sIdsPadre.')';
		if ($bDebug){echo 'Ejecutando: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$data1903[]=$fila;
			$iTotal1903++;
			}
		$iTotal1904=0;
		$sSQL='SELECT even04idevento, even04idparticipante, even04id, even04institucion, even04cargo, even04correo, even04telefono, even04estadoasistencia FROM even04eventoparticipante WHERE even04idevento IN ('.$sIdsPadre.')';
		if ($bDebug){echo 'Ejecutando: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$data1904[]=$fila;
			$iTotal1904++;
			}
		$iTotal1905=0;
		$sSQL='SELECT even05idevento, even05consec, even05id, even05fecha, even05publicar, even05idtercero, even05noticia FROM eve05eventonoticia WHERE even05idevento IN ('.$sIdsPadre.')';
		if ($bDebug){echo 'Ejecutando: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$data1905[]=$fila;
			$iTotal1905++;
			}
		$res=array();
		$res[]=$iTotal;
		$res[]=$data1;
		$res[]=$data1903;
		$res[]=$data1904;
		$res[]=$data1905;
		}
	if ($sError!=''){
		$res=array();
		$res[]=-2;
		$res[]=$sError;
		$bResponde=true;
		}
	break;
	default:
	if ($bDebug){
		echo 'No se ha encontrado la petici&oacute;n "'.$datos[0].'"';
		}else{
		header('Location:index.php');
		die();
		}
	}
if ($bResponde){
	print json_encode($res);
	}
?>