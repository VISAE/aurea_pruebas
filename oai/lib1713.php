<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- Modelo Versión 1.2.0 martes, 08 de julio de 2014
*/
function f1713_db_Guardar($valores, $objdb){
	$icodmodulo=1713;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1713='lg/lg_1713_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1713)){$mensajes_1713='lg/lg_1713_es.php';}
	require $mensajes_todas;
	require $mensajes_1713;
	$sError='';
	$objdb->xajax();
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer13idoferta=numeros_validar($valores[1]);
	$ofer13consec=numeros_validar($valores[2]);
	$ofer13id=numeros_validar($valores[3], true);
	$ofer13fecha=$valores[4];
	$ofer13hora=numeros_validar($valores[5]);
	$ofer13minuto=numeros_validar($valores[6]);
	$ofer13idusuario=numeros_validar($valores[7]);
	$ofer13anotacion=htmlspecialchars($valores[8]);
	//if ($ofer13hora==''){$ofer13hora=0;}
	//if ($ofer13minuto==''){$ofer13minuto=0;}
	if ($ofer13anotacion==''){$sError=$ERR['ofer13anotacion'];}
	if ($ofer13idusuario==0){$sError=$ERR['ofer13idusuario'];}
	if ($ofer13minuto==''){$sError=$ERR['ofer13minuto'];}
	if ($ofer13hora==''){$sError=$ERR['ofer13hora'];}
	if (!fecha_esvalida($ofer13fecha)){
		//$ofer13fecha='00/00/0000';
		$sError=$ERR['ofer13fecha'];
		}
	//if ($ofer13id==''){$sError=$ERR['ofer13id'];}//CONSECUTIVO
	//if ($ofer13consec==''){$sError=$ERR['ofer13consec'];}//CONSECUTIVO
	if ($ofer13idoferta==''){$sError=$ERR['ofer13idoferta'];}
	if ($sError==''){
		if ((int)$ofer13id==0){
			if ((int)$ofer13consec==0){
				$ofer13consec=tabla_consecutivo('ofer13ofertaanotacion', 'ofer13consec', 'ofer13idoferta='.$ofer13idoferta.'', $objdb);
				if ($ofer13consec==-1){$sError=$objdb->serror;}
				}
			$sql='SELECT ofer13idoferta FROM ofer13ofertaanotacion WHERE ofer13idoferta='.$ofer13idoferta.' AND ofer13consec='.$ofer13consec.'';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$ofer13id=tabla_consecutivo('ofer13ofertaanotacion', 'ofer13id', '', $objdb);
				if ($ofer13id==-1){$sError=$objdb->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//Si el campo ofer13anotacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$ofer13anotacion=str_replace('"', '\"', $ofer13anotacion);
		$ofer13anotacion=str_replace('&quot;', '\"', $ofer13anotacion);
		if ($binserta){
			$scampos='ofer13idoferta, ofer13consec, ofer13id, ofer13fecha, ofer13hora, ofer13minuto, ofer13idusuario, ofer13anotacion, ofer13atendida, ofer13fechaatendida, ofer13idatiende, ofer13detalle';
			$svalores=''.$ofer13idoferta.', '.$ofer13consec.', '.$ofer13id.', "'.$ofer13fecha.'", '.$ofer13hora.', '.$ofer13minuto.', "'.$ofer13idusuario.'", "'.$ofer13anotacion.'", "N", "00/00/0000", 0, ""';
			$sql='INSERT INTO ofer13ofertaanotacion ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError='Error critico al tratar de guardar Anotaciones, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sql, $objdb);
					}
				}
			}else{
			$scampo1713[1]='ofer13fecha';
			$scampo1713[2]='ofer13hora';
			$scampo1713[3]='ofer13minuto';
			$scampo1713[4]='ofer13idusuario';
			$scampo1713[5]='ofer13anotacion';
			$svr1713[1]=$ofer13fecha;
			$svr1713[2]=$ofer13hora;
			$svr1713[3]=$ofer13minuto;
			$svr1713[4]=$ofer13idusuario;
			$svr1713[5]=$ofer13anotacion;
			$inumcampos=5;
			$sWhere='ofer13idoferta='.$ofer13idoferta.' AND ofer13consec='.$ofer13consec.'';
			$sql='SELECT * FROM ofer13ofertaanotacion WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1713[$k]]!=$svr1713[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1713[$k].'="'.$svr1713[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sql='UPDATE ofer13ofertaanotacion SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError='Error critico al tratar de guardar Anotaciones, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, 0, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError);
	}
function f1713_db_Eliminar($params, $objdb){
	$icodmodulo=1713;
	$bAudita[4]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1713='lg/lg_1713_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1713)){$mensajes_1713='lg/lg_1713_es.php';}
	require $mensajes_todas;
	require $mensajes_1713;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer13idoferta=numeros_validar($params[1]);
	$ofer13consec=numeros_validar($params[2]);
/*	if (!comprobacion){
		$sError='No se puede eliminar';//EXPLICAR LA RAZON
		}*/
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='ofer13idoferta='.$ofer13idoferta.' AND ofer13consec='.$ofer13consec.'';
		$sql='DELETE FROM ofer13ofertaanotacion WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError='Error critico al tratar de eliminar Anotaciones, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sql, $objdb);
				}
			}
		}
	return $sError;
	}
