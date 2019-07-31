<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 lunes, 23 de noviembre de 2015
--- Modelo Versión 2.22.3 miércoles, 15 de agosto de 2018
--- 106 Permisos por perfil
*/
function html_combo_unad06idmodulo($objDB, $idsistema, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$scondi='';
	if ($idsistema!=''){$scondi='unad02idsistema='.$idsistema;}
	$res=html_combo('bmodulo', 'unad02id', 'CONCAT(unad02nombre," {",unad02id,"}")', 'unad02modulos', $scondi, 'unad02nombre', $valor, $objDB, 'revisaf106()', true, '{'.$ETI['msg_todos'].'}', '');
	return utf8_encode($res);
	}
function html_combo_unad06idpermiso($objDB, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('bpermiso', 'unad03id', 'CONCAT(unad03nombre," {",unad03id,"}")', 'unad03permisos', '', 'unad03nombre', $valor, $objDB, 'revisaf106()', true, '{'.$ETI['msg_todos'].'}', '');
	return utf8_encode($res);
	}
function f106_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=106;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_106='lg/lg_106_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_106)){$mensajes_106='lg/lg_106_es.php';}
	require $mensajes_todas;
	require $mensajes_106;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$bdato_106=$valores[100];
	$unad06idperfil=numeros_validar($valores[1]);
	$unad06idmodulo=numeros_validar($valores[2]);
	$unad06idpermiso=numeros_validar($valores[3]);
	$unad06vigente=htmlspecialchars($valores[4]);
	if ($unad06vigente==''){$sError=$ERR['unad06vigente'];}
	if ($unad06idpermiso==''){$sError=$ERR['unad06idpermiso'];}
	if ($unad06idmodulo==''){$sError=$ERR['unad06idmodulo'];}
	if ($unad06idperfil==''){$sError=$ERR['unad06idperfil'];}
	if ($sError==''){
		if ((int)$bdato_106==0){
			$sql='SELECT unad06idperfil FROM unad06perfilmodpermiso WHERE unad06idperfil='.$unad06idperfil.' AND unad06idmodulo='.$unad06idmodulo.' AND unad06idpermiso='.$unad06idpermiso.'';
			$result=$objDB->ejecutasql($sql);
			if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
				}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente';
			$svalores=''.$unad06idperfil.', '.$unad06idmodulo.', '.$unad06idpermiso.', "'.$unad06vigente.'"';
			$sql='INSERT INTO unad06perfilmodpermiso ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objDB->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Permisos por perfil}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $CampoIdHijo, $sql, $objDB);
					}
				}
			}else{
			$scampo106[1]='unad06vigente';
			$svr106[1]=$unad06vigente;
			$inumcampos=1;
			$sWhere='unad06idperfil='.$unad06idperfil.' AND unad06idmodulo='.$unad06idmodulo.' AND unad06idpermiso='.$unad06idpermiso.'';
			$sql='SELECT * FROM unad06perfilmodpermiso WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sql);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo106[$k]]!=$svr106[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo106[$k].'="'.$svr106[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sql='UPDATE unad06perfilmodpermiso SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objDB->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Permisos por perfil}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $CampoIdHijo, $sql, $objDB);
						}
					}
				}
			}
		}
	return array($sError);
	}
function f106_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=106;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_106='lg/lg_106_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_106)){$mensajes_106='lg/lg_106_es.php';}
	require $mensajes_todas;
	require $mensajes_106;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$unad06idperfil=numeros_validar($aParametros[1]);
	$unad06idmodulo=numeros_validar($aParametros[2]);
	$unad06idpermiso=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='unad06idperfil='.$unad06idperfil.' AND unad06idmodulo='.$unad06idmodulo.' AND unad06idpermiso='.$unad06idpermiso.'';
		$sql='DELETE FROM unad06perfilmodpermiso WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {106 Permisos por perfil}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $CampoIdHijo, $sql, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f106_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_106='lg/lg_106_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_106)){$mensajes_106='lg/lg_106_es.php';}
	require $mensajes_todas;
	require $mensajes_106;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sDebug='';
	$unad05id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$sqladd='';
	//if (isset($aParametros[0])==0){$aParametros[0]='';}
	//if ((int)$aParametros[0]!=-1){$sqladd=$sqladd.' AND TB.campo='.$aParametros[0];}
	if ($aParametros[91]!=''){$sqladd=$sqladd.' AND T2.unad02idsistema='.$aParametros[91].'';}
	if ($aParametros[92]!=''){$sqladd=$sqladd.' AND T4.unad04idmodulo='.$aParametros[92].'';}
	if ($aParametros[93]!=''){$sqladd=$sqladd.' AND T4.unad04idpermiso='.$aParametros[93].'';}
	$sTitulos='Perfil, Modulo, Permiso, Vigente';
	$sql='SELECT T2.unad02idsistema, T1.unad01nombre, T4.unad04idmodulo, T2.unad02nombre, T4.unad04idpermiso, T3.unad03nombre 
FROM unad04modulopermisos AS T4, unad02modulos AS T2 LEFT JOIN unad01sistema AS T1 ON (T2.unad02idsistema=T1.unad01id), unad03permisos AS T3 
WHERE T4.unad04vigente="S" AND T4.unad04idmodulo=T2.unad02id AND T4.unad04idpermiso=T3.unad03id '.$sqladd.'
ORDER BY T2.unad02idsistema, T2.unad02nombre, T4.unad04idpermiso ';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_106" name="consulta_106" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_106" name="titulos_106" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf106" name="paginaf106" type="hidden" value="'.$pagina.'"/><input id="lppf106" name="lppf106" type="hidden" value="'.$lineastabla.'"/>');
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sql.$limite);
			}
		}
	$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><strong>'.$ETI['sistema'].'</strong></td>
