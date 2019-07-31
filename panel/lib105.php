<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 lunes, 23 de noviembre de 2015
--- Modelo Versión 2.22.3 miércoles, 15 de agosto de 2018
--- 105 unad05perfiles
*/
function f105_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unad05id=numeros_validar($datos[1]);
	if ($unad05id==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unad05id FROM unad05perfiles WHERE unad05id='.$unad05id.'';
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
function f105_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_105='lg/lg_105_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_105)){$mensajes_105='lg/lg_105_es.php';}
	require $mensajes_todas;
	require $mensajes_105;
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
	$sTitulo='<h2>'.$ETI['titulo_105'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f105_HtmlBusqueda($aParametros){
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
function f105_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_105='lg/lg_105_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_105)){$mensajes_105='lg/lg_105_es.php';}
	require $mensajes_todas;
	require $mensajes_105;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='1';
	//if ((int)$aParametros[0]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[0];}
	if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.unad05nombre LIKE "%'.$aParametros[103].'%"';}
	$sTitulos='Id, Nombre, Aplicativo, Reservado';
	$sSQL='SELECT TB.unad05id, TB.unad05nombre, TB.unad05reservado, TB.unad05delegable, TB.unad05aplicativo 
FROM unad05perfiles AS TB 
WHERE  '.$sSQLadd.' 
ORDER BY TB.unad05nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_105" name="consulta_105" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_105" name="titulos_105" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 105: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf105" name="paginaf105" type="hidden" value="'.$pagina.'"/><input id="lppf105" name="lppf105" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unad05id'].'</b></td>
<td><b>'.$ETI['unad05nombre'].'</b></td>
<td><b>'.$ETI['unad05aplicativo'].'</b></td>
<td><b>'.$ETI['unad05reservado'].'</b></td>
<td><b>'.$ETI['unad05delegable'].'</b></td>
<td align="right">
'.html_paginador('paginaf105', $registros, $lineastabla, $pagina, 'paginarf105()').'
'.html_lpp('lppf105', $lineastabla, 'paginarf105()').'
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
		$et_unad05reservado=$ETI['no'];
		if ($filadet['unad05reservado']=='S'){$et_unad05reservado=$ETI['si'];}
		$et_unad05delegable=$ETI['no'];
		if ($filadet['unad05delegable']=='S'){$et_unad05delegable=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['unad05id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad05id'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad05nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad05aplicativo'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad05reservado.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad05delegable.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f105_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f105_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f105detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}

function f105_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=105;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_105='lg/lg_105_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_105)){$mensajes_105='lg/lg_105_es.php';}
	require $mensajes_todas;
	require $mensajes_105;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unad05id'])==0){$DATA['unad05id']='';}
	if (isset($DATA['unad05nombre'])==0){$DATA['unad05nombre']='';}
	if (isset($DATA['unad05aplicativo'])==0){$DATA['unad05aplicativo']='';}
	if (isset($DATA['unad05reservado'])==0){$DATA['unad05reservado']='';}
	if (isset($DATA['unad05delegable'])==0){$DATA['unad05delegable']='';}
	*/
	$DATA['unad05id']=numeros_validar($DATA['unad05id']);
	$DATA['unad05nombre']=htmlspecialchars(trim($DATA['unad05nombre']));
	$DATA['unad05aplicativo']=numeros_validar($DATA['unad05aplicativo']);
	$DATA['unad05reservado']=htmlspecialchars(trim($DATA['unad05reservado']));
	$DATA['unad05delegable']=htmlspecialchars(trim($DATA['unad05delegable']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['unad05aplicativo']==''){$DATA['unad05aplicativo']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['unad05delegable']==''){$sError=$ERR['unad05delegable'].$sSepara.$sError;}
		if ($DATA['unad05reservado']==''){$sError=$ERR['unad05reservado'].$sSepara.$sError;}
		//if ($DATA['unad05aplicativo']==''){$sError=$ERR['unad05aplicativo'].$sSepara.$sError;}
		if ($DATA['unad05nombre']==''){$sError=$ERR['unad05nombre'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unad05id']==''){$sError=$ERR['unad05id'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT unad05id FROM unad05perfiles WHERE unad05id='.$DATA['unad05id'].'';
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
			$sCampos105='unad05id, unad05nombre, unad05aplicativo, unad05reservado, unad05delegable';
			$sValores105=''.$DATA['unad05id'].', "'.$DATA['unad05nombre'].'", '.$DATA['unad05aplicativo'].', "'.$DATA['unad05reservado'].'", "'.$DATA['unad05delegable'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unad05perfiles ('.$sCampos105.') VALUES ('.utf8_encode($sValores105).');';
				$sdetalle=$sCampos105.'['.utf8_encode($sValores105).']';
				}else{
				$sSQL='INSERT INTO unad05perfiles ('.$sCampos105.') VALUES ('.$sValores105.');';
				$sdetalle=$sCampos105.'['.$sValores105.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='unad05nombre';
			$scampo[2]='unad05aplicativo';
			$scampo[3]='unad05reservado';
			$scampo[4]='unad05delegable';
			$sdato[1]=$DATA['unad05nombre'];
			$sdato[2]=$DATA['unad05aplicativo'];
			$sdato[3]=$DATA['unad05reservado'];
			$sdato[4]=$DATA['unad05delegable'];
			$numcmod=4;
			$sWhere='unad05id='.$DATA['unad05id'].'';
			$sSQL='SELECT * FROM unad05perfiles WHERE '.$sWhere;
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
					$sSQL='UPDATE unad05perfiles SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unad05perfiles SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [105] ..<!-- '.$sSQL.' -->';
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 105 '.$sSQL.'<br>';}
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
function f105_db_Eliminar($DATA, $objDB, $bDebug=false){
	$iCodModulo=105;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_105='lg/lg_105_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_105)){$mensajes_105='lg/lg_105_es.php';}
	require $mensajes_todas;
	require $mensajes_105;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$DATA['unad05id']=numeros_validar($DATA['unad05id']);
	if ($sError==''){
		$sSQL='SELECT unad06idperfil FROM unad06perfilmodpermiso WHERE unad06idperfil='.$DATA['unad05id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El reporte contiene Permisos por perfil, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sWhere='unad05id='.$DATA['unad05id'].'';
		$sSQL='DELETE FROM unad05perfiles WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, 0, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f105_TituloBusqueda(){
	return 'Busqueda de Perfiles';
	}
function f105_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b105nombre" name="b105nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f105_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b105nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f105_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>