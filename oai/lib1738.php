<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 jueves, 01 de octubre de 2015
--- Modelo Versión 2.12.9 viernes, 06 de mayo de 2016
--- Modelo Versión 2.15.6 sábado, 20 de agosto de 2016
--- 1738 Matricula del curso
*/
function f1738_HTMLComboV2_ofer38idrol($objdb, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
/*
	$objCombos->nuevo('ofer38idrol', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf1738()';
	$res=$objCombos->html('SELECT unad58id AS id, unad58nombre AS nombre FROM unad58rolmoodle', $objdb);
*/
	$res=html_combo('ofer38idrol', 'unad58id', 'CONCAT(unad58nombre, " {", unad58id, "}")', 'unad58rolmoodle', 'unad58id IN (3, 4, 5, 6, 9, 10, 11, 14, 18)', 'unad58id', $valor, $objdb, 'revisaf1738()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1738_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=1738;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1738='lg/lg_1738_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1738)){$mensajes_1738='lg/lg_1738_es.php';}
	require $mensajes_todas;
	require $mensajes_1738;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($valores[13])==0){$valores[13]=0;}
	$ofer38idoferta=numeros_validar($valores[1]);
	$ofer38idtercero=numeros_validar($valores[2]);
	$ofer38idrol=numeros_validar($valores[3]);
	$ofer38grupo=numeros_validar($valores[4]);
	$ofer38origenmatricula=numeros_validar($valores[5]);
	$ofer38id=numeros_validar($valores[6], true);
	$ofer38activo=htmlspecialchars(trim($valores[7]));
	$ofer38detalle=htmlspecialchars(trim($valores[8]));
	$ofer38usuario=numeros_validar($valores[9]);
	$ofer38fechamat=$valores[10];
	$ofer38horamat=numeros_validar($valores[11]);
	$ofer38minmat=numeros_validar($valores[12]);
	$ofer38grupo_cod=htmlspecialchars(trim($valores[13]));
	if ($ofer38grupo_cod!=''){
		list($ofer38grupo, $bNuevo)=f1738_CrearGrupo($ofer38grupo_cod, $objdb);
		}else{
		$ofer38grupo=0;
		}
	if ($ofer38grupo==''){$ofer38grupo=0;}
	if ($ofer38usuario==''){$ofer38usuario=0;}
	if ($ofer38horamat==''){$ofer38horamat=0;}
	if ($ofer38minmat==''){$ofer38minmat=0;}
	//if ($ofer38minmat==''){$sError=$ERR['ofer38minmat'];}
	//if ($ofer38horamat==''){$sError=$ERR['ofer38horamat'];}
	if (!fecha_esvalida($ofer38fechamat)){
		$ofer38fechamat='00/00/0000';
		//$sError=$ERR['ofer38fechamat'];
		}
	//if ($ofer38usuario==0){$sError=$ERR['ofer38usuario'];}
	//if ($ofer38detalle==''){$sError=$ERR['ofer38detalle'];}
	if ($ofer38activo==''){$sError=$ERR['ofer38activo'];}
	//if ($ofer38id==''){$sError=$ERR['ofer38id'];}//CONSECUTIVO
	if ($ofer38idrol==''){$sError=$ERR['ofer38idrol'];}
	if ($ofer38idtercero==0){$sError=$ERR['ofer38idtercero'];}
	if ($ofer38idoferta==''){$sError=$ERR['ofer38idoferta'];}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($ofer38usuario, $objdb);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($ofer38idtercero, $objdb);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$ofer38id==0){
			if ($sError==''){
				$sql='SELECT ofer38idoferta FROM ofer38matricula WHERE ofer38idoferta='.$ofer38idoferta.' AND ofer38idtercero="'.$ofer38idtercero.'" AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.' AND ofer38origenmatricula=0'; // '.$ofer38origenmatricula.'
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'].' - '.$ofer38grupo;
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$ofer38id=tabla_consecutivo('ofer38matricula', 'ofer38id', '', $objdb);
				if ($ofer38id==-1){$sError=$objdb->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$ofer38origenmatricula=0;
			}
		}
	if ($sError==''){
		//Si el campo ofer38detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$ofer38detalle=str_replace('"', '\"', $ofer38detalle);
		$ofer38detalle=str_replace('&quot;', '\"', $ofer38detalle);
		if ($binserta){
			$ofer38usuario=$_SESSION['unad_id_tercero'];
			$ofer38horamat=fecha_hora();
			$ofer38minmat=fecha_minuto();
			$ofer38fechamat=fecha_hoy();
			$scampos='ofer38idoferta, ofer38idtercero, ofer38idrol, ofer38grupo, ofer38origenmatricula, ofer38id, ofer38activo, ofer38detalle, ofer38usuario, ofer38fechamat, ofer38horamat, ofer38minmat';
			$svalores=''.$ofer38idoferta.', "'.$ofer38idtercero.'", '.$ofer38idrol.', '.$ofer38grupo.', '.$ofer38origenmatricula.', '.$ofer38id.', "'.$ofer38activo.'", "'.$ofer38detalle.'", "'.$ofer38usuario.'", "'.$ofer38fechamat.'", '.$ofer38horamat.', '.$ofer38minmat.'';
			if ($APP->utf8==1){
				$sql='INSERT INTO ofer38matricula ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sql='INSERT INTO ofer38matricula ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Matricula del curso}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $ofer38id, $sql, $objdb);
					}
				}
			}else{
			$scampo1738[1]='ofer38activo';
			$scampo1738[2]='ofer38detalle';
			$svr1738[1]=$ofer38activo;
			$svr1738[2]=$ofer38detalle;
			$inumcampos=2;
			$sWhere='ofer38id='.$ofer38id.'';
			//$sWhere='ofer38idoferta='.$ofer38idoferta.' AND ofer38idtercero="'.$ofer38idtercero.'" AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.' AND ofer38origenmatricula='.$ofer38origenmatricula.'';
			$sql='SELECT * FROM ofer38matricula WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1738[$k]]!=$svr1738[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1738[$k].'="'.$svr1738[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE ofer38matricula SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE ofer38matricula SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Matricula del curso}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $ofer38id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $ofer38id, $sDebug);
	}