<td><strong>'.$ETI['unad06idmodulo'].'</strong></td>
<td><strong>'.$ETI['unad06idpermiso'].'</strong></td>
<td align="right" colspan="2">
'.html_paginador('paginaf106', $registros, $lineastabla, $pagina, 'paginarf106()').'
'.html_lpp('lppf106', $lineastabla, 'paginarf106()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$spref='';
		$ssufi='';
		$sClass='';
		$sistema=$filadet['unad01nombre'];
		if ($filadet['unad02idsistema']==0){$sistema='{'.$ETI['msg_todos'].'}';}
		$link1='<a href="javascript:anexapermiso('.$filadet['unad04idmodulo'].",".$filadet['unad04idpermiso'].')" class="lnkresalte">'.$ETI['lnk_anexar'].'</a>';
		$link2='';
		$sql='SELECT unad06vigente FROM unad06perfilmodpermiso WHERE unad06idperfil='.$unad05id.' AND unad06idmodulo='.$filadet['unad04idmodulo'].' AND unad06idpermiso='.$filadet['unad04idpermiso'].' AND unad06vigente="S"';
		$result=$objDB->ejecutasql($sql);
		if ($objDB->nf($result)>0){
			$spref='<b>';
			$ssufi='</b>';
			$link1='';
			$link2='<a href="javascript:quitapermiso('.$filadet['unad04idmodulo'].",".$filadet['unad04idpermiso'].')" class="lnkresalte">'.$ETI['lnk_quitar'].'</a>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$res=$res.'<tr'.$sClass.'>
<td>'.$spref.$sistema.$ssufi.'</td>
<td>'.$spref.cadena_notildes($filadet['unad02nombre']).$ssufi.'</td>
<td>'.$spref.$filadet['unad03nombre'].$ssufi.'</td>
<td>'.$link1.'</td>
<td>'.$link2.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f106_Clonar($unad06idperfil, $unad06idperfilPadre, $objDB){
	$sError='';
	if ($sError==''){
		$sCampos106='unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente';
		$sValores106='';
		$sql='SELECT * FROM unad06perfilmodpermiso WHERE unad06idperfil='.$unad06idperfilPadre.'';
		$tabla=$objDB->ejecutasql($sql);
		while($fila=$objDB->sf($tabla)){
			if ($sValores106!=''){$sValores106=$sValores106.', ';}
			$sValores106=$sValores106.'('.$unad06idperfil.', '.$fila['unad06idmodulo'].', '.$fila['unad06idpermiso'].', "'.$fila['unad06vigente'].'")';
			}
		if ($sValores106!=''){
			$sql='INSERT INTO unad06perfilmodpermiso('.$sCampos106.') VALUES '.$sValores106.'';
			$result=$objDB->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 106 Permisos por perfil XAJAX 
function f106_Guardar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError)=f106_db_Guardar($valores, $objDB);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f106_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f106detalle', 'innerHTML', $sdetalle);
		$objResponse->call('limpiaf106');
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f106_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$unad06idperfil=numeros_validar($aParametros[1]);
		$unad06idmodulo=numeros_validar($aParametros[2]);
		$unad06idpermiso=numeros_validar($aParametros[3]);
		if (($unad06idperfil!='')&&($unad06idmodulo!='')&&($unad06idpermiso!='')){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'unad06idperfil='.$unad06idperfil.' AND unad06idmodulo='.$unad06idmodulo.' AND unad06idpermiso='.$unad06idpermiso.'';
			}
		$sql='SELECT * FROM unad06perfilmodpermiso WHERE '.$sqlcondi;
		$tabla=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla)>0){
			$row=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		list($unad06idmodulo_nombre, $sError_det)=tabla_campoxid('unad02modulos','unad02nombre','unad02id',$row['unad06idmodulo'],'{Sin dato}', $objDB);
		$html_unad06idmodulo=html_oculto('unad06idmodulo', $row['unad06idmodulo'], $unad06idmodulo_nombre);
		$objResponse->assign('div_unad06idmodulo', 'innerHTML', $html_unad06idmodulo);
		list($unad06idpermiso_nombre, $sError_det)=tabla_campoxid('unad03permisos','unad03nombre','unad03id',$row['unad06idpermiso'],'{Sin dato}', $objDB);
		$html_unad06idpermiso=html_oculto('unad06idpermiso', $row['unad06idpermiso'], $unad06idpermiso_nombre);
		$objResponse->assign('div_unad06idpermiso', 'innerHTML', $html_unad06idpermiso);
		$objResponse->assign('unad06vigente', 'value', $row['unad06vigente']);
		$objResponse->assign('bdato_106', 'value', 1);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina106','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unad06idmodulo', 'value', $unad06idmodulo);
			$objResponse->assign('unad06idpermiso', 'value', $unad06idpermiso);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$CampoIdHijo.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f106_Eliminar($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f106_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f106_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f106detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf106');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objDB->CerrarConexion();
	return $objResponse;
	}
function f106_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f106_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f106detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f106_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_unad06idmodulo=html_combo_unad06idmodulo($objDB, 0);
	$html_unad06idpermiso=html_combo_unad06idpermiso($objDB, 0);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad06idmodulo','innerHTML', $html_unad06idmodulo);
	$objResponse->assign('div_unad06idpermiso','innerHTML', $html_unad06idpermiso);
	$objResponse->assign('bdato_106', 'value', 0);
	return $objResponse;
	}
function pintar_combo_unad06idmodulo($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$idsistema=$aParametros[91];
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_unad06idmodulo=html_combo_unad06idmodulo($objDB, $idsistema, 0);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bmodulo','innerHTML', $html_unad06idmodulo);
	return $objResponse;
	}
?>