<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Tuesday, August 27, 2019
--- 1903 Cursos
*/
function f1903_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1903;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1903='lg/lg_1903_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1903)){$mensajes_1903='lg/lg_1903_es.php';}
	require $mensajes_todas;
	require $mensajes_1903;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even03idevento=numeros_validar($valores[1]);
	$even03idcurso=numeros_validar($valores[2]);
	$even03id=numeros_validar($valores[3], true);
	$even03vigente=htmlspecialchars(trim($valores[4]));
	$sSepara=', ';
	if ($even03vigente==''){$sError=$ERR['even03vigente'].$sSepara.$sError;}
	//if ($even03id==''){$sError=$ERR['even03id'].$sSepara.$sError;}//CONSECUTIVO
	if ($even03idcurso==0){$sError=$ERR['even03idcurso'].$sSepara.$sError;}
	if ($even03idevento==''){$sError=$ERR['even03idevento'].$sSepara.$sError;}
	if ($sError==''){
		$sSQL='SELECT unad40id FROM unad40curso WHERE unad40id="'.$even03idcurso.'"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){$sError='No se encuentra el Curso {ref '.$even03idcurso.'}';}
		}
	if ($sError==''){
		if ((int)$even03id==0){
			if ($sError==''){
				$sSQL='SELECT even03idevento FROM even03eventocurso WHERE even03idevento='.$even03idevento.' AND even03idcurso='.$even03idcurso.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even03id=tabla_consecutivo('even03eventocurso', 'even03id', '', $objDB);
				if ($even03id==-1){$sError=$objDB->serror;}
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
			$scampos='even03idevento, even03idcurso, even03id, even03vigente';
			$svalores=''.$even03idevento.', '.$even03idcurso.', '.$even03id.', "'.$even03vigente.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO even03eventocurso ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO even03eventocurso ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Cursos}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $even03id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1903[1]='even03vigente';
			$svr1903[1]=$even03vigente;
			$inumcampos=1;
			$sWhere='even03id='.$even03id.'';
			//$sWhere='even03idevento='.$even03idevento.' AND even03idcurso='.$even03idcurso.'';
			$sSQL='SELECT * FROM even03eventocurso WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1903[$k]]!=$svr1903[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1903[$k].'="'.$svr1903[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE even03eventocurso SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE even03eventocurso SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cursos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $even03id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even03id, $sDebug);
	}
function f1903_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1903;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1903='lg/lg_1903_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1903)){$mensajes_1903='lg/lg_1903_es.php';}
	require $mensajes_todas;
	require $mensajes_1903;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$even03idevento=numeros_validar($aParametros[1]);
	$even03idcurso=numeros_validar($aParametros[2]);
	$even03id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1903';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$even03id.' LIMIT 0, 1';
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
		$sWhere='even03id='.$even03id.'';
		//$sWhere='even03idevento='.$even03idevento.' AND even03idcurso='.$even03idcurso.'';
		$sSQL='DELETE FROM even03eventocurso WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1903 Cursos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $even03id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1903_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1903='lg/lg_1903_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1903)){$mensajes_1903='lg/lg_1903_es.php';}
	require $mensajes_todas;
	require $mensajes_1903;
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
	$sTitulos='Evento, Curso, Id, Vigente';
	$sSQL='SELECT TB.even03idevento, T2.unad40nombre, TB.even03id, TB.even03vigente, TB.even03idcurso 
