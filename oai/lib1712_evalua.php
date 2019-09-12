<?php
// -- 1712 Historico de oferta
function f1712_Guardar($valores, $params){
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
		list($sError)=f1712_db_Guardar($valores, $objDB);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1712_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1712detalle","innerHTML",$sdetalle);
		$objResponse->call("limpiaf1712");
		$objResponse->assign("alarma","innerHTML",'item guardado');
		}else{
		$objResponse->assign("alarma","innerHTML",$sError);
		}
	return $objResponse;
	}
function f1712_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$ofer12idoferta=numeros_validar($params[1]);
		$ofer12consec=numeros_validar($params[2]);
		if (($ofer12idoferta!='')&&($ofer12consec!='')){$besta=true;}
		}else{
		$ofer12id=$params[103];
		if ((int)$ofer12id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'ofer12idoferta='.$ofer12idoferta.' AND ofer12consec='.$ofer12consec.'';
			}else{
			$sqlcondi=$sqlcondi.'ofer12id='.$ofer12id.'';
			}
		$sql='SELECT * FROM ofer12ofertahistorico WHERE '.$sqlcondi;
		$tabla=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla)>0){
			$row=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$ofer12idtercero_id=(int)$row['ofer12idtercero'];
		$ofer12idtercero_td=$APP->tipo_doc;
		$ofer12idtercero_doc='';
		$ofer12idtercero_nombre='';
		if ($ofer12idtercero_id!=0){
			list($ofer12idtercero_id, $ofer12idtercero_td, $ofer12idtercero_doc, $ofer12idtercero_nombre)=tabla_terceros_traer($ofer12idtercero_id, $ofer12idtercero_td, $ofer12idtercero_doc, $objDB);
			}
		$ofer12consec_nombre='';
		$html_ofer12consec=html_oculto('ofer12consec', $row['ofer12consec'], $ofer12consec_nombre);
		$objResponse->assign('div_ofer12consec', 'innerHTML', $html_ofer12consec);
		$ofer12id_nombre='';
		$html_ofer12id=html_oculto('ofer12id', $row['ofer12id'], $ofer12id_nombre);
		$objResponse->assign('div_ofer12id', 'innerHTML', $html_ofer12id);
		$objResponse->assign('ofer12fechaoferta', 'value', $row['ofer12fechaoferta']);
		$objResponse->assign("ofer12fechaoferta_dia","value",substr($row['ofer12fechaoferta'],0,2));
		$objResponse->assign("ofer12fechaoferta_mes","value",substr($row['ofer12fechaoferta'],3,2));
		$objResponse->assign("ofer12fechaoferta_agno","value",substr($row['ofer12fechaoferta'],6,4));
		$objResponse->assign('ofer12fechacancela', 'value', $row['ofer12fechacancela']);
		$objResponse->assign("ofer12fechacancela_dia","value",substr($row['ofer12fechacancela'],0,2));
		$objResponse->assign("ofer12fechacancela_mes","value",substr($row['ofer12fechacancela'],3,2));
		$objResponse->assign("ofer12fechacancela_agno","value",substr($row['ofer12fechacancela'],6,4));
		$objResponse->assign('ofer12idtercero', 'value', $row['ofer12idtercero']);
		$objResponse->assign('ofer12idtercero_td', 'value', $ofer12idtercero_td);
		$objResponse->assign('ofer12idtercero_doc', 'value', $ofer12idtercero_doc);
		$objResponse->assign('div_ofer12idtercero', 'innerHTML', $ofer12idtercero_nombre);
		$objResponse->assign("alarma","innerHTML",'');
		$objResponse->call("verboton('belimina1712','block')");
		}else{
		if ($paso==1){
			$objResponse->assign("ofer12consec","value",$ofer12consec);
			}else{
			$objResponse->assign("alarma","innerHTML",'No se encontro el registro de referencia:'.$ofer12id);
			}
		}
	return $objResponse;
	}
function f1712_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sError=f1712_db_Eliminar($params, $objDB);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1712_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1712detalle","innerHTML",$sDetalle);
		$objResponse->call("limpiaf1712");
		$sError='Item eliminado';
		}
	$objResponse->assign("alarma","innerHTML",$sError);
	return $objResponse;
	}
function f1712_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle=f1712_TablaDetalle($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1712detalle","innerHTML",$sDetalle);
	return $objResponse;
	}
function f1712_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$html_ofer12consec='<input id="ofer12consec" name="ofer12consec" type="text" value="" onchange="revisaf1712()" class="cuatro"/>';
	$html_ofer12id='<input id="ofer12id" name="ofer12id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ofer12consec','innerHTML', $html_ofer12consec);
	$objResponse->assign('div_ofer12id','innerHTML', $html_ofer12id);
	return $objResponse;
	}
?>