<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 13 de agosto de 2018
--- 2216 core16actamatricula
*/
function f2216_HTMLComboV2_core16peraca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core16peraca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL=f146_ConsultaCombo();
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2216_HTMLComboV2_core16idprograma($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core16idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT exte03id AS id, exte03nombre AS nombre FROM exte03programa';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2216_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$core16peraca=numeros_validar($datos[1]);
	if ($core16peraca==''){$bHayLlave=false;}
	$core16tercero=numeros_validar($datos[2]);
	if ($core16tercero==''){$bHayLlave=false;}
	$core16idprograma=numeros_validar($datos[3]);
	if ($core16idprograma==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT core16idprograma FROM core16actamatricula WHERE core16peraca='.$core16peraca.' AND core16tercero='.$core16tercero.' AND core16idprograma='.$core16idprograma.'';
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
function f2216_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
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
		case 'core16tercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2216);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2216'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2216_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'core16tercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2216_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	if (isset($aParametros[109])==0){$aParametros[109]='';}
	if (isset($aParametros[110])==0){$aParametros[110]='';}
	if (isset($aParametros[111])==0){$aParametros[111]='';}
	if (isset($aParametros[112])==0){$aParametros[112]='';}
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
	if ($aParametros[106]!=''){$sSQLadd1=$sSQLadd1.'TB.core16peraca='.$aParametros[106].' AND ';}

	if ($aParametros[112]!=''){
		$sSQLadd1=$sSQLadd1.'TB.core16idprograma='.$aParametros[112].' AND ';
		}else{
		if ($aParametros[111]!=''){$sSQLadd1=$sSQLadd1.'TB.core16idescuela='.$aParametros[111].' AND ';}
		}
	if ($aParametros[110]!=''){
		$sSQLadd1=$sSQLadd1.'TB.core16idcead='.$aParametros[110].' AND ';
		}else{
		if ($aParametros[109]!=''){$sSQLadd1=$sSQLadd1.'TB.core16idzona='.$aParametros[109].' AND ';}
		}

	if ($aParametros[107]!=''){$sSQLadd1=$sSQLadd1.'TB.core16parametros LIKE "%'.$aParametros[107].'%" AND ';}
	switch($aParametros[104]){
		case 1: // Estudiantes nuevos
		$sSQLadd1=$sSQLadd1.'TB.core16nuevo=1 AND ';
		break;
		case 2: // Estudiantes antiguso
		$sSQLadd1=$sSQLadd1.'TB.core16nuevo=0 AND ';
		break;
		case 3: // Sin procesar matricula
		$sSQLadd1=$sSQLadd1.'TB.core16procesado=0 AND ';
		break;
		case 4: // Sin procesar caracterizacion
		$sSQLadd1=$sSQLadd1.'TB.core16proccarac=0 AND ';
		break;
		case 5: // Sin procesar agenda
		$sSQLadd1=$sSQLadd1.'TB.core16procagenda=0 AND TB.core16procesado<>0 AND ';
		break;
		}
	if ($aParametros[105]!=''){$sSQLadd=$sSQLadd.' AND T2.unad11doc LIKE "%'.$aParametros[105].'%"';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T2.unad11razonsocial LIKE "%'.$sCadena.'%"';
				}
			}
		}
	$sTablaConvenio='';
	if ($aParametros[108]!=''){
		$sTablaConvenio=', core51convenioest AS T51';
		$sSQLadd1=$sSQLadd1.'TB.core16tercero=T51.core51idtercero AND T51.core51idconvenio='.$aParametros[108].' AND T51.core51activo="S" AND ';
		}
	$sTitulos='Peraca, Tercero, Programa, Id, Cead, Parametros, Escuela, Zona, Fecharecibido, Minrecibido, Procesado, Numcursos, Numaprobados, Promedio, Origen, Nuevo, Proccarac, Procagenda';
	$sSQL='SELECT T2.unad11razonsocial AS C2_nombre, TB.core16id, TB.core16idcead, TB.core16parametros, TB.core16fecharecibido, TB.core16minrecibido, TB.core16procesado, TB.core16numcursos, TB.core16numaprobados, TB.core16promedio, TB.core16origen, TB.core16nuevo, TB.core16proccarac, TB.core16procagenda, TB.core16peraca, TB.core16tercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.core16idprograma, TB.core16idescuela, TB.core16idzona 
