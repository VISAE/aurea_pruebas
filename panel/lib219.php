<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Wednesday, August 14, 2019
--- 219 Rangos
*/
function f219_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=219;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_219='lg/lg_219_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_219)){$mensajes_219='lg/lg_219_es.php';}
	require $mensajes_todas;
	require $mensajes_219;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$unae19idrangoedad=numeros_validar($valores[1]);
	$unae19consec=numeros_validar($valores[2]);
	$unae19id=numeros_validar($valores[3], true);
	$unae19titulo=htmlspecialchars(trim($valores[4]));
	$unae19base=numeros_validar($valores[5]);
	$unae19techo=numeros_validar($valores[6]);
	//if ($unae19base==''){$unae19base=0;}
	//if ($unae19techo==''){$unae19techo=0;}
	$sSepara=', ';
	if ($unae19techo==''){$sError=$ERR['unae19techo'].$sSepara.$sError;}
	if ($unae19base==''){$sError=$ERR['unae19base'].$sSepara.$sError;}
	if ($unae19titulo==''){$sError=$ERR['unae19titulo'].$sSepara.$sError;}
	//if ($unae19id==''){$sError=$ERR['unae19id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($unae19consec==''){$sError=$ERR['unae19consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($unae19idrangoedad==''){$sError=$ERR['unae19idrangoedad'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$unae19id==0){
			if ((int)$unae19consec==0){
				$unae19consec=tabla_consecutivo('unae19rango', 'unae19consec', 'unae19idrangoedad='.$unae19idrangoedad.'', $objDB);
				if ($unae19consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT unae19idrangoedad FROM unae19rango WHERE unae19idrangoedad='.$unae19idrangoedad.' AND unae19consec='.$unae19consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$unae19id=tabla_consecutivo('unae19rango', 'unae19id', '', $objDB);
				if ($unae19id==-1){$sError=$objDB->serror;}
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
			$scampos='unae19idrangoedad, unae19consec, unae19id, unae19titulo, unae19base, 
unae19techo';
			$svalores=''.$unae19idrangoedad.', '.$unae19consec.', '.$unae19id.', "'.$unae19titulo.'", '.$unae19base.', 
'.$unae19techo.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae19rango ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO unae19rango ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Rangos}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $unae19id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo219[1]='unae19titulo';
			$scampo219[2]='unae19base';
			$scampo219[3]='unae19techo';
			$svr219[1]=$unae19titulo;
			$svr219[2]=$unae19base;
			$svr219[3]=$unae19techo;
			$inumcampos=3;
			$sWhere='unae19id='.$unae19id.'';
			//$sWhere='unae19idrangoedad='.$unae19idrangoedad.' AND unae19consec='.$unae19consec.'';
			$sSQL='SELECT * FROM unae19rango WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo219[$k]]!=$svr219[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo219[$k].'="'.$svr219[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE unae19rango SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE unae19rango SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Rangos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $unae19id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $unae19id, $sDebug);
	}
function f219_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=219;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_219='lg/lg_219_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_219)){$mensajes_219='lg/lg_219_es.php';}
	require $mensajes_todas;
	require $mensajes_219;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$unae19idrangoedad=numeros_validar($aParametros[1]);
	$unae19consec=numeros_validar($aParametros[2]);
	$unae19id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=219';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$unae19id.' LIMIT 0, 1';
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
		$sWhere='unae19id='.$unae19id.'';
		//$sWhere='unae19idrangoedad='.$unae19idrangoedad.' AND unae19consec='.$unae19consec.'';
		$sSQL='DELETE FROM unae19rango WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {219 Rangos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae19id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f219_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_219='lg/lg_219_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_219)){$mensajes_219='lg/lg_219_es.php';}
	require $mensajes_todas;
	require $mensajes_219;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$unae18id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	$sSQL='SELECT unae18estado FROM unae18rangoedad WHERE unae18id='.$unae18id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['unae18estado']!='S'){$babierta=true;}
		}
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
	$sTitulos='Rangoedad, Consec, Id, Titulo, Base, Techo';
	$sSQL='SELECT TB.unae19idrangoedad, TB.unae19consec, TB.unae19id, TB.unae19titulo, TB.unae19base, TB.unae19techo 
