<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.6b miércoles, 5 de diciembre de 2018
--- 102 unad02modulos
*/
function f102_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unad02id=numeros_validar($datos[1]);
	if ($unad02id==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unad02id FROM unad02modulos WHERE unad02id='.$unad02id.'';
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
function f102_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_102='lg/lg_102_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_102)){$mensajes_102='lg/lg_102_es.php';}
	require $mensajes_todas;
	require $mensajes_102;
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
	$sTitulo='<h2>'.$ETI['titulo_102'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f102_HtmlBusqueda($aParametros){
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
function f102_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_102='lg/lg_102_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_102)){$mensajes_102='lg/lg_102_es.php';}
	require $mensajes_todas;
	require $mensajes_102;
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
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.unad02nombre LIKE "%'.$aParametros[103].'%"';}
	if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.unad02idsistema='.$aParametros[104].' AND ';}
	
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd1=$sSQLadd1.'TB.unad02nombre LIKE "%'.$sCadena.'%" AND ';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Id, Nombre, Sistema';
	$sSQL='SELECT TB.unad02id, TB.unad02nombre, T3.unad01nombre, TB.unad02idsistema 
FROM unad02modulos AS TB, unad01sistema AS T3 
WHERE '.$sSQLadd1.' TB.unad02idsistema=T3.unad01id '.$sSQLadd.'
ORDER BY TB.unad02id';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_102" name="consulta_102" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_102" name="titulos_102" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 102: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf102" name="paginaf102" type="hidden" value="'.$pagina.'"/><input id="lppf102" name="lppf102" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unad02id'].'</b></td>
<td><b>'.$ETI['unad02nombre'].'</b></td>
<td><b>'.$ETI['unad02idsistema'].'</b></td>
<td align="right">
'.html_paginador('paginaf102', $registros, $lineastabla, $pagina, 'paginarf102()').'
'.html_lpp('lppf102', $lineastabla, 'paginarf102()').'
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
		if ($babierta){
			$sLink='<a href="javascript:cargadato('.$filadet['unad02id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad02id'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad01nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f102_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f102_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f102detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f102_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$sSQL='SELECT * FROM unad02modulos WHERE unad02id='.$DATA['unad02id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['unad02id']=$fila['unad02id'];
		$DATA['unad02nombre']=$fila['unad02nombre'];
		$DATA['unad02idsistema']=$fila['unad02idsistema'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta102']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f102_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=102;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_102='lg/lg_102_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_102)){$mensajes_102='lg/lg_102_es.php';}
	require $mensajes_todas;
	require $mensajes_102;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['unad02id']=numeros_validar($DATA['unad02id']);
	$DATA['unad02nombre']=htmlspecialchars(trim($DATA['unad02nombre']));
	$DATA['unad02idsistema']=numeros_validar($DATA['unad02idsistema']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['unad02idsistema']==''){$DATA['unad02idsistema']=0;}
	// -- Seccion para validar los posibles causales de error.
	if ($DATA['unad02idsistema']==''){$sError=$ERR['unad02idsistema'];}
	if ($DATA['unad02nombre']==''){$sError=$ERR['unad02nombre'];}
	if ($DATA['unad02id']==''){$sError=$ERR['unad02id'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT unad02id FROM unad02modulos WHERE unad02id='.$DATA['unad02id'].'';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos102='unad02id, unad02nombre, unad02idsistema';
			$sValores102=''.$DATA['unad02id'].', "'.$DATA['unad02nombre'].'", '.$DATA['unad02idsistema'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unad02modulos ('.$sCampos102.') VALUES ('.utf8_encode($sValores102).');';
				$sdetalle=$sCampos102.'['.utf8_encode($sValores102).']';
				}else{
				$sSQL='INSERT INTO unad02modulos ('.$sCampos102.') VALUES ('.$sValores102.');';
				$sdetalle=$sCampos102.'['.$sValores102.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='unad02nombre';
			$scampo[2]='unad02idsistema';
			$sdato[1]=$DATA['unad02nombre'];
			$sdato[2]=$DATA['unad02idsistema'];
			$numcmod=2;
			$sWhere='unad02id='.$DATA['unad02id'].'';
			$sSQL='SELECT * FROM unad02modulos WHERE '.$sWhere;
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
					$sSQL='UPDATE unad02modulos SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unad02modulos SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [102] ..<!-- '.$sSQL.' -->';
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 102 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, 0, $sdetalle, $objDB);}
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
function f102_db_Eliminar($unad02id, $objDB, $bDebug=false){
	$iCodModulo=102;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_102='lg/lg_102_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_102)){$mensajes_102='lg/lg_102_es.php';}
	require $mensajes_todas;
	require $mensajes_102;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM unad02modulos WHERE unad02id='.$unad02id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unad02id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad04idmodulo FROM unad04modulopermisos WHERE unad04idmodulo='.$unad02id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Permiso por modulo creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
/*
	if ($sError==''){
		$sSQL='SELECT * FROM tablaexterna WHERE idexterno='.$_REQUEST['CampoRevisa'].' LIMIT 0, 1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError=$ERR['p1'];//Incluya la explicacion al error en el archivo de idioma
			}
		}
*/
	if ($sError==''){
		//$sSQL='DELETE FROM unad04modulopermisos WHERE unad04idmodulo='.$filabase['unad02id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='unad02id='.$unad02id.'';
		$sSQL='DELETE FROM unad02modulos WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unad02id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f102_TituloBusqueda(){
	return 'Busqueda de Modulos';
	}
function f102_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b102nombre" name="b102nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f102_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b102nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f102_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>