FROM core16actamatricula AS TB'.$sTablaConvenio.', unad11terceros AS T2 
WHERE '.$sSQLadd1.' TB.core16tercero=T2.unad11id '.$sSQLadd.'
ORDER BY TB.core16peraca DESC, TB.core16idcead, TB.core16idprograma, TB.core16tercero';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2216" name="consulta_2216" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2216" name="titulos_2216" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2216: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2216" name="paginaf2216" type="hidden" value="'.$pagina.'"/><input id="lppf2216" name="lppf2216" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['core16tercero'].'</b></td>
<td><b>'.$ETI['core16procesado'].'</b></td>
<td><b>'.$ETI['core16nuevo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2216', $registros, $lineastabla, $pagina, 'paginarf2216()').'
'.html_lpp('lppf2216', $lineastabla, 'paginarf2216()').'
</td>
</tr>';
	$tlinea=1;
	$idPeraca=-1;
	$idPrograma=-1;
	$idCead=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idPeraca!=$filadet['core16peraca']){
			$idPeraca=$filadet['core16peraca'];
			$idPrograma=-1;
			$idCead=-1;
			$sNomPeraca='{'.$filadet['core16peraca'].'}';
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$filadet['core16peraca'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomPeraca=cadena_notildes($fila['exte02nombre']);
				}
			$res=$res.'<tr class="fondoazul">
<td colspan="5">'.$ETI['core16peraca'].' <b>'.$sNomPeraca.'</b></td>
</tr>';
			}
		if ($idPrograma!=$filadet['core16idprograma']){
			$idPrograma=$filadet['core16idprograma'];
			$idCead=-1;
			$sNomPrograma='{'.$filadet['core16idprograma'].'}';
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$filadet['core16idprograma'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomPrograma=cadena_notildes($fila['core09nombre']);
				}
			$res=$res.'<tr class="fondoazul">
<td colspan="5">'.$ETI['core16idprograma'].' <b>'.$sNomPrograma.'</b></td>
</tr>';
			}
		if ($idCead!=$filadet['core16idcead']){
			$idCead=$filadet['core16idcead'];
			$sNomCEAD='{'.$filadet['core16idcead'].'}';
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$filadet['core16idcead'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomCEAD=cadena_notildes($fila['unad24nombre']);
				}
			$res=$res.'<tr class="fondoazul">
<td colspan="5">'.$ETI['core16idcead'].' <b>'.$sNomCEAD.'</b></td>
</tr>';
			}
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
		/*
		$et_core16fecharecibido='';
		if ($filadet['core16fecharecibido']!=0){
			$et_core16fecharecibido=fecha_desdenumero($filadet['core16fecharecibido']).' '.html_TablaHoraMinDesdeNumero($filadet['core16minrecibido']);
			}
		*/
		$et_core16nuevo=$ETI['no'];
		if ($filadet['core16nuevo']==1){
			$et_core16nuevo=$ETI['si'];
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2216('.$filadet['core16id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		/*
<td><b>'.$ETI['core16idprograma'].'</b></td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core16parametros']).$sSufijo.'</td>
		*/
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16procesado'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_core16nuevo.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2216_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2216_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2216detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2216_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['core16tercero_td']=$APP->tipo_doc;
	$DATA['core16tercero_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='core16peraca='.$DATA['core16peraca'].' AND core16tercero="'.$DATA['core16tercero'].'" AND core16idprograma='.$DATA['core16idprograma'].'';
		}else{
		$sSQLcondi='core16id='.$DATA['core16id'].'';
		}
	$sSQL='SELECT * FROM core16actamatricula WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['core16peraca']=$fila['core16peraca'];
		$DATA['core16tercero']=$fila['core16tercero'];
		$DATA['core16idprograma']=$fila['core16idprograma'];
		$DATA['core16id']=$fila['core16id'];
		$DATA['core16idcead']=$fila['core16idcead'];
		$DATA['core16parametros']=$fila['core16parametros'];
		$DATA['core16idescuela']=$fila['core16idescuela'];
		$DATA['core16idzona']=$fila['core16idzona'];
		$DATA['core16fecharecibido']=$fila['core16fecharecibido'];
		$DATA['core16minrecibido']=$fila['core16minrecibido'];
		$DATA['core16procesado']=$fila['core16procesado'];
		$DATA['core16numcursos']=$fila['core16numcursos'];
		$DATA['core16numaprobados']=$fila['core16numaprobados'];
		$DATA['core16promedio']=$fila['core16promedio'];
		$DATA['core16origen']=$fila['core16origen'];
		$DATA['core16nuevo']=$fila['core16nuevo'];
		$DATA['core16proccarac']=$fila['core16proccarac'];
		$DATA['core16procagenda']=$fila['core16procagenda'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2216']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2216_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2216;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['core16peraca'])==0){$DATA['core16peraca']='';}
	if (isset($DATA['core16tercero'])==0){$DATA['core16tercero']='';}
	if (isset($DATA['core16idprograma'])==0){$DATA['core16idprograma']='';}
	if (isset($DATA['core16id'])==0){$DATA['core16id']='';}
	if (isset($DATA['core16idcead'])==0){$DATA['core16idcead']='';}
	if (isset($DATA['core16parametros'])==0){$DATA['core16parametros']='';}
	if (isset($DATA['core16idescuela'])==0){$DATA['core16idescuela']='';}
	if (isset($DATA['core16idzona'])==0){$DATA['core16idzona']='';}
	if (isset($DATA['core16fecharecibido'])==0){$DATA['core16fecharecibido']='';}
	if (isset($DATA['core16minrecibido'])==0){$DATA['core16minrecibido']='';}
	if (isset($DATA['core16nuevo'])==0){$DATA['core16nuevo']='';}
	*/
	$DATA['core16peraca']=numeros_validar($DATA['core16peraca']);
	$DATA['core16idprograma']=numeros_validar($DATA['core16idprograma']);
	$DATA['core16idcead']=numeros_validar($DATA['core16idcead']);
	$DATA['core16parametros']=htmlspecialchars(trim($DATA['core16parametros']));
	$DATA['core16idescuela']=numeros_validar($DATA['core16idescuela']);
	$DATA['core16idzona']=numeros_validar($DATA['core16idzona']);
	$DATA['core16minrecibido']=numeros_validar($DATA['core16minrecibido']);
	$DATA['core16nuevo']=numeros_validar($DATA['core16nuevo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['core16idcead']==''){$DATA['core16idcead']=0;}
	//if ($DATA['core16idescuela']==''){$DATA['core16idescuela']=0;}
	//if ($DATA['core16idzona']==''){$DATA['core16idzona']=0;}
	//if ($DATA['core16minrecibido']==''){$DATA['core16minrecibido']=0;}
	if ($DATA['core16procesado']==''){$DATA['core16procesado']=0;}
	if ($DATA['core16numcursos']==''){$DATA['core16numcursos']=0;}
	if ($DATA['core16numaprobados']==''){$DATA['core16numaprobados']=0;}
	if ($DATA['core16promedio']==''){$DATA['core16promedio']=0;}
	if ($DATA['core16origen']==''){$DATA['core16origen']=0;}
	//if ($DATA['core16nuevo']==''){$DATA['core16nuevo']=0;}
	if ($DATA['core16proccarac']==''){$DATA['core16proccarac']=0;}
	if ($DATA['core16procagenda']==''){$DATA['core16procagenda']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['core16nuevo']==''){$sError=$ERR['core16nuevo'].$sSepara.$sError;}
		if ($DATA['core16minrecibido']==''){$sError=$ERR['core16minrecibido'].$sSepara.$sError;}
		if ($DATA['core16fecharecibido']==0){
			//$DATA['core16fecharecibido']=fecha_DiaMod();
			$sError=$ERR['core16fecharecibido'].$sSepara.$sError;
			}
		if ($DATA['core16idzona']==''){$sError=$ERR['core16idzona'].$sSepara.$sError;}
		if ($DATA['core16idescuela']==''){$sError=$ERR['core16idescuela'].$sSepara.$sError;}
		if ($DATA['core16parametros']==''){$sError=$ERR['core16parametros'].$sSepara.$sError;}
		if ($DATA['core16idcead']==''){$sError=$ERR['core16idcead'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['core16idprograma']==''){$sError=$ERR['core16idprograma'];}
	if ($DATA['core16tercero']==0){$sError=$ERR['core16tercero'];}
	if ($DATA['core16peraca']==''){$sError=$ERR['core16peraca'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['core16tercero_td'], $DATA['core16tercero_doc'], $objDB, 'El tercero Tercero ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['core16tercero'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16peraca='.$DATA['core16peraca'].' AND core16tercero="'.$DATA['core16tercero'].'" AND core16idprograma='.$DATA['core16idprograma'].'';
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
			$DATA['core16id']=tabla_consecutivo('core16actamatricula','core16id', '', $objDB);
			if ($DATA['core16id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$core16fecharecibido=fecha_DiaMod();
			$DATA['core16procesado']=0;
			$DATA['core16numcursos']=0;
			$DATA['core16numaprobados']=0;
			$DATA['core16promedio']=0;
			$DATA['core16origen']=0;
			$DATA['core16proccarac']=0;
			$DATA['core16procagenda']=0;
			$sCampos2216='core16peraca, core16tercero, core16idprograma, core16id, core16idcead, core16parametros, core16idescuela, core16idzona, core16fecharecibido, core16minrecibido, 
core16procesado, core16numcursos, core16numaprobados, core16promedio, core16origen, core16nuevo, core16proccarac, core16procagenda';
			$sValores2216=''.$DATA['core16peraca'].', '.$DATA['core16tercero'].', '.$DATA['core16idprograma'].', '.$DATA['core16id'].', '.$DATA['core16idcead'].', "'.$DATA['core16parametros'].'", '.$DATA['core16idescuela'].', '.$DATA['core16idzona'].', "'.$DATA['core16fecharecibido'].'", '.$DATA['core16minrecibido'].', 
'.$DATA['core16procesado'].', '.$DATA['core16numcursos'].', '.$DATA['core16numaprobados'].', '.$DATA['core16promedio'].', '.$DATA['core16origen'].', '.$DATA['core16nuevo'].', '.$DATA['core16proccarac'].', '.$DATA['core16procagenda'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core16actamatricula ('.$sCampos2216.') VALUES ('.utf8_encode($sValores2216).');';
				$sdetalle=$sCampos2216.'['.utf8_encode($sValores2216).']';
				}else{
				$sSQL='INSERT INTO core16actamatricula ('.$sCampos2216.') VALUES ('.$sValores2216.');';
				$sdetalle=$sCampos2216.'['.$sValores2216.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='core16idcead';
			$scampo[2]='core16parametros';
			$scampo[3]='core16idescuela';
			$scampo[4]='core16idzona';
			$scampo[5]='core16fecharecibido';
			$scampo[6]='core16minrecibido';
			$scampo[7]='core16nuevo';
			$sdato[1]=$DATA['core16idcead'];
			$sdato[2]=$DATA['core16parametros'];
			$sdato[3]=$DATA['core16idescuela'];
			$sdato[4]=$DATA['core16idzona'];
			$sdato[5]=$DATA['core16fecharecibido'];
			$sdato[6]=$DATA['core16minrecibido'];
			$sdato[7]=$DATA['core16nuevo'];
			$numcmod=7;
			$sWhere='core16id='.$DATA['core16id'].'';
			$sSQL='SELECT * FROM core16actamatricula WHERE '.$sWhere;
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
					$sSQL='UPDATE core16actamatricula SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE core16actamatricula SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2216] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['core16id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2216 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['core16id'], $sdetalle, $objDB);}
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
function f2216_db_Eliminar($core16id, $objDB, $bDebug=false){
	$iCodModulo=2216;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$core16id=numeros_validar($core16id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM core16actamatricula WHERE core16id='.$core16id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$core16id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2216';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['core16id'].' LIMIT 0, 1';
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
		$sWhere='core16id='.$core16id.'';
		//$sWhere='core16idprograma='.$filabase['core16idprograma'].' AND core16tercero="'.$filabase['core16tercero'].'" AND core16peraca='.$filabase['core16peraca'].'';
		$sSQL='DELETE FROM core16actamatricula WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core16id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2216_TituloBusqueda(){
	return 'Busqueda de Actas de matricula';
	}
function f2216_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2216nombre" name="b2216nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2216_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2216nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2216_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
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
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Peraca, Tercero, Programa, Id, Cead, Parametros, Escuela, Zona, Fecharecibido, Minrecibido, Procesado, Numcursos, Numaprobados, Promedio, Origen, Nuevo, Proccarac, Procagenda';
	$sSQL='SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, T3.exte03nombre, TB.core16id, TB.core16idcead, TB.core16parametros, T7.core12nombre, T8.unad23nombre, TB.core16fecharecibido, TB.core16minrecibido, TB.core16procesado, TB.core16numcursos, TB.core16numaprobados, TB.core16promedio, TB.core16origen, TB.core16nuevo, TB.core16proccarac, TB.core16procagenda, TB.core16peraca, TB.core16tercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.core16idprograma, TB.core16idescuela, TB.core16idzona 
FROM core16actamatricula AS TB, exte02per_aca AS T1, unad11terceros AS T2, exte03programa AS T3, core12escuela AS T7, unad23zona AS T8 
WHERE '.$sSQLadd1.' TB.core16peraca=T1.exte02id AND TB.core16tercero=T2.unad11id AND TB.core16idprograma=T3.exte03id AND TB.core16idescuela=T7.core12id AND TB.core16idzona=T8.unad23id '.$sSQLadd.'
ORDER BY TB.core16peraca, TB.core16tercero, TB.core16idprograma';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2216" name="paginaf2216" type="hidden" value="'.$pagina.'"/><input id="lppf2216" name="lppf2216" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['core16peraca'].'</b></td>
<td colspan="2"><b>'.$ETI['core16tercero'].'</b></td>
<td><b>'.$ETI['core16idprograma'].'</b></td>
<td><b>'.$ETI['core16idcead'].'</b></td>
<td><b>'.$ETI['core16parametros'].'</b></td>
<td><b>'.$ETI['core16idescuela'].'</b></td>
<td><b>'.$ETI['core16idzona'].'</b></td>
<td><b>'.$ETI['core16fecharecibido'].'</b></td>
<td><b>'.$ETI['core16minrecibido'].'</b></td>
<td><b>'.$ETI['core16procesado'].'</b></td>
<td><b>'.$ETI['core16numcursos'].'</b></td>
<td><b>'.$ETI['core16numaprobados'].'</b></td>
<td><b>'.$ETI['core16promedio'].'</b></td>
<td><b>'.$ETI['core16origen'].'</b></td>
<td><b>'.$ETI['core16nuevo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['core16id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_core16fecharecibido='';
		if ($filadet['core16fecharecibido']!=0){$et_core16fecharecibido=fecha_desdenumero($filadet['core16fecharecibido']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16idcead'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core16parametros']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_core16fecharecibido.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16minrecibido'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16procesado'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16numcursos'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16numaprobados'].$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_moneda($filadet['core16promedio']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16origen'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16nuevo'].$sSufijo.'</td>
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
function f2216_RegistrarMatricula($aParametros, $objDB, $bDebug=false){
	//Esta funcion es la que recibe el web service de matricula.
	$sDebug='';
	$sError='';
	//{peraca}/{documento}/{programa}/{cead}/{nuevo}/{cursos}
	if (isset($aParametros['peraca'])==0){$sError='No se ha definido un periodo';}
	if (isset($aParametros['documento'])==0){$sError='No se ha definido un documento';}
	if (isset($aParametros['programa'])==0){$sError='No se ha definido un programa';}
	if (isset($aParametros['cead'])==0){$sError='No se ha definido un cead';}
	if (isset($aParametros['nuevo'])==0){$sError='No se ha definido el parametro de nuevo';}
	if (isset($aParametros['cursos'])==0){$sError='No se han reportado cursos';}
	$core16tercero=0;
	$core16idescuela=0;
	$core16idzona=0;
	$core16idcead=0;
	if ($sError==''){
		//Ver si el peraca existe.
		$sSQL='SELECT exte02id FROM exte02per_aca WHERE exte02id='.$aParametros['peraca'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			$sError='No se ha encontrado el periodo '.$aParametros['peraca'].'';
			}
		}
	if ($sError==''){
		//Ver el programa.
		$sSQL='SELECT core09idescuela FROM core09programa WHERE core09id='.$aParametros['programa'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			$sError='No se ha encontrado el programa '.$aParametros['programa'].'';
			}else{
			$fila=$objDB->sf($tabla);
			$core16idescuela=$fila['core09idescuela'];
			}
		}
	if ($sError==''){
		//Ver el cead...
		if ($aParametros['cead']==86201){
			$core16idzona=9;
			$core16idcead=11;
			}else{
			$sSQL='SELECT unad24idzona, unad24id FROM unad24sede WHERE unad24codigoryc='.$aParametros['cead'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$sError='No se ha encontrado el CEAD '.$aParametros['cead'].'';
				}else{
				$fila=$objDB->sf($tabla);
				$core16idzona=$fila['unad24idzona'];
				$core16idcead=$fila['unad24id'];
				}
			}
		}
	if ($sError==''){
		//Ver el tercero...
		$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$aParametros['documento'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			unad11_importar_V2($aParametros['documento'], '', $objDB);
			$tabla=$objDB->ejecutasql($sSQL);
			}
		if ($objDB->nf($tabla)==0){
			$sError='No se ha encontrado el documento '.$aParametros['documento'].'';
			}else{
			$fila=$objDB->sf($tabla);
			$core16tercero=$fila['unad11id'];
			}
		}
	if ($sError==''){
		$core16fecharecibido=fecha_DiaMod();
		$core16minrecibido=fecha_MinutoMod();
		//Si no existe insertarlo.
		$sSQL='SELECT core16id, core16procesado, core16parametros, core16idcead, core16idzona, core16nuevo 
FROM core16actamatricula 
WHERE core16tercero='.$core16tercero.' AND core16idprograma='.$aParametros['programa'].' AND core16peraca='.$aParametros['peraca'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			$core16id=tabla_consecutivo('core16actamatricula','core16id', '', $objDB);
			$sCampos2216='core16peraca, core16tercero, core16idprograma, core16id, core16idcead, core16parametros, core16idescuela, core16idzona, core16fecharecibido, core16minrecibido, 
core16procesado, core16numcursos, core16numaprobados, core16promedio, core16origen, core16nuevo';
			$sValores2216=''.$aParametros['peraca'].', '.$core16tercero.', '.$aParametros['programa'].', '.$core16id.', '.$core16idcead.', "'.$aParametros['cursos'].'", '.$core16idescuela.', '.$core16idzona.', '.$core16fecharecibido.', '.$core16minrecibido.', 
0, 0, 0, 0, 1, '.$aParametros['nuevo'].'';
			$sSQL='INSERT INTO core16actamatricula ('.$sCampos2216.') VALUES ('.$sValores2216.');';
			//$sdetalle=$sCampos2216.'['.$sValores2216.']';
			$result=$objDB->ejecutasql($sSQL);
			}else{
			$fila=$objDB->sf($tabla);
			if ($fila['core16procesado']==0){
				$sSQL='UPDATE core16actamatricula SET core16parametros="'.$aParametros['cursos'].'", core16idcead='.$core16idcead.', core16idzona='.$core16idzona.', core16fecharecibido='.$core16fecharecibido.', core16minrecibido='.$core16minrecibido.', core16nuevo='.$aParametros['nuevo'].' WHERE core16id='.$fila['core16id'].'';
				$result=$objDB->ejecutasql($sSQL);
				//$sError=$sSQL;
				}else{
				//$sError='El registro ya se encuentra procesado.';
				//inicia la edicion.
				$sdatos='';
				if ($fila['core16idcead']!=$core16idcead){
					$sdatos='core16idcead='.$core16idcead.'';
					}
				if  ($fila['core16parametros']!=$aParametros['cursos']){
					if ($sdatos!=''){$sdatos=$sdatos.', ';}
					$sdatos=$sdatos.'core16parametros="'.$aParametros['cursos'].'", core16procesado=0, core16errormatricula=0';
					}
				if ($fila['core16idzona']!=$core16idzona){
					if ($sdatos!=''){$sdatos=$sdatos.', ';}
					$sdatos=$sdatos.'core16idzona='.$core16idzona.'';
					}
				if  ($fila['core16nuevo']!=$aParametros['nuevo']){
					if ($sdatos!=''){$sdatos=$sdatos.', ';}
					$sdatos=$sdatos.'core16nuevo='.$aParametros['nuevo'].'';
					}
				if ($sdatos!=''){
					$sSQL='UPDATE core16actamatricula SET '.$sdatos.' WHERE core16id='.$fila['core16id'].'';
					$result=$objDB->ejecutasql($sSQL);
					}
				//completa la edicion
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2216_ProcesarMatricula($core16id, $objDB, $objNoConformidad, $bDebug=false){
	$sDebug='';
	$sError='';
	$iHoy=fecha_DiaMod();
	$core16procesado=0;
	$core16numcursos=0;
	$core16errormatricula=0;
	$sSQL='SELECT core16peraca, core16tercero, core16idprograma, core16procesado, core16numcursos, core16nuevo, core16idescuela, core16idzona, core16idcead, core16parametros, core16fecharecibido, core16idcead  
FROM core16actamatricula WHERE core16id='.$core16id.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila16=$objDB->sf($tabla);
		$idTercero=$fila16['core16tercero'];
		$idPrograma=$fila16['core16idprograma'];
		$iNuevo=$fila16['core16nuevo'];
		$core16procesado=$fila16['core16procesado'];
		$core16numcursos=$fila16['core16numcursos'];
		$core16peraca=$fila16['core16peraca'];
		$core16fecharecibido=$fila16['core16fecharecibido'];
		$core16idcead=$fila16['core16idcead'];
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Iniciando proceso de matricula del registro '.$core16id.'<br>';}
		if ($core16procesado==0){}
		list($idContTercero, $sError)=f1011_BloqueTercero($idTercero, $objDB);
		if ($sError==''){
			$idContPeraca=f146_Contenedor($core16peraca, $objDB);
			if ($idContPeraca==0){$sError='';}
			}
		if ($sError==''){
			//Armar la matricula.
			$sTabla04='core04matricula_'.$idContTercero;
			$aCursos=explode('@', $fila16['core16parametros']);
			$iCursos=count($aCursos);
			//Limpiar la casa.
			$sSQL='UPDATE '.$sTabla04.' SET core04estado=9 WHERE core04tercero='.$idTercero.' AND core04peraca='.$core16peraca.' AND core04idprograma='.$idPrograma.'';
			$result=$objDB->ejecutasql($sSQL);
			$core16procesado=0;
			$core16numcursos=0;
			$iEstadoOferta=0;
			for ($k=1;$k<=$iCursos;$k++){
				$sCodCurso=$aCursos[$k-1];
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Analizando curso '.$sCodCurso.'<br>';}
				$sErrorCurso='';
				$core04cursoequivalente=0;
				$sCodRevisa=$sCodCurso;
				if ($sCodCurso=='700004'){$core04cursoequivalente='700001';}
				if ($core04cursoequivalente!=0){$sCodRevisa=$core04cursoequivalente;}
				//Ver que este ofertado
				$sSQL='SELECT ofer08estadooferta, ofer08estadocampus, ofer08idagenda, ofer08idnav, ofer08idcursonav, ofer08idescuela FROM ofer08oferta WHERE ofer08idcurso="'.$sCodRevisa.'" AND ofer08idper_aca='.$core16peraca.' AND ofer08cead=0';
				$tabla08=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla08)==0){
					//El curso no esta ofertado...
					//Saber si el curso existe, no vaya y sea un error de codigo
					$sSQL='SELECT unad40idescuela FROM unad40curso WHERE unad40id="'.$sCodCurso.'"';
					$tabla08=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla08)==0){
						$sErrorCurso='No se encuentra el curso con codigo ['.$sCodCurso.']';
						}else{
						$sErrorCurso='El curso '.$sCodCurso.' no ha sido ofertado';
						$fila=$objDB->sf($tabla08);
						$idEscuela=$fila['unad40idescuela'];
						//Si existe el curso, poner la no conformidad.
						$sSQL='SELECT unae03id FROM unae03noconforme WHERE unae03curso='.$sCodCurso.' AND unae03peraca='.$core16peraca.' AND unae03idproceso=2216';
						$tabla08=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla08)==0){
							//Insertar la no conformidad.
							$objNoConformidad->nuevo(2216);
							$objNoConformidad->unae03peraca=$core16peraca;
							$objNoConformidad->unae03curso=$sCodCurso;
							$objNoConformidad->unae03escuela=$idEscuela;
							$objNoConformidad->unae03idresponsable=0;
							$objNoConformidad->unae03idautoriza=0;
							$objNoConformidad->guardar2($objDB, false);
							$sErrorL=$objNoConformidad->sError;
							if ($sErrorL!=''){
								$sErrorCurso=$sErrorCurso.' - '.$sErrorL;
								}
							}
						}
					}else{
					//Puede pasar que la oferta este cancelada o que el curso no este acreditado o certificado... (Aunque esto solo afecta la agenda)
					$fila=$objDB->sf($tabla08);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Cargando oferta del curso '.$sCodRevisa.'<br>';}
					$iEstadoOferta=$fila['ofer08estadocampus'];
					$ofer08idagenda=0;
					$ofer08idnav=0;
					$core04idmoodle=0;
					switch($iEstadoOferta){
						case 10:
						case 12:
						$ofer08idagenda=$fila['ofer08idagenda'];
						$ofer08idnav=$fila['ofer08idnav'];
						$core04idmoodle=$fila['ofer08idcursonav'];
						break;
						}
					if ($fila['ofer08estadooferta']!=1){
						$idEscuela=$fila['ofer08idescuela'];
						$sErrorCurso='La oferta del curso '.$sCodRevisa.' esta cancelada.';
						//Si esta cancelada, poner la no conformidad.
						$sSQL='SELECT unae03id FROM unae03noconforme WHERE unae03curso='.$sCodCurso.' AND unae03peraca='.$core16peraca.' AND unae03idproceso=2217';
						$tabla08=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla08)==0){
							//Insertar la no conformidad.
							$objNoConformidad->nuevo(2217);
							$objNoConformidad->unae03peraca=$core16peraca;
							$objNoConformidad->unae03curso=$sCodCurso;
							$objNoConformidad->unae03escuela=$idEscuela;
							$objNoConformidad->unae03idresponsable=0;
							$objNoConformidad->unae03idautoriza=0;
							$objNoConformidad->guardar2($objDB, false);
							$sErrorL=$objNoConformidad->sError;
							if ($sErrorL!=''){
								$sErrorCurso=$sErrorCurso.' - '.$sErrorL;
								}
							}
						}
					}
				if ($sErrorCurso==''){
					//Agregar el curso a la matricula.
					$sSQL='SELECT core04id FROM '.$sTabla04.' WHERE core04tercero='.$idTercero.' AND core04peraca='.$core16peraca.' AND core04idcurso='.$sCodCurso.'';
					$result=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($result)==0){
						$core04id=tabla_consecutivo($sTabla04, 'core04id', '', $objDB);
						$sSQL='INSERT INTO '.$sTabla04.' (core04peraca, core04tercero, core04idcurso, core04id, core04idrol, core04idaula, core04fechamatricula, core04idcead, core04tienenota, core04idagenda, core04idnav, core04idprograma, core04nuevo, core04cursoequivalente, core04idmoodle) VALUES ('.$core16peraca.', '.$idTercero.', '.$sCodCurso.', '.$core04id.', 5, 0, '.$core16fecharecibido.', '.$core16idcead.', 1, '.$ofer08idagenda.', '.$ofer08idnav.', '.$idPrograma.', '.$iNuevo.', '.$core04cursoequivalente.', '.$core04idmoodle.')';
						//$core04id++;
						}else{
						$sAdd=', core04idagenda='.$ofer08idagenda.', core04idnav='.$ofer08idnav.', core04idmoodle='.$core04idmoodle.'';
						$sSQL='UPDATE '.$sTabla04.' SET core04estado=0'.$sAdd.', core04idprograma='.$idPrograma.', core04nuevo='.$iNuevo.' WHERE core04tercero='.$idTercero.' AND core04peraca='.$core16peraca.' AND core04idcurso='.$sCodCurso.'';
						}
					$result=$objDB->ejecutasql($sSQL);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insersion del curso '.$sSQL.'<br>';}
					if ($result==false){
						$sErrorCurso='Error al intentar insertar el curso '.$sCodCurso.' (Por favor informe al administrador del sistema.)<!-- '.$sSQL.' --> ';
						}else{
						$core16numcursos++;
						}
					}
				if ($sErrorCurso!=''){
					if ($sError!=''){$sError=$sError.', ';}
					$sError=$sError.$sErrorCurso;
					}
				}
			if ($sError==''){
				$core16procesado=$iHoy;
				}else{
				$core16errormatricula=1;
				}
			//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Registro de estudiante '.$sSQL.'<br>';}
			//Se termina el proceso
			$sSQL='UPDATE core16actamatricula SET core16procesado='.$core16procesado.', core16numcursos='.$core16numcursos.', core16errormatricula='.$core16errormatricula.' WHERE core16id='.$core16id.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return array($core16procesado, $core16numcursos, $sError, $sDebug);
	}
function f2216_IniciarGruposPeriodo($idPeraca, $objDB, $bDebug=false){
	$sDebug='';
	//Limpiar la tabla temporal.
	$sSQL='DELETE FROM core96tmp WHERE core96peraca='.$idPeraca.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Limpiando tabla temporal: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	//Hacer el inseto
	$sSQL='INSERT INTO core96tmp (core96doc, core96curso, core96grupo, core96role, core96peraca, core96idtercero)
SELECT idnumber, SUBSTRING(grupo, 1, LOCATE("_", grupo)-1) AS curso, SUBSTRING(grupo, LOCATE("_", grupo)+1) AS idgrupo, role, peraca, 0 
FROM sw_edu_enrollment_final 
WHERE peraca='.$idPeraca.' AND idnumber<>0 AND role IN (3,4,5) AND LOCATE("_", grupo)>0
GROUP BY idnumber, SUBSTRING(grupo, 1, LOCATE("_", grupo)-1), SUBSTRING(grupo, LOCATE("_", grupo)+1), role, peraca';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Armando tabla temporal: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	$sSQL='UPDATE core96tmp AS TB, unad11terceros AS T1 SET TB.core96idtercero=T1.unad11id WHERE TB.core96peraca='.$idPeraca.' AND TB.core96idtercero=0 AND TB.core96doc=T1.unad11doc';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando terceros en la temporal: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	//Habilitar el armado de las agendas para los que fallaron. (segundo plano)
	$sSQL='UPDATE core16actamatricula SET core16erroragenda=0 WHERE core16peraca='.$idPeraca.' AND core16procagenda=0 AND core16erroragenda<>0';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' habilitando la armada de agendas en segundo plano: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	//Revisar los documentos que no han sido importados.
	$sSQL='SELECT core96doc FROM core96tmp WHERE core96idtercero=0 GROUP BY core96doc';
	return array($sDebug);
	}
function f2216_ArmarGrupo($idTercero, $idPeraca, $objDB, $bDebug=false, $idContTercero=0, $idContPeraca=0){
	$idGrupo=0;
	$sError='';
	$sDebug='';
	if ($sError==''){
		if ($idContTercero==0){
			list($idContTercero, $sError)=f1011_BloqueTercero($idTercero, $objDB);
			}
		}
	if ($sError==''){
		if ($idContPeraca==0){
			$idContPeraca=f146_Contenedor($idPeraca, $objDB);
			}
		}
	if ($sError==''){
		$objDBRyC=TraerDBRyC();
		$sTabla04='core04matricula_'.$idContTercero;
		$sTabla06='core06grupos_'.$idContPeraca;
		$sSQL='SELECT core04id, core04idcurso, core04idaula, core04idgrupo FROM '.$sTabla04.' WHERE core04tercero='.$idTercero.' AND core04peraca='.$idPeraca.' AND core04idgrupo<1';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Listando cursos sin grupo '.$sSQL.'<br>';}
		$tabla04=$objDB->ejecutasql($sSQL);
		while($fila04=$objDB->sf($tabla04)){
			$sCurso=$fila04['core04idcurso'];
			$id06=0;
			$id06aula=0;
			$bEntra=false;
			if ($objDBRyC==NULL){
				//El grupo viene de registro y control, entonces debe venir armado en la temporal..
				$sSQL='SELECT core96grupo, core96idtercero FROM core96tmp WHERE core96idtercero='.$idTercero.' AND core96role=5 AND core96curso='.$sCurso.' AND core96peraca='.$idPeraca.'';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Trayendo informaci&oacute;n del grupo '.$sSQL.'<br>';}
				$tabla5=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla5)>0){
					$fila5=$objDB->sf($tabla5);
					//Sacar el aula
					$idAula=1;
					$sGrupo=$fila5['core96grupo'];
					$bEntra=true;
					}
				}else{
				$sSQL='SELECT unad11doc FROM unad11terceros WHERE unad11id='.$idTercero.'';
				$tabla5=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla5)>0){
					$fila5=$objDB->sf($tabla5);
					$sSQL='SELECT T1.grupo 
FROM registro AS TR, cursos_periodos AS T1 
WHERE TR.ins_estudiante='.$fila5['unad11doc'].' AND T1.cur_materia='.$sCurso.' AND TR.ano='.$idPeraca.' AND TR.ins_novedad IN (77, 79)
AND TR.ins_curso=T1.consecutivo AND T1.cur_edificio<>99';
					$tabla5=$objDBRyC->ejecutasql($sSQL);
					if ($objDBRyC->nf($tabla5)>0){
						$fila5=$objDBRyC->sf($tabla5);
						$idAula=1;
						$sGrupo=$fila5['grupo'];
						$bEntra=true;
						}
					}
				}
			if ($bEntra){
				if ($sGrupo>700){$idAula=2;}
				if ($sGrupo>1400){$idAula=3;}
				if ($sGrupo>2100){$idAula=4;}
				if ($sGrupo>2800){$idAula=5;}
				$id06aula=$idAula;
				//Ya tenemos el grupo y el aula... ahora veamos que existe el grupo.
				$sSQL='SELECT core06id, core06idaula, core06idtutor FROM '.$sTabla06.' WHERE core06idcurso='.$sCurso.' AND core06consec='.$sGrupo.' AND core06peraca='.$idPeraca.'';
				$tabla6=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla6)==0){
					//Crear el grupo.
					$params=array();
					list($sError, $sDebug)=f2206_CrearGrupo($idPeraca, $sCurso, $sGrupo, $params, $objDB, $objDBRyC, $idContPeraca, $bDebug);
					$tabla6=$objDB->ejecutasql($sSQL);
					}
				if ($objDB->nf($tabla6)>0){
					$fila6=$objDB->sf($tabla6);
					$id06=$fila6['core06id'];
					$id06aula=$fila6['core06idaula'];
					$core06idtutor=$fila6['core06idtutor'];
					}
				}else{
				if ($bDebug){
					if ($objDBRyC==NULL){
						$sDebug=$sDebug.fecha_microtiempo().' No hay informaci&oacute;n de los grupos en la tabla core96tmp<br>';
						}else{
						$sDebug=$sDebug.fecha_microtiempo().' No se ha encontrado informaci&oacute;n del grupos en RyC '.$sSQL.'<br>';
						}
					}
				}
			//Termina la busqueda del grupo ahora asociarlo.
			if ($id06!=0){
				$sSQL='UPDATE '.$sTabla04.' SET core04idgrupo='.$id06.', core04idaula='.$id06aula.', core04idtutor='.$core06idtutor.' WHERE core04id='.$fila04['core04id'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Asignacion del grupo '.$sSQL.'<br>';}
				//Faltaria totalizar el grupo....
				}
			}
		if ($objDBRyC!=NULL){
			$objDBRyC->CerrarConexion();
			}
		}
	return array($idGrupo, $sError, $sDebug);
	}
