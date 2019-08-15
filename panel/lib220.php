<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Wednesday, August 14, 2019
--- 220 Distribucion
*/
function f220_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=220;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_220='lg/lg_220_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_220)){$mensajes_220='lg/lg_220_es.php';}
	require $mensajes_todas;
	require $mensajes_220;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$unae20idrangoedad=numeros_validar($valores[1]);
	$unae20edad=numeros_validar($valores[2]);
	$unae20id=numeros_validar($valores[3], true);
	$unae20idrango=numeros_validar($valores[4]);
	//if ($unae20idrango==''){$unae20idrango=0;}
	$sSepara=', ';
	if ($unae20idrango==''){$sError=$ERR['unae20idrango'].$sSepara.$sError;}
	//if ($unae20id==''){$sError=$ERR['unae20id'].$sSepara.$sError;}//CONSECUTIVO
	if ($unae20edad==''){$sError=$ERR['unae20edad'].$sSepara.$sError;}
	if ($unae20idrangoedad==''){$sError=$ERR['unae20idrangoedad'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$unae20id==0){
			if ($sError==''){
				$sSQL='SELECT unae20idrangoedad FROM unae20rangosdist WHERE unae20idrangoedad='.$unae20idrangoedad.' AND unae20edad='.$unae20edad.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$unae20id=tabla_consecutivo('unae20rangosdist', 'unae20id', '', $objDB);
				if ($unae20id==-1){$sError=$objDB->serror;}
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
			$scampos='unae20idrangoedad, unae20edad, unae20id, unae20idrango';
			$svalores=''.$unae20idrangoedad.', '.$unae20edad.', '.$unae20id.', '.$unae20idrango.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae20rangosdist ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO unae20rangosdist ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Distribucion}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $unae20id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo220[1]='unae20idrango';
			$svr220[1]=$unae20idrango;
			$inumcampos=1;
			$sWhere='unae20id='.$unae20id.'';
			//$sWhere='unae20idrangoedad='.$unae20idrangoedad.' AND unae20edad='.$unae20edad.'';
			$sSQL='SELECT * FROM unae20rangosdist WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo220[$k]]!=$svr220[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo220[$k].'="'.$svr220[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE unae20rangosdist SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE unae20rangosdist SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Distribucion}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $unae20id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $unae20id, $sDebug);
	}
function f220_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=220;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_220='lg/lg_220_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_220)){$mensajes_220='lg/lg_220_es.php';}
	require $mensajes_todas;
	require $mensajes_220;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$unae20idrangoedad=numeros_validar($aParametros[1]);
	$unae20edad=numeros_validar($aParametros[2]);
	$unae20id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=220';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$unae20id.' LIMIT 0, 1';
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
		$sWhere='unae20id='.$unae20id.'';
		//$sWhere='unae20idrangoedad='.$unae20idrangoedad.' AND unae20edad='.$unae20edad.'';
		$sSQL='DELETE FROM unae20rangosdist WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {220 Distribucion}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae20id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f220_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_220='lg/lg_220_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_220)){$mensajes_220='lg/lg_220_es.php';}
	require $mensajes_todas;
	require $mensajes_220;
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
	$sTitulos='Rangoedad, Edad, Id, Rango';
	$sSQL='SELECT TB.unae20idrangoedad, TB.unae20edad, TB.unae20id, TB.unae20idrango, T1.unae19titulo 
FROM unae20rangosdist AS TB, unae19rango AS T1 
WHERE '.$sSQLadd1.' TB.unae20idrangoedad='.$unae18id.' AND TB.unae20idrango=T1.unae19id '.$sSQLadd.'
ORDER BY TB.unae20edad';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_220" name="consulta_220" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_220" name="titulos_220" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 220: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf220" name="paginaf220" type="hidden" value="'.$pagina.'"/><input id="lppf220" name="lppf220" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae20edad'].'</b></td>
<td><b>'.$ETI['unae20idrango'].'</b></td>
<td align="right">
'.html_paginador('paginaf220', $registros, $lineastabla, $pagina, 'paginarf220()').'
'.html_lpp('lppf220', $lineastabla, 'paginarf220()', 200).'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if ($filadet['unae20idrango']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_unae20edad=$sPrefijo.$filadet['unae20edad'].$sSufijo;
		$et_unae20idrango=$sPrefijo.$filadet['unae19titulo'].$sSufijo;
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf220('.$filadet['unae20id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_unae20edad.'</td>
<td>'.$et_unae20idrango.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f220_Clonar($unae20idrangoedad, $unae20idrangoedadPadre, $objDB){
	$sError='';
	$unae20id=tabla_consecutivo('unae20rangosdist', 'unae20id', '', $objDB);
	if ($unae20id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos220='unae20idrangoedad, unae20edad, unae20id, unae20idrango';
		$sValores220='';
		$sSQL='SELECT * FROM unae20rangosdist WHERE unae20idrangoedad='.$unae20idrangoedadPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores220!=''){$sValores220=$sValores220.', ';}
			$sValores220=$sValores220.'('.$unae20idrangoedad.', '.$fila['unae20edad'].', '.$unae20id.', '.$fila['unae20idrango'].')';
			$unae20id++;
			}
		if ($sValores220!=''){
			$sSQL='INSERT INTO unae20rangosdist('.$sCampos220.') VALUES '.$sValores220.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 220 Distribucion XAJAX 
function f220_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $unae20id, $sDebugGuardar)=f220_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f220_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f220detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf220('.$unae20id.')');
			//}else{
			$objResponse->call('limpiaf220');
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
function f220_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$unae20idrangoedad=numeros_validar($aParametros[1]);
		$unae20edad=numeros_validar($aParametros[2]);
		if (($unae20idrangoedad!='')&&($unae20edad!='')){$besta=true;}
		}else{
		$unae20id=$aParametros[103];
		if ((int)$unae20id!=0){$besta=true;}
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
			$sSQLcondi=$sSQLcondi.'unae20idrangoedad='.$unae20idrangoedad.' AND unae20edad='.$unae20edad.'';
			}else{
			$sSQLcondi=$sSQLcondi.'unae20id='.$unae20id.'';
			}
		$sSQL='SELECT * FROM unae20rangosdist WHERE '.$sSQLcondi;
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
		$unae20edad_nombre='';
		$html_unae20edad=html_oculto('unae20edad', $fila['unae20edad'], $unae20edad_nombre);
		$objResponse->assign('div_unae20edad', 'innerHTML', $html_unae20edad);
		$unae20id_nombre='';
		$html_unae20id=html_oculto('unae20id', $fila['unae20id'], $unae20id_nombre);
		$objResponse->assign('div_unae20id', 'innerHTML', $html_unae20id);
		$objResponse->assign('unae20idrango', 'value', $fila['unae20idrango']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina220','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unae20edad', 'value', $unae20edad);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$unae20id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f220_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f220_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f220_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f220detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf220');
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
function f220_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f220_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f220detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f220_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_unae20edad='<input id="unae20edad" name="unae20edad" type="text" value="" onchange="revisaf220()" class="cuatro"/>';
	$html_unae20id='<input id="unae20id" name="unae20id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unae20edad','innerHTML', $html_unae20edad);
	$objResponse->assign('div_unae20id','innerHTML', $html_unae20id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>