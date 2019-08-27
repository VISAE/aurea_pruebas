<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.5 Tuesday, August 27, 2019
*/
/*
/** Archivo para reportes tipo csv 1902.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Tuesday, August 27, 2019
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libs/clsplanos.php';
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
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']='';}
if (isset($_REQUEST['v5'])==0){$_REQUEST['v5']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==1902){$bEntra=true;}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$cSepara=',';
	$cEvita=';';
	$cComplementa='.';
	if (isset($_REQUEST['separa'])!=0){
		if ($_REQUEST['separa']==';'){
			$cSepara=';';
			$cEvita=',';
			}
		}
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t1902.csv';
	$sTituloRpt='even02evento';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('even02evento');
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$aeven02tipo=array();
	$aeven02categoria=array();
	$aeven02estado=array();
	$aeven02idzona=array();
	$aeven02idcead=array();
	$aeven02peraca=array();
	$aSys11=array();
	$sTitulo1='Titulo 1';
	for ($l=1;$l<=20;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sBloque1=''.'Consec'.$cSepara.'Tipo'.$cSepara.'Categoria'.$cSepara.'Estado'.$cSepara.'Publicado'.$cSepara
.'Nombre'.$cSepara.'Zona'.$cSepara.'Cead'.$cSepara.'Peraca'.$cSepara.'Lugar'.$cSepara
.'Inifecha'.$cSepara.'Inihora'.$cSepara.'Iniminuto'.$cSepara.'Finfecha'.$cSepara.'Finhora'.$cSepara
.'Finminuto'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Organizador'.$cSepara.'Contacto';
	$sTitulos2='Titulo 2';
	for ($l=1;$l<=5;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.'Insfechaini'.$cSepara.'Insfechafin'.$cSepara.'Certificado'.$cSepara.'Rubrica'.$cSepara.'Detalle';
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2);
	$objplano->AdicionarLinea($sBloque1.$sBloque2);
	$sSQL='SELECT * 
FROM even02evento 
WHERE even02consec='.$DATA['even02consec'].'
';
	if ($bDebug){$objplano->adlinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_even02consec=$cSepara;
		$lin_even02tipo=$cSepara;
		$lin_even02categoria=$cSepara;
		$lin_even02estado=$cSepara;
		$lin_even02publicado=$cSepara;
		$lin_even02nombre=$cSepara;
		$lin_even02idzona=$cSepara;
		$lin_even02idcead=$cSepara;
		$lin_even02peraca=$cSepara;
		$lin_even02lugar=$cSepara;
		$lin_even02inifecha=$cSepara;
		$lin_even02inihora=$cSepara;
		$lin_even02iniminuto=$cSepara;
		$lin_even02finfecha=$cSepara;
		$lin_even02finhora=$cSepara;
		$lin_even02finminuto=$cSepara;
		$lin_even02idorganizador=$cSepara.$cSepara.$cSepara;
		$lin_even02contacto=$cSepara;
		$lin_even02insfechaini=$cSepara;
		$lin_even02insfechafin=$cSepara;
		$lin_even02idcertificado=$cSepara;
		$lin_even02idrubrica=$cSepara;
		$lin_even02detalle=$cSepara;
		$lin_even02consec=$fila['even02consec'];
		$i_even02tipo=$fila['even02tipo'];
		if (isset($aeven02tipo[$i_even02tipo])==0){
			$sSQL='SELECT even01nombre FROM even01tipoevento WHERE even01id='.$i_even02tipo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aeven02tipo[$i_even02tipo]=str_replace($cSepara, $cComplementa, $filae['even01nombre']);
				}else{
				$aeven02tipo[$i_even02tipo]='';
				}
			}
		$lin_even02tipo=$cSepara.utf8_decode($aeven02tipo[$i_even02tipo]);
		$i_even02categoria=$fila['even02categoria'];
		if (isset($aeven02categoria[$i_even02categoria])==0){
			$sSQL='SELECT even41titulo FROM even41categoria WHERE even41id='.$i_even02categoria.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aeven02categoria[$i_even02categoria]=str_replace($cSepara, $cComplementa, $filae['even41titulo']);
				}else{
				$aeven02categoria[$i_even02categoria]='';
				}
			}
		$lin_even02categoria=$cSepara.utf8_decode($aeven02categoria[$i_even02categoria]);
		$i_even02estado=$fila['even02estado'];
		if (isset($aeven02estado[$i_even02estado])==0){
			$sSQL='SELECT even14nombre FROM even14estadoevento WHERE even14id='.$i_even02estado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aeven02estado[$i_even02estado]=str_replace($cSepara, $cComplementa, $filae['even14nombre']);
				}else{
				$aeven02estado[$i_even02estado]='';
				}
			}
		$lin_even02estado=$cSepara.utf8_decode($aeven02estado[$i_even02estado]);
		$lin_even02publicado=$cSepara.$fila['even02publicado'];
		$lin_even02nombre=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['even02nombre']));
		$i_even02idzona=$fila['even02idzona'];
		if (isset($aeven02idzona[$i_even02idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_even02idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aeven02idzona[$i_even02idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$aeven02idzona[$i_even02idzona]='';
				}
			}
		$lin_even02idzona=$cSepara.utf8_decode($aeven02idzona[$i_even02idzona]);
		$i_even02idcead=$fila['even02idcead'];
		if (isset($aeven02idcead[$i_even02idcead])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_even02idcead.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aeven02idcead[$i_even02idcead]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$aeven02idcead[$i_even02idcead]='';
				}
			}
		$lin_even02idcead=$cSepara.utf8_decode($aeven02idcead[$i_even02idcead]);
		$i_even02peraca=$fila['even02peraca'];
		if (isset($aeven02peraca[$i_even02peraca])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_even02peraca.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aeven02peraca[$i_even02peraca]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$aeven02peraca[$i_even02peraca]='';
				}
			}
		$lin_even02peraca=$cSepara.utf8_decode($aeven02peraca[$i_even02peraca]);
		$lin_even02lugar=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['even02lugar']));
		$lin_even02inifecha=$cSepara.$fila['even02inifecha'];
		$lin_even02inihora=$cSepara.$fila['even02inihora'];
		$lin_even02iniminuto=$cSepara.$fila['even02iniminuto'];
		$lin_even02finfecha=$cSepara.$fila['even02finfecha'];
		$lin_even02finhora=$cSepara.$fila['even02finhora'];
		$lin_even02finminuto=$cSepara.$fila['even02finminuto'];
		$iTer=$fila['even02idorganizador'];
		if (isset($aSys11[$iTer]['doc'])==0){
			$sSQL='SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id='.$iTer.'';
			$tabla11=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11)>0){
				$fila11=$objDB->sf($tabla11);
				$aSys11[$iTer]['td']=$fila11['unad11tipodoc'];
				$aSys11[$iTer]['doc']=$fila11['unad11doc'];
				$aSys11[$iTer]['razon']=$fila11['unad11razonsocial'];
				}else{
				$aSys11[$iTer]['td']='';
				$aSys11[$iTer]['doc']='['.$iTer.']';
				$aSys11[$iTer]['razon']='';
				}
			}
		$lin_even02idorganizador=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_even02contacto=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['even02contacto']));
		$lin_even02insfechaini=$cSepara.$fila['even02insfechaini'];
		$lin_even02insfechafin=$cSepara.$fila['even02insfechafin'];
		$lin_even02idcertificado=$cSepara.$fila['even02idcertificado'];
		$lin_even02idrubrica=$cSepara.$fila['even02idrubrica'];
		$lin_even02detalle=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['even02detalle']));
		$sBloque1=''.$lin_even02consec.$lin_even02tipo.$lin_even02categoria.$lin_even02estado.$lin_even02publicado
.$lin_even02nombre.$lin_even02idzona.$lin_even02idcead.$lin_even02peraca.$lin_even02lugar
.$lin_even02inifecha.$lin_even02inihora.$lin_even02iniminuto.$lin_even02finfecha.$lin_even02finhora
.$lin_even02finminuto.$lin_even02idorganizador.$lin_even02contacto;
		$sBloque2=''.$lin_even02insfechaini.$lin_even02insfechafin.$lin_even02idcertificado.$lin_even02idrubrica.$lin_even02detalle;
		$objplano->AdicionarLinea($sBloque1.$sBloque2);
		}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}
?>