function f2216_ProcesarAgenda($core16id, $objDB, $bDebug=false){
	$sDebug='';
	$sError='';
	$iHoy=fecha_DiaMod();
	$core16procagenda=0;
	$core16erroragenda=0;
	$sSQL='SELECT core16peraca, core16tercero, core16idprograma, core16procesado, core16procagenda, core16numcursos, core16nuevo, core16idescuela, core16idzona, core16idcead, core16fecharecibido  
FROM core16actamatricula WHERE core16id='.$core16id.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila16=$objDB->sf($tabla);
		$idTercero=$fila16['core16tercero'];
		$idPrograma=$fila16['core16idprograma'];
		$core16procagenda=$fila16['core16procagenda'];
		$core16procesado=$fila16['core16procesado'];
		$core16peraca=$fila16['core16peraca'];
		if ($core16procesado==0){$sError='No se ha armado la matricula, no puede procesar la agenda.';}
		if ($sError==''){
			list($idContTercero, $sError)=f1011_BloqueTercero($idTercero, $objDB);
			if ($sError==''){
				$idContPeraca=f146_Contenedor($core16peraca, $objDB);
				if ($idContPeraca==0){$sError='';}
				}
			}
		if ($sError==''){
			//Asegurarnos de armar los grupos.
			$iFormaGrupo=0;
			if ($iFormaGrupo==0){
				list($idGrupo, $sErrCurso, $sDebugG)=f2216_ArmarGrupo($idTercero, $core16peraca, $objDB, $bDebug, $idContTercero, $idContPeraca);
				$sDebug=$sDebug.$sDebugG;
				}
			}
		if ($sError==''){
			$core16procagenda=$iHoy;
			//Armar las agendas.
			$sTabla04='core04matricula_'.$idContTercero;
			$sSQL='SELECT TB.core04id, TB.core04idcurso 
FROM '.$sTabla04.' AS TB 
WHERE TB.core04tercero='.$idTercero.' AND TB.core04peraca='.$core16peraca.' AND TB.core04aplicoagenda=0';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Listando cursos '.$sSQL.'<br>';}
			$tabla04=$objDB->ejecutasql($sSQL);
			while($fila04=$objDB->sf($tabla04)){
				list($sErrorA, $sErrCurso, $sDebugA)=f2205_ArmarAgendaCursoEstudiante($core16peraca, $idTercero, $fila04['core04idcurso'], $objDB, $idContTercero, $bDebug);
				$sDebug=$sDebug.$sDebugA;
				if ($sErrCurso!=''){
					$sError=$sError.' Curso '.$fila04['core04idcurso'].': '.$sErrCurso.', ';
					$core16procagenda=0;
					$core16erroragenda=$iHoy;
					}
				}
			//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Registro de estudiante '.$sSQL.'<br>';}
			//Se termina el proceso
			$sSQL='UPDATE core16actamatricula SET core16procagenda='.$core16procagenda.', core16erroragenda='.$core16erroragenda.' WHERE core16id='.$core16id.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return array($core16procagenda, $sError, $sDebug);
	}
