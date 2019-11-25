<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.12.13 mi�rcoles, 22 de junio de 2016
--- Modelo Versi�n 2.19.7c jueves, 25 de enero de 2018
--- 1707 ofer08oferta
*/
/** Archivo lib1707.php.
* Libreria 1707 ofer08oferta.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date sábado, 1 de junio de 2019
*/
function f1707_HTMLComboV2_ofer08idper_aca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ofer08idper_aca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave(1);';
	$res=$objCombos->html('SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca', $objDB);
	return $res;
	}
function f1707_HTMLComboV2_ofer08cead($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ofer08cead', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$res=$objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede', $objDB);
	return $res;
	}
function f1707_HTMLComboV2_ofer08idescuela($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ofer08idescuela', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$res=$objCombos->html('SELECT exte01id AS id, exte01nombre AS nombre FROM exte01escuela', $objDB);
	return $res;
	}
function f1707_HTMLComboV2_ofer08metodomatricula($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ofer08metodomatricula', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$res=$objCombos->html('SELECT unad61id AS id, unad61nombre AS nombre FROM unad61origenmatricula', $objDB);
	return $res;
	}
function f1707_NombreCohorte($idCohorte, $sProceso, $iEstado, $objDB, $bCampo=true, $bMostrarFecha2=false, $bHTML=true){
	$sNegIni='<b>';
	$sNegFin='</b>';
	if (!$bHTML){
		$sNegIni='';
		$sNegFin='';
		}
	$sRes='{'.$sNegIni.'No se ha definido un cohorte'.$sNegFin.'}';
	$sAlerta='';
	if ($idCohorte!=0){
		$sRes='{'.$idCohorte.'}';
		$sCampos='ofer52certfechaentregaescuela AS Fecha1, ofer52certfechaentregavimep AS Fecha2';
		if ($sProceso=='S'){$sCampos='ofer52acrefechaentregaescuela AS Fecha1, ofer52acrefechaentregavimep AS Fecha2';}
		$sql='SELECT ofer52consec, '.$sCampos.' FROM ofer52cohortes WHERE ofer52id='.$idCohorte.'';
		$tabla=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sFecha2='';
			if ($bMostrarFecha2){$sFecha2=' '.fecha_desdenumero($fila['Fecha2']).'';}
			$sAlerta='';
			$iHoy=fecha_DiaMod();
			$bEstaVencido=false;
			if ($bCampo){
				//Solo si necesitamos el campo se carga alerta.
				if ($iHoy>$fila['Fecha1']){
					//Pero si ya fue entregado, no esta vencido..
					switch($iEstado){
						case 8: // En Acreditacion.
						case 10: // Acreditado
						case 11: // En Certificacion
						case 12: //Certificado
						break;
						default:
						$bEstaVencido=true;
						break;
						}
					}
				}
			if ($bEstaVencido){
				//Esta vencido...
				$iEstadoFin=12;
				if ($sProceso=='S'){$iEstadoFin=10;}
				if ($iEstado<>$iEstadoFin){
					$sfechaini=fecha_desdenumero($fila['Fecha1']);
					$sfechafin=fecha_hoy();
					$iDias=fecha_numdiasentrefechas($sfechaini,$sfechafin);
					$sAlerta='&nbsp;<span class="rojo">El curso registra '.$iDias.' d&iacute;as de mora en la entrega.</span>';
					}
				}
			$sRes=''.$sNegIni.''.$fila['ofer52consec'].' - '.fecha_desdenumero($fila['Fecha1']).$sFecha2.''.$sNegFin.'';
			}
		}
	if ($bCampo){
		$sRes=$sRes.'<input id="ofer08idcohorte" name="ofer08idcohorte" type="hidden" value="'.$idCohorte.'" />'.$sAlerta;
		}
	return $sRes;
	}
