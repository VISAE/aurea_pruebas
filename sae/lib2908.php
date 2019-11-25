<?php
/*
--- © Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - UNAD - 2019 ---
--- samicial@puntosoftware.net - http://www.puntosoftware.net 
// --- Desarrollo por encargo para la UNAD Contrato OS-2019-000130 
// --- Conforme a la metodología de desarrollo de la plataforma AUREA.
--- Modelo Versión 2.23.7 Friday, October 18, 2019
--- 2908 plab08emprbolsempleo
*/
/** Archivo lib2908.php.
* Libreria 2908 plab08emprbolsempleo.
* @author Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - samicial@puntosoftware.net
* @date Friday, October 18, 2019
*/
function f2908_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$plab08idtercero=numeros_validar($datos[1]);
	if ($plab08idtercero==''){$bHayLlave=false;}
	$plab08consecutivo=numeros_validar($datos[2]);
	if ($plab08consecutivo==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT plab08consecutivo FROM plab08emprbolsempleo WHERE plab08idtercero='.$plab08idtercero.' AND plab08consecutivo='.$plab08consecutivo.'';
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)==0){$bHayLlave=false;}
		$objDB->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f2908_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2908='lg/lg_2908_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2908)){$mensajes_2908='lg/lg_2908_es.php';}
	require $mensajes_todas;
	require $mensajes_2908;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sCampo=$aParametros[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$aParametrosB=array();
	$aParametrosB[101]=1;
	$aParametrosB[102]=20;
	switch($sCampo){
		case 'plab08idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2908);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2908'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2908_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'plab08idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2908_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2908='lg/lg_2908_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2908)){$mensajes_2908='lg/lg_2908_es.php';}
	require $mensajes_todas;
	require $mensajes_2908;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2908" name="paginaf2908" type="hidden" value="'.$pagina.'"/><input id="lppf2908" name="lppf2908" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
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
	$sTitulos='Tercero, Consecutivo, Id, Activo, Fechainicontr, Fechafincontr';
	$sSQL='SELECT T1.unad11razonsocial AS C1_nombre, TB.plab08consecutivo, TB.plab08id, TB.plab08activo, TB.plab08fechainicontr, TB.plab08fechafincontr, TB.plab08idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc 