function f2216_Procesar($core16id, $objDB, $bDebug=false, $iCompleta=0){
	//A pesar de que se pensaba procesar todo en una sola, se toma la decision de separarlo.
	$sDebug='';
	$sError='';
	$iHoy=fecha_DiaMod();
	$core16procesado=0;
	$core16proccarac=0;
	$core16procagenda=0;
	$core16numcursos=0;
	$sSQL='SELECT core16peraca, core16tercero, core16idprograma, core16procesado, core16proccarac, core16procagenda, core16numcursos, core16nuevo, core16idescuela, core16idzona, core16idcead, core16fecharecibido  
FROM core16actamatricula WHERE core16id='.$core16id.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila16=$objDB->sf($tabla);
		$idTercero=$fila16['core16tercero'];
		$idPrograma=$fila16['core16idprograma'];
		$idPeraca=$fila16['core16peraca'];
		$core16procesado=$fila16['core16procesado'];
		$core16proccarac=$fila16['core16proccarac'];
		$core16procagenda=$fila16['core16procagenda'];
		$core16numcursos=$fila16['core16numcursos'];
		$core16peraca=$fila16['core16peraca'];
		//Son 3 bloques.... Armar la caracterizacion, la matricula y la agenda.
		// Caracterizacion.
		//Ver que el estudiante este registrado
		$sSQL='SELECT core01id, core01peracainicial, core01fechaultmatricula FROM core01estprograma WHERE core01idtercero='.$fila16['core16tercero'].' AND core01idprograma='.$fila16['core16idprograma'].'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Registro de estudiante '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			//No esta el registro, agregarlo.
			$core01idplandeestudios=0;
			$core01numcredbasicos=0;
			$core01numcredespecificos=0;
			$core01numcredelectivos=0;
			$core01idestado=0;
			$core01numcredbasicosaprob=0;
			$core01numcredespecificosaprob=0;
			$core01numcredelectivosaprob=0;
			$core01notaminima=3;
			$core01notamaxima=5;
			$core01fechafinaliza=0;
			$core01peracafinal=0;
			if ($fila16['core16nuevo']==0){
				//Todos los antiguos van al periodo 87
				$core16peraca=87;
				}
			//Miramos la version del programa.
			$sSQL='SELECT core10id, core10numcredbasicos, core10numcredespecificos, core10numcredelectivos FROM core10programaversion WHERE core10idprograma='.$fila16['core16idprograma'].' AND core10estado="S" ORDER BY core10fechavence DESC';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$core01idplandeestudios=$fila['core10id'];
				$core01numcredbasicos=$fila['core10numcredbasicos'];
				$core01numcredespecificos=$fila['core10numcredespecificos'];
				$core01numcredelectivos=$fila['core10numcredelectivos'];
				}
			$sCampos2202='core01idtercero, core01idprograma, core01id, core01idescuela, core01idzona, core011idcead, core01fechainicio, core01peracainicial, core01fechaultmatricula, core01idplandeestudios, 
