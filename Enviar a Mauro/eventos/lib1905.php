<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Tuesday, August 27, 2019
--- 1905 Noticias
*/
function f1905_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1905;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1905='lg/lg_1905_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1905)){$mensajes_1905='lg/lg_1905_es.php';}
	require $mensajes_todas;
	require $mensajes_1905;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even05idevento=numeros_validar($valores[1]);
	$even05consec=numeros_validar($valores[2]);
	$even05id=numeros_validar($valores[3], true);
	$even05fecha=$valores[4];
	$even05publicar=htmlspecialchars(trim($valores[5]));
	$even05idtercero=numeros_validar($valores[6]);
	$even05noticia=htmlspecialchars(trim($valores[7]));
	$sSepara=', ';
	if ($even05noticia==''){$sError=$ERR['even05noticia'].$sSepara.$sError;}
	if ($even05idtercero==0){$sError=$ERR['even05idtercero'].$sSepara.$sError;}
	if ($even05publicar==''){$sError=$ERR['even05publicar'].$sSepara.$sError;}
	if (!fecha_esvalida($even05fecha)){
		//$even05fecha='00/00/0000';
		$sError=$ERR['even05fecha'].$sSepara.$sError;
		}
	//if ($even05id==''){$sError=$ERR['even05id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($even05consec==''){$sError=$ERR['even05consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($even05idevento==''){$sError=$ERR['even05idevento'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($even05idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$even05id==0){
			if ((int)$even05consec==0){
				$even05consec=tabla_consecutivo('eve05eventonoticia', 'even05consec', 'even05idevento='.$even05idevento.'', $objDB);
				if ($even05consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT even05idevento FROM eve05eventonoticia WHERE even05idevento='.$even05idevento.' AND even05consec='.$even05consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even05id=tabla_consecutivo('eve05eventonoticia', 'even05id', '', $objDB);
				if ($even05id==-1){$sError=$objDB->serror;}
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
		//Si el campo even05noticia permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$even05noticia=str_replace('"', '\"', $even05noticia);
		$even05noticia=str_replace('"', '\"', $even05noticia);
		if ($binserta){
			$scampos='even05idevento, even05consec, even05id, even05fecha, even05publicar, 
even05idtercero, even05noticia';
			$svalores=''.$even05idevento.', '.$even05consec.', '.$even05id.', "'.$even05fecha.'", "'.$even05publicar.'", 
"'.$even05idtercero.'", "'.$even05noticia.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO eve05eventonoticia ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO eve05eventonoticia ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Noticias}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $even05id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1905[1]='even05publicar';
			$scampo1905[2]='even05noticia';
			$svr1905[1]=$even05publicar;
			$svr1905[2]=$even05noticia;
			$inumcampos=2;
			$sWhere='even05id='.$even05id.'';
			//$sWhere='even05idevento='.$even05idevento.' AND even05consec='.$even05consec.'';
			$sSQL='SELECT * FROM eve05eventonoticia WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1905[$k]]!=$svr1905[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1905[$k].'="'.$svr1905[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE eve05eventonoticia SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE eve05eventonoticia SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Noticias}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $even05id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even05id, $sDebug);
	}
function f1905_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1905;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1905='lg/lg_1905_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1905)){$mensajes_1905='lg/lg_1905_es.php';}
	require $mensajes_todas;
	require $mensajes_1905;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$even05idevento=numeros_validar($aParametros[1]);
	$even05consec=numeros_validar($aParametros[2]);
	$even05id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1905';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$even05id.' LIMIT 0, 1';
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
		$sWhere='even05id='.$even05id.'';
		//$sWhere='even05idevento='.$even05idevento.' AND even05consec='.$even05consec.'';
		$sSQL='DELETE FROM eve05eventonoticia WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1905 Noticias}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $even05id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1905_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1905='lg/lg_1905_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1905)){$mensajes_1905='lg/lg_1905_es.php';}
	require $mensajes_todas;
	require $mensajes_1905;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$even02id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM even02evento WHERE even02id='.$even02id;
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
	$sTitulos='Evento, Consec, Id, Fecha, Publicar, Tercero, Noticia';
	$sSQL='SELECT TB.even05idevento, TB.even05consec, TB.even05id, TB.even05fecha, TB.even05publicar, T6.unad11razonsocial AS C6_nombre, TB.even05noticia, TB.even05idtercero, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc 
