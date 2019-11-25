<?php
/*
--- © Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - UNAD - 2019 ---
--- samicial@puntosoftware.net - http://www.puntosoftware.net 
// --- Desarrollo por encargo para la UNAD Contrato OS-2019-000130 
// --- Conforme a la metodología de desarrollo de la plataforma AUREA.
--- Modelo Versión 2.23.7 Friday, October 18, 2019
--- 2907 plab07nivingles
*/
/** Archivo lib2907.php.
* Libreria 2907 plab07nivingles.
* @author Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - samicial@puntosoftware.net
* @date Friday, October 18, 2019
*/
function f2907_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$plab07consecutivo=numeros_validar($datos[1]);
	if ($plab07consecutivo==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT plab07consecutivo FROM plab07nivingles WHERE plab07consecutivo='.$plab07consecutivo.'';
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
function f2907_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2907='lg/lg_2907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2907)){$mensajes_2907='lg/lg_2907_es.php';}
	require $mensajes_todas;
	require $mensajes_2907;
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
	$sTitulo='<h2>'.$ETI['titulo_2907'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2907_HtmlBusqueda($aParametros){
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
function f2907_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2907='lg/lg_2907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2907)){$mensajes_2907='lg/lg_2907_es.php';}
	require $mensajes_todas;
	require $mensajes_2907;
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
		return array($sLeyenda.'<input id="paginaf2907" name="paginaf2907" type="hidden" value="'.$pagina.'"/><input id="lppf2907" name="lppf2907" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consecutivo, Id, Nombre, Activo';
	$sSQL='SELECT TB.plab07consecutivo, TB.plab07id, TB.plab07nombre, TB.plab07activo 
FROM plab07nivingles AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.plab07consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2907" name="consulta_2907" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2907" name="titulos_2907" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2907: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2907" name="paginaf2907" type="hidden" value="'.$pagina.'"/><input id="lppf2907" name="lppf2907" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab07consecutivo'].'</b></td>
<td><b>'.$ETI['plab07nombre'].'</b></td>
<td><b>'.$ETI['plab07activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2907', $registros, $lineastabla, $pagina, 'paginarf2907()').'
'.html_lpp('lppf2907', $lineastabla, 'paginarf2907()').'
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
		$et_plab07activo=$ETI['no'];
		if ($filadet['plab07activo']=='S'){$et_plab07activo=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2907('.$filadet['plab07id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['plab07consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab07nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab07activo.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2907_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2907_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2907detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2907_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='plab07consecutivo='.$DATA['plab07consecutivo'].'';
		}else{
		$sSQLcondi='plab07id='.$DATA['plab07id'].'';
		}
	$sSQL='SELECT * FROM plab07nivingles WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['plab07consecutivo']=$fila['plab07consecutivo'];
		$DATA['plab07id']=$fila['plab07id'];
		$DATA['plab07nombre']=$fila['plab07nombre'];
		$DATA['plab07activo']=$fila['plab07activo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2907']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2907_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2907;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2907='lg/lg_2907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2907)){$mensajes_2907='lg/lg_2907_es.php';}
	require $mensajes_todas;
	require $mensajes_2907;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['plab07consecutivo'])==0){$DATA['plab07consecutivo']='';}
	if (isset($DATA['plab07id'])==0){$DATA['plab07id']='';}
	if (isset($DATA['plab07nombre'])==0){$DATA['plab07nombre']='';}
	if (isset($DATA['plab07activo'])==0){$DATA['plab07activo']='';}
	*/
	$DATA['plab07consecutivo']=numeros_validar($DATA['plab07consecutivo']);
	$DATA['plab07nombre']=htmlspecialchars(trim($DATA['plab07nombre']));
	$DATA['plab07activo']=htmlspecialchars(trim($DATA['plab07activo']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['plab07activo']==''){$sError=$ERR['plab07activo'].$sSepara.$sError;}
		if ($DATA['plab07nombre']==''){$sError=$ERR['plab07nombre'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['plab07consecutivo']==''){
				$DATA['plab07consecutivo']=tabla_consecutivo('plab07nivingles', 'plab07consecutivo', '', $objDB);
				if ($DATA['plab07consecutivo']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['plab07consecutivo']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM plab07nivingles WHERE plab07consecutivo='.$DATA['plab07consecutivo'].'';
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
			$DATA['plab07id']=tabla_consecutivo('plab07nivingles','plab07id', '', $objDB);
			if ($DATA['plab07id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2907='plab07consecutivo, plab07id, plab07nombre, plab07activo';
			$sValores2907=''.$DATA['plab07consecutivo'].', '.$DATA['plab07id'].', "'.$DATA['plab07nombre'].'", "'.$DATA['plab07activo'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab07nivingles ('.$sCampos2907.') VALUES ('.utf8_encode($sValores2907).');';
				$sdetalle=$sCampos2907.'['.utf8_encode($sValores2907).']';
				}else{
				$sSQL='INSERT INTO plab07nivingles ('.$sCampos2907.') VALUES ('.$sValores2907.');';
				$sdetalle=$sCampos2907.'['.$sValores2907.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='plab07nombre';
			$scampo[2]='plab07activo';
			$sdato[1]=$DATA['plab07nombre'];
			$sdato[2]=$DATA['plab07activo'];
			$numcmod=2;
			$sWhere='plab07id='.$DATA['plab07id'].'';
			$sSQL='SELECT * FROM plab07nivingles WHERE '.$sWhere;
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
					$sSQL='UPDATE plab07nivingles SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE plab07nivingles SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2907] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['plab07id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2907 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['plab07id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		if ($bQuitarCodigo){
			$DATA['plab07consecutivo']='';
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2907_db_Eliminar($plab07id, $objDB, $bDebug=false){
	$iCodModulo=2907;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2907='lg/lg_2907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2907)){$mensajes_2907='lg/lg_2907_es.php';}
	require $mensajes_todas;
	require $mensajes_2907;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$plab07id=numeros_validar($plab07id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM plab07nivingles WHERE plab07id='.$plab07id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$plab07id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2907';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['plab07id'].' LIMIT 0, 1';
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
		$sWhere='plab07id='.$plab07id.'';
		//$sWhere='plab07consecutivo='.$filabase['plab07consecutivo'].'';
		$sSQL='DELETE FROM plab07nivingles WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab07id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2907_TituloBusqueda(){
	return 'Busqueda de nivel ingles';
	}
function f2907_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2907nombre" name="b2907nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2907_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2907nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2907_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2907='lg/lg_2907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2907)){$mensajes_2907='lg/lg_2907_es.php';}
	require $mensajes_todas;
	require $mensajes_2907;
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
		return array($sLeyenda.'<input id="paginaf2907" name="paginaf2907" type="hidden" value="'.$pagina.'"/><input id="lppf2907" name="lppf2907" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consecutivo, Id, Nombre, Activo';
	$sSQL='SELECT TB.plab07consecutivo, TB.plab07id, TB.plab07nombre, TB.plab07activo 
FROM plab07nivingles AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.plab07consecutivo';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2907" name="paginaf2907" type="hidden" value="'.$pagina.'"/><input id="lppf2907" name="lppf2907" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab07consecutivo'].'</b></td>
<td><b>'.$ETI['plab07nombre'].'</b></td>
<td><b>'.$ETI['plab07activo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['plab07id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_plab07activo=$ETI['no'];
		if ($filadet['plab07activo']=='S'){$et_plab07activo=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['plab07consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab07nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab07activo.$sSufijo.'</td>
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