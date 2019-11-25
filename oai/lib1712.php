<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- Modelo Versión 1.2.0 martes, 08 de julio de 2014
*/
function f1712_db_GuardarV0($valores, $objdb){
	$icodmodulo=1712;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1712='lg/lg_1712_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1712)){$mensajes_1712='lg/lg_1712_es.php';}
	require $mensajes_todas;
	require $mensajes_1712;
	$sError='';
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer12idoferta=numeros_validar($valores[1]);
	$ofer12consec=numeros_validar($valores[2]);
	$ofer12id=numeros_validar($valores[3], true);
	$ofer12fechaoferta=$valores[4];
	$ofer12fechacancela=$valores[5];
	$ofer12idtercero=numeros_validar($valores[6]);
	if ($ofer12idtercero==0){$sError=$ERR['ofer12idtercero'];}
	if (!fecha_esvalida($ofer12fechacancela)){
		//$ofer12fechacancela='00/00/0000';
		$sError=$ERR['ofer12fechacancela'];
		}
	if (!fecha_esvalida($ofer12fechaoferta)){
		//$ofer12fechaoferta='00/00/0000';
		$sError=$ERR['ofer12fechaoferta'];
		}
	//if ($ofer12id==''){$sError=$ERR['ofer12id'];}//CONSECUTIVO
	//if ($ofer12consec==''){$sError=$ERR['ofer12consec'];}//CONSECUTIVO
	if ($ofer12idoferta==''){$sError=$ERR['ofer12idoferta'];}
	if ($sError==''){
		if ((int)$ofer12id==0){
			if ((int)$ofer12consec==0){
				$ofer12consec=tabla_consecutivo('ofer12ofertahistorico', 'ofer12consec', 'ofer12idoferta='.$ofer12idoferta.'', $objdb);
				if ($ofer12consec==-1){$sError=$objdb->serror;}
				}
			$sql='SELECT ofer12idoferta FROM ofer12ofertahistorico WHERE ofer12idoferta='.$ofer12idoferta.' AND ofer12consec='.$ofer12consec.'';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$ofer12id=tabla_consecutivo('ofer12ofertahistorico', 'ofer12id', '', $objdb);
				if ($ofer12id==-1){$sError=$objdb->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='ofer12idoferta, ofer12consec, ofer12id, ofer12fechaoferta, ofer12fechacancela, ofer12idtercero';
			$svalores=''.$ofer12idoferta.', '.$ofer12consec.', '.$ofer12id.', "'.$ofer12fechaoferta.'", "'.$ofer12fechacancela.'", "'.$ofer12idtercero.'"';
			$sql='INSERT INTO ofer12ofertahistorico ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError='Error critico al tratar de guardar Historico de oferta, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sql, $objdb);
					}
				}
			}else{
			$scampo1712[1]='ofer12fechaoferta';
			$scampo1712[2]='ofer12fechacancela';
			$scampo1712[3]='ofer12idtercero';
			$svr1712[1]=$ofer12fechaoferta;
			$svr1712[2]=$ofer12fechacancela;
			$svr1712[3]=$ofer12idtercero;
			$inumcampos=3;
			$sWhere='ofer12idoferta='.$ofer12idoferta.' AND ofer12consec='.$ofer12consec.'';
			$sql='SELECT * FROM ofer12ofertahistorico WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1712[$k]]!=$svr1712[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1712[$k].'="'.$svr1712[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sql='UPDATE ofer12ofertahistorico SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError='Error critico al tratar de guardar Historico de oferta, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
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
function f1712_db_Eliminar($params, $objdb){
	$icodmodulo=1712;
	$bAudita[4]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1712='lg/lg_1712_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1712)){$mensajes_1712='lg/lg_1712_es.php';}
	require $mensajes_todas;
	require $mensajes_1712;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer12idoferta=numeros_validar($params[1]);
	$ofer12consec=numeros_validar($params[2]);
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
		$sWhere='ofer12idoferta='.$ofer12idoferta.' AND ofer12consec='.$ofer12consec.'';
		$sql='DELETE FROM ofer12ofertahistorico WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError='Error critico al tratar de eliminar Historico de oferta, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sql, $objdb);
				}
			}
		}
	return $sError;
	}
function f1712_TablaDetalle($params, $objdb){
	$mensajes_1712='lg/lg_1712_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1712)){$mensajes_1712='lg/lg_1712_es.php';}
	require $mensajes_1712;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer08id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$sqladd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sErrConsulta='';
	$sql='SELECT TB.ofer12idoferta, TB.ofer12consec, TB.ofer12id, TB.ofer12fechaoferta, TB.ofer12fechacancela, T6.unad11razonsocial AS C6_nombre, TB.ofer12idtercero, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc 
FROM ofer12ofertahistorico AS TB, unad11terceros AS T6 
WHERE TB.ofer12idoferta='.$ofer08id.' AND TB.ofer12idtercero=T6.unad11id '.$sqladd.' ORDER BY TB.ofer12consec DESC';
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
<td><b>'.$ETI['ofer12consec'].'</b></td>
<td><b>'.$ETI['ofer12fechaoferta'].'</b></td>
<td><b>'.$ETI['ofer12fechacancela'].'</b></td>
<td colspan="2" align="center"><b>'.$ETI['ofer12idtercero'].'</b></td>
<td align="right">
'.html_paginador("paginaf1712", $registros, $lineastabla, $pagina, "paginarf1712()").'
'.html_lpp("lppf1712", $lineastabla, "paginarf1712()").'
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
		$et_ofer12fechaoferta='';
		if ($filadet['ofer12fechaoferta']!='00/00/0000'){$et_ofer12fechaoferta=$filadet['ofer12fechaoferta'];}
		$et_ofer12fechacancela='';
		if ($filadet['ofer12fechacancela']!='00/00/0000'){$et_ofer12fechacancela=$filadet['ofer12fechacancela'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1712('."'".$filadet['ofer12id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.$filadet['ofer12consec'].$ssufijo.'</td>
<td>'.$sprefijo.$et_ofer12fechaoferta.$ssufijo.'</td>
<td>'.$sprefijo.$et_ofer12fechacancela.$ssufijo.'</td>
<td>'.$sprefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['C6_nombre']).$ssufijo.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
?>
