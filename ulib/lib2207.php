<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.4 miércoles, 5 de septiembre de 2018
--- 2207 core07matriculaest
*/
function f2207_HTMLComboV2_core07idperaca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core07idperaca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->sAccion='paginarf2207();';
	$objCombos->sAccion='cambiapagina();';
	$sSQL=f146_ConsultaCombo(2216, $objDB);
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2207_HTMLComboV2_core07idprograma($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//core09idescuela="'.$vr.'"
	$sCondi='';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('core07idprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2207();';
	$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa'.$sCondi.' ORDER BY core09activo DESC, core09nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2207_HTMLComboV2_core07idcead($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//unad24idzona="'.$vr.'"
	$sCondi='unad24activa="S"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('core07idcead', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2207();';
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi.' ORDER BY unad24nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2207_HTMLComboV2_core07idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core07idzona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_core07id()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2207_HTMLComboV2_core07idescuela($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core07idescuela', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT exte01id AS id, exte01nombre AS nombre FROM exte01escuela';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2207_Combocore07idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_core07idprograma=f2207_HTMLComboV2_core07idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core07idprograma', 'innerHTML', $html_core07idprograma);
	return $objResponse;
	}
function f2207_Busqueda_db_core07idcurso($sCodigo, $objDB, $bDebug=false){
	$sRespuesta='';
	$sDebug='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Busqueda: '.$sSQL.'<br>';}
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.cadena_notildes($fila['unad40nombre']).'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta), $sDebug);
	}
