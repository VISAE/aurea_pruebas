<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.6d martes, 15 de enero de 2019
*/
function f1707_IniciarOferta($peraca, $idCurso, $idCead, $iMetodoMatricula, $incluyelaboratorio, $puntajelaboratorio, $ofer08incluyesalida, $puntajesalida, $objDB, $bDebug=false){
	list($INFO, $sError, $sDebug)=f1707_IniciarOfertaV2($peraca, $idCurso, $idCead, 'S', $iMetodoMatricula, $incluyelaboratorio, $puntajelaboratorio, $ofer08incluyesalida, $puntajesalida, 0, 0, 5, 5, $objDB, $bDebug);
	return array($INFO, $sError, $sDebug);
	}
function f1707_IniciarOfertaV2($peraca, $idCurso, $idCead, $sProceso, $iMetodoMatricula, $incluyelaboratorio, $puntajelaboratorio, $ofer08incluyesalida, $puntajesalida, $idOrigen, $iFormaGrupo, $iMinGrupo, $iMaxGrupo, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	$INFO=array();
	$sDebug='';
	$sError='';
	$sSQL='SELECT ofer08idper_aca FROM ofer08oferta WHERE ofer08idcurso='.$idCurso.' AND ofer08idper_aca='.$peraca.' AND ofer08cead='.$idCead.'';
	$result=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($result)!=0){
		$sError=$ERR['existe'];
		}
	if ($sError==''){
		$INFO['ofer08idescuela']=0;
		$INFO['ofer08estadooferta']=1;
		$INFO['ofer08idprograma']=0;
		$INFO['ofer08nomprograma']='';
		$INFO['ofer08idagenda']=0;
		$INFO['ofer08diainical']=0;
		$INFO['ofer08numestudiantes']=50;
		$INFO['ofer08idnav']=0;
		$INFO['ofer08numestaula1']=50;
		$INFO['ofer08tipostandard']=0;
		//Preparar el Id, Si no lo hay se quita la comprobaci�n.
		$INFO['ofer08id']=tabla_consecutivo('ofer08oferta','ofer08id', '', $objDB);
		if ($INFO['ofer08id']==-1){$sError=$objDB->serror;}
		//Cargar la informacion del curso...
		$DATA2=OAI_InfoCurso($idCurso, $objDB);
		if ($DATA2!=NULL){
			$INFO['ofer08idagenda']=$DATA2['unad40idagenda'];
			$INFO['ofer08diainical']=$DATA2['unad40diainical'];
			$INFO['ofer08numestudiantes']=$DATA2['unad40numestudiantes'];
			$INFO['ofer08idnav']=$DATA2['unad40idnav'];
			$INFO['ofer08numestaula1']=$DATA2['unad40numestaula1'];
			$INFO['ofer08tipostandard']=$DATA2['unad40tipostandard'];
			$INFO['ofer08idprograma']=$DATA2['unad40idprograma'];
			$INFO['ofer08idescuela']=$DATA2['unad40idescuela'];
			}
		//ya esta la carga del grupo ahora cargar del peraca base.
		$iEstCiclo1=OAI_EstudiantesCicloBase($peraca, $idCurso, 1, $objDB);
		if ($iEstCiclo1>0){
			$INFO['ofer08numestudiantes']=0;
			$INFO['ofer08numestaula1']=$iEstCiclo1;
			}
		if ($INFO['ofer08idprograma']!=0){
			$sSQL='SELECT exte03nombre FROM exte03programa WHERE exte03id='.$INFO['ofer08idprograma'];
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$INFO['ofer08nomprograma']=$fila['exte03nombre'];
				}
			}
		}
	if ($sError==''){
		if ($INFO['ofer08numestaula1']==0){$INFO['ofer08numestaula1']=50;}
		if ($INFO['ofer08numestudiantes']==0){$INFO['ofer08numestudiantes']=$INFO['ofer08numestaula1'];}
		$scampos='ofer08idper_aca, ofer08idcurso, ofer08cead, ofer08id, ofer08idescuela, 
ofer08estadooferta, ofer08idagenda, ofer08diainical, ofer08numestudiantes, ofer08numestaula1, 
ofer08fechaoferta, ofer08fechacancela, ofer08estadocampus, ofer08idnav, ofer08origen, 
ofer08fechasolicrestaurar, ofer08migrados, ofer08fecharestaurado, ofer08idusariorestaura, ofer08fechaaccede, 
ofer08usuarioconfirmaacceso, ofer08fechaaprobado, ofer08idaprueba, ofer08fechaacredita, ofer08idacredita, 
ofer08idevalacredita, ofer08puntajeacredita, ofer08restaurado, ofer08idcursoncontents, ofer08idcursonav, 
ofer08tipostandard, ofer08obligaacreditar, ofer08notaacredita, ofer08metodomatricula, ofer08incluyelaboratorio, 
ofer08puntajelaboratorio, ofer08incluyesalida, ofer08puntajesalida, ofer08tablacidusuario, ofer08tablacfecha, 
ofer08tablacruta, ofer08idprograma, ofer08nomprograma, 
ofer08idorigenoferta, ofer08grupoidforma, ofer08grupominest, ofer08grupomaxest';
		$svalores=''.$peraca.', '.$idCurso.', '.$idCead.', '.$INFO['ofer08id'].', '.$INFO['ofer08idescuela'].', 
'.$INFO['ofer08estadooferta'].', '.$INFO['ofer08idagenda'].', '.$INFO['ofer08diainical'].', '.$INFO['ofer08numestudiantes'].', '.$INFO['ofer08numestaula1'].', 
"00/00/0000", "00/00/0000", 0, '.$INFO['ofer08idnav'].', "", 
"00/00/0000", "", "00/00/0000", 0, "00/00/0000", 
0, "00/00/0000", 0, "00/00/0000", 0, 
0, 0, "N", 0, 0, 
'.$INFO['ofer08tipostandard'].', "'.$sProceso.'", "", '.$iMetodoMatricula.', "'.$incluyelaboratorio.'", 
'.$puntajelaboratorio.', "'.$ofer08incluyesalida.'", '.$puntajesalida.', 0, "00/00/0000",
"", '.$INFO['ofer08idprograma'].', "'.$INFO['ofer08nomprograma'].'", 
'.$idOrigen.', '.$iFormaGrupo.', '.$iMinGrupo.', '.$iMaxGrupo.'';
		if ($APP->utf8==1){
			$sSQL='INSERT INTO ofer08oferta ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
			$sdetalle=$scampos.'['.utf8_encode($svalores).']';
			}else{
			$sSQL='INSERT INTO ofer08oferta ('.$scampos.') VALUES ('.$svalores.');';
			$sdetalle=$scampos.'['.$svalores.']';
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 1707 '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			
			}else{
			seg_auditar(1707, $_SESSION['unad_id_tercero'], 2, $INFO['ofer08id'], $sdetalle, $objDB);
			//Ver si el curso tiene aulas adicionales e insertarlas.
			$sErrorAulas=AulasDuplicar($peraca, $idCurso, $objDB);
			}
		}
	return array($INFO, $sError, $sDebug);
	}
