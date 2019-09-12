<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
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
function f1718_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$babierta=true;
	$sDetalle=f1718_TablaDetalle($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1718detalle","innerHTML",$sDetalle);
	return $objResponse;
	}
function f1718_TablaDetalleAgendaHija($params, $objdb){
	$mensajes_1718='lg/lg_1718_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1718)){$mensajes_1718='lg/lg_1718_es.php';}
	require $mensajes_1718;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=50;}
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	if (isset($params[103])==0){$params[103]=-1;}
	if (isset($params[104])==0){$params[104]=-1;}
	if (isset($params[105])==0){$params[105]=-1;}
	if ($params[103]==''){$params[103]=-1;}
	if ($params[104]==''){$params[104]=-1;}
	if ($params[105]==''){$params[105]=-1;}
	$sqladd='';
	$sqladd1=' TB.ofer18curso='.$params[104].' AND TB.ofer18numaula='.$params[105].' AND TB.ofer18per_aca='.$params[103].' AND ';
	//if ($params[1]!=''){$sqladd=$sqladd.' AND TB.campo2="'.$params[1].'"';}
	//ofer04cursoactividad	ofer04id	ofer04nombre
	$sql='SELECT TB.ofer18per_aca, TB.ofer18curso, TB.ofer18numaula, TB.ofer18fase, TB.ofer18unidad, TB.ofer18idactividad, TB.ofer18diaini, TB.ofer18fechainicio, TB.ofer18duracion, TB.ofer18fechacierrre, TB.ofer18diasretro, TB.ofer18fecharetro, T1.ofer04nombre, TB.ofer18peso, T2.ofer02nombre, T3.ofer03nombre 
FROM ofer18cargaxnavxdia AS TB, ofer04cursoactividad AS T1, ofer02cursofase AS T2, ofer03cursounidad AS T3 
WHERE '.$sqladd1.' TB.ofer18idactividad=T1.ofer04id AND TB.ofer18fase=T2.ofer02id AND TB.ofer18unidad=T3.ofer03id '.$sqladd.' 
ORDER BY ofer18orden, STR_TO_DATE(TB.ofer18fechainicio, "%d/%m/%Y"), STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabladetalle)==0){
		$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="resaltetabla">
<td><b>NO SE HAN CARGADO ACTIVIDADES PARA ESTA AULA AUDICIONAL</b></td>
</tr>';
		}else{
		$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="resaltetabla">
<td><b>'.$ETI['ofer18fase'].'</b></td>
<td><b>'.$ETI['ofer18unidad'].'</b></td>
<td><b>'.$ETI['ofer18idactividad'].'</b></td>
<td><b>'.$ETI['ofer18fechainicio'].'</b></td>
<td><b>'.$ETI['ofer18fechacierrre'].'</b></td>
<td><b>'.$ETI['ofer18fecharetro'].'</b></td>
<td><b>'.$ETI['ofer18peso'].'</b></td>
</tr>';
		}
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
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.cadena_notildes($filadet['ofer02nombre']).$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer03nombre']).$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer04nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer18fechainicio'].$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer18fechacierrre'].$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer18fecharetro'].$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer18peso'].$ssufijo.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function f1718_Agenda_Actualizar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$babierta=true;
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	$iTipoError=0;
	list($sError, $sDebug)=OAI_ArmarAgendaV2($valores[1], $valores[2], 1, false, $objDB, true, $bDebug);	
	if ($sError==''){
		$sError='<b>Se ha actualizado la agenda</b>';
		$iTipoError=1;
		}
	$sDetalle=f1718_TablaDetalle($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1718detalle','innerHTML',$sDetalle);
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function Agenda_ActualizarHija($valores, $params){
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$iTipoError=0;
	$babierta=true;
	$sError=OAI_ArmarAgenda($valores[1], $valores[2], $valores[3], true, $objDB);	
	if ($sError==''){
		$sError='<b>Se ha actualizado la agenda '.$valores[3].'</b>';
		}
	//$sDetalle=f1718_TablaDetalle($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	$objResponse->call('paginarf148');
	return $objResponse;
	}
?>
