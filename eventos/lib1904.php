<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Tuesday, August 27, 2019
--- 1904 Participantes
*/
function f1904_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1904;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1904='lg/lg_1904_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1904)){$mensajes_1904='lg/lg_1904_es.php';}
	require $mensajes_todas;
	require $mensajes_1904;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even04idevento=numeros_validar($valores[1]);
	$even04idparticipante=numeros_validar($valores[2]);
	$even04id=numeros_validar($valores[3], true);
	$even04institucion=htmlspecialchars(trim($valores[4]));
	$even04cargo=htmlspecialchars(trim($valores[5]));
	$even04correo=htmlspecialchars(trim($valores[6]));
	$even04telefono=htmlspecialchars(trim($valores[7]));
	$even04estadoasistencia=numeros_validar($valores[8]);
	//if ($even04estadoasistencia==''){$even04estadoasistencia=0;}
	$sSepara=', ';
	if ($even04estadoasistencia==''){$sError=$ERR['even04estadoasistencia'].$sSepara.$sError;}
	if ($even04telefono==''){$sError=$ERR['even04telefono'].$sSepara.$sError;}
	if ($even04correo==''){$sError=$ERR['even04correo'].$sSepara.$sError;}
	if ($even04cargo==''){$sError=$ERR['even04cargo'].$sSepara.$sError;}
	if ($even04institucion==''){$sError=$ERR['even04institucion'].$sSepara.$sError;}
	//if ($even04id==''){$sError=$ERR['even04id'].$sSepara.$sError;}//CONSECUTIVO
	if ($even04idparticipante==0){$sError=$ERR['even04idparticipante'].$sSepara.$sError;}
	if ($even04idevento==''){$sError=$ERR['even04idevento'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($even04idparticipante, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$even04id==0){
			if ($sError==''){
				$sSQL='SELECT even04idevento FROM even04eventoparticipante WHERE even04idevento='.$even04idevento.' AND even04idparticipante="'.$even04idparticipante.'"';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even04id=tabla_consecutivo('even04eventoparticipante', 'even04id', '', $objDB);
				if ($even04id==-1){$sError=$objDB->serror;}
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
			$scampos='even04idevento, even04idparticipante, even04id, even04institucion, even04cargo, 
even04correo, even04telefono, even04estadoasistencia';
			$svalores=''.$even04idevento.', "'.$even04idparticipante.'", '.$even04id.', "'.$even04institucion.'", "'.$even04cargo.'", 
"'.$even04correo.'", "'.$even04telefono.'", '.$even04estadoasistencia.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO even04eventoparticipante ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO even04eventoparticipante ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Participantes}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $even04id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1904[1]='even04institucion';
			$scampo1904[2]='even04cargo';
			$scampo1904[3]='even04correo';
			$scampo1904[4]='even04telefono';
			$scampo1904[5]='even04estadoasistencia';
			$svr1904[1]=$even04institucion;
			$svr1904[2]=$even04cargo;
			$svr1904[3]=$even04correo;
			$svr1904[4]=$even04telefono;
			$svr1904[5]=$even04estadoasistencia;
			$inumcampos=5;
			$sWhere='even04id='.$even04id.'';
			//$sWhere='even04idevento='.$even04idevento.' AND even04idparticipante="'.$even04idparticipante.'"';
			$sSQL='SELECT * FROM even04eventoparticipante WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1904[$k]]!=$svr1904[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1904[$k].'="'.$svr1904[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE even04eventoparticipante SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE even04eventoparticipante SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Participantes}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $even04id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even04id, $sDebug);
	}
function f1904_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1904;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1904='lg/lg_1904_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1904)){$mensajes_1904='lg/lg_1904_es.php';}
	require $mensajes_todas;
	require $mensajes_1904;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$even04idevento=numeros_validar($aParametros[1]);
	$even04idparticipante=numeros_validar($aParametros[2]);
	$even04id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1904';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$even04id.' LIMIT 0, 1';
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
		$sWhere='even04id='.$even04id.'';
		//$sWhere='even04idevento='.$even04idevento.' AND even04idparticipante="'.$even04idparticipante.'"';
		$sSQL='DELETE FROM even04eventoparticipante WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1904 Participantes}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $even04id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1904_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1904='lg/lg_1904_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1904)){$mensajes_1904='lg/lg_1904_es.php';}
	require $mensajes_todas;
	require $mensajes_1904;
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
	$sTitulos='Evento, Participante, Id, Institucion, Cargo, Correo, Telefono, Estadoasistencia';
	$sSQL='SELECT TB.even04idevento, T2.unad11razonsocial AS C2_nombre, TB.even04id, TB.even04institucion, TB.even04cargo, TB.even04correo, TB.even04telefono, T8.even13nombre, TB.even04idparticipante, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.even04estadoasistencia 
FROM even04eventoparticipante AS TB, unad11terceros AS T2, even13estadoasistencia AS T8 
WHERE  TB.even04idevento='.$even02id.' AND TB.even04idparticipante=T2.unad11id AND TB.even04estadoasistencia=T8.even13id 
ORDER BY TB.even04idparticipante';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1904" name="consulta_1904" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1904" name="titulos_1904" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1904: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1904" name="paginaf1904" type="hidden" value="'.$pagina.'"/><input id="lppf1904" name="lppf1904" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['even04idparticipante'].'</b></td>
<td><b>'.$ETI['even04institucion'].'</b></td>
<td><b>'.$ETI['even04cargo'].'</b></td>
<td><b>'.$ETI['even04correo'].'</b></td>
<td><b>'.$ETI['even04telefono'].'</b></td>
<td><b>'.$ETI['even04estadoasistencia'].'</b></td>
<td align="right">
'.html_paginador('paginaf1904', $registros, $lineastabla, $pagina, 'paginarf1904()').'
'.html_lpp('lppf1904', $lineastabla, 'paginarf1904()').'
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
		$et_even04idparticipante=$sPrefijo.$filadet['even04idparticipante'].$sSufijo;
		$et_even04institucion=$sPrefijo.cadena_notildes($filadet['even04institucion']).$sSufijo;
		$et_even04cargo=$sPrefijo.cadena_notildes($filadet['even04cargo']).$sSufijo;
		$et_even04correo=$sPrefijo.cadena_notildes($filadet['even04correo']).$sSufijo;
		$et_even04telefono=$sPrefijo.cadena_notildes($filadet['even04telefono']).$sSufijo;
		$et_even04estadoasistencia=$sPrefijo.cadena_notildes($filadet['even13nombre']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1904('.$filadet['even04id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$et_even04institucion.'</td>
<td>'.$et_even04cargo.'</td>
<td>'.$et_even04correo.'</td>
<td>'.$et_even04telefono.'</td>
<td>'.$et_even04estadoasistencia.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1904_Clonar($even04idevento, $even04ideventoPadre, $objDB){
	$sError='';
	$even04id=tabla_consecutivo('even04eventoparticipante', 'even04id', '', $objDB);
	if ($even04id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1904='even04idevento, even04idparticipante, even04id, even04institucion, even04cargo, even04correo, even04telefono, even04estadoasistencia';
		$sValores1904='';
		$sSQL='SELECT * FROM even04eventoparticipante WHERE even04idevento='.$even04ideventoPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1904!=''){$sValores1904=$sValores1904.', ';}
			$sValores1904=$sValores1904.'('.$even04idevento.', '.$fila['even04idparticipante'].', '.$even04id.', "'.$fila['even04institucion'].'", "'.$fila['even04cargo'].'", "'.$fila['even04correo'].'", "'.$fila['even04telefono'].'", '.$fila['even04estadoasistencia'].')';
			$even04id++;
			}
		if ($sValores1904!=''){
			$sSQL='INSERT INTO even04eventoparticipante('.$sCampos1904.') VALUES '.$sValores1904.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1904 Participantes XAJAX 
function f1904_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $even04id, $sDebugGuardar)=f1904_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1904_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1904detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1904('.$even04id.')');
			//}else{
			$objResponse->call('limpiaf1904');
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
function f1904_Traer($aParametros){
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
		$even04idevento=numeros_validar($aParametros[1]);
		$even04idparticipante=numeros_validar($aParametros[2]);
		if (($even04idevento!='')&&($even04idparticipante!='')){$besta=true;}
		}else{
		$even04id=$aParametros[103];
		if ((int)$even04id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'even04idevento='.$even04idevento.' AND even04idparticipante='.$even04idparticipante.'';
			}else{
			$sSQLcondi=$sSQLcondi.'even04id='.$even04id.'';
			}
		$sSQL='SELECT * FROM even04eventoparticipante WHERE '.$sSQLcondi;
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
		$even04idparticipante_id=(int)$fila['even04idparticipante'];
		$even04idparticipante_td=$APP->tipo_doc;
		$even04idparticipante_doc='';
		$even04idparticipante_nombre='';
		if ($even04idparticipante_id!=0){
			list($even04idparticipante_nombre, $even04idparticipante_id, $even04idparticipante_td, $even04idparticipante_doc)=html_tercero($even04idparticipante_td, $even04idparticipante_doc, $even04idparticipante_id, 0, $objDB);
			}
		$html_even04idparticipante_llaves=html_DivTerceroV2('even04idparticipante', $even04idparticipante_td, $even04idparticipante_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('even04idparticipante', 'value', $even04idparticipante_id);
		$objResponse->assign('div_even04idparticipante_llaves', 'innerHTML', $html_even04idparticipante_llaves);
		$objResponse->assign('div_even04idparticipante', 'innerHTML', $even04idparticipante_nombre);
		$even04id_nombre='';
		$html_even04id=html_oculto('even04id', $fila['even04id'], $even04id_nombre);
		$objResponse->assign('div_even04id', 'innerHTML', $html_even04id);
		$objResponse->assign('even04institucion', 'value', $fila['even04institucion']);
		$objResponse->assign('even04cargo', 'value', $fila['even04cargo']);
		$objResponse->assign('even04correo', 'value', $fila['even04correo']);
		$objResponse->assign('even04telefono', 'value', $fila['even04telefono']);
		$objResponse->assign('even04estadoasistencia', 'value', $fila['even04estadoasistencia']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1904','block')");
		}else{
		if ($paso==1){
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even04id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1904_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1904_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1904_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1904detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1904');
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
function f1904_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1904_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1904detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1904_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$even04idparticipante=0;
	$even04idparticipante_rs='';
	$html_even04idparticipante_llaves=html_DivTerceroV2('even04idparticipante', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_even04id='<input id="even04id" name="even04id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('even04idparticipante','value', $even04idparticipante);
	$objResponse->assign('div_even04idparticipante_llaves','innerHTML', $html_even04idparticipante_llaves);
	$objResponse->assign('div_even04idparticipante','innerHTML', $even04idparticipante_rs);
	$objResponse->assign('div_even04id','innerHTML', $html_even04id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>