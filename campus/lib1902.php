<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Tuesday, August 27, 2019
--- 1902 even02evento
*/
/** Archivo lib1902.php.
* Libreria 1902 even02evento.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Tuesday, August 27, 2019
*/
function f1902_HTMLComboV2_even02tipo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('even02tipo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_even02categoria()';
	$sSQL='SELECT even01id AS id, even01nombre AS nombre FROM even01tipoevento';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1902_HTMLComboV2_even02categoria($objDB, $objCombos, $valor, $vreven02tipo){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='even41idtipoevento="'.$vreven02tipo.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('even02categoria', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT even41id AS id, even41titulo AS nombre FROM even41categoria'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1902_HTMLComboV2_even02idcead($objDB, $objCombos, $valor, $vreven02nombre){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vreven02nombre.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('even02idcead', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1902_Comboeven02categoria($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_even02categoria=f1902_HTMLComboV2_even02categoria($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even02categoria', 'innerHTML', $html_even02categoria);
	return $objResponse;
	}
function f1902_Comboeven02idcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_even02idcead=f1902_HTMLComboV2_even02idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even02idcead', 'innerHTML', $html_even02idcead);
	return $objResponse;
	}
function f1902_Busqueda_db_even02idcertificado($sCodigo, $objDB, $bDebug=false){
	$sRespuesta='';
	$sDebug='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT even06id, even06titulo, even06consec FROM even06certificados WHERE even06consec="'.$sCodigo.'"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Busqueda: '.$sSQL.'<br>';}
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila['even06consec'].' '.cadena_notildes($fila['even06titulo']).'</b>';
			$id=$fila['even06id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta), $sDebug);
	}
function f1902_Busqueda_even02idcertificado($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$sDebug='';
	$scodigo=$aParametros[0];
	$bxajax=true;
	$bDebug=false;
	if (isset($aParametros[3])!=0){if ($aParametros[3]==1){$bxajax=false;}}
	if (isset($aParametros[9])!=0){if ($aParametros[9]==1){$bDebug=true;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $sRespuesta, $sDebugCon)=f1902_Busqueda_db_even02idcertificado($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1902_Busqueda_db_even02idrubrica($sCodigo, $objDB, $bDebug=false){
	$sRespuesta='';
	$sDebug='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT , ,  FROM  WHERE ="'.$sCodigo.'"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Busqueda: '.$sSQL.'<br>';}
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila[''].' '.cadena_notildes($fila['']).'</b>';
			$id=$fila[''];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta), $sDebug);
	}
