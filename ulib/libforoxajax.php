<?php
function foro_actualizar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_id_tercero'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$idProceso=$params[1];
	$idRef=$params[2];
	$sComentario=htmlspecialchars($params[4]);
	$idUsuario=$params[5];
	if ($sComentario!=$params[4]){
		$sHTMLForo='Esta ejecutando una acci&oacute;n prohibida, se ha informado al administrador del sistema.';
		}else{
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$objForo=new clsForo($idProceso, $idRef, true);
		list($sHTMLForo)=$objForo->html($idUsuario, $objdb);
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_foro'.$idProceso, 'innerHTML', $sHTMLForo);
	return $objResponse;
	}
function foro_comentar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_id_tercero'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$idProceso=$params[1];
	$idRef=$params[2];
	$idPadre=$params[3];
	$sComentario=htmlspecialchars($params[4]);
	$idUsuario=$params[5];
	if ($sComentario!=$params[4]){
		$sHTMLForo='Esta ejecutando una acci&oacute;n prohibida, se ha informado al administrador del sistema.';
		}else{
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$unad80consec=tabla_consecutivo('unad80foro', 'unad80consec', 'unad80idpadre='.$idPadre.' AND unad80idref='.$idRef.' AND unad80idproceso='.$idProceso.'', $objdb);
		$unad80id=tabla_consecutivo('unad80foro','unad80id', '', $objdb);
		$unad80mensaje=str_replace('&quot;', '\"', $sComentario);
		$unad80ifecha=fecha_EnNumero(fecha_hoy());
		$unad80hora=fecha_hora();
		$unad80minuto=fecha_minuto();
		$sCampos1580='unad80idproceso, unad80idref, unad80idpadre, unad80consec, unad80id, unad80mensaje, unad80ifecha, unad80hora, unad80minuto, unad80usuario';
		$sValores1580=''.$idProceso.', '.$idRef.', '.$idPadre.', '.$unad80consec.', '.$unad80id.', "'.$unad80mensaje.'", '.$unad80ifecha.', '.$unad80hora.', '.$unad80minuto.', '.$idUsuario.'';
		if ($APP->utf8==1){
			$sql='INSERT INTO unad80foro ('.$sCampos1580.') VALUES ('.utf8_encode($sValores1580).');';
			//$sdetalle=$sCampos1580.'['.utf8_encode($sValores1580).']';
			}else{
			$sql='INSERT INTO unad80foro ('.$sCampos1580.') VALUES ('.$sValores1580.');';
			//$sdetalle=$sCampos1580.'['.$sValores1580.']';
			}
		$result=$objdb->ejecutasql($sql);
		$objForo=new clsForo($idProceso, $idRef, true);
		list($sHTMLForo)=$objForo->html($idUsuario, $objdb);
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_foro'.$idProceso, 'innerHTML', $sHTMLForo);
	return $objResponse;
	}
$xajax->register(XAJAX_FUNCTION,'foro_actualizar');
$xajax->register(XAJAX_FUNCTION,'foro_comentar');
?>