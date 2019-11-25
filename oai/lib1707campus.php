<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- 1707 ofer08oferta
*/
function f1707_TablaDetalleCampus($params, $objDB){
	list($sRes, $sDebug)=f1707_TablaDetalleV2Campus($params, $objDB, false);
	return $sRes;
	}
function f1707_TablaDetalleV2Campus($params, $objDB, $bDebug=false){
	$sDebug='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]='';}
	if (isset($params[107])==0){$params[107]=-1;}
	if (isset($params[108])==0){$params[108]='';}
	if (isset($params[109])==0){$params[109]='';}
	if (isset($params[110])==0){$params[110]='';}
	if (isset($params[111])==0){$params[111]='';}
	if (isset($params[112])==0){$params[112]='';}
	if (isset($params[113])==0){$params[113]='';}
	$params[112]=numeros_validar($params[112]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sadd='';
	$idPeraca=$_SESSION['oai_per_aca'];
	if ($idPeraca==''){$idPeraca=-99;}
	// AND TB.ofer08estadocampus IN (-1, 2, 15, 19)
	$sqladd1=' TB.ofer08idper_aca='.$idPeraca.' AND TB.ofer08estadooferta=1 AND ';
	$sqladd='';
	
	if ((int)$params[103]!=0){$sqladd1=$sqladd1.'TB.ofer08idescuela='.$params[103].' AND ';}
	if ($params[104]!=''){$sqladd=$sqladd.' AND T2.unad40nombre LIKE "%'.$params[104].'%"';}
	switch ($params[105]){
		case 1:
		$sqladd1=$sqladd1.'TB.ofer08estadocampus IN (10,12) AND TB.ofer08copiaidusuario=0 AND ';
		break;
		case 2:
		$sqladd1=$sqladd1.'TB.ofer08tablacidusuario=0 AND ';
		break;
		case 3: //Id de Moodle en cero
		$sqladd1=$sqladd1.'TB.ofer08idcursonav=0 AND ';
		break;
		case 4: //Pendientes de migrar Con ID cero
		$sqladd1=$sqladd1.'TB.ofer08idcursonav=0 AND TB.ofer08estadocampus=1 AND ';
		break;
		case 5: //Pendientes de migrar
		$sqladd1=$sqladd1.'TB.ofer08idcursonav<>0 AND TB.ofer08estadocampus=1 AND ';
		break;
		default:
		break;
		}
	if ($params[106]!=''){$sqladd1=$sqladd1.'TB.ofer08idcurso LIKE "%'.$params[106].'%" AND ';}
	if ($params[107]!=-1){
		//solo cursos donde tenga permisos
		$sIds='-99';
		$sSQL='SELECT ofer11idcurso FROM ofer11actores WHERE ofer11per_aca='.$_SESSION['oai_per_aca'].' AND ofer11idtercero='.$_SESSION['unad_id_tercero'].' AND ofer11idcurso<>-1';
		$tabla=$objDB->ejecutasql($sql);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['ofer11idcurso'];
			}
		$sqladd1=$sqladd1.'TB.ofer08idcurso IN ('.$sIds.') AND ';
		}
	switch ($params[108]){
		case '': 
		break;
		case -1: // No accede
		case 0:
		case 1:
		case 2:
		case 4:
		case 7: //En evaluacion
		case 8: //En Acreditacion.
		case 9: //Ajustes técnicos.
		case 10: // Acreditacion.
		case 11: // En certificacion.
		case 12: // Certificado .
		case 15:
		case 19:
		case 20:
		$sqladd1=$sqladd1.'TB.ofer08estadocampus='.$params[108].' AND ';
		break;
		}
	if ($params[109]!=''){$sqladd1=$sqladd1.'TB.ofer08tipostandard='.$params[109].' AND ';}
	if ($params[110]!=''){$sqladd1=$sqladd1.'TB.ofer08idnav='.$params[110].' AND ';}
	if ($params[111]!=''){$sqladd1=$sqladd1.'TB.ofer08obligaacreditar="'.$params[111].'" AND ';}
	if ($params[112]!=''){$sqladd1=$sqladd1.'TB.ofer08idresponsablepti='.$params[112].' AND ';}
	if ($params[113]!=''){$sqladd1=$sqladd1.'TB.ofer08idcohorte='.$params[113].' AND ';}
	//, Fechacancela, Origen, Fechasolicrestaurar, Migrados, Fecharestaurado, Usariorestaura, Fechaaccede, Usuarioconfirmaacceso, Fechaaprobado, Aprueba
	$sCNombre='T12.ofer15nombre';
	$sCTono='T12.ofer15tono';
	if ($_SESSION['oai_per_aca']<222){
		$sCNombre='T12.ofer15prevnombre AS ofer15nombre';
		$sCTono='T12.ofer15prevtono AS ofer15tono';
		}
	$sTitulos='Escuela, CodCurso, NomCurso, Estado Oferta, Estado Campus, Nav, Fecha Inicio Alistamiento, Num estudiantes,Fecha Solicita Restaurar, Usuario restaura, Fecha del proceso, Datos de migracion';
	$sql='SELECT T5.core12nombre, TB.ofer08idcurso, T2.unad40nombre, T6.ofer07nombre, '.$sCNombre.', TB.ofer08idnav, TB.ofer08diainical, TB.ofer08numestudiantes, TB.ofer08fechasolicrestaurar, T11.unad11razonsocial, TB.ofer08fecharestaurado, TB.ofer08migrados, 
TB.ofer08fechaoferta, TB.ofer08fechacancela, TB.ofer08origen, TB.ofer08idusariorestaura, TB.ofer08fechaaccede, TB.ofer08usuarioconfirmaacceso, TB.ofer08fechaaprobado, TB.ofer08idper_aca, TB.ofer08idescuela, TB.ofer08estadooferta, TB.ofer08idagenda, TB.ofer08estadocampus, TB.ofer08idaprueba, TB.ofer08id, '.$sCTono.', TB.ofer08cead 
FROM ofer08oferta AS TB, unad40curso AS T2, core12escuela AS T5, ofer07estadooferta AS T6, ofer15estadocampus AS T12, unad11terceros AS T11 
WHERE '.$sqladd1.' TB.ofer08idcurso=T2.unad40id AND TB.ofer08idescuela=T5.core12id AND TB.ofer08estadooferta=T6.ofer07id AND TB.ofer08estadocampus=T12.ofer15id AND TB.ofer08idusariorestaura=T11.unad11id '.$sqladd.' 
ORDER BY T5.core12nombre, T2.unad40nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1707" name="consulta_1707" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1707" name="titulos_1707" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sql);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1707 '.$sql.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['ofer08idcurso'].'</b></td>
<td><b>'.$ETI['ofer08cead'].'</b></td>
<td><b>'.$ETI['ofer08estadocampus'].'</b></td>
<td align="right">
'.html_paginador('paginaf1707', $registros, $lineastabla, $pagina, 'paginarf1707()').'
'.html_lpp('lppf1707', $lineastabla, 'paginarf1707()', 200).'
</td>
</tr>';
	$tlinea=1;
	$sEscuela='';
	while($filadet=$objDB->sf($tabladetalle)){
		if ($sEscuela!=$filadet['core12nombre']){
			$sEscuela=$filadet['core12nombre'];
			$res=$res.'<tr><td colspan="9" align="center"><b>'.cadena_notildes($filadet['core12nombre']).'</b></td></tr>';
			}
		$sprefijo='';
		$ssufijo='';
		$sClass='';
		$sLink='';
		$et_ofer08cead='CAMPUS';
		if ($filadet['ofer08cead']!=0){
			$et_ofer08cead=$filadet['ofer08cead'];
			}
		$sColor=$filadet['ofer15tono'];
		if ($filadet['ofer08estadooferta']!=1){
			$sColor='FF0000';
			}
		$sprefijo='<b><font color="#'.$sColor.'">';
		$ssufijo='</font></b>';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1707('."'".$filadet['ofer08id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.$filadet['ofer08idcurso'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['unad40nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$et_ofer08cead.$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer15nombre']).$ssufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1707_HtmlTablaCampus($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$babierta=true;
	list($sDetalle, $sDebug)=f1707_TablaDetalleV2Campus($params, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1707detalle','innerHTML',$sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1707_db_GuardarV3Campus($DATA, $objDB, $bGuardaCopia, $bGuardaTablaCalificaciones, $bDebug=false){
	$icodmodulo=1707;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['ofer08idper_aca']=numeros_validar($DATA['ofer08idper_aca']);
	$DATA['ofer08idcurso']=numeros_validar($DATA['ofer08idcurso']);
	$DATA['ofer08cead']=numeros_validar($DATA['ofer08cead']);
	$DATA['ofer08idescuela']=numeros_validar($DATA['ofer08idescuela']);
	$DATA['ofer08estadooferta']=numeros_validar($DATA['ofer08estadooferta']);
	$DATA['ofer08idagenda']=numeros_validar($DATA['ofer08idagenda']);
	$DATA['ofer08diainical']=numeros_validar($DATA['ofer08diainical']);
	$DATA['ofer08numestudiantes']=numeros_validar($DATA['ofer08numestudiantes']);
	$DATA['ofer08numestaula1']=numeros_validar($DATA['ofer08numestaula1']);
	$DATA['ofer08estadocampus']=numeros_validar($DATA['ofer08estadocampus']);
	$DATA['ofer08idnav']=numeros_validar($DATA['ofer08idnav']);
	$DATA['ofer08idcursonav']=numeros_validar($DATA['ofer08idcursonav']);
	$DATA['ofer08origen']=htmlspecialchars($DATA['ofer08origen']);
	$DATA['ofer08fechasolicrestaurar']=htmlspecialchars($DATA['ofer08fechasolicrestaurar']);
	$DATA['ofer08migrados']=htmlspecialchars($DATA['ofer08migrados']);
	$DATA['ofer08fecharestaurado']=htmlspecialchars($DATA['ofer08fecharestaurado']);
	$DATA['ofer08idusariorestaura']=numeros_validar($DATA['ofer08idusariorestaura']);
	$DATA['ofer08fechaaccede']=htmlspecialchars($DATA['ofer08fechaaccede']);
	$DATA['ofer08usuarioconfirmaacceso']=numeros_validar($DATA['ofer08usuarioconfirmaacceso']);
	$DATA['ofer08idevalacredita']=numeros_validar($DATA['ofer08idevalacredita']);
	$DATA['ofer08puntajeacredita']=numeros_validar($DATA['ofer08puntajeacredita'],true);
	$DATA['ofer08restaurado']=htmlspecialchars($DATA['ofer08restaurado']);
	$DATA['ofer08metodomatricula']=numeros_validar($DATA['ofer08metodomatricula']);
	$DATA['ofer08copiaruta']=htmlspecialchars(trim($DATA['ofer08copiaruta']));
	$DATA['ofer08tablacruta']=htmlspecialchars(trim($DATA['ofer08tablacruta']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['ofer08idescuela']==''){$DATA['ofer08idescuela']=0;}
	if ($DATA['ofer08estadooferta']==''){$DATA['ofer08estadooferta']=0;}
	if ($DATA['ofer08idagenda']==''){$DATA['ofer08idagenda']=0;}
	if ($DATA['ofer08diainical']==''){$DATA['ofer08diainical']=0;}
	if ($DATA['ofer08numestudiantes']==''){$DATA['ofer08numestudiantes']=50;}
	if ($DATA['ofer08numestaula1']==''){$DATA['ofer08numestaula1']=50;}
	if ($DATA['ofer08estadocampus']==''){$DATA['ofer08estadocampus']=0;}
	if ($DATA['ofer08idnav']==''){$DATA['ofer08idnav']=0;}
	if ($DATA['ofer08idcursonav']==''){$DATA['ofer08idcursonav']=0;}
	if ($DATA['ofer08idevalacredita']==''){$DATA['ofer08idevalacredita']=0;}
	if ($DATA['ofer08puntajeacredita']==''){$DATA['ofer08puntajeacredita']=0;}
	// -- Seccion para validar los posibles causales de error.
	if (!fecha_esvalida($DATA['ofer08fechaacredita'])){
		$DATA['ofer08fechaacredita']='00/00/0000';
		}
	if ($DATA['ofer08idusariorestaura']==''){$DATA['ofer08idusariorestaura']=0;}
	if ($DATA['ofer08usuarioconfirmaacceso']==''){$DATA['ofer08usuarioconfirmaacceso']=0;}
	//if ($DATA['ofer08idaprueba']==0){$sError=$ERR['ofer08idaprueba'];}
	if (!fecha_esvalida($DATA['ofer08fechaaprobado'])){
		$DATA['ofer08fechaaprobado']='00/00/0000';
		//$sError=$ERR['ofer08fechaaprobado'];
		}
	//if ($DATA['ofer08usuarioconfirmaacceso']==0){$sError=$ERR['ofer08usuarioconfirmaacceso'];}
	if (!fecha_esvalida($DATA['ofer08fechaaccede'])){
		$DATA['ofer08fechaaccede']='00/00/0000';
		//$sError=$ERR['ofer08fechaaccede'];
		}
	//if ($DATA['ofer08idusariorestaura']==0){$sError=$ERR['ofer08idusariorestaura'];}
	if (!fecha_esvalida($DATA['ofer08fecharestaurado'])){
		$DATA['ofer08fecharestaurado']='00/00/0000';
		//$sError=$ERR['ofer08fecharestaurado'];
		}
	//if ($DATA['ofer08migrados']==''){$sError=$ERR['ofer08migrados'];}
	if (!fecha_esvalida($DATA['ofer08fechasolicrestaurar'])){
		$DATA['ofer08fechasolicrestaurar']='00/00/0000';
		//$sError=$ERR['ofer08fechasolicrestaurar'];
		}
	//if ($DATA['ofer08origen']==''){$sError=$ERR['ofer08origen'];}
	//if ($DATA['ofer08idnav']==''){$sError=$ERR['ofer08idnav'];}
	if ($DATA['ofer08estadocampus']==''){$sError=$ERR['ofer08estadocampus'];}
	if (!fecha_esvalida($DATA['ofer08fechacancela'])){
		$DATA['ofer08fechacancela']='00/00/0000';
		//$sError=$ERR['ofer08fechacancela'];
		}
	if (!fecha_esvalida($DATA['ofer08fechaoferta'])){
		$DATA['ofer08fechaoferta']='00/00/0000';
		//$sError=$ERR['ofer08fechaoferta'];
		}
	if ($DATA['ofer08numestudiantes']==''){$sError=$ERR['ofer08numestudiantes'];}
	//if ($DATA['ofer08diainical']==''){$sError=$ERR['ofer08diainical'];}
	//if ($DATA['ofer08idagenda']==''){$sError=$ERR['ofer08idagenda'];}
	if ($DATA['ofer08estadooferta']==''){$sError=$ERR['ofer08estadooferta'];}
	if ($DATA['ofer08idescuela']==''){$sError=$ERR['ofer08idescuela'];}
	//if ($DATA['ofer08id']==''){$sError=$ERR['ofer08id'];}//CONSECUTIVO
	if ($DATA['ofer08cead']==''){$sError=$ERR['ofer08cead'];}
	if ($DATA['ofer08idcurso']==''){$sError=$ERR['ofer08idcurso'];}
	if ($DATA['ofer08idper_aca']==''){$sError=$ERR['ofer08idper_aca'];}
	// -- Se verifican los valores de campos de otras tablas.
	//if ($sError==''){$sError=tabla_terceros_existe($DATA['ofer08idacredita_td'], $DATA['ofer08idacredita_doc'], $objDB, 'El tercero Acredita ');}
	//if ($sError==''){$sError=tabla_terceros_existe($DATA['ofer08idaprueba_td'], $DATA['ofer08idaprueba_doc'], $objDB, 'El tercero Aprueba ');}
	//if ($sError==''){$sError=tabla_terceros_existe($DATA['ofer08usuarioconfirmaacceso_td'], $DATA['ofer08usuarioconfirmaacceso_doc'], $objDB, 'El tercero Usuarioconfirmaacceso ');}
	//if ($sError==''){$sError=tabla_terceros_existe($DATA['ofer08idusariorestaura_td'], $DATA['ofer08idusariorestaura_doc'], $objDB, 'El tercero Usariorestaura ');}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sError=$ERR['no_insertar'];
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			}
		}
	$idAgendaAnterior=0;
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			}else{
			$scampo[1]='ofer08diainical';
			$scampo[2]='ofer08idnav';
			$scampo[3]='ofer08numestaula1';
			$scampo[4]='ofer08idcursonav';
			$scampo[5]='ofer08idagenda';
			$scampo[6]='ofer08tipostandard';
			$scampo[7]='ofer08metodomatricula';
			$scampo[8]='ofer08copiaruta';
			$scampo[9]='ofer08idresponsablepti';
			$scampo[10]='ofer08incluyelaboratorio';
			$scampo[11]='ofer08puntajelaboratorio';
			$scampo[12]='ofer08incluyesalida';
			$scampo[13]='ofer08puntajesalida';
			$sdato[1]=$DATA['ofer08diainical'];
			$sdato[2]=$DATA['ofer08idnav'];
			$sdato[3]=$DATA['ofer08numestaula1'];
			$sdato[4]=$DATA['ofer08idcursonav'];
			$sdato[5]=$DATA['ofer08idagenda'];
			$sdato[6]=$DATA['ofer08tipostandard'];
			$sdato[7]=$DATA['ofer08metodomatricula'];
			$sdato[8]=$DATA['ofer08copiaruta'];
			$sdato[9]=$DATA['ofer08idresponsablepti'];
			$sdato[10]=$DATA['ofer08incluyelaboratorio'];
			$sdato[11]=$DATA['ofer08puntajelaboratorio'];
			$sdato[12]=$DATA['ofer08incluyesalida'];
			$sdato[13]=$DATA['ofer08puntajesalida'];
			$numcmod=13;
			if ($bGuardaCopia){
				$DATA['ofer08copiaidusuario']=$_SESSION['unad_id_tercero'];
				if (!fecha_esvalida($DATA['ofer08copiafecha'])){
					$DATA['ofer08copiafecha']=fecha_hoy();
					}
				$numcmod++;
				$scampo[$numcmod]='ofer08copiaidusuario';
				$sdato[$numcmod]=$DATA['ofer08copiaidusuario'];
				$numcmod++;
				$scampo[$numcmod]='ofer08copiafecha';
				$sdato[$numcmod]=$DATA['ofer08copiafecha'];
				}
			if ($bGuardaTablaCalificaciones){
				$DATA['ofer08tablacidusuario']=$_SESSION['unad_id_tercero'];
				if (!fecha_esvalida($DATA['ofer08tablacfecha'])){
					$DATA['ofer08tablacfecha']=fecha_hoy();
					}
				$numcmod++;
				$scampo[$numcmod]='ofer08tablacruta';
				$sdato[$numcmod]=$DATA['ofer08tablacruta'];
				$numcmod++;
				$scampo[$numcmod]='ofer08tablacidusuario';
				$sdato[$numcmod]=$DATA['ofer08tablacidusuario'];
				$numcmod++;
				$scampo[$numcmod]='ofer08tablacfecha';
				$sdato[$numcmod]=$DATA['ofer08tablacfecha'];
				}
			$bModAgenda=false;
			$NavDB=0;
			$EstDB=0;
			//$sWhere='ofer08idper_aca='.$DATA['ofer08idper_aca'].' AND ofer08idcurso='.$DATA['ofer08idcurso'].' AND ofer08cead='.$DATA['ofer08cead'].'';
			$sWhere='ofer08id='.$DATA['ofer08id'].'';
			$sql='SELECT * FROM ofer08oferta WHERE '.$sWhere;
			$sdatos='';
			$bCambiaProceso=false;
			$sProcesoAnterior='';
			$result=$objDB->ejecutasql($sql);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($DATA['ofer08idagenda']!=$filabase['ofer08idagenda']){$bModAgenda=true;};
				if ($DATA['ofer08diainical']!=$filabase['ofer08diainical']){$bModAgenda=true;};
				if ($DATA['ofer08idnav']!=$filabase['ofer08idnav']){
					$DATA['ofer08idcursonav']=OAI_TraerIdCursoMoodle($DATA['ofer08idper_aca'], $DATA['ofer08idcurso'], 1, $DATA['ofer08id'], $objDB);
					$sdato[4]=$DATA['ofer08idcursonav'];
					}
				$NavDB=$filabase['ofer08idnav'];
				$EstDB=$filabase['ofer08numestaula1'];
				$bAgendaBloqueada=false;
				if ($filabase['ofer08estadocampus']==10){$bAgendaBloqueada=true;}
				if ($filabase['ofer08estadocampus']==12){$bAgendaBloqueada=true;}
				if ($bAgendaBloqueada){
					if ($bModAgenda){
						//Si ya esta acreditado no dejar modificar la agenda
						$DATA['ofer08diainical']=$filabase['ofer08diainical'];
						$sdato[1]=$DATA['ofer08diainical'];
						$DATA['ofer08idagenda']=$filabase['ofer08idagenda'];
						$sdato[5]=$DATA['ofer08idagenda'];
						$sError='El curso ya fue acreditado no se permite moficar ni la agenda ni el d&iacute;a inicial.';
						}
					$bModAgenda=false;
					}
				$bsepara=false;
				$sProcesoAnterior=$filabase['ofer08obligaacreditar'];
				if ($filabase['ofer08obligaacreditar']!=$DATA['ofer08obligaacreditar']){$bCambiaProceso=true;}
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			//$sError='Datos a guardar '.$numcmod.' - {'.$sdatos.'}';
			if ($bGuardaTablaCalificaciones){
				//$sDebug=$sDebug.fecha_microtiempo().' '.$sdatos;
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sql='UPDATE ofer08oferta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sql='UPDATE ofer08oferta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' ..<!-- '.$sql.' -->';
				if ($idaccion==2){$DATA['ofer08id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				//ACTUALIZAR LA AGENDA DEL CURSO.
				if ($bModAgenda){
					//Si cambia de agenda hay que forzar el cambio en la tabla 17.
					$sError=OAI_ArmarAgenda($DATA['ofer08idper_aca'], $DATA['ofer08idcurso'], 1, false, $objDB);
					}else{
					//Actualizar la 17 por si cambio la carga de estudiantes.
					if ($EstDB!=$DATA['ofer08numestaula1']){
						$DATA['ofer08numestudiantes']=OAI_TotalEstudiantes_Actualizar($DATA['ofer08id'], $objDB);
						}
					//Si cambia el nav actualizar la 48 y la 17
					if ($NavDB!=$DATA['ofer08idnav']){
						$sql='UPDATE unad48cursoaula SET unad48idnav='.$DATA['ofer08idnav'].' WHERE unad48per_aca='.$DATA['ofer08idper_aca'].' AND unad48idcurso='.$DATA['ofer08idcurso'].'';
						$result=$objDB->ejecutasql($sql);
						$sql='UPDATE ofer17cargaxnav SET ofer17nav='.$DATA['ofer08idnav'].' WHERE ofer17per_aca='.$DATA['ofer08idper_aca'].' AND ofer17curso='.$DATA['ofer08idcurso'].'';
						$result=$objDB->ejecutasql($sql);
						}
					}
				//Actualizar la tabla cursos...
				$sql='UPDATE unad40curso SET unad40idagenda='.$DATA['ofer08idagenda'].', unad40diainical='.$DATA['ofer08diainical'].', unad40numestudiantes='.$DATA['ofer08numestudiantes'].', unad40numestaula1='.$DATA['ofer08numestaula1'].', unad40idnav='.$DATA['ofer08idnav'].', unad40tipostandard='.$DATA['ofer08tipostandard'].' WHERE unad40id='.$DATA['ofer08idcurso'];
				$result=$objDB->ejecutasql($sql);
				if ($bAudita[$idaccion]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['ofer08id'], $sdetalle, $objDB);}
					if ($bCambiaProceso){
						$aProceso['S']='Acreditación';
						$aProceso['N']='Certificación';
						$aProceso['E']='Procesos especiales';
						if (isset($aProceso[$sProcesoAnterior])==0){
							$sProcesoAnterior='{'.$sProcesoAnterior.'}';
							}else{
							$sProcesoAnterior=$aProceso[$sProcesoAnterior];
							}
						if (isset($aProceso[$DATA['ofer08obligaacreditar']])==0){
							$sNuevoProceso='{'.$DATA['ofer08obligaacreditar'].'}';
							}else{
							$sNuevoProceso=$aProceso[$DATA['ofer08obligaacreditar']];
							}
						$sNota='Cambia el proceso de '.$sProcesoAnterior.' a '.$sNuevoProceso;
						f1730_CambiaEstado($DATA['ofer08id'], $DATA['ofer08estadocampus'], $DATA['ofer08estadocampus'], $sNota, $objDB);
						}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=2;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}


function f1707_TablaDetalleV2Historico($params, $objDB, $bDebug=false){
	$sDebug='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]='';}
	if (isset($params[107])==0){$params[107]=-1;}
	if (isset($params[108])==0){$params[108]='';}
	if (isset($params[109])==0){$params[109]='';}
	if (isset($params[110])==0){$params[110]='';}
	if (isset($params[111])==0){$params[111]='';}
	if (isset($params[112])==0){$params[112]='';}
	$params[112]=numeros_validar($params[112]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sadd='';
	$idPeraca=$params[104];
	$idCurso=$params[103];
	//if ($idPeraca==''){$idPeraca=-99;}
	// AND TB.ofer08estadocampus IN (-1, 2, 15, 19)
	$sqladd1='TB.ofer08idcurso='.$idCurso.' AND TB.ofer08idper_aca<'.$idPeraca.' AND ';
	$sqladd='';
	
	$sCNombre='T12.ofer15nombre';
	$sCTono='T12.ofer15tono';
	$sTitulos='Escuela, CodCurso, NomCurso, Estado Oferta, Estado Campus, Nav, Fecha Inicio Alistamiento, Num estudiantes';
	$sql='SELECT TB.ofer08idper_aca, T6.ofer07nombre, '.$sCNombre.', T1.exte02nombre, TB.ofer08idper_aca, TB.ofer08estadooferta, TB.ofer08estadocampus, TB.ofer08id, '.$sCTono.' 
FROM ofer08oferta AS TB, exte02per_aca AS T1, ofer07estadooferta AS T6, ofer15estadocampus AS T12 
WHERE '.$sqladd1.' TB.ofer08idper_aca=T1.exte02id AND TB.ofer08estadooferta=T6.ofer07id AND TB.ofer08estadocampus=T12.ofer15id '.$sqladd.' 
ORDER BY TB.ofer08idper_aca DESC';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='';
	$tabladetalle=$objDB->ejecutasql($sql);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1707 '.$sql.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['ofer08idper_aca'].'</b></td>
<td><b>'.$ETI['ofer08estadooferta'].'</b></td>
<td><b>'.$ETI['ofer08estadocampus'].'</b></td>
<td align="right"></td>
</tr>';
	$tlinea=1;
	$sEscuela='';
	while($filadet=$objDB->sf($tabladetalle)){
		$sprefijo='';
		$ssufijo='';
		$sClass='';
		$sLink='';
		$sColor=$filadet['ofer15tono'];
		if ($filadet['ofer08estadooferta']!=1){
			$sColor='FF0000';
			}
		$sprefijo='<font color="#'.$sColor.'">';
		$ssufijo='</font>';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf1707('."'".$filadet['ofer08id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.cadena_notildes($filadet['exte02nombre']).$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer07nombre']).$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer15nombre']).$ssufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1707_HtmlTablaHistorico($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$babierta=true;
	list($sDetalle, $sDebug)=f1707_TablaDetalleV2Historico($params, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1707historico','innerHTML',$sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>