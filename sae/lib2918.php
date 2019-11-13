<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.7 Tuesday, October 22, 2019
--- 2918 
*/
function f2918_HTMLComboV2_plab18ubidep($objDB, $objCombos, $valor, $vrplab18ubipais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad19codpais="'.$vrplab18ubipais.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('plab18ubidep', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_plab18ubiciudad()';
	$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2918_Comboplab18ubidep($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_plab18ubidep=f2918_HTMLComboV2_plab18ubidep($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab18ubidep', 'innerHTML', $html_plab18ubidep);
	return $objResponse;
	}
function f2918_HTMLComboV2_plab18ubiciudad($objDB, $objCombos, $valor, $vrplab18ubidep){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad20coddepto="'.$vrplab18ubidep.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('plab18ubiciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2918_Comboplab18ubiciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_plab18ubiciudad=f2918_HTMLComboV2_plab18ubiciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab18ubiciudad', 'innerHTML', $html_plab18ubiciudad);
	return $objResponse;
	}
function f2918_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2918;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2918='lg/lg_2918_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2918)){$mensajes_2918='lg/lg_2918_es.php';}
	require $mensajes_todas;
	require $mensajes_2918;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$plab18idoferta=numeros_validar($valores[1]);
	$plab18consecutivo=numeros_validar($valores[2]);
	$plab18id=numeros_validar($valores[3], true);
	$plab18ubipais=htmlspecialchars(trim($valores[4]));
	$plab18ubidep=htmlspecialchars(trim($valores[5]));
	$plab18ubiciudad=htmlspecialchars(trim($valores[6]));
	$sSepara=', ';
	if ($plab18ubiciudad==''){$sError=$ERR['plab18ubiciudad'].$sSepara.$sError;}
	if ($plab18ubidep==''){$sError=$ERR['plab18ubidep'].$sSepara.$sError;}
	if ($plab18ubipais==''){$sError=$ERR['plab18ubipais'].$sSepara.$sError;}
	//if ($plab18id==''){$sError=$ERR['plab18id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($plab18consecutivo==''){$sError=$ERR['plab18consecutivo'].$sSepara.$sError;}//CONSECUTIVO
	if ($plab18idoferta==''){$sError=$ERR['plab18idoferta'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$plab18id==0){
			if ((int)$plab18consecutivo==0){
				$plab18consecutivo=tabla_consecutivo('plab18ofertaciudad', 'plab18consecutivo', 'plab18idoferta='.$plab18idoferta.'', $objDB);
				if ($plab18consecutivo==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT plab18idoferta FROM plab18ofertaciudad WHERE plab18idoferta='.$plab18idoferta.' AND plab18consecutivo='.$plab18consecutivo.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$plab18id=tabla_consecutivo('plab18ofertaciudad', 'plab18id', '', $objDB);
				if ($plab18id==-1){$sError=$objDB->serror;}
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
			$scampos='plab18idoferta, plab18consecutivo, plab18id, plab18ubipais, plab18ubidep, 
plab18ubiciudad';
			$svalores=''.$plab18idoferta.', '.$plab18consecutivo.', '.$plab18id.', "'.$plab18ubipais.'", "'.$plab18ubidep.'", 
"'.$plab18ubiciudad.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab18ofertaciudad ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO plab18ofertaciudad ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $plab18id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2918[1]='plab18ubipais';
			$scampo2918[2]='plab18ubidep';
			$scampo2918[3]='plab18ubiciudad';
			$svr2918[1]=$plab18ubipais;
			$svr2918[2]=$plab18ubidep;
			$svr2918[3]=$plab18ubiciudad;
			$inumcampos=3;
			$sWhere='plab18id='.$plab18id.'';
			//$sWhere='plab18idoferta='.$plab18idoferta.' AND plab18consecutivo='.$plab18consecutivo.'';
			$sSQL='SELECT * FROM plab18ofertaciudad WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2918[$k]]!=$svr2918[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2918[$k].'="'.$svr2918[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE plab18ofertaciudad SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE plab18ofertaciudad SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $plab18id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $plab18id, $sDebug);
	}
function f2918_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2918;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2918='lg/lg_2918_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2918)){$mensajes_2918='lg/lg_2918_es.php';}
	require $mensajes_todas;
	require $mensajes_2918;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$plab18idoferta=numeros_validar($aParametros[1]);
	$plab18consecutivo=numeros_validar($aParametros[2]);
	$plab18id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2918';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$plab18id.' LIMIT 0, 1';
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
		$sWhere='plab18id='.$plab18id.'';
		//$sWhere='plab18idoferta='.$plab18idoferta.' AND plab18consecutivo='.$plab18consecutivo.'';
		$sSQL='DELETE FROM plab18ofertaciudad WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2918 }.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab18id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2918_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2918='lg/lg_2918_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2918)){$mensajes_2918='lg/lg_2918_es.php';}
	require $mensajes_todas;
	require $mensajes_2918;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$plab10id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM plab10oferta WHERE plab10id='.$plab10id;
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
	$sTitulos='Oferta, Consecutivo, Id, Ubipais, Ubidep, Ubiciudad';
	$sSQL='SELECT TB.plab18idoferta, TB.plab18consecutivo, TB.plab18id, T4.unad18nombre, T5.unad19nombre, T6.unad20nombre, TB.plab18ubipais, TB.plab18ubidep, TB.plab18ubiciudad 
FROM plab18ofertaciudad AS TB, unad18pais AS T4, unad19depto AS T5, unad20ciudad AS T6 
WHERE '.$sSQLadd1.' TB.plab18idoferta='.$plab10id.' AND TB.plab18ubipais=T4.unad18codigo AND TB.plab18ubidep=T5.unad19codigo AND TB.plab18ubiciudad=T6.unad20codigo '.$sSQLadd.'
ORDER BY TB.plab18consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2918" name="consulta_2918" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2918" name="titulos_2918" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2918: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2918" name="paginaf2918" type="hidden" value="'.$pagina.'"/><input id="lppf2918" name="lppf2918" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab18consecutivo'].'</b></td>
<td><b>'.$ETI['plab18ubipais'].'</b></td>
<td><b>'.$ETI['plab18ubidep'].'</b></td>
<td><b>'.$ETI['plab18ubiciudad'].'</b></td>
<td align="right">
'.html_paginador('paginaf2918', $registros, $lineastabla, $pagina, 'paginarf2918()').'
'.html_lpp('lppf2918', $lineastabla, 'paginarf2918()').'
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
		$et_plab18consecutivo=$sPrefijo.$filadet['plab18consecutivo'].$sSufijo;
		$et_plab18ubipais=$sPrefijo.cadena_notildes($filadet['unad18nombre']).$sSufijo;
		$et_plab18ubidep=$sPrefijo.cadena_notildes($filadet['unad19nombre']).$sSufijo;
		$et_plab18ubiciudad=$sPrefijo.cadena_notildes($filadet['unad20nombre']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2918('.$filadet['plab18id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_plab18consecutivo.'</td>
<td>'.$et_plab18ubipais.'</td>
<td>'.$et_plab18ubidep.'</td>
<td>'.$et_plab18ubiciudad.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2918_Clonar($plab18idoferta, $plab18idofertaPadre, $objDB){
	$sError='';
	$plab18consecutivo=tabla_consecutivo('plab18ofertaciudad', 'plab18consecutivo', 'plab18idoferta='.$plab18idoferta.'', $objDB);
	if ($plab18consecutivo==-1){$sError=$objDB->serror;}
	$plab18id=tabla_consecutivo('plab18ofertaciudad', 'plab18id', '', $objDB);
	if ($plab18id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2918='plab18idoferta, plab18consecutivo, plab18id, plab18ubipais, plab18ubidep, plab18ubiciudad';
		$sValores2918='';
		$sSQL='SELECT * FROM plab18ofertaciudad WHERE plab18idoferta='.$plab18idofertaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2918!=''){$sValores2918=$sValores2918.', ';}
			$sValores2918=$sValores2918.'('.$plab18idoferta.', '.$plab18consecutivo.', '.$plab18id.', "'.$fila['plab18ubipais'].'", "'.$fila['plab18ubidep'].'", "'.$fila['plab18ubiciudad'].'")';
			$plab18consecutivo++;
			$plab18id++;
			}
		if ($sValores2918!=''){
			$sSQL='INSERT INTO plab18ofertaciudad('.$sCampos2918.') VALUES '.$sValores2918.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2918  XAJAX 
function f2918_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $plab18id, $sDebugGuardar)=f2918_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2918_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2918detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2918('.$plab18id.')');
			//}else{
			$objResponse->call('limpiaf2918');
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
function f2918_Traer($aParametros){
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
		$plab18idoferta=numeros_validar($aParametros[1]);
		$plab18consecutivo=numeros_validar($aParametros[2]);
		if (($plab18idoferta!='')&&($plab18consecutivo!='')){$besta=true;}
		}else{
		$plab18id=$aParametros[103];
		if ((int)$plab18id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'plab18idoferta='.$plab18idoferta.' AND plab18consecutivo='.$plab18consecutivo.'';
			}else{
			$sSQLcondi=$sSQLcondi.'plab18id='.$plab18id.'';
			}
		$sSQL='SELECT * FROM plab18ofertaciudad WHERE '.$sSQLcondi;
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
		$plab18consecutivo_nombre='';
		$html_plab18consecutivo=html_oculto('plab18consecutivo', $fila['plab18consecutivo'], $plab18consecutivo_nombre);
		$objResponse->assign('div_plab18consecutivo', 'innerHTML', $html_plab18consecutivo);
		$plab18id_nombre='';
		$html_plab18id=html_oculto('plab18id', $fila['plab18id'], $plab18id_nombre);
		$objResponse->assign('div_plab18id', 'innerHTML', $html_plab18id);
		$objResponse->assign('plab18ubipais', 'value', $fila['plab18ubipais']);
		$objResponse->assign('plab18ubidep', 'value', $fila['plab18ubidep']);
		$objResponse->assign('plab18ubiciudad', 'value', $fila['plab18ubiciudad']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2918','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('plab18consecutivo', 'value', $plab18consecutivo);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$plab18id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2918_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2918_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2918_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2918detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2918');
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
function f2918_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2918_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2918detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2918_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_plab18consecutivo='<input id="plab18consecutivo" name="plab18consecutivo" type="text" value="" onchange="revisaf2918()" class="cuatro"/>';
	$html_plab18id='<input id="plab18id" name="plab18id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab18consecutivo','innerHTML', $html_plab18consecutivo);
	$objResponse->assign('div_plab18id','innerHTML', $html_plab18id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>