FROM eve05eventonoticia AS TB, unad11terceros AS T6 
WHERE '.$sSQLadd1.' TB.even05idevento='.$even02id.' AND TB.even05idtercero=T6.unad11id '.$sSQLadd.'
ORDER BY TB.even05consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1905" name="consulta_1905" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1905" name="titulos_1905" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1905: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1905" name="paginaf1905" type="hidden" value="'.$pagina.'"/><input id="lppf1905" name="lppf1905" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['even05consec'].'</b></td>
<td><b>'.$ETI['even05fecha'].'</b></td>
<td><b>'.$ETI['even05publicar'].'</b></td>
<td colspan="2"><b>'.$ETI['even05idtercero'].'</b></td>
<td><b>'.$ETI['even05noticia'].'</b></td>
<td align="right">
'.html_paginador('paginaf1905', $registros, $lineastabla, $pagina, 'paginarf1905()').'
'.html_lpp('lppf1905', $lineastabla, 'paginarf1905()').'
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
		$et_even05consec=$sPrefijo.$filadet['even05consec'].$sSufijo;
		$et_even05fecha='';
		if ($filadet['even05fecha']!='00/00/0000'){$et_even05fecha=$sPrefijo.$filadet['even05fecha'].$sSufijo;}
		$et_even05publicar=$ETI['no'];
		if ($filadet['even05publicar']=='S'){$et_even05publicar=$ETI['si'];}
		$et_even05idtercero=$sPrefijo.$filadet['even05idtercero'].$sSufijo;
		$et_even05noticia=$sPrefijo.cadena_notildes($filadet['even05noticia']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1905('.$filadet['even05id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_even05consec.'</td>
<td>'.$et_even05fecha.'</td>
<td>'.$et_even05publicar.'</td>
<td>'.$sPrefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C6_nombre']).$sSufijo.'</td>
<td>'.$et_even05noticia.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1905_Clonar($even05idevento, $even05ideventoPadre, $objDB){
	$sError='';
	$even05consec=tabla_consecutivo('eve05eventonoticia', 'even05consec', 'even05idevento='.$even05idevento.'', $objDB);
	if ($even05consec==-1){$sError=$objDB->serror;}
	$even05id=tabla_consecutivo('eve05eventonoticia', 'even05id', '', $objDB);
	if ($even05id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1905='even05idevento, even05consec, even05id, even05fecha, even05publicar, even05idtercero, even05noticia';
		$sValores1905='';
		$sSQL='SELECT * FROM eve05eventonoticia WHERE even05idevento='.$even05ideventoPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1905!=''){$sValores1905=$sValores1905.', ';}
			$sValores1905=$sValores1905.'('.$even05idevento.', '.$even05consec.', '.$even05id.', "'.$fila['even05fecha'].'", "'.$fila['even05publicar'].'", '.$fila['even05idtercero'].', "'.$fila['even05noticia'].'")';
			$even05consec++;
			$even05id++;
			}
		if ($sValores1905!=''){
			$sSQL='INSERT INTO eve05eventonoticia('.$sCampos1905.') VALUES '.$sValores1905.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1905 Noticias XAJAX 
function f1905_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $even05id, $sDebugGuardar)=f1905_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1905_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1905detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1905('.$even05id.')');
			//}else{
			$objResponse->call('limpiaf1905');
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
function f1905_Traer($aParametros){
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
		$even05idevento=numeros_validar($aParametros[1]);
		$even05consec=numeros_validar($aParametros[2]);
		if (($even05idevento!='')&&($even05consec!='')){$besta=true;}
		}else{
		$even05id=$aParametros[103];
		if ((int)$even05id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'even05idevento='.$even05idevento.' AND even05consec='.$even05consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'even05id='.$even05id.'';
			}
		$sSQL='SELECT * FROM eve05eventonoticia WHERE '.$sSQLcondi;
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
		$even05idtercero_id=(int)$fila['even05idtercero'];
		$even05idtercero_td=$APP->tipo_doc;
		$even05idtercero_doc='';
		$even05idtercero_nombre='';
		if ($even05idtercero_id!=0){
			list($even05idtercero_nombre, $even05idtercero_id, $even05idtercero_td, $even05idtercero_doc)=html_tercero($even05idtercero_td, $even05idtercero_doc, $even05idtercero_id, 0, $objDB);
			}
		$even05consec_nombre='';
		$html_even05consec=html_oculto('even05consec', $fila['even05consec'], $even05consec_nombre);
		$objResponse->assign('div_even05consec', 'innerHTML', $html_even05consec);
		$even05id_nombre='';
		$html_even05id=html_oculto('even05id', $fila['even05id'], $even05id_nombre);
		$objResponse->assign('div_even05id', 'innerHTML', $html_even05id);
		$html_even05fecha=html_oculto('even05fecha', $fila['even05fecha']);
		$objResponse->assign('div_even05fecha', 'innerHTML', $html_even05fecha);
		$objResponse->assign('even05publicar', 'value', $fila['even05publicar']);
		$bOculto=true;
		$html_even05idtercero_llaves=html_DivTerceroV2('even05idtercero', $even05idtercero_td, $even05idtercero_doc, $bOculto, $even05idtercero_id, $ETI['ing_doc']);
		$objResponse->assign('even05idtercero', 'value', $even05idtercero_id);
		$objResponse->assign('div_even05idtercero_llaves', 'innerHTML', $html_even05idtercero_llaves);
		$objResponse->assign('div_even05idtercero', 'innerHTML', $even05idtercero_nombre);
		$objResponse->assign('even05noticia', 'value', $fila['even05noticia']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1905','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even05consec', 'value', $even05consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even05id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1905_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1905_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1905_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1905detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1905');
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
function f1905_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1905_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1905detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1905_PintarLlaves($aParametros){
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
	$objCombos=new clsHtmlCombos('n');
	$html_even05consec='<input id="even05consec" name="even05consec" type="text" value="" onchange="revisaf1905()" class="cuatro"/>';
	$html_even05id='<input id="even05id" name="even05id" type="hidden" value=""/>';
	$seven05fecha=fecha_hoy();
	$html_even05fecha=html_oculto('even05fecha', $seven05fecha, formato_fechalarga($seven05fecha));
	list($even05idtercero_rs, $even05idtercero, $even05idtercero_td, $even05idtercero_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_even05idtercero_llaves=html_DivTerceroV2('even05idtercero', $even05idtercero_td, $even05idtercero_doc, true, 0, $ETI['ing_doc']);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even05consec','innerHTML', $html_even05consec);
	$objResponse->assign('div_even05id','innerHTML', $html_even05id);
	$objResponse->assign('div_even05fecha','innerHTML', $html_even05fecha);
	$objResponse->assign('even05idtercero','value', $even05idtercero);
	$objResponse->assign('div_even05idtercero_llaves','innerHTML', $html_even05idtercero_llaves);
	$objResponse->assign('div_even05idtercero','innerHTML', $even05idtercero_rs);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>