<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2019 ---
--- Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
--- Modelo Versión 2.23.7 Friday, October 18, 2019
--- 2901 plab01hv
*/
function f2901_HTMLComboV2_plab01emprbolsempleo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('plab01emprbolsempleo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->iAncho=270;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT TB.plab08id AS id, T1.unad11razonsocial AS nombre 
FROM plab08emprbolsempleo AS TB, unad11terceros AS T1
WHERE TB.plab08idtercero=T1.unad11id ';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2901_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$plab01emprbolsempleo=numeros_validar($datos[1]);
	if ($plab01emprbolsempleo==''){$bHayLlave=false;}
	$plab01idtercero=numeros_validar($datos[2]);
	if ($plab01idtercero==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT plab01idtercero FROM plab01hv WHERE plab01emprbolsempleo='.$plab01emprbolsempleo.' AND plab01idtercero='.$plab01idtercero.'';
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
function f2901_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
	require $mensajes_todas;
	require $mensajes_2901;
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
		case 'plab01idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2901);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2901'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2901_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'plab01idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2901_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
	require $mensajes_todas;
	require $mensajes_2901;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[105])==0){$aParametros[105]='';}//Fecha Registro Desde
	if (isset($aParametros[106])==0){$aParametros[106]='';}//Fecha Registro Hasta
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
		return array($sLeyenda.'<input id="paginaf2901" name="paginaf2901" type="hidden" value="'.$pagina.'"/><input id="lppf2901" name="lppf2901" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	if ($aParametros[105]!=0){ //Fecha Registro Desde
		$sSQLadd=$sSQLadd.' AND TB.plab01fechareg >= "'.$aParametros[105].'" ';
		}
	if ($aParametros[106]!=0){ //Fecha Registro Hasta
        $sSQLadd=$sSQLadd.' AND TB.plab01fechareg <= "'.$aParametros[106].'" ';
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
	$sTitulos='Emprbolsempleo, Tercero, Id, Fechareg, Telprin, Telofic, Telmov, Correo, Ultprof, Aspsal, Nomemprultexp, Cargo, Industria, Sector, Fechainiexp, Fechafinexp, Nivingles, Fechaacthv, Numpostula, Condicion';
	$sSQL='SELECT T1.plab08idtercero, T2.unad11razonsocial AS C2_nombre, TB.plab01id, TB.plab01fechareg, TB.plab01telprin, TB.plab01telofic, TB.plab01telmov, TB.plab01correo, T9.plab02nombre, T10.plab16nombre, TB.plab01nomemprultexp, T12.plab04nombre, T13.plab05nombre, T14.plab06nombre, TB.plab01fechainiexp, TB.plab01fechafinexp, T17.plab07nombre, TB.plab01fechaacthv, TB.plab01numpostula, T20.plab15nombre, TB.plab01emprbolsempleo, TB.plab01idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.plab01ultprof, TB.plab01aspsal, TB.plab01cargo, TB.plab01industria, TB.plab01sector, TB.plab01nivingles, TB.plab01condicion 
FROM plab01hv AS TB, plab08emprbolsempleo AS T1, unad11terceros AS T2, plab02prof AS T9, plab16aspsala AS T10, plab04cargo AS T12, plab05industria AS T13, plab06sector AS T14, plab07nivingles AS T17, plab15hvcondicion AS T20 
WHERE '.$sSQLadd1.' TB.plab01emprbolsempleo=T1.plab08id AND TB.plab01idtercero=T2.unad11id AND TB.plab01ultprof=T9.plab02id AND TB.plab01aspsal=T10.plab16id AND TB.plab01cargo=T12.plab04id AND TB.plab01industria=T13.plab05id AND TB.plab01sector=T14.plab06id AND TB.plab01nivingles=T17.plab07id AND TB.plab01condicion=T20.plab15id '.$sSQLadd.'
ORDER BY TB.plab01emprbolsempleo, TB.plab01idtercero';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2901" name="consulta_2901" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2901" name="titulos_2901" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2901: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2901" name="paginaf2901" type="hidden" value="'.$pagina.'"/><input id="lppf2901" name="lppf2901" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab01emprbolsempleo'].'</b></td>
<td colspan="2"><b>'.$ETI['plab01idtercero'].'</b></td>
<td><b>'.$ETI['plab01fechareg'].'</b></td>
<td><b>'.$ETI['plab01numpostula'].'</b></td>
<td><b>'.$ETI['plab01condicion'].'</b></td>
<td align="right">
'.html_paginador('paginaf2901', $registros, $lineastabla, $pagina, 'paginarf2901()').'
'.html_lpp('lppf2901', $lineastabla, 'paginarf2901()').'
</td>
</tr>';
	$aplab08idtercero=array();
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
		$et_plab01fechareg='';
		if ($filadet['plab01fechareg']!=0){$et_plab01fechareg=fecha_desdenumero($filadet['plab01fechareg']);}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2901('.$filadet['plab01id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$i_plab08idtercero=$filadet['plab08idtercero'];
		if (isset($aplab08idtercero[$i_plab08idtercero])==0){
			$sSQL='SELECT unad11razonsocial FROM unad11terceros WHERE unad11id='.$i_plab08idtercero.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $aplab08idtercero[$i_plab08idtercero]=$filae['unad11razonsocial'];
				}else{
                $aplab08idtercero[$i_plab08idtercero]='';
				}
			}
        $lin_plab08idtercero=utf8_decode($aplab08idtercero[$i_plab08idtercero]);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($lin_plab08idtercero).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab01fechareg.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab01numpostula'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab15nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2901_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2901_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2901detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2901_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['plab01idtercero_td']=$APP->tipo_doc;
	$DATA['plab01idtercero_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='plab01emprbolsempleo='.$DATA['plab01emprbolsempleo'].' AND plab01idtercero="'.$DATA['plab01idtercero'].'"';
		}else{
		$sSQLcondi='plab01id='.$DATA['plab01id'].'';
		}
	$sSQL='SELECT * FROM plab01hv WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['plab01emprbolsempleo']=$fila['plab01emprbolsempleo'];
		$DATA['plab01idtercero']=$fila['plab01idtercero'];
		$DATA['plab01id']=$fila['plab01id'];
		$DATA['plab01fechareg']=$fila['plab01fechareg'];
		$DATA['plab01telprin']=$fila['plab01telprin'];
		$DATA['plab01telofic']=$fila['plab01telofic'];
		$DATA['plab01telmov']=$fila['plab01telmov'];
		$DATA['plab01correo']=$fila['plab01correo'];
		$DATA['plab01ultprof']=$fila['plab01ultprof'];
		$DATA['plab01aspsal']=$fila['plab01aspsal'];
		$DATA['plab01nomemprultexp']=$fila['plab01nomemprultexp'];
		$DATA['plab01cargo']=$fila['plab01cargo'];
		$DATA['plab01industria']=$fila['plab01industria'];
		$DATA['plab01sector']=$fila['plab01sector'];
		$DATA['plab01fechainiexp']=$fila['plab01fechainiexp'];
		$DATA['plab01fechafinexp']=$fila['plab01fechafinexp'];
		$DATA['plab01nivingles']=$fila['plab01nivingles'];
		$DATA['plab01fechaacthv']=$fila['plab01fechaacthv'];
		$DATA['plab01numpostula']=$fila['plab01numpostula'];
		$DATA['plab01condicion']=$fila['plab01condicion'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2901']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2901_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2901;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
	require $mensajes_todas;
	require $mensajes_2901;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['plab01emprbolsempleo'])==0){$DATA['plab01emprbolsempleo']='';}
	if (isset($DATA['plab01idtercero'])==0){$DATA['plab01idtercero']='';}
	if (isset($DATA['plab01id'])==0){$DATA['plab01id']='';}
	if (isset($DATA['plab01fechareg'])==0){$DATA['plab01fechareg']='';}
	if (isset($DATA['plab01telprin'])==0){$DATA['plab01telprin']='';}
	if (isset($DATA['plab01telofic'])==0){$DATA['plab01telofic']='';}
	if (isset($DATA['plab01telmov'])==0){$DATA['plab01telmov']='';}
	if (isset($DATA['plab01correo'])==0){$DATA['plab01correo']='';}
	if (isset($DATA['plab01ultprof'])==0){$DATA['plab01ultprof']='';}
	if (isset($DATA['plab01aspsal'])==0){$DATA['plab01aspsal']='';}
	if (isset($DATA['plab01nomemprultexp'])==0){$DATA['plab01nomemprultexp']='';}
	if (isset($DATA['plab01cargo'])==0){$DATA['plab01cargo']='';}
	if (isset($DATA['plab01industria'])==0){$DATA['plab01industria']='';}
	if (isset($DATA['plab01sector'])==0){$DATA['plab01sector']='';}
	if (isset($DATA['plab01fechainiexp'])==0){$DATA['plab01fechainiexp']='';}
	if (isset($DATA['plab01fechafinexp'])==0){$DATA['plab01fechafinexp']='';}
	if (isset($DATA['plab01nivingles'])==0){$DATA['plab01nivingles']='';}
	if (isset($DATA['plab01fechaacthv'])==0){$DATA['plab01fechaacthv']='';}
	if (isset($DATA['plab01numpostula'])==0){$DATA['plab01numpostula']='';}
	if (isset($DATA['plab01condicion'])==0){$DATA['plab01condicion']='';}
	*/
	$DATA['plab01emprbolsempleo']=numeros_validar($DATA['plab01emprbolsempleo']);
	$DATA['plab01telprin']=htmlspecialchars(trim($DATA['plab01telprin']));
	$DATA['plab01telofic']=htmlspecialchars(trim($DATA['plab01telofic']));
	$DATA['plab01telmov']=htmlspecialchars(trim($DATA['plab01telmov']));
	$DATA['plab01correo']=htmlspecialchars(trim($DATA['plab01correo']));
	$DATA['plab01ultprof']=numeros_validar($DATA['plab01ultprof']);
	$DATA['plab01aspsal']=numeros_validar($DATA['plab01aspsal']);
	$DATA['plab01nomemprultexp']=htmlspecialchars(trim($DATA['plab01nomemprultexp']));
	$DATA['plab01cargo']=numeros_validar($DATA['plab01cargo']);
	$DATA['plab01industria']=numeros_validar($DATA['plab01industria']);
	$DATA['plab01sector']=numeros_validar($DATA['plab01sector']);
	$DATA['plab01nivingles']=numeros_validar($DATA['plab01nivingles']);
	$DATA['plab01numpostula']=numeros_validar($DATA['plab01numpostula']);
	$DATA['plab01condicion']=numeros_validar($DATA['plab01condicion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['plab01ultprof']==''){$DATA['plab01ultprof']=0;}
	//if ($DATA['plab01aspsal']==''){$DATA['plab01aspsal']=0;}
	//if ($DATA['plab01cargo']==''){$DATA['plab01cargo']=0;}
	//if ($DATA['plab01industria']==''){$DATA['plab01industria']=0;}
	//if ($DATA['plab01sector']==''){$DATA['plab01sector']=0;}
	//if ($DATA['plab01nivingles']==''){$DATA['plab01nivingles']=0;}
	//if ($DATA['plab01numpostula']==''){$DATA['plab01numpostula']=0;}
	//if ($DATA['plab01condicion']==''){$DATA['plab01condicion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['plab01condicion']==''){$sError=$ERR['plab01condicion'].$sSepara.$sError;}
		if ($DATA['plab01numpostula']==''){$sError=$ERR['plab01numpostula'].$sSepara.$sError;}
		if ($DATA['plab01fechaacthv']==0){
			//$DATA['plab01fechaacthv']=fecha_DiaMod();
			$sError=$ERR['plab01fechaacthv'].$sSepara.$sError;
			}
		if ($DATA['plab01nivingles']==''){$sError=$ERR['plab01nivingles'].$sSepara.$sError;}
		if ($DATA['plab01fechafinexp']==0){
			//$DATA['plab01fechafinexp']=fecha_DiaMod();
			$sError=$ERR['plab01fechafinexp'].$sSepara.$sError;
			}
		if ($DATA['plab01fechainiexp']==0){
			//$DATA['plab01fechainiexp']=fecha_DiaMod();
			$sError=$ERR['plab01fechainiexp'].$sSepara.$sError;
			}
		if ($DATA['plab01sector']==''){$sError=$ERR['plab01sector'].$sSepara.$sError;}
		if ($DATA['plab01industria']==''){$sError=$ERR['plab01industria'].$sSepara.$sError;}
		if ($DATA['plab01cargo']==''){$sError=$ERR['plab01cargo'].$sSepara.$sError;}
		if ($DATA['plab01nomemprultexp']==''){$sError=$ERR['plab01nomemprultexp'].$sSepara.$sError;}
		if ($DATA['plab01aspsal']==''){$sError=$ERR['plab01aspsal'].$sSepara.$sError;}
		if ($DATA['plab01ultprof']==''){$sError=$ERR['plab01ultprof'].$sSepara.$sError;}
		if ($DATA['plab01correo']==''){$sError=$ERR['plab01correo'].$sSepara.$sError;}
		if ($DATA['plab01telmov']==''){$sError=$ERR['plab01telmov'].$sSepara.$sError;}
		if ($DATA['plab01telofic']==''){$sError=$ERR['plab01telofic'].$sSepara.$sError;}
		if ($DATA['plab01telprin']==''){$sError=$ERR['plab01telprin'].$sSepara.$sError;}
		if ($DATA['plab01fechareg']==0){
			//$DATA['plab01fechareg']=fecha_DiaMod();
			$sError=$ERR['plab01fechareg'].$sSepara.$sError;
			}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['plab01idtercero']==0){$sError=$ERR['plab01idtercero'];}
	if ($DATA['plab01emprbolsempleo']==''){$sError=$ERR['plab01emprbolsempleo'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['plab01idtercero_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['plab01idtercero_td'], $DATA['plab01idtercero_doc'], $objDB, 'El tercero Tercero ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['plab01idtercero'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT 1 FROM plab01hv WHERE plab01emprbolsempleo='.$DATA['plab01emprbolsempleo'].' AND plab01idtercero="'.$DATA['plab01idtercero'].'"';
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
			$DATA['plab01id']=tabla_consecutivo('plab01hv','plab01id', '', $objDB);
			if ($DATA['plab01id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$plab01fechareg=fecha_DiaMod();
			$plab01fechainiexp=fecha_DiaMod();
			$plab01fechafinexp=fecha_DiaMod();
			$plab01fechaacthv=fecha_DiaMod();
			$sCampos2901='plab01emprbolsempleo, plab01idtercero, plab01id, plab01fechareg, plab01telprin, plab01telofic, plab01telmov, plab01correo, plab01ultprof, plab01aspsal, 
plab01nomemprultexp, plab01cargo, plab01industria, plab01sector, plab01fechainiexp, plab01fechafinexp, plab01nivingles, plab01fechaacthv, plab01numpostula, plab01condicion';
			$sValores2901=''.$DATA['plab01emprbolsempleo'].', '.$DATA['plab01idtercero'].', '.$DATA['plab01id'].', "'.$DATA['plab01fechareg'].'", "'.$DATA['plab01telprin'].'", "'.$DATA['plab01telofic'].'", "'.$DATA['plab01telmov'].'", "'.$DATA['plab01correo'].'", '.$DATA['plab01ultprof'].', '.$DATA['plab01aspsal'].', 
"'.$DATA['plab01nomemprultexp'].'", '.$DATA['plab01cargo'].', '.$DATA['plab01industria'].', '.$DATA['plab01sector'].', "'.$DATA['plab01fechainiexp'].'", "'.$DATA['plab01fechafinexp'].'", '.$DATA['plab01nivingles'].', "'.$DATA['plab01fechaacthv'].'", '.$DATA['plab01numpostula'].', '.$DATA['plab01condicion'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab01hv ('.$sCampos2901.') VALUES ('.utf8_encode($sValores2901).');';
				$sdetalle=$sCampos2901.'['.utf8_encode($sValores2901).']';
				}else{
				$sSQL='INSERT INTO plab01hv ('.$sCampos2901.') VALUES ('.$sValores2901.');';
				$sdetalle=$sCampos2901.'['.$sValores2901.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='plab01fechareg';
			$scampo[2]='plab01telprin';
			$scampo[3]='plab01telofic';
			$scampo[4]='plab01telmov';
			$scampo[5]='plab01correo';
			$scampo[6]='plab01ultprof';
			$scampo[7]='plab01aspsal';
			$scampo[8]='plab01nomemprultexp';
			$scampo[9]='plab01cargo';
			$scampo[10]='plab01industria';
			$scampo[11]='plab01sector';
			$scampo[12]='plab01fechainiexp';
			$scampo[13]='plab01fechafinexp';
			$scampo[14]='plab01nivingles';
			$scampo[15]='plab01fechaacthv';
			$scampo[16]='plab01numpostula';
			$scampo[17]='plab01condicion';
			$sdato[1]=$DATA['plab01fechareg'];
			$sdato[2]=$DATA['plab01telprin'];
			$sdato[3]=$DATA['plab01telofic'];
			$sdato[4]=$DATA['plab01telmov'];
			$sdato[5]=$DATA['plab01correo'];
			$sdato[6]=$DATA['plab01ultprof'];
			$sdato[7]=$DATA['plab01aspsal'];
			$sdato[8]=$DATA['plab01nomemprultexp'];
			$sdato[9]=$DATA['plab01cargo'];
			$sdato[10]=$DATA['plab01industria'];
			$sdato[11]=$DATA['plab01sector'];
			$sdato[12]=$DATA['plab01fechainiexp'];
			$sdato[13]=$DATA['plab01fechafinexp'];
			$sdato[14]=$DATA['plab01nivingles'];
			$sdato[15]=$DATA['plab01fechaacthv'];
			$sdato[16]=$DATA['plab01numpostula'];
			$sdato[17]=$DATA['plab01condicion'];
			$numcmod=17;
			$sWhere='plab01id='.$DATA['plab01id'].'';
			$sSQL='SELECT * FROM plab01hv WHERE '.$sWhere;
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
					$sSQL='UPDATE plab01hv SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE plab01hv SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2901] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){
					$DATA['plab01id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2901 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['plab01id'], $sdetalle, $objDB);}
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
function f2901_db_Eliminar($plab01id, $objDB, $bDebug=false){
	$iCodModulo=2901;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
	require $mensajes_todas;
	require $mensajes_2901;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$plab01id=numeros_validar($plab01id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM plab01hv WHERE plab01id='.$plab01id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$plab01id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2901';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['plab01id'].' LIMIT 0, 1';
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
		$sWhere='plab01id='.$plab01id.'';
		//$sWhere='plab01idtercero="'.$filabase['plab01idtercero'].'" AND plab01emprbolsempleo='.$filabase['plab01emprbolsempleo'].'';
		$sSQL='DELETE FROM plab01hv WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab01id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2901_TituloBusqueda(){
	return 'Busqueda de hoja de vida';
	}
function f2901_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2901nombre" name="b2901nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2901_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2901nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2901_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
	require $mensajes_todas;
	require $mensajes_2901;
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
		return array($sLeyenda.'<input id="paginaf2901" name="paginaf2901" type="hidden" value="'.$pagina.'"/><input id="lppf2901" name="lppf2901" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Emprbolsempleo, Tercero, Id, Fechareg, Telprin, Telofic, Telmov, Correo, Ultprof, Aspsal, Nomemprultexp, Cargo, Industria, Sector, Fechainiexp, Fechafinexp, Nivingles, Fechaacthv, Numpostula, Condicion';
	$sSQL='SELECT T1.plab08idtercero, T2.unad11razonsocial AS C2_nombre, TB.plab01id, TB.plab01fechareg, TB.plab01telprin, TB.plab01telofic, TB.plab01telmov, TB.plab01correo, T9.plab02nombre, T10.plab16nombre, TB.plab01nomemprultexp, T12.plab04nombre, T13.plab05nombre, T14.plab06nombre, TB.plab01fechainiexp, TB.plab01fechafinexp, T17.plab07nombre, TB.plab01fechaacthv, TB.plab01numpostula, T20.plab15nombre, TB.plab01emprbolsempleo, TB.plab01idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.plab01ultprof, TB.plab01aspsal, TB.plab01cargo, TB.plab01industria, TB.plab01sector, TB.plab01nivingles, TB.plab01condicion 
FROM plab01hv AS TB, plab08emprbolsempleo AS T1, unad11terceros AS T2, plab02prof AS T9, plab16aspsala AS T10, plab04cargo AS T12, plab05industria AS T13, plab06sector AS T14, plab07nivingles AS T17, plab15hvcondicion AS T20 
WHERE '.$sSQLadd1.' TB.plab01emprbolsempleo=T1.plab08id AND TB.plab01idtercero=T2.unad11id AND TB.plab01ultprof=T9.plab02id AND TB.plab01aspsal=T10.plab16id AND TB.plab01cargo=T12.plab04id AND TB.plab01industria=T13.plab05id AND TB.plab01sector=T14.plab06id AND TB.plab01nivingles=T17.plab07id AND TB.plab01condicion=T20.plab15id '.$sSQLadd.'
ORDER BY TB.plab01emprbolsempleo, TB.plab01idtercero';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2901" name="paginaf2901" type="hidden" value="'.$pagina.'"/><input id="lppf2901" name="lppf2901" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab01emprbolsempleo'].'</b></td>
<td colspan="2"><b>'.$ETI['plab01idtercero'].'</b></td>
<td><b>'.$ETI['plab01fechareg'].'</b></td>
<td><b>'.$ETI['plab01telprin'].'</b></td>
<td><b>'.$ETI['plab01telofic'].'</b></td>
<td><b>'.$ETI['plab01telmov'].'</b></td>
<td><b>'.$ETI['plab01correo'].'</b></td>
<td><b>'.$ETI['plab01ultprof'].'</b></td>
<td><b>'.$ETI['plab01aspsal'].'</b></td>
<td><b>'.$ETI['plab01nomemprultexp'].'</b></td>
<td><b>'.$ETI['plab01cargo'].'</b></td>
<td><b>'.$ETI['plab01industria'].'</b></td>
<td><b>'.$ETI['plab01sector'].'</b></td>
<td><b>'.$ETI['plab01fechainiexp'].'</b></td>
<td><b>'.$ETI['plab01fechafinexp'].'</b></td>
<td><b>'.$ETI['plab01nivingles'].'</b></td>
<td><b>'.$ETI['plab01fechaacthv'].'</b></td>
<td><b>'.$ETI['plab01numpostula'].'</b></td>
<td><b>'.$ETI['plab01condicion'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['plab01id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_plab01fechareg='';
		if ($filadet['plab01fechareg']!=0){$et_plab01fechareg=fecha_desdenumero($filadet['plab01fechareg']);}
		$et_plab01fechainiexp='';
		if ($filadet['plab01fechainiexp']!=0){$et_plab01fechainiexp=fecha_desdenumero($filadet['plab01fechainiexp']);}
		$et_plab01fechafinexp='';
		if ($filadet['plab01fechafinexp']!=0){$et_plab01fechafinexp=fecha_desdenumero($filadet['plab01fechafinexp']);}
		$et_plab01fechaacthv='';
		if ($filadet['plab01fechaacthv']!=0){$et_plab01fechaacthv=fecha_desdenumero($filadet['plab01fechaacthv']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['plab08idtercero']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab01fechareg.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab01telprin']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab01telofic']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab01telmov']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab01correo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab16nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab01nomemprultexp']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab04nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab05nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab06nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab01fechainiexp.$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab01fechafinexp.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab07nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab01fechaacthv.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab01numpostula'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab15nombre']).$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
/** Función f2901_ProcesarArchivo.
* Esta función recibe un archivo y lo procesa.
* @author Cristhiam Dario Silva Chavez - cristhiam.silva@unad.edu.co
* @param $DATA contiene las variables $_REQUEST del formulario de origen
* @param $ARCHIVO contiene las variables $_FILE del formulario de origen
* @param $objDB Objeto de base datos del tipo clsdbadmin
* @param $bDebug (Opcional), bandera para indicar si se generan datos de depuración
* @date Thursday, October 31, 2019
*/
function f2901_ProcesarArchivo($DATA, $ARCHIVO, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sInfoProceso='';
	$sDebug='';
	$sArchivo=$ARCHIVO['archivodatos']['tmp_name'];
	$sVerExcel='Excel2007';
	switch($ARCHIVO['archivodatos']['type']){
		case 'application/vnd.ms-excel':
		$sVerExcel='Excel5';
		break;
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
		break;
		case '':
		case 'application/download':
		$sExt=pathinfo($ARCHIVO['archivodatos']['name'], PATHINFO_EXTENSION);
		switch ($sExt){
			case 'xls':
			$sVerExcel='Excel5';
			break;
			case 'xlsx':
			break;
			default:
			$sError='Tipo de archivo no permitido {'.$ARCHIVO['archivodatos']['type'].' - '.$sExt.' - '.$sArchivo.'}';
			}
		break;
		default:
		$sError='Tipo de archivo no permitido {'.$ARCHIVO['archivodatos']['type'].'}';
		}
	if ($sError==''){
		if (!file_exists($sArchivo)){
			$sError='El archivo no fue cargado correctamente ['.$ARCHIVO['archivodatos']['name'].' - '.$ARCHIVO['archivodatos']['tmp_name'].']';
			}
		}
	if ($sError==''){
		require './app.php';
		require $APP->rutacomun.'excel/PHPExcel.php';
		require $APP->rutacomun.'excel/PHPExcel/Writer/Excel2007.php';
		$objReader=PHPExcel_IOFactory::createReader($sVerExcel);
		$objPHPExcel=@$objReader->load($sArchivo);
		if (!is_object(@$objPHPExcel->getActiveSheet())){
			$sError='El archivo se cargo en forma correcta, pero no fue posible leerlo en '.$sVerExcel;
			}
		}
	if ($sError==''){
        $iHoja=1;
		$iFila=2;
		$iDatos=0;
		$iActualizados=0;
        $iRegistrados=0;
        $iNoEncontrados=0;
        $sNoEncontrados='';
		//$sCampos2901='plab01emprbolsempleo, plab01idtercero, plab01id, plab01fechareg, plab01telprin, plab01telofic, plab01telmov, plab01correo, plab01ultprof, plab01aspsal, plab01nomemprultexp, plab01cargo, plab01industria, plab01sector, plab01fechainiexp, plab01fechafinexp, plab01nivingles, plab01fechaacthv, plab01numpostula, plab01condicion';
		//$plab01id=tabla_consecutivo('plab01hv','plab01id', '', $objDB);
        $plab01emprbolsempleo=$DATA['plab01emprbolsempleo'];
        $plab01condicion=0; // vigente
        $plab01fechareg=0;
        $plab01ultprof=array();
        $plab01aspsal=array();
        $plab01cargo=array();
        $plab01industria=array();
        $plab01sector=array();
        $plab01nivingles=array();
        $sFormato='dd/mm/YYYY';
        switch ($plab01emprbolsempleo) {
            case 1: $sFormato='YYYY/mm/dd';
            $iHoja=1;
            $iFila=8;
            $aColumnas=array('fechareg'=>2,'documento'=>7,'telprin'=>9,'telofic'=>10,'telmov'=>11,'correo'=>15,'ultprof'=>17,'aspsal'=>19,'nomemprultexp'=>20,'cargo'=>21,'industria'=>23,'sector'=>24,'fechainiexp'=>25,'fechafinexp'=>26,'nivingles'=>27,'fechaacthv'=>28);
            $objPHPExcel->setActiveSheetIndex($iHoja);
            break;
            case 2:
            case 3:
            case 4:
            break;
            }
        if (empty($aColumnas)){
            $sError='<br>No se han establecido los par&aacute;metros para la bolsa de empleo';
            } else{
            $sSQL='SELECT plab02id, plab02nombre FROM plab02prof WHERE plab02activo="S"';
            $tabla=$objDB->ejecutasql($sSQL);
            while ($fila=$objDB->sf($tabla)){
                $plab01ultprof[$fila['plab02nombre']]=$fila['plab02id'];
                }
            $sSQL='SELECT plab16id, plab16nombre FROM plab16aspsala WHERE plab16activo="S"';
            $tabla=$objDB->ejecutasql($sSQL);
            while ($fila=$objDB->sf($tabla)){
                $plab01aspsal[$fila['plab16nombre']]=$fila['plab16id'];
                }
            $sSQL='SELECT plab04id, plab04nombre FROM plab04cargo WHERE plab04activo="S"';
            $tabla=$objDB->ejecutasql($sSQL);
            while ($fila=$objDB->sf($tabla)){
                $plab01cargo[$fila['plab04nombre']]=$fila['plab04id'];
                }
            $sSQL='SELECT plab05id, plab05nombre FROM plab05industria WHERE plab05activo="S"';
            $tabla=$objDB->ejecutasql($sSQL);
            while ($fila=$objDB->sf($tabla)){
                $plab01industria[$fila['plab05nombre']]=$fila['plab05id'];
                }
            $sSQL='SELECT plab06id, plab06nombre FROM plab06sector WHERE plab06activo="S"';
            $tabla=$objDB->ejecutasql($sSQL);
            while ($fila=$objDB->sf($tabla)){
                $plab01sector[$fila['plab06nombre']]=$fila['plab06id'];
                }
            $sSQL='SELECT plab07id, plab07nombre FROM plab07nivingles WHERE plab07activo="S"';
            $tabla=$objDB->ejecutasql($sSQL);
            while ($fila=$objDB->sf($tabla)){
                $plab01nivingles[$fila['plab07nombre']]=$fila['plab07id'];
                }
            $sDato=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['documento'], $iFila)->getValue(); // Documento
            while($sDato!=''){
                $iDatos++;
                $sErrLinea='';
                //Aqui debe procesar linea
                $plab01fechainiexp=0;
                $plab01fechafinexp=0;
                $plab01fechaacthv=0;
                if($plab01emprbolsempleo==1){
                    $fecha=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['fechareg'], $iFila)->getValue(); // Fecha registro
                    if ($fecha != '' && $fecha != 'NA'){
                        list($sErrLinea, $iDia, $iMes, $iAgno)=fecha_Validar($fecha, $sFormato);
                        if ($sErrLinea==''){
                            $plab01fechareg=fecha_ArmarNumero($iDia, $iMes, $iAgno);
                            }
                        }
                    if ($sErrLinea == ''){
                        $fecha=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['fechainiexp'], $iFila)->getValue(); // Fecha inicio exp laboral
                        if ($fecha != '' && $fecha != 'NA'){
                            list($sErrLinea, $iDia, $iMes, $iAgno)=fecha_Validar($fecha, $sFormato);
                            if ($sErrLinea==''){
                                $plab01fechainiexp=fecha_ArmarNumero($iDia, $iMes, $iAgno);
                                }
                            }
                        }
                    if ($sErrLinea == ''){
                        $fecha=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['fechafinexp'], $iFila)->getValue(); // Fecha fin exp laboral
                        if ($fecha != '' && $fecha != 'NA'){
                            list($sErrLinea, $iDia, $iMes, $iAgno)=fecha_Validar($fecha, $sFormato);
                            if ($sErrLinea==''){
                                $plab01fechafinexp=fecha_ArmarNumero($iDia, $iMes, $iAgno);
                                }
                            }
                        }
                    if ($sErrLinea == ''){
                        $fecha=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['fechaacthv'], $iFila)->getValue(); // Fecha actualizacion hv
                        if ($fecha != '' && $fecha != 'NA'){
                            list($sErrLinea, $iDia, $iMes, $iAgno)=fecha_Validar($fecha, $sFormato);
                            if ($sErrLinea==''){
                                $plab01fechaacthv=fecha_ArmarNumero($iDia, $iMes, $iAgno);
                                }
                            }
                        }
                    if ($sErrLinea == ''){
                        $sDatoCampo02=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['ultprof'], $iFila)->getValue(); // Ultima Profesion
                        if ($sDatoCampo02 != ''){ // Ultima Profesion
                            if (isset($plab01ultprof[$sDatoCampo02]) == 0){
                                $plab02consec=tabla_consecutivo('plab02prof', 'plab02consecutivo', '', $objDB);
                                $plab02id=tabla_consecutivo('plab02prof', 'plab02id', '', $objDB);
                                $plab02programa=0;
                                $sSQL='SELECT core09id FROM core09programa WHERE core09nombre LIKE "%'.$sDatoCampo02.'%"';
                                $tabla=$objDB->ejecutasql($sSQL);
                                if ($objDB->nf($tabla) > 0){
                                    $fila=$objDB->sf($tabla);
                                    $plab02programa=$fila['core09id'];
                                    }
                                $sCampos02 = 'plab02consecutivo, plab02id, plab02nombre, plab02programa, plab02activo';
                                $sValores02 = ''.$plab02consec.', '.$plab02id. ', "'.$sDatoCampo02.'", '.$plab02programa.', "S"';
                                $sSQL='INSERT INTO plab02prof ('.$sCampos02.') VALUES ('.$sValores02.')';
                                $result = $objDB->ejecutasql($sSQL);
                                if ($result == false) {
                                    if ($bDebug) {
                                        $sDebug=$sDebug.fecha_microtiempo().'Consulta falla Insert: '.$sSQL.'<br>';
                                        }
                                    }
                                $plab01ultprof[$sDatoCampo02] = $plab02id;
                                }
                            }
                        $sDatoCampo16=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['aspsal'], $iFila)->getValue(); // Aspiracion salarial
                        if ($sDatoCampo16 != ''){ // Aspiracion salarial
                            if (isset($plab01aspsal[$sDatoCampo16]) == 0){
                                $plab16consec = tabla_consecutivo('plab16aspsala', 'plab16consecutivo', '', $objDB);
                                $plab16id = tabla_consecutivo('plab16aspsala', 'plab16id', '', $objDB);
                                $sCampos16 = 'plab16consecutivo, plab16id, plab16nombre, plab16activo';
                                $sValores16 = ''.$plab16consec.', '.$plab16id.', "'.$sDatoCampo16.'", "S"';
                                $sSQL = 'INSERT INTO plab16aspsala ('.$sCampos16.') VALUES ('.$sValores16.')';
                                $result = $objDB->ejecutasql($sSQL);
                                if ($result == false){
                                    if ($bDebug){
                                        $sDebug=$sDebug.fecha_microtiempo().'Consulta falla Insert: '.$sSQL.'<br>';
                                        }
                                    }
                                $plab01aspsal[$sDatoCampo16]=$plab16id;
                                }
                            }
                        $sDatoCampo04=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['cargo'], $iFila)->getValue(); // Cargo
                        if ($sDatoCampo04 != ''){ // Cargo
                            if (isset($plab01cargo[$sDatoCampo04]) == 0){
                                $plab04consec=tabla_consecutivo('plab04cargo', 'plab04consecutivo', '', $objDB);
                                $plab04id=tabla_consecutivo('plab04cargo', 'plab04id', '', $objDB);
                                $sCampos04='plab04consecutivo, plab04id, plab04nombre, plab04activo';
                                $sValores04=''.$plab04consec.', '.$plab04id.', "'.$sDatoCampo04.'", "S"';
                                $sSQL='INSERT INTO plab04cargo ('.$sCampos04.')  VALUES ('.$sValores04.')';
                                $result=$objDB->ejecutasql($sSQL);
                                if ($result == false){
                                    if ($bDebug){
                                        $sDebug=$sDebug.fecha_microtiempo().'Consulta falla Insert: '.$sSQL.'<br>';
                                        }
                                    }
                                $plab01cargo[$sDatoCampo04] = $plab04id;
                                }
                            }
                        $sDatoCampo05=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['industria'], $iFila)->getValue(); // Industria
                        if ($sDatoCampo05 != ''){ // Industria
                            if (isset($plab01industria[$sDatoCampo05]) == 0){
                                $plab05consec=tabla_consecutivo('plab05industria', 'plab05consecutivo', '', $objDB);
                                $plab05id=tabla_consecutivo('plab05industria', 'plab05id', '', $objDB);
                                $sCampos05='plab05consecutivo, plab05id, plab05nombre, plab05activo';
                                $sValores05=''.$plab05consec.', '.$plab05id.', "'.$sDatoCampo05.'", "S"';
                                $sSQL='INSERT INTO plab05industria ('.$sCampos05.') VALUES ('.$sValores05.')';
                                $result=$objDB->ejecutasql($sSQL);
                                if ($result == false){
                                    if ($bDebug){
                                        $sDebug=$sDebug.fecha_microtiempo().'Consulta falla Insert: '.$sSQL.'<br>';
                                        }
                                    }
                                $plab01industria[$sDatoCampo05]=$plab05id;
                                }
                            }
                        $sDatoCampo06=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['sector'], $iFila)->getValue(); // Sector
                        if ($sDatoCampo06 != ''){ // Sector
                            if (isset($plab01sector[$sDatoCampo06]) == 0){
                                $plab06consec=tabla_consecutivo('plab06sector', 'plab06consecutivo', '', $objDB);
                                $plab06id=tabla_consecutivo('plab06sector', 'plab06id', '', $objDB);
                                $sCampos06='plab06consecutivo, plab06id, plab06nombre, plab06activo';
                                $sValores06=''.$plab06consec.', '.$plab06id.', "'.$sDatoCampo06.'", "S"';
                                $sSQL='INSERT INTO plab06sector ('.$sCampos06.') VALUES ('.$sValores06.')';
                                $result=$objDB->ejecutasql($sSQL);
                                if ($result == false){
                                    if ($bDebug){
                                        $sDebug=$sDebug.fecha_microtiempo().'Consulta falla Insert: '.$sSQL.'<br>';
                                        }
                                    }
                                $plab01sector[$sDatoCampo06] = $plab06id;
                                }
                            }
                        $sDatoCampo07=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['nivingles'], $iFila)->getValue(); // Nivel de ingles
                        if ($sDatoCampo07 != ''){ // Nivel de ingles
                            if (isset($plab01nivingles[$sDatoCampo07]) == 0){
                                $plab07consec=tabla_consecutivo('plab07nivingles', 'plab07consecutivo', '', $objDB);
                                $plab07id=tabla_consecutivo('plab07nivingles', 'plab07id', '', $objDB);
                                $sCampos07='plab07consecutivo, plab07id, plab07nombre, plab07activo';
                                $sValores07=''.$plab07consec.', '.$plab07id.', "'.$sDatoCampo07.'", "S"';
                                $sSQL='INSERT INTO plab07nivingles ('.$sCampos07.') VALUES ('.$sValores07.')';
                                $result=$objDB->ejecutasql($sSQL);
                                if ($result == false){
                                    if ($bDebug){
                                        $sDebug=$sDebug.fecha_microtiempo().'Consulta falla Insert: '.$sSQL.'<br>';
                                        }
                                    }
                                $plab01nivingles[$sDatoCampo07] = $plab07id;
                                }
                            }
                        $plab01telprin=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['telprin'], $iFila)->getValue(); // Telefono principal
                        $plab01telofic=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['telofic'], $iFila)->getValue(); // Telefono oficina
                        $plab01telmov=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['telmov'], $iFila)->getValue(); // Telefono movil
                        $plab01correo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['correo'], $iFila)->getValue(); // Correo electronico
                        $plab01nomemprultexp=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['nomemprultexp'], $iFila)->getValue(); // Nombre empresa ultima experiencia
                        $sDatoCampo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['documento'], $iFila)->getValue(); // documento
                        //Encontrar el Tercero.
                        $sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDatoCampo.'"';
                        $tabla=$objDB->ejecutasql($sSQL);
                        if ($objDB->nf($tabla) > 0){
                            $fila=$objDB->sf($tabla);
                            $plab01idtercero=$fila['unad11id'];
                            $plab01id=tabla_consecutivo('plab01hv', 'plab01id', '', $objDB);
                            // Extraer id Hoja de Vida
                            $sSQL='SELECT plab01id FROM plab01hv WHERE plab01emprbolsempleo='.$plab01emprbolsempleo.' AND plab01idtercero='.$plab01idtercero.'';
                            $tabla=$objDB->ejecutasql($sSQL);
                            if ($objDB->nf($tabla) > 0){
                                $fila=$objDB->sf($tabla);
                                $sSQL='UPDATE plab01hv SET plab01fechareg='.$plab01fechareg.', plab01telprin="'.$plab01telprin.'", plab01telofic="'.$plab01telofic.'", plab01telmov="'.$plab01telmov.'", plab01correo="'.$plab01correo.'", plab01ultprof='.$plab01ultprof[$sDatoCampo02].', plab01aspsal='.$plab01aspsal[$sDatoCampo16].', plab01nomemprultexp="'.$plab01nomemprultexp.'", plab01cargo='.$plab01cargo[$sDatoCampo04].', plab01industria='.$plab01industria[$sDatoCampo05].', plab01sector='.$plab01sector[$sDatoCampo06].', plab01fechainiexp='.$plab01fechainiexp.', plab01fechafinexp='.$plab01fechafinexp.', plab01nivingles='.$plab01nivingles[$sDatoCampo07].', plab01fechaacthv='.$plab01fechaacthv.' WHERE plab01id='.$fila['plab01id'].'';
                                $tabla=$objDB->ejecutasql($sSQL);
                                if ($tabla == false){
                                    if ($bDebug){
                                        $sDebug=$sDebug.fecha_microtiempo().'Consulta falla Update: '.$sSQL.'<br>';
                                        }
                                    }
                                $iActualizados++;
                                }else{
                                $sSQL = 'INSERT INTO plab01hv(plab01emprbolsempleo,plab01idtercero,plab01id,plab01fechareg,plab01telprin,plab01telofic,plab01telmov,plab01correo,plab01ultprof,plab01aspsal,plab01nomemprultexp,plab01cargo,plab01industria,plab01sector,plab01fechainiexp,plab01fechafinexp,plab01nivingles,plab01fechaacthv,plab01numpostula,plab01condicion)
    VALUES('.$plab01emprbolsempleo.','.$plab01idtercero.','.$plab01id.','.$plab01fechareg.',"'.$plab01telprin.'","'.$plab01telofic.'","'.$plab01telmov.'","'.$plab01correo.'",'.$plab01ultprof[$sDatoCampo02].','.$plab01aspsal[$sDatoCampo16].',"'.$plab01nomemprultexp.'",'.$plab01cargo[$sDatoCampo04].','.$plab01industria[$sDatoCampo05].','.$plab01sector[$sDatoCampo06].','.$plab01fechainiexp.','.$plab01fechafinexp.','.$plab01nivingles[$sDatoCampo07].','.$plab01fechaacthv.',0,0)';
                                $result = $objDB->ejecutasql($sSQL);
                                if ($result == false){
                                    if ($bDebug) {
                                        $sDebug = $sDebug.fecha_microtiempo().'Consulta falla Insert: '.$sSQL.'<br>';
                                        }
                                    }
                                $iRegistrados++;
                                }
                            }else{
                            $iNoEncontrados++;
                            $sNoEncontrados=$sNoEncontrados.', '.$sDatoCampo;
                            if ($bDebug){
                                $sDebug=$sDebug.fecha_microtiempo().' No se  ha encontrado información para el documento '.$sDatoCampo.'<br>';
                                }
                            }
                        }
                    }
                //Aqui termina proceso
                if ($sErrLinea==''){
                    //$iActualizados++;
                    }
                if ($sErrLinea!=''){
                    if ($sInfoProceso!=''){$sInfoProceso=$sInfoProceso.'<br>';}
                    $sInfoProceso=$sInfoProceso.' Error en la l&iacute;nea '.$iFila.': '.$sErrLinea;
                    }
                //$iActualizados++;
                //Leer el siguiente dato
                $iFila++;
                $sDato=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($aColumnas['documento'], $iFila)->getValue();
                }
            }
		$sError='Registros totales '.$iDatos.$sError;
		if ($iActualizados>0 || $iRegistrados>0){
            $sError=$sError.'<br>Registrados: <b>'.$iRegistrados.'</b> Actualizados: <b>'.$iActualizados.'</b><br>No encontrados <b>['.$iNoEncontrados.']</b>: <b>'.$sNoEncontrados.'</b>';
            $iTipoError=1;
			}
		}
	return array($sError, $iTipoError, $sInfoProceso, $sDebug);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f1902_Buscar_HV($aDatos){
    $plab01idtercero_doc='';
    $plab01emprbolsempleo='';
    $plab01id='';
    $plab01fechareg='';
    $plab01telprin='';
    $plab01telofic='';
    $plab01telmov='';
    $plab01correo='';
    $plab01ultprof='';
    $plab01aspsal='';
    $plab01nomemprultexp='';
    $plab01cargo='';
    $plab01industria='';
    $plab01sector='';
    $plab01fechainiexp='';
    $plab01fechafinexp='';
    $plab01nivingles='';
    $plab01fechaacthv='';
    $plab01numpostula='';
    $plab01condicion='';
    $sSQL='';
    $sSQLadd='';
    require './app.php';
    $objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
    if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
    $objDB->xajax();
    $mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
    if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
    $mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
    if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
    require $mensajes_todas;
    require $mensajes_2901;
    if ($aDatos[1]!=''){
        $plab01idtercero_doc=$aDatos[1];
        $sSQLadd=$sSQLadd.' AND T1.unad11doc='.$plab01idtercero_doc;
        }
    if ($aDatos[4]!='') {
        $plab01emprbolsempleo = $aDatos[4];
        $sSQLadd = $sSQLadd . ' AND TB.plab01emprbolsempleo=' . $plab01emprbolsempleo;
        $sSQL = 'SELECT TB.plab01id, TB.plab01fechareg, TB.plab01telprin, TB.plab01telofic, TB.plab01telmov, TB.plab01correo, TB.plab01ultprof, TB.plab01aspsal, TB.plab01nomemprultexp, TB.plab01cargo, TB.plab01industria, TB.plab01sector, TB.plab01fechainiexp, TB.plab01fechafinexp, TB.plab01nivingles, TB.plab01fechaacthv, TB.plab01numpostula, TB.plab01condicion  
FROM plab01hv AS TB, unad11terceros AS T1 
WHERE TB.plab01idtercero=T2.unad11id ' . $sSQLadd . '';
        $res = $objDB->ejecutasql($sSQL);
        if ($objDB->nf($res) != 0) {
            $fila = $objDB->sf($res);
            $plab01id=$fila['plab01id'];
            $plab01fechareg=$fila['plab01fechareg'];
            $plab01telprin=$fila['plab01telprin'];
            $plab01telofic=$fila['plab01telofic'];
            $plab01telmov=$fila['plab01telmov'];
            $plab01correo=$fila['plab01correo'];
            $plab01ultprof=$fila['plab01ultprof'];
            $plab01aspsal=$fila['plab01aspsal'];
            $plab01nomemprultexp=$fila['plab01nomemprultexp'];
            $plab01cargo=$fila['plab01cargo'];
            $plab01industria=$fila['plab01industria'];
            $plab01sector=$fila['plab01sector'];
            $plab01fechainiexp=$fila['plab01fechainiexp'];
            $plab01fechafinexp=$fila['plab01fechafinexp'];
            $plab01nivingles=$fila['plab01nivingles'];
            $plab01fechaacthv=$fila['plab01fechaacthv'];
            $plab01numpostula=$fila['plab01numpostula'];
            $plab01condicion=$fila['plab01condicion'];
        }
    }
    $objResponse=new xajaxResponse();
    $objResponse->assign('plab01id','value', $plab01id);
    $objResponse->assign('plab01fechareg','value', $plab01fechareg);
    $objResponse->assign('plab01telprin','value', $plab01telprin);
    $objResponse->assign('plab01telofic','value', $plab01telofic);
    $objResponse->assign('plab01telmov','value', $plab01telmov);
    $objResponse->assign('plab01correo','value', $plab01correo);
    $objResponse->assign('plab01ultprof','value', $plab01ultprof);
    $objResponse->assign('plab01aspsal','value', $plab01aspsal);
    $objResponse->assign('plab01nomemprultexp','value', $plab01nomemprultexp);
    $objResponse->assign('plab01cargo','value', $plab01cargo);
    $objResponse->assign('plab01industria','value', $plab01industria);
    $objResponse->assign('plab01sector','value', $plab01sector);
    $objResponse->assign('plab01fechainiexp','value', $plab01fechainiexp);
    $objResponse->assign('plab01fechafinexp','value', $plab01fechafinexp);
    $objResponse->assign('plab01nivingles','value', $plab01nivingles);
    $objResponse->assign('plab01fechaacthv','value', $plab01fechaacthv);
    $objResponse->assign('plab01numpostula','value', $plab01numpostula);
    $objResponse->assign('plab01condicion','value', $plab01condicion);
    return $objResponse;
    }
?>