core01numcredbasicos, core01numcredespecificos, core01numcredelectivos, core01idestado, core01numcredbasicosaprob, core01numcredespecificosaprob, core01numcredelectivosaprob, core01notaminima, core01notamaxima, core01fechafinaliza, 
core01peracafinal';
			$core01id=tabla_consecutivo('core01estprograma','core01id', '', $objDB);
			$sValores2202=''.$fila16['core16tercero'].', '.$fila16['core16idprograma'].', '.$core01id.', '.$fila16['core16idescuela'].', '.$fila16['core16idzona'].', '.$fila16['core16idcead'].', '.$fila16['core16fecharecibido'].', '.$core16peraca.', '.$fila16['core16fecharecibido'].', '.$core01idplandeestudios.', 
'.$core01numcredbasicos.', '.$core01numcredespecificos.', '.$core01numcredelectivos.', '.$core01idestado.', '.$core01numcredbasicosaprob.', '.$core01numcredespecificosaprob.', '.$core01numcredelectivosaprob.', '.$core01notaminima.', '.$core01notamaxima.', '.$core01fechafinaliza.', 
'.$core01peracafinal.'';
			$sSQL='INSERT INTO core01estprograma ('.$sCampos2202.') VALUES ('.$sValores2202.');';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insertando el estudiante '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			$bEntra=false;
			if ($core16proccarac==0){$bEntra=true;}
			if ($iCompleta!=0){$bEntra=true;}
			if ($bEntra){
				//Iniciamos la caracterizacion...
				list($sErrorE, $sDebugE)=f2301_IniciarEncuesta($idTercero, $idPeraca, $objDB, $bDebug);
				$sDebug=$sDebug.$sDebugE;
				$core16proccarac=$iHoy;
				}
			}else{
			$bEntra=false;
			if ($iCompleta!=0){$bEntra=true;}
			if ($core16proccarac==0){
				$bEntra=true;
				$core16proccarac=$iHoy;
				}
			if ($bEntra){
				list($sErrorE, $sDebugE)=f2301_IniciarEncuesta($idTercero, $idPeraca, $objDB, $bDebug);
				$sDebug=$sDebug.$sDebugE;
				}
			}
		if ($iCompleta!=0){
			$objNoConformidad=new clsT203(2216);
			//Matricula.
			list($core16procesado, $core16numcursos, $sErrorM, $sDebugM)=f2216_ProcesarMatricula($core16id, $objDB, $objNoConformidad, $bDebug);
			$sError=$sError.$sErrorM;
			$sDebug=$sDebug.$sDebugM;
			//Agenda.
			list($core16procagenda, $sErrorM, $sDebugM)=f2216_ProcesarAgenda($core16id, $objDB, $bDebug);
			$sError=$sError.$sErrorM;
			$sDebug=$sDebug.$sDebugM;
			}
		//Marcar los cambios...
		$sSQL='UPDATE core16actamatricula SET core16procesado='.$core16procesado.', core16proccarac='.$core16proccarac.', core16procagenda='.$core16procagenda.', core16numcursos='.$core16numcursos.' WHERE core16id='.$core16id.'';
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($core16procesado, $core16proccarac, $core16procagenda, $core16numcursos, $sError, $sDebug);
	}
