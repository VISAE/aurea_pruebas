<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Tuesday, August 27, 2019
--- 1941 Categorias
*/
function f1941_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1941;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1941='lg/lg_1941_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1941)){$mensajes_1941='lg/lg_1941_es.php';}
	require $mensajes_todas;
	require $mensajes_1941;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even41idtipoevento=numeros_validar($valores[1]);
	$even41consec=numeros_validar($valores[2]);
	$even41id=numeros_validar($valores[3], true);
	$even41activo=htmlspecialchars(trim($valores[4]));
	$even41titulo=htmlspecialchars(trim($valores[5]));
	$sSepara=', ';
	if ($even41titulo==''){$sError=$ERR['even41titulo'].$sSepara.$sError;}
	if ($even41activo==''){$sError=$ERR['even41activo'].$sSepara.$sError;}
	//if ($even41id==''){$sError=$ERR['even41id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($even41consec==''){$sError=$ERR['even41consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($even41idtipoevento==''){$sError=$ERR['even41idtipoevento'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$even41id==0){
			if ((int)$even41consec==0){
				$even41consec=tabla_consecutivo('even41categoria', 'even41consec', 'even41idtipoevento='.$even41idtipoevento.'', $objDB);
				if ($even41consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT even41idtipoevento FROM even41categoria WHERE even41idtipoevento='.$even41idtipoevento.' AND even41consec='.$even41consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even41id=tabla_consecutivo('even41categoria', 'even41id', '', $objDB);
				if ($even41id==-1){$sError=$objDB->serror;}
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
			$scampos='even41idtipoevento, even41consec, even41id, even41activo, even41titulo';
			$svalores=''.$even41idtipoevento.', '.$even41consec.', '.$even41id.', "'.$even41activo.'", "'.$even41titulo.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO even41categoria ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO even41categoria ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Categorias}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $even41id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1941[1]='even41activo';
			$scampo1941[2]='even41titulo';
			$svr1941[1]=$even41activo;
			$svr1941[2]=$even41titulo;
			$inumcampos=2;
			$sWhere='even41id='.$even41id.'';
			//$sWhere='even41idtipoevento='.$even41idtipoevento.' AND even41consec='.$even41consec.'';
			$sSQL='SELECT * FROM even41categoria WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1941[$k]]!=$svr1941[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1941[$k].'="'.$svr1941[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE even41categoria SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE even41categoria SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Categorias}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $even41id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even41id, $sDebug);
	}
function f1941_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1941;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1941='lg/lg_1941_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1941)){$mensajes_1941='lg/lg_1941_es.php';}
	require $mensajes_todas;
	require $mensajes_1941;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$even41idtipoevento=numeros_validar($aParametros[1]);
	$even41consec=numeros_validar($aParametros[2]);
	$even41id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1941';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$even41id.' LIMIT 0, 1';
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
		$sWhere='even41id='.$even41id.'';
		//$sWhere='even41idtipoevento='.$even41idtipoevento.' AND even41consec='.$even41consec.'';
		$sSQL='DELETE FROM even41categoria WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1941 Categorias}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $even41id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1941_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1941='lg/lg_1941_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1941)){$mensajes_1941='lg/lg_1941_es.php';}
	require $mensajes_todas;
	require $mensajes_1941;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$even01id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM even01tipoevento WHERE ='.$even01id;
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
	$sTitulos='Tipoevento, Consec, Id, Activo, Titulo';
	$sSQL='SELECT TB.even41idtipoevento, TB.even41consec, TB.even41id, TB.even41activo, TB.even41titulo 
FROM even41categoria AS TB 
WHERE '.$sSQLadd1.' TB.even41idtipoevento='.$even01id.' '.$sSQLadd.'
ORDER BY TB.even41consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1941" name="consulta_1941" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1941" name="titulos_1941" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1941: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1941" name="paginaf1941" type="hidden" value="'.$pagina.'"/><input id="lppf1941" name="lppf1941" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['even41consec'].'</b></td>
<td><b>'.$ETI['even41activo'].'</b></td>
<td><b>'.$ETI['even41titulo'].'</b></td>
<td align="right">
'.html_paginador('paginaf1941', $registros, $lineastabla, $pagina, 'paginarf1941()').'
'.html_lpp('lppf1941', $lineastabla, 'paginarf1941()').'
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
		$et_even41consec=$sPrefijo.$filadet['even41consec'].$sSufijo;
		$et_even41activo=$ETI['no'];
		if ($filadet['even41activo']=='S'){$et_even41activo=$ETI['si'];}
		$et_even41titulo=$sPrefijo.cadena_notildes($filadet['even41titulo']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1941('.$filadet['even41id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_even41consec.'</td>
<td>'.$et_even41activo.'</td>
<td>'.$et_even41titulo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1941_Clonar($even41idtipoevento, $even41idtipoeventoPadre, $objDB){
	$sError='';
	$even41consec=tabla_consecutivo('even41categoria', 'even41consec', 'even41idtipoevento='.$even41idtipoevento.'', $objDB);
	if ($even41consec==-1){$sError=$objDB->serror;}
	$even41id=tabla_consecutivo('even41categoria', 'even41id', '', $objDB);
	if ($even41id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1941='even41idtipoevento, even41consec, even41id, even41activo, even41titulo';
		$sValores1941='';
		$sSQL='SELECT * FROM even41categoria WHERE even41idtipoevento='.$even41idtipoeventoPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1941!=''){$sValores1941=$sValores1941.', ';}
			$sValores1941=$sValores1941.'('.$even41idtipoevento.', '.$even41consec.', '.$even41id.', "'.$fila['even41activo'].'", "'.$fila['even41titulo'].'")';
			$even41consec++;
			$even41id++;
			}
		if ($sValores1941!=''){
			$sSQL='INSERT INTO even41categoria('.$sCampos1941.') VALUES '.$sValores1941.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1941 Categorias XAJAX 
function f1941_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $even41id, $sDebugGuardar)=f1941_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1941_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1941detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1941('.$even41id.')');
			//}else{
			$objResponse->call('limpiaf1941');
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
function f1941_Traer($aParametros){
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
		$even41idtipoevento=numeros_validar($aParametros[1]);
		$even41consec=numeros_validar($aParametros[2]);
		if (($even41idtipoevento!='')&&($even41consec!='')){$besta=true;}
		}else{
		$even41id=$aParametros[103];
		if ((int)$even41id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'even41idtipoevento='.$even41idtipoevento.' AND even41consec='.$even41consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'even41id='.$even41id.'';
			}
		$sSQL='SELECT * FROM even41categoria WHERE '.$sSQLcondi;
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
		$even41consec_nombre='';
		$html_even41consec=html_oculto('even41consec', $fila['even41consec'], $even41consec_nombre);
		$objResponse->assign('div_even41consec', 'innerHTML', $html_even41consec);
		$even41id_nombre='';
		$html_even41id=html_oculto('even41id', $fila['even41id'], $even41id_nombre);
		$objResponse->assign('div_even41id', 'innerHTML', $html_even41id);
		$objResponse->assign('even41activo', 'value', $fila['even41activo']);
		$objResponse->assign('even41titulo', 'value', $fila['even41titulo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1941','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even41consec', 'value', $even41consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even41id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1941_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1941_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1941_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1941detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1941');
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
function f1941_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1941_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1941detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1941_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_even41consec='<input id="even41consec" name="even41consec" type="text" value="" onchange="revisaf1941()" class="cuatro"/>';
	$html_even41id='<input id="even41id" name="even41id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even41consec','innerHTML', $html_even41consec);
	$objResponse->assign('div_even41id','innerHTML', $html_even41id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>