function f2207_Busqueda_core07idcurso($aParametros){
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
		list($id, $sRespuesta, $sDebugCon)=f2207_Busqueda_db_core07idcurso($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('RevisaLlave');
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2207_Combocore07idcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_core07idcead=f2207_HTMLComboV2_core07idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core07idcead', 'innerHTML', $html_core07idcead);
	return $objResponse;
	}
function f2207_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2207=$APP->rutacomun.'lg/lg_2207_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2207)){$mensajes_2207=$APP->rutacomun.'lg/lg_2207_es.php';}
	require $mensajes_todas;
	require $mensajes_2207;
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
		case 'core07idcurso':
		$bExiste=true;
		if (file_exists('lib140.php')){
			require 'lib140.php';
			}else{
			$bExiste=false;
			}
		if ($bExiste){
			if (!function_exists('f140_TablaDetalleBusquedas')){$bExiste=false;}
			}
		if ($bExiste){
			$sTabla=f140_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo=f140_TituloBusqueda();
			$sParams=f140_ParametrosBusqueda();
			$sJavaBusqueda=f140_JavaScriptBusqueda(2207);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 140, por favor informe al administrador del sistema.</div>';
			}
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2207'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2207_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'core07idcurso':
		if (file_exists('lib140.php')){
			require 'lib140.php';
			$sDetalle=f140_TablaDetalleBusquedas($aParametros, $objDB);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib140, por favor informe al administrador del sistema.';
			}
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2207_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2207=$APP->rutacomun.'lg/lg_2207_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2207)){$mensajes_2207=$APP->rutacomun.'lg/lg_2207_es.php';}
	require $mensajes_todas;
	require $mensajes_2207;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='N';}
	if (isset($aParametros[108])==0){$aParametros[108]='N';}
	if (isset($aParametros[109])==0){$aParametros[109]='N';}
	if (isset($aParametros[110])==0){$aParametros[110]=0;}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$idCurso=$aParametros[106];
	$iTipoRpt=$aParametros[110];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($aParametros[103]==''){$sLeyenda='<b>Debe seleccionar un periodo a consultar</b>';}
	if ($sLeyenda==''){
		if ($iTipoRpt==1){
			if ($idCurso==''){
				$sLeyenda='<b>Debe seleccionar un curso a consultar</b>';
				}
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2207" name="paginaf2207" type="hidden" value="'.$pagina.'"/>
<input id="lppf2207" name="lppf2207" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	$bConPrograma=false;
	$bConZona=false;
	$bConCead=false;
	$bConTotal=true;
	if ($aParametros[107]=='S'){$bConPrograma=true;}
	if ($aParametros[108]=='S'){$bConCead=true;}
	if ($aParametros[109]=='S'){$bConZona=true;}
	if ($bConPrograma&&$bConCead&&$bConZona){$bConTotal=false;}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[104]!=''){
		$sSQLadd1=$sSQLadd1.'TB.core07idprograma='.$aParametros[104].' AND ';
		//$bConPrograma=false;
		}
	if ($aParametros[105]!=''){
		$sSQLadd1=$sSQLadd1.'TB.core07idcead='.$aParametros[105].' AND ';
		//$bConCead=false;
		}
	if ($aParametros[106]!=''){$sSQLadd1=$sSQLadd1.'TB.core07idcurso LIKE "%'.$aParametros[106].'%" AND ';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	, T6.unad23nombre, T7.exte01nombre
	, unad23zona AS T6, exte01escuela AS T7
	 AND TB.core07idzona=T6.unad23id AND TB.core07idescuela=T7.exte01id
	*/
	$aPrograma=array();
	if ($bConPrograma){
		$sSQL='SELECT core09id, core09nombre FROM core09programa';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$aPrograma[$fila['core09id']]=cadena_notildes($fila['core09nombre']);
			}
		}
	$aZona=array();
	if ($iTipoRpt==1){
		}
	$aCurso=array();
	$sTitulos='Peraca, Programa, Curso, Cead, Id, Zona, Escuela, Numestudiantes, Numnuevos';
	$bDetallado=false;
	$sTablas='';
	$sCondi='';
	$sCampos='';
	$sCamposDato='TB.core07numestudiantes AS Antiguos, TB.core07numnuevos AS Nuevos';
	$sGrupo='';
	$sGrupoTotal='';
	if ($bConPrograma){
		$sCampos='TB.core07idprograma';
		}
	if ($bConCead){
		if ($sCampos!=''){$sCampos=$sCampos.', ';}
		$sCampos=$sCampos.'T24.unad24idzona, T24.unad24nombre, TB.core07idcead';
		$sTablas=', unad24sede AS T24';
		$sCondi=' AND TB.core07idcead=T24.unad24id ';
		}
	if ($sCampos!=''){
		$sGrupoTotal=' GROUP BY '.$sCampos;
		}
	if ($sCampos!=''){$sCampos=$sCampos.', ';}
	if ($bConTotal){
		$sCamposDato='SUM(TB.core07numestudiantes) AS Antiguos, SUM(TB.core07numnuevos) AS Nuevos';
		$sGrupo=' GROUP BY '.$sCampos.'T3.unad40consec, T3.unad40nombre, TB.core07idcurso';
		}
	//Primero calcular los totales.
	$sSQL='SELECT SUM(TB.core07numestudiantes) AS Ant, SUM(TB.core07numnuevos) AS Nuevo 
FROM core07matriculaest AS TB 
WHERE '.$sSQLadd1.' TB.core07idperaca='.$aParametros[103].' '.$sSQLadd.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Totales: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	$fila=$objDB->sf($tabla);
	$iTotalAntiguos=$fila['Ant'];
	$iTotalNuevos=$fila['Nuevo'];
	if ($iTipoRpt==1){
		$sSQL='SELECT T23.unad23id, T23.unad23nombre, SUM(TB.core07numestudiantes+TB.core07numnuevos) AS Total 
FROM core07matriculaest AS TB, unad23zona AS T23 
WHERE '.$sSQLadd1.' TB.core07idperaca='.$aParametros[103].' AND TB.core07idzona=T23.unad23id '.$sSQLadd.'
GROUP BY T23.unad23id, T23.unad23nombre
';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$aZona[$fila['unad23id']]['nombre']=cadena_notildes($fila['unad23nombre']);
			$aZona[$fila['unad23id']]['total']=cadena_notildes($fila['Total']);
			}
		}
	//Ahora si la tabla detalle.
	$sSQL='SELECT '.$sCampos.'T3.unad40consec, T3.unad40nombre, '.$sCamposDato.', TB.core07idcurso 
FROM core07matriculaest AS TB, unad40curso AS T3'.$sTablas.' 
WHERE '.$sSQLadd1.' TB.core07idperaca='.$aParametros[103].' AND TB.core07idcurso=T3.unad40id '.$sCondi.$sSQLadd.$sGrupo.'
ORDER BY '.$sCampos.'TB.core07idcurso';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2207" name="consulta_2207" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2207" name="titulos_2207" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2207: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2207" name="paginaf2207" type="hidden" value="'.$pagina.'"/><input id="lppf2207" name="lppf2207" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$iColumnas=3;
	$sTituloCurso='<td><b>'.$ETI['core07idcurso'].'</b></td>';
	$sTituloCead='';
	if ($bConCead){
		$iColumnas=4;
		$sTituloCead='<td><b>'.$ETI['core07idcead'].'</b></td>';
		}
	if ($iTipoRpt==1){
		$sTituloCurso='';
		$iColumnas--;
		}
	//$sLeyenda=$sLeyenda.'TR '.$iTipoRpt;
	$res=$sErrConsulta.$sLeyenda.'
