<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Saul Alexander Hernandez Albarracin - UNAD - 2019 ---
--- saul.hernandez@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.1 viernes, 24 de mayo de 2019
*/
/*
/** Archivo para reportes tipo csv 111.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 24 de mayo de 2019
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require '../config.php';
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
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']='';}
if (isset($_REQUEST['v7'])==0){$_REQUEST['v7']='';}
if (isset($_REQUEST['v8'])==0){$_REQUEST['v8']='';}
//if (isset($_REQUEST['v9'])==0){$_REQUEST['v9']='';}
if (isset($_REQUEST['v10'])==0){$_REQUEST['v10']='';}
if (isset($_REQUEST['v11'])==0){$_REQUEST['v11']='';}
if (isset($_REQUEST['v12'])==0){$_REQUEST['v12']='';}
if (isset($_REQUEST['v13'])==0){$_REQUEST['v13']='';}
if (isset($_REQUEST['v14'])==0){$_REQUEST['v14']='';}
if (isset($_REQUEST['v15'])==0){$_REQUEST['v15']='';}
if (isset($_REQUEST['v16'])==0){$_REQUEST['v16']='';}


if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==111){$bEntra=true;}
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
	$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_111;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t111.csv';
	$sTituloRpt='Terceros';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=('Terceros');
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$aSaltos=array("\r","\n", "\r\n");
	$aunad11rolunad=array('Estudiante','Contratista','Personal de planta','Egresado');
	$aunad11idzona=array();
	$aunad11idcead=array();
	$aunad11idescuela=array();
	$aunad11idprograma=array();
    $acore51idconvenio=array();
	$acore16idperaca=array();
	$sTitulo1='Titulo 1';
	for ($l=1;$l<=20;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}

	$sBloque1=''.'Tipo doc'.$cSepara.'Documento'.$cSepara.'Usuario'.$cSepara.'Razon social'.$cSepara.'Pais'.$cSepara.'Departamento origen'.$cSepara.'Ciudad origen'.$cSepara.'Direccion'.$cSepara.'Telefono';
	$sTitulo2='Titulo 2';
	for ($l=1;$l<=9;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.'Rol'.$cSepara.'Correo'.$cSepara.'Correo institucional'.$cSepara.'Correo notifica'.$cSepara.'Correo notifica nuevo'.$cSepara.'Correo funcionario';
	$sTitulo3='Titulo 3';
	for ($l=1;$l<=6;$l++){
		$sTitulo3=$sTitulo3.$cSepara;
		}
	$sBloque3=''.$cSepara.'Zona'.$cSepara.'Cead'.$cSepara.'Escuela';
	$sTitulo4='Titulo 4';
	for ($l=1;$l<=3;$l++){
		$sTitulo4=$sTitulo4.$cSepara;
		}
	$sBloque4=''.$cSepara.'Programa'.$cSepara.'Fecha ultimo ingreso'.$cSepara.'Convenio';
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2.$sTitulo3.$sTitulo4);
	$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4);
	
	// empieza sql
	$sSQLadd='';
	$sSQLadd1='';
	$limite='';
	
	/*if ($_REQUEST['v9']!=''){
		$sSubConsulta='SELECT T16.core16tercero FROM core16actamatricula AS T16 WHERE T16.core16peraca='.$_REQUEST['v9'].'';
		$sSQLadd=$sSQLadd.' TB.unad11id IN ('.$sSubConsulta.')';
		}
	*/	
	if ($_REQUEST['v3']!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.' TB.unad11doc LIKE "%'.$_REQUEST['v3'].'%"';
		}
	if ($_REQUEST['v4']!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11razonsocial LIKE "%'.$_REQUEST['v4'].'%"';
		}
	if ($_REQUEST['v5']!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11usuario LIKE "%'.$_REQUEST['v5'].'%"';
		}
	//parametro de correo electronico.
	if ($_REQUEST['v6']!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		switch($_REQUEST['v7']){
			case 1: //Correo personal
			$sSQLadd=$sSQLadd.'TB.unad11correo LIKE "%'.$_REQUEST['v6'].'%"';
			break;
			case 2: //Correo notificaciones
			$sSQLadd=$sSQLadd.'TB.unad11correonotifica LIKE "%'.$_REQUEST['v6'].'%"';
			break;
			case 3: //Correo institucional
			$sSQLadd=$sSQLadd.'TB.unad11correoinstitucional LIKE "%'.$_REQUEST['v6'].'%"';
			break;
			case 4: //Correo funcionario
			$sSQLadd=$sSQLadd.'TB.unad11correofuncionario LIKE "%'.$_REQUEST['v6'].'%"';
			break;
			default:
			//Todos los correos...
			$sSQLadd=$sSQLadd.'((TB.unad11correo LIKE "%'.$_REQUEST['v6'].'%") OR (TB.unad11correonotifica LIKE "%'.$_REQUEST['v6'].'%") OR (TB.unad11correoinstitucional LIKE "%'.$_REQUEST['v6'].'%") OR (TB.unad11correofuncionario LIKE "%'.$_REQUEST['v6'].'%"))';
			break;
			}
		}
	switch($_REQUEST['v8']){
		case 1:
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11fechaconfmail<>0';
		break;
		case 2:
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11fechaconfmail=0';
		break;
		}
	//Convenio.
	
	if ($_REQUEST['v10']!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		//$sSQLadd=$sSQLadd.'TB.unad11id=T51.core51idtercero AND T51.core51idconvenio='.$_REQUEST['v10'].' AND T51.core51activo="S"';
		 $sSubConsulta='SELECT T51.core51idtercero FROM core51convenioest AS T51 WHERE T51.core51idconvenio='.$_REQUEST['v10'].'  AND T51.core51activo="S" ';
		 $sSQLadd=$sSQLadd.' TB.unad11id IN ('.$sSubConsulta.')';
		}
	//Fecha Desde Hasta
	if ($_REQUEST['v11']!='0'){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.'TB.unad11fechaultingreso >= '.$_REQUEST['v11'].' ';
		}	
	if ($_REQUEST['v12']!='0'){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.'TB.unad11fechaultingreso <= '.$_REQUEST['v12'].' ';
		}	
	
	
	//Escuela
				
	if ($_REQUEST['v16']!=''){ //112 programa
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
	    $sSQLadd=$sSQLadd.' TB.unad11idprograma='.$_REQUEST['v16'].'  ';
		}else{
		if ($_REQUEST['v15']!=''){ //111 escuela
			if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.'  TB.unad11idescuela='.$_REQUEST['v15'].'  ';}
		}
	
	// cead
	if ($_REQUEST['v14']!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.' TB.unad11idcead='.$_REQUEST['v14'].'  ';
		}else{
		if ($_REQUEST['v13']!=''){
			if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.' TB.unad11idzona='.$_REQUEST['v13'].'  ';}
		}
		
		//, TB.unad11dv, TB.unad11nombre1, TB.unad11nombre2, TB.unad11apellido1, TB.unad11apellido2, TB.unad11genero
	if ($sSQLadd!=''){
		$sSQLadd=' WHERE '.$sSQLadd.'';
		}else{
		//Es un super reporte, precargar datos.
		$sSQL='SELECT core09id, core09nombre FROM core09programa';
		$tablae=$objDB->ejecutasql($sSQL);
		while($filae=$objDB->sf($tablae)){
			$aunad11idprograma[$filae['core09id']]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
			}
		}
	