class cls2216Total{
	var $idPrograma=0;
	var $idCurso=0;
	var $idCead=0;
	var $iNuevos=0;
	var $iAntiguos=0;
	function sumarNuevo($iNuevos){
		$this->iNuevos=$this->iNuevos+$iNuevos;
		}
	function sumarAntiguo($iAntiguos){
		$this->iAntiguos=$this->iAntiguos+$iAntiguos;
		}
	function __construct($idPrograma, $idCurso, $idCead){
		$this->idPrograma=$idPrograma;
		$this->idCurso=$idCurso;
		$this->idCead=$idCead;
		}
	}
function f2216_TotalizarPeraca($idPeraca, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	//Se ejecuta cuando se procesa la matricula.
	$aData=array();
	$iData=0;
	//Borramos la temporal
	$sSQL='DELETE FROM core97matriculaest_t WHERE core97idperaca='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	//Alistamos los contenedores.
	$sSQL='SHOW TABLES LIKE "core04%"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		//Recorrer los contenedores cargando la data.
		$sTabla=$fila[0];
		$sSQL='INSERT INTO core97matriculaest_t (core97idperaca, core97idprograma, core97idcurso, core97idcead, core97contenedor, core97condicion, core97numestudiantes, core97numnuevos)
SELECT '.$idPeraca.', core04idprograma, core04idcurso, core04idcead, "'.$sTabla.'", 0, COUNT(core04id), 0 FROM '.$sTabla.' WHERE core04peraca='.$idPeraca.' AND core04nuevo=0 GROUP BY core04idprograma, core04idcurso, core04idcead';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Insertando Antiguos: '.$sSQL.'<br>';}
		$tabla04=$objDB->ejecutasql($sSQL);
		$sSQL='INSERT INTO core97matriculaest_t (core97idperaca, core97idprograma, core97idcurso, core97idcead, core97contenedor, core97condicion, core97numestudiantes, core97numnuevos)
