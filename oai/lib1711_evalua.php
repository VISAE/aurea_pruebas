<?php
function html_combo_ofer11idrol($objDB, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('ofer11idrol', 'ofer10id', 'ofer10nombre', 'ofer10rol', 'ofer10claserol IN (4, 5, 8)', 'ofer10orden, ofer10nombre', $valor, $objDB, 'RevisaRol()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return $res;
	}
// -- 1711 Actores
function f1711_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($sError)=f1711_db_Guardar($valores, $objDB);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1711_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1711detalle","innerHTML",$sdetalle);
		$objResponse->call("limpiaf1711");
		$objResponse->assign("alarma","innerHTML",'item guardado');
		}else{
		$objResponse->assign("alarma","innerHTML",$sError);
		}
	return $objResponse;
	}
function f1711_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$ofer11per_aca=numeros_validar($params[1]);
		$ofer11idescuela=numeros_validar($params[2]);
		$ofer11idcurso=numeros_validar($params[3]);
		$ofer11idrol=numeros_validar($params[4]);
		if (($ofer11per_aca!='')&&($ofer11idescuela!='')&&($ofer11idcurso!='')&&($ofer11idrol!='')){$besta=true;}
		}else{
		$ofer11id=$params[103];
		if ((int)$ofer11id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'ofer11per_aca='.$ofer11per_aca.' AND ofer11idescuela='.$ofer11idescuela.' AND ofer11idcurso='.$ofer11idcurso.' AND ofer11idrol='.$ofer11idrol.'';
			}else{
			$sqlcondi=$sqlcondi.'ofer11id='.$ofer11id.'';
			}
		$sql='SELECT * FROM ofer11actores WHERE '.$sqlcondi;
		$tabla=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla)>0){
			$row=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
		if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
		require $mensajes_todas;
		$ofer11idtercero_id=(int)$row['ofer11idtercero'];
		$ofer11idtercero_td=$APP->tipo_doc;
		$ofer11idtercero_doc='';
		$ofer11idtercero_nombre='';
		if ($ofer11idtercero_id!=0){
			list($ofer11idtercero_id, $ofer11idtercero_td, $ofer11idtercero_doc, $ofer11idtercero_nombre)=tabla_terceros_traer($ofer11idtercero_id, $ofer11idtercero_td, $ofer11idtercero_doc, $objDB);
			}
		//list($ofer11per_aca_nombre, $serror_det)=tabla_campoxid('exte02per_aca', 'exte02nombre', 'ext02id', $row['ofer11per_aca'], '{'.$ETI['msg_sindato'].'}', $objDB);
		//$html_ofer11per_aca=html_oculto('ofer11per_aca', $row['ofer11per_aca'], $ofer11per_aca_nombre);
		//$objResponse->assign('div_ofer11per_aca', 'innerHTML', $html_ofer11per_aca);
		//list($ofer11idescuela_nombre, $serror_det)=tabla_campoxid('exte01escuela','exte01nombre','exte01id',$row['ofer11idescuela'],'{'.$ETI['msg_sindato'].'}', $objDB);
		//$html_ofer11idescuela=html_oculto('ofer11idescuela', $row['ofer11idescuela'], $ofer11idescuela_nombre);
		//$objResponse->assign('div_ofer11idescuela', 'innerHTML', $html_ofer11idescuela);
		list($ofer11idrol_nombre, $serror_det)=tabla_campoxid('ofer10rol','ofer10nombre','ofer10id',$row['ofer11idrol'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_ofer11idrol=html_oculto('ofer11idrol', $row['ofer11idrol'], $ofer11idrol_nombre);
		$objResponse->assign('div_ofer11idrol', 'innerHTML', $html_ofer11idrol);
		//$ofer11id_nombre='';
		$html_ofer11id=html_oculto('ofer11id', $row['ofer11id']);
		$objResponse->assign('div_ofer11id', 'innerHTML', $html_ofer11id);
		$objResponse->assign('ofer11idtercero', 'value', $row['ofer11idtercero']);
		$objResponse->assign('ofer11idtercero_td', 'value', $ofer11idtercero_td);
		$objResponse->assign('ofer11idtercero_doc', 'value', $ofer11idtercero_doc);
		$objResponse->assign('div_ofer11idtercero', 'innerHTML', $ofer11idtercero_nombre);
		//$objResponse->assign('ofer11detalle', 'value', $row['ofer11detalle']);
		$objResponse->assign("alarma","innerHTML",'');
		$objResponse->call("verboton('belimina1711','block')");
		}else{
		if ($paso==1){
			//$objResponse->assign("ofer11per_aca","value",$ofer11per_aca);
			//$objResponse->assign("ofer11idescuela","value",$ofer11idescuela);
			//$objResponse->assign("ofer11idrol","value",$ofer11idrol);
			}else{
			$objResponse->assign("alarma","innerHTML",'No se encontro el registro de referencia:'.$ofer11id);
			}
		}
	return $objResponse;
	}
function f1711_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sError=f1711_db_Eliminar($params, $objDB);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1711_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1711detalle","innerHTML",$sDetalle);
		$objResponse->call("limpiaf1711");
		$sError='El actor ha sido retirado';
		}
	$objResponse->assign("alarma","innerHTML",$sError);
	return $objResponse;
	}