FROM plab08emprbolsempleo AS TB, unad11terceros AS T1 
WHERE '.$sSQLadd1.' TB.plab08idtercero=T1.unad11id '.$sSQLadd.'
ORDER BY TB.plab08idtercero, TB.plab08consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2908" name="consulta_2908" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2908" name="titulos_2908" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2908: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2908" name="paginaf2908" type="hidden" value="'.$pagina.'"/><input id="lppf2908" name="lppf2908" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['plab08idtercero'].'</b></td>
<td><b>'.$ETI['plab08consecutivo'].'</b></td>
<td><b>'.$ETI['plab08activo'].'</b></td>
<td><b>'.$ETI['plab08fechainicontr'].'</b></td>
<td><b>'.$ETI['plab08fechafincontr'].'</b></td>
<td align="right">
'.html_paginador('paginaf2908', $registros, $lineastabla, $pagina, 'paginarf2908()').'
'.html_lpp('lppf2908', $lineastabla, 'paginarf2908()').'
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
		$et_plab08activo=$ETI['no'];
		if ($filadet['plab08activo']=='S'){$et_plab08activo=$ETI['si'];}
		$et_plab08fechainicontr='';
		if ($filadet['plab08fechainicontr']!=0){$et_plab08fechainicontr=fecha_desdenumero($filadet['plab08fechainicontr']);}
		$et_plab08fechafincontr='';
		if ($filadet['plab08fechafincontr']!=0){$et_plab08fechafincontr=fecha_desdenumero($filadet['plab08fechafincontr']);}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2908('.$filadet['plab08id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C1_td'].' '.$filadet['C1_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C1_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab08consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab08activo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab08fechainicontr.$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab08fechafincontr.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2908_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2908_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2908detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2908_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['plab08idtercero_td']=$APP->tipo_doc;
	$DATA['plab08idtercero_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='plab08idtercero="'.$DATA['plab08idtercero'].'" AND plab08consecutivo='.$DATA['plab08consecutivo'].'';
		}else{
		$sSQLcondi='plab08id='.$DATA['plab08id'].'';
		}
	$sSQL='SELECT * FROM plab08emprbolsempleo WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['plab08idtercero']=$fila['plab08idtercero'];
		$DATA['plab08consecutivo']=$fila['plab08consecutivo'];
		$DATA['plab08id']=$fila['plab08id'];
		$DATA['plab08activo']=$fila['plab08activo'];
		$DATA['plab08fechainicontr']=$fila['plab08fechainicontr'];
		$DATA['plab08fechafincontr']=$fila['plab08fechafincontr'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2908']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2908_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2908;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2908='lg/lg_2908_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2908)){$mensajes_2908='lg/lg_2908_es.php';}
	require $mensajes_todas;
	require $mensajes_2908;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['plab08idtercero'])==0){$DATA['plab08idtercero']='';}
	if (isset($DATA['plab08consecutivo'])==0){$DATA['plab08consecutivo']='';}
	if (isset($DATA['plab08id'])==0){$DATA['plab08id']='';}
	if (isset($DATA['plab08activo'])==0){$DATA['plab08activo']='';}
	if (isset($DATA['plab08fechainicontr'])==0){$DATA['plab08fechainicontr']='';}
	if (isset($DATA['plab08fechafincontr'])==0){$DATA['plab08fechafincontr']='';}
	*/
	$DATA['plab08consecutivo']=numeros_validar($DATA['plab08consecutivo']);
	$DATA['plab08activo']=htmlspecialchars(trim($DATA['plab08activo']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['plab08fechafincontr']==0){
			//$DATA['plab08fechafincontr']=fecha_DiaMod();
			$sError=$ERR['plab08fechafincontr'].$sSepara.$sError;
			}
		if ($DATA['plab08fechainicontr']==0){
			//$DATA['plab08fechainicontr']=fecha_DiaMod();
			$sError=$ERR['plab08fechainicontr'].$sSepara.$sError;
			}
		if ($DATA['plab08activo']==''){$sError=$ERR['plab08activo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['plab08idtercero']==0){$sError=$ERR['plab08idtercero'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['plab08idtercero_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['plab08idtercero_td'], $DATA['plab08idtercero_doc'], $objDB, 'El tercero Tercero ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['plab08idtercero'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['plab08consecutivo']==''){
				$DATA['plab08consecutivo']=tabla_consecutivo('plab08emprbolsempleo', 'plab08consecutivo', 'plab08idtercero='.$DATA['plab08idtercero'].'', $objDB);
				if ($DATA['plab08consecutivo']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['plab08consecutivo']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM plab08emprbolsempleo WHERE plab08idtercero="'.$DATA['plab08idtercero'].'" AND plab08consecutivo='.$DATA['plab08consecutivo'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['plab08id']=tabla_consecutivo('plab08emprbolsempleo','plab08id', '', $objDB);
			if ($DATA['plab08id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$plab08fechainicontr=fecha_DiaMod();
			$plab08fechafincontr=fecha_DiaMod();
			$sCampos2908='plab08idtercero, plab08consecutivo, plab08id, plab08activo, plab08fechainicontr, plab08fechafincontr';
			$sValores2908=''.$DATA['plab08idtercero'].', '.$DATA['plab08consecutivo'].', '.$DATA['plab08id'].', "'.$DATA['plab08activo'].'", "'.$DATA['plab08fechainicontr'].'", "'.$DATA['plab08fechafincontr'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab08emprbolsempleo ('.$sCampos2908.') VALUES ('.utf8_encode($sValores2908).');';
				$sdetalle=$sCampos2908.'['.utf8_encode($sValores2908).']';
				}else{
				$sSQL='INSERT INTO plab08emprbolsempleo ('.$sCampos2908.') VALUES ('.$sValores2908.');';
				$sdetalle=$sCampos2908.'['.$sValores2908.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='plab08activo';
			$scampo[2]='plab08fechainicontr';
			$scampo[3]='plab08fechafincontr';
			$sdato[1]=$DATA['plab08activo'];
			$sdato[2]=$DATA['plab08fechainicontr'];
			$sdato[3]=$DATA['plab08fechafincontr'];
			$numcmod=3;
			$sWhere='plab08id='.$DATA['plab08id'].'';
			$sSQL='SELECT * FROM plab08emprbolsempleo WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE plab08emprbolsempleo SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE plab08emprbolsempleo SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2908] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['plab08id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2908 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['plab08id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		if ($bQuitarCodigo){
			$DATA['plab08consecutivo']='';
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2908_db_Eliminar($plab08id, $objDB, $bDebug=false){
	$iCodModulo=2908;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2908='lg/lg_2908_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2908)){$mensajes_2908='lg/lg_2908_es.php';}
	require $mensajes_todas;
	require $mensajes_2908;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$plab08id=numeros_validar($plab08id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM plab08emprbolsempleo WHERE plab08id='.$plab08id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$plab08id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2908';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['plab08id'].' LIMIT 0, 1';
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
		$sWhere='plab08id='.$plab08id.'';
		//$sWhere='plab08consecutivo='.$filabase['plab08consecutivo'].' AND plab08idtercero="'.$filabase['plab08idtercero'].'"';
		$sSQL='DELETE FROM plab08emprbolsempleo WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab08id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2908_TituloBusqueda(){
	return 'Busqueda de empresa bolsa de empleo';
	}
function f2908_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2908nombre" name="b2908nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2908_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2908nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2908_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2908='lg/lg_2908_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2908)){$mensajes_2908='lg/lg_2908_es.php';}
	require $mensajes_todas;
	require $mensajes_2908;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2908" name="paginaf2908" type="hidden" value="'.$pagina.'"/><input id="lppf2908" name="lppf2908" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
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
	$sTitulos='Tercero, Consecutivo, Id, Activo, Fechainicontr, Fechafincontr';
	$sSQL='SELECT T1.unad11razonsocial AS C1_nombre, TB.plab08consecutivo, TB.plab08id, TB.plab08activo, TB.plab08fechainicontr, TB.plab08fechafincontr, TB.plab08idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc 
FROM plab08emprbolsempleo AS TB, unad11terceros AS T1 
WHERE '.$sSQLadd1.' TB.plab08idtercero=T1.unad11id '.$sSQLadd.'
ORDER BY TB.plab08idtercero, TB.plab08consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2908" name="paginaf2908" type="hidden" value="'.$pagina.'"/><input id="lppf2908" name="lppf2908" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['plab08idtercero'].'</b></td>
<td><b>'.$ETI['plab08consecutivo'].'</b></td>
<td><b>'.$ETI['plab08activo'].'</b></td>
<td><b>'.$ETI['plab08fechainicontr'].'</b></td>
<td><b>'.$ETI['plab08fechafincontr'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['plab08id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_plab08activo=$ETI['no'];
		if ($filadet['plab08activo']=='S'){$et_plab08activo=$ETI['si'];}
		$et_plab08fechainicontr='';
		if ($filadet['plab08fechainicontr']!=0){$et_plab08fechainicontr=fecha_desdenumero($filadet['plab08fechainicontr']);}
		$et_plab08fechafincontr='';
		if ($filadet['plab08fechafincontr']!=0){$et_plab08fechafincontr=fecha_desdenumero($filadet['plab08fechafincontr']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['C1_td'].' '.$filadet['C1_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C1_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab08consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab08activo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab08fechainicontr.$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab08fechafincontr.$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>