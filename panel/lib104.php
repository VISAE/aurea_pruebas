<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.6.2 lunes, 09 de marzo de 2015
--- Modelo Versión 2.22.6b miércoles, 5 de diciembre de 2018
--- 104 Permiso por modulo
*/
function html_combo_unad04idpermiso($objdb, $valor){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('unad04idpermiso', 'unad03id', 'CONCAT(unad03nombre, " [", unad03id, "]")', 'unad03permisos', '', 'unad03id', $valor, $objdb, 'revisaf104()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f104_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=104;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_104='lg/lg_104_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_104)){$mensajes_104='lg/lg_104_es.php';}
	require $mensajes_todas;
	require $mensajes_104;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$unad04idmodulo=numeros_validar($valores[1]);
	$unad04idpermiso=numeros_validar($valores[2]);
	$unad04vigente=htmlspecialchars(trim($valores[3]));
	$sSepara=', ';
	if ($unad04vigente==''){$sError=$ERR['unad04vigente'].$sSepara.$sError;}
	if ($unad04idpermiso==''){$sError=$ERR['unad04idpermiso'].$sSepara.$sError;}
	if ($unad04idmodulo==''){$sError=$ERR['unad04idmodulo'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$unad04idpermiso!=0){
			if ($sError==''){
				$sSQL='SELECT unad04idmodulo FROM unad04modulopermisos WHERE unad04idmodulo='.$unad04idmodulo.' AND unad04idpermiso='.$unad04idpermiso.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					//$sError=$ERR['existe'];
					if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
					}else{
					$binserta=true;
					$iAccion=2;
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			$sError='No se ha seleccionado un permiso a asignar.';
			}
		}
	if ($sError==''){
		if ($binserta){
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='unad04idmodulo, unad04idpermiso, unad04vigente';
			$svalores=''.$unad04idmodulo.', '.$unad04idpermiso.', "'.$unad04vigente.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unad04modulopermisos ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO unad04modulopermisos ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Permiso por modulo}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, 0, $sSQL, $objDB);
					}
				}
			}else{
			$scampo104[1]='unad04vigente';
			$svr104[1]=$unad04vigente;
			$inumcampos=1;
			$sWhere='unad04idmodulo='.$unad04idmodulo.' AND unad04idpermiso='.$unad04idpermiso.'';
			$sSQL='SELECT * FROM unad04modulopermisos WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo104[$k]]!=$svr104[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo104[$k].'="'.$svr104[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE unad04modulopermisos SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE unad04modulopermisos SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Permiso por modulo}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, 0, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, 0, $sDebug);
	}
function f104_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=104;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_104='lg/lg_104_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_104)){$mensajes_104='lg/lg_104_es.php';}
	require $mensajes_todas;
	require $mensajes_104;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$unad04idmodulo=numeros_validar($aParametros[1]);
	$unad04idpermiso=numeros_validar($aParametros[2]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='unad04idmodulo='.$unad04idmodulo.' AND unad04idpermiso='.$unad04idpermiso.'';
		$sSQL='DELETE FROM unad04modulopermisos WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {104 Permiso por modulo}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, 0, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f104_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_104='lg/lg_104_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_104)){$mensajes_104='lg/lg_104_es.php';}
	require $mensajes_todas;
	require $mensajes_104;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$unad02id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM unad02modulos WHERE ='.$unad02id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Modulo, Permiso, Vigente';
	$sSQL='SELECT TB.unad04idmodulo, T2.unad03nombre, TB.unad04vigente, TB.unad04idpermiso 
FROM unad04modulopermisos AS TB, unad03permisos AS T2 
WHERE '.$sSQLadd1.' TB.unad04idmodulo='.$unad02id.' AND TB.unad04idpermiso=T2.unad03id '.$sSQLadd.'
ORDER BY TB.unad04idpermiso';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_104" name="consulta_104" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_104" name="titulos_104" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 104: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf104" name="paginaf104" type="hidden" value="'.$pagina.'"/><input id="lppf104" name="lppf104" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unad04idpermiso'].'</b></td>
<td><b>'.$ETI['unad04vigente'].'</b></td>
<td align="right">
'.html_paginador('paginaf104', $registros, $lineastabla, $pagina, 'paginarf104()').'
'.html_lpp('lppf104', $lineastabla, 'paginarf104()').'
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
		$et_unad04vigente=$ETI['no'];
		if ($filadet['unad04vigente']=='S'){$et_unad04vigente=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf104('.$filadet['unad04idpermiso'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['unad03nombre']).' {'.$filadet['unad04idpermiso'].'}'.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad04vigente.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f104_Clonar($unad04idmodulo, $unad04idmoduloPadre, $objDB){
	$sError='';
	if ($sError==''){
		$sCampos104='unad04idmodulo, unad04idpermiso, unad04vigente';
		$sValores104='';
		$sSQL='SELECT * FROM unad04modulopermisos WHERE unad04idmodulo='.$unad04idmoduloPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores104!=''){$sValores104=$sValores104.', ';}
			$sValores104=$sValores104.'('.$unad04idmodulo.', '.$fila['unad04idpermiso'].', "'.$fila['unad04vigente'].'")';
			}
		if ($sValores104!=''){
			$sSQL='INSERT INTO unad04modulopermisos('.$sCampos104.') VALUES '.$sValores104.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 104 Permiso por modulo XAJAX 
function f104_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $CampoIdHijo, $sDebugGuardar)=f104_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f104_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f104detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf104('.$CampoIdHijo.')');
			//}else{
			$objResponse->call('limpiaf104');
			//}
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
function f104_Traer($aParametros){
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
		$unad04idmodulo=numeros_validar($aParametros[1]);
		$unad04idpermiso=numeros_validar($aParametros[2]);
		if (($unad04idmodulo!='')&&($unad04idpermiso!='')){$besta=true;}
		}else{
		$CampoIdHijo=$aParametros[103];
		if ((int)$CampoIdHijo!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'unad04idmodulo='.$unad04idmodulo.' AND unad04idpermiso='.$unad04idpermiso.'';
			}else{
			$sSQLcondi=$sSQLcondi.'CampoIdHijo='.$CampoIdHijo.'';
			}
		$sSQL='SELECT * FROM unad04modulopermisos WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		if (isset($APP->piel)==0){$APP->piel=1;}
		$iPiel=$APP->piel;
		list($unad04idpermiso_nombre, $serror_det)=tabla_campoxid('unad03permisos','unad03nombre','unad03id', $fila['unad04idpermiso'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_unad04idpermiso=html_oculto('unad04idpermiso', $fila['unad04idpermiso'], $unad04idpermiso_nombre);
		$objResponse->assign('div_unad04idpermiso', 'innerHTML', $html_unad04idpermiso);
		$objResponse->assign('unad04vigente', 'value', $fila['unad04vigente']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina104','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unad04idpermiso', 'value', $unad04idpermiso);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f104_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f104_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f104_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f104detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf104');
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
function f104_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f104_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f104detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f104_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_unad04idpermiso=html_combo_unad04idpermiso($objDB, 0);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad04idpermiso','innerHTML', $html_unad04idpermiso);
	return $objResponse;
	}
?>