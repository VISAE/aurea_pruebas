<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Saúl Alexánder Hernández 16-08-2019
--- Omar Bautista 16-08-2019
--- Modelo Version 2.23.3 sábado, 20 de julio de 2019
*/
/*
/** Archivo para reportes tipo csv 2408.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date sábado, 20 de julio de 2019
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
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
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']='';}//ceca08idtutor
if (isset($_REQUEST['v7'])==0){$_REQUEST['v7']='';}//ceca08idzona
if (isset($_REQUEST['v8'])==0){$_REQUEST['v8']='';}//ceca08idcentro
if (isset($_REQUEST['v9'])==0){$_REQUEST['v9']='';}//ceca08idescuela
if (isset($_REQUEST['v10'])==0){$_REQUEST['v10']='';}//ceca08idprograma
if (isset($_REQUEST['v11'])==0){$_REQUEST['v11']='';}//incluirceca08sexo
if (isset($_REQUEST['v12'])==0){$_REQUEST['v12']='';}//unae18id
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==2408){$bEntra=true;}
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
	$mensajes_2408='lg/lg_2408_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2408)){$mensajes_2408='lg/lg_2408_es.php';}
	require $mensajes_todas;
	require $mensajes_2408;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2408.csv';
	$sTituloRpt='Estadistica de calificaciones';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Estadistica de calificaciones');
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	$sEncabezado='';
	/* Alistar los arreglos para las tablas hijas */
	$aceca08idperaca=array();
	$aceca08idcurso=array();
	$aceca08idzona=array();
	$aceca08idcentro=array();
	$aceca08idescuela=array();
	$aceca08idprograma=array();
	$sSalto="\r";
	$aSys11=array();
	$sTitulo1='Titulo 1';
	$sTipoEstadis='';
	$sTitulosTutor='';
	$sTituloSexo='';
	$sTituloRangoEdad='';
	$sEncabezado='';
	if ($_REQUEST['v3']!=''){
		$iTipo=$_REQUEST['v3'];
    }
	if ($iTipo==1){
		$sTipoEstadis='';
		$sEncabezado=$sEncabezado.'Totalizado por Cursos'.$sSalto;
		}
	if ($iTipo==2){
		$sTipoEstadis='';
		$sTitulosTutor=''.'Tutor TD'.$cSepara.'Tutor Documento'.$cSepara.'Tutor Nombre'.$cSepara;
		$sEncabezado=$sEncabezado.'Por curso Tutor'.$sSalto; 
		}
	if ($iTipo==3){
		$sTipoEstadis='Zonas';
		$sEncabezado=$sEncabezado.'Por curso Zonas'.$sSalto;
		}
	if ($iTipo==4){
		$sTipoEstadis='Centros';
		$sEncabezado=$sEncabezado.'Por curso Centros'.$sSalto;
		}
	if ($iTipo==5){
		$sTipoEstadis='Escuelas';
		$sEncabezado=$sEncabezado.'Por curso Escuelas'.$sSalto;
		}
	if ($iTipo==6){
		$sTipoEstadis='Programas';
		$sEncabezado=$sEncabezado.'Por curso Programas'.$sSalto;
		}
	for ($l=1;$l<=20;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sSQLadd='';
	$sGroupBy='';
	$sGroupByTotal='';
	$sSelect='';
	$sTablaEdad='';
	$sSelectTotal='';
	$sOrderByUnion='';
	$sSQL='';
	if ($_REQUEST['v4']!=''){
		$idPeraca=$_REQUEST['v4'];
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id= '.$idPeraca;
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sEncabezado=$sEncabezado.' Peraca '.$fila['exte02nombre'].$sSalto;
				}
		
		}
	if ($_REQUEST['v5']!=''){
		$sSQLadd=$sSQLadd.' AND TB.ceca08idcurso='.$_REQUEST['v5'];
		$sSQL='SELECT  unad40nombre  FROM unad40curso WHERE unad40id='.$_REQUEST['v5'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sEncabezado=$sEncabezado.' Curso '.$_REQUEST['v5'].' '.$fila['unad40nombre'].$sSalto;	
					}	
		}
	if ($_REQUEST['v6']!=''){
		$sSQLadd=$sSQLadd.' AND TB.ceca08idtutor='.$_REQUEST['v6'];
				$sSQL='SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id='.$_REQUEST['v6'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sEncabezado=$sEncabezado.' Tutor '.$fila['unad11tipodoc'].$fila['unad11doc'].' '.($fila['unad11razonsocial']).$sSalto;	
					}				
		}
	if ($_REQUEST['v8']!=''){
		$sSQLadd=$sSQLadd.' AND TB.ceca08idcentro='.$_REQUEST['v8'];
		$sSQL='SELECT unad24nombre  FROM unad24sede WHERE unad24id='.$_REQUEST['v8'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sEncabezado=$sEncabezado.' Centro '.$fila['unad24nombre'].$sSalto;	
					}	
		}else{
		if ($_REQUEST['v7']!=''){
			$sSQLadd=$sSQLadd.' AND TB.ceca08idzona='.$_REQUEST['v7'];
			$sSQL='SELECT  unad23nombre FROM unad23zona WHERE unad23id='.$_REQUEST['v7'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sEncabezado=$sEncabezado.' Zona '.$fila['unad23nombre'].$sSalto;	
					}	
			}
		}
	if ($_REQUEST['v10']!=''){
		$sSQLadd=$sSQLadd.' AND TB.ceca08idprograma='.$_REQUEST['v10'];
		$sSQL='SELECT  core09nombre FROM core09programa WHERE core09id='.$_REQUEST['v10'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sEncabezado=$sEncabezado.' Programa '.$fila['core09nombre'].$sSalto;	
					}	
		}else{
		if ($_REQUEST['v9']!=''){
			$sSQLadd=$sSQLadd.' AND TB.ceca08idescuela='.$_REQUEST['v9'];
			$sSQL='SELECT  core12nombre  FROM core12escuela WHERE core12id='.$_REQUEST['v9'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sEncabezado=$sEncabezado.' Escuela '.$fila['core12nombre'].$sSalto;	
					}	
		}
	}
	if ($_REQUEST['v11']=='S'){
		$sTituloSexo=''.'Sexo'.$cSepara; 
		$sGroupBy=$sGroupBy.' ,TB.ceca08sexo ';
		$sSelect=$sSelect.' ,TB.ceca08sexo ';
		$sSelectTotal =', ceca08sexo';
		$sGroupByTotal=$sGroupByTotal.'  GROUP BY TB.ceca08sexo ';
		}
	
	if ($_REQUEST['v12']!=''){
	$sTituloRangoEdad=''.'Rango de Edad'.$cSepara;
	$sTablaEdad=', unae20rangosdist AS T20  ';
		$sSQLadd=$sSQLadd.' AND TB.ceca08edad=T20.unae20edad  AND unae20idrangoedad='.$_REQUEST['v12'];
		$sGroupBy=$sGroupBy.' ,T20.unae20idrango ';
		$sOrderByUnion=' ,unae20idrango';
		$sSelectTotal=$sSelectTotal.', "" AS unae20idrango';
		$sSelect=$sSelect.',T20.unae20idrango ';
	}
    // se pone en este lugar para poder armar los titulos dinamicamente
	$sBloque1=''.'Id Curso'.$cSepara.'Curso'.$cSepara.$sTitulosTutor.'Estudiantes'.$cSepara.'Reprobados'.$cSepara.'Inasistentes'.$cSepara.$sTituloSexo.$sTituloRangoEdad.'% de perdida'.$cSepara
