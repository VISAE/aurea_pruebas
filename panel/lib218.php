<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Wednesday, August 14, 2019
--- 218 unae18rangoedad
*/
/** Archivo lib218.php.
* Libreria 218 unae18rangoedad.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Wednesday, August 14, 2019
*/
function f218_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unae18consec=numeros_validar($datos[1]);
	if ($unae18consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unae18consec FROM unae18rangoedad WHERE unae18consec='.$unae18consec.'';
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
function f218_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_218='lg/lg_218_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_218)){$mensajes_218='lg/lg_218_es.php';}
	require $mensajes_todas;
	require $mensajes_218;
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
		}
	$sTitulo='<h2>'.$ETI['titulo_218'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f218_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f218_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_218='lg/lg_218_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_218)){$mensajes_218='lg/lg_218_es.php';}
	require $mensajes_todas;
	require $mensajes_218;
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
		return array($sLeyenda.'<input id="paginaf218" name="paginaf218" type="hidden" value="'.$pagina.'"/><input id="lppf218" name="lppf218" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='1';
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
	$sTitulos='Consec, Id, Estado, Titulo';
	$sSQL='SELECT TB.unae18consec, TB.unae18id, TB.unae18estado, TB.unae18titulo 
FROM unae18rangoedad AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.unae18consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_218" name="consulta_218" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_218" name="titulos_218" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 218: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf218" name="paginaf218" type="hidden" value="'.$pagina.'"/><input id="lppf218" name="lppf218" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae18consec'].'</b></td>
<td><b>'.$ETI['unae18estado'].'</b></td>
<td><b>'.$ETI['unae18titulo'].'</b></td>
<td align="right">
'.html_paginador('paginaf218', $registros, $lineastabla, $pagina, 'paginarf218()').'
'.html_lpp('lppf218', $lineastabla, 'paginarf218()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if ($filadet['unae18estado']!='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_unae18estado=$ETI['msg_abierto'];
		if ($filadet['unae18estado']=='S'){$et_unae18estado=$ETI['msg_cerrado'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf218('.$filadet['unae18id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unae18consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae18estado.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae18titulo']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f218_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f218_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f218detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f218_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='unae18consec='.$DATA['unae18consec'].'';
		}else{
		$sSQLcondi='unae18id='.$DATA['unae18id'].'';
		}
	$sSQL='SELECT * FROM unae18rangoedad WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['unae18consec']=$fila['unae18consec'];
		$DATA['unae18id']=$fila['unae18id'];
		$DATA['unae18estado']=$fila['unae18estado'];
		$DATA['unae18titulo']=$fila['unae18titulo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta218']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f218_Cerrar($unae18id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	$aEdades=array();
	for($k=0;$k<151;$k++){
		$aEdades[$k]=0;
		}
	//Cargar los rangos...
	$sSQL='SELECT unae19id, unae19base, unae19techo FROM unae19rango WHERE unae19idrangoedad='.$unae18id.' ORDER BY unae19techo DESC';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		for($k=$fila['unae19base'];$k<=$fila['unae19techo'];$k++){
			$aEdades[$k]=$fila['unae19id'];
			}
		}
	//Insertar los rangos... o actualizarlos.
	$scampos='unae20idrangoedad, unae20edad, unae20id, unae20idrango';
	$unae20id=tabla_consecutivo('unae20rangosdist', 'unae20id', '', $objDB);
	for($k=0;$k<151;$k++){
		$sSQL='SELECT unae20id, unae20idrango FROM unae20rangosdist WHERE unae20idrangoedad='.$unae18id.' AND unae20edad='.$k.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			$svalores=''.$unae18id.', '.$k.', '.$unae20id.', '.$aEdades[$k].'';
			$sSQL='INSERT INTO unae20rangosdist ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objDB->ejecutasql($sSQL);
			$unae20id++;
			}else{
			$fila=$objDB->sf($tabla);
			if ($fila['unae20idrango']!=$aEdades[$k]){
				$sSQL='UPDATE unae20rangosdist SET unae20idrango='.$aEdades[$k].' WHERE unae20id='.$fila['unae20id'].'';
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		}
	return array($sInfo, $sDebug);
	}
function f218_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=218;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_218='lg/lg_218_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_218)){$mensajes_218='lg/lg_218_es.php';}
	require $mensajes_todas;
	require $mensajes_218;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unae18consec'])==0){$DATA['unae18consec']='';}
	if (isset($DATA['unae18id'])==0){$DATA['unae18id']='';}
	if (isset($DATA['unae18estado'])==0){$DATA['unae18estado']='';}
	if (isset($DATA['unae18titulo'])==0){$DATA['unae18titulo']='';}
	*/
	$DATA['unae18consec']=numeros_validar($DATA['unae18consec']);
	$DATA['unae18titulo']=htmlspecialchars(trim($DATA['unae18titulo']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['unae18estado']==''){$DATA['unae18estado']='N';}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if ($DATA['unae18estado']=='S'){
		if ($DATA['unae18titulo']==''){$sError=$ERR['unae18titulo'].$sSepara.$sError;}
		if ($sError!=''){$DATA['unae18estado']='N';}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Tiene un cerrado.
	if ($DATA['unae18estado']=='S'){
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['unae18estado']='N';
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['unae18consec']==''){
				$DATA['unae18consec']=tabla_consecutivo('unae18rangoedad', 'unae18consec', '', $objDB);
				if ($DATA['unae18consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['unae18consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT unae18consec FROM unae18rangoedad WHERE unae18consec='.$DATA['unae18consec'].'';
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
			$DATA['unae18id']=tabla_consecutivo('unae18rangoedad','unae18id', '', $objDB);
			if ($DATA['unae18id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['unae18estado']='N';
			$sCampos218='unae18consec, unae18id, unae18estado, unae18titulo';
			$sValores218=''.$DATA['unae18consec'].', '.$DATA['unae18id'].', "'.$DATA['unae18estado'].'", "'.$DATA['unae18titulo'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae18rangoedad ('.$sCampos218.') VALUES ('.utf8_encode($sValores218).');';
				$sdetalle=$sCampos218.'['.utf8_encode($sValores218).']';
				}else{
				$sSQL='INSERT INTO unae18rangoedad ('.$sCampos218.') VALUES ('.$sValores218.');';
				$sdetalle=$sCampos218.'['.$sValores218.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='unae18estado';
			$scampo[2]='unae18titulo';
			$sdato[1]=$DATA['unae18estado'];
			$sdato[2]=$DATA['unae18titulo'];
			$numcmod=2;
			$sWhere='unae18id='.$DATA['unae18id'].'';
			$sSQL='SELECT * FROM unae18rangoedad WHERE '.$sWhere;
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
					$sSQL='UPDATE unae18rangoedad SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unae18rangoedad SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [218] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['unae18id']='';}
				$DATA['paso']=$DATA['paso']-10;
				$bCerrando=false;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 218 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['unae18id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		$bCerrando=false;
		}
	$sInfoCierre='';
	if ($bCerrando){
		list($sErrorCerrando, $sDebugCerrar)=f218_Cerrar($DATA['unae18id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f218_db_Eliminar($unae18id, $objDB, $bDebug=false){
	$iCodModulo=218;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_218='lg/lg_218_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_218)){$mensajes_218='lg/lg_218_es.php';}
	require $mensajes_todas;
	require $mensajes_218;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$unae18id=numeros_validar($unae18id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM unae18rangoedad WHERE unae18id='.$unae18id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unae18id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unae19idrangoedad FROM unae19rango WHERE unae19idrangoedad='.$filabase['unae18id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Rangos creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unae20idrangoedad FROM unae20rangosdist WHERE unae20idrangoedad='.$filabase['unae18id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Distribucion creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=218';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['unae18id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM unae19rango WHERE unae19idrangoedad='.$filabase['unae18id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM unae20rangosdist WHERE unae20idrangoedad='.$filabase['unae18id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='unae18id='.$unae18id.'';
		//$sWhere='unae18consec='.$filabase['unae18consec'].'';
		$sSQL='DELETE FROM unae18rangoedad WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae18id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f218_TituloBusqueda(){
	return 'Busqueda de Rangos de edad';
	}
function f218_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b218nombre" name="b218nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f218_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b218nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f218_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_218='lg/lg_218_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_218)){$mensajes_218='lg/lg_218_es.php';}
	require $mensajes_todas;
	require $mensajes_218;
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
		return array($sLeyenda.'<input id="paginaf218" name="paginaf218" type="hidden" value="'.$pagina.'"/><input id="lppf218" name="lppf218" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='1';
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
	$sTitulos='Consec, Id, Estado, Titulo';
	$sSQL='SELECT TB.unae18consec, TB.unae18id, TB.unae18estado, TB.unae18titulo 
FROM unae18rangoedad AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.unae18consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf218" name="paginaf218" type="hidden" value="'.$pagina.'"/><input id="lppf218" name="lppf218" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae18consec'].'</b></td>
<td><b>'.$ETI['unae18estado'].'</b></td>
<td><b>'.$ETI['unae18titulo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['unae18id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_unae18estado=$ETI['msg_abierto'];
		if ($filadet['unae18estado']=='S'){$et_unae18estado=$ETI['msg_cerrado'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['unae18consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae18estado.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae18titulo']).$sSufijo.'</td>
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