function f1711_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1711=$APP->rutacomun.'lg/lg_1711_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1711)){$mensajes_1711=$APP->rutacomun.'lg/lg_1711_es.php';}
	require $mensajes_todas;
	require $mensajes_1711;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sqladd='';
	$sqladd1='';
	$bMarcaGestor=false;
	if (isset($params[99])==0){$params[99]='';}
	//Si el parametro 100 viene en 1 es que se pueden marcar gestores y directores.
	if (isset($params[100])==0){$params[100]=0;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]=0;}
	if ($params[100]==1){$bMarcaGestor=true;}
	if ($params[99]==1){
		$babierta=false;
		$bMarcaGestor=false;
		}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	if ($params[103]!=''){
		$sqladd1=$sqladd1.' AND TB.ofer11idcurso='.$params[103];
		}else{
		//ver si forza el curso
		if ($params[104]==1){
			$sqladd1=$sqladd1.' AND TB.ofer11idcurso=-99';
			}
		}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]='';}
	if (isset($params[107])==0){$params[107]='';}
	if (isset($params[108])==0){$params[108]='';}
	if (isset($params[109])==0){$params[109]='';}
	if ($params[105]!=''){$sqladd=$sqladd.' AND T6.unad11doc LIKE "%'.$params[105].'%"';}
	if ($params[106]!=''){$sqladd1=$sqladd1.' AND TB.ofer11idcurso LIKE "%'.$params[106].'%"';}
	if ($params[107]!=''){$sqladd=$sqladd.' AND T3.mat_descripcion LIKE "%'.$params[107].'%"';}
	if ($params[108]!=''){$sqladd1=$sqladd1.' AND TB.ofer11idrol='.$params[108].'';}
	if ($params[109]!=''){$sqladd1=$sqladd1.' AND TB.ofer11idescuela='.$params[109].'';}
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
	//Octubre 11 de 2014 - Estas consultas son sumamente pesadas por el doble left join en la segunda consulta... la primera esta bien...
	//por lo tanto se separan para que el count lo haga una consulta y luego la de mostrar la tabla la haga otra pero con el limit.
	$sTitulos='Per_aca, Escuela, Curso, Rol, Id, Tercero, Detalle, Fechaacceso, Fecharegistro';
	if ($params[104]==1){
		$sql='SELECT T4.ofer10nombre, TB.ofer11id, T6.unad11razonsocial AS C6_nombre, TB.ofer11detalle, TB.ofer11per_aca, TB.ofer11idescuela, TB.ofer11idcurso, TB.ofer11idrol, TB.ofer11idtercero, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc, T6.unad11telefono, T6.unad11correo, T6.unad11correoinstitucional, T6.unad11correonotifica, T6.unad11correofuncionario, T6.unad11aceptanotificacion, TB.ofer11fecharegistro, T6.unad11mostrarcelular 
FROM ofer11actores AS TB, ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" '.$sqladd1.' AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sqladd.' 
ORDER BY T6.unad11razonsocial, T4.ofer10nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1711" name="consulta_1711" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1711" name="titulos_1711" type="hidden" value="'.$sTitulos.'"/>';
		$tabladetalle=$objdb->ejecutasql($sql);
		$registros=$objdb->nf($tabladetalle);
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}else{
		$sql='SELECT TB.ofer11id
FROM ofer11actores AS TB, ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" '.$sqladd1.' AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sqladd.'';
		$tabladetalle=$objdb->ejecutasql($sql);
		$registros=$objdb->nf($tabladetalle);
		$sql='SELECT T2.exte01nombre, T3.unad40nombre, T4.ofer10nombre, TB.ofer11id, T6.unad11razonsocial AS C6_nombre, TB.ofer11detalle, TB.ofer11per_aca, TB.ofer11idescuela, TB.ofer11idcurso, TB.ofer11idrol, TB.ofer11idtercero, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc, TB.ofer11idcurso, T6.unad11telefono, T6.unad11correo, T6.unad11correofuncionario, T6.unad11correoinstitucional, T6.unad11correonotifica, T6.unad11aceptanotificacion, TB.ofer11fecharegistro, T6.unad11mostrarcelular
FROM (ofer11actores AS TB LEFT JOIN exte01escuela AS T2 ON (TB.ofer11idescuela=T2.exte01id)) LEFT JOIN unad40curso AS T3 ON (TB.ofer11idcurso=T3.unad40id), ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" '.$sqladd1.' AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sqladd.' 
ORDER BY T6.unad11razonsocial, T4.ofer10nombre';
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1711" name="consulta_1711" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1711" name="titulos_1711" type="hidden" value="'.$sTitulos.'"/>';
		$tabladetalle=$objdb->ejecutasql($sql.$limite);
		}
	$sTitulo='';
	if ($params[104]!=1){
		$sTitulo='
<td><b>'.$ETI['ofer11idescuela'].'</b></td>
<td><b>'.$ETI['ofer11idcurso'].'</b></td>';
		}
	$sCols='';
	if ($bMarcaGestor){
		$sCols=' colspan="3"';
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">'.$sTitulo.'
<td><b>'.$ETI['ofer11idrol'].'</b></td>
<td colspan="4"><b>'.$ETI['ofer11idtercero'].'</b></td>
<td><b>'.$ETI['ofer11fecharegistro'].'</b></td>
<td align="right"'.$sCols.'>
'.html_paginador('paginaf1711', $registros, $lineastabla, $pagina, 'paginarf1711('.$params[100].')').'
'.html_lpp('lppf1711', $lineastabla, 'paginarf1711('.$params[100].')').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sprefijo='';
		$ssufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sprefijo='<b>';
			$ssufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$sEscuela='';
		if ($params[104]!=1){
			$sEscuela='
<td>'.$sprefijo.cadena_notildes($filadet['exte01nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer11idcurso'].' '.cadena_notildes($filadet['unad40nombre']).$ssufijo.'</td>';
			}
		//unad11correoinstitucional, T6.unad11correonotifica, T6.unad11aceptanotificacion, unad11correofuncionario
		$et_correo='';
		if (correo_VerificarDireccion($filadet['unad11correofuncionario'])){
			$et_correo=$filadet['unad11correofuncionario'];
			}else{
			if (correo_VerificarDireccion($filadet['unad11correoinstitucional'])){
				$et_correo=$filadet['unad11correoinstitucional'];
				}else{
				if ($filadet['unad11aceptanotificacion']=='S'){
					$et_correo=$filadet['unad11correonotifica'];
					}else{
					$et_correo=$filadet['unad11correo'];
					}
				}
			}
		$et_ofer11fecharegistro='';
		if ($filadet['ofer11fecharegistro']!='00/00/0000'){
			$et_ofer11fecharegistro=$filadet['ofer11fecharegistro'];
			}
		$et_unad11telefono='';
		if ($filadet['unad11mostrarcelular']=='S'){
			$et_unad11telefono=$filadet['unad11telefono'];
			}
		$sCols='';
		if ($bMarcaGestor){
			$sCols='<td><a href="javascript:marcargestor(1, '.$filadet['ofer11idtercero'].', 1)" class="lnkresalte">'.$ETI['msg_gestor'].'</a></td>
<td><a href="javascript:marcargestor(2, '.$filadet['ofer11idtercero'].', 1)" class="lnkresalte">'.$ETI['msg_director'].'</a></td>';
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1711('.$filadet['ofer11id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
'.$sEscuela.'
<td>'.$sprefijo.cadena_notildes($filadet['ofer10nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['C6_nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$et_unad11telefono.$ssufijo.'</td>
<td>'.$sprefijo.$et_correo.$ssufijo.'</td>
<td>'.$sprefijo.$et_ofer11fecharegistro.$ssufijo.'</td>
<td>'.$sLink.'</td>'.$sCols.'
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1711_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebug)=f1711_TablaDetalleV2($params, $objDB, $bDebug);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1711detalle","innerHTML",$sDetalle);
	return $objResponse;
	}
function f1711_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_ofer11idrol=html_combo_ofer11idrol($objDB, 0);
	$html_ofer11id='<input id="ofer11id" name="ofer11id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ofer11idrol','innerHTML', $html_ofer11idrol);
	$objResponse->assign('div_ofer11id','innerHTML', $html_ofer11id);
	return $objResponse;
	}
function f1711_db_MarcarActor($idOferta, $idRol, $idTercero, $objDB, $bForzar=false, $bDebug=false){
	$sError='';
	$sDebug='';
	$sCampo='ofer08idrespvimep';
	if ($idRol==2){$sCampo='ofer08iddirector';}
	$sSQL='UPDATE ofer08oferta SET '.$sCampo.'='.$idTercero.' WHERE ofer08id='.$idOferta.'';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Marcando el actor: '.$sSQL.'<br>';}
	return array($sError, $sDebug);
	}
function f1711_MarcarActor($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebug)=f1711_db_MarcarActor($opts[0], $opts[1], $opts[2], $objDB, true, $bDebug);
	if ($sError==''){
		list($sDetalle, $sDebug)=f1711_TablaDetalleV2($params, $objDB, $bDebug);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$objResponse->assign("div_f1711detalle","innerHTML",$sDetalle);
		$objResponse->call("MensajeAlarmaV2('Asignaci&oacute;n completa', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>