function f1902_Busqueda_even02idrubrica($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$sDebug='';
	$scodigo=$aParametros[0];
	$bxajax=true;
	$bDebug=false;
	if (isset($aParametros[3])!=0){if ($aParametros[3]==1){$bxajax=false;}}
	if (isset($aParametros[9])!=0){if ($aParametros[9]==1){$bDebug=true;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $sRespuesta, $sDebugCon)=f1902_Busqueda_db_even02idrubrica($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1902_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$even02consec=numeros_validar($datos[1]);
	if ($even02consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT even02consec FROM even02evento WHERE even02consec='.$even02consec.'';
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)==0){$bHayLlave=false;}
		$objDB->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f1902_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
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
		case 'even02idorganizador':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1902);
		break;
		case 'even02idcertificado':
		$bExiste=true;
		if (file_exists('lib1906.php')){
			require 'lib1906.php';
			}else{
			$bExiste=false;
			}
		if ($bExiste){
			if (!function_exists('f1906_TablaDetalleBusquedas')){$bExiste=false;}
			}
		if ($bExiste){
			$sTabla=f1906_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo=f1906_TituloBusqueda();
			$sParams=f1906_ParametrosBusqueda();
			$sJavaBusqueda=f1906_JavaScriptBusqueda(1902);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 1906, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'even02idrubrica':
		$bExiste=true;
		if (file_exists('lib0.php')){
			require 'lib0.php';
			}else{
			$bExiste=false;
			}
		if ($bExiste){
			if (!function_exists('f0_TablaDetalleBusquedas')){$bExiste=false;}
			}
		if ($bExiste){
			$sTabla=f0_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo=f0_TituloBusqueda();
			$sParams=f0_ParametrosBusqueda();
			$sJavaBusqueda=f0_JavaScriptBusqueda(1902);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 0, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'even03idcurso':
		$bExiste=true;
		if (file_exists('lib140.php')){
			require 'lib140.php';
			}else{
			$bExiste=false;
			}
		if ($bExiste){
			if (!function_exists('f140_TablaDetalleBusquedas')){$bExiste=false;}
			}
		if ($bExiste){
			$sTabla=f140_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo=f140_TituloBusqueda();
			$sParams=f140_ParametrosBusqueda();
			$sJavaBusqueda=f140_JavaScriptBusqueda(1902);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 140, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'even04idparticipante':// aqui llega al buscar con la lupa
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1902);
		break;
		case 'even05idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1902);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_1902'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f1902_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'even02idorganizador':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'even02idcertificado':
		if (file_exists('lib1906.php')){
			require 'lib1906.php';
			$sDetalle=f1906_TablaDetalleBusquedas($aParametros, $objDB);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib1906, por favor informe al administrador del sistema.';
			}
		break;
		case 'even02idrubrica':
		if (file_exists('lib0.php')){
			require 'lib0.php';
			$sDetalle=f0_TablaDetalleBusquedas($aParametros, $objDB);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib0, por favor informe al administrador del sistema.';
			}
		break;
		case 'even03idcurso':
		if (file_exists('lib140.php')){
			require 'lib140.php';
			$sDetalle=f140_TablaDetalleBusquedas($aParametros, $objDB);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib140, por favor informe al administrador del sistema.';
			}
		break;
		case 'even04idparticipante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'even05idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1902_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[106])==0){$aParametros[106]='';} // peraca
	if (isset($aParametros[109])==0){$aParametros[109]='';} //$_REQUEST['bzona']; //109
	if (isset($aParametros[110])==0){$aParametros[110]='';} // $_REQUEST['bcead'];// 110
	if (isset($aParametros[111])==0){$aParametros[111]='';}//Desde 
	if (isset($aParametros[112])==0){$aParametros[112]='';}// Hasta
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf1902" name="paginaf1902" type="hidden" value="'.$pagina.'"/><input id="lppf1902" name="lppf1902" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	
	if ($aParametros[106]!=''){$sSQLadd1=$sSQLadd1.'  AND TB.even02peraca='.$aParametros[106].' ';}

	
	if ($aParametros[110]!=''){
		$sSQLadd1=$sSQLadd1.'  AND TB.even02idcead='.$aParametros[110].'  ';
		}else{
		if ($aParametros[109]!=''){$sSQLadd1=$sSQLadd1.'  AND TB.even02idzona='.$aParametros[109].'  ';}
		}

//Fecha Desde Hasta
	if ($aParametros[111]!=0){
			$sSQLadd1=$sSQLadd1.' AND  STR_TO_DATE(TB.even02inifecha,"%d/%m/%Y")  >= STR_TO_DATE("'.fecha_desdenumero($aParametros[111]).'","%d/%m/%Y")';
		}	
	if ($aParametros[112]!=0){
		
			$sSQLadd1=$sSQLadd1.' AND  STR_TO_DATE(TB.even02finfecha,"%d/%m/%Y") <= STR_TO_DATE("'.fecha_desdenumero($aParametros[112]).'","%d/%m/%Y")';
		}	
	
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
/*	
	$sTitulos='Consec, Id, Tipo, Categoria, Estado, Publicado, Nombre, Zona, Cead, Peraca, Lugar, Inifecha, Inihora, Iniminuto, Finfecha, Finhora, Finminuto, Organizador, Contacto, Insfechaini, Insfechafin, Certificado, Rubrica, Detalle, Formainscripcion';
*/

$sTitulos='Consec, Id, Tipo, Categoria, Estado, Publicado, Nombre, Zona, Cead, Peraca, Lugar, Inifecha, Inihora, Iniminuto, Finfecha, Finhora, Finminuto, Organizador, Contacto, Insfechaini, Insfechafin,  Detalle, Formainscripcion';	
/*
	$sSQL='SELECT TB.even02consec, TB.even02id, T3.even01nombre, T4.even41titulo, T5.even14nombre, TB.even02publicado, TB.even02nombre, 
T8.unad23nombre, T9.unad24nombre, T10.exte02nombre, TB.even02lugar, TB.even02inifecha, TB.even02inihora, TB.even02iniminuto, 
TB.even02finfecha, TB.even02finhora, TB.even02finminuto, T18.unad11razonsocial AS C18_nombre, TB.even02contacto, TB.even02insfechaini, 
TB.even02insfechafin,
-- , T22.even06titulo, 
TB.even02idrubrica, TB.even02detalle,TB.even02formainscripcion, TB.even02tipo, TB.even02categoria, TB.even02estado, 
TB.even02idzona, TB.even02idcead, TB.even02peraca, TB.even02idorganizador, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, 
TB.even02idcertificado FROM even02evento AS TB, even01tipoevento AS T3, even41categoria AS T4, even14estadoevento AS T5, unad23zona AS T8, 
unad24sede AS T9, exte02per_aca AS T10, unad11terceros AS T18
-- , even06certificados AS T22 
WHERE TB.even02tipo=T3.even01id 
AND TB.even02categoria=T4.even41id AND TB.even02estado=T5.even14id AND TB.even02idzona=T8.unad23id AND TB.even02idcead=T9.unad24id 
AND TB.even02peraca=T10.exte02id AND TB.even02idorganizador=T18.unad11id'.$sSQLadd1.' 
-- AND TB.even02idcertificado=T22.even06id 

ORDER BY TB.even02consec';
	
*/
/*
$sSQL='SELECT TB.even02consec, TB.even02id, T3.even01nombre, T4.even41titulo, T5.even14nombre, TB.even02publicado, TB.even02nombre, 
T8.unad23nombre, T9.unad24nombre, T10.exte02nombre, TB.even02lugar, TB.even02inifecha, TB.even02inihora, TB.even02iniminuto, 
TB.even02finfecha, TB.even02finhora, TB.even02finminuto, T18.unad11razonsocial AS C18_nombre, TB.even02contacto, TB.even02insfechaini, 
TB.even02insfechafin,
TB.even02idrubrica, TB.even02detalle,TB.even02formainscripcion, TB.even02tipo, TB.even02categoria, TB.even02estado, 
TB.even02idzona, TB.even02idcead, TB.even02peraca, TB.even02idorganizador, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, 
TB.even02idcertificado FROM even02evento AS TB, even01tipoevento AS T3, even41categoria AS T4, even14estadoevento AS T5, unad23zona AS T8, 
unad24sede AS T9, exte02per_aca AS T10, unad11terceros AS T18
  WHERE TB.even02tipo=T3.even01id 
AND TB.even02categoria=T4.even41id AND TB.even02estado=T5.even14id AND TB.even02idzona=T8.unad23id AND TB.even02idcead=T9.unad24id 
AND TB.even02peraca=T10.exte02id AND TB.even02idorganizador=T18.unad11id'.$sSQLadd1.' 

ORDER BY TB.even02consec';
*/
$sSQL='SELECT TB.even02consec, TB.even02id, T3.even01nombre, T4.even41titulo, T5.even14nombre, TB.even02publicado, TB.even02nombre, 
T8.unad23nombre, T9.unad24nombre, T10.exte02nombre, TB.even02lugar, TB.even02inifecha, TB.even02inihora, TB.even02iniminuto, 
TB.even02finfecha, TB.even02finhora, TB.even02finminuto, T18.unad11razonsocial AS C18_nombre, TB.even02contacto, TB.even02insfechaini, 
TB.even02insfechafin,
TB.even02detalle,TB.even02formainscripcion, TB.even02tipo, TB.even02categoria, TB.even02estado, 
TB.even02idzona, TB.even02idcead, TB.even02peraca, TB.even02idorganizador, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc 
FROM even02evento AS TB, even01tipoevento AS T3, even41categoria AS T4, even14estadoevento AS T5, unad23zona AS T8, 
unad24sede AS T9, exte02per_aca AS T10, unad11terceros AS T18
  WHERE TB.even02tipo=T3.even01id 
AND TB.even02categoria=T4.even41id AND TB.even02estado=T5.even14id AND TB.even02idzona=T8.unad23id AND TB.even02idcead=T9.unad24id 
AND TB.even02peraca=T10.exte02id AND TB.even02idorganizador=T18.unad11id'.$sSQLadd1.' 
ORDER BY TB.even02consec';


//echo  $sSQL;	
	
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1902" name="consulta_1902" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1902" name="titulos_1902" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1902: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1902" name="paginaf1902" type="hidden" value="'.$pagina.'"/><input id="lppf1902" name="lppf1902" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['even02consec'].'</b></td>
<td><b>'.$ETI['even02tipo'].'</b></td>
<td><b>'.$ETI['even02categoria'].'</b></td>
<td><b>'.$ETI['even02estado'].'</b></td>
<td><b>'.$ETI['even02publicado'].'</b></td>
<td><b>'.$ETI['even02nombre'].'</b></td>
<td><b>'.$ETI['even02idzona'].'</b></td>
<td><b>'.$ETI['even02idcead'].'</b></td>
<td><b>'.$ETI['even02peraca'].'</b></td>
<td><b>'.$ETI['even02lugar'].'</b></td>
<td><b>'.$ETI['even02inifecha'].'</b></td>
<td><b>'.$ETI['even02inihora'].'</b></td>
<td><b>'.$ETI['even02finfecha'].'</b></td>
<td><b>'.$ETI['even02finhora'].'</b></td>
<td colspan="2"><b>'.$ETI['even02idorganizador'].'</b></td>
<td><b>'.$ETI['even02contacto'].'</b></td>';
//<td><b>'.$ETI['even02insfechaini'].'</b></td>
//<td><b>'.$ETI['even02insfechafin'].'</b></td>
//<td><b>'.$ETI['even02idcertificado'].'</b></td>
//<td><b>'.$ETI['even02idrubrica'].'</b></td>
//<td><b>'.$ETI['even02detalle'].'</b></td>
$res=$res.'<td><b>'.$ETI['even02formainscripcion'].'</b></td>
<td align="right">
'.html_paginador('paginaf1902', $registros, $lineastabla, $pagina, 'paginarf1902()').'
'.html_lpp('lppf1902', $lineastabla, 'paginarf1902()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_even02publicado=$ETI['no'];
		if ($filadet['even02publicado']=='S'){$et_even02publicado=$ETI['si'];}
		$et_even02inifecha='';
		if ($filadet['even02inifecha']!='00/00/0000'){$et_even02inifecha=$filadet['even02inifecha'];}
		$et_even02inihora=html_TablaHoraMin($filadet['even02inihora'], $filadet['even02iniminuto']);
		$et_even02finfecha='';
		if ($filadet['even02finfecha']!='00/00/0000'){$et_even02finfecha=$filadet['even02finfecha'];}
		$et_even02finhora=html_TablaHoraMin($filadet['even02finhora'], $filadet['even02finminuto']);
		$et_even02insfechaini='';
		if ($filadet['even02insfechaini']!='00/00/0000'){$et_even02insfechaini=$filadet['even02insfechaini'];}
		$et_even02insfechafin='';
		if ($filadet['even02insfechafin']!='00/00/0000'){$et_even02insfechafin=$filadet['even02insfechafin'];}
		if ($filadet['even02formainscripcion']=='0'){$et_even02formainscripcion='Cerrada';}else{$et_even02formainscripcion='Abierta';}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1902('.$filadet['even02id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even02consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even41titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even14nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02publicado.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even02lugar']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02inifecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02inihora.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02finfecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02finhora.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C18_td'].' '.$filadet['C18_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C18_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even02contacto']).$sSufijo.'</td>';