function f1707_TablaDetalleV2Oferta($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]='';}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]=-1;}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	if (isset($aParametros[109])==0){$aParametros[109]='';}
	$idTercero=numeros_validar($aParametros[100]);
	if ($idTercero==''){
		$idTercero=$_SESSION['unad_id_tercero'];
		}
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sadd='';
	$sSQLadd1='TB.ofer08idper_aca='.$_SESSION['oai_per_aca'].' AND ';
	$sEtiqueta='';
	$sSQLadd='';
	if ((int)$aParametros[103]!=0){$sSQLadd1=$sSQLadd1.'TB.ofer08idescuela='.$aParametros[103].' AND ';}
	if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND T2.unad40nombre LIKE "%'.$aParametros[104].'%"';}
	switch ($aParametros[105]){
		case '': 
		break;
		case 0:
		case 1:
		case 9:
		$sSQLadd1=$sSQLadd1.'TB.ofer08estadooferta='.$aParametros[105].' AND ';
		break;
		}
	if ($aParametros[106]!=''){$sSQLadd1=$sSQLadd1.'TB.ofer08idcurso LIKE "%'.$aParametros[106].'%" AND ';}
	if ($aParametros[107]!=-1){
		//solo cursos donde tenga permisos
		}
	switch ($aParametros[108]){
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
		case 10:
		case 11: // En Certificacion
		case 12: // Certificado
		case 14: // Vencido.
		case 15:
		case 19:
		case 20:
		$sSQLadd1=$sSQLadd1.'TB.ofer08estadocampus='.$aParametros[108].' AND ';
		break;
		}
	if ($aParametros[109]!=''){$sSQLadd1=$sSQLadd1.'TB.ofer08tipostandard='.$aParametros[109].' AND ';}
	$sTitulos='Escuela, CodCurso, NomCurso, Estado Oferta, Proceso, Nav, Fecha Inicio Alistamiento, Agenda, Numestudiantes, Dia Inicial';
	//, Fechacancela, Origen, Fechasolicrestaurar, Migrados, Fecharestaurado, Usariorestaura, Fechaaccede, Usuarioconfirmaacceso, Fechaaprobado, Aprueba
	$sCNombre='T12.ofer15nombre';
	$sCTono='T12.ofer15tono';
	if ($_SESSION['oai_per_aca']<222){
		$sCNombre='T12.ofer15prevnombre AS ofer15nombre';
		$sCTono='T12.ofer15prevtono AS ofer15tono';
		}
	$sSQL='SELECT T2.unad40nombre, TB.ofer08cead, T5.exte01nombre, TB.ofer08diainical, TB.ofer08numestudiantes, TB.ofer08fechaoferta, TB.ofer08fechacancela, '.$sCNombre.', TB.ofer08origen, TB.ofer08fechasolicrestaurar, TB.ofer08migrados, TB.ofer08fecharestaurado, TB.ofer08idusariorestaura, TB.ofer08fechaaccede, TB.ofer08usuarioconfirmaacceso, TB.ofer08fechaaprobado, TB.ofer08idper_aca, TB.ofer08idcurso, TB.ofer08idescuela, TB.ofer08estadooferta, TB.ofer08idagenda, TB.ofer08estadocampus, TB.ofer08idnav, TB.ofer08idaprueba, TB.ofer08id, '.$sCTono.', TB.ofer08idcohorte, TB.ofer08obligaacreditar 
