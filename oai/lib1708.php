<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 jueves, 01 de octubre de 2015
--- Modelo Versión 2.12.1 domingo, 31 de enero de 2016
--- 1707 ofer08oferta
*/
function Cargar_ofer08idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$html_ofer08idcurso=html_combo_ofer08idcurso($objdb, '', $params[0], $params[1]);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_ofer08idcurso","innerHTML",$html_ofer08idcurso);
	return $objResponse;
	}
function html_combo_ofer08idescuela($objdb, $valor, $vr){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='';
	$res=html_combo('ofer08idescuela', 'exte01id', 'exte01nombre', 'exte01escuela', $scondi, 'exte01nombre', $valor, $objdb, 'carga_combo_ofer08idcurso()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function TraerBusqueda_db_ofer08idcurso($sCodigo, $objdb){
	$sRespuesta='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sql='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)!=0){
			$fila=$objdb->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.cadena_notildes($fila['unad40nombre']).'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta));
	}
function TraerBusqueda_ofer08idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$respuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($id, $respuesta)=TraerBusqueda_db_ofer08idcurso($scodigo, $objdb);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('RevisaLlave');
		}
	return $objResponse;
	}




?>