//<td>'.$sPrefijo.$et_even02insfechaini.$sSufijo.'</td>
//<td>'.$sPrefijo.$et_even02insfechafin.$sSufijo.'</td>
//<td>'.$sPrefijo.cadena_notildes($filadet['even06titulo']).$sSufijo.'</td>
//<td>'.$sPrefijo.$filadet['even02idrubrica'].$sSufijo.'</td>
//<td>'.$sPrefijo.$filadet['even02detalle'].$sSufijo.'</td>

$res=$res.'<td>'.$sPrefijo.$et_even02formainscripcion.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1902_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1902_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1902detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1902_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['even02idorganizador_td']=$APP->tipo_doc;
	$DATA['even02idorganizador_doc']='';
	$DATA['even02idcertificado_cod']='';
	$DATA['even02idrubrica_cod']='';
	if ($DATA['paso']==1){
		$sSQLcondi='even02consec='.$DATA['even02consec'].'';
		}else{
		$sSQLcondi='even02id='.$DATA['even02id'].'';
		}
	$sSQL='SELECT * FROM even02evento WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['even02consec']=$fila['even02consec'];
		$DATA['even02id']=$fila['even02id'];
		$DATA['even02tipo']=$fila['even02tipo'];
		$DATA['even02categoria']=$fila['even02categoria'];
		$DATA['even02estado']=$fila['even02estado'];
		$DATA['even02publicado']=$fila['even02publicado'];
		$DATA['even02nombre']=$fila['even02nombre'];
		$DATA['even02idzona']=$fila['even02idzona'];
		$DATA['even02idcead']=$fila['even02idcead'];
		$DATA['even02peraca']=$fila['even02peraca'];
		$DATA['even02lugar']=$fila['even02lugar'];
		$DATA['even02inifecha']=$fila['even02inifecha'];
		$DATA['even02inihora']=$fila['even02inihora'];
		$DATA['even02iniminuto']=$fila['even02iniminuto'];
		$DATA['even02finfecha']=$fila['even02finfecha'];
		$DATA['even02finhora']=$fila['even02finhora'];
		$DATA['even02finminuto']=$fila['even02finminuto'];
		$DATA['even02idorganizador']=$fila['even02idorganizador'];
		$DATA['even02contacto']=$fila['even02contacto'];
		$DATA['even02insfechaini']=$fila['even02insfechaini'];
		$DATA['even02insfechafin']=$fila['even02insfechafin'];
		$DATA['even02idcertificado']=$fila['even02idcertificado'];
		$DATA['even02idrubrica']=$fila['even02idrubrica'];
		$DATA['even02detalle']=$fila['even02detalle'];
		$DATA['even02formainscripcion']=$fila['even02formainscripcion'];
		$sSQL='SELECT even06consec, even06titulo FROM even06certificados WHERE even06id='.$DATA['even02idcertificado'];
		$tabladet=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladet)>0){
			$filadet=$objDB->sf($tabladet);
			$DATA['even02idcertificado_cod']=$filadet['even06consec'];
			$even02idcertificado_nombre=$filadet['even06titulo'];
			}
		$sSQL='SELECT ,  FROM  WHERE ='.$DATA['even02idrubrica'];
		$tabladet=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladet)>0){
			$filadet=$objDB->sf($tabladet);
			$DATA['even02idrubrica_cod']=$DATA['even02idrubrica'];
			$even02idrubrica_nombre=$filadet[''];
			}
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta1902']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1902_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=1902;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['even02consec'])==0){$DATA['even02consec']='';}
	if (isset($DATA['even02id'])==0){$DATA['even02id']='';}
	if (isset($DATA['even02tipo'])==0){$DATA['even02tipo']='';}
	if (isset($DATA['even02categoria'])==0){$DATA['even02categoria']='';}
	if (isset($DATA['even02publicado'])==0){$DATA['even02publicado']='';}
	if (isset($DATA['even02nombre'])==0){$DATA['even02nombre']='';}
	if (isset($DATA['even02idzona'])==0){$DATA['even02idzona']='';}
	if (isset($DATA['even02idcead'])==0){$DATA['even02idcead']='';}
	if (isset($DATA['even02peraca'])==0){$DATA['even02peraca']='';}
	if (isset($DATA['even02lugar'])==0){$DATA['even02lugar']='';}
	if (isset($DATA['even02inifecha'])==0){$DATA['even02inifecha']='';}
	if (isset($DATA['even02inihora'])==0){$DATA['even02inihora']='';}
	if (isset($DATA['even02iniminuto'])==0){$DATA['even02iniminuto']='';}
	if (isset($DATA['even02finfecha'])==0){$DATA['even02finfecha']='';}
	if (isset($DATA['even02finhora'])==0){$DATA['even02finhora']='';}
	if (isset($DATA['even02finminuto'])==0){$DATA['even02finminuto']='';}
	if (isset($DATA['even02idorganizador'])==0){$DATA['even02idorganizador']='';}
	if (isset($DATA['even02contacto'])==0){$DATA['even02contacto']='';}
	if (isset($DATA['even02insfechaini'])==0){$DATA['even02insfechaini']='';}
	if (isset($DATA['even02insfechafin'])==0){$DATA['even02insfechafin']='';}
	if (isset($DATA['even02idcertificado'])==0){$DATA['even02idcertificado']='';}
	if (isset($DATA['even02idrubrica'])==0){$DATA['even02idrubrica']='';}
	if (isset($DATA['even02detalle'])==0){$DATA['even02detalle']='';}
	if (isset($DATA['even02formainscripcion'])==0){$DATA['even02formainscripcion']='';}
	*/
	$DATA['even02consec']=numeros_validar($DATA['even02consec']);
	$DATA['even02tipo']=numeros_validar($DATA['even02tipo']);
	$DATA['even02categoria']=numeros_validar($DATA['even02categoria']);
	$DATA['even02publicado']=htmlspecialchars(trim($DATA['even02publicado']));
	$DATA['even02nombre']=htmlspecialchars(trim($DATA['even02nombre']));
	$DATA['even02idzona']=numeros_validar($DATA['even02idzona']);
	$DATA['even02idcead']=numeros_validar($DATA['even02idcead']);
	$DATA['even02peraca']=numeros_validar($DATA['even02peraca']);
	$DATA['even02lugar']=htmlspecialchars(trim($DATA['even02lugar']));
    $DATA['even02url']=htmlspecialchars(trim($DATA['even02url']));
    $DATA['even02modalidad']=htmlspecialchars(trim($DATA['even02modalidad']));
	$DATA['even02inihora']=numeros_validar($DATA['even02inihora']);
	$DATA['even02iniminuto']=numeros_validar($DATA['even02iniminuto']);
	$DATA['even02finhora']=numeros_validar($DATA['even02finhora']);
	$DATA['even02finminuto']=numeros_validar($DATA['even02finminuto']);
	$DATA['even02contacto']=htmlspecialchars(trim($DATA['even02contacto']));
	$DATA['even02idcertificado']=numeros_validar($DATA['even02idcertificado']);
	$DATA['even02idrubrica']=numeros_validar($DATA['even02idrubrica']);
	$DATA['even02detalle']=htmlspecialchars(trim($DATA['even02detalle']));
	$DATA['even02formainscripcion']=numeros_validar($DATA['even02formainscripcion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['even02tipo']==''){$DATA['even02tipo']=0;}
	//if ($DATA['even02categoria']==''){$DATA['even02categoria']=0;}
	if ($DATA['even02estado']==''){$DATA['even02estado']=0;}
	//if ($DATA['even02idzona']==''){$DATA['even02idzona']=0;}
	//if ($DATA['even02idcead']==''){$DATA['even02idcead']=0;}
	//if ($DATA['even02peraca']==''){$DATA['even02peraca']=0;}
	//if ($DATA['even02inihora']==''){$DATA['even02inihora']=0;}
	//if ($DATA['even02iniminuto']==''){$DATA['even02iniminuto']=0;}
	//if ($DATA['even02finhora']==''){$DATA['even02finhora']=0;}
	//if ($DATA['even02finminuto']==''){$DATA['even02finminuto']=0;}
	//if ($DATA['even02idcertificado']==''){$DATA['even02idcertificado']=0;}
	//if ($DATA['even02idrubrica']==''){$DATA['even02idrubrica']=0;}
	//if ($DATA['even02formainscripcion']==''){$DATA['even02formainscripcion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['even02formainscripcion']==''){$sError=$ERR['even02formainscripcion'].$sSepara.$sError;}
		//if ($DATA['even02detalle']==''){$sError=$ERR['even02detalle'].$sSepara.$sError;}
		//if ($DATA['even02idrubrica']==''){$sError=$ERR['even02idrubrica'].$sSepara.$sError;}
		//if ($DATA['even02idcertificado']==''){$sError=$ERR['even02idcertificado'].$sSepara.$sError;}
		if (!fecha_esvalida($DATA['even02insfechafin'])){
			//$DATA['even02insfechafin']='00/00/0000';
			$sError=$ERR['even02insfechafin'].$sSepara.$sError;
			}
		if (!fecha_esvalida($DATA['even02insfechaini'])){
			//$DATA['even02insfechaini']='00/00/0000';
			$sError=$ERR['even02insfechaini'].$sSepara.$sError;
			}
		if ($DATA['even02contacto']==''){$sError=$ERR['even02contacto'].$sSepara.$sError;}
		if ($DATA['even02idorganizador']==0){$sError=$ERR['even02idorganizador'].$sSepara.$sError;}
		if ($DATA['even02finminuto']==''){$sError=$ERR['even02finminuto'].$sSepara.$sError;}
		if ($DATA['even02finhora']==''){$sError=$ERR['even02finhora'].$sSepara.$sError;}
		if (!fecha_esvalida($DATA['even02finfecha'])){
			//$DATA['even02finfecha']='00/00/0000';
			$sError=$ERR['even02finfecha'].$sSepara.$sError;
			}
		if ($DATA['even02iniminuto']==''){$sError=$ERR['even02iniminuto'].$sSepara.$sError;}
		if ($DATA['even02inihora']==''){$sError=$ERR['even02inihora'].$sSepara.$sError;}
		if (!fecha_esvalida($DATA['even02inifecha'])){
			//$DATA['even02inifecha']='00/00/0000';
			$sError=$ERR['even02inifecha'].$sSepara.$sError;
			}
		if ($DATA['even02lugar']==''){$sError=$ERR['even02lugar'].$sSepara.$sError;}
		    	
        if ($DATA['even02modalidad']==''){$sError=$ERR['even02modalidad'].$sSepara.$sError;}
		if ($DATA['even02modalidad']!=0){
			if ($DATA['even02url']==''){$sError=$ERR['even02url'].$sSepara.$sError;}			
			}
		if ($DATA['even02urlinfo']==''){$sError=$ERR['even02urlinfo'].$sSepara.$sError;}
		if ($DATA['even02peraca']==''){$sError=$ERR['even02peraca'].$sSepara.$sError;}
		if ($DATA['even02idcead']==''){$sError=$ERR['even02idcead'].$sSepara.$sError;}
		if ($DATA['even02idzona']==''){$sError=$ERR['even02idzona'].$sSepara.$sError;}
		if ($DATA['even02nombre']==''){$sError=$ERR['even02nombre'].$sSepara.$sError;}
		if ($DATA['even02publicado']==''){$sError=$ERR['even02publicado'].$sSepara.$sError;}
		if ($DATA['even02categoria']==''){$sError=$ERR['even02categoria'].$sSepara.$sError;}
		if ($DATA['even02tipo']==''){$sError=$ERR['even02tipo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		$DATA['even02idrubrica_cod']=trim($DATA['even02idrubrica_cod']);
		if ($DATA['even02idrubrica_cod']!=''){
			$sSQL='SELECT  FROM  WHERE ="'.$DATA['even02idrubrica_cod'].'"';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)==0){$sError='El c&oacute;digo "'.$DATA['even02idrubrica_cod'].'" en '.$ETI['even02idrubrica'].' no existe';}
			}else{
			//$sError=$ERR['even02idrubrica'];
			}
		}
	if ($sError==''){
		$DATA['even02idcertificado_cod']=trim($DATA['even02idcertificado_cod']);
		if ($DATA['even02idcertificado_cod']!=''){
			$sSQL='SELECT even06id FROM even06certificados WHERE even06consec="'.$DATA['even02idcertificado_cod'].'"';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)==0){$sError='El c&oacute;digo "'.$DATA['even02idcertificado_cod'].'" en '.$ETI['even02idcertificado'].' no existe';}
			}else{
			//$sError=$ERR['even02idcertificado'];
			}
		}
	if ($DATA['even02idorganizador_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['even02idorganizador_td'], $DATA['even02idorganizador_doc'], $objDB, 'El tercero Organizador ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['even02idorganizador'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['even02consec']==''){
				$DATA['even02consec']=tabla_consecutivo('even02evento', 'even02consec', '', $objDB);
				if ($DATA['even02consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['even02consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT even02consec FROM even02evento WHERE even02consec='.$DATA['even02consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['even02id']=tabla_consecutivo('even02evento','even02id', '', $objDB);
			if ($DATA['even02id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['even02detalle']=stripslashes($DATA['even02detalle']);}
		//Si el campo even02detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$even02detalle=addslashes($DATA['even02detalle']);
		$even02detalle=str_replace('"', '\"', $DATA['even02detalle']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['even02estado']=0;
			$sCampos1902='even02consec, even02id, even02tipo, even02categoria, even02estado, even02publicado, even02nombre, even02idzona, even02idcead, even02peraca, 
even02lugar, even02inifecha, even02inihora, even02iniminuto, even02finfecha, even02finhora, even02finminuto, even02idorganizador, even02contacto, even02insfechaini, 
even02insfechafin, even02idcertificado, even02idrubrica, even02detalle, even02formainscripcion, even02url, even02modalidad,even02urlinfo';
			$sValores1902=''.$DATA['even02consec'].', '.$DATA['even02id'].', '.$DATA['even02tipo'].', '.$DATA['even02categoria'].', '.$DATA['even02estado'].', "'.$DATA['even02publicado'].'", "'.$DATA['even02nombre'].'", '.$DATA['even02idzona'].', '.$DATA['even02idcead'].', '.$DATA['even02peraca'].', 
"'.$DATA['even02lugar'].'", "'.$DATA['even02inifecha'].'", '.$DATA['even02inihora'].', '.$DATA['even02iniminuto'].', "'.$DATA['even02finfecha'].'", '.$DATA['even02finhora'].', '.$DATA['even02finminuto'].', '.$DATA['even02idorganizador'].', "'.$DATA['even02contacto'].'", "'.$DATA['even02insfechaini'].'", 
"'.$DATA['even02insfechafin'].'", '.$DATA['even02idcertificado'].', '.$DATA['even02idrubrica'].', "'.$even02detalle.'", '.$DATA['even02formainscripcion'].', "'
.$DATA['even02url'].'", '.$DATA['even02modalidad'].'," '.$DATA['even02urlinfo'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO even02evento ('.$sCampos1902.') VALUES ('.utf8_encode($sValores1902).');';
				$sdetalle=$sCampos1902.'['.utf8_encode($sValores1902).']';
				}else{
				$sSQL='INSERT INTO even02evento ('.$sCampos1902.') VALUES ('.$sValores1902.');';
				$sdetalle=$sCampos1902.'['.$sValores1902.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='even02categoria';
			$scampo[2]='even02publicado';
			$scampo[3]='even02nombre';
			$scampo[4]='even02idzona';
			$scampo[5]='even02idcead';
			$scampo[6]='even02peraca';
			$scampo[7]='even02lugar';
			$scampo[8]='even02inifecha';
			$scampo[9]='even02inihora';
			$scampo[10]='even02iniminuto';
			$scampo[11]='even02finfecha';
			$scampo[12]='even02finhora';
			$scampo[13]='even02finminuto';
			$scampo[14]='even02idorganizador';
			$scampo[15]='even02contacto';
			$scampo[16]='even02insfechaini';
			$scampo[17]='even02insfechafin';
			$scampo[18]='even02idcertificado';
			$scampo[19]='even02idrubrica';
			$scampo[20]='even02detalle';
			$scampo[21]='even02formainscripcion';
			$scampo[22]='even02estado';
            $scampo[23]='even02url';
            $scampo[24]='even02modalidad';
			$scampo[25]='even02urlinfo';
			$sdato[1]=$DATA['even02categoria'];
			$sdato[2]=$DATA['even02publicado'];
			$sdato[3]=$DATA['even02nombre'];
			$sdato[4]=$DATA['even02idzona'];
			$sdato[5]=$DATA['even02idcead'];
			$sdato[6]=$DATA['even02peraca'];
			$sdato[7]=$DATA['even02lugar'];
			$sdato[8]=$DATA['even02inifecha'];
			$sdato[9]=$DATA['even02inihora'];
			$sdato[10]=$DATA['even02iniminuto'];
			$sdato[11]=$DATA['even02finfecha'];
			$sdato[12]=$DATA['even02finhora'];
			$sdato[13]=$DATA['even02finminuto'];
			$sdato[14]=$DATA['even02idorganizador'];
			$sdato[15]=$DATA['even02contacto'];
			$sdato[16]=$DATA['even02insfechaini'];
			$sdato[17]=$DATA['even02insfechafin'];
			$sdato[18]=$DATA['even02idcertificado'];
			$sdato[19]=$DATA['even02idrubrica'];
			$sdato[20]=$even02detalle;
			$sdato[21]=$DATA['even02formainscripcion'];
			$sdato[22]=$DATA['even02estado'];
            $sdato[23]=$DATA['even02url'];
            $sdato[24]=$DATA['even02modalidad'];
			$sdato[25]=$DATA['even02urlinfo'];
			$numcmod=25;
			$sWhere='even02id='.$DATA['even02id'].'';
			$sSQL='SELECT * FROM even02evento WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE even02evento SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE even02evento SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [1902] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['even02id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 1902 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['even02id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1902_db_Eliminar($even02id, $objDB, $bDebug=false){
	$iCodModulo=1902;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$even02id=numeros_validar($even02id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM even02evento WHERE even02id='.$even02id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$even02id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT even03idevento FROM even03eventocurso WHERE even03idevento='.$filabase['even02id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Cursos creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT even04idevento FROM even04eventoparticipante WHERE even04idevento='.$filabase['even02id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Participantes creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT even05idevento FROM eve05eventonoticia WHERE even05idevento='.$filabase['even02id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Noticias creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1902';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['even02id'].' LIMIT 0, 1';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		//$sSQL='DELETE FROM even03eventocurso WHERE even03idevento='.$filabase['even02id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM even04eventoparticipante WHERE even04idevento='.$filabase['even02id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM eve05eventonoticia WHERE even05idevento='.$filabase['even02id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='even02id='.$even02id.'';
		//$sWhere='even02consec='.$filabase['even02consec'].'';
		$sSQL='DELETE FROM even02evento WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $even02id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f1902_TituloBusqueda(){
	return 'Busqueda de Eventos';
	}
function f1902_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b1902nombre" name="b1902nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f1902_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b1902nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f1902_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf1902" name="paginaf1902" type="hidden" value="'.$pagina.'"/><input id="lppf1902" name="lppf1902" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
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
	$sTitulos='Consec, Id, Tipo, Categoria, Estado, Publicado, Nombre, Zona, Cead, Peraca, Lugar, Inifecha, Inihora, Iniminuto, Finfecha, Finhora, Finminuto, Organizador, Contacto, Insfechaini, Insfechafin, Certificado, Rubrica, Detalle, Formainscripcion';
	$sSQL='SELECT TB.even02consec, TB.even02id, T3.even01nombre, T4.even41titulo, T5.even14nombre, TB.even02publicado, TB.even02nombre, T8.unad23nombre, T9.unad24nombre, T10.exte02nombre, TB.even02lugar, TB.even02inifecha, TB.even02inihora, TB.even02iniminuto, TB.even02finfecha, TB.even02finhora, TB.even02finminuto, T18.unad11razonsocial AS C18_nombre, TB.even02contacto, TB.even02insfechaini, TB.even02insfechafin, T22.even06titulo, TB.even02idrubrica, TB.even02detalle, TB.even02formainscripcion, TB.even02tipo, TB.even02categoria, TB.even02estado, TB.even02idzona, TB.even02idcead, TB.even02peraca, TB.even02idorganizador, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.even02idcertificado 
FROM even02evento AS TB, even01tipoevento AS T3, even41categoria AS T4, even14estadoevento AS T5, unad23zona AS T8, unad24sede AS T9, exte02per_aca AS T10, unad11terceros AS T18, even06certificados AS T22 
WHERE '.$sSQLadd1.' TB.even02tipo=T3.even01id AND TB.even02categoria=T4.even41id AND TB.even02estado=T5.even14id AND TB.even02idzona=T8.unad23id AND TB.even02idcead=T9.unad24id AND TB.even02peraca=T10.exte02id AND TB.even02idorganizador=T18.unad11id AND TB.even02idcertificado=T22.even06id '.$sSQLadd.'
ORDER BY TB.even02consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1902" name="paginaf1902" type="hidden" value="'.$pagina.'"/><input id="lppf1902" name="lppf1902" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['even02consec'].'</b></td>
<td><b>'.$ETI['even02tipo'].'</b></td>
<td><b>'.$ETI['even02categoria'].'</b></td>
<td><b>'.$ETI['even02estado'].'</b></td>
<td><b>'.$ETI['even02publicado'].'</b></td>
<td><b>'.$ETI['even02nombre'].'</b></td>
<td><b>'.$ETI['even02idzona'].'</b></td>
<td><b>'.$ETI['even02idcead'].'</b></td>
<td><b>'.$ETI['even02peraca'].'</b></td>
<td><b>'.$ETI['even02lugar'].'</b></td>
<td><b>'.$ETI['even02inifecha'].'</b></td>
<td><b>'.$ETI['even02inihora'].'</b></td>
<td><b>'.$ETI['even02finfecha'].'</b></td>
<td><b>'.$ETI['even02finhora'].'</b></td>
<td colspan="2"><b>'.$ETI['even02idorganizador'].'</b></td>
<td><b>'.$ETI['even02contacto'].'</b></td>
<td><b>'.$ETI['even02insfechaini'].'</b></td>
<td><b>'.$ETI['even02insfechafin'].'</b></td>
<td><b>'.$ETI['even02idcertificado'].'</b></td>
<td><b>'.$ETI['even02idrubrica'].'</b></td>
<td><b>'.$ETI['even02detalle'].'</b></td>
<td><b>'.$ETI['even02formainscripcion'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['even02id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_even02inifecha='';
		if ($filadet['even02inifecha']!='00/00/0000'){$et_even02inifecha=$filadet['even02inifecha'];}
		$et_even02inihora=html_TablaHoraMin($filadet['even02inihora'], $filadet['even02iniminuto']);
		$et_even02finfecha='';
		if ($filadet['even02finfecha']!='00/00/0000'){$et_even02finfecha=$filadet['even02finfecha'];}
		$et_even02finhora=html_TablaHoraMin($filadet['even02finhora'], $filadet['even02finminuto']);
		$et_even02insfechaini='';
		if ($filadet['even02insfechaini']!='00/00/0000'){$et_even02insfechaini=$filadet['even02insfechaini'];}
		$et_even02insfechafin='';
		if ($filadet['even02insfechafin']!='00/00/0000'){$et_even02insfechafin=$filadet['even02insfechafin'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['even02consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even41titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even14nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even02publicado'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even02lugar']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02inifecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02inihora.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02finfecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02finhora.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C18_td'].' '.$filadet['C18_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C18_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even02contacto']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02insfechaini.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02insfechafin.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even06titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even02idrubrica'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even02detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even02formainscripcion'].$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------


function f1902_Buscar_Participante($aDatos){
$Docu=$aDatos[0];
$Id=$aDatos[1];
$sSQL='';
$sSQL1='';
$sSQLadd='';
$even04institucion='';
$even04cargo='';
$even04correo='';
$even04telefono='';
$bDebug=true;
$core16tercero='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
	if ($Docu!=''){
	$sSQLadd=' WHERE unad11terceros.unad11doc='.$Docu;// Buscando en matriculados
	}
	
	if ($Id!=''){
	$sSQLadd=' WHERE unad11terceros.unad11id='.$Id;// Buscando en matriculados
	}
		$sSQL='SELECT unad11id,unad11telefono,unad11correo,unad11rolunad FROM unad11terceros '.$sSQLadd;
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idtercero=$fila['unad11id'];
		    $even04correo=$fila['unad11correo'];
			$even04telefono= $fila['unad11telefono'];
			$Idcargo= $fila['unad11rolunad'];
			switch($Idcargo){
			case '-1':
			$even04cargo='Sin definir';
			break;
			case '0':
			$even04cargo='Estudiante';
			break;
			case '1':
			$even04cargo='Contratista';
			break;
			case '2':
			$even04cargo='Personal de planta';
			break;
			case '3':
			$even04cargo='Egresado';
			break;
			
			}
			
		
		$sSQL='SELECT core16idzona,core16idcead,core16idescuela,core16idprograma FROM core16actamatricula WHERE core16tercero='.$Idtercero; 
		
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idzona=$fila['core16idzona'];
			$Idcead=$fila['core16idcead'];
			$Idescuela=$fila['core16idescuela'];
			$Idprograma=$fila['core16idprograma'];
			
			if($Idzona!=0){
				$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id=' .$Idzona;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad23nombre'];
				}			
			}
			
			if($Idcead!=0){
				$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id=' .$Idcead;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad24nombre'];
				}			
			}
			if($Idescuela!=0){
				$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id=' .$Idescuela;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core12nombre'];
				}			
			}
			
			if($Idprograma!=0){
				$sSQL='SELECT core09nombre FROM core09programa WHERE core09id=' .$Idprograma;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core09nombre'];
				}			
			}
			
		$even04cargo='Estudiante';
		
		// TERMINA SI ES ESTUDIANTE MATRICULADO
	}else{// buscar en graduados
	$sSQL='SELECT core01idzona,core011idcead,core01idescuela,core01idprograma FROM core01estprograma WHERE core01idtercero='. $Idtercero .' AND core01gradoestado=21 ORDER BY core01gradofecha DESC LIMIT 1';
		
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idzona=$fila['core01idzona'];
			$Idcead=$fila['core011idcead'];
			$Idescuela=$fila['core01idescuela'];
			$Idprograma=$fila['core01idprograma'];
			
			if($Idzona!=0){
				$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id=' .$Idzona;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad23nombre'];
				}			
			}
			
			if($Idcead!=0){
				$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id=' .$Idcead;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad24nombre'];
				}			
			}
			if($Idescuela!=0){
				$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id=' .$Idescuela;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core12nombre'];
				}			
			}
			
			if($Idprograma!=0){
				$sSQL='SELECT core09nombre FROM core09programa WHERE core09id=' .$Idprograma;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core09nombre'];
				}			
			}
			
		$even04cargo='Graduado';
		
		// TERMINA SI ES GRADUADO
	
		}else{// Buscando en Usuarios
	$sSQL='SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero='. $Idtercero .' AND unad07usuarios.unad07vigente="S"   LIMIT 1';
		
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idperfil=$fila['unad07idperfil'];
			
			
			if($Idperfil!=0){
				$sSQL='SELECT unad05nombre FROM unad05perfiles WHERE unad05id=' .$Idperfil;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04cargo='Funcionario '.$fila['unad05nombre'];
				}			
			}
			
		$even04institucion='UNAD';	
		
		
	
	
	}	
		
		
		
	}	
		
		
			
	
	}
	}
	$objResponse=new xajaxResponse();
	$objResponse->assign('even04institucion','value', $even04institucion);
	$objResponse->assign('even04cargo','value', $even04cargo);
	$objResponse->assign('even04correo','value', $even04correo);
	$objResponse->assign('even04telefono','value', $even04telefono);
	return $objResponse;

}



