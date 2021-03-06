<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.3 sábado, 20 de julio de 2019
--- 2408 ceca08estadisticacurso
*/
/** Archivo lib2408.php.
* Libreria 2408 ceca08estadisticacurso.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date sábado, 20 de julio de 2019
*/
function f2408_HTMLComboV2_ceca08idperaca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ceca08idperaca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_ceca08idcurso()';
	$sIds='-99';
	$sSQL='SELECT ceca08idperaca FROM ceca08estadisticacurso AS TB GROUP BY ceca08idperaca';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['ceca08idperaca'];
		}
	$sWhere='exte02id IN ('.$sIds.')';
	$sSQL=f146_ConsultaCombo($sWhere, $objDB);
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2408_HTMLComboV2_ceca08idcurso($objDB, $objCombos, $valor, $vrceca08idperaca){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sSQL1='SELECT ceca08idcurso FROM ceca08estadisticacurso AS TB WHERE ceca08idperaca= '.$vrceca08idperaca.'  GROUP BY ceca08idcurso';
	$sCondi='unad40id IN ('.$sSQL1.')';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('ceca08idcurso', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_ceca08idtutor()';
	$sSQL='SELECT unad40id AS id, unad40nombre AS nombre FROM unad40curso'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2408_HTMLComboV2_ceca08idtutor($objDB, $objCombos, $valor, $vrceca08idcurso){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sSQL1='SELECT ceca08idtutor FROM ceca08estadisticacurso AS TB WHERE ceca08idcurso= '.$vrceca08idcurso .' GROUP BY ceca08idtutor';
	$sCondi='unad11id IN ('.$sSQL1.')';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('ceca08idtutor', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->sAccion='RevisaLlave();';
	//$objCombos->sAccion='carga_combo_ceca08idzona()';
	$objCombos->sAccion='cargar_zona_y_escuela()';
	$sSQL='SELECT  unad11id AS id, unad11razonsocial AS nombre FROM unad11terceros'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2408_HTMLComboV2_ceca08idzona($objDB, $objCombos, $valor, $vrceca08idtutor=''){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='';
	$sSQL1='';
	if ($vrceca08idtutor!=''){
	$sSQL1='SELECT ceca08idzona FROM ceca08estadisticacurso AS TB WHERE ceca08idtutor= '.$vrceca08idtutor.' GROUP BY ceca08idtutor';
	$sCondi=' AND unad23id IN ('.$sSQL1.')';
	}
	$objCombos->nuevo('ceca08idzona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_ceca08idcentro()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ' .$sCondi.' ORDER BY unad23nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2408_HTMLComboV2_ceca08idcentro($objDB, $objCombos, $valor, $vrceca08idzona,$vrceca08idtutor=''){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='';
	$sCondi2='';
	$sSQL1='';
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrceca08idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	if ($vrceca08idtutor!=''){
	$sSQL1='SELECT ceca08idcentro FROM ceca08estadisticacurso AS TB WHERE ceca08idtutor= '.$vrceca08idtutor.' GROUP BY ceca08idtutor';
	$sCondi2=' AND unad24id IN ('.$sSQL1.')';
	}
		
	$objCombos->nuevo('ceca08idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='paginarf2408()';
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi.$sCondi2.' ORDER BY unad24nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2408_HTMLComboV2_ceca08idescuela($objDB, $objCombos, $valor,$vrceca08idtutor=''){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi2='';
	if ($vrceca08idtutor!=''){
	$sSQL1='SELECT ceca08idescuela FROM ceca08estadisticacurso AS TB WHERE ceca08idtutor= '.$vrceca08idtutor.' GROUP BY ceca08idtutor';
	$sCondi2=' AND core12id IN ('.$sSQL1.')';
	}
	$objCombos->nuevo('ceca08idescuela', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_ceca08idprograma()';
	$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" '.$sCondi2;
	$res=$objCombos->html($sSQL, $objDB);
	//$res='  vrceca08idtutor='.$vrceca08idtutor;
	return $res;
	}
function f2408_HTMLComboV2_ceca08idprograma($objDB, $objCombos, $valor, $vrceca08idescuela,$vrceca08idtutor=''){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi2='';
	if ($vrceca08idtutor!=''){
	$sSQL1='SELECT ceca08idprograma FROM ceca08estadisticacurso AS TB WHERE ceca08idtutor= '.$vrceca08idtutor.' GROUP BY ceca08idtutor';
	$sCondi2=' AND core09id IN ('.$sSQL1.')';
	}
	//@@ Se debe arreglar la condicion..
	$sCondi='core09idescuela="'.$vrceca08idescuela.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('ceca08idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa '.$sCondi.$sCondi2;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2408_Comboceca08idcurso($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca08idcurso=f2408_HTMLComboV2_ceca08idcurso($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca08idcurso', 'innerHTML', $html_ceca08idcurso);
	$objResponse->call('paginarf2408');
	return $objResponse;
	}
function f2408_Comboceca08idtutor($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca08idtutor=f2408_HTMLComboV2_ceca08idtutor($objDB, $objCombos, '', $aParametros[0]);
	//$html_ceca08idtutor= $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca08idtutor', 'innerHTML', $html_ceca08idtutor);
	$objResponse->call('paginarf2408');
	return $objResponse;
	}
	
function f2408_Comboceca08idzona($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca08idzona=f2408_HTMLComboV2_ceca08idzona($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca08idzona', 'innerHTML', $html_ceca08idzona);
	$objResponse->call('paginarf2408');
	return $objResponse;
	}	
function f2408_Comboceca08idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca08idcentro=f2408_HTMLComboV2_ceca08idcentro($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca08idcentro', 'innerHTML', $html_ceca08idcentro);
	$objResponse->call('paginarf2408');
	return $objResponse;
	}

function f2408_Comboceca08idescuela($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	//$aParametros[1]=204573;
	$html_ceca08idescuela=f2408_HTMLComboV2_ceca08idescuela($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca08idescuela', 'innerHTML', $html_ceca08idescuela);
	$objResponse->call('paginarf2408');
	return $objResponse;
	}

function f2408_Comboceca08idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca08idprograma=f2408_HTMLComboV2_ceca08idprograma($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca08idprograma', 'innerHTML', $html_ceca08idprograma);
	$objResponse->call('paginarf2408');
	return $objResponse;
	}
function f2408_ExisteDato($datos){}
function f2408_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2408='lg/lg_2408_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2408)){$mensajes_2408='lg/lg_2408_es.php';}
	require $mensajes_todas;
	require $mensajes_2408;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sCampo=$aParametros[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$aParametrosB=array();
	$aParametrosB[101]=1;
	$aParametrosB[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_2408'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2408_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2408_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2408='lg/lg_2408_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2408)){$mensajes_2408='lg/lg_2408_es.php';}
	require $mensajes_todas;
	require $mensajes_2408;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]=1;}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	if (isset($aParametros[109])==0){$aParametros[109]='';}
	if (isset($aParametros[110])==0){$aParametros[110]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$iTipo=$aParametros[103];
	$idPeraca=$aParametros[104];
	$idCurso=$aParametros[105];
	$idTutor=$aParametros[106];
	$idzona=$aParametros[107];
	$idcentro=$aParametros[108];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($idPeraca==''){
		$sLeyenda='<b>No se ha seleccionado un periodo a consultar</b>';
		}else{
		switch($iTipo){
			case 1: //Totalizado por curso
			case 2: // Por curso Tutor
			case 3: // Por curso zona
			case 4: // Por curso centro
			case 5: // Por curso escuela
			case 6: // Por curso programa
			break;
			default:
			$sLeyenda='<b>Aun no es posible expandir este tipo de reporte {'.$iTipo.'}</b>';
			break;
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda.'<input id="paginaf2408" name="paginaf2408" type="hidden" value="'.$pagina.'"/><input id="lppf2408" name="lppf2408" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	
	if ($aParametros[105]!=''){
	$sSQLadd=$sSQLadd.' AND TB.ceca08idcurso='.$idCurso;
	}
	
	
	if ($aParametros[106]!=''){
	$sSQLadd=$sSQLadd.' AND TB.ceca08idtutor='.$idTutor;
	}
	
	if ($aParametros[107]!=''){
	$sSQLadd=$sSQLadd.' AND TB.ceca08idzona='.$idzona;
	}
	
	if ($aParametros[108]!=''){
	$sSQLadd=$sSQLadd.' AND TB.ceca08idcentro='.$idcentro;
	}
	$sTitulos='Peraca, Curso, Tutor, Zona, Centro, Escuela, Programa, Sexo, Edad, Id, Tiporegistro, Fechareporta75, Fechareporta25, Numestudiantes, Numresagados, Numreprobados, Numinasistentes, Promedio75, Promedio25, Promediototal, Puntaje75, Puntaje25';
	$sConsultaTutor='';
	if ($iTipo==2){
	$iTipoBusqueda='ceca08idtutor';
		$sConsultaTutor=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idtutor, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen  
FROM ceca08estadisticacurso AS TB
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idtutor';
		}
		
$iTipoBusqueda='ceca08idtutor';
if ($iTipo==3){
$iTipoBusqueda='ceca08idzona';

		$sConsultaZona=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idzona, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen  
FROM ceca08estadisticacurso AS TB
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idzona';
		}		

if ($iTipo==4){
$iTipoBusqueda='ceca08idcentro';

		$sConsultaZona=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idcentro, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen  
FROM ceca08estadisticacurso AS TB
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idcentro';
		}		


if ($iTipo==5){
$iTipoBusqueda='ceca08idescuela';

		$sConsultaZona=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idescuela, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen  
FROM ceca08estadisticacurso AS TB
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idescuela';
		}		



if ($iTipo==6){
$iTipoBusqueda='ceca08idprograma';

		$sConsultaZona=' UNION
SELECT TB.ceca08idcurso, "" AS unad40titulo, "" AS unad40nombre, TB.ceca08idprograma, SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen  
FROM ceca08estadisticacurso AS TB
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, TB.ceca08idprograma';
		}		


	$sSQL='SELECT TB.ceca08idcurso, T40.unad40titulo, T40.unad40nombre, -1 AS '.$iTipoBusqueda.', SUM(TB.ceca08numestudiantes) AS NumEst, SUM(TB.ceca08numreprobados) AS NumReprob, SUM(TB.ceca08numinasistentes) AS NumNoAsistentes, (SUM(TB.ceca08suma75)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom75, (SUM(TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS Prom25 , (SUM(TB.ceca08suma75+TB.ceca08suma25)/SUM(TB.ceca08numestudiantes-TB.ceca08numinasistentes)) AS PromGen  
FROM ceca08estadisticacurso AS TB, unad40curso AS T40
WHERE TB.ceca08idperaca='.$idPeraca.' AND TB.ceca08tiporegistro=1 AND TB.ceca08idcurso=T40.unad40id ' .$sSQLadd.
' GROUP BY TB.ceca08idcurso, T40.unad40titulo, T40.unad40nombre
'.$sConsultaTutor.$sConsultaZona.'
ORDER BY ceca08idcurso, '.$iTipoBusqueda;
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2408" name="consulta_2408" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2408" name="titulos_2408" type="hidden" value="'.$sTitulos.'"/>';
	//$sErrConsulta=$sSQL;
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2408: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		}
	/*<td><b>'.$ETI['ceca08idzona'].'</b></td>
<td><b>'.$ETI['ceca08idcentro'].'</b></td>
<td><b>'.$ETI['ceca08idescuela'].'</b></td>
<td><b>'.$ETI['ceca08idprograma'].'</b></td>
<td><b>'.$ETI['ceca08sexo'].'</b></td>
<td><b>'.$ETI['ceca08edad'].'</b></td>
<td><b>'.$ETI['ceca08numresagados'].'</b></td>
*/
	$sAddTitulo='';
	if ($iTipo==2){
		$sAddTitulo='
<td><b>'.$ETI['ceca08idtutor'].'</b></td>';
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['ceca08idcurso'].'</b></td>'.$sAddTitulo.'
<td align="center"><b>'.$ETI['ceca08numestudiantes'].'</b></td>
<td align="center"><b>'.$ETI['ceca08numreprobados'].'</b></td>
<td align="center"><b>'.$ETI['ceca08numinasistentes'].'</b></td>
<td align="center"><b>'.$ETI['msg_porcperd'].'</b></td>
<td align="center"><b>'.$ETI['ceca08promedio75'].'</b></td>
<td align="center"><b>'.$ETI['ceca08promedio25'].'</b></td>
<td align="center"><b>'.$ETI['ceca08promediototal'].'</b>
<td align="center"><b>'.$ETI['ceca08idzona'].'</b></td>
<td align="center"><b>'.$ETI['ceca08idcentro'].'</b></td>
<td align="center"><b>'.$ETI['ceca08idescuela'].'</b></td>
<td align="center"><b>'.$ETI['ceca08idprograma'].'</b></td>
<input id="paginaf2408" name="paginaf2408" type="hidden" value="'.$pagina.'"/><input id="lppf2408" name="lppf2408" type="hidden" value="'.$lineastabla.'"/></td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$sNomCurso=cadena_notildes($filadet['unad40nombre']);
		$sDocumento='';
		$sRazonSocial='TODOS';
		if ($iTipo==2){
			if ($filadet['ceca08idtutor']>0){
				$sRazonSocial='{'.$filadet['ceca08idtutor'].'}';
				$sSQL='SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id='.$filadet['ceca08idtutor'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sDocumento=$fila['unad11tipodoc'].$fila['unad11doc'];
					$sRazonSocial=cadena_notildes($fila['unad11razonsocial']);
					}
				}else{
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			}
			
			if ($iTipo==3){
			if ($filadet['ceca08idzona']>0){
				$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$filadet['ceca08idzona'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sZona=$fila['unad23nombre'];
					
					}
				}else{
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			}
			
			
			if ($iTipo==4){
			if ($filadet['ceca08idcentro']>0){
				$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$filadet['ceca08idcentro'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sCentro=$fila['unad24nombre'];
					
					}
				}else{
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			}
			
			
			if ($iTipo==5){
			if ($filadet['ceca08idescuela']>0){
				$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$filadet['ceca08idescuela'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sEscuela=$fila['core12nombre'];
					
					}
				}else{
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			}
			
			if ($iTipo==6){
			if ($filadet['ceca08idprograma']>0){
				$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$filadet['ceca08idprograma'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sPrograma=$fila['core09nombre'];
					
					}
				}else{
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			}
			
		$sReprobados='';
		$sNoAsistentes='';
		$sPorPerdida='';
		if ($filadet['NumReprob']>0){
			$sReprobados=$filadet['NumReprob'];
			$iAprobados=($filadet['NumEst']-$filadet['NumNoAsistentes']);
			if ($iAprobados>0){
				$sPorPerdida=formato_numero((($filadet['NumReprob']-$filadet['NumNoAsistentes'])/$iAprobados*100), 2).' %';
				}else{
				$sPorPerdida='100 %';
				}
			}
		if ($filadet['NumNoAsistentes']>0){$sNoAsistentes=$filadet['NumNoAsistentes'];}
		$sNota25='';
		if ($filadet['Prom25']>0){$sNota25=formato_numero($filadet['Prom25']/100, 2);}
		$sInfoTutor='<td>'.$sPrefijo.$sNomCurso.$sSufijo.'</td>';
		if ($iTipo==2){
			if ($filadet['ceca08idtutor']>0){
				$sInfoTutor='<td>'.$sPrefijo.$sDocumento.$sSufijo.'</td>
<td>'.$sPrefijo.$sRazonSocial.$sSufijo.'</td>';
				}else{
				$sInfoTutor='<td colspan="2">'.$sPrefijo.$sNomCurso.$sSufijo.'</td>';
				}
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf2408('.$filadet['ceca08id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			//NumEst 	NumReprob 	NumNoAsistentes 	Prom75 	Prom25 	PromGen
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad40titulo'].$sSufijo.'</td>
'.$sInfoTutor.'
<td align="center">'.$sPrefijo.$filadet['NumEst'].$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sReprobados.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sNoAsistentes.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sPorPerdida.$sSufijo.'</td>
<td align="center">'.$sPrefijo.formato_numero($filadet['Prom75']/100, 2).$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sNota25.$sSufijo.'</td>
<td align="center">'.$sPrefijo.formato_numero($filadet['PromGen']/100, 2).$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sZona.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sCentro.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sEscuela.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sPrograma.$sSufijo.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2408_HtmlTabla($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla)=f2408_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2408detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2408_db_CargarPadre($DATA, $objDB, $bDebug=false){}
function f2408_db_GuardarV2($DATA, $objDB, $bDebug=false){}
function f2408_db_Eliminar($ceca08id, $objDB, $bDebug=false){}
function f2408_TituloBusqueda(){
	return 'Busqueda de Estadistica de calificaciones';
	}
function f2408_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2408nombre" name="b2408nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2408_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2408nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2408_TablaDetalleBusquedas($aParametros, $objDB){}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>