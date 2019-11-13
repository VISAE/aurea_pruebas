<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.7 Tuesday, October 22, 2019
--- 2910 plab10oferta
*/
/** Archivo lib2910.php.
* Libreria 2910 plab10oferta.
* @author Cristhiam Dario Silva Chavez - cristhiam.silva@unad.edu.co
* @date Tuesday, October 22, 2019
*/
function f2910_HTMLComboV2_plab10emprbolsempleo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('plab10emprbolsempleo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT plab08id AS id, plab08idtercero AS nombre FROM plab08emprbolsempleo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2910_HTMLComboV2_plab10ubidep($objDB, $objCombos, $valor, $vrplab10ubipais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad19codpais="'.$vrplab10ubipais.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('plab10ubidep', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_plab10ubiciudad()';
	$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2910_HTMLComboV2_plab10ubiciudad($objDB, $objCombos, $valor, $vrplab10ubidep){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad20coddepto="'.$vrplab10ubidep.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('plab10ubiciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2910_HTMLComboV2_plab10rangsala($objDB, $objCombos, $valor, $vrplab10empresa){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='plab03idempresa="'.$vrplab10empresa.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('plab10rangsala', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT plab03id AS id, plab03nombre AS nombre FROM plab03rangsala'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2910_Comboplab10ubidep($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_plab10ubidep=f2910_HTMLComboV2_plab10ubidep($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab10ubidep', 'innerHTML', $html_plab10ubidep);
	return $objResponse;
	}
function f2910_Comboplab10ubiciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_plab10ubiciudad=f2910_HTMLComboV2_plab10ubiciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab10ubiciudad', 'innerHTML', $html_plab10ubiciudad);
	return $objResponse;
	}
function f2910_Comboplab10rangsala($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_plab10rangsala=f2910_HTMLComboV2_plab10rangsala($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab10rangsala', 'innerHTML', $html_plab10rangsala);
	return $objResponse;
	}
function f2910_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$plab10consecutivo=numeros_validar($datos[1]);
	if ($plab10consecutivo==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT plab10consecutivo FROM plab10oferta WHERE plab10consecutivo='.$plab10consecutivo.'';
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
function f2910_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
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
		case 'plab10empresa':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2910);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2910'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2910_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'plab10empresa':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2910_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
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
		return array($sLeyenda.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consecutivo, Id, Emprbolsempleo, Refoferta, Empresa, Titulo, Ubicacion, Ubipais, Ubidep, Ubiciudad, Fechapubini, Tipocont, Estado, Rangsala, Segmento, Totalaplica, Numvacantes, Profesion, Activo, Fechapubfin, Detalle';
	$sSQL='SELECT TB.plab10consecutivo, TB.plab10id, T3.plab08idtercero, TB.plab10refoferta, T5.unad11razonsocial AS C5_nombre, TB.plab10titulo, TB.plab10ubicacion, T8.unad18nombre, T9.unad19nombre, T10.unad20nombre, TB.plab10fechapubini, T12.plab11nombre, T13.plab12nombre, T14.plab03nombre, T15.plab13nombre, TB.plab10totalaplica, TB.plab10numvacantes, T18.plab02nombre, TB.plab10activo, TB.plab10fechapubfin, TB.plab10detalle, TB.plab10emprbolsempleo, TB.plab10empresa, T5.unad11tipodoc AS C5_td, T5.unad11doc AS C5_doc, TB.plab10ubipais, TB.plab10ubidep, TB.plab10ubiciudad, TB.plab10tipocont, TB.plab10estado, TB.plab10rangsala, TB.plab10segmento, TB.plab10profesion 
FROM plab10oferta AS TB, plab08emprbolsempleo AS T3, unad11terceros AS T5, unad18pais AS T8, unad19depto AS T9, unad20ciudad AS T10, plab11tipocont AS T12, plab12estadooferta AS T13, plab03rangsala AS T14, plab13segmento AS T15, plab02prof AS T18 
WHERE '.$sSQLadd1.' TB.plab10emprbolsempleo=T3.plab08id AND TB.plab10empresa=T5.unad11id AND TB.plab10ubipais=T8.unad18codigo AND TB.plab10ubidep=T9.unad19codigo AND TB.plab10ubiciudad=T10.unad20codigo AND TB.plab10tipocont=T12.plab11id AND TB.plab10estado=T13.plab12id AND TB.plab10rangsala=T14.plab03id AND TB.plab10segmento=T15.plab13id AND TB.plab10profesion=T18.plab02id '.$sSQLadd.'
ORDER BY TB.plab10consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2910" name="consulta_2910" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2910" name="titulos_2910" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2910: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab10consecutivo'].'</b></td>
<td><b>'.$ETI['plab10emprbolsempleo'].'</b></td>
<td><b>'.$ETI['plab10refoferta'].'</b></td>
<td colspan="2"><b>'.$ETI['plab10empresa'].'</b></td>
<td><b>'.$ETI['plab10titulo'].'</b></td>
<td><b>'.$ETI['plab10ubicacion'].'</b></td>
<td><b>'.$ETI['plab10ubipais'].'</b></td>
<td><b>'.$ETI['plab10ubidep'].'</b></td>
<td><b>'.$ETI['plab10ubiciudad'].'</b></td>
<td><b>'.$ETI['plab10fechapubini'].'</b></td>
<td><b>'.$ETI['plab10tipocont'].'</b></td>
<td><b>'.$ETI['plab10estado'].'</b></td>
<td><b>'.$ETI['plab10rangsala'].'</b></td>
<td><b>'.$ETI['plab10segmento'].'</b></td>
<td><b>'.$ETI['plab10totalaplica'].'</b></td>
<td><b>'.$ETI['plab10numvacantes'].'</b></td>
<td><b>'.$ETI['plab10profesion'].'</b></td>
<td><b>'.$ETI['plab10activo'].'</b></td>
<td><b>'.$ETI['plab10fechapubfin'].'</b></td>
<td><b>'.$ETI['plab10detalle'].'</b></td>
<td align="right">
'.html_paginador('paginaf2910', $registros, $lineastabla, $pagina, 'paginarf2910()').'
'.html_lpp('lppf2910', $lineastabla, 'paginarf2910()').'
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
		$et_plab10fechapubini='';
		if ($filadet['plab10fechapubini']!=0){$et_plab10fechapubini=fecha_desdenumero($filadet['plab10fechapubini']);}
		$et_plab10activo=$ETI['no'];
		if ($filadet['plab10activo']=='S'){$et_plab10activo=$ETI['si'];}
		$et_plab10fechapubfin='';
		if ($filadet['plab10fechapubfin']!=0){$et_plab10fechapubfin=fecha_desdenumero($filadet['plab10fechapubfin']);}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2910('.$filadet['plab10id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['plab10consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab08idtercero']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10refoferta']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C5_td'].' '.$filadet['C5_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C5_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubicacion'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10ubipais']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10ubidep']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10ubiciudad']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10fechapubini.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab13nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10totalaplica'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10numvacantes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10activo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10fechapubfin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10detalle'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2910_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2910_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2910detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2910_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['plab10empresa_td']=$APP->tipo_doc;
	$DATA['plab10empresa_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='plab10consecutivo='.$DATA['plab10consecutivo'].'';
		}else{
		$sSQLcondi='plab10id='.$DATA['plab10id'].'';
		}
	$sSQL='SELECT * FROM plab10oferta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['plab10consecutivo']=$fila['plab10consecutivo'];
		$DATA['plab10id']=$fila['plab10id'];
		$DATA['plab10emprbolsempleo']=$fila['plab10emprbolsempleo'];
		$DATA['plab10refoferta']=$fila['plab10refoferta'];
		$DATA['plab10empresa']=$fila['plab10empresa'];
		$DATA['plab10titulo']=$fila['plab10titulo'];
		$DATA['plab10ubicacion']=$fila['plab10ubicacion'];
		$DATA['plab10ubipais']=$fila['plab10ubipais'];
		$DATA['plab10ubidep']=$fila['plab10ubidep'];
		$DATA['plab10ubiciudad']=$fila['plab10ubiciudad'];
		$DATA['plab10fechapubini']=$fila['plab10fechapubini'];
		$DATA['plab10tipocont']=$fila['plab10tipocont'];
		$DATA['plab10estado']=$fila['plab10estado'];
		$DATA['plab10rangsala']=$fila['plab10rangsala'];
		$DATA['plab10segmento']=$fila['plab10segmento'];
		$DATA['plab10totalaplica']=$fila['plab10totalaplica'];
		$DATA['plab10numvacantes']=$fila['plab10numvacantes'];
		$DATA['plab10profesion']=$fila['plab10profesion'];
		$DATA['plab10activo']=$fila['plab10activo'];
		$DATA['plab10fechapubfin']=$fila['plab10fechapubfin'];
		$DATA['plab10detalle']=$fila['plab10detalle'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2910']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2910_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2910;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['plab10consecutivo'])==0){$DATA['plab10consecutivo']='';}
	if (isset($DATA['plab10id'])==0){$DATA['plab10id']='';}
	if (isset($DATA['plab10emprbolsempleo'])==0){$DATA['plab10emprbolsempleo']='';}
	if (isset($DATA['plab10refoferta'])==0){$DATA['plab10refoferta']='';}
	if (isset($DATA['plab10empresa'])==0){$DATA['plab10empresa']='';}
	if (isset($DATA['plab10titulo'])==0){$DATA['plab10titulo']='';}
	if (isset($DATA['plab10ubicacion'])==0){$DATA['plab10ubicacion']='';}
	if (isset($DATA['plab10ubipais'])==0){$DATA['plab10ubipais']='';}
	if (isset($DATA['plab10ubidep'])==0){$DATA['plab10ubidep']='';}
	if (isset($DATA['plab10ubiciudad'])==0){$DATA['plab10ubiciudad']='';}
	if (isset($DATA['plab10fechapubini'])==0){$DATA['plab10fechapubini']='';}
	if (isset($DATA['plab10tipocont'])==0){$DATA['plab10tipocont']='';}
	if (isset($DATA['plab10estado'])==0){$DATA['plab10estado']='';}
	if (isset($DATA['plab10rangsala'])==0){$DATA['plab10rangsala']='';}
	if (isset($DATA['plab10segmento'])==0){$DATA['plab10segmento']='';}
	if (isset($DATA['plab10totalaplica'])==0){$DATA['plab10totalaplica']='';}
	if (isset($DATA['plab10numvacantes'])==0){$DATA['plab10numvacantes']='';}
	if (isset($DATA['plab10profesion'])==0){$DATA['plab10profesion']='';}
	if (isset($DATA['plab10activo'])==0){$DATA['plab10activo']='';}
	if (isset($DATA['plab10fechapubfin'])==0){$DATA['plab10fechapubfin']='';}
	if (isset($DATA['plab10detalle'])==0){$DATA['plab10detalle']='';}
	*/
	$DATA['plab10consecutivo']=numeros_validar($DATA['plab10consecutivo']);
	$DATA['plab10emprbolsempleo']=numeros_validar($DATA['plab10emprbolsempleo']);
	$DATA['plab10refoferta']=htmlspecialchars(trim($DATA['plab10refoferta']));
	$DATA['plab10titulo']=htmlspecialchars(trim($DATA['plab10titulo']));
	$DATA['plab10ubicacion']=htmlspecialchars(trim($DATA['plab10ubicacion']));
	$DATA['plab10ubipais']=htmlspecialchars(trim($DATA['plab10ubipais']));
	$DATA['plab10ubidep']=htmlspecialchars(trim($DATA['plab10ubidep']));
	$DATA['plab10ubiciudad']=htmlspecialchars(trim($DATA['plab10ubiciudad']));
	$DATA['plab10tipocont']=numeros_validar($DATA['plab10tipocont']);
	$DATA['plab10estado']=numeros_validar($DATA['plab10estado']);
	$DATA['plab10rangsala']=numeros_validar($DATA['plab10rangsala']);
	$DATA['plab10segmento']=numeros_validar($DATA['plab10segmento']);
	$DATA['plab10totalaplica']=numeros_validar($DATA['plab10totalaplica']);
	$DATA['plab10numvacantes']=numeros_validar($DATA['plab10numvacantes']);
	$DATA['plab10profesion']=numeros_validar($DATA['plab10profesion']);
	$DATA['plab10activo']=htmlspecialchars(trim($DATA['plab10activo']));
	$DATA['plab10detalle']=htmlspecialchars(trim($DATA['plab10detalle']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['plab10emprbolsempleo']==''){$DATA['plab10emprbolsempleo']=0;}
	//if ($DATA['plab10tipocont']==''){$DATA['plab10tipocont']=0;}
	//if ($DATA['plab10estado']==''){$DATA['plab10estado']=0;}
	//if ($DATA['plab10rangsala']==''){$DATA['plab10rangsala']=0;}
	//if ($DATA['plab10segmento']==''){$DATA['plab10segmento']=0;}
	//if ($DATA['plab10totalaplica']==''){$DATA['plab10totalaplica']=0;}
	//if ($DATA['plab10numvacantes']==''){$DATA['plab10numvacantes']=0;}
	//if ($DATA['plab10profesion']==''){$DATA['plab10profesion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//if ($DATA['plab10detalle']==''){$sError=$ERR['plab10detalle'].$sSepara.$sError;}
		if ($DATA['plab10fechapubfin']==0){
			//$DATA['plab10fechapubfin']=fecha_DiaMod();
			$sError=$ERR['plab10fechapubfin'].$sSepara.$sError;
			}
		if ($DATA['plab10activo']==''){$sError=$ERR['plab10activo'].$sSepara.$sError;}
		if ($DATA['plab10profesion']==''){$sError=$ERR['plab10profesion'].$sSepara.$sError;}
		if ($DATA['plab10numvacantes']==''){$sError=$ERR['plab10numvacantes'].$sSepara.$sError;}
		if ($DATA['plab10totalaplica']==''){$sError=$ERR['plab10totalaplica'].$sSepara.$sError;}
		if ($DATA['plab10segmento']==''){$sError=$ERR['plab10segmento'].$sSepara.$sError;}
		if ($DATA['plab10rangsala']==''){$sError=$ERR['plab10rangsala'].$sSepara.$sError;}
		if ($DATA['plab10estado']==''){$sError=$ERR['plab10estado'].$sSepara.$sError;}
		if ($DATA['plab10tipocont']==''){$sError=$ERR['plab10tipocont'].$sSepara.$sError;}
		if ($DATA['plab10fechapubini']==0){
			//$DATA['plab10fechapubini']=fecha_DiaMod();
			$sError=$ERR['plab10fechapubini'].$sSepara.$sError;
			}
		if ($DATA['plab10ubiciudad']==''){$sError=$ERR['plab10ubiciudad'].$sSepara.$sError;}
		if ($DATA['plab10ubidep']==''){$sError=$ERR['plab10ubidep'].$sSepara.$sError;}
		if ($DATA['plab10ubipais']==''){$sError=$ERR['plab10ubipais'].$sSepara.$sError;}
		//if ($DATA['plab10ubicacion']==''){$sError=$ERR['plab10ubicacion'].$sSepara.$sError;}
		if ($DATA['plab10titulo']==''){$sError=$ERR['plab10titulo'].$sSepara.$sError;}
		if ($DATA['plab10empresa']==0){$sError=$ERR['plab10empresa'].$sSepara.$sError;}
		if ($DATA['plab10refoferta']==''){$sError=$ERR['plab10refoferta'].$sSepara.$sError;}
		if ($DATA['plab10emprbolsempleo']==''){$sError=$ERR['plab10emprbolsempleo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['plab10empresa_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['plab10empresa_td'], $DATA['plab10empresa_doc'], $objDB, 'El tercero Empresa ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['plab10empresa'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['plab10consecutivo']==''){
				$DATA['plab10consecutivo']=tabla_consecutivo('plab10oferta', 'plab10consecutivo', '', $objDB);
				if ($DATA['plab10consecutivo']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['plab10consecutivo']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM plab10oferta WHERE plab10consecutivo='.$DATA['plab10consecutivo'].'';
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
			$DATA['plab10id']=tabla_consecutivo('plab10oferta','plab10id', '', $objDB);
			if ($DATA['plab10id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['plab10ubicacion']=stripslashes($DATA['plab10ubicacion']);}
		//Si el campo plab10ubicacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$plab10ubicacion=addslashes($DATA['plab10ubicacion']);
		$plab10ubicacion=str_replace('"', '\"', $DATA['plab10ubicacion']);
		if (get_magic_quotes_gpc()==1){$DATA['plab10detalle']=stripslashes($DATA['plab10detalle']);}
		//Si el campo plab10detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$plab10detalle=addslashes($DATA['plab10detalle']);
		$plab10detalle=str_replace('"', '\"', $DATA['plab10detalle']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$plab10fechapubini=fecha_DiaMod();
			$plab10fechapubfin=fecha_DiaMod();
			$sCampos2910='plab10consecutivo, plab10id, plab10emprbolsempleo, plab10refoferta, plab10empresa, plab10titulo, plab10ubicacion, plab10ubipais, plab10ubidep, plab10ubiciudad, 
plab10fechapubini, plab10tipocont, plab10estado, plab10rangsala, plab10segmento, plab10totalaplica, plab10numvacantes, plab10profesion, plab10activo, plab10fechapubfin, 
plab10detalle';
			$sValores2910=''.$DATA['plab10consecutivo'].', '.$DATA['plab10id'].', '.$DATA['plab10emprbolsempleo'].', "'.$DATA['plab10refoferta'].'", '.$DATA['plab10empresa'].', "'.$DATA['plab10titulo'].'", "'.$plab10ubicacion.'", "'.$DATA['plab10ubipais'].'", "'.$DATA['plab10ubidep'].'", "'.$DATA['plab10ubiciudad'].'", 
"'.$DATA['plab10fechapubini'].'", '.$DATA['plab10tipocont'].', '.$DATA['plab10estado'].', '.$DATA['plab10rangsala'].', '.$DATA['plab10segmento'].', '.$DATA['plab10totalaplica'].', '.$DATA['plab10numvacantes'].', '.$DATA['plab10profesion'].', "'.$DATA['plab10activo'].'", "'.$DATA['plab10fechapubfin'].'", 
"'.$plab10detalle.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab10oferta ('.$sCampos2910.') VALUES ('.utf8_encode($sValores2910).');';
				$sdetalle=$sCampos2910.'['.utf8_encode($sValores2910).']';
				}else{
				$sSQL='INSERT INTO plab10oferta ('.$sCampos2910.') VALUES ('.$sValores2910.');';
				$sdetalle=$sCampos2910.'['.$sValores2910.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='plab10refoferta';
			$scampo[2]='plab10empresa';
			$scampo[3]='plab10titulo';
			$scampo[4]='plab10ubicacion';
			$scampo[5]='plab10ubipais';
			$scampo[6]='plab10ubidep';
			$scampo[7]='plab10ubiciudad';
			$scampo[8]='plab10fechapubini';
			$scampo[9]='plab10tipocont';
			$scampo[10]='plab10estado';
			$scampo[11]='plab10rangsala';
			$scampo[12]='plab10segmento';
			$scampo[13]='plab10totalaplica';
			$scampo[14]='plab10numvacantes';
			$scampo[15]='plab10profesion';
			$scampo[16]='plab10activo';
			$scampo[17]='plab10fechapubfin';
			$scampo[18]='plab10detalle';
			$sdato[1]=$DATA['plab10refoferta'];
			$sdato[2]=$DATA['plab10empresa'];
			$sdato[3]=$DATA['plab10titulo'];
			$sdato[4]=$plab10ubicacion;
			$sdato[5]=$DATA['plab10ubipais'];
			$sdato[6]=$DATA['plab10ubidep'];
			$sdato[7]=$DATA['plab10ubiciudad'];
			$sdato[8]=$DATA['plab10fechapubini'];
			$sdato[9]=$DATA['plab10tipocont'];
			$sdato[10]=$DATA['plab10estado'];
			$sdato[11]=$DATA['plab10rangsala'];
			$sdato[12]=$DATA['plab10segmento'];
			$sdato[13]=$DATA['plab10totalaplica'];
			$sdato[14]=$DATA['plab10numvacantes'];
			$sdato[15]=$DATA['plab10profesion'];
			$sdato[16]=$DATA['plab10activo'];
			$sdato[17]=$DATA['plab10fechapubfin'];
			$sdato[18]=$plab10detalle;
			$numcmod=18;
			$sWhere='plab10id='.$DATA['plab10id'].'';
			$sSQL='SELECT * FROM plab10oferta WHERE '.$sWhere;
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
					$sSQL='UPDATE plab10oferta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE plab10oferta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2910] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['plab10id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2910 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['plab10id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		if ($bQuitarCodigo){
			$DATA['plab10consecutivo']='';
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2910_db_Eliminar($plab10id, $objDB, $bDebug=false){
	$iCodModulo=2910;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$plab10id=numeros_validar($plab10id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM plab10oferta WHERE plab10id='.$plab10id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$plab10id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT plab14oferta FROM plab14aplicaoferta WHERE plab14oferta='.$filabase['plab10id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen aplicacion a oferta creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT plab18idoferta FROM plab18ofertaciudad WHERE plab18idoferta='.$filabase['plab10id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen  creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2910';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['plab10id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM plab14aplicaoferta WHERE plab14oferta='.$filabase['plab10id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM plab18ofertaciudad WHERE plab18idoferta='.$filabase['plab10id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='plab10id='.$plab10id.'';
		//$sWhere='plab10consecutivo='.$filabase['plab10consecutivo'].'';
		$sSQL='DELETE FROM plab10oferta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab10id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2910_TituloBusqueda(){
	return 'Busqueda de ofertas';
	}
function f2910_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2910nombre" name="b2910nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2910_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2910nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2910_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
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
		return array($sLeyenda.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consecutivo, Id, Emprbolsempleo, Refoferta, Empresa, Titulo, Ubicacion, Ubipais, Ubidep, Ubiciudad, Fechapubini, Tipocont, Estado, Rangsala, Segmento, Totalaplica, Numvacantes, Profesion, Activo, Fechapubfin, Detalle';
	$sSQL='SELECT TB.plab10consecutivo, TB.plab10id, T3.plab08idtercero, TB.plab10refoferta, T5.unad11razonsocial AS C5_nombre, TB.plab10titulo, TB.plab10ubicacion, T8.unad18nombre, T9.unad19nombre, T10.unad20nombre, TB.plab10fechapubini, T12.plab11nombre, T13.plab12nombre, T14.plab03nombre, T15.plab13nombre, TB.plab10totalaplica, TB.plab10numvacantes, T18.plab02nombre, TB.plab10activo, TB.plab10fechapubfin, TB.plab10detalle, TB.plab10emprbolsempleo, TB.plab10empresa, T5.unad11tipodoc AS C5_td, T5.unad11doc AS C5_doc, TB.plab10ubipais, TB.plab10ubidep, TB.plab10ubiciudad, TB.plab10tipocont, TB.plab10estado, TB.plab10rangsala, TB.plab10segmento, TB.plab10profesion 
FROM plab10oferta AS TB, plab08emprbolsempleo AS T3, unad11terceros AS T5, unad18pais AS T8, unad19depto AS T9, unad20ciudad AS T10, plab11tipocont AS T12, plab12estadooferta AS T13, plab03rangsala AS T14, plab13segmento AS T15, plab02prof AS T18 
WHERE '.$sSQLadd1.' TB.plab10emprbolsempleo=T3.plab08id AND TB.plab10empresa=T5.unad11id AND TB.plab10ubipais=T8.unad18codigo AND TB.plab10ubidep=T9.unad19codigo AND TB.plab10ubiciudad=T10.unad20codigo AND TB.plab10tipocont=T12.plab11id AND TB.plab10estado=T13.plab12id AND TB.plab10rangsala=T14.plab03id AND TB.plab10segmento=T15.plab13id AND TB.plab10profesion=T18.plab02id '.$sSQLadd.'
ORDER BY TB.plab10consecutivo';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab10consecutivo'].'</b></td>
<td><b>'.$ETI['plab10emprbolsempleo'].'</b></td>
<td><b>'.$ETI['plab10refoferta'].'</b></td>
<td colspan="2"><b>'.$ETI['plab10empresa'].'</b></td>
<td><b>'.$ETI['plab10titulo'].'</b></td>
<td><b>'.$ETI['plab10ubicacion'].'</b></td>
<td><b>'.$ETI['plab10ubipais'].'</b></td>
<td><b>'.$ETI['plab10ubidep'].'</b></td>
<td><b>'.$ETI['plab10ubiciudad'].'</b></td>
<td><b>'.$ETI['plab10fechapubini'].'</b></td>
<td><b>'.$ETI['plab10tipocont'].'</b></td>
<td><b>'.$ETI['plab10estado'].'</b></td>
<td><b>'.$ETI['plab10rangsala'].'</b></td>
<td><b>'.$ETI['plab10segmento'].'</b></td>
<td><b>'.$ETI['plab10totalaplica'].'</b></td>
<td><b>'.$ETI['plab10numvacantes'].'</b></td>
<td><b>'.$ETI['plab10profesion'].'</b></td>
<td><b>'.$ETI['plab10activo'].'</b></td>
<td><b>'.$ETI['plab10fechapubfin'].'</b></td>
<td><b>'.$ETI['plab10detalle'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['plab10id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_plab10fechapubini='';
		if ($filadet['plab10fechapubini']!=0){$et_plab10fechapubini=fecha_desdenumero($filadet['plab10fechapubini']);}
		$et_plab10activo=$ETI['no'];
		if ($filadet['plab10activo']=='S'){$et_plab10activo=$ETI['si'];}
		$et_plab10fechapubfin='';
		if ($filadet['plab10fechapubfin']!=0){$et_plab10fechapubfin=fecha_desdenumero($filadet['plab10fechapubfin']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['plab10consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab08idtercero']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10refoferta']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C5_td'].' '.$filadet['C5_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C5_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubicacion'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubipais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubidep'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubiciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10fechapubini.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab13nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10totalaplica'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10numvacantes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10activo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10fechapubfin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10detalle'].$sSufijo.'</td>
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