function f1707_HTMLComboV2_ofer08idcohorte($objDB, $objCombos, $valor, $vrofer08idper_aca, $sProceso){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	if ($vrofer08idper_aca==''){$vrofer08idper_aca='-99';}
	$sCondi='ofer52idperaca='.$vrofer08idper_aca.'';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('ofer08idcohorte', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	//ofer52acrefechaentregaescuela, ofer52acrefechaentregavimep, ofer52certfechaentregaescuela, ofer52certfechaentregavimep
	$sCampos='ofer52certfechaentregaescuela, " - ", ofer52certfechaentregavimep';
	if ($sProceso=='S'){$sCampos='ofer52acrefechaentregaescuela, " - ", ofer52acrefechaentregavimep';}
	$res=$objCombos->html('SELECT ofer52id AS id, CONCAT(ofer52consec, " - ", '.$sCampos.') AS nombre FROM ofer52cohortes'.$sCondi, $objDB);
	return $res;
	}
function html_combo_ofer08idcurso($objDB, $valor, $vrofer08idescuela, $iNumEscuelas){
	//@@ Es posible que deba ajustar las acciones del combo..
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_1707;
	if ((int)$vrofer08idescuela==0){$vrofer08idescuela='-999';}
	$scondi='unad40idescuela="'.$vrofer08idescuela.'"';
	if ($iNumEscuelas!=-1){
		//solo cursos donde tenga permisos
		$sIdCursos='-99';
		$sql='SELECT ofer11idcurso FROM ofer11actores WHERE ofer11per_aca='.$_SESSION['oai_per_aca'].' AND ofer11idtercero='.$_SESSION['unad_id_tercero'].' AND ofer11idcurso<>-1';
		$tabla=$objDB->ejecutasql($sql);
		while($fila=$objDB->sf($tabla)){
			$sIdCursos=$sIdCursos.','.$fila['ofer11idcurso'];
			}
		$scondi=$scondi.' AND unad40id IN ('.$sIdCursos.')';
		}
	$res=html_combo('ofer08idcurso', 'unad40id', 'CONCAT(unad40id," - ",unad40nombre)', 'unad40curso', $scondi, 'unad40nombre', $valor, $objDB, 'RevisaLlave();', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1707_Comboofer08idcohorte($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ofer08idcohorte=f1707_HTMLComboV2_ofer08idcohorte($objDB, $objCombos, '', $params[0], $params[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ofer08idcohorte', 'innerHTML', $html_ofer08idcohorte);
	return $objResponse;
	}
function html_combo_ofer08idagendaV2($valor, $idCurso, $idPeraca, $objDB){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('ofer08idagenda', 'ofer05id', 'CONCAT(ofer05nombre, " [", ofer05consec, "]")', 'ofer05agenda', 'ofer05idcurso='.$idCurso.' AND ofer05idperaca='.$idPeraca.'', 'ofer05idperaca DESC, ofer05idioma, ofer05nombre', $valor, $objDB, 'cambiaagenda()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1707_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$ofer08idper_aca=numeros_validar($datos[1]);
	if ($ofer08idper_aca==''){$bHayLlave=false;}
	$ofer08idcurso=numeros_validar($datos[2]);
	if ($ofer08idcurso==''){$bHayLlave=false;}
	$ofer08cead=numeros_validar($datos[3]);
	if ($ofer08cead==''){$bHayLlave=false;}
	$bCargaCurso=false;
	$idCurso=$datos[2];
	if (isset($datos[4])==0){$datos[4]=0;}
	if ($datos[4]==1){$bCargaCurso=true;}
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
	if ($bHayLlave){
	$sql='SELECT ofer08cead FROM ofer08oferta WHERE ofer08idper_aca='.$datos[1].' AND ofer08idcurso='.$datos[2].' AND ofer08cead='.$datos[3].'';
	$res=$objDB->ejecutasql($sql);
	if ($objDB->nf($res)!=0){
		$bCargaCurso=false;
		$objResponse=new xajaxResponse();
		$objResponse->call('cambiapaginaV2');
		return $objResponse;
		}
		}
		$objDB->CerrarConexion();
	if ($bCargaCurso){
		$sql='SELECT unad40incluyelaboratorio, unad40incluyesalida FROM unad40curso WHERE unad40id='.$idCurso.'';
		$tabla=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$objResponse=new xajaxResponse();
			$objResponse->assign('ofer08incluyelaboratorio', 'value', $fila['unad40incluyelaboratorio']);
			$objResponse->assign('ofer08incluyesalida', 'value', $fila['unad40incluyesalida']);
			return $objResponse;
			}
		}
	}
function f1707_Busquedas($params){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sCampo=$params[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$paramsb=array();
	$paramsb[101]=1;
	$paramsb[102]=20;
	switch($sCampo){
		case 'ofer08idcurso':
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
			$sTabla=f140_TablaDetalleBusquedas($paramsb, $objDB);
			$sTitulo=f140_TituloBusqueda();
			$sParams=f140_ParametrosBusqueda();
			$sJavaBusqueda=f140_JavaScriptBusqueda(1707);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 140, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'ofer08idusariorestaura':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer08usuarioconfirmaacceso':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer08idaprueba':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer08idacredita':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer08copiaidusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer08idresponsablepti':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer08tablacidusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer11idcurso':
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
			$sTabla=f140_TablaDetalleBusquedas($paramsb, $objDB);
			$sTitulo=f140_TituloBusqueda();
			$sParams=f140_ParametrosBusqueda();
			$sJavaBusqueda=f140_JavaScriptBusqueda(1707);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 140, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'ofer11idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer12idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer13idusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer13idatiende':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer30idactor':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer38idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		case 'ofer38grupo':
		$bExiste=true;
		if (file_exists('lib1741.php')){
			require 'lib1741.php';
			}else{
			$bExiste=false;
			}
		if ($bExiste){
			if (!function_exists('f1741_TablaDetalleBusquedas')){$bExiste=false;}
			}
		if ($bExiste){
			$sTabla=f1741_TablaDetalleBusquedas($paramsb, $objDB);
			$sTitulo=f1741_TituloBusqueda();
			$sParams=f1741_ParametrosBusqueda();
			$sJavaBusqueda=f1741_JavaScriptBusqueda(1707);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 1741, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'ofer38usuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1707);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_1707'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f1707_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($params[100]){
		case 'ofer08idcurso':
		if (file_exists('lib140.php')){
			require 'lib140.php';
			$sDetalle=f140_TablaDetalleBusquedas($params, $objDB);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib140, por favor informe al administrador del sistema.';
			}
		break;
		case 'ofer08idusariorestaura':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer08usuarioconfirmaacceso':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer08idaprueba':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer08idacredita':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer08copiaidusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer08idresponsablepti':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer08tablacidusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer11idcurso':
		if (file_exists('lib140.php')){
			require 'lib140.php';
			$sDetalle=f140_TablaDetalleBusquedas($params, $objDB);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib140, por favor informe al administrador del sistema.';
			}
		break;
		case 'ofer11idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer12idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer13idusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer13idatiende':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer30idactor':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer38idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'ofer38grupo':
		if (file_exists('lib1741.php')){
			require 'lib1741.php';
			$sDetalle=f1741_TablaDetalleBusquedas($params, $objDB);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib1741, por favor informe al administrador del sistema.';
			}
		break;
		case 'ofer38usuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1707_TituloBusqueda(){
	return 'Busqueda de Matricula manual';
	}
function f1707_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b1707nombre" name="b1707nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f1707_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b1707nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f1707_TablaDetalleBusquedas($params, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$sLeyenda='';
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f1707_InfoCurso($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$idCurso=$datos[2];
	if ($idCurso==''){$idCurso=-99;}
	$sql='SELECT unad40incluyelaboratorio, unad40incluyesalida FROM unad40curso WHERE unad40id='.$idCurso.'';
	$tabla=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$objResponse=new xajaxResponse();
		$objResponse->assign('ofer08incluyelaboratorio', 'value', $fila['unad40incluyelaboratorio']);
		$objResponse->assign('ofer08incluyesalida', 'value', $fila['unad40incluyesalida']);
		return $objResponse;
		}
	}
function Agenda_Actualizar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$babierta=true;
	$iTipoError=0;
	//OAI_ArmarAgendaV2($idperaca, $idcurso, $numaula, $bForzar, $objDB, $bControlaFechas=true, $bDebug=false)
	list($sError, $sDebug)=OAI_ArmarAgendaV2($valores[1], $valores[2], 1, false, $objDB, true, $bDebug);	
	if ($sError==''){
		$sError='<b>Se ha actualizado la agenda</b>';
		$iTipoError=1;
		}
	list($sDetalle, $sDebugT)=f1718_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugT;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1718detalle','innerHTML',$sDetalle);
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function Agenda_Cambia($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$idAgenda=$aParametros[2];
	$idAgendaPrevia='';
	$sSQL='SELECT ofer08idagenda, ofer08idper_aca, ofer08idcurso FROM ofer08oferta WHERE ofer08id='.$aParametros[1].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idPeraca=$fila['ofer08idper_aca'];
		$idCurso=$fila['ofer08idcurso'];
		if ($fila['ofer08idagenda']!=0){
			$idAgendaPrevia=$fila['ofer08idagenda'];
			}
		}else{
		$sError='No se ha encontrado un registro de oferta';
		}
	if ($idAgenda!=0){
		if ($sError==''){
			list($ofer05idtipoagenda, $iMomento1, $iMomento2, $iMomento3, $iPeso, $iPesoFin, $iPresenta1, $iPresenta2, $iPresenta3)=f1705_CalcularEstructura($idAgenda, $objDB, $bDebug);
			switch($ofer05idtipoagenda){
				case 1: //Actividades al 100
				$iPesoTotal=$iPeso+$iPesoFin;
				if ($iPesoTotal!=500){
					$sError='La sumatoria de peso evaluativo de las actividades de la agenda es incorrecta, por favor corrijala para continuar (Valor encontrado '.$iPesoTotal.', valor esperado 500)';
					}
				break;
				case 2:
				//No se consideran los pesos evaluativos, pero los momentos pueden venir en 0, asi que solo se voltean para que no fallen en las validaciones comunes.
				if ($iPresenta1!=0){if ($iMomento1==0){$iMomento1=1;}}
				if ($iPresenta2!=0){if ($iMomento2==0){$iMomento2=1;}}
				if ($iPresenta3!=0){if ($iMomento3==0){$iMomento3=1;}}
				break;
				default: //Agenda standar.
				if ($iPeso!=375){
					$sError='La sumatoria de peso evaluativo para actividades iniciales e intermedias de la agenda es incorrecta, por favor corrijala para continuar (Valor encontrado '.$iPeso.', valor esperado 375)';
					}
				if ($sError==''){
					if ($iPesoFin!=125){
						$sError='La sumatoria de peso evaluativo para actividades finales de la agenda es incorrecta, por favor corrijala para continuar (Valor encontrado '.$iPesoFin.', valor esperado 125)';
						}
					}
				break;
				}
			//En todas las agendas tienen que existir los 3 momentos.
			if ($sError==''){
				if ($iMomento1==0){
					$sError='En la agenda no se ha relacionado el momento estadistico Inicial';
					}
				if ($iMomento2==0){
					$sError='En la agenda no se ha relacionado el momento estadistico Intermedio';
					}
				if ($iMomento3==0){
					$sError='En la agenda no se ha relacionado el momento estadistico Final';
					}
				}
			}
		}
	if ($sError==''){
		$iTipoError=2;
		$sError='Se ha asignado la agenda, actualizando actividades...';
		if ($idAgenda==0){
			$sError='Se ha liberado la agenda, liberando la informaci&oacute;n de actividades.';
			}
		$sSQL='UPDATE ofer08oferta SET ofer08idagenda='.$idAgenda.' WHERE ofer08id='.$aParametros[1];
		$tabla=$objDB->ejecutasql($sSQL);
		$sBotones=html_botonesagenda($idAgenda);
		$sBotones2=html_botonesagenda2($idAgenda);
		$objResponse=new xajaxResponse();
		$objResponse->assign("div_botonesagenda","innerHTML",$sBotones);
		$objResponse->assign("div_botonesagenda2","innerHTML",$sBotones2);
		$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
		$objResponse->call("actualizaagenda");
		return $objResponse;
		}else{
		$objResponse=new xajaxResponse();
		$objResponse->assign('ofer08idagenda', 'value', $idAgendaPrevia);
		$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
		return $objResponse;
		}
	}
function f1707_ActoresPrograma($idOferta, $objDB, $iModo=0, $bDebug=false){
	require './app.php';
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_1707;
	$sRes='';
	$idGestor=0;
	$idDirector=0;
	$idDecano=0;
	$idSecretario=0;
	$idLider=0;
	$idPrograma=0;
	$idEscuela=0;
	$sDebug='';
	//Primero revisamos la oferta a ver si ya los tiene registrados (cuando se acreditan o se certifican se les asigna el decano.
	$sSQL='SELECT ofer08idescuela, ofer08idprograma, ofer08idrespvimep, ofer08iddirector, ofer08iddecano, ofer08idsecretarioacad, ofer08idliderprograma FROM ofer08oferta WHERE ofer08id='.$idOferta.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idEscuela=$fila['ofer08idescuela'];
		$idPrograma=$fila['ofer08idprograma'];
		$idDirector=$fila['ofer08iddirector'];
		$idGestor=$fila['ofer08idrespvimep'];
		$idDecano=$fila['ofer08iddecano'];
		$idSecretario=$fila['ofer08idsecretarioacad'];
		$idLider=$fila['ofer08idliderprograma'];
		}
	if ($idPrograma!=0){
		if ($idLider==0){
			$sSQL='SELECT core09iddirector FROM core09programa WHERE core09id='.$idPrograma.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$idLider=$fila['core09iddirector'];
				}
			}
		}
	if ($idEscuela!=0){
		if (($idDecano==0)||($idSecretario==0)){
			$sSQL='SELECT core12iddecano, core12idadministrador FROM core12escuela WHERE core12id='.$idEscuela.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if ($idDecano==0){$idDecano=$fila['core12iddecano'];}
				if ($idSecretario==0){$idSecretario=$fila['core12idadministrador'];}
				}
			}
		}
	$aActores=array(0,$idDecano,$idSecretario,$idLider,$idGestor,$idDirector);
	$aCampos=array('','ofer08iddecano', 'ofer08idsecretarioacad', 'ofer08idliderprograma','ofer08idrespvimep','ofer08iddirector');
	for ($k=1;$k<=5;$k++){
		if ($aActores[$k]!=0){
			$objTercero=new clsHtmlTercero($aActores[$k], $aCampos[$k], $ETI[$aCampos[$k]]);
			$objTercero->bSoloDatos=true;
			$objTercero->bConCorreo=true;
			$objTercero->Cargar($objDB);
			$sRes=$sRes.$objTercero->html();
			}
		}
	if ($sRes!=''){
		$sRes=$sRes.'<div class="salto1px"></div>';
		}
	return array($sRes, $idDecano, $idSecretario, $idLider, $sDebug);
	}
?>