FROM ofer08oferta AS TB, unad40curso AS T2, exte01escuela AS T5, ofer15estadocampus AS T12 
WHERE '.$sSQLadd1.' TB.ofer08idcurso=T2.unad40id AND TB.ofer08idescuela=T5.exte01id AND TB.ofer08estadocampus=T12.ofer15id '.$sSQLadd.' 
ORDER BY T2.unad40nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1707" name="consulta_1707" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1707" name="titulos_1707" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1707: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1707" name="paginaf1707" type="hidden" value="'.$pagina.'"/><input id="lppf1707" name="lppf1707" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	//<td><b>'.$ETI['ofer08idescuela'].'</b></td>
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['ofer08idcurso'].'</b></td>
<td><b>'.$ETI['ofer08estadocampus'].'</b></td>
<td><b>'.$ETI['msg_inicioalista'].'</b></td>
<td><b>'.$ETI['ofer08idcohorte'].'</b></td>
<td align="right">
'.html_paginador('paginaf1707', $registros, $lineastabla, $pagina, 'paginarf1707()').'
'.html_lpp('lppf1707', $lineastabla, 'paginarf1707()', 1000).'
</td>
</tr>';
	$tlinea=1;
	$sEscuela='';
	while($filadet=$objDB->sf($tabladetalle)){
		if ($sEscuela!=$filadet['exte01nombre']){
			$sEscuela=$filadet['exte01nombre'];
			$res=$res.'<tr><td colspan="5" align="center"><b>'.cadena_notildes($filadet['exte01nombre']).'</b></td></tr>';
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
		$et_ofer08fechaoferta='';
		if ($filadet['ofer08fechaoferta']!='00/00/0000'){$et_ofer08fechaoferta=$filadet['ofer08fechaoferta'];}
		$et_ofer08idcohorte='{'.$ETI['msg_ninguna'].'}';
		if ($filadet['ofer08idcohorte']!=0){
			$et_ofer08idcohorte=f1707_NombreCohorte($filadet['ofer08idcohorte'], $filadet['ofer08obligaacreditar'], $filadet['ofer08estadocampus'], $objDB, false);
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1707('.$filadet['ofer08id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.$filadet['ofer08idcurso'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['unad40nombre']).$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer15nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$et_ofer08fechaoferta.$ssufijo.'</td>
<td>'.$sprefijo.$et_ofer08idcohorte.$ssufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1707_HtmlTablaOferta($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1707_TablaDetalleV2Oferta($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1707detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1707_db_GuardarV2Oferta($DATA, $objDB, $bDebug=false){
	$iCodModulo=1707;
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
	/*
	if (isset($DATA['ofer08idper_aca'])==0){$DATA['ofer08idper_aca']='';}
	if (isset($DATA['ofer08idcurso'])==0){$DATA['ofer08idcurso']='';}
	if (isset($DATA['ofer08cead'])==0){$DATA['ofer08cead']='';}
	if (isset($DATA['ofer08id'])==0){$DATA['ofer08id']='';}
	if (isset($DATA['ofer08idescuela'])==0){$DATA['ofer08idescuela']='';}
	if (isset($DATA['ofer08estadooferta'])==0){$DATA['ofer08estadooferta']='';}
	if (isset($DATA['ofer08idagenda'])==0){$DATA['ofer08idagenda']='';}
	if (isset($DATA['ofer08diainical'])==0){$DATA['ofer08diainical']='';}
	if (isset($DATA['ofer08numestudiantes'])==0){$DATA['ofer08numestudiantes']='';}
	if (isset($DATA['ofer08numestaula1'])==0){$DATA['ofer08numestaula1']='';}
	if (isset($DATA['ofer08fechaoferta'])==0){$DATA['ofer08fechaoferta']='';}
	if (isset($DATA['ofer08fechacancela'])==0){$DATA['ofer08fechacancela']='';}
	if (isset($DATA['ofer08estadocampus'])==0){$DATA['ofer08estadocampus']='';}
	if (isset($DATA['ofer08idnav'])==0){$DATA['ofer08idnav']='';}
	if (isset($DATA['ofer08origen'])==0){$DATA['ofer08origen']='';}
	if (isset($DATA['ofer08fechasolicrestaurar'])==0){$DATA['ofer08fechasolicrestaurar']='';}
	if (isset($DATA['ofer08migrados'])==0){$DATA['ofer08migrados']='';}
	if (isset($DATA['ofer08fecharestaurado'])==0){$DATA['ofer08fecharestaurado']='';}
	if (isset($DATA['ofer08idusariorestaura'])==0){$DATA['ofer08idusariorestaura']='';}
	if (isset($DATA['ofer08fechaaccede'])==0){$DATA['ofer08fechaaccede']='';}
	if (isset($DATA['ofer08usuarioconfirmaacceso'])==0){$DATA['ofer08usuarioconfirmaacceso']='';}
	if (isset($DATA['ofer08fechaaprobado'])==0){$DATA['ofer08fechaaprobado']='';}
	if (isset($DATA['ofer08idaprueba'])==0){$DATA['ofer08idaprueba']='';}
	if (isset($DATA['ofer08fechaacredita'])==0){$DATA['ofer08fechaacredita']='';}
	if (isset($DATA['ofer08idacredita'])==0){$DATA['ofer08idacredita']='';}
	if (isset($DATA['ofer08idevalacredita'])==0){$DATA['ofer08idevalacredita']='';}
	if (isset($DATA['ofer08puntajeacredita'])==0){$DATA['ofer08puntajeacredita']='';}
	if (isset($DATA['ofer08restaurado'])==0){$DATA['ofer08restaurado']='';}
	if (isset($DATA['ofer08idcursoncontents'])==0){$DATA['ofer08idcursoncontents']='';}
	if (isset($DATA['ofer08idcursonav'])==0){$DATA['ofer08idcursonav']='';}
	if (isset($DATA['ofer08tipostandard'])==0){$DATA['ofer08tipostandard']='';}
	if (isset($DATA['ofer08obligaacreditar'])==0){$DATA['ofer08obligaacreditar']='';}
	if (isset($DATA['ofer08notaacredita'])==0){$DATA['ofer08notaacredita']='';}
	if (isset($DATA['ofer08idnavalista'])==0){$DATA['ofer08idnavalista']='';}
	if (isset($DATA['ofer08fechaestadocampus'])==0){$DATA['ofer08fechaestadocampus']='';}
	if (isset($DATA['ofer08motivocancela'])==0){$DATA['ofer08motivocancela']='';}
	if (isset($DATA['ofer08procesado'])==0){$DATA['ofer08procesado']='';}
	if (isset($DATA['ofer08metodomatricula'])==0){$DATA['ofer08metodomatricula']='';}
	if (isset($DATA['ofer08copiaidusuario'])==0){$DATA['ofer08copiaidusuario']='';}
	if (isset($DATA['ofer08copiafecha'])==0){$DATA['ofer08copiafecha']='';}
	if (isset($DATA['ofer08copiaruta'])==0){$DATA['ofer08copiaruta']='';}
	if (isset($DATA['ofer08idresponsablepti'])==0){$DATA['ofer08idresponsablepti']='';}
	if (isset($DATA['ofer08incluyelaboratorio'])==0){$DATA['ofer08incluyelaboratorio']='';}
	if (isset($DATA['ofer08puntajelaboratorio'])==0){$DATA['ofer08puntajelaboratorio']='';}
	if (isset($DATA['ofer08incluyesalida'])==0){$DATA['ofer08incluyesalida']='';}
	if (isset($DATA['ofer08puntajesalida'])==0){$DATA['ofer08puntajesalida']='';}
	if (isset($DATA['ofer08idcohorte'])==0){$DATA['ofer08idcohorte']='';}
	if (isset($DATA['ofer08idioma'])==0){$DATA['ofer08idioma']='';}
	if (isset($DATA['ofer08razonvence'])==0){$DATA['ofer08razonvence']='';}
	if (isset($DATA['ofer08idestrategiaaprende'])==0){$DATA['ofer08idestrategiaaprende']='';}
	*/
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
	$DATA['ofer08origen']=htmlspecialchars(trim($DATA['ofer08origen']));
	$DATA['ofer08fechasolicrestaurar']=htmlspecialchars($DATA['ofer08fechasolicrestaurar']);
	$DATA['ofer08migrados']=htmlspecialchars(trim($DATA['ofer08migrados']));
	$DATA['ofer08fecharestaurado']=htmlspecialchars($DATA['ofer08fecharestaurado']);
	$DATA['ofer08idusariorestaura']=numeros_validar($DATA['ofer08idusariorestaura']);
	$DATA['ofer08fechaaccede']=htmlspecialchars($DATA['ofer08fechaaccede']);
	$DATA['ofer08usuarioconfirmaacceso']=numeros_validar($DATA['ofer08usuarioconfirmaacceso']);
	$DATA['ofer08idevalacredita']=numeros_validar($DATA['ofer08idevalacredita']);
	$DATA['ofer08puntajeacredita']=numeros_validar($DATA['ofer08puntajeacredita'],true);
	$DATA['ofer08restaurado']=htmlspecialchars(trim($DATA['ofer08restaurado']));
	$DATA['ofer08metodomatricula']=numeros_validar($DATA['ofer08metodomatricula']);
	$DATA['ofer08incluyelaboratorio']=htmlspecialchars(trim($DATA['ofer08incluyelaboratorio']));
	$DATA['ofer08puntajelaboratorio']=numeros_validar($DATA['ofer08puntajelaboratorio']);
	$DATA['ofer08incluyesalida']=htmlspecialchars(trim($DATA['ofer08incluyesalida']));
	$DATA['ofer08puntajesalida']=numeros_validar($DATA['ofer08puntajesalida']);
	$DATA['ofer08idcohorte']=numeros_validar($DATA['ofer08idcohorte']);
	//$DATA['ofer08idioma']=htmlspecialchars(trim($DATA['ofer08idioma']));
	$DATA['ofer08razonvence']=numeros_validar($DATA['ofer08razonvence']);
	$DATA['ofer08idestrategiaaprende']=numeros_validar($DATA['ofer08idestrategiaaprende']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente n�meros}.
	//if ($DATA['ofer08idescuela']==''){$DATA['ofer08idescuela']=0;}
	if ($DATA['ofer08estadooferta']==''){$DATA['ofer08estadooferta']=0;}
	if ($DATA['ofer08idagenda']==''){$DATA['ofer08idagenda']=0;}
	if ($DATA['ofer08diainical']==''){$DATA['ofer08diainical']=0;}
	if ($DATA['ofer08numestudiantes']==''){$DATA['ofer08numestudiantes']=50;}
	if ($DATA['ofer08numestaula1']==''){$DATA['ofer08numestaula1']=50;}
	if ($DATA['ofer08estadocampus']==''){$DATA['ofer08estadocampus']=0;}
	if ($DATA['ofer08idnav']==''){$DATA['ofer08idnav']=0;}
	if ($DATA['ofer08idevalacredita']==''){$DATA['ofer08idevalacredita']=0;}
	if ($DATA['ofer08puntajeacredita']==''){$DATA['ofer08puntajeacredita']=0;}
	//if ($DATA['ofer08idcursonav']==''){$DATA['ofer08idcursonav']=0;}
	if ($DATA['ofer08puntajelaboratorio']==''){$DATA['ofer08puntajelaboratorio']=0;}
	if ($DATA['ofer08puntajesalida']==''){$DATA['ofer08puntajesalida']=0;}
	if ($DATA['ofer08idcohorte']==''){$DATA['ofer08idcohorte']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if ($DATA['ofer08idusariorestaura']==''){$DATA['ofer08idusariorestaura']=0;}
	if ($DATA['ofer08usuarioconfirmaacceso']==''){$DATA['ofer08usuarioconfirmaacceso']=0;}
	if (!fecha_esvalida($DATA['ofer08fechaacredita'])){
		$DATA['ofer08fechaacredita']='00/00/0000';
		}
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
			$sSQL='SELECT ofer08idper_aca FROM ofer08oferta WHERE ofer08idper_aca='.$DATA['ofer08idper_aca'].' AND ofer08idcurso='.$DATA['ofer08idcurso'].' AND ofer08cead='.$DATA['ofer08cead'].'';
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
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['ofer08motivocancela']=stripslashes($DATA['ofer08motivocancela']);}
		$ofer08motivocancela=str_replace('"', '\"', $DATA['ofer08motivocancela']);
		$bpasa=false;
		if ($DATA['paso']==10){
			//list($INFO, $sError, $sDebug)=f1707_IniciarOferta($DATA['ofer08idper_aca'], $DATA['ofer08idcurso'], $DATA['ofer08cead'], $DATA['ofer08metodomatricula'], $DATA['ofer08incluyelaboratorio'], $DATA['ofer08puntajelaboratorio'], $DATA['ofer08incluyesalida'], $DATA['ofer08puntajesalida'], $objDB, $bDebug);
			list($INFO, $sError, $sDebug)=f1707_IniciarOfertaV2($DATA['ofer08idper_aca'], $DATA['ofer08idcurso'], $DATA['ofer08cead'], $DATA['ofer08obligaacreditar'], $DATA['ofer08metodomatricula'], $DATA['ofer08incluyelaboratorio'], $DATA['ofer08puntajelaboratorio'], $DATA['ofer08incluyesalida'], $DATA['ofer08puntajesalida'], $DATA['ofer08idorigenoferta'], $DATA['ofer08grupoidforma'], $DATA['ofer08grupominest'], $DATA['ofer08grupomaxest'], $objDB, $bDebug);
			if ($sError==''){
				$idaccion=2;
				$bpasa=true;
				$DATA['ofer08id']=$INFO['ofer08id'];
				$DATA['ofer08numestaula1']=$INFO['ofer08numestaula1'];
				$DATA['ofer08idagenda']=$INFO['ofer08idagenda'];
				$DATA['ofer08diainical']=$INFO['ofer08diainical'];
				$DATA['ofer08numestudiantes']=$INFO['ofer08numestudiantes'];
				$DATA['ofer08idnav']=$INFO['ofer08idnav'];
				$DATA['ofer08numestaula1']=$INFO['ofer08numestaula1'];
				$DATA['ofer08tipostandard']=$INFO['ofer08tipostandard'];
				$DATA['ofer08idprograma']=$INFO['ofer08idprograma'];
				$DATA['paso']=2;
				}else{
				$DATA['paso']=0;
				$DATA['ofer08id']='';
				}
			}else{
			$scampo[1]='ofer08obligaacreditar';
			$scampo[2]='ofer08incluyelaboratorio';
			$scampo[3]='ofer08puntajelaboratorio';
			$scampo[4]='ofer08incluyesalida';
			$scampo[5]='ofer08puntajesalida';
			$scampo[6]='ofer08idcohorte';
			$scampo[7]='ofer08razonvence';
			$sdato[1]=$DATA['ofer08obligaacreditar'];
			$sdato[2]=$DATA['ofer08incluyelaboratorio'];
			$sdato[3]=$DATA['ofer08puntajelaboratorio'];
			$sdato[4]=$DATA['ofer08incluyesalida'];
			$sdato[5]=$DATA['ofer08puntajesalida'];
			$sdato[6]=$DATA['ofer08idcohorte'];
			$sdato[7]=$DATA['ofer08razonvence'];
			$numcmod=7;
			$sWhere='ofer08id='.$DATA['ofer08id'].'';
			//$sWhere='ofer08idper_aca='.$DATA['ofer08idper_aca'].' AND ofer08idcurso='.$DATA['ofer08idcurso'].' AND ofer08cead='.$DATA['ofer08cead'].'';
			$sSQL='SELECT * FROM ofer08oferta WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$bCambiaCohorte=false;
			$bCambiaProceso=false;
			$sProcesoAnterior='';
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
				$sProcesoAnterior=$filabase['ofer08obligaacreditar'];
				if ($filabase['ofer08idcohorte']!=$DATA['ofer08idcohorte']){$bCambiaCohorte=true;}
				if ($filabase['ofer08obligaacreditar']!=$DATA['ofer08obligaacreditar']){$bCambiaProceso=true;}
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($bCambiaCohorte){
					$sdatos=$sdatos.', ofer08fechavence=0';
					}
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE ofer08oferta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE ofer08oferta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [1707] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['ofer08id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($idaccion!=2){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 1707 '.$sSQL.'<br>';}
					if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['ofer08id'], $sdetalle, $objDB);}
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
					}
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
function html_botonesagenda($idAgenda){
	$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
	require $mensajes_1707;
	$res='<input id="cmdnuevaagenda" name="cmdnuevaagenda" type="button" class="btSoloProceso" value="'.$ETI['bt_nuevaagenda'].'" onclick="ofer_nuevaagenda()"/>';
	return $res;
	}
function html_combo_ofer08idagenda($objDB, $valor, $consec, $idCurso){
	}
function html_combo_ofer08idescuela($objDB, $valor, $vr){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='';
	$res=html_combo('ofer08idescuela', 'exte01id', 'exte01nombre', 'exte01escuela', $scondi, 'exte01nombre', $valor, $objDB, 'carga_combo_ofer08idcurso()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
//Fin de las funciones para las tablas hijas
function Cargar_ofer08idcurso($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_ofer08idcurso=html_combo_ofer08idcurso($objDB, '', $aParametros[0], $aParametros[1]);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_ofer08idcurso","innerHTML",$html_ofer08idcurso);
	return $objResponse;
	}
// -- Espacio para incluir funciones xajax personalizadas.
function bExisteRol($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sSQL='SELECT ofer11id FROM ofer11actores WHERE ofer11per_aca='.$datos[1].' AND ofer11idescuela='.$datos[2].' AND ofer11idcurso='.$datos[3].' AND ofer11idrol='.$datos[4].'';
	$res=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($res)!=0){
		$fila=$objDB->sf($res);
		$id11=$fila['ofer11id'];
		//f1711_Traer($datos);
		$objResponse=new xajaxResponse();
		$objResponse->call("cargaridf1711(".$id11.")");
		return $objResponse;
		}
	}
?>