.'Promedio 75%'.$cSepara.'Promedio 25%'.$cSepara.'Promedio total';
	$sTitulo2='Titulo 2';
	for ($l=1;$l<=3;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	//$sBloque2=''.$cSepara.'Promediototal'.$cSepara.'Puntaje75'.$cSepara.'Puntaje25';
	$sBloque2=''.$cSepara.$sTipoEstadis;
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2);
	$objplano->AdicionarLinea($sEncabezado);
	$objplano->AdicionarLinea($sBloque1.$sBloque2);

	
	
	
	$sConsultaUnion='';
	$iTipoBusqueda='ceca08idtutor';

	if ($iTipo==1){
	$iTipoBusqueda='ceca08idtutor';

$sConsultaUnion=' UNION    
SELECT "" AS ceca08idcurso, "" AS unad40titulo, "Todos los cursos" AS unad40nombre, "" AS ceca08idtutor, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , 
(SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen'.$sSelectTotal.'     
FROM ceca08estadisticacurso AS TB'.$sTablaEdad.'
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.$sGroupByTotal;
		}		
	
	if ($iTipo==2){
	$iTipoBusqueda='ceca08idtutor';
		$sConsultaUnion=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idtutor, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen'.$sSelect.'  
FROM ceca08estadisticacurso AS TB'.$sTablaEdad.'
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idtutor'.$sGroupBy;
		}
	if ($iTipo==3){
	$iTipoBusqueda='ceca08idzona';
		$sConsultaUnion=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idzona, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen'.$sSelect.'  
FROM ceca08estadisticacurso AS TB'.$sTablaEdad.'
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idzona'.$sGroupBy;;
		}		
	if ($iTipo==4){
	$iTipoBusqueda='ceca08idcentro';
		$sConsultaUnion=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idcentro, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen'.$sSelect.'  
FROM ceca08estadisticacurso AS TB'.$sTablaEdad.'
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idcentro'.$sGroupBy;;
		}		
	if ($iTipo==5){
	$iTipoBusqueda='ceca08idescuela';
		$sConsultaUnion=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idescuela, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen'.$sSelect.'  
FROM ceca08estadisticacurso AS TB'.$sTablaEdad.'
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idescuela'.$sGroupBy;;
		}		
	if ($iTipo==6){
	$iTipoBusqueda='ceca08idprograma';
		$sConsultaUnion=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idprograma, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen'.$sSelect.'  
FROM ceca08estadisticacurso AS TB'.$sTablaEdad.'
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idprograma'.$sGroupBy;;
		}		
	$sSQL='SELECT TB.ceca08idcurso, T40.unad40titulo, T40.unad40nombre, -1 AS '.$iTipoBusqueda.', SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen'.$sSelect.'   
FROM ceca08estadisticacurso AS TB, unad40curso AS T40'.$sTablaEdad.'
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 AND TB.ceca08idcurso=T40.unad40id ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, T40.unad40titulo, T40.unad40nombre'.$sGroupBy.$sConsultaUnion.'
ORDER BY ceca08idcurso ,'.$iTipoBusqueda.$sOrderByUnion;
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}	
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_ceca08idperaca=$cSepara;
		$lin_ceca08idcurso=$cSepara;
		$lin_ceca08idtutor=$cSepara.$cSepara.$cSepara;
		$lin_ceca08idzona='';
		$lin_ceca08idcentro='';
		$lin_ceca08idescuela='';
		$lin_ceca08idprograma='';
		$lin_ceca08sexo=$cSepara;
		$lin_ceca08edad=$cSepara;
		$lin_ceca08tiporegistro=$cSepara;
		$lin_ceca08fechareporta75=$cSepara;
		$lin_ceca08fechareporta25=$cSepara;
		$lin_ceca08numestudiantes=$cSepara;
		$lin_ceca08numresagados=$cSepara;
		$lin_ceca08numreprobados=$cSepara;
		$lin_ceca08numinasistentes=$cSepara;
		$lin_ceca08promedio75=$cSepara;
		$lin_ceca08promedio25=$cSepara;
		$lin_ceca08promediototal=$cSepara;
		$lin_ceca08puntaje75=$cSepara;
		$lin_ceca08puntaje25=$cSepara;
		$lin_NumEst=$cSepara;
		$lin_NumReprob=$cSepara;
		$lin_NumNoAsistentes=$cSepara;
		$lin_Prom75=$cSepara;
		$lin_Prom25=$cSepara;
		$lin_PromGen=$cSepara;
		$lin_unae19titulo=$cSepara;
		$i_ceca08idcurso=$fila['ceca08idcurso'];
		if (isset($aceca08idcurso[$i_ceca08idcurso])==0){
			$sSQL='SELECT unad40nombre FROM unad40curso WHERE unad40id='.$i_ceca08idcurso.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aceca08idcurso[$i_ceca08idcurso]=str_replace($cSepara, $cComplementa, $filae['unad40nombre']);
				}else{
				$aceca08idcurso[$i_ceca08idcurso]='';
				}
			}
		$lin_ceca08idcurso=$i_ceca08idcurso;
		$lin_ceca08Nombrecurso='';
		if($i_ceca08idcurso==''){
		$lin_ceca08Nombrecurso=$cSepara.'Todos los cursos';
		}else{
		$lin_ceca08Nombrecurso=$cSepara.utf8_decode($aceca08idcurso[$i_ceca08idcurso]);
		}
		if ($iTipo==2){
		$iTer=$fila['ceca08idtutor'];
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
		$lin_ceca08idtutor=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		}
		if ($iTipo==3){
		$i_ceca08idzona=$fila['ceca08idzona'];
		if (isset($aceca08idzona[$i_ceca08idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_ceca08idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aceca08idzona[$i_ceca08idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$aceca08idzona[$i_ceca08idzona]='';
				}
			}
		if($i_ceca08idzona<0){
			$lin_ceca08idzona=$cSepara.'Total Todas las Zonas';
		}else{
			$lin_ceca08idzona=$cSepara.utf8_decode($aceca08idzona[$i_ceca08idzona]);
		}
		}
		if ($iTipo==4){
		$i_ceca08idcentro=$fila['ceca08idcentro'];
		if (isset($aceca08idcentro[$i_ceca08idcentro])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_ceca08idcentro.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aceca08idcentro[$i_ceca08idcentro]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$aceca08idcentro[$i_ceca08idcentro]='';
				}
			}
		if($i_ceca08idcentro<0){
			$lin_ceca08idcentro=$cSepara.'Total Todos los centros';
		}else{
			$lin_ceca08idcentro=$cSepara.utf8_decode($aceca08idcentro[$i_ceca08idcentro]);
		}
		}
		
		if ($iTipo==5){
		$i_ceca08idescuela=$fila['ceca08idescuela'];
		if (isset($aceca08idescuela[$i_ceca08idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_ceca08idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aceca08idescuela[$i_ceca08idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$aceca08idescuela[$i_ceca08idescuela]='';
				}
			}
			if($i_ceca08idescuela<0){
				$lin_ceca08idescuela=$cSepara.'Total Todas las escuelas';
				}else{
				$lin_ceca08idescuela=$cSepara.utf8_decode($aceca08idescuela[$i_ceca08idescuela]);
				}
		
		}
		if ($iTipo==6){
		$i_ceca08idprograma=$fila['ceca08idprograma'];
		if (isset($aceca08idprograma[$i_ceca08idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_ceca08idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aceca08idprograma[$i_ceca08idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$aceca08idprograma[$i_ceca08idprograma]='';
				}
			}
			if($i_ceca08idprograma<0){
				$lin_ceca08idprograma=$cSepara.'Total Todos los Programas';
				}else{
				$lin_ceca08idprograma=$cSepara.utf8_decode($aceca08idprograma[$i_ceca08idprograma]);
				}
		
		}
		$lin_NumEst=$cSepara.$fila['NumEst'];
		$lin_NumReprob=$cSepara.$fila['NumReprob'];
		$lin_NumNoAsistentes=$cSepara.$fila['NumNoAsistentes'];
		$lin_Prom75=$cSepara.formato_numero($fila['Prom75']/100, 2);
		$lin_Prom25=$cSepara.formato_numero($fila['Prom25']/100, 2);
		$lin_PromGen=$cSepara.formato_numero($fila['PromGen']/100, 2);
		$sPorPerdida=$cSepara.'';
		if ($fila['NumReprob']>0){
			$sReprobados=$fila['NumReprob'];
			$iAprobados=($fila['NumEst']-$fila['NumNoAsistentes']);
			if ($iAprobados>0){
				$sPorPerdida=$cSepara.formato_numero((($fila['NumReprob']-$fila['NumNoAsistentes'])/$iAprobados*100), 2).' %';
				}else{
				$sPorPerdida=$cSepara.'100 %';
				}
			}
		$lin_unae19titulo='';
		if ($_REQUEST['v12']!=''){	
			// Buscando el titulo de rango de edad
			$i_unae20idrango=$fila['unae20idrango'];
		if (isset($aunae19titulo[$i_unae20idrango])==0){
			$sSQL='SELECT unae19titulo FROM unae19rango WHERE unae19id='.$i_unae20idrango.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunae19titulo[$i_unae20idrango]=str_replace($cSepara, $cComplementa, $filae['unae19titulo']);
				}else{
				$aunae19titulo[$i_unae20idrango]='';
				}
			}
		$lin_unae19titulo=$cSepara.utf8_decode($aunae19titulo[$i_unae20idrango]);
		}	
		if ($iTipo!=2){$lin_ceca08idtutor='';}
		$lin_sexo='';
		if ($_REQUEST['v11']=='S'){	$lin_sexo=$cSepara.$fila['ceca08sexo'];}
		$sBloque1=''.$lin_ceca08idcurso.$lin_ceca08Nombrecurso.$lin_ceca08idtutor.$lin_NumEst.$lin_NumReprob.$lin_NumNoAsistentes.$lin_sexo.$lin_unae19titulo.$sPorPerdida.$lin_Prom75.$lin_Prom25.$lin_PromGen;
		$sBloque2=''.$lin_ceca08idzona.$lin_ceca08idcentro.$lin_ceca08idescuela.$lin_ceca08idprograma;
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