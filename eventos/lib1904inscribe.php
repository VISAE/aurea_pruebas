<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Friday, September 6, 2019
--- 1904 even04eventoparticipante
*/
/** Archivo lib1904.php.
* Libreria 1904 even04eventoparticipante.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Friday, September 6, 2019
*/
function f1904_HTMLComboV2_even04idevento($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('even04idevento', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT even02id AS id, even02nombre AS nombre FROM even02evento';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1904_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1904='lg/lg_1904_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1904)){$mensajes_1904='lg/lg_1904_es.php';}
	require $mensajes_todas;
	require $mensajes_1904;
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
		case 'even04idparticipante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1904);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_1904'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f1904_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'even04idparticipante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1904_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
    $mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
    if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
	$mensajes_1904='lg/lg_1904_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1904)){$mensajes_1904='lg/lg_1904_es.php';}
	require $mensajes_todas;
    require $mensajes_1902;
	require $mensajes_1904;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
    if (isset($aParametros[104])==0){$aParametros[104]='';}
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
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf1904" name="paginaf1904" type="hidden" value="'.$pagina.'"/><input id="lppf1904" name="lppf1904" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
    $sSQL='';
	$sSQLadd='';
    $sSQLadd1='';
	$etiLnk='';
    $estadoAsistencia = '';
	if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND T2.even04idparticipante='.$aParametros[103].'';}
	if ($aParametros[104]!=''){
        $sSQLadd1='even04id';
        $etiLnk=$etiLnk.'lnk_'.$aParametros[104].'';
	    switch ($aParametros[104]) {
            case 'futuro':
                $operadorFecha = '>';
                $estadoAsistencia = '1';
                break;
            case 'presente':
                $operadorFecha = '=';
                $estadoAsistencia = '1,7';
                break;
            case 'pasado':
                $operadorFecha = '<';
                $estadoAsistencia = '3';
                $babierta=false;
                $etiLnk='';
                break;
            case 'bfuturo':
                $operadorFecha = '>';
                $estadoAsistencia = '0';
                $sSQLadd1='even02id';
                $sSQL=$sSQL.'SELECT '.$sSQLadd1.',T3.even01nombre, T4.even41titulo, T5.even14nombre, TB.even02nombre, 
TB.even02lugar, TB.even02inifecha, TB.even02inihora, TB.even02iniminuto, 
TB.even02finfecha, TB.even02finhora, TB.even02finminuto, "" AS even13nombre, TB.even02idcertificado
FROM even02evento AS TB, even01tipoevento AS T3, even41categoria AS T4, even14estadoevento AS T5 
WHERE TB.even02tipo=T3.even01id AND TB.even02categoria=T4.even41id AND TB.even02estado=T5.even14id AND TB.even02formainscripcion=0 
AND STR_TO_DATE(TB.even02inifecha,"%d/%m/%Y") '.$operadorFecha.' DATE(NOW())  
AND TB.even02id NOT IN (SELECT T2.even04idevento FROM even04eventoparticipante AS T2 WHERE TRUE '.$sSQLadd.') 
UNION ';
                $sSQLadd=$sSQLadd.' AND TB.even02formainscripcion=1 ';
                break;
            }
        $sSQLadd=$sSQLadd.' AND T2.even04estadoasistencia IN ('.$estadoAsistencia.')  
AND STR_TO_DATE(TB.even02inifecha,"%d/%m/%Y") '.$operadorFecha.' DATE(NOW()) ';
	}
	$sTitulos='Evento, Tipo, Categoria, Lugar, Fecha Inicial, Hora Inicial, Fecha Final, Hora Final';
	$sSQL=$sSQL.'SELECT '.$sSQLadd1.', T3.even01nombre, T4.even41titulo, T5.even14nombre, TB.even02nombre, 
TB.even02lugar, TB.even02inifecha, TB.even02inihora, TB.even02iniminuto, 
TB.even02finfecha, TB.even02finhora, TB.even02finminuto, T6.even13nombre, TB.even02idcertificado
FROM even02evento AS TB, even04eventoparticipante AS T2, even01tipoevento AS T3, even41categoria AS T4, even14estadoevento AS T5, even13estadoasistencia AS T6
WHERE TB.even02id=T2.even04idevento AND TB.even02tipo=T3.even01id AND TB.even02categoria=T4.even41id AND TB.even02estado=T5.even14id AND T2.even04estadoasistencia=T6.even13id '.$sSQLadd.'';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1904" name="consulta_1904" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1904" name="titulos_1904" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1904: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1904" name="paginaf1904" type="hidden" value="'.$pagina.'"/><input id="lppf1904" name="lppf1904" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['even04idevento'].'</b></td>
<td><b>'.$ETI['even02tipo'].'</b></td>
<td><b>'.$ETI['even02categoria'].'</b></td>
<td><b>'.$ETI['even02lugar'].'</b></td>
<td><b>'.$ETI['even02inifecha'].'</b></td>
<td><b>'.$ETI['even02inihora'].'</b></td>
<td><b>'.$ETI['even02finfecha'].'</b></td>
<td><b>'.$ETI['even02finhora'].'</b></td>
<td align="right">
'.html_paginador('paginaf1904', $registros, $lineastabla, $pagina, 'paginarf1904()').'
'.html_lpp('lppf1904', $lineastabla, 'paginarf1904()').'
</td>
</tr>';
	$tlinea=1;
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
        $et_even02inifecha='';
        if ($filadet['even02inifecha']!='00/00/0000'){$et_even02inifecha=$filadet['even02inifecha'];}
        $et_even02inihora=html_TablaHoraMin($filadet['even02inihora'], $filadet['even02iniminuto']);
        $et_even02finfecha='';
        if ($filadet['even02finfecha']!='00/00/0000'){$et_even02finfecha=$filadet['even02finfecha'];}
        $et_even02finhora=html_TablaHoraMin($filadet['even02finhora'], $filadet['even02finminuto']);
        if($filadet['even02idcertificado']!=0){
            $babierta=true;
            $etiLnk=$etiLnk.'lnk_'.$aParametros[104].'';
            }
		if ($babierta){
			$sLink='<a href="javascript:cambiaEstadoEventoId('.$filadet[$sSQLadd1].',\''.$aParametros[104].'\')" class="lnkresalte">'.$ETI[$etiLnk].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['even02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even41titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even02lugar']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02inifecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02inihora.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02finfecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even02finhora.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	if($tlinea == 1){$res = '';}
	return array(utf8_encode($res), $sDebug);
	}
function f1904_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1904_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1904detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1904_CambiarEstado($aParametros){
    switch ($aParametros[106]) {
        case 'futuro':

            break;
        case 'presente':
            break;
        case 'pasado':
            break;
        case 'bfuturo':
            break;
        }
    }
?>