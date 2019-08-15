<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Saul Alexander Hernandez Albarracin - UNAD - 2019 ---
--- saul.hernandez@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.1 jueves, 30 de julio de 2015
--- Modelo Versión 2.14.3 miércoles, 20 de julio de 2016
--- Modelo Versión 2.17.3 miércoles, 29 de marzo de 2017
--- Modelo Versión 2.19.7c viernes, 09 de febrero de 2018
--- Modelo Versión 2.21.0 jueves, 14 de junio de 2018
--- Modelo Versión 2.22.6 jueves, 15 de noviembre de 2018
--- 111 unad11terceros
*/
function html_combo_unad11deptoorigen($objdb, $valor, $vrunad11nacionalidad){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='unad19codpais="'.$vrunad11nacionalidad.'"';
	$res=html_combo('unad11deptoorigen', 'unad19codigo', 'unad19nombre', 'unad19depto', $scondi, 'unad19nombre', $valor, $objdb, 'carga_combo_unad11ciudadorigen()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_unad11ciudadorigen($objdb, $valor, $vrunad11deptoorigen){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='unad20coddepto="'.$vrunad11deptoorigen.'"';
	$res=html_combo('unad11ciudadorigen', 'unad20codigo', 'unad20nombre', 'unad20ciudad', $scondi, 'unad20nombre', $valor, $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_unad11deptodoc($objdb, $valor, $vrunad11pais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='unad19codpais="'.$vrunad11pais.'"';
	$res=html_combo('unad11deptodoc', 'unad19codigo', 'unad19nombre', 'unad19depto', $scondi, 'unad19nombre', $valor, $objdb, 'carga_combo_unad11ciudaddoc()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_unad11ciudaddoc($objdb, $valor, $vrunad11deptodoc){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='unad20coddepto="'.$vrunad11deptodoc.'"';
	$res=html_combo('unad11ciudaddoc', 'unad20codigo', 'unad20nombre', 'unad20ciudad', $scondi, 'unad20nombre', $valor, $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f111_HTMLComboV2_unad11idcead($objdb, $objCombos, $valor, $vrunad11idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrunad11idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('unad11idcead', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$res=$objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi, $objdb);
	return $res;
	}
function f111_HTMLComboV2_unad11idprograma($objdb, $objCombos, $valor, $vrunad11idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='core09idescuela="'.$vrunad11idescuela.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('unad11idprograma', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$res=$objCombos->html('SELECT core09id AS id, core09nombre AS nombre FROM core09programa'.$sCondi, $objdb);
	return $res;
	}
function Cargar_unad11deptoorigen($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$html_unad11deptoorigen=html_combo_unad11deptoorigen($objdb, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11deptoorigen', 'innerHTML', $html_unad11deptoorigen);
	return $objResponse;
	}
function Cargar_unad11ciudadorigen($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$html_unad11ciudadorigen=html_combo_unad11ciudadorigen($objdb, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11ciudadorigen', 'innerHTML', $html_unad11ciudadorigen);
	return $objResponse;
	}
function Cargar_unad11deptodoc($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$html_unad11deptodoc=html_combo_unad11deptodoc($objdb, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11deptodoc', 'innerHTML', $html_unad11deptodoc);
	return $objResponse;
	}
function Cargar_unad11ciudaddoc($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$html_unad11ciudaddoc=html_combo_unad11ciudaddoc($objdb, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11ciudaddoc', 'innerHTML', $html_unad11ciudaddoc);
	return $objResponse;
	}
function f111_Combounad11idcead($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_unad11idcead=f111_HTMLComboV2_unad11idcead($objdb, $objCombos, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11idcead', 'innerHTML', $html_unad11idcead);
	return $objResponse;
	}
function f111_Combounad11idprograma($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_unad11idprograma=f111_HTMLComboV2_unad11idprograma($objdb, $objCombos, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11idprograma', 'innerHTML', $html_unad11idprograma);
	return $objResponse;
	}
function f111_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$bDebug=false;
	if (isset($datos[9])==0){$datos[9]=0;}
	if ($datos[9]==1){$bDebug=true;}
	$unad11tipodoc=htmlspecialchars($datos[1]);
	if ($unad11tipodoc==''){$bHayLlave=false;}
	$unad11doc=htmlspecialchars($datos[2]);
	if ($unad11doc==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sSQL='SELECT unad11doc FROM unad11terceros WHERE unad11doc="'.$unad11doc.'" AND unad11tipodoc="'.$unad11tipodoc.'"';
		$res=$objdb->ejecutasql($sSQL);
		if ($objdb->nf($res)==0){
			//Intentamos importarlo primero.
			unad11_importar_V2($unad11doc, '', $objdb);
			$res=$objdb->ejecutasql($sSQL);
			}
		if ($objdb->nf($res)==0){$bHayLlave=false;}
		$objdb->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f111_Busquedas($params){
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_111;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sCampo=$params[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$paramsb=array();
	$paramsb[101]=1;
	$paramsb[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_111'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f111_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle='';
	switch($params[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f111_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_111;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]='';}
	if (isset($params[107])==0){$params[107]='';}
	if (isset($params[108])==0){$params[108]='';}
	if (isset($params[109])==0){$params[109]='';}
	if (isset($params[110])==0){$params[110]='';}
	if (isset($params[111])==0){$params[111]='';}
	if (isset($params[112])==0){$params[112]='';}
	if (isset($params[113])==0){$params[113]='';}
	if (isset($params[114])==0){$params[114]='';}
	if (isset($params[115])==0){$params[115]='';}
	if (isset($params[116])==0){$params[116]='';}
	//$params[103]=numeros_validar($params[103]);
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='';
	$sSQLadd1='';
	$limite='';
	//if (isset($params[0])==0){$params[0]='';}
	//if ((int)$params[0]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[0];}
/*	if ($params[109]!=''){
		$sSubConsulta='SELECT T16.core16tercero FROM core16actamatricula AS T16 WHERE T16.core16peraca='.$params[109].'';
		$sSQLadd=$sSQLadd.' TB.unad11id IN ('.$sSubConsulta.')';
		}
		*/
	if ($params[103]!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.' TB.unad11doc LIKE "%'.$params[103].'%"';
		unad11_importar_V2($params[103], '', $objdb);
		}
	if ($params[104]!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11razonsocial LIKE "%'.$params[104].'%"';
		}
	if ($params[105]!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11usuario LIKE "%'.$params[105].'%"';
		unad11_importar_V2('', $params[105], $objdb);
		}
	//parametro de correo electronico.
	if ($params[106]!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		switch($params[107]){
			case 1: //Correo personal
			$sSQLadd=$sSQLadd.'TB.unad11correo LIKE "%'.$params[106].'%"';
			break;
			case 2: //Correo notificaciones
			$sSQLadd=$sSQLadd.'TB.unad11correonotifica LIKE "%'.$params[106].'%"';
			break;
			case 3: //Correo institucional
			$sSQLadd=$sSQLadd.'TB.unad11correoinstitucional LIKE "%'.$params[106].'%"';
			break;
			case 4: //Correo funcionario
			$sSQLadd=$sSQLadd.'TB.unad11correofuncionario LIKE "%'.$params[106].'%"';
			break;
			default:
			//Todos los correos...
			$sSQLadd=$sSQLadd.'((TB.unad11correo LIKE "%'.$params[106].'%") OR (TB.unad11correonotifica LIKE "%'.$params[106].'%") OR (TB.unad11correoinstitucional LIKE "%'.$params[106].'%") OR (TB.unad11correofuncionario LIKE "%'.$params[106].'%"))';
			break;
			}
		}
	switch($params[108]){
		case 1:
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11fechaconfmail<>0';
		break;
		case 2:
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11fechaconfmail=0';
		break;
		}
	//Convenio.
	$sTablaConvenio='';
	if ($params[110]!=''){
		$sTablaConvenio=', core51convenioest AS T51';
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.'TB.unad11id=T51.core51idtercero AND T51.core51idconvenio='.$params[110].' AND T51.core51activo="S"';
		}
	//Fecha Desde Hasta
	if ($params[111]!='0'){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.'TB.unad11fechaultingreso >= '.$params[111].' ';
		}	
	if ($params[112]!='0'){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.'TB.unad11fechaultingreso <= '.$params[112].' ';
		}	
	
	// cead
	if ($params[114]!=''){
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
		$sSQLadd=$sSQLadd.' TB.unad11idcead='.$params[114].'  ';
		}else{
		if ($params[113]!=''){
			if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.' TB.unad11idzona='.$params[113].'  ';}
		}
	
       //Escuela
				
	if ($params[116]!=''){ //112 programa
		if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
	    $sSQLadd=$sSQLadd.' TB.unad11idprograma='.$params[116].'  ';
		}else{
		if ($params[115]!=''){ //111 escuela
			if ($sSQLadd!=''){$sSQLadd=$sSQLadd.' AND ';}
			$sSQLadd=$sSQLadd.'  TB.unad11idescuela='.$params[115].'  ';}
		}	
	
	//, TB.unad11dv, TB.unad11nombre1, TB.unad11nombre2, TB.unad11apellido1, TB.unad11apellido2, TB.unad11genero
	if ($sSQLadd!=''){$sSQLadd=' WHERE '.$sSQLadd.'';}
	$sErrConsulta='';
	$sSQL='SELECT 1 
FROM unad11terceros AS TB'.$sTablaConvenio.' 
'.$sSQLadd.' ';
	$tabladetalle=$objdb->ejecutasql($sSQL);
	$registros=$objdb->nf($tabladetalle);
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		}
	$sTitulos='Tipo doc,doc, usuario, razonsocial, direccion, telefono, correo personal, fecha tablero, correo notificaciones, fecha confirma correo,Fecha Nace,Escuela,Programa,Zona,Cead';
	

$sSQL='SELECT TB.unad11tipodoc, TB.unad11doc, TB.unad11usuario, TB.unad11razonsocial, TB.unad11direccion, TB.unad11telefono, TB.unad11correo, TB.unad11fechatablero, TB.unad11correonotifica, TB.unad11fechaconfmail, TB.unad11id,
TB.unad11fechanace FROM unad11terceros AS TB'.$sTablaConvenio.$sSQLadd.'  ORDER BY TB.unad11razonsocial';
//TB.unad11idmoodle, TB.unad11sitioweb, TB.unad11pais, TB.unad11ecivil, TB.unad11nacionalidad, TB.unad11deptoorigen, TB.unad11ciudadorigen, TB.unad11deptodoc, TB.unad11ciudaddoc, TB.unad11rh
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_111" name="consulta_111" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_111" name="titulos_111" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sSQL.$limite);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 111: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objdb->serror.'"/>';
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['unad11doc'].'</b></td>
<td><b>'.$ETI['unad11razonsocial'].'</b></td>
<td><b>'.$ETI['unad11fechanace'].'</b></td>
<td><b>'.$ETI['unad11usuario'].'</b></td>
<td align="right">
'.html_paginador('paginaf111', $registros, $lineastabla, $pagina, 'paginarf111()').'
'.html_lpp('lppf111', $lineastabla, 'paginarf111()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		$sFecha='';
		if ($filadet['unad11fechanace']!='00/00/0000'){$sFecha=$filadet['unad11fechanace'];}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf111('.$filadet['unad11id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad11tipodoc'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11razonsocial']).$sSufijo.'</td>
<td>'.$sPrefijo.$sFecha.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11usuario'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f111_HtmlTabla($params){
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
	list($sDetalle, $sDebugTabla)=f111_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f111detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}

function f111_db_GuardarV2($DATA, $objdb, $bDebug=false){
	$icodmodulo=111;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_111;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if (isset($DATA['unad11idzona'])==0){$DATA['unad11idzona']='';}
	if (isset($DATA['unad11idcead'])==0){$DATA['unad11idcead']='';}
	if (isset($DATA['unad11idescuela'])==0){$DATA['unad11idescuela']='';}
	if (isset($DATA['unad11idprograma'])==0){$DATA['unad11idprograma']='';}
	$DATA['unad11tipodoc']=htmlspecialchars($DATA['unad11tipodoc']);
	$DATA['unad11doc']=htmlspecialchars($DATA['unad11doc']);
	$DATA['unad11pais']=htmlspecialchars($DATA['unad11pais']);
	$DATA['unad11usuario']=htmlspecialchars($DATA['unad11usuario']);
	$DATA['unad11dv']=htmlspecialchars($DATA['unad11dv']);
	$DATA['unad11nombre1']=htmlspecialchars($DATA['unad11nombre1']);
	$DATA['unad11nombre2']=htmlspecialchars($DATA['unad11nombre2']);
	$DATA['unad11apellido1']=htmlspecialchars($DATA['unad11apellido1']);
	$DATA['unad11apellido2']=htmlspecialchars($DATA['unad11apellido2']);
	$DATA['unad11genero']=htmlspecialchars($DATA['unad11genero']);
	$DATA['unad11rh']=htmlspecialchars($DATA['unad11rh']);
	$DATA['unad11ecivil']=htmlspecialchars($DATA['unad11ecivil']);
	$DATA['unad11razonsocial']=htmlspecialchars($DATA['unad11razonsocial']);
	$DATA['unad11direccion']=htmlspecialchars($DATA['unad11direccion']);
	$DATA['unad11telefono']=htmlspecialchars($DATA['unad11telefono']);
	$DATA['unad11correo']=htmlspecialchars(trim($DATA['unad11correo']));
	$DATA['unad11sitioweb']=htmlspecialchars($DATA['unad11sitioweb']);
	$DATA['unad11nacionalidad']=htmlspecialchars($DATA['unad11nacionalidad']);
	$DATA['unad11deptoorigen']=htmlspecialchars($DATA['unad11deptoorigen']);
	$DATA['unad11ciudadorigen']=htmlspecialchars($DATA['unad11ciudadorigen']);
	$DATA['unad11deptodoc']=htmlspecialchars($DATA['unad11deptodoc']);
	$DATA['unad11ciudaddoc']=htmlspecialchars($DATA['unad11ciudaddoc']);
	$DATA['unad11clave']=htmlspecialchars($DATA['unad11clave']);
	$DATA['unad11idmoodle']=numeros_validar($DATA['unad11idmoodle']);
	$DATA['unad11idncontents']=numeros_validar($DATA['unad11idncontents']);
	$DATA['unad11iddatateca']=numeros_validar($DATA['unad11iddatateca']);
	$DATA['unad11idcampus']=numeros_validar($DATA['unad11idcampus']);
	$DATA['unad11claveapps']=htmlspecialchars($DATA['unad11claveapps']);
	$DATA['unad11bloqueado']=htmlspecialchars($DATA['unad11bloqueado']);
	$DATA['unad11aceptanotificacion']=htmlspecialchars($DATA['unad11aceptanotificacion']);
	$DATA['unad11correonotifica']=htmlspecialchars(trim($DATA['unad11correonotifica']));
	$DATA['unad11correoinstitucional']=htmlspecialchars(trim($DATA['unad11correoinstitucional']));
	$DATA['unad11latgrados']=numeros_validar($DATA['unad11latgrados']);
	$DATA['unad11latdecimas']=htmlspecialchars(trim($DATA['unad11latdecimas']));
	$DATA['unad11longrados']=numeros_validar($DATA['unad11longrados']);
	$DATA['unad11longdecimas']=htmlspecialchars(trim($DATA['unad11longdecimas']));
	$DATA['unad11skype']=htmlspecialchars(trim($DATA['unad11skype']));
	$DATA['unad11mostrarcelular']=htmlspecialchars(trim($DATA['unad11mostrarcelular']));
	$DATA['unad11mostrarcorreo']=htmlspecialchars(trim($DATA['unad11mostrarcorreo']));
	$DATA['unad11mostrarskype']=htmlspecialchars(trim($DATA['unad11mostrarskype']));
	$DATA['unad11fechaterminos']=numeros_validar($DATA['unad11fechaterminos']);
	$DATA['unad11minutotablero']=numeros_validar($DATA['unad11minutotablero'],true);
	$DATA['unad11noubicar']=numeros_validar($DATA['unad11noubicar']);
	$DATA['unad11exluirdobleaut']=htmlspecialchars(trim($DATA['unad11exluirdobleaut']));
	$DATA['unad11idzona']=numeros_validar($DATA['unad11idzona']);
	$DATA['unad11idcead']=numeros_validar($DATA['unad11idcead']);
	$DATA['unad11idescuela']=numeros_validar($DATA['unad11idescuela']);
	$DATA['unad11idprograma']=numeros_validar($DATA['unad11idprograma']);
	$DATA['unad11correofuncionario']=htmlspecialchars(trim($DATA['unad11correofuncionario']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['unad11idmoodle']==''){$DATA['unad11idmoodle']=0;}
	if ($DATA['unad11idncontents']==''){$DATA['unad11idncontents']=0;}
	if ($DATA['unad11iddatateca']==''){$DATA['unad11iddatateca']=0;}
	if ($DATA['unad11idcampus']==''){$DATA['unad11idcampus']=0;}
	if ($DATA['unad11idzona']==''){$DATA['unad11idzona']=0;}
	if ($DATA['unad11idcead']==''){$DATA['unad11idcead']=0;}
	if ($DATA['unad11idescuela']==''){$DATA['unad11idescuela']=0;}
	if ($DATA['unad11idprograma']==''){$DATA['unad11idprograma']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
	//if ($DATA['unad11ciudaddoc']==''){$sError=$ERR['unad11ciudaddoc'];}
	//if ($DATA['unad11deptodoc']==''){$sError=$ERR['unad11deptodoc'];}
	//if ($DATA['unad11ciudadorigen']==''){$sError=$ERR['unad11ciudadorigen'];}
	//if ($DATA['unad11deptoorigen']==''){$sError=$ERR['unad11deptoorigen'];}
	//if ($DATA['unad11nacionalidad']==''){$sError=$ERR['unad11nacionalidad'];}
	//if ($DATA['unad11sitioweb']==''){$sError=$ERR['unad11sitioweb'];}
	//if ($DATA['unad11correo']==''){$sError=$ERR['unad11correo'];}
	//if ($DATA['unad11telefono']==''){$sError=$ERR['unad11telefono'];}
	if ($DATA['unad11direccion']==''){$sError=$ERR['unad11direccion'];}
	//if ($DATA['unad11ecivil']==''){$sError=$ERR['unad11ecivil'];}
	//if ($DATA['unad11rh']==''){$sError=$ERR['unad11rh'];}
	if (!fecha_esvalida($DATA['unad11fechanace'])){
		$DATA['unad11fechanace']='00/00/0000';
		//$sError=$ERR['unad11fechanace'];
		}
	$DATA['unad11nombre1']=strtoupper($DATA['unad11nombre1']);
	$DATA['unad11nombre2']=strtoupper($DATA['unad11nombre2']);
	$DATA['unad11apellido1']=strtoupper($DATA['unad11apellido1']);
	$DATA['unad11apellido2']=strtoupper($DATA['unad11apellido2']);
	if ($DATA['unad11tipodoc']!='NI'){
		if ($DATA['unad11nombre1']==''){$sError=$ERR['unad11nombre1'];}
		if ($DATA['unad11apellido1']==''){$sError=$ERR['unad11apellido1'];}
		$DATA['unad11razonsocial']=trim(trim($DATA['unad11nombre1']).' '.trim($DATA['unad11nombre2']).' '.trim($DATA['unad11apellido1']).' '.trim($DATA['unad11apellido2']));
		}
	if ($DATA['unad11razonsocial']==''){$sError=$ERR['unad11razonsocial'];}
	//if ($DATA['unad11genero']==''){$sError=$ERR['unad11genero'];}
	//if ($DATA['unad11apellido2']==''){$sError=$ERR['unad11apellido2'];}
	//if ($DATA['unad11nombre2']==''){$sError=$ERR['unad11nombre2'];}
	//if ($DATA['unad11dv']==''){$sError=$ERR['unad11dv'];}
	//if ($DATA['unad11usuario']==''){$sError=$ERR['unad11usuario'];}
	if ($DATA['unad11pais']==''){$sError=$ERR['unad11pais'];}
	//if ($DATA['unad11id']==''){$sError=$ERR['unad11id'];}//CONSECUTIVO
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unad11doc']==''){$sError=$ERR['unad11doc'];}
	if ($DATA['unad11tipodoc']==''){$sError=$ERR['unad11tipodoc'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT unad11tipodoc FROM unad11terceros WHERE unad11tipodoc="'.$DATA['unad11tipodoc'].'" AND unad11doc="'.$DATA['unad11doc'].'"';
			$result=$objdb->ejecutasql($sSQL);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['unad11id']=tabla_consecutivo('unad11terceros','unad11id', '', $objdb);
			if ($DATA['unad11id']==-1){$sError=$objdb->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$unad11modf=fecha_DiaMod();
			$unad11modm=fecha_MinutoMod();
			$scampos='unad11tipodoc, unad11doc, unad11id, unad11pais, unad11usuario, unad11dv, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11genero, unad11fechanace, unad11rh, unad11ecivil, unad11razonsocial, unad11direccion, unad11telefono, unad11correo, unad11sitioweb, unad11nacionalidad, unad11deptoorigen, unad11ciudadorigen, unad11deptodoc, unad11ciudaddoc, unad11clave, unad11idmoodle, unad11idncontents, unad11iddatateca, unad11idcampus, unad11claveapps, unad11fechaclaveapps, unad11fechatablero, unad11bloqueado, unad11modf, unad11modm, unad11aceptanotificacion, unad11correonotifica, unad11correoinstitucional';
			$svalores='"'.$DATA['unad11tipodoc'].'", "'.$DATA['unad11doc'].'", '.$DATA['unad11id'].', "'.$DATA['unad11pais'].'", "'.$DATA['unad11usuario'].'", "'.$DATA['unad11dv'].'", "'.$DATA['unad11nombre1'].'", "'.$DATA['unad11nombre2'].'", "'.$DATA['unad11apellido1'].'", "'.$DATA['unad11apellido2'].'", "'.$DATA['unad11genero'].'", "'.$DATA['unad11fechanace'].'", "'.$DATA['unad11rh'].'", "'.$DATA['unad11ecivil'].'", "'.$DATA['unad11razonsocial'].'", "'.$DATA['unad11direccion'].'", "'.$DATA['unad11telefono'].'", "'.$DATA['unad11correo'].'", "'.$DATA['unad11sitioweb'].'", "'.$DATA['unad11nacionalidad'].'", "'.$DATA['unad11deptoorigen'].'", "'.$DATA['unad11ciudadorigen'].'", "'.$DATA['unad11deptodoc'].'", "'.$DATA['unad11ciudaddoc'].'", "'.$DATA['unad11clave'].'", '.$DATA['unad11idmoodle'].', '.$DATA['unad11idncontents'].', '.$DATA['unad11iddatateca'].', '.$DATA['unad11idcampus'].', "'.$DATA['unad11claveapps'].'", "'.$DATA['unad11fechaclaveapps'].'", "'.$DATA['unad11fechatablero'].'", "'.$DATA['unad11bloqueado'].'", '.$unad11modf.', '.$unad11modm.', "'.$DATA['unad11aceptanotificacion'].'", "'.$DATA['unad11correonotifica'].'", "'.$DATA['unad11correoinstitucional'].'"';
			$sSQL='INSERT INTO unad11terceros ('.$scampos.') VALUES ('.$svalores.');';
			$sdetalle=$scampos.'['.$svalores.']';
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='unad11nombre1';
			$scampo[2]='unad11nombre2';
			$scampo[3]='unad11apellido1';
			$scampo[4]='unad11apellido2';
			$scampo[5]='unad11genero';
			$scampo[6]='unad11fechanace';
			$scampo[7]='unad11rh';
			$scampo[8]='unad11ecivil';
			$scampo[9]='unad11razonsocial';
			$scampo[10]='unad11direccion';
			$scampo[11]='unad11telefono';
			$scampo[12]='unad11correo';
			$scampo[13]='unad11sitioweb';
			$scampo[14]='unad11nacionalidad';
			$scampo[15]='unad11deptoorigen';
			$scampo[16]='unad11ciudadorigen';
			$scampo[17]='unad11deptodoc';
			$scampo[18]='unad11ciudaddoc';
			$scampo[19]='unad11idmoodle';
			$scampo[20]='unad11idncontents';
			$scampo[21]='unad11iddatateca';
			$scampo[22]='unad11idcampus';
			$scampo[23]='unad11aceptanotificacion';
			$scampo[24]='unad11correonotifica';
			$scampo[25]='unad11correoinstitucional';
			$scampo[26]='unad11latgrados';
			$scampo[27]='unad11latdecimas';
			$scampo[28]='unad11longrados';
			$scampo[29]='unad11longdecimas';
			$scampo[30]='unad11skype';
			$scampo[31]='unad11mostrarcelular';
			$scampo[32]='unad11mostrarcorreo';
			$scampo[33]='unad11mostrarskype';
			$scampo[34]='unad11fechaterminos';
			$scampo[35]='unad11minutotablero';
			$scampo[36]='unad11noubicar';
			$scampo[37]='unad11rolunad';
			$scampo[38]='unad11exluirdobleaut';
			$scampo[39]='unad11idzona';
			$scampo[40]='unad11idcead';
			$scampo[41]='unad11idescuela';
			$scampo[42]='unad11idprograma';
			$scampo[43]='unad11correofuncionario';
			$sdato[1]=$DATA['unad11nombre1'];
			$sdato[2]=$DATA['unad11nombre2'];
			$sdato[3]=$DATA['unad11apellido1'];
			$sdato[4]=$DATA['unad11apellido2'];
			$sdato[5]=$DATA['unad11genero'];
			$sdato[6]=$DATA['unad11fechanace'];
			$sdato[7]=$DATA['unad11rh'];
			$sdato[8]=$DATA['unad11ecivil'];
			$sdato[9]=$DATA['unad11razonsocial'];
			$sdato[10]=$DATA['unad11direccion'];
			$sdato[11]=$DATA['unad11telefono'];
			$sdato[12]=$DATA['unad11correo'];
			$sdato[13]=$DATA['unad11sitioweb'];
			$sdato[14]=$DATA['unad11nacionalidad'];
			$sdato[15]=$DATA['unad11deptoorigen'];
			$sdato[16]=$DATA['unad11ciudadorigen'];
			$sdato[17]=$DATA['unad11deptodoc'];
			$sdato[18]=$DATA['unad11ciudaddoc'];
			$sdato[19]=$DATA['unad11idmoodle'];
			$sdato[20]=$DATA['unad11idncontents'];
			$sdato[21]=$DATA['unad11iddatateca'];
			$sdato[22]=$DATA['unad11idcampus'];
			$sdato[23]=$DATA['unad11aceptanotificacion'];
			$sdato[24]=$DATA['unad11correonotifica'];
			$sdato[25]=$DATA['unad11correoinstitucional'];
			$sdato[26]=$DATA['unad11latgrados'];
			$sdato[27]=$DATA['unad11latdecimas'];
			$sdato[28]=$DATA['unad11longrados'];
			$sdato[29]=$DATA['unad11longdecimas'];
			$sdato[30]=$DATA['unad11skype'];
			$sdato[31]=$DATA['unad11mostrarcelular'];
			$sdato[32]=$DATA['unad11mostrarcorreo'];
			$sdato[33]=$DATA['unad11mostrarskype'];
			$sdato[34]=$DATA['unad11fechaterminos'];
			$sdato[35]=$DATA['unad11minutotablero'];
			$sdato[36]=$DATA['unad11noubicar'];
			$sdato[37]=$DATA['unad11rolunad'];
			$sdato[38]=$DATA['unad11exluirdobleaut'];
			$sdato[39]=$DATA['unad11idzona'];
			$sdato[40]=$DATA['unad11idcead'];
			$sdato[41]=$DATA['unad11idescuela'];
			$sdato[42]=$DATA['unad11idprograma'];
			$sdato[43]=$DATA['unad11correofuncionario'];
			$numcmod=43;
			$sWhere='unad11id='.$DATA['unad11id'].'';
			$sSQL='SELECT * FROM unad11terceros WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objdb->ejecutasql($sSQL);
			if ($objdb->nf($result)>0){
				$filabase=$objdb->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$unad11modf=fecha_DiaMod();
				$unad11modm=fecha_MinutoMod();
				$sdatos=$sdatos.', unad11modf='.$unad11modf.', unad11modm='.$unad11modm.'';
				$sSQL='UPDATE unad11terceros SET '.$sdatos.' WHERE '.$sWhere.';';
				$sdetalle=$sdatos.'['.$sWhere.']';
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objdb->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [111] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['unad11id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 111 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['unad11id'], $sdetalle, $objdb);}
				$DATA['paso']=2;
				list($sErrorM, $sDebugM)=AUREA_ActualizarPerfilMoodle($DATA['unad11id'], $objdb, $bDebug);
				$sDebug=$sDebug.$sDebugM;
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
function f111_TituloBusqueda(){
	return 'Busqueda de Terceros';
	}
function f111_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b111nombre" name="b111nombre" type="text" value="" onchange="paginarbusqueda()" /></label>
<label class="Label30">y</label><label><input id="b111nombre2" name="b111nombre2" type="text" value="" onchange="paginarbusqueda()" /></label>
<div class="salto1px"></div>
<label class="Label90">Documento</label><label><input id="b111doc" name="b111doc" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f111_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b111nombre.value;
params[104]=window.document.frmedita.b111nombre2.value;
params[105]=window.document.frmedita.b111doc.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f111_TablaDetalleBusquedas($params, $objdb){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_111;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	//$params[103]=numeros_validar($params[103]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sSQL);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$params[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[103];}
	if ($params[105]!=''){$sSQLadd1=$sSQLadd1.'TB.unad11doc LIKE "%'.$params[105].'%" AND ';}
	if ($params[103]!=''){
		$sBase=trim(strtoupper($params[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd1=$sSQLadd1.'TB.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd1=$sSQLadd1.'TB.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Tipodoc, Documento, DV, Razonsocial, Genero, Fecha nacimiento, Direccion, Telefono';
	$sOrden='';
	if ($sSQLadd1!=''){
		$sOrden='ORDER BY TB.unad11doc';
		}
	$sSQLadd1='WHERE TB.unad11id>0 AND '.$sSQLadd1.' TB.unad11doc<>"0" ';
	$limite='';
	$sSQL='SELECT 1 
FROM unad11terceros AS TB 
'.$sSQLadd1.' ';
	$tabladetalle=$objdb->ejecutasql($sSQL);
	$registros=$objdb->nf($tabladetalle);
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		}
	$sSQL='SELECT TB.unad11tipodoc, TB.unad11doc, TB.unad11razonsocial, TB.unad11dv, TB.unad11genero, TB.unad11fechanace, TB.unad11direccion, TB.unad11telefono, TB.unad11id 
FROM unad11terceros AS TB 
'.$sSQLadd1.'
'.$sOrden.'';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sSQL.$limite);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf111" name="paginaf111" type="hidden" value="'.$pagina.'"/><input id="lppf111" name="lppf111" type="hidden" value="'.$lineastabla.'"/>');
			//break;
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['unad11doc'].'</b></td>
<td><b>'.$ETI['unad11razonsocial'].'</b></td>
<td><b>'.$ETI['unad11telefono'].'</b></td>
<td><b>'.$ETI['unad11genero'].'</b></td>
<td><b>'.$ETI['unad11fechanace'].'</b></td>
<td><b>'.$ETI['unad11direccion'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['unad11id'].'\');">';
		$sSufijo='</a>';
		$sClass='';
		$sLink='';
		$tlinea++;
		$et_unad11fechanace='';
		if ($filadet['unad11fechanace']!='00/00/0000'){$et_unad11fechanace=$filadet['unad11fechanace'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['unad11tipodoc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11doc']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11razonsocial']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11telefono']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11genero'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad11fechanace.$sSufijo.'</td>
<td colspan="2">'.$sPrefijo.cadena_notildes($filadet['unad11direccion']).$sSufijo.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

function upd_dv($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$res='';
	$sSQL='SELECT unad11tipodoc, unad11doc FROM unad11terceros WHERE unad11id='.$params[0];
	$tabla=$objdb->ejecutasql($sSQL);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		switch ($fila['unad11tipodoc']){
			case 'CC':
			case 'NI':
			case 'TI':
			$res=numeros_dv($fila['unad11doc']);
			}
		$sSQL='UPDATE unad11terceros SET unad11dv="'.$res.'" WHERE unad11id='.$params[0];
		$result=$objdb->ejecutasql($sSQL);
		}
	$sfinal=html_oculto('unad11dv', $res);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_unad11dv","innerHTML",$sfinal);
	return $objResponse;
	}
?>