function f1902_Cargar_Participante($aDatos){
	$Docu=$aDatos[0];
$Id=$aDatos[1];
$sSQL='';
$sSQL1='';
$sSQLadd='';
$even04institucion='';
$even04cargo='';
$even04correo='';
$even04telefono='';
$bDebug=true;
$Idtercero='';
$sTipoDoc='';
$sDoc='';
$unad11razonsocial='';
$ArrayParticipantes= array();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	require $mensajes_todas;
	require $mensajes_1902;
	if ($Docu!=''){
	$sSQLadd=' WHERE unad11terceros.unad11doc='.$Docu;// Buscando en matriculados
	}
	
	if ($Id!=''){
	$sSQLadd=' WHERE unad11terceros.unad11id='.$Id;// Buscando en matriculados
	}
		$sSQL='SELECT unad11id,unad11telefono,unad11correo,unad11rolunad,unad11terceros.unad11tipodoc,unad11doc,unad11razonsocial,unad11id FROM unad11terceros '.$sSQLadd;
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idtercero=$fila['unad11id'];
			$sTipoDoc=$fila['unad11tipodoc'];
			$sDoc=$fila['unad11doc'];
            $unad11razonsocial=$fila['unad11razonsocial'];
		    $even04correo=$fila['unad11correo'];
			$even04telefono= $fila['unad11telefono'];
			$Idcargo= $fila['unad11rolunad'];
			switch($Idcargo){
			case '-1':
			$even04cargo='Sin definir';
			break;
			case '0':
			$even04cargo='Estudiante';
			break;
			case '1':
			$even04cargo='Contratista';
			break;
			case '2':
			$even04cargo='Personal de planta';
			break;
			case '3':
			$even04cargo='Egresado';
			break;
			
			}
			
		
		$sSQL='SELECT core16idzona,core16idcead,core16idescuela,core16idprograma FROM core16actamatricula WHERE core16tercero='.$Idtercero; 
		
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idzona=$fila['core16idzona'];
			$Idcead=$fila['core16idcead'];
			$Idescuela=$fila['core16idescuela'];
			$Idprograma=$fila['core16idprograma'];
			
			if($Idzona!=0){
				$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id=' .$Idzona;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad23nombre'];
				}			
			}
			
			if($Idcead!=0){
				$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id=' .$Idcead;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad24nombre'];
				}			
			}
			if($Idescuela!=0){
				$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id=' .$Idescuela;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core12nombre'];
				}			
			}
			
			if($Idprograma!=0){
				$sSQL='SELECT core09nombre FROM core09programa WHERE core09id=' .$Idprograma;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core09nombre'];
				}			
			}
			
		$even04cargo='Estudiante';
		
		// TERMINA SI ES ESTUDIANTE MATRICULADO
	}else{// buscar en graduados
	$sSQL='SELECT core01idzona,core011idcead,core01idescuela,core01idprograma FROM core01estprograma WHERE core01idtercero='. $Idtercero .' AND core01gradoestado=21 ORDER BY core01gradofecha DESC LIMIT 1';
		
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idzona=$fila['core01idzona'];
			$Idcead=$fila['core011idcead'];
			$Idescuela=$fila['core01idescuela'];
			$Idprograma=$fila['core01idprograma'];
			
			if($Idzona!=0){
				$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id=' .$Idzona;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad23nombre'];
				}			
			}
			
			if($Idcead!=0){
				$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id=' .$Idcead;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['unad24nombre'];
				}			
			}
			if($Idescuela!=0){
				$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id=' .$Idescuela;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core12nombre'];
				}			
			}
			
			if($Idprograma!=0){
				$sSQL='SELECT core09nombre FROM core09programa WHERE core09id=' .$Idprograma;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04institucion=$even04institucion.' '.$fila['core09nombre'];
				}			
			}
			
		$even04cargo='Graduado';
		
		// TERMINA SI ES GRADUADO
	
		}else{// Buscando en Usuarios
	$sSQL='SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero='. $Idtercero .' AND unad07usuarios.unad07vigente="S"   LIMIT 1';
		
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$Idperfil=$fila['unad07idperfil'];
			
			
			if($Idperfil!=0){
				$sSQL='SELECT unad05nombre FROM unad05perfiles WHERE unad05id=' .$Idperfil;
				$res=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($res)!=0){
				$fila=$objDB->sf($res);
				$even04cargo='Funcionario '.$fila['unad05nombre'];
				}			
			}
			
		$even04institucion='UNAD';	
		
		
	
	
	}	
		
		
		
	}	
		
		
			
	
	}
	}
	
	$ArrayParticipantes[0]=$Idtercero;
	$ArrayParticipantes[1]=$sTipoDoc;
	$ArrayParticipantes[2]=$sDoc;
	$ArrayParticipantes[3]=$unad11razonsocial;
	$ArrayParticipantes[4]=$even04institucion;
	$ArrayParticipantes[5]=$even04cargo;
	$ArrayParticipantes[6]=$even04correo;
	$ArrayParticipantes[7]=$even04telefono;
	
		
	return $ArrayParticipantes;
	

}


function f1902_HTMLComboV2_bcead($objDB, $objCombos, $valor, $vrcara01idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrcara01idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('bcead', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf1902()';
	$res=$objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi, $objDB);
	return $res;
	}

function f1902_Combobcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bcead=f1902_HTMLComboV2_bcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bcead', 'innerHTML', $html_bcead);
	$objResponse->call('paginarf1902');
	return $objResponse;
	}

?>