FROM even03eventocurso AS TB, unad40curso AS T2 
WHERE '.$sSQLadd1.' TB.even03idevento='.$even02id.' AND TB.even03idcurso=T2.unad40id '.$sSQLadd.'
ORDER BY TB.even03idcurso';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1903" name="consulta_1903" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1903" name="titulos_1903" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1903: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1903" name="paginaf1903" type="hidden" value="'.$pagina.'"/><input id="lppf1903" name="lppf1903" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['even03idcurso'].'</b></td>
<td><b>'.$ETI['even03vigente'].'</b></td>
<td align="right">
'.html_paginador('paginaf1903', $registros, $lineastabla, $pagina, 'paginarf1903()').'
'.html_lpp('lppf1903', $lineastabla, 'paginarf1903()').'
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
		$et_even03idcurso=$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo;
		$et_even03vigente=$ETI['no'];
		if ($filadet['even03vigente']=='S'){$et_even03vigente=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1903('.$filadet['even03id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_even03idcurso.'</td>
<td>'.$et_even03vigente.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1903_Clonar($even03idevento, $even03ideventoPadre, $objDB){
	$sError='';
	$even03id=tabla_consecutivo('even03eventocurso', 'even03id', '', $objDB);
	if ($even03id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1903='even03idevento, even03idcurso, even03id, even03vigente';
		$sValores1903='';
		$sSQL='SELECT * FROM even03eventocurso WHERE even03idevento='.$even03ideventoPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1903!=''){$sValores1903=$sValores1903.', ';}
			$sValores1903=$sValores1903.'('.$even03idevento.', '.$fila['even03idcurso'].', '.$even03id.', "'.$fila['even03vigente'].'")';
			$even03id++;
			}
		if ($sValores1903!=''){
			$sSQL='INSERT INTO even03eventocurso('.$sCampos1903.') VALUES '.$sValores1903.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1903 Cursos XAJAX 
function f1903_Busqueda_db_even03idcurso($sCodigo, $objDB, $bDebug=false){
	$sRespuesta='';
	$sDebug='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT unad40id, unad40nombre, unad40consec FROM unad40curso WHERE unad40consec="'.$sCodigo.'"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Busqueda: '.$sSQL.'<br>';}
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila['unad40consec'].' '.cadena_notildes($fila['unad40nombre']).'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta), $sDebug);
	}
function f1903_Busqueda_even03idcurso($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$sDebug='';
	$scodigo=$aParametros[0];
	$bxajax=true;
	$bDebug=false;
	if (isset($aParametros[3])!=0){if ($aParametros[3]==1){$bxajax=false;}}
	if (isset($aParametros[9])!=0){if ($aParametros[9]==1){$bDebug=true;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $sRespuesta, $sDebugCon)=f1903_Busqueda_db_even03idcurso($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('revisaf1903');
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1903_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $even03id, $sDebugGuardar)=f1903_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1903_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1903detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1903('.$even03id.')');
			//}else{
			$objResponse->call('limpiaf1903');
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
function f1903_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$even03idevento=numeros_validar($aParametros[1]);
		$even03idcurso=numeros_validar($aParametros[2]);
		if (($even03idevento!='')&&($even03idcurso!='')){$besta=true;}
		}else{
		$even03id=$aParametros[103];
		if ((int)$even03id!=0){$besta=true;}
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
			$sSQLcondi=$sSQLcondi.'even03idevento='.$even03idevento.' AND even03idcurso='.$even03idcurso.'';
			}else{
			$sSQLcondi=$sSQLcondi.'even03id='.$even03id.'';
			}
		$sSQL='SELECT * FROM even03eventocurso WHERE '.$sSQLcondi;
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
		$even03idcurso_nombre='';
		$even03idcurso_cod='';
		if ((int)$fila['even03idcurso']!=0){
			$sSQL='SELECT unad40consec, unad40nombre FROM unad40curso WHERE unad40id='.$fila['even03idcurso'].'';
			$res=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($res)!=0){
				$filaDetalle=$objDB->sf($res);
				$even03idcurso_nombre='<b>'.cadena_notildes($filaDetalle['unad40nombre']).'</b>';
				$even03idcurso_cod=$filaDetalle['unad40consec'];
				}
			if ($even03idcurso_nombre==''){
				$even03idcurso_nombre='<font class="rojo">{Ref : '.$fila['even03idcurso'].' No encontrado}</font>';
				}
			}
		$html_even03idcurso_cod=html_oculto('even03idcurso_cod', $even03idcurso_cod);
		$objResponse->assign('even03idcurso', 'value', $fila['even03idcurso']);
		$objResponse->assign('div_even03idcurso_cod', 'innerHTML', $html_even03idcurso_cod);
		$objResponse->call("verboton('beven03idcurso','none')");
		$objResponse->assign('div_even03idcurso', 'innerHTML', $even03idcurso_nombre);
		$even03id_nombre='';
		$html_even03id=html_oculto('even03id', $fila['even03id'], $even03id_nombre);
		$objResponse->assign('div_even03id', 'innerHTML', $html_even03id);
		$objResponse->assign('even03vigente', 'value', $fila['even03vigente']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1903','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even03idcurso', 'value', $even03idcurso);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even03id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1903_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1903_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1903_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1903detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1903');
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
function f1903_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1903_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1903detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1903_PintarLlaves($aParametros){
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
	$html_even03idcurso_cod='<input id="even03idcurso_cod" name="even03idcurso_cod" type="text" value="" onchange="cod_even03idcurso()" class="veinte"/>';
	$html_even03id='<input id="even03id" name="even03id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('even03idcurso','value', '0');
	$objResponse->assign('div_even03idcurso_cod','innerHTML', $html_even03idcurso_cod);
	$objResponse->assign('div_even03idcurso','innerHTML', '');
	$objResponse->call("verboton('beven03idcurso','block')");
	$objResponse->assign('div_even03id','innerHTML', $html_even03id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f1903_Comboeven02idcead($aParametros){
    $_SESSION['u_ultimominuto']=iminutoavance();
    if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
    require './app.php';
    $objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
    if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
    $objDB->xajax();
    $objCombos=new clsHtmlCombos('n');
    $html_even02idcead=f1903_HTMLComboV2_even02idcead($objDB, $objCombos, '', $aParametros[0]);
    $objDB->CerrarConexion();
    $objResponse=new xajaxResponse();
    $objResponse->assign('div_even02idcead', 'innerHTML', $html_even02idcead);
    $objResponse->call('paginarf1903');
    return $objResponse;
}
function f1903_HTMLComboV2_even02idcead($objDB, $objCombos, $valor, $vreven02idzona){
    require './app.php';
    $mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
    if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
    require $mensajes_todas;
    $sCondi='';
    $sCondi2='';
    $sSQL1='';
    //@@ Se debe arreglar la condicion..
    $sCondi='unad24idzona="'.$vreven02idzona.'"';
    if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
    $objCombos->nuevo('even02idcead', $valor, true, '{'.$ETI['msg_seleccione'].'}');
    $objCombos->sAccion='paginarf1903()';
    $sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi.' ORDER BY unad24nombre';
    $res=$objCombos->html($sSQL, $objDB);
    return $res;
}
function f1903_Comboeven02idzona($aParametros){
    $_SESSION['u_ultimominuto']=iminutoavance();
    if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
    require './app.php';
    $objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
    if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
    $objDB->xajax();
    $objCombos=new clsHtmlCombos('n');
    $html_even02idzona=f1903_HTMLComboV2_even02idzona($objDB, $objCombos, '');
    $objDB->CerrarConexion();
    $objResponse=new xajaxResponse();
    $objResponse->assign('div_even02idzona', 'innerHTML', $html_even02idzona);
    $objResponse->call('paginarf1903');
    return $objResponse;
}
function f1903_HTMLComboV2_even02idzona($objDB, $objCombos, $valor){
    require './app.php';
    $mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
    if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
    require $mensajes_todas;
    $sCondi='';
    $sSQL1='';
    $objCombos->nuevo('even02idzona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
    $objCombos->sAccion='carga_combo_even02idcead()';
    $sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
    $res=$objCombos->html($sSQL, $objDB);
    return $res;
}
?>