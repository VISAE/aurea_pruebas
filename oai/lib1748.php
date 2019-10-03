<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.19.7c jueves, 25 de enero de 2018
--- 1748 ofer48responsablesvimep
*/
function f1748_HTMLComboV2_ofer48idperaca($objdb, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ofer48idperaca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$res=$objCombos->html('SELECT exte02id AS id, CONCAT(CASE exte02vigente WHEN "S" THEN "" ELSE "[" END, exte02nombre," {",exte02id,"} ",CASE exte02vigente WHEN "S" THEN "" ELSE " - INACTIVO]" END) AS nombre FROM exte02per_aca ORDER BY exte02vigente DESC, exte02id DESC', $objdb);
	return $res;
	}
function f1748_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$ofer48idperaca=numeros_validar($datos[1]);
	if ($ofer48idperaca==''){$bHayLlave=false;}
	$ofer48idgestor=numeros_validar($datos[2]);
	if ($ofer48idgestor==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sql='SELECT ofer48idgestor FROM ofer48responsablesvimep WHERE ofer48idperaca='.$ofer48idperaca.' AND ofer48idgestor='.$ofer48idgestor.'';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)==0){$bHayLlave=false;}
		$objdb->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f1748_Busquedas($params){
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1748='lg/lg_1748_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1748)){$mensajes_1748='lg/lg_1748_es.php';}
	require $mensajes_todas;
	require $mensajes_1748;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sCampo=$params[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$paramsb=array();
	$paramsb[101]=1;
	$paramsb[102]=20;
	switch($sCampo){
		case 'ofer48idgestor':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objdb);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1748);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_1748'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f1748_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle='';
	switch($params[100]){
		case 'ofer48idgestor':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objdb);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1748_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1748='lg/lg_1748_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1748)){$mensajes_1748='lg/lg_1748_es.php';}
	require $mensajes_todas;
	require $mensajes_1748;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	//$params[103]=numeros_validar($params[103]);
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sqladd='';
	$sqladd1='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	if ($params[103]!=''){
		$sBase=trim(strtoupper($params[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T2.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	if ($params[104]!=''){$sqladd1=$sqladd1.'TB.ofer48idperaca='.$params[104].' AND ';}
	if ($params[105]!=''){$sqladd=$sqladd.' AND T2.unad11doc LIKE "%'.$params[105].'%"';}
	$sTitulos='Peraca, Gestor, Id, Meta';
	$sql='SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, TB.ofer48id, TB.ofer48meta, TB.ofer48idperaca, TB.ofer48idgestor, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
FROM ofer48responsablesvimep AS TB, exte02per_aca AS T1, unad11terceros AS T2 
WHERE '.$sqladd1.' TB.ofer48idperaca=T1.exte02id AND TB.ofer48idgestor=T2.unad11id '.$sqladd.'
ORDER BY TB.ofer48idperaca, TB.ofer48idgestor';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1748" name="consulta_1748" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1748" name="titulos_1748" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1748: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1748" name="paginaf1748" type="hidden" value="'.$pagina.'"/><input id="lppf1748" name="lppf1748" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['ofer48idperaca'].'</b></td>
<td colspan="2"><b>'.$ETI['ofer48idgestor'].'</b></td>
<td><b>'.$ETI['ofer48meta'].'</b></td>
<td align="right">
'.html_paginador('paginaf1748', $registros, $lineastabla, $pagina, 'paginarf1748()').'
'.html_lpp('lppf1748', $lineastabla, 'paginarf1748()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1748('.$filadet['ofer48id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer48meta'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1748_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1748_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1748detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1748_db_CargarPadre($DATA, $objdb, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['ofer48idgestor_td']=$APP->tipo_doc;
	$DATA['ofer48idgestor_doc']='';
	if ($DATA['paso']==1){
		$sqlcondi='ofer48idperaca='.$DATA['ofer48idperaca'].' AND ofer48idgestor="'.$DATA['ofer48idgestor'].'"';
		}else{
		$sqlcondi='ofer48id='.$DATA['ofer48id'].'';
		}
	$sql='SELECT * FROM ofer48responsablesvimep WHERE '.$sqlcondi;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$DATA['ofer48idperaca']=$fila['ofer48idperaca'];
		$DATA['ofer48idgestor']=$fila['ofer48idgestor'];
		$DATA['ofer48id']=$fila['ofer48id'];
		$DATA['ofer48meta']=$fila['ofer48meta'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta1748']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1748_db_GuardarV2($DATA, $objdb, $bDebug=false){
	$icodmodulo=1748;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1748='lg/lg_1748_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1748)){$mensajes_1748='lg/lg_1748_es.php';}
	require $mensajes_todas;
	require $mensajes_1748;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['ofer48idperaca'])==0){$DATA['ofer48idperaca']='';}
	if (isset($DATA['ofer48idgestor'])==0){$DATA['ofer48idgestor']='';}
	if (isset($DATA['ofer48id'])==0){$DATA['ofer48id']='';}
	if (isset($DATA['ofer48meta'])==0){$DATA['ofer48meta']='';}
	*/
	$DATA['ofer48idperaca']=numeros_validar($DATA['ofer48idperaca']);
	$DATA['ofer48meta']=numeros_validar($DATA['ofer48meta']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['ofer48meta']==''){$DATA['ofer48meta']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['ofer48meta']==''){$sError=$ERR['ofer48meta'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['ofer48idgestor']==0){$sError=$ERR['ofer48idgestor'];}
	if ($DATA['ofer48idperaca']==''){$sError=$ERR['ofer48idperaca'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['ofer48idgestor_td'], $DATA['ofer48idgestor_doc'], $objdb, 'El tercero Gestor ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['ofer48idgestor'], $objdb);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sql='SELECT ofer48idperaca FROM ofer48responsablesvimep WHERE ofer48idperaca='.$DATA['ofer48idperaca'].' AND ofer48idgestor="'.$DATA['ofer48idgestor'].'"';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['ofer48id']=tabla_consecutivo('ofer48responsablesvimep','ofer48id', '', $objdb);
			if ($DATA['ofer48id']==-1){$sError=$objdb->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos1748='ofer48idperaca, ofer48idgestor, ofer48id, ofer48meta';
			$sValores1748=''.$DATA['ofer48idperaca'].', '.$DATA['ofer48idgestor'].', '.$DATA['ofer48id'].', '.$DATA['ofer48meta'].'';
			if ($APP->utf8==1){
				$sql='INSERT INTO ofer48responsablesvimep ('.$sCampos1748.') VALUES ('.utf8_encode($sValores1748).');';
				$sdetalle=$sCampos1748.'['.utf8_encode($sValores1748).']';
				}else{
				$sql='INSERT INTO ofer48responsablesvimep ('.$sCampos1748.') VALUES ('.$sValores1748.');';
				$sdetalle=$sCampos1748.'['.$sValores1748.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='ofer48meta';
			$sdato[1]=$DATA['ofer48meta'];
			$numcmod=1;
			$sWhere='ofer48id='.$DATA['ofer48id'].'';
			$sql='SELECT * FROM ofer48responsablesvimep WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filabase=$objdb->sf($result);
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
					$sql='UPDATE ofer48responsablesvimep SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sql='UPDATE ofer48responsablesvimep SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [1748] ..<!-- '.$sql.' -->';
				if ($idaccion==2){$DATA['ofer48id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 1748 '.$sql.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['ofer48id'], $sdetalle, $objdb);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1748_db_Eliminar($ofer48id, $objdb, $bDebug=false){
	$icodmodulo=1748;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1748='lg/lg_1748_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1748)){$mensajes_1748='lg/lg_1748_es.php';}
	require $mensajes_todas;
	require $mensajes_1748;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$ofer48id=numeros_validar($ofer48id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sql='SELECT * FROM ofer48responsablesvimep WHERE ofer48id='.$ofer48id.'';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$filabase=$objdb->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$ofer48id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sql='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1748';
		$tablaor=$objdb->ejecutasql($sql);
		while ($filaor=$objdb->sf($tablaor)){
			$sql='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['ofer48id'].' LIMIT 0, 1';
			$tabla=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		$sWhere='ofer48id='.$ofer48id.'';
		//$sWhere='ofer48idgestor="'.$filabase['ofer48idgestor'].'" AND ofer48idperaca='.$filabase['ofer48idperaca'].'';
		$sql='DELETE FROM ofer48responsablesvimep WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $ofer48id, $sWhere, $objdb);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f1748_TituloBusqueda(){
	return 'Busqueda de Gestores';
	}
function f1748_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b1748nombre" name="b1748nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f1748_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b1748nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f1748_TablaDetalleBusquedas($params, $objdb){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1748='lg/lg_1748_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1748)){$mensajes_1748='lg/lg_1748_es.php';}
	require $mensajes_todas;
	require $mensajes_1748;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sqladd='';
	$sqladd1='';
	//if ($params[103]!=''){$sqladd1=$sqladd1.'TB.campo2 LIKE "%'.$params[103].'%" AND ';}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Peraca, Gestor, Id, Meta';
	$sql='SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, TB.ofer48id, TB.ofer48meta, TB.ofer48idperaca, TB.ofer48idgestor, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
FROM ofer48responsablesvimep AS TB, exte02per_aca AS T1, unad11terceros AS T2 
WHERE '.$sqladd1.' TB.ofer48idperaca=T1.exte02id AND TB.ofer48idgestor=T2.unad11id '.$sqladd.'
ORDER BY TB.ofer48idperaca, TB.ofer48idgestor';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1748" name="paginaf1748" type="hidden" value="'.$pagina.'"/><input id="lppf1748" name="lppf1748" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['ofer48idperaca'].'</b></td>
<td colspan="2"><b>'.$ETI['ofer48idgestor'].'</b></td>
<td><b>'.$ETI['ofer48meta'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['ofer48id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer48meta'].$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>