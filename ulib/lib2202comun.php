<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 martes, 5 de marzo de 2019
--- 2202 core01estprograma
*/
function f2202_HTMLComboV2_bprograma($objDB, $objCombos, $valor, $vrbescuela, $iVer=0){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('bprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='carga_combo_bversion()';
	if ($iVer==0){
		$objCombos->sAccion='paginarf2202()';
		}
	$sSQL='';
	if ($vrbescuela!=''){
		$sCondi='WHERE core09idescuela="'.$vrbescuela.'"';
		$sSQL='SELECT core09id AS id, CONCAT(core09nombre, " - ", core09codigo, CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre FROM core09programa '.$sCondi.' ORDER BY core09activo DESC, core09nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
	
function f2202_HTMLComboV2_programa($objDB, $objCombos, $valor, $vrbescuela, $iVer=0){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core16idprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='carga_combo_bversion()';
	if ($iVer==0){
		$objCombos->sAccion='paginarf2202()';
		}
	$sSQL='';
	if ($vrbescuela!=''){
		$sCondi='WHERE core09idescuela="'.$vrbescuela.'"';
		$sSQL='SELECT core09id AS id, CONCAT(core09nombre, " - ", core09codigo, CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre FROM core09programa '.$sCondi.' ORDER BY core09activo DESC, core09nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}	
function f2202_Combobprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[1])==0){$aParametros[1]=0;}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bprograma=f2202_HTMLComboV2_bprograma($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$html_bversion=f2202_HTMLComboV2_bversion($objDB, $objCombos, '', '');
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bprograma', 'innerHTML', $html_bprograma);
	$objResponse->assign('div_bversion', 'innerHTML', $html_bversion);
	$objResponse->call('paginarf2202');
	return $objResponse;
	}
function f2202_HTMLComboV2_bversion($objDB, $objCombos, $valor, $vrbprograma){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('bversion', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2202()';
	$objCombos->addItem('0', '{'.$ETI['msg_ninguno'].'}');
	$sSQL='';
	if ($vrbprograma!=''){
		$sCondi='WHERE core10idprograma="'.$vrbprograma.'" AND core10estado IN ("S", "X")';
		$sSQL='SELECT core10id AS id, CONCAT(core10consec, " - N&deg; Res ", core10numregcalificado) AS nombre FROM core10programaversion '.$sCondi.' ORDER BY core10consec DESC';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2202_Combobversion($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bversion=f2202_HTMLComboV2_bversion($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bversion', 'innerHTML', $html_bversion);
	$objResponse->call('paginarf2202');
	return $objResponse;
	}
function f2202_HTMLComboV2_bcead($objDB, $objCombos, $valor, $vrcara01idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrcara01idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('bcead', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2202()';
	$res=$objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi, $objDB);
	return $res;
	}
function f2202_Combobcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bcead=f2202_HTMLComboV2_bcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bcead', 'innerHTML', $html_bcead);
	$objResponse->call('paginarf2202');
	return $objResponse;
	}
	/*
	function f2202_HTMLComboV2_cead($objDB, $objCombos, $valor, $vrcara01idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrcara01idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('core16idcead', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2202()';
	$res=$objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi, $objDB);
	return $res;
	}
	*/	
?>