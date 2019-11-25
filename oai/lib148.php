<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 -2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.1 jueves, 17 de julio de 2014
--- Modelo Versión 2.17.0 lunes, 06 de marzo de 2017
--- 148 Aulas adicionales
*/
function f148_HTMLComboV2_unad48per_aca($objdb, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('unad48per_aca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf148()';
	$res=$objCombos->html('SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca', $objdb);
	return $res;
	}
function f148_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=148;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_148='lg/lg_148_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_148)){$mensajes_148='lg/lg_148_es.php';}
	require $mensajes_todas;
	require $mensajes_148;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	if (isset($valores[0])==0){$valores[0]=0;}
	$unad48idcurso=numeros_validar($valores[1]);
	$unad48per_aca=numeros_validar($valores[2]);
	$unad48consec=numeros_validar($valores[3]);
	$unad48id=numeros_validar($valores[4], true);
	$unad48identificador=htmlspecialchars($valores[5]);
	$unad48numestudiantes=numeros_validar($valores[6]);
	$unad48diainicial=numeros_validar($valores[7]);
	$unad48idnav=numeros_validar($valores[8]);
	//if ($unad48numestudiantes==''){$unad48numestudiantes=0;}
	//if ($unad48diainicial==''){$unad48diainicial=0;}
	//if ($unad48idnav==''){$unad48idnav=0;}
	if ($unad48idnav==''){$sError=$ERR['unad48idnav'];}
	if ($unad48diainicial==''){$sError=$ERR['unad48diainicial'];}
	if ($unad48numestudiantes==''){$sError=$ERR['unad48numestudiantes'];}
	if ($unad48identificador==''){$sError=$ERR['unad48identificador'];}
	//if ($unad48id==''){$sError=$ERR['unad48id'];}//CONSECUTIVO
	//if ($unad48consec==''){$sError=$ERR['unad48consec'];}//CONSECUTIVO
	if ($unad48per_aca==''){$sError=$ERR['unad48per_aca'];}
	if ($unad48idcurso==''){$sError=$ERR['unad48idcurso'];}
	if ($sError==''){
		if ((int)$unad48id==0){
			if ((int)$unad48consec==0){
				$unad48consec=tabla_consecutivo('unad48cursoaula', 'unad48consec', 'unad48idcurso='.$unad48idcurso.' AND unad48per_aca='.$unad48per_aca.'', $objdb);
				if ($unad48consec==-1){$sError=$objdb->serror;}
				if ($unad48consec==1){$unad48consec=2;}
				}
			$sql='SELECT unad48idcurso FROM unad48cursoaula WHERE unad48idcurso='.$unad48idcurso.' AND unad48per_aca='.$unad48per_aca.' AND unad48consec='.$unad48consec.'';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$unad48id=tabla_consecutivo('unad48cursoaula', 'unad48id', '', $objdb);
				if ($unad48id==-1){$sError=$objdb->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$unad48idcursonav=0;
			$scampos='unad48idcurso, unad48per_aca, unad48consec, unad48id, unad48identificador, unad48numestudiantes, unad48diainicial, unad48idnav, unad48idcursonav';
			$svalores=''.$unad48idcurso.', '.$unad48per_aca.', '.$unad48consec.', '.$unad48id.', "'.$unad48identificador.'", '.$unad48numestudiantes.', '.$unad48diainicial.', '.$unad48idnav.', '.$unad48idcursonav.'';
			if ($APP->utf8==1){
				$sql='INSERT INTO unad48cursoaula ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sql='INSERT INTO unad48cursoaula ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Aulas adicionales}.<!-- '.$sql.' -->';
				}else{
				if ($valores[0]!=0){OAI_TotalEstudiantes_Actualizar($valores[0], $objdb);}
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sql, $objdb);
					}
				}
			}else{
			$scampo148[1]='unad48identificador';
			$scampo148[2]='unad48numestudiantes';
			$scampo148[3]='unad48diainicial';
			$svr148[1]=$unad48identificador;
			$svr148[2]=$unad48numestudiantes;
			$svr148[3]=$unad48diainicial;
			$inumcampos=3;
			$iEstDB=0;
			$sWhere='unad48id='.$unad48id.'';
			//$sWhere='unad48idcurso='.$unad48idcurso.' AND unad48per_aca='.$unad48per_aca.' AND unad48consec='.$unad48consec.'';
			$sql='SELECT * FROM unad48cursoaula WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				$iEstDB=$filaorigen['unad48numestudiantes'];
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo148[$k]]!=$svr148[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo148[$k].'="'.$svr148[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE unad48cursoaula SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE unad48cursoaula SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Aulas adicionales}. <!-- '.$sql.' -->';
					}else{
					if ($iEstDB!=$unad48numestudiantes){
						//Actualizar las carga por nav.
						$sql='UPDATE ofer17cargaxnav SET ofer17numestudiantes='.$unad48numestudiantes.' WHERE ofer17per_aca='.$unad48per_aca.' AND ofer17curso='.$unad48idcurso.' AND ofer17numaula='.$unad48consec;
						$result=$objdb->ejecutasql($sql);
						if ($valores[0]!=0){OAI_TotalEstudiantes_Actualizar($valores[0], $objdb);}
						}
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $unad48id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $unad48id, $sDebug);
	}