function f1713_TablaDetalle($params, $objdb){
	$mensajes_1713='lg/lg_1713_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1713)){$mensajes_1713='lg/lg_1713_es.php';}
	require $mensajes_1713;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer08id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sqladd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sErrConsulta='';
	$sql='SELECT TB.ofer13idoferta, TB.ofer13consec, TB.ofer13id, TB.ofer13fecha, TB.ofer13hora, TB.ofer13minuto, T7.unad11razonsocial AS C7_nombre, TB.ofer13anotacion, TB.ofer13idusuario, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc, TB.ofer13atendida, TB.ofer13fechaatendida, TB.ofer13idatiende, TB.ofer13detalle, T11.unad11razonsocial AS C11_nombre 
FROM ofer13ofertaanotacion AS TB LEFT JOIN unad11terceros AS T11 ON (TB.ofer13idatiende=T11.unad11id), unad11terceros AS T7  
WHERE TB.ofer13idoferta='.$ofer08id.' AND TB.ofer13idusuario=T7.unad11id '.$sqladd.' ORDER BY TB.ofer13consec DESC';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$sErrConsulta='..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}
	$registros=$objdb->nf($tabladetalle);
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		$tabladetalle=$objdb->ejecutasql($sql.$limite);
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><strong>'.$ETI['ofer13consec'].'</strong></td>
<td colspan="2"><strong>'.$ETI['ofer13idusuario'].'</strong></td>
<td><strong>'.$ETI['ofer13fecha'].'</strong></td>
<td><strong>'.cadena_notildes($ETI['ofer13anotacion']).'</strong></td>
<td align="right">
'.html_paginador("paginaf1713", $registros, $lineastabla, $pagina, "paginarf1713()").'
'.html_lpp("lppf1713", $lineastabla, "paginarf1713()").'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sprefijo='';
		$ssufijo='';
		if (false){
			$sprefijo='<b>';
			$ssufijo='</b>';
			}
		$res=$res.'<tr ';
		if(($tlinea%2)==0){$res=$res.'class="resaltetabla"';}
		$tlinea++;
		$res=$res.'>
<td>'.$sprefijo.$filadet['ofer13consec'].$ssufijo.'</td>
<td>'.$sprefijo.$filadet['C7_td'].' '.$filadet['C7_doc'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['C7_nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer13fecha'].' '.formato_hora($filadet['ofer13hora']).':'.formato_hora($filadet['ofer13minuto']).$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer13anotacion']).$ssufijo.'</td>
<td>';
		if ($_SESSION['unad_id_tercero']==$filadet['ofer13idusuario']){
			$res=$res.'<a href="javascript:cargaridf1713('."'".$filadet['ofer13id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'</td></tr>';
		if ($filadet['ofer13atendida']=='S'){
			$res=$res.'<tr><td colspan="6">Respuesta: <b>'.cadena_notildes($filadet['ofer13detalle']).'</b><br>Atendido por: '.$filadet['C11_nombre'].' '.$filadet['ofer13fecha'].'</td></tr>';
			}
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
?>