<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
'.$sTituloCurso.'
'.$sTituloCead.'
<td><b>'.$ETI['core07numestudiantes'].'</b></td>
<td><b>'.$ETI['core07numnuevos'].'</b></td>
</tr>';
	$tlinea=1;
	$iPeraca=-1;
	$iPrograma=-1;
	$idZona=-1;
	$iSubTotal=0;
	$bPrimera=true;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($bConPrograma){
		if ($iPrograma!=$filadet['core07idprograma']){
			$iPrograma=$filadet['core07idprograma'];
			$sNomPrograma='['.$filadet['core07idprograma'].']';
			if (isset($aPrograma[$filadet['core07idprograma']])!=0){
				$sNomPrograma=$aPrograma[$filadet['core07idprograma']];
				}
			$sPaginador='';
			$iColIni=$iColumnas;
			if ($bPrimera){
				$iColIni=$iColumnas-2;
				$sPaginador='<td align="right" colspan="2">
'.html_paginador('paginaf2207', $registros, $lineastabla, $pagina, 'paginarf2207()').'
'.html_lpp('lppf2207', $lineastabla, 'paginarf2207()', 500).'';
				$bPrimera=false;
				}
			$res=$res.'<tr class="fondoazul">
<td colspan="'.$iColIni.'">'.$ETI['core07idprograma'].' <b>'.$sNomPrograma.'</b></td>'.$sPaginador.'
</tr>';
			}
			}else{
			if ($bPrimera){
				$iColIni=$iColumnas-2;
				$bPrimera=false;
				$res=$res.'<tr class="fondoazul">
<td colspan="'.$iColIni.'"></td>
<td align="right" colspan="2">
'.html_paginador('paginaf2207', $registros, $lineastabla, $pagina, 'paginarf2207()').'
'.html_lpp('lppf2207', $lineastabla, 'paginarf2207()', 500).'
</td>
</tr>';
				}
			}
		if ($iTipoRpt==1){
			if ($idZona!=$filadet['unad24idzona']){
				if ($iSubTotal!=0){
					/*
					$res=$res.'<tr>
<td colspan="'.($iColIni).'" align="right"><b>SubTotal Zona</b></td>
<td colspan="2" align="center"><b>'.formato_numero($iSubTotal).'</b></td>
</tr>';
					*/
					$iSubTotal=0;
					}
				$idZona=$filadet['unad24idzona'];
				$sNomZona='['.$filadet['unad24idzona'].']';
				$sTotalZona='';
				if (isset($aZona[$filadet['unad24idzona']]['nombre'])!=0){
					$sNomZona=$aZona[$filadet['unad24idzona']]['nombre'];
					$sTotalZona='Estudiantes '.formato_numero($aZona[$filadet['unad24idzona']]['total']).' ';
					}
				$res=$res.'<tr class="fondoazul">
<td colspan="'.($iColIni+2).'" align="center"><b>'.$sNomZona.'</b> '.$sTotalZona.'</td>
</tr>';
				}
			$iSubTotal=$iSubTotal+$filadet['Antiguos']+$filadet['Nuevos'];
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if ($iTipoRpt==1){
			$sNomCurso='';
			}else{
			$sNomCurso='<td>'.$sPrefijo.$filadet['unad40consec'].' - '.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>';
			}
		$sNomSede='';
		if ($bConCead){
			$sNomSede='<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>';
		/*
			$sNomSede='['.$filadet['core07idcead'].']';
			if (isset($aSede[$filadet['core07idcead']])!=0){
				$sNomSede=$aSede[$filadet['core07idcead']];
				}
			$sNomSede='<td>'.$sPrefijo.$sNomSede.$sSufijo.'</td>';
		*/
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf2207('.$filadet['core07id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
'.$sNomCurso.'
'.$sNomSede.'
<td align="center">'.$sPrefijo.formato_numero($filadet['Antiguos']).$sSufijo.'</td>
<td align="center">'.$sPrefijo.formato_numero($filadet['Nuevos']).$sSufijo.'</td>
</tr>';
		}
	if ($iSubTotal!=0){
		/*
		$res=$res.'<tr>
<td colspan="'.($iColIni).'" align="right"><b>SubTotal Zona</b></td>
<td colspan="2" align="center"><b>'.formato_numero($iSubTotal).'</b></td>
</tr>';
		*/
		}
	$sPaginador='';
	if ($bPrimera){
		$sPaginador='<input id="paginaf2207" name="paginaf2207" type="hidden" value="'.$pagina.'"/><input id="lppf2207" name="lppf2207" type="hidden" value="'.$lineastabla.'"/>';
		}
	$sClass='';
	if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
	$res=$res.'<tr'.$sClass.'>
<td colspan="'.($iColumnas-2).'" rowspan="2" align="right">Totales'.$sPaginador.'</td>
<td align="center">'.formato_numero($iTotalAntiguos).'</td>
<td align="center">'.formato_numero($iTotalNuevos).'</td>
</tr>
<tr'.$sClass.'>
<td colspan="2" align="center"><b>'.formato_numero($iTotalAntiguos+$iTotalNuevos).'</b></td>
</tr>
</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2207_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2207_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2207detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>