function f1738_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=1738;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1738='lg/lg_1738_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1738)){$mensajes_1738='lg/lg_1738_es.php';}
	require $mensajes_todas;
	require $mensajes_1738;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer38idoferta=numeros_validar($params[1]);
	$ofer38idtercero=numeros_validar($params[2]);
	$ofer38idrol=numeros_validar($params[3]);
	$ofer38grupo=numeros_validar($params[4]);
	$ofer38origenmatricula=numeros_validar($params[5]);
	$ofer38id=numeros_validar($params[6]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sql='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1738';
		$tablaor=$objdb->ejecutasql($sql);
		while ($filaor=$objdb->sf($tablaor)){
			$sql='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$ofer38id.' LIMIT 0, 1';
			$tabla=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='ofer38id='.$ofer38id.'';
		//$sWhere='ofer38idoferta='.$ofer38idoferta.' AND ofer38idtercero="'.$ofer38idtercero.'" AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.'';
		$sql='DELETE FROM ofer38matricula WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1738 Matricula del curso}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $ofer38id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
// Si necesita compatilidad con versiones anteriores se habilita esta parte.
function f1738_TablaDetalle($params, $objdb){
	list($sRes, $sDebug)=f1738_TablaDetalleV2($params, $objdb);
	return $sRes;
	}
function f1738_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1738='lg/lg_1738_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1738)){$mensajes_1738='lg/lg_1738_es.php';}
	require $mensajes_todas;
	require $mensajes_1738;
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	//$params[103]=numeros_validar($params[103]);
	$params[0]=numeros_validar($params[0]);
	if ($params[0]==''){$params[0]=-1;}
	$ofer08id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$sqladd='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	if ($params[103]!=''){$sqladd=$sqladd.' AND T2.unad11razonsocial LIKE "%'.$params[103].'%"';}
	if ($params[104]!=''){$sqladd=$sqladd.' AND T2.unad11doc LIKE "%'.$params[104].'%"';}
	$sTitulos='Oferta, Tercero, Rol, Id, Activo, Detalle, Usuario, Fechamat, Horamat, Minmat';
	$sql='SELECT TB.ofer38idoferta, T2.unad11razonsocial AS C2_nombre, T3.unad58nombre, TB.ofer38id, TB.ofer38grupo, TB.ofer38activo, TB.ofer38detalle, TB.ofer38fechamat, TB.ofer38horamat, TB.ofer38minmat, TB.ofer38idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.ofer38idrol, TB.ofer38usuario, TB.ofer38origenmatricula 
FROM ofer38matricula AS TB, unad11terceros AS T2, unad58rolmoodle AS T3 
WHERE TB.ofer38idoferta='.$ofer08id.' AND TB.ofer38idtercero=T2.unad11id AND TB.ofer38idrol=T3.unad58id '.$sqladd.' 
ORDER BY TB.ofer38activo DESC, TB.ofer38idrol DESC, T2.unad11doc';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1738" name="consulta_1738" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1738" name="titulos_1738" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1738: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1738" name="paginaf1738" type="hidden" value="'.$pagina.'"/><input id="lppf1738" name="lppf1738" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['ofer38idrol'].'</b></td>
<td colspan="2"><b>'.$ETI['ofer38idtercero'].'</b></td>
<td><b>'.$ETI['ofer38activo'].'</b></td>
<td><b>'.$ETI['ofer38grupo'].'</b></td>
<td><b>'.$ETI['ofer38grupo'].'</b></td>
<td><b>'.$ETI['ofer38origenmatricula'].'</b></td>
<td align="right">
'.html_paginador('paginaf1738', $registros, $lineastabla, $pagina, 'paginarf1738()').'
'.html_lpp('lppf1738', $lineastabla, 'paginarf1738()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_ofer38activo=$ETI['si'];
		if ($filadet['ofer38activo']!='S'){
			$et_ofer38activo=$ETI['no'];
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_ofer38grupo=$sPrefijo.$filadet['ofer38grupo'].$sSufijo;
		$et_ofer38fechamat='';
		if ($filadet['ofer38fechamat']!='00/00/0000'){$et_ofer38fechamat=$filadet['ofer38fechamat'];}
		$et_ofer38horamat=html_TablaHoraMin($filadet['ofer38horamat'], $filadet['ofer38minmat']);
		$et_ofer38origenmatricula='{'.$filadet['ofer38origenmatricula'].'}';
		switch($filadet['ofer38origenmatricula']){
			case 0:
			$et_ofer38origenmatricula='Manual';
			break;
			case 17:
			$et_ofer38origenmatricula='OAI';
			break;
			case 19:
			$et_ofer38origenmatricula='OIL';
			break;
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1738('."'".$filadet['ofer38id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['unad58nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_ofer38activo.$sSufijo.'</td>
<td>'.$et_ofer38grupo.'</td>
<td>'.$sPrefijo.$et_ofer38origenmatricula.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ofer38detalle']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1738_Clonar($ofer38idoferta, $ofer38idofertaPadre, $objdb){
	$sError='';
	$ofer38id=tabla_consecutivo('ofer38matricula', 'ofer38id', '', $objdb);
	if ($ofer38id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1738='ofer38idoferta, ofer38idtercero, ofer38idrol, ofer38grupo, ofer38origenmatricula, ofer38id, ofer38activo, ofer38detalle, ofer38usuario, ofer38fechamat, ofer38horamat, ofer38minmat';
		$sValores1738='';
		$sql='SELECT * FROM ofer38matricula WHERE ofer38idoferta='.$ofer38idofertaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1738!=''){$sValores1738=$sValores1738.', ';}
			$sValores1738=$sValores1738.'('.$ofer38idoferta.', '.$fila['ofer38idtercero'].', '.$fila['ofer38idrol'].', '.$fila['ofer38grupo'].', '.$fila['ofer38origenmatricula'].', '.$ofer38id.', "'.$fila['ofer38activo'].'", "'.$fila['ofer38detalle'].'", '.$fila['ofer38usuario'].', "'.$fila['ofer38fechamat'].'", '.$fila['ofer38horamat'].', '.$fila['ofer38minmat'].')';
			$ofer38id++;
			}
		if ($sValores1738!=''){
			$sql='INSERT INTO ofer38matricula('.$sCampos1738.') VALUES '.$sValores1738.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1738 Matricula del curso XAJAX 
function TraerBusqueda_db_ofer38grupo($sCodigo, $objdb){
	$sRespuesta='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sql='SELECT ofer41id, ofer41codigo, ofer41codigo FROM ofer41grupos WHERE ofer41codigo="'.$sCodigo.'"';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)!=0){
			$fila=$objdb->sf($res);
			$sRespuesta='<b>'.$fila['ofer41codigo'].' '.cadena_notildes($fila['ofer41codigo']).'</b>';
			$id=$fila['ofer41id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta));
	}
function TraerBusqueda_ofer38grupo($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($id, $sRespuesta)=TraerBusqueda_db_ofer38grupo($scodigo, $objdb);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('revisaf1738');
		}
	return $objResponse;
	}
function f1738_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($sError, $iAccion, $ofer38id, $sDebugGuardar)=f1738_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1738_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1738detalle', 'innerHTML', $sdetalle);
			$objResponse->call('limpiaf1738');
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1738_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$paso=$params[0];
	if ($paso==1){
		$ofer38idoferta=numeros_validar($params[1]);
		$ofer38idtercero=numeros_validar($params[2]);
		$ofer38idrol=numeros_validar($params[3]);
		$ofer38grupo='';
		$ofer38grupo_cod=htmlspecialchars($params[4]);
		$sql='SELECT ofer41id FROM ofer41grupos WHERE ofer41codigo="'.$ofer38grupo_cod.'"';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$ofer38grupo=$fila['ofer41id'];
			}
		$ofer38origenmatricula=numeros_validar($params[5]);
		if (($ofer38idoferta!='')&&($ofer38idtercero!='')&&($ofer38idrol!='')&&($ofer38grupo!='')){$besta=true;}
		}else{
		$ofer38id=$params[103];
		if ((int)$ofer38id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'ofer38idoferta='.$ofer38idoferta.' AND ofer38idtercero='.$ofer38idtercero.' AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.' AND ofer38origenmatricula='.$ofer38origenmatricula.'';
			}else{
			$sqlcondi=$sqlcondi.'ofer38id='.$ofer38id.'';
			}
		$sql='SELECT * FROM ofer38matricula WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$ofer38idtercero_id=(int)$fila['ofer38idtercero'];
		$ofer38idtercero_td=$APP->tipo_doc;
		$ofer38idtercero_doc='';
		$ofer38idtercero_nombre='';
		if ($ofer38idtercero_id!=0){
			list($ofer38idtercero_nombre, $ofer38idtercero_id, $ofer38idtercero_td, $ofer38idtercero_doc)=html_tercero($ofer38idtercero_td, $ofer38idtercero_doc, $ofer38idtercero_id, 0, $objdb);
			}
		$ofer38grupo_nombre='';
		$ofer38grupo_cod='';
		if ((int)$fila['ofer38grupo']!=0){
			$sql='SELECT ofer41codigo, ofer41codigo FROM ofer41grupos WHERE ofer41id='.$fila['ofer38grupo'].'';
			$res=$objdb->ejecutasql($sql);
			if ($objdb->nf($res)!=0){
				$filaDetalle=$objdb->sf($res);
				$ofer38grupo_nombre='<b>'.cadena_notildes($filaDetalle['ofer41codigo']).'</b>';
				$ofer38grupo_cod=$filaDetalle['ofer41codigo'];
				}
			if ($ofer38grupo_nombre==''){
				$ofer38grupo_nombre='<font class="rojo">{Ref : '.$fila['ofer38grupo'].' No encontrado}</font>';
				}
			}
		$ofer38usuario_id=(int)$fila['ofer38usuario'];
		$ofer38usuario_td=$APP->tipo_doc;
		$ofer38usuario_doc='';
		$ofer38usuario_nombre='';
		if ($ofer38usuario_id!=0){
			list($ofer38usuario_nombre, $ofer38usuario_id, $ofer38usuario_td, $ofer38usuario_doc)=html_tercero($ofer38usuario_td, $ofer38usuario_doc, $ofer38usuario_id, 0, $objdb);
			}
		$html_ofer38idtercero_llaves=html_DivTercero('ofer38idtercero', $ofer38idtercero_td, $ofer38idtercero_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('ofer38idtercero', 'value', $ofer38idtercero_id);
		$objResponse->assign('div_ofer38idtercero_llaves', 'innerHTML', $html_ofer38idtercero_llaves);
		$objResponse->assign('div_ofer38idtercero', 'innerHTML', $ofer38idtercero_nombre);
		list($ofer38idrol_nombre, $serror_det)=tabla_campoxid('unad58rolmoodle','unad58nombre','unad58id', $fila['ofer38idrol'],'{'.$ETI['msg_sindato'].'}', $objdb);
		$html_ofer38idrol=html_oculto('ofer38idrol', $fila['ofer38idrol'], $ofer38idrol_nombre);
		$objResponse->assign('div_ofer38idrol', 'innerHTML', $html_ofer38idrol);
		$html_ofer38grupo_cod=html_oculto('ofer38grupo_cod', $ofer38grupo_cod);
		$objResponse->assign('ofer38grupo', 'value', $fila['ofer38grupo']);
		$objResponse->assign('div_ofer38grupo_cod', 'innerHTML', $html_ofer38grupo_cod);
		$objResponse->call("verboton('bofer38grupo','none')");
		$objResponse->assign('div_ofer38grupo', 'innerHTML', $ofer38grupo_nombre);
		$ofer38id_nombre='';
		$html_ofer38id=html_oculto('ofer38id', $fila['ofer38id'], $ofer38id_nombre);
		$objResponse->assign('div_ofer38id', 'innerHTML', $html_ofer38id);
		$objResponse->assign('ofer38activo', 'value', $fila['ofer38activo']);
		$objResponse->assign('ofer38detalle', 'value', $fila['ofer38detalle']);
		$bOculto=true;
		$html_ofer38usuario_llaves=html_DivTercero('ofer38usuario', $ofer38usuario_td, $ofer38usuario_doc, $bOculto, $ofer38usuario_id, $ETI['ing_doc']);
		$objResponse->assign('ofer38usuario', 'value', $ofer38usuario_id);
		$objResponse->assign('div_ofer38usuario_llaves', 'innerHTML', $html_ofer38usuario_llaves);
		$objResponse->assign('div_ofer38usuario', 'innerHTML', $ofer38usuario_nombre);
		$html_ofer38fechamat=html_oculto('ofer38fechamat', $fila['ofer38fechamat'], formato_fechalarga($fila['ofer38fechamat']));
		$objResponse->assign('div_ofer38fechamat', 'innerHTML', $html_ofer38fechamat);
		$html_ofer38horamat=html_HoraMin('ofer38horamat', $fila['ofer38horamat'], 'ofer38minmat', $fila['ofer38minmat'], true);
		$objResponse->assign('div_ofer38horamat', 'innerHTML', $html_ofer38horamat);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1738','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('ofer38idrol', 'value', $ofer38idrol);
			$objResponse->assign('ofer38grupo', 'value', $ofer38grupo);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$ofer38id.'", 0)');
			}
		}
	return $objResponse;
	}
function f1738_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1738_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1738detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1738_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos=new clsHtmlCombos('n');
	$ofer38idtercero=0;
	$ofer38idtercero_rs='';
	$html_ofer38idtercero_llaves=html_DivTercero('ofer38idtercero', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_ofer38idrol=f1738_HTMLComboV2_ofer38idrol($objdb, $objCombos, 0);
	$html_ofer38grupo_cod='<input id="ofer38grupo_cod" name="ofer38grupo_cod" type="text" value="" onchange="cod_ofer38grupo()" class="diez"/>';
	$html_ofer38id='<input id="ofer38id" name="ofer38id" type="hidden" value=""/>';
	list($ofer38usuario_rs, $ofer38usuario, $ofer38usuario_td, $ofer38usuario_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objdb);
	$html_ofer38usuario_llaves=html_DivTercero('ofer38usuario', $ofer38usuario_td, $ofer38usuario_doc, true, 0, $ETI['ing_doc']);
	$sofer38fechamat=fecha_hoy();
	$html_ofer38fechamat=html_oculto('ofer38fechamat', $sofer38fechamat, formato_fechalarga($sofer38fechamat));
	$html_ofer38horamat=html_HoraMin('ofer38horamat', fecha_hora(), 'ofer38minmat', fecha_minuto(), true);
	$objResponse=new xajaxResponse();
	$objResponse->assign('ofer38idtercero','value', $ofer38idtercero);
	$objResponse->assign('div_ofer38idtercero_llaves','innerHTML', $html_ofer38idtercero_llaves);
	$objResponse->assign('div_ofer38idtercero','innerHTML', $ofer38idtercero_rs);
	$objResponse->assign('div_ofer38idrol','innerHTML', $html_ofer38idrol);
	$objResponse->assign('ofer38grupo','value', '0');
	$objResponse->assign('div_ofer38grupo_cod','innerHTML', $html_ofer38grupo_cod);
	$objResponse->assign('div_ofer38grupo','innerHTML', '');
	$objResponse->call("verboton('bofer38grupo','block')");
	$objResponse->assign('div_ofer38id','innerHTML', $html_ofer38id);
	$objResponse->assign('ofer38usuario','value', $ofer38usuario);
	$objResponse->assign('div_ofer38usuario_llaves','innerHTML', $html_ofer38usuario_llaves);
	$objResponse->assign('div_ofer38usuario','innerHTML', $ofer38usuario_rs);
	$objResponse->assign('div_ofer38fechamat','innerHTML', $html_ofer38fechamat);
	$objResponse->assign('div_ofer38horamat','innerHTML', $html_ofer38horamat);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>