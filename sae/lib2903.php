<?php
/*
--- © Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - UNAD - 2019 ---
--- samicial@puntosoftware.net - http://www.puntosoftware.net 
// --- Desarrollo por encargo para la UNAD Contrato OS-2019-000130 
// --- Conforme a la metodología de desarrollo de la plataforma AUREA.
--- Modelo Versión 2.23.7 Friday, October 18, 2019
--- 2903 rango salarial
*/
function f2903_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2903;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2903='lg/lg_2903_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2903)){$mensajes_2903='lg/lg_2903_es.php';}
	require $mensajes_todas;
	require $mensajes_2903;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$plab03idempresa=numeros_validar($valores[1]);
	$plab03consecutivo=numeros_validar($valores[2]);
	$plab03id=numeros_validar($valores[3], true);
	$plab03activo=htmlspecialchars(trim($valores[4]));
	$plab03nombre=htmlspecialchars(trim($valores[5]));
	$sSepara=', ';
	if ($plab03nombre==''){$sError=$ERR['plab03nombre'].$sSepara.$sError;}
	if ($plab03activo==''){$sError=$ERR['plab03activo'].$sSepara.$sError;}
	//if ($plab03id==''){$sError=$ERR['plab03id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($plab03consecutivo==''){$sError=$ERR['plab03consecutivo'].$sSepara.$sError;}//CONSECUTIVO
	if ($plab03idempresa==''){$sError=$ERR['plab03idempresa'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$plab03id==0){
			if ((int)$plab03consecutivo==0){
				$plab03consecutivo=tabla_consecutivo('plab03rangsala', 'plab03consecutivo', 'plab03idempresa='.$plab03idempresa.'', $objDB);
				if ($plab03consecutivo==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT plab03idempresa FROM plab03rangsala WHERE plab03idempresa='.$plab03idempresa.' AND plab03consecutivo='.$plab03consecutivo.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$plab03id=tabla_consecutivo('plab03rangsala', 'plab03id', '', $objDB);
				if ($plab03id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='plab03idempresa, plab03consecutivo, plab03id, plab03activo, plab03nombre';
			$svalores=''.$plab03idempresa.', '.$plab03consecutivo.', '.$plab03id.', "'.$plab03activo.'", "'.$plab03nombre.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab03rangsala ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO plab03rangsala ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {rango salarial}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $plab03id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2903[1]='plab03activo';
			$scampo2903[2]='plab03nombre';
			$svr2903[1]=$plab03activo;
			$svr2903[2]=$plab03nombre;
			$inumcampos=2;
			$sWhere='plab03id='.$plab03id.'';
			//$sWhere='plab03idempresa='.$plab03idempresa.' AND plab03consecutivo='.$plab03consecutivo.'';
			$sSQL='SELECT * FROM plab03rangsala WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2903[$k]]!=$svr2903[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2903[$k].'="'.$svr2903[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE plab03rangsala SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE plab03rangsala SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {rango salarial}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $plab03id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $plab03id, $sDebug);
	}
function f2903_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2903;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2903='lg/lg_2903_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2903)){$mensajes_2903='lg/lg_2903_es.php';}
	require $mensajes_todas;
	require $mensajes_2903;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$plab03idempresa=numeros_validar($aParametros[1]);
	$plab03consecutivo=numeros_validar($aParametros[2]);
	$plab03id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2903';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$plab03id.' LIMIT 0, 1';
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
		//acciones previas
		$sWhere='plab03id='.$plab03id.'';
		//$sWhere='plab03idempresa='.$plab03idempresa.' AND plab03consecutivo='.$plab03consecutivo.'';
		$sSQL='DELETE FROM plab03rangsala WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2903 rango salarial}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab03id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2903_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2903='lg/lg_2903_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2903)){$mensajes_2903='lg/lg_2903_es.php';}
	require $mensajes_todas;
	require $mensajes_2903;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$plab09id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM plab09empresa WHERE plab09id='.$plab09id;
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
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Empresa, Consecutivo, Id, Activo, Nombre';
	$sSQL='SELECT TB.plab03idempresa, TB.plab03consecutivo, TB.plab03id, TB.plab03activo, TB.plab03nombre 
FROM plab03rangsala AS TB 
WHERE '.$sSQLadd1.' TB.plab03idempresa='.$plab09id.' '.$sSQLadd.'
ORDER BY TB.plab03consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2903" name="consulta_2903" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2903" name="titulos_2903" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2903: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2903" name="paginaf2903" type="hidden" value="'.$pagina.'"/><input id="lppf2903" name="lppf2903" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab03consecutivo'].'</b></td>
<td><b>'.$ETI['plab03activo'].'</b></td>
<td><b>'.$ETI['plab03nombre'].'</b></td>
<td align="right">
'.html_paginador('paginaf2903', $registros, $lineastabla, $pagina, 'paginarf2903()').'
'.html_lpp('lppf2903', $lineastabla, 'paginarf2903()').'
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
		$et_plab03consecutivo=$sPrefijo.$filadet['plab03consecutivo'].$sSufijo;
		$et_plab03activo=$ETI['no'];
		if ($filadet['plab03activo']=='S'){$et_plab03activo=$ETI['si'];}
		$et_plab03nombre=$sPrefijo.cadena_notildes($filadet['plab03nombre']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2903('.$filadet['plab03id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_plab03consecutivo.'</td>
<td>'.$et_plab03activo.'</td>
<td>'.$et_plab03nombre.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2903_Clonar($plab03idempresa, $plab03idempresaPadre, $objDB){
	$sError='';
	$plab03consecutivo=tabla_consecutivo('plab03rangsala', 'plab03consecutivo', 'plab03idempresa='.$plab03idempresa.'', $objDB);
	if ($plab03consecutivo==-1){$sError=$objDB->serror;}
	$plab03id=tabla_consecutivo('plab03rangsala', 'plab03id', '', $objDB);
	if ($plab03id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2903='plab03idempresa, plab03consecutivo, plab03id, plab03activo, plab03nombre';
		$sValores2903='';
		$sSQL='SELECT * FROM plab03rangsala WHERE plab03idempresa='.$plab03idempresaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2903!=''){$sValores2903=$sValores2903.', ';}
			$sValores2903=$sValores2903.'('.$plab03idempresa.', '.$plab03consecutivo.', '.$plab03id.', "'.$fila['plab03activo'].'", "'.$fila['plab03nombre'].'")';
			$plab03consecutivo++;
			$plab03id++;
			}
		if ($sValores2903!=''){
			$sSQL='INSERT INTO plab03rangsala('.$sCampos2903.') VALUES '.$sValores2903.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2903 rango salarial XAJAX 
function f2903_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $plab03id, $sDebugGuardar)=f2903_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2903_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2903detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2903('.$plab03id.')');
			//}else{
			$objResponse->call('limpiaf2903');
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
function f2903_Traer($aParametros){
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
		$plab03idempresa=numeros_validar($aParametros[1]);
		$plab03consecutivo=numeros_validar($aParametros[2]);
		if (($plab03idempresa!='')&&($plab03consecutivo!='')){$besta=true;}
		}else{
		$plab03id=$aParametros[103];
		if ((int)$plab03id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'plab03idempresa='.$plab03idempresa.' AND plab03consecutivo='.$plab03consecutivo.'';
			}else{
			$sSQLcondi=$sSQLcondi.'plab03id='.$plab03id.'';
			}
		$sSQL='SELECT * FROM plab03rangsala WHERE '.$sSQLcondi;
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
		$plab03consecutivo_nombre='';
		$html_plab03consecutivo=html_oculto('plab03consecutivo', $fila['plab03consecutivo'], $plab03consecutivo_nombre);
		$objResponse->assign('div_plab03consecutivo', 'innerHTML', $html_plab03consecutivo);
		$plab03id_nombre='';
		$html_plab03id=html_oculto('plab03id', $fila['plab03id'], $plab03id_nombre);
		$objResponse->assign('div_plab03id', 'innerHTML', $html_plab03id);
		$objResponse->assign('plab03activo', 'value', $fila['plab03activo']);
		$objResponse->assign('plab03nombre', 'value', $fila['plab03nombre']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2903','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('plab03consecutivo', 'value', $plab03consecutivo);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$plab03id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2903_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2903_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2903_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2903detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2903');
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
function f2903_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2903_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2903detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2903_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_plab03consecutivo='<input id="plab03consecutivo" name="plab03consecutivo" type="text" value="" onchange="revisaf2903()" class="cuatro"/>';
	$html_plab03id='<input id="plab03id" name="plab03id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab03consecutivo','innerHTML', $html_plab03consecutivo);
	$objResponse->assign('div_plab03id','innerHTML', $html_plab03id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>