FROM unae19rango AS TB 
WHERE '.$sSQLadd1.' TB.unae19idrangoedad='.$unae18id.' '.$sSQLadd.'
ORDER BY TB.unae19consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_219" name="consulta_219" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_219" name="titulos_219" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 219: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf219" name="paginaf219" type="hidden" value="'.$pagina.'"/><input id="lppf219" name="lppf219" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae19consec'].'</b></td>
<td><b>'.$ETI['unae19titulo'].'</b></td>
<td><b>'.$ETI['unae19base'].'</b></td>
<td><b>'.$ETI['unae19techo'].'</b></td>
<td align="right">
'.html_paginador('paginaf219', $registros, $lineastabla, $pagina, 'paginarf219()').'
'.html_lpp('lppf219', $lineastabla, 'paginarf219()').'
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
		$et_unae19consec=$sPrefijo.$filadet['unae19consec'].$sSufijo;
		$et_unae19titulo=$sPrefijo.cadena_notildes($filadet['unae19titulo']).$sSufijo;
		$et_unae19base=$sPrefijo.$filadet['unae19base'].$sSufijo;
		$et_unae19techo=$sPrefijo.$filadet['unae19techo'].$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf219('.$filadet['unae19id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_unae19consec.'</td>
<td>'.$et_unae19titulo.'</td>
<td>'.$et_unae19base.'</td>
<td>'.$et_unae19techo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f219_Clonar($unae19idrangoedad, $unae19idrangoedadPadre, $objDB){
	$sError='';
	$unae19consec=tabla_consecutivo('unae19rango', 'unae19consec', 'unae19idrangoedad='.$unae19idrangoedad.'', $objDB);
	if ($unae19consec==-1){$sError=$objDB->serror;}
	$unae19id=tabla_consecutivo('unae19rango', 'unae19id', '', $objDB);
	if ($unae19id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos219='unae19idrangoedad, unae19consec, unae19id, unae19titulo, unae19base, unae19techo';
		$sValores219='';
		$sSQL='SELECT * FROM unae19rango WHERE unae19idrangoedad='.$unae19idrangoedadPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores219!=''){$sValores219=$sValores219.', ';}
			$sValores219=$sValores219.'('.$unae19idrangoedad.', '.$unae19consec.', '.$unae19id.', "'.$fila['unae19titulo'].'", '.$fila['unae19base'].', '.$fila['unae19techo'].')';
			$unae19consec++;
			$unae19id++;
			}
		if ($sValores219!=''){
			$sSQL='INSERT INTO unae19rango('.$sCampos219.') VALUES '.$sValores219.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 219 Rangos XAJAX 
function f219_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $unae19id, $sDebugGuardar)=f219_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f219_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f219detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf219('.$unae19id.')');
			//}else{
			$objResponse->call('limpiaf219');
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
function f219_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$unae19idrangoedad=numeros_validar($aParametros[1]);
		$unae19consec=numeros_validar($aParametros[2]);
		if (($unae19idrangoedad!='')&&($unae19consec!='')){$besta=true;}
		}else{
		$unae19id=$aParametros[103];
		if ((int)$unae19id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'unae19idrangoedad='.$unae19idrangoedad.' AND unae19consec='.$unae19consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'unae19id='.$unae19id.'';
			}
		$sSQL='SELECT * FROM unae19rango WHERE '.$sSQLcondi;
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
		$unae19consec_nombre='';
		$html_unae19consec=html_oculto('unae19consec', $fila['unae19consec'], $unae19consec_nombre);
		$objResponse->assign('div_unae19consec', 'innerHTML', $html_unae19consec);
		$unae19id_nombre='';
		$html_unae19id=html_oculto('unae19id', $fila['unae19id'], $unae19id_nombre);
		$objResponse->assign('div_unae19id', 'innerHTML', $html_unae19id);
		$objResponse->assign('unae19titulo', 'value', $fila['unae19titulo']);
		$objResponse->assign('unae19base', 'value', $fila['unae19base']);
		$objResponse->assign('unae19techo', 'value', $fila['unae19techo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina219','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unae19consec', 'value', $unae19consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$unae19id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f219_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f219_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f219_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f219detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf219');
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
function f219_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f219_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f219detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f219_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_unae19consec='<input id="unae19consec" name="unae19consec" type="text" value="" onchange="revisaf219()" class="cuatro"/>';
	$html_unae19id='<input id="unae19id" name="unae19id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unae19consec','innerHTML', $html_unae19consec);
	$objResponse->assign('div_unae19id','innerHTML', $html_unae19id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>