// termina filtro parametros

$sSQL='SELECT TB.unad11tipodoc,TB.unad11doc, TB.unad11usuario, TB.unad11razonsocial,TB.unad11pais,
TB.unad11deptoorigen, TB.unad11ciudadorigen,TB.unad11direccion, TB.unad11telefono, TB.unad11correo,
TB.unad11correoinstitucional, TB.unad11correonotifica,TB.unad11correonotificanuevo, TB.unad11correofuncionario,TB.unad11bloqueado,
TB.unad11idzona,TB.unad11idcead,TB.unad11idescuela,TB.unad11idprograma,TB.unad11fechaultingreso,
TB.unad11rolunad
FROM unad11terceros AS TB'.$sSQLadd.' 
ORDER BY TB.unad11razonsocial';
	//if ($bDebug){$objplano->adlinea($sSQL);}
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}	
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_unad11tipodoc=$cSepara;
		$lin_unad11doc=$cSepara;
		$lin_unad11usuario=$cSepara;
		$lin_unad11genero=$cSepara;
		$lin_unad11razonsocial=$cSepara;
		$lin_unad11direccion=$cSepara;
		$lin_unad11telefono=$cSepara;
		$lin_unad11correo=$cSepara;
		$lin_unad11rolunad=$cSepara;
		$lin_unad11fechatablero=$cSepara;
		$lin_unad11bloqueado=$cSepara;
		$lin_unad11correonotifica=$cSepara;
		$lin_unad11correoinstitucional=$cSepara;
		$lin_unad11correonotificanuevo=$cSepara;
		$lin_unad11idzona=$cSepara;
		$lin_unad11idcead=$cSepara;
		$lin_unad11idescuela=$cSepara;
		$lin_unad11idprograma=$cSepara;
		$lin_unad11presentacion=$cSepara;
		$lin_unad11fechaultingreso=$cSepara;
		$lin_unad11correofuncionario=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11correofuncionario']));
		$lin_unad11necesidadesp=$cSepara;
		$lin_unad11tipodoc=$fila['unad11tipodoc'];
		$lin_unad11doc=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11doc']));
		$lin_unad11usuario=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11usuario']));
		$lin_unad11razonsocial=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11razonsocial']));
		$lin_unad11pais=$cSepara.$fila['unad11pais'];
		$lin_unad11deptoorigen=$cSepara.$fila['unad11deptoorigen'];
		if ($fila['unad11rolunad']!=-1){
			$lin_unad11rolunad=$cSepara.$aunad11rolunad[$fila['unad11rolunad']];
			}
		$lin_unad11ciudadorigen=$cSepara.$fila['unad11ciudadorigen'];
		$lin_unad11direccion=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11direccion']));
		$lin_unad11telefono=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11telefono']));
		$lin_unad11correo=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11correo']));
		$lin_unad11correonotifica=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11correonotifica']));
		$lin_unad11correoinstitucional=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11correoinstitucional']));
		$lin_unad11correonotificanuevo=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['unad11correonotificanuevo']));
		$lin_core51convenio=$cSepara;
		$lin_core16peraca=$cSepara;
		
	    $i_unad11idzona=$fila['unad11idzona'];
		if (isset($aunad11idzona[$i_unad11idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_unad11idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunad11idzona[$i_unad11idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$aunad11idzona[$i_unad11idzona]='';
				}
			}
		$lin_unad11idzona=$cSepara.utf8_decode($aunad11idzona[$i_unad11idzona]);
		$i_unad11idcead=$fila['unad11idcead'];
		if (isset($aunad11idcead[$i_unad11idcead])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_unad11idcead.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunad11idcead[$i_unad11idcead]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$aunad11idcead[$i_unad11idcead]='';
				}
			}
		$lin_unad11idcead=$cSepara.utf8_decode($aunad11idcead[$i_unad11idcead]);
    	$i_unad11idescuela=$fila['unad11idescuela'];
		if (isset($aunad11idescuela[$i_unad11idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_unad11idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunad11idescuela[$i_unad11idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$aunad11idescuela[$i_unad11idescuela]='';
				}
			}
		$lin_unad11idescuela=$cSepara.utf8_decode($aunad11idescuela[$i_unad11idescuela]);
		$i_unad11idprograma=$fila['unad11idprograma'];
		if (isset($aunad11idprograma[$i_unad11idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_unad11idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunad11idprograma[$i_unad11idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$aunad11idprograma[$i_unad11idprograma]='';
				}
			}
		$lin_unad11idprograma=$cSepara.utf8_decode($aunad11idprograma[$i_unad11idprograma]);
		$i_unad11pais=$fila['unad11pais'];
		if (isset($aunad11pais[$i_unad11pais])==0){
			$sSQL='SELECT unad18nombre FROM unad18pais WHERE unad18codigo='.$i_unad11pais.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunad11pais[$i_unad11pais]=str_replace($cSepara, $cComplementa, $filae['unad18nombre']);
				}else{
				$aunad11pais[$i_unad11pais]='';
				}
			}
		$lin_unad11pais=$cSepara.utf8_decode($aunad11pais[$i_unad11pais]);
		$i_unad11deptoorigen=$fila['unad11deptoorigen'];
		if (isset($aunad11deptoorigen[$i_unad11deptoorigen])==0){
			$sSQL='SELECT unad19nombre FROM unad19depto WHERE unad19codigo='.$i_unad11deptoorigen.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunad11deptoorigen[$i_unad11deptoorigen]=str_replace($cSepara, $cComplementa, $filae['unad19nombre']);
				}else{
				$aunad11deptoorigen[$i_unad11deptoorigen]='';
				}
			}
		$lin_unad11deptoorigen=$cSepara.utf8_decode($aunad11deptoorigen[$i_unad11deptoorigen]);

		$i_unad11ciudadorigen=$fila['unad11ciudadorigen'];
		if (isset($aunad11ciudadorigen[$i_unad11ciudadorigen])==0){
			$sSQL='SELECT unad20nombre FROM unad20ciudad WHERE unad20codigo='.$i_unad11ciudadorigen.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aunad11ciudadorigen[$i_unad11ciudadorigen]=str_replace($cSepara, $cComplementa, $filae['unad20nombre']);
				}else{
				$aunad11ciudadorigen[$i_unad11ciudadorigen]='';
				}
			}
		$lin_unad11ciudadorigen=$cSepara.utf8_decode($aunad11ciudadorigen[$i_unad11ciudadorigen]);
	if ($_REQUEST['v10']!='') {
		$i_core51idconvenio=$_REQUEST['v10'];
		if (isset($acore51idconvenio[$i_core51idconvenio])==0){
			$sSQL='SELECT core50nombre FROM core50convenios WHERE core50id='.$i_core51idconvenio.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acore51idconvenio[$i_core51idconvenio]=str_replace($cSepara, $cComplementa, $filae['core50nombre']);
				}else{
				$acore51idconvenio[$i_core51idconvenio]='';
				}
			}
		$lin_core51convenio=$cSepara.utf8_decode($acore51idconvenio[$i_core51idconvenio]);
		}
		/*if ($_REQUEST['v9']!=''){
		$i_core16idperaca=$_REQUEST['v9'];//**
		if (isset($acore16idperaca[$i_core16idperaca])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_core16idperaca.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acore16idperaca[$i_core16idperaca]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$acore16idperaca[$i_core16idperaca]='';
				}
		}
		$lin_core16peraca=$cSepara.utf8_decode($acore16idperaca[$i_core16idperaca]);
		}
		*/
		$lin_unad11fechaultingreso=$cSepara.$fila['unad11fechaultingreso'];
		$lin_unad11fechaultingreso=$cSepara.fecha_desdenumero($lin_unad11fechaultingreso);
		$sBloque1=''.$lin_unad11tipodoc.$lin_unad11doc.$lin_unad11usuario.$lin_unad11razonsocial.$lin_unad11pais.$lin_unad11deptoorigen.$lin_unad11ciudadorigen
		.$lin_unad11direccion.$lin_unad11telefono;
		$sBloque2=''.$lin_unad11rolunad.$lin_unad11correo.$lin_unad11correoinstitucional.$lin_unad11correonotifica.$lin_unad11correonotificanuevo
		.$lin_unad11correofuncionario;
		$sBloque3=''.$lin_unad11idzona.$lin_unad11idcead.$lin_unad11idescuela;
		$sBloque4=''.$lin_unad11idprograma.$lin_unad11fechaultingreso.$lin_core51convenio;
		//Quitar saltos de linea
		$sBloque1=str_replace($aSaltos, '', $sBloque1);
		$sBloque2=str_replace($aSaltos, '', $sBloque2);
		$sBloque3=str_replace($aSaltos, '', $sBloque3);
		$sBloque4=str_replace($aSaltos, '', $sBloque4);
		//Armar la linea.
		$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4);
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