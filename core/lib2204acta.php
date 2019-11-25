<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 sábado, 18 de agosto de 2018
--- 2204 Matricula
*/
function f2204_HTMLComboV2_core04peraca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core04peraca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf2204()';
	$sSQL='SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2204_HTMLComboV2_core04idrol($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core04idrol', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf2204()';
	$sSQL='SELECT unad58id AS id, unad58nombre AS nombre FROM unad58rolmoodle';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2204_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2204;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2204='lg/lg_2204_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2204)){$mensajes_2204='lg/lg_2204_es.php';}
	require $mensajes_todas;
	require $mensajes_2204;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$core04peraca=numeros_validar($valores[1]);
	$core04tercero=numeros_validar($valores[2]);
	$core04idcurso=numeros_validar($valores[3]);
	$core04idaula=numeros_validar($valores[4]);
	$core04idrol=numeros_validar($valores[5]);
	$core04id=numeros_validar($valores[6]);
	$core04idnav=numeros_validar($valores[7]);
	$core04idgrupo=numeros_validar($valores[8]);
	//if ($core04id==''){$core04id=0;}
	//if ($core04idnav==''){$core04idnav=0;}
	//if ($core04idgrupo==''){$core04idgrupo=0;}
	$sSepara=', ';
	if ($core04idgrupo==0){$sError=$ERR['core04idgrupo'].$sSepara.$sError;}
	if ($core04idnav==''){$sError=$ERR['core04idnav'].$sSepara.$sError;}
	if ($core04id==''){$sError=$ERR['core04id'].$sSepara.$sError;}
	if ($core04idrol==''){$sError=$ERR['core04idrol'].$sSepara.$sError;}
	if ($core04idaula==''){$sError=$ERR['core04idaula'].$sSepara.$sError;}
	if ($core04idcurso==0){$sError=$ERR['core04idcurso'].$sSepara.$sError;}
	if ($core04tercero==''){$sError=$ERR['core04tercero'].$sSepara.$sError;}
	if ($core04peraca==''){$sError=$ERR['core04peraca'].$sSepara.$sError;}
	if ($sError==''){
		$sSQL='SELECT  FROM  WHERE ="'.$core04idgrupo.'"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){$sError='No se encuentra el Grupo {ref '.$core04idgrupo.'}';}
		}
	if ($sError==''){
		$sSQL='SELECT unad40id FROM unad40curso WHERE unad40id="'.$core04idcurso.'"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){$sError='No se encuentra el Curso {ref '.$core04idcurso.'}';}
		}
	if ($sError==''){
		if ((int)$CampoIdHijo==0){
			if ($sError==''){
				$sSQL='SELECT core04peraca FROM core04matricula WHERE core04peraca='.$core04peraca.' AND core04tercero='.$core04tercero.' AND core04idcurso='.$core04idcurso.' AND core04idaula='.$core04idaula.' AND core04idrol='.$core04idrol.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$core04idgrupo=0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
				//$fila=$objDB->sf($tabla);
				//$sCampo=$fila['sCampo'];
				//}
			$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='core04peraca, core04tercero, core04idcurso, core04idaula, core04idrol, 
core04id, core04idnav, core04idgrupo';
			$svalores=''.$core04peraca.', '.$core04tercero.', '.$core04idcurso.', '.$core04idaula.', '.$core04idrol.', 
'.$core04id.', '.$core04idnav.', '.$core04idgrupo.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core04matricula ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO core04matricula ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Matricula}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $CampoIdHijo, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2204[1]='core04id';
			$scampo2204[2]='core04idnav';
			$svr2204[1]=$core04id;
			$svr2204[2]=$core04idnav;
			$inumcampos=2;
			$sWhere='CampoIdHijo='.$CampoIdHijo.'';
			//$sWhere='core04peraca='.$core04peraca.' AND core04tercero='.$core04tercero.' AND core04idcurso='.$core04idcurso.' AND core04idaula='.$core04idaula.' AND core04idrol='.$core04idrol.'';
			$sSQL='SELECT * FROM core04matricula WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2204[$k]]!=$svr2204[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2204[$k].'="'.$svr2204[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE core04matricula SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE core04matricula SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Matricula}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $CampoIdHijo, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $CampoIdHijo, $sDebug);
	}
function f2204_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2204;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2204='lg/lg_2204_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2204)){$mensajes_2204='lg/lg_2204_es.php';}
	require $mensajes_todas;
	require $mensajes_2204;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$core04peraca=numeros_validar($aParametros[1]);
	$core04tercero=numeros_validar($aParametros[2]);
	$core04idcurso=numeros_validar($aParametros[3]);
	$core04idaula=numeros_validar($aParametros[4]);
	$core04idrol=numeros_validar($aParametros[5]);
	$CampoIdHijo=numeros_validar($aParametros[6]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2204';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$CampoIdHijo.' LIMIT 0, 1';
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
		//acciones previas
		$sWhere='CampoIdHijo='.$CampoIdHijo.'';
		//$sWhere='core04peraca='.$core04peraca.' AND core04tercero='.$core04tercero.' AND core04idcurso='.$core04idcurso.' AND core04idaula='.$core04idaula.' AND core04idrol='.$core04idrol.'';
		$sSQL='DELETE FROM core04matricula WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2204 Matricula}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $CampoIdHijo, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2204_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2204='lg/lg_2204_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2204)){$mensajes_2204='lg/lg_2204_es.php';}
	require $mensajes_todas;
	require $mensajes_2204;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[1])==0){$aParametros[1]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$core16tercero=$aParametros[0];
	$core16peraca=$aParametros[1];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	list($idBloque, $sError)=f1011_BloqueTercero($core16tercero, $objDB);
	//$sSQL='SELECT Campo FROM core16actamatricula WHERE core16id='.$core16tercero;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if ($idBloque==0){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> No ha sido posible acceder a los contenedores, por favor informe al administrador del sistema.<br>
Informaci&oacute;n para el administrador: '.$sError.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2204" name="paginaf2204" type="hidden" value="'.$pagina.'"/><input id="lppf2204" name="lppf2204" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$idContPeraca=f146_Contenedor($core16peraca, $objDB);
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
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
	$sTabla='core04matricula_'.$idBloque;
	$sTabla06='core06grupos_'.$idContPeraca;
	$sTitulos='Peraca, Tercero, Curso, Aula, Rol, Id, Nav, Grupo';
	$sSQL='SELECT T3.unad40consec, T3.unad40nombre, TB.core04idaula, TB.core04id, TB.core04idgrupo, TB.core04peraca, TB.core04idcurso, TB.core04idrol, TB.core04idnav, T6.core06consec, TB.core04aplicoagenda 
FROM '.$sTabla.' AS TB, unad40curso AS T3, '.$sTabla06.' AS T6  
WHERE '.$sSQLadd1.' TB.core04tercero='.$core16tercero.' AND TB.core04peraca='.$core16peraca.' AND TB.core04idcurso=T3.unad40id  AND TB.core04idgrupo=T6.core06id '.$sSQLadd.'
ORDER BY TB.core04idcurso, TB.core04idaula, TB.core04idrol';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2204" name="consulta_2204" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2204" name="titulos_2204" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2204: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2204" name="paginaf2204" type="hidden" value="'.$pagina.'"/><input id="lppf2204" name="lppf2204" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['core04idcurso'].'</b></td>
<td><b>'.$ETI['core04idaula'].'</b></td>
<td><b>'.$ETI['core04idgrupo'].'</b></td>
<td><b>'.'Actividades'.'</b></td>
<td align="right">
'.html_paginador('paginaf2204', $registros, $lineastabla, $pagina, 'paginarf2204()').'
'.html_lpp('lppf2204', $lineastabla, 'paginarf2204()').'
</td>
</tr>';
	$tlinea=1;
	$aAula=array('', 'A', 'B', 'C', 'D', 'E');
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
		$et_core04idcurso=$sPrefijo.$filadet['unad40consec'].' '.cadena_notildes($filadet['unad40nombre']).$sSufijo;
		$et_core04idaula=$sPrefijo.$aAula[$filadet['core04idaula']].$sSufijo;
		$et_core04idgrupo=$sPrefijo.'{'.$ETI['msg_ninguno'].'}'.$sSufijo;
		if ($filadet['core04idgrupo']!=0){
			$et_core04idgrupo=$sPrefijo.$filadet['core06consec'].$sSufijo;
			}
		$sActividades='{Pendiente}';
		if ($filadet['core04aplicoagenda']!=0){
			$sActividades='{Sin actividades.}';
			$sSQL='SELECT core05estado, COUNT(core05id) AS Total FROM core05actividades_'.$idBloque.' WHERE core05tercero='.$core16tercero.' AND core05idcurso='.$filadet['core04idcurso'].' AND core05peraca='.$core16peraca.' GROUP BY core05estado';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sActividades='';
				while ($fila=$objDB->sf($tabla)){
					switch($fila['core05estado']){
						case 0:
						$sTituloEstado='Pendientes';
						break;
						case 1:
						$sTituloEstado='Iniciadas';
						break;
						case 3:
						$sTituloEstado='Presentadas';
						break;
						case 5:
						$sTituloEstado='No presentadas';
						break;
						case 7:
						$sTituloEstado='Calificadas';
						break;
						default:
						$sTituloEstado='[Estado '.$fila['core05estado'].']';
						break;
						}
					$sActividades=$sActividades.' '.$fila['Total'].' '.$sTituloEstado;
					}
				}
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2204('.$filadet['core04id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_core04idcurso.'</td>
<td>'.$et_core04idaula.'</td>
<td>'.$et_core04idgrupo.'</td>
<td>'.$sActividades.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2204_Clonar($core04tercero, $core04terceroPadre, $objDB){
	$sError='';
	if ($sError==''){
		$sCampos2204='core04peraca, core04tercero, core04idcurso, core04idaula, core04idrol, core04id, core04idnav, core04idgrupo';
		$sValores2204='';
		$sSQL='SELECT * FROM core04matricula WHERE core04tercero='.$core04terceroPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2204!=''){$sValores2204=$sValores2204.', ';}
			$sValores2204=$sValores2204.'('.$fila['core04peraca'].', '.$core04tercero.', '.$fila['core04idcurso'].', '.$fila['core04idaula'].', '.$fila['core04idrol'].', '.$fila['core04id'].', '.$fila['core04idnav'].', '.$fila['core04idgrupo'].')';
			}
		if ($sValores2204!=''){
			$sSQL='INSERT INTO core04matricula('.$sCampos2204.') VALUES '.$sValores2204.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2204 Matricula XAJAX 
function f2204_Busqueda_db_core04idcurso($sCodigo, $objDB, $bDebug=false){
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
function f2204_Busqueda_core04idcurso($aParametros){
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
		list($id, $sRespuesta, $sDebugCon)=f2204_Busqueda_db_core04idcurso($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('revisaf2204');
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2204_Busqueda_db_core04idgrupo($sCodigo, $objDB, $bDebug=false){
	$sRespuesta='';
	$sDebug='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT , ,  FROM  WHERE ="'.$sCodigo.'"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Busqueda: '.$sSQL.'<br>';}
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila[''].' '.cadena_notildes($fila['']).'</b>';
			$id=$fila[''];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta), $sDebug);
	}
function f2204_Busqueda_core04idgrupo($aParametros){
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
		list($id, $sRespuesta, $sDebugCon)=f2204_Busqueda_db_core04idgrupo($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2204_Guardar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $CampoIdHijo, $sDebugGuardar)=f2204_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2204_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2204detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2204('.$CampoIdHijo.')');
			//}else{
			$objResponse->call('limpiaf2204');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2204_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$core04peraca=numeros_validar($aParametros[1]);
		$core04tercero=numeros_validar($aParametros[2]);
		$core04idcurso=numeros_validar($aParametros[3]);
		$core04idaula=numeros_validar($aParametros[4]);
		$core04idrol=numeros_validar($aParametros[5]);
		if (($core04peraca!='')&&($core04tercero!='')&&($core04idcurso!='')&&($core04idaula!='')&&($core04idrol!='')){$besta=true;}
		}else{
		$CampoIdHijo=$aParametros[103];
		if ((int)$CampoIdHijo!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'core04peraca='.$core04peraca.' AND core04tercero='.$core04tercero.' AND core04idcurso='.$core04idcurso.' AND core04idaula='.$core04idaula.' AND core04idrol='.$core04idrol.'';
			}else{
			$sSQLcondi=$sSQLcondi.'CampoIdHijo='.$CampoIdHijo.'';
			}
		$sSQL='SELECT * FROM core04matricula WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		if (isset($APP->piel)==0){$APP->piel=1;}
		$iPiel=$APP->piel;
		$core04idcurso_nombre='';
		$core04idcurso_cod='';
		if ((int)$fila['core04idcurso']!=0){
			$sSQL='SELECT unad40id, unad40nombre FROM unad40curso WHERE unad40id='.$fila['core04idcurso'].'';
			$res=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($res)!=0){
				$filaDetalle=$objDB->sf($res);
				$core04idcurso_nombre='<b>'.cadena_notildes($filaDetalle['unad40nombre']).'</b>';
				$core04idcurso_cod=$filaDetalle['unad40id'];
				}
			if ($core04idcurso_nombre==''){
				$core04idcurso_nombre='<font class="rojo">{Ref : '.$fila['core04idcurso'].' No encontrado}</font>';
				}
			}
		$core04idgrupo_nombre='';
		$core04idgrupo_cod='';
		if ((int)$fila['core04idgrupo']!=0){
			$sSQL='SELECT ,  FROM  WHERE ='.$fila['core04idgrupo'].'';
			$res=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($res)!=0){
				$filaDetalle=$objDB->sf($res);
				$core04idgrupo_nombre='<b>'.cadena_notildes($filaDetalle['']).'</b>';
				$core04idgrupo_cod=$filaDetalle[''];
				}
			if ($core04idgrupo_nombre==''){
				$core04idgrupo_nombre='<font class="rojo">{Ref : '.$fila['core04idgrupo'].' No encontrado}</font>';
				}
			}
		list($core04peraca_nombre, $serror_det)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id', $fila['core04peraca'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_core04peraca=html_oculto('core04peraca', $fila['core04peraca'], $core04peraca_nombre);
		$objResponse->assign('div_core04peraca', 'innerHTML', $html_core04peraca);
		$html_core04idcurso_cod=html_oculto('core04idcurso_cod', $core04idcurso_cod);
		$objResponse->assign('core04idcurso', 'value', $fila['core04idcurso']);
		$objResponse->assign('div_core04idcurso_cod', 'innerHTML', $html_core04idcurso_cod);
		$objResponse->call("verboton('bcore04idcurso','none')");
		$objResponse->assign('div_core04idcurso', 'innerHTML', $core04idcurso_nombre);
		$core04idaula_nombre='';
		$html_core04idaula=html_oculto('core04idaula', $fila['core04idaula'], $core04idaula_nombre);
		$objResponse->assign('div_core04idaula', 'innerHTML', $html_core04idaula);
		list($core04idrol_nombre, $serror_det)=tabla_campoxid('unad58rolmoodle','unad58nombre','unad58id', $fila['core04idrol'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_core04idrol=html_oculto('core04idrol', $fila['core04idrol'], $core04idrol_nombre);
		$objResponse->assign('div_core04idrol', 'innerHTML', $html_core04idrol);
		$objResponse->assign('core04id', 'value', $fila['core04id']);
		$objResponse->assign('core04idnav', 'value', $fila['core04idnav']);
		$html_core04idgrupo_cod=html_oculto('core04idgrupo_cod', $core04idgrupo_cod);
		$objResponse->assign('core04idgrupo', 'value', $fila['core04idgrupo']);
		$objResponse->assign('core04idgrupo_cod', 'value', $core04idgrupo_cod);
		$objResponse->assign('div_core04idgrupo', 'innerHTML', $core04idgrupo_nombre);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2204','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('core04peraca', 'value', $core04peraca);
			$objResponse->assign('core04idcurso', 'value', $core04idcurso);
			$objResponse->assign('core04idaula', 'value', $core04idaula);
			$objResponse->assign('core04idrol', 'value', $core04idrol);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$CampoIdHijo.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2204_Eliminar($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f2204_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2204_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2204detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2204');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objDB->CerrarConexion();
	return $objResponse;
	}
function f2204_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2204_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2204detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2204_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$objCombos=new clsHtmlCombos('n');
	$html_core04peraca=f2204_HTMLComboV2_core04peraca($objDB, $objCombos, 0);
	$html_core04idcurso_cod='<input id="core04idcurso_cod" name="core04idcurso_cod" type="text" value="" onchange="cod_core04idcurso()" class="veinte"/>';
	$html_core04idaula='<input id="core04idaula" name="core04idaula" type="text" value="" onchange="revisaf2204()" class="cuatro"/>';
	$html_core04idrol=f2204_HTMLComboV2_core04idrol($objDB, $objCombos, 0);
	$html_core04idgrupo_cod='<input id="core04idgrupo_cod" name="core04idgrupo_cod" type="text" value="" onchange="cod_core04idgrupo()" class="veinte"/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core04peraca','innerHTML', $html_core04peraca);
	$objResponse->assign('core04idcurso','value', '0');
	$objResponse->assign('div_core04idcurso_cod','innerHTML', $html_core04idcurso_cod);
	$objResponse->assign('div_core04idcurso','innerHTML', '');
	$objResponse->call("verboton('bcore04idcurso','block')");
	$objResponse->assign('div_core04idaula','innerHTML', $html_core04idaula);
	$objResponse->assign('div_core04idrol','innerHTML', $html_core04idrol);
	$objResponse->assign('core04idgrupo','value', '0');
	$objResponse->assign('div_core04idgrupo_cod','innerHTML', $html_core04idgrupo_cod);
	$objResponse->assign('div_core04idgrupo','innerHTML', '');
	$objResponse->call("verboton('bcore04idgrupo','block')");
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>