SELECT '.$idPeraca.', core04idprograma, core04idcurso, core04idcead, "'.$sTabla.'", 1, 0, COUNT(core04id) FROM '.$sTabla.' WHERE core04peraca='.$idPeraca.' AND core04nuevo<>0 GROUP BY core04idprograma, core04idcurso, core04idcead';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Insertando Antiguos: '.$sSQL.'<br>';}
		$tabla04=$objDB->ejecutasql($sSQL);
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Termina de armar el totalizador<br>';}
	// Ahora guardar los datos.
	$sSQL='DELETE FROM core07matriculaest WHERE core07idperaca='.$idPeraca.'';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Limpia el totalizador: '.$sSQL.'<br>';}
	$sSQL='INSERT INTO core07matriculaest(core07idperaca, core07idprograma, core07idcurso, core07idcead, core07idzona, core07idescuela, core07numestudiantes, core07numnuevos) 
SELECT '.$idPeraca.', core97idprograma, core97idcurso, core97idcead, 0, 0, SUM(core97numestudiantes), SUM(core97numnuevos) FROM core97matriculaest_t WHERE core97idperaca='.$idPeraca.' GROUP BY core97idprograma, core97idcurso, core97idcead';
	$result=$objDB->ejecutasql($sSQL);
	//Actualizar el dato de zona
	$sSQL='UPDATE core07matriculaest AS TB, unad24sede AS T2 SET TB.core07idzona=T2.unad24idzona WHERE TB.core97idperaca='.$idPeraca.' AND TB.core07idcead=T2.unad24id';
	$result=$objDB->ejecutasql($sSQL);
	//Actualizar el dato de escuela.
	$sSQL='UPDATE core07matriculaest AS TB, core09programa AS T2 SET TB.core07idescuela=T2.core09idescuela WHERE TB.core97idperaca='.$idPeraca.' AND TB.core07idprograma=T2.core09id';
	$result=$objDB->ejecutasql($sSQL);
	//Optimizar la tabla.
	$sSQL='OPTIMIZE TABLE core07matriculaest';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Termina de construir los totales<br>';}
	return array($sError, $sDebug);
	}
