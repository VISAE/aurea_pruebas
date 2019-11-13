<?php
/*
--- © Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - UNAD - 2019 ---
--- samicial@puntosoftware.net - http://www.puntosoftware.net 
// --- Desarrollo por encargo para la UNAD Contrato OS-2019-000130 
// --- Conforme a la metodología de desarrollo de la plataforma AUREA.
--- Modelo Versión 2.23.7 Friday, October 18, 2019
--- 2909 plab09empresa
*/
/** Archivo lib2909.php.
* Libreria 2909 plab09empresa.
* @author Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - samicial@puntosoftware.net
* @date Friday, October 18, 2019
*/
function f2909_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$plab09idtercero=numeros_validar($datos[1]);
	if ($plab09idtercero==''){$bHayLlave=false;}
	$plab09consecutivo=numeros_validar($datos[2]);
	if ($plab09consecutivo==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT plab09consecutivo FROM plab09empresa WHERE plab09idtercero='.$plab09idtercero.' AND plab09consecutivo='.$plab09consecutivo.'';
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
function f2909_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2909='lg/lg_2909_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2909)){$mensajes_2909='lg/lg_2909_es.php';}
	require $mensajes_todas;
	require $mensajes_2909;
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
		case 'plab09idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2909);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2909'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2909_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'plab09idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2909_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2909='lg/lg_2909_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2909)){$mensajes_2909='lg/lg_2909_es.php';}
	require $mensajes_todas;
	require $mensajes_2909;
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
		return array($sLeyenda.'<input id="paginaf2909" name="paginaf2909" type="hidden" value="'.$pagina.'"/><input id="lppf2909" name="lppf2909" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tercero, Consecutivo, Id, Industria, Sector, Contnombre, Contcorreo, Numoferpub, Activo';
	$sSQL='SELECT T1.unad11razonsocial AS C1_nombre, TB.plab09consecutivo, TB.plab09id, T4.plab05nombre, T5.plab06nombre, TB.plab09contnombre, TB.plab09contcorreo, TB.plab09numoferpub, TB.plab09activo, TB.plab09idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.plab09industria, TB.plab09sector 
FROM plab09empresa AS TB, unad11terceros AS T1, plab05industria AS T4, plab06sector AS T5 
WHERE '.$sSQLadd1.' TB.plab09idtercero=T1.unad11id AND TB.plab09industria=T4.plab05id AND TB.plab09sector=T5.plab06id '.$sSQLadd.'
ORDER BY TB.plab09idtercero, TB.plab09consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2909" name="consulta_2909" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2909" name="titulos_2909" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2909: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2909" name="paginaf2909" type="hidden" value="'.$pagina.'"/><input id="lppf2909" name="lppf2909" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['plab09idtercero'].'</b></td>
<td><b>'.$ETI['plab09consecutivo'].'</b></td>
<td><b>'.$ETI['plab09industria'].'</b></td>
<td><b>'.$ETI['plab09sector'].'</b></td>
<td><b>'.$ETI['plab09contnombre'].'</b></td>
<td><b>'.$ETI['plab09contcorreo'].'</b></td>
<td><b>'.$ETI['plab09numoferpub'].'</b></td>
<td><b>'.$ETI['plab09activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2909', $registros, $lineastabla, $pagina, 'paginarf2909()').'
'.html_lpp('lppf2909', $lineastabla, 'paginarf2909()').'
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
		$et_plab09activo=$ETI['no'];
		if ($filadet['plab09activo']=='S'){$et_plab09activo=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2909('.$filadet['plab09id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C1_td'].' '.$filadet['C1_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C1_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab05nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab06nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09contnombre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09contcorreo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09numoferpub'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab09activo.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2909_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2909_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2909detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2909_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['plab09idtercero_td']=$APP->tipo_doc;
	$DATA['plab09idtercero_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='plab09idtercero="'.$DATA['plab09idtercero'].'" AND plab09consecutivo='.$DATA['plab09consecutivo'].'';
		}else{
		$sSQLcondi='plab09id='.$DATA['plab09id'].'';
		}
	$sSQL='SELECT * FROM plab09empresa WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['plab09idtercero']=$fila['plab09idtercero'];
		$DATA['plab09consecutivo']=$fila['plab09consecutivo'];
		$DATA['plab09id']=$fila['plab09id'];
		$DATA['plab09industria']=$fila['plab09industria'];
		$DATA['plab09sector']=$fila['plab09sector'];
		$DATA['plab09contnombre']=$fila['plab09contnombre'];
		$DATA['plab09contcorreo']=$fila['plab09contcorreo'];
		$DATA['plab09numoferpub']=$fila['plab09numoferpub'];
		$DATA['plab09activo']=$fila['plab09activo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2909']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2909_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2909;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2909='lg/lg_2909_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2909)){$mensajes_2909='lg/lg_2909_es.php';}
	require $mensajes_todas;
	require $mensajes_2909;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['plab09idtercero'])==0){$DATA['plab09idtercero']='';}
	if (isset($DATA['plab09consecutivo'])==0){$DATA['plab09consecutivo']='';}
	if (isset($DATA['plab09id'])==0){$DATA['plab09id']='';}
	if (isset($DATA['plab09industria'])==0){$DATA['plab09industria']='';}
	if (isset($DATA['plab09sector'])==0){$DATA['plab09sector']='';}
	if (isset($DATA['plab09contnombre'])==0){$DATA['plab09contnombre']='';}
	if (isset($DATA['plab09contcorreo'])==0){$DATA['plab09contcorreo']='';}
	if (isset($DATA['plab09numoferpub'])==0){$DATA['plab09numoferpub']='';}
	if (isset($DATA['plab09activo'])==0){$DATA['plab09activo']='';}
	*/
	$DATA['plab09consecutivo']=numeros_validar($DATA['plab09consecutivo']);
	$DATA['plab09industria']=numeros_validar($DATA['plab09industria']);
	$DATA['plab09sector']=numeros_validar($DATA['plab09sector']);
	$DATA['plab09contnombre']=htmlspecialchars(trim($DATA['plab09contnombre']));
	$DATA['plab09contcorreo']=htmlspecialchars(trim($DATA['plab09contcorreo']));
	$DATA['plab09numoferpub']=numeros_validar($DATA['plab09numoferpub']);
	$DATA['plab09activo']=htmlspecialchars(trim($DATA['plab09activo']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['plab09industria']==''){$DATA['plab09industria']=0;}
	//if ($DATA['plab09sector']==''){$DATA['plab09sector']=0;}
	//if ($DATA['plab09numoferpub']==''){$DATA['plab09numoferpub']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['plab09activo']==''){$sError=$ERR['plab09activo'].$sSepara.$sError;}
		if ($DATA['plab09numoferpub']==''){$sError=$ERR['plab09numoferpub'].$sSepara.$sError;}
		//if ($DATA['plab09contcorreo']==''){$sError=$ERR['plab09contcorreo'].$sSepara.$sError;}
		//if ($DATA['plab09contnombre']==''){$sError=$ERR['plab09contnombre'].$sSepara.$sError;}
		if ($DATA['plab09sector']==''){$sError=$ERR['plab09sector'].$sSepara.$sError;}
		if ($DATA['plab09industria']==''){$sError=$ERR['plab09industria'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['plab09idtercero']==0){$sError=$ERR['plab09idtercero'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['plab09idtercero_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['plab09idtercero_td'], $DATA['plab09idtercero_doc'], $objDB, 'El tercero Tercero ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['plab09idtercero'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['plab09consecutivo']==''){
				$DATA['plab09consecutivo']=tabla_consecutivo('plab09empresa', 'plab09consecutivo', 'plab09idtercero='.$DATA['plab09idtercero'].'', $objDB);
				if ($DATA['plab09consecutivo']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['plab09consecutivo']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM plab09empresa WHERE plab09idtercero="'.$DATA['plab09idtercero'].'" AND plab09consecutivo='.$DATA['plab09consecutivo'].'';
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
			$DATA['plab09id']=tabla_consecutivo('plab09empresa','plab09id', '', $objDB);
			if ($DATA['plab09id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['plab09contnombre']=stripslashes($DATA['plab09contnombre']);}
		//Si el campo plab09contnombre permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$plab09contnombre=addslashes($DATA['plab09contnombre']);
		$plab09contnombre=str_replace('"', '\"', $DATA['plab09contnombre']);
		if (get_magic_quotes_gpc()==1){$DATA['plab09contcorreo']=stripslashes($DATA['plab09contcorreo']);}
		//Si el campo plab09contcorreo permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$plab09contcorreo=addslashes($DATA['plab09contcorreo']);
		$plab09contcorreo=str_replace('"', '\"', $DATA['plab09contcorreo']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2909='plab09idtercero, plab09consecutivo, plab09id, plab09industria, plab09sector, plab09contnombre, plab09contcorreo, plab09numoferpub, plab09activo';
			$sValores2909=''.$DATA['plab09idtercero'].', '.$DATA['plab09consecutivo'].', '.$DATA['plab09id'].', '.$DATA['plab09industria'].', '.$DATA['plab09sector'].', "'.$plab09contnombre.'", "'.$plab09contcorreo.'", '.$DATA['plab09numoferpub'].', "'.$DATA['plab09activo'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab09empresa ('.$sCampos2909.') VALUES ('.utf8_encode($sValores2909).');';
				$sdetalle=$sCampos2909.'['.utf8_encode($sValores2909).']';
				}else{
				$sSQL='INSERT INTO plab09empresa ('.$sCampos2909.') VALUES ('.$sValores2909.');';
				$sdetalle=$sCampos2909.'['.$sValores2909.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='plab09industria';
			$scampo[2]='plab09sector';
			$scampo[3]='plab09contnombre';
			$scampo[4]='plab09contcorreo';
			$scampo[5]='plab09numoferpub';
			$scampo[6]='plab09activo';
			$sdato[1]=$DATA['plab09industria'];
			$sdato[2]=$DATA['plab09sector'];
			$sdato[3]=$plab09contnombre;
			$sdato[4]=$plab09contcorreo;
			$sdato[5]=$DATA['plab09numoferpub'];
			$sdato[6]=$DATA['plab09activo'];
			$numcmod=6;
			$sWhere='plab09id='.$DATA['plab09id'].'';
			$sSQL='SELECT * FROM plab09empresa WHERE '.$sWhere;
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
					$sSQL='UPDATE plab09empresa SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE plab09empresa SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2909] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['plab09id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2909 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['plab09id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		if ($bQuitarCodigo){
			$DATA['plab09consecutivo']='';
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2909_db_Eliminar($plab09id, $objDB, $bDebug=false){
	$iCodModulo=2909;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2909='lg/lg_2909_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2909)){$mensajes_2909='lg/lg_2909_es.php';}
	require $mensajes_todas;
	require $mensajes_2909;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$plab09id=numeros_validar($plab09id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM plab09empresa WHERE plab09id='.$plab09id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$plab09id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT plab03idempresa FROM plab03rangsala WHERE plab03idempresa='.$filabase['plab09id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen rango salarial creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2909';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['plab09id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM plab03rangsala WHERE plab03idempresa='.$filabase['plab09id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='plab09id='.$plab09id.'';
		//$sWhere='plab09consecutivo='.$filabase['plab09consecutivo'].' AND plab09idtercero="'.$filabase['plab09idtercero'].'"';
		$sSQL='DELETE FROM plab09empresa WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab09id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2909_TituloBusqueda(){
	return 'Busqueda de empresa ofertante';
	}
function f2909_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2909nombre" name="b2909nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2909_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2909nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2909_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2909='lg/lg_2909_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2909)){$mensajes_2909='lg/lg_2909_es.php';}
	require $mensajes_todas;
	require $mensajes_2909;
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
		return array($sLeyenda.'<input id="paginaf2909" name="paginaf2909" type="hidden" value="'.$pagina.'"/><input id="lppf2909" name="lppf2909" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tercero, Consecutivo, Id, Industria, Sector, Contnombre, Contcorreo, Numoferpub, Activo';
	$sSQL='SELECT T1.unad11razonsocial AS C1_nombre, TB.plab09consecutivo, TB.plab09id, T4.plab05nombre, T5.plab06nombre, TB.plab09contnombre, TB.plab09contcorreo, TB.plab09numoferpub, TB.plab09activo, TB.plab09idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.plab09industria, TB.plab09sector 
FROM plab09empresa AS TB, unad11terceros AS T1, plab05industria AS T4, plab06sector AS T5 
WHERE '.$sSQLadd1.' TB.plab09idtercero=T1.unad11id AND TB.plab09industria=T4.plab05id AND TB.plab09sector=T5.plab06id '.$sSQLadd.'
ORDER BY TB.plab09idtercero, TB.plab09consecutivo';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2909" name="paginaf2909" type="hidden" value="'.$pagina.'"/><input id="lppf2909" name="lppf2909" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['plab09idtercero'].'</b></td>
<td><b>'.$ETI['plab09consecutivo'].'</b></td>
<td><b>'.$ETI['plab09industria'].'</b></td>
<td><b>'.$ETI['plab09sector'].'</b></td>
<td><b>'.$ETI['plab09contnombre'].'</b></td>
<td><b>'.$ETI['plab09contcorreo'].'</b></td>
<td><b>'.$ETI['plab09numoferpub'].'</b></td>
<td><b>'.$ETI['plab09activo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['plab09id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_plab09activo=$ETI['no'];
		if ($filadet['plab09activo']=='S'){$et_plab09activo=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['C1_td'].' '.$filadet['C1_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C1_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab05nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab06nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09contnombre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09contcorreo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab09numoferpub'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab09activo.$sSufijo.'</td>
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