function f148_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=148;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_148='lg/lg_148_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_148)){$mensajes_148='lg/lg_148_es.php';}
	require $mensajes_todas;
	require $mensajes_148;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$unad48idcurso=numeros_validar($params[1]);
	$unad48per_aca=numeros_validar($params[2]);
	$unad48consec=numeros_validar($params[3]);
	$unad48id=numeros_validar($params[4]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		//Hay que borrar las actividades de esa agenda...
		$sql='DELETE FROM ofer18cargaxnavxdia WHERE ofer18curso='.$unad48idcurso.' AND ofer18numaula='.$unad48consec.' AND ofer18per_aca='.$unad48per_aca.'';
		$result=$objdb->ejecutasql($sql);
		$sWhere='unad48id='.$unad48id.'';
		//$sWhere='unad48idcurso='.$unad48idcurso.' AND unad48per_aca='.$unad48per_aca.' AND unad48consec='.$unad48consec.'';
		$sql='DELETE FROM unad48cursoaula WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {148 Aulas adicionales}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $unad48id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f148_TablaDetalle($params, $objdb){
	list($res, $sDebug)=f148_TablaDetalleV2($params, $objdb, false);
	return $res;
	}
function f148_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_148='lg/lg_148_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_148)){$mensajes_148='lg/lg_148_es.php';}
	require $mensajes_todas;
	require $mensajes_148;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[100])==0){$params[100]='-999';}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$params[0]=numeros_validar($params[0]);
	if ($params[0]==''){$params[0]=-1;}
	$sDebug='';
	$unad40id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sqladd='';
	$sqladd1='';
	$sLeyenda='';
	$sAdd='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Curso, Per_aca, Consec, Id, Entificador, Numestudiantes, Diainicial, Nav, Cursonav';
	$sql='SELECT TB.unad48idcurso, TB.unad48consec, TB.unad48id, TB.unad48identificador, TB.unad48numestudiantes, TB.unad48diainicial 
FROM unad48cursoaula AS TB 
WHERE TB.unad48idcurso='.$unad40id.' AND TB.unad48per_aca='.$params[100].' '.$sqladd.' ORDER BY TB.unad48consec';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_148" name="consulta_148" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_148" name="titulos_148" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 148: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf148" name="paginaf148" type="hidden" value="'.$pagina.'"/><input id="lppf148" name="lppf148" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	if ($params[103]==1){$sAdd=' colspan="3"';}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['unad48consec'].'</b></td>
<td><b>'.$ETI['unad48identificador'].'</b></td>
<td><b>'.$ETI['unad48numestudiantes'].'</b></td>
<td><b>'.$ETI['unad48diainicial'].'</b></td>
<td align="right"'.$sAdd.'>
'.html_paginador('paginaf148', $registros, $lineastabla, $pagina, 'paginarf148()').'
'.html_lpp('lppf148', $lineastabla, 'paginarf148()').'
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf148('.$filadet['unad48id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$sAdd='';
		if ($params[103]==1){
			
			$sAdd='<td><a href="javascript:descargaragendaV2('.$filadet['unad48consec'].')" class="lnkresalte">'.$ETI['lnk_descarga'].'</a></td>
<td><a href="javascript:agendaDatateca('.$filadet['unad48consec'].')" class="lnkresalte">'.$ETI['lnk_datateca'].'</a></td>';
			}
		$paramshija[103]=$params[100];
		$paramshija[104]=$unad40id;
		$paramshija[105]=$filadet['unad48consec'];
		$sInfoHija=f1718_TablaDetalleAgendaHija($paramshija, $objdb);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.$filadet['unad48consec'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['unad48identificador']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['unad48numestudiantes'].$ssufijo.'</td>
<td>'.$sprefijo.$filadet['unad48diainicial'].$ssufijo.'</td>
<td>'.$sLink.'</td>'.$sAdd.'
</tr><tr><td colspan="7">'.$sInfoHija.'</td></tr>';
		
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f148_Clonar($unad48idcurso, $unad48idcursoPadre, $objdb){
	$sError='';
	$unad48consec=tabla_consecutivo('unad48cursoaula', 'unad48consec', 'unad48idcurso='.$unad48idcurso.'', $objdb);
	if ($unad48consec==-1){$sError=$objdb->serror;}
	$unad48id=tabla_consecutivo('unad48cursoaula', 'unad48id', '', $objdb);
	if ($unad48id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos148='unad48idcurso, unad48per_aca, unad48consec, unad48id, unad48identificador, unad48numestudiantes, unad48diainicial, unad48idnav, unad48idcursonav';
		$sValores148='';
		$sql='SELECT * FROM unad48cursoaula WHERE unad48idcurso='.$unad48idcursoPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores148!=''){$sValores148=$sValores148.', ';}
			$sValores148=$sValores148.'('.$unad48idcurso.', '.$fila['unad48per_aca'].', '.$unad48consec.', '.$unad48id.', "'.$fila['unad48identificador'].'", '.$fila['unad48numestudiantes'].', '.$fila['unad48diainicial'].', '.$fila['unad48idnav'].', '.$fila['unad48idcursonav'].')';
			$unad48consec++;
			$unad48id++;
			}
		if ($sValores148!=''){
			$sql='INSERT INTO unad48cursoaula('.$sCampos148.') VALUES '.$sValores148.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 148 Aulas adicionales XAJAX 
function f148_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $unad48id, $sDebugGuardar)=f148_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f148_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f148detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf148('.$unad48id.')');
			//}else{
			$objResponse->call('limpiaf148');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objdb->CerrarConexion();
		}
	return $objResponse;
	}
function f148_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$unad48idcurso=numeros_validar($params[1]);
		$unad48per_aca=numeros_validar($params[2]);
		$unad48consec=numeros_validar($params[3]);
		if (($unad48idcurso!='')&&($unad48per_aca!='')&&($unad48consec!='')){$besta=true;}
		}else{
		$unad48id=$params[103];
		if ((int)$unad48id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$bHayDb=true;
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'unad48idcurso='.$unad48idcurso.' AND unad48per_aca='.$unad48per_aca.' AND unad48consec='.$unad48consec.'';
			}else{
			$sqlcondi=$sqlcondi.'unad48id='.$unad48id.'';
			}
		$sql='SELECT * FROM unad48cursoaula WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$unad48consec_nombre='';
		$html_unad48consec=html_oculto('unad48consec', $fila['unad48consec'], $unad48consec_nombre);
		$objResponse->assign('div_unad48consec', 'innerHTML', $html_unad48consec);
		$unad48id_nombre='';
		$html_unad48id=html_oculto('unad48id', $fila['unad48id'], $unad48id_nombre);
		$objResponse->assign('div_unad48id', 'innerHTML', $html_unad48id);
		$objResponse->assign('unad48identificador', 'value', $fila['unad48identificador']);
		$objResponse->assign('unad48numestudiantes', 'value', $fila['unad48numestudiantes']);
		$objResponse->assign('unad48diainicial', 'value', $fila['unad48diainicial']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina148','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unad48per_aca', 'value', $unad48per_aca);
			$objResponse->assign('unad48consec', 'value', $unad48consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$unad48id.'", 0)');
			}
		}
	if ($bHayDb){
		$objdb->CerrarConexion();
		}
	return $objResponse;
	}
function f148_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sError, $sDebugElimina)=f148_db_Eliminar($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f148_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f148detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf148');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objdb->CerrarConexion();
	return $objResponse;
	}
function f148_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f148_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f148detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f148_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_unad48consec='<input id="unad48consec" name="unad48consec" type="text" value="" onchange="revisaf148()" class="cuatro"/>';
	$html_unad48id='<input id="unad48id" name="unad48id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad48consec','innerHTML', $html_unad48consec);
	$objResponse->assign('div_unad48id','innerHTML', $html_unad48id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
//Duplicar aulas cuando se oferta un curso.
function AulasDuplicar($idPeraca, $idCurso, $objdb){
	$sError='';
	$sql='SELECT TB.unad48consec, TB.unad48identificador, TB.unad48numestudiantes, TB.unad48diainicial, TB.unad48idnav 
FROM unad48cursoaula AS TB 
WHERE TB.unad48idcurso='.$idCurso.' AND TB.unad48per_aca=0';
	$tablabase=$objdb->ejecutasql($sql);
	if ($objdb->nf($tablabase)>0){
		$scampos48='unad48idcurso, unad48per_aca, unad48consec, unad48id, unad48identificador, unad48numestudiantes, unad48diainicial, unad48idnav';
		//Sacar el id.
		$unad48id=tabla_consecutivo('unad48cursoaula', 'unad48id', '', $objdb);
		//Preparar la insersion.
		while ($fila=$objdb->sf($tablabase)){
			$sql='SELECT unad48id FROM unad48cursoaula WHERE unad48idcurso='.$idCurso.' AND unad48per_aca='.$idPeraca.' AND unad48consec='.$fila['unad48consec'];
			//echo $sql.'<br>';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)==0){
				$svalores=''.$idCurso.', '.$idPeraca.', '.$fila['unad48consec'].', '.$unad48id.', "'.$fila['unad48identificador'].'", '.$fila['unad48numestudiantes'].', '.$fila['unad48diainicial'].', '.$fila['unad48idnav'].'';
				$sql='INSERT INTO unad48cursoaula ('.$scampos48.') VALUES ('.$svalores.');';
				$result=$objdb->ejecutasql($sql);
				}
			}
		}
	return $sError;
	}
?>