function f2216_ActualizarPuntajesAgenda($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$sSQL='SHOW TABLES LIKE "core04%"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$iContenedor=substr($fila[0], 16);
		//Recorrer los contenedores cargando la data.
		$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB, core04matricula_'.$iContenedor.' AS T1, ofer06agendaactividad AS T6, ofer02cursofase AS T2
SET TB.core05puntaje75=T6.ofer06peso 
WHERE TB.core05idmatricula=T1.core04id 
AND T1.core04idagenda=T6.ofer06idagenda AND TB.core05puntaje75<>T6.ofer06peso 
AND TB.core05idfase=T6.ofer06idfase 
AND TB.core05idunidad=T6.ofer06idunidad AND TB.core05idactividad=T6.ofer06idactividad 
AND TB.core05idfase=T2.ofer02id AND T2.ofer02fechabase=0';
		$result=$objDB->ejecutasql($sSQL);
		$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB, core04matricula_'.$iContenedor.' AS T1, ofer06agendaactividad AS T6, ofer02cursofase AS T2
SET TB.core05puntaje25=T6.ofer06peso 
WHERE TB.core05idmatricula=T1.core04id 
AND T1.core04idagenda=T6.ofer06idagenda AND TB.core05puntaje25<>T6.ofer06peso 
AND TB.core05idfase=T6.ofer06idfase 
AND TB.core05idunidad=T6.ofer06idunidad AND TB.core05idactividad=T6.ofer06idactividad 
AND TB.core05idfase=T2.ofer02id AND T2.ofer02fechabase IN (3, 4)';
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
function f2216_RevisarAgendas(){
	$sSQL='SELECT TB.core05peraca, TB.core05tercero, TB.core05idcurso, SUM(TB.core05puntaje75+TB.core05puntaje25) AS Puntaje
FROM core05actividades_1 AS TB 
GROUP BY TB.core05peraca, TB.core05tercero, TB.core05idcurso
HAVING SUM(TB.core05puntaje75+TB.core05puntaje25)<>500';
	}
function f2216_ActualizarTutores($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	//Empezamos limpiando la tabla de totales.
	$iSegIni=microtime(true);
	$sPeraca=-99;
	$sSQL='SELECT exte02id FROM exte02per_aca WHERE exte02vigente="S" AND exte02contgrupos<>0';
	$tabla2=$objDB->ejecutasql($sSQL);
	while($fila2=$objDB->sf($tabla2)){
		$sPeraca=$sPeraca.','.$fila2['exte02id'];
		}
	$sSQL='DELETE FROM ceca92avancegrupo WHERE ceca92peraca IN ('.$sPeraca.')';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){
		list($sDebugT, $iSegIni)=debug_Cronometrado(' Borrando totales: '.$sSQL.'', $iSegIni);
		$sDebug=$sDebug.$sDebugT;
		}
	$sSQL='SHOW TABLES LIKE "core04%"';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($bDebug){
		list($sDebugT, $iSegIni)=debug_Cronometrado('Total Periodo: Lista de contenedores: '.$sSQL.'', $iSegIni);
		$sDebug=$sDebug.$sDebugT;
		}
	while($fila=$objDB->sf($tabla)){
		$iContenedor=substr($fila[0], 16);
		//Recorrer los contenedores.
		//Actualizamos los grupos.
		$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB, core04matricula_'.$iContenedor.' AS T4 
SET TB.core05idgrupo=T4.core04idgrupo
WHERE TB.core05idmatricula=T4.core04id AND TB.core05idgrupo<>T4.core04idgrupo';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Actualizando grupos contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		//Actualizar tutores.
		//obtenemos la lista de peracas que tiene el grupo
		$sIds=-99;
		$sSQL='SELECT core04peraca FROM core04matricula_'.$iContenedor.' GROUP BY core04peraca';
		$tabla4=$objDB->ejecutasql($sSQL);
		while($fila4=$objDB->sf($tabla4)){
			$sIds=$sIds.','.$fila4['core04peraca'];
			}
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Listado de peracas contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		$sSQL='SELECT exte02id, exte02contgrupos FROM exte02per_aca WHERE exte02id IN ('.$sIds.') AND exte02vigente="S" AND exte02contgrupos<>0';
		$tabla2=$objDB->ejecutasql($sSQL);
		while($fila2=$objDB->sf($tabla2)){
			$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB, core06grupos_'.$fila2['exte02contgrupos'].' AS T6 
SET TB.core05idtutor=T6.core06idtutor
WHERE TB.core05peraca='.$fila2['exte02id'].' AND TB.core05idgrupo<>0 
AND TB.core05idgrupo=T6.core06id AND TB.core05idtutor<>T6.core06idtutor';
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){
				list($sDebugT, $iSegIni)=debug_Cronometrado('Actualizando tutores contenedor '.$iContenedor.' periodo '.$fila2['exte02id'].': '.$sSQL.'', $iSegIni);
				$sDebug=$sDebug.$sDebugT;
				}
			}
		//Armar la tabla de totales.
		$sSQL='INSERT INTO ceca92avancegrupo(ceca92idgrupo, ceca92contenedor, ceca92idcurso, ceca92peraca, ceca92idtutor, ceca92numest, ceca92est_0, ceca92est_1, ceca92est_3, ceca92est_5, ceca92est_7) 
SELECT TB.core05idgrupo, '.$iContenedor.', TB.core05idcurso, TB.core05peraca, TB.core05idtutor, COUNT(DISTINCT(TB.core05tercero)), 0, 0, 0, 0, 0
FROM core05actividades_'.$iContenedor.' AS TB 
GROUP BY TB.core05idgrupo, TB.core05idcurso, TB.core05peraca, TB.core05idtutor';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Insertando totales contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		$sSQL='UPDATE ceca92avancegrupo AS T92
SET T92.ceca92est_0=(SELECT COUNT(TB.core05estado)
FROM core05actividades_'.$iContenedor.' AS TB
WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=0 AND TB.core05peraca=T92.ceca92peraca)
WHERE T92.ceca92contenedor='.$iContenedor.'';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Actualizando actividades 0 contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		$sSQL='UPDATE ceca92avancegrupo AS T92
SET T92.ceca92est_1=(SELECT COUNT(TB.core05estado)
FROM core05actividades_'.$iContenedor.' AS TB
WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=1 AND TB.core05peraca=T92.ceca92peraca)
WHERE T92.ceca92contenedor='.$iContenedor.'';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Actualizando actividades 1 contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		$sSQL='UPDATE ceca92avancegrupo AS T92
SET T92.ceca92est_3=(SELECT COUNT(TB.core05estado)
FROM core05actividades_'.$iContenedor.' AS TB
WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=3 AND TB.core05peraca=T92.ceca92peraca)
WHERE T92.ceca92contenedor='.$iContenedor.'';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Actualizando actividades 3 contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		$sSQL='UPDATE ceca92avancegrupo AS T92
SET T92.ceca92est_5=(SELECT COUNT(TB.core05estado)
FROM core05actividades_'.$iContenedor.' AS TB
WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=5 AND TB.core05peraca=T92.ceca92peraca)
WHERE T92.ceca92contenedor='.$iContenedor.'';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Actualizando actividades 5 contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		$sSQL='UPDATE ceca92avancegrupo AS T92
SET T92.ceca92est_7=(SELECT COUNT(TB.core05estado)
FROM core05actividades_'.$iContenedor.' AS TB
WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=7 AND TB.core05peraca=T92.ceca92peraca)
WHERE T92.ceca92contenedor='.$iContenedor.'';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Actualizando actividades 7 contenedor '.$iContenedor.': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		}
	$sSQL='OPTIMIZE TABLE ceca92avancegrupo';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){
		list($sDebugT, $iSegIni)=debug_Cronometrado('Optimizando totales: '.$sSQL.'', $iSegIni);
		$sDebug=$sDebug.$sDebugT;
		}
	//Al final poner los totales en cada grupo.
	$sSQL='SELECT exte02id, exte02contgrupos FROM exte02per_aca WHERE exte02id IN ('.$sPeraca.')';
	$tabla2=$objDB->ejecutasql($sSQL);
	while($fila2=$objDB->sf($tabla2)){
		$sSQL='UPDATE core06grupos_'.$fila2['exte02contgrupos'].' AS TB 
SET TB.core06numinscritos=(SELECT COALESCE(SUM(T2.ceca92numest), 0) 
FROM ceca92avancegrupo AS T2
WHERE T2.ceca92idgrupo=TB.core06id AND T2.ceca92peraca='.$fila2['exte02id'].')
WHERE TB.core06peraca='.$fila2['exte02id'].'';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			list($sDebugT, $iSegIni)=debug_Cronometrado('Totalizando grupos periodo '.$fila2['exte02id'].': '.$sSQL.'', $iSegIni);
			$sDebug=$sDebug.$sDebugT;
			}
		}
	return array($sError, $sDebug);
	}
function f_InciarActividades(){
	$sSQL='UPDATE core05actividades_1 AS TB
SET TB.core05estado=1
WHERE TB.core05estado=0 AND TB.core05fechaapertura<=20180911';
	}
	
	
	function f2216_Comboprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[1])==0){$aParametros[1]=0;}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_programa=f2202_HTMLComboV2_programa($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core16idprograma', 'innerHTML', $html_programa);
	return $objResponse;
	}
	
	
	function f2216_Combocead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[1])==0){$aParametros[1]=0;}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_cead=f2202_HTMLComboV2_cead($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core16idcead', 'innerHTML', $html_cead);
	return $objResponse;
	}
?>