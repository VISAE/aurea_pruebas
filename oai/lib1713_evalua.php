<?php
// -- 1713 Anotaciones
function f1713_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($sError)=f1713_db_Guardar($valores, $objDB);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1713_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1713detalle","innerHTML",$sdetalle);
		$objResponse->call("limpiaf1713");
		$objResponse->assign("alarma","innerHTML",'item guardado');
		}else{
		$objResponse->assign("alarma","innerHTML",$sError);
		}
	return $objResponse;
	}
function f1713_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$ofer13idoferta=numeros_validar($params[1]);
		$ofer13consec=numeros_validar($params[2]);
		if (($ofer13idoferta!='')&&($ofer13consec!='')){$besta=true;}
		}else{
		$ofer13id=$params[103];
		if ((int)$ofer13id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'ofer13idoferta='.$ofer13idoferta.' AND ofer13consec='.$ofer13consec.'';
			}else{
			$sqlcondi=$sqlcondi.'ofer13id='.$ofer13id.'';
			}
		$sql='SELECT * FROM ofer13ofertaanotacion WHERE '.$sqlcondi;
		$tabla=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla)>0){
			$row=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
		if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
		require $mensajes_todas;
		$ofer13idusuario_id=(int)$row['ofer13idusuario'];
		$ofer13idusuario_td=$APP->tipo_doc;
		$ofer13idusuario_doc='';
		$ofer13idusuario_nombre='';
		if ($ofer13idusuario_id!=0){
			list($ofer13idusuario_id, $ofer13idusuario_td, $ofer13idusuario_doc, $ofer13idusuario_nombre)=tabla_terceros_traer($ofer13idusuario_id, $ofer13idusuario_td, $ofer13idusuario_doc, $objDB);
			}
		$ofer13consec_nombre='';
		$html_ofer13consec=html_oculto('ofer13consec', $row['ofer13consec'], $ofer13consec_nombre);
		$objResponse->assign('div_ofer13consec', 'innerHTML', $html_ofer13consec);
		$ofer13id_nombre='';
		$html_ofer13id=html_oculto('ofer13id', $row['ofer13id'], $ofer13id_nombre);
		$html_ofer13fecha=html_oculto('ofer13fecha', $row['ofer13fecha']);
		$html_ofer13hora=html_oculto('ofer13hora', $row['ofer13hora'], formato_hora($row['ofer13hora']));
		$html_ofer13minuto=html_oculto('ofer13minuto', $row['ofer13minuto'], formato_hora($row['ofer13minuto']));
		$ofer13idusuario_td=html_oculto('ofer13idusuario_td',$ofer13idusuario_td).html_oculto('ofer13idusuario_doc',$ofer13idusuario_doc);
		$objResponse->assign('div_ofer13id', 'innerHTML', $html_ofer13id);
		$objResponse->assign('div_ofer13fecha', 'innerHTML', $html_ofer13fecha);
		$objResponse->assign('div_ofer13hora', 'innerHTML', $html_ofer13hora);
		$objResponse->assign('div_ofer13minuto', 'innerHTML', $html_ofer13minuto);
		$objResponse->assign('ofer13idusuario', 'value', $row['ofer13idusuario']);
		$objResponse->assign('ofer13idusuario_td', 'innerHTML', $ofer13idusuario_td);
		$objResponse->assign('div_ofer13idusuario', 'innerHTML', $ofer13idusuario_nombre);
		$objResponse->assign('ofer13anotacion', 'value', $row['ofer13anotacion']);
		$objResponse->assign("alarma","innerHTML",'');
		$objResponse->call("verboton('belimina1713','block')");
		}else{
		if ($paso==1){
			$objResponse->assign("ofer13consec","value",$ofer13consec);
			}else{
			$objResponse->assign("alarma","innerHTML",'No se encontro el registro de referencia:'.$ofer13id);
			}
		}
	return $objResponse;
	}
function f1713_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sError=f1713_db_Eliminar($params, $objDB);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1713_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1713detalle","innerHTML",$sDetalle);
		$objResponse->call("limpiaf1713");
		$sError='Item eliminado';
		}
	$objResponse->assign("alarma","innerHTML",$sError);
	return $objResponse;
	}
function f1713_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle=f1713_TablaDetalle($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1713detalle","innerHTML",$sDetalle);
	return $objResponse;
	}
function f1713_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_ofer13consec='<input id="ofer13consec" name="ofer13consec" type="text" value="" onchange="revisaf1713()" class="cuatro"/>';
	$html_ofer13id='<input id="ofer13id" name="ofer13id" type="hidden" value=""/>';
	$html_ofer13fecha=html_oculto('ofer13fecha', fecha_hoy());
	$hora=fecha_hora();
	$minuto=fecha_minuto();
	$html_ofer13hora=html_oculto('ofer13hora', $hora, formato_hora($hora));
	$html_ofer13minuto=html_oculto('ofer13minuto', $minuto, formato_hora($minuto));
	$ofer13idusuario_rs='';
	list($ofer13idusuario,$ofer13idusuario_td,$ofer13idusuario_doc,$ofer13idusuario_rs)=tabla_terceros_traer($_SESSION['unad_id_tercero'],'','', $objDB);
	$ofer13idusuario_td=html_oculto('ofer13idusuario_td', $ofer13idusuario_td).html_oculto('ofer13idusuario_doc', $ofer13idusuario_doc);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ofer13consec','innerHTML', $html_ofer13consec);
	$objResponse->assign('div_ofer13id','innerHTML', $html_ofer13id);
	$objResponse->assign('div_ofer13fecha','innerHTML', $html_ofer13fecha);
	$objResponse->assign('div_ofer13hora','innerHTML', $html_ofer13hora);
	$objResponse->assign('div_ofer13minuto','innerHTML', $html_ofer13minuto);
	$objResponse->assign('ofer13idusuario','value',$ofer13idusuario);
	$objResponse->assign('div_ofer13idusuario_td','innerHTML', $ofer13idusuario_td);
	$objResponse->assign('div_ofer13idusuario','innerHTML', $ofer13idusuario_rs);
	return $objResponse;
	}
?>
