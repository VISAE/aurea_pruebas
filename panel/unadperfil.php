<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 0.4.0 viernes, 14 de febrero de 2014
--- Modelo Version 0.7.0 viernes, 14 de marzo de 2014
--- Modelo Versión 2.7.10 miércoles, 10 de junio de 2015
--- Modelo Versión 2.9.7 lunes, 23 de noviembre de 2015
--- Modelo Versión 2.22.3 miércoles, 15 de agosto de 2018
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
if (isset($APP->https)==0){$APP->https=0;}
if ($APP->https==2){
	$bObliga=false;
	if (isset($_SERVER['HTTPS'])==0){
		$bObliga=true;
		}else{
		if ($_SERVER['HTTPS']!='on'){$bObliga=true;}
		}
	if ($bObliga){
		$pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	}
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=105;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_105='lg/lg_105_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_105)){$mensajes_105='lg/lg_105_es.php';}
require $mensajes_todas;
require $mensajes_105;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=unadperfil.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$mensajes_106='lg/lg_106_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_106)){$mensajes_106='lg/lg_106_es.php';}
require $mensajes_106;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 105 unad05perfiles
require 'lib105.php';
// -- 106 Permisos por perfil
require 'lib106.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f105_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f105_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f106_Guardar');
$xajax->register(XAJAX_FUNCTION,'f106_Traer');
$xajax->register(XAJAX_FUNCTION,'f106_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f106_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f106_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'pintar_combo_unad06idmodulo');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf105'])==0){$_REQUEST['paginaf105']=1;}
if (isset($_REQUEST['lppf105'])==0){$_REQUEST['lppf105']=20;}
if (isset($_REQUEST['boculta105'])==0){$_REQUEST['boculta105']=0;}
if (isset($_REQUEST['paginaf106'])==0){$_REQUEST['paginaf106']=1;}
if (isset($_REQUEST['lppf106'])==0){$_REQUEST['lppf106']=20;}
if (isset($_REQUEST['boculta106'])==0){$_REQUEST['boculta106']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad05id'])==0){$_REQUEST['unad05id']='';}
if (isset($_REQUEST['unad05nombre'])==0){$_REQUEST['unad05nombre']='';}
if (isset($_REQUEST['unad05aplicativo'])==0){$_REQUEST['unad05aplicativo']=0;}
if (isset($_REQUEST['unad05reservado'])==0){$_REQUEST['unad05reservado']='N';}
if (isset($_REQUEST['unad05delegable'])==0){$_REQUEST['unad05delegable']='N';}
if ((int)$_REQUEST['paso']>0){
	//Permisos por perfil
	if (isset($_REQUEST['bsistema'])==0){$_REQUEST['bsistema']='';}
	if (isset($_REQUEST['bmodulo'])==0){$_REQUEST['bmodulo']='';}
	if (isset($_REQUEST['bpermiso'])==0){$_REQUEST['bpermiso']='';}
	if (isset($_REQUEST['bdato_106'])==0){$_REQUEST['bdato_106']=0;}
	if (isset($_REQUEST['unad06idmodulo'])==0){$_REQUEST['unad06idmodulo']='';}
	if (isset($_REQUEST['unad06idpermiso'])==0){$_REQUEST['unad06idpermiso']='';}
	if (isset($_REQUEST['unad06vigente'])==0){$_REQUEST['unad06vigente']='S';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$sSQL='SELECT * FROM unad05perfiles WHERE unad05id='.$_REQUEST['unad05id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad05id']=$fila['unad05id'];
		$_REQUEST['unad05nombre']=$fila['unad05nombre'];
		$_REQUEST['unad05aplicativo']=$fila['unad05aplicativo'];
		$_REQUEST['unad05reservado']=$fila['unad05reservado'];
		$_REQUEST['unad05delegable']=$fila['unad05delegable'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta105']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f105_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f105_db_Eliminar($_REQUEST[''], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad05id']='';
	$_REQUEST['unad05nombre']='';
	$_REQUEST['unad05aplicativo']=0;
	$_REQUEST['unad05reservado']='N';
	$_REQUEST['unad05delegable']='N';
	$_REQUEST['paso']=0;
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos('n');
$html_unad05aplicativo=html_combo('unad05aplicativo', 'unad01id', 'unad01nombre', 'unad01sistema', '', 'unad01nombre', $_REQUEST['unad05aplicativo'], $objDB, '', true, '{'.$ETI['msg_ninguno'].'}|{'.$ETI['msg_todos'].'}', '0|-1');
$objCombos->nuevo('unad05reservado', $_REQUEST['unad05reservado'], false);
$objCombos->sino();
$html_unad05reservado=$objCombos->html('', $objDB);
$objCombos->nuevo('unad05delegable', $_REQUEST['unad05delegable'], false);
$objCombos->sino();
$html_unad05delegable=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']>0){
	$html_bsistema=html_combo('bsistema', 'unad01id', 'unad01nombre', 'unad01sistema', 'unad01instalado="S"', 'unad01nombre', $_REQUEST['bsistema'], $objDB, 'CambiaSistema()', true, '{'.$ETI['msg_todos'].'}', '');
	$html_bmodulo=html_combo_unad06idmodulo($objDB, $_REQUEST['bsistema'], $_REQUEST['bmodulo']);
	$html_bpermiso=html_combo_unad06idpermiso($objDB, $_REQUEST['bpermiso']);
	}
//Alistar datos adicionales
$id_rpt=0;
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=105;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
//Cargar las tablas de datos
$aParametros[101]=$_REQUEST['paginaf105'];
$aParametros[102]=$_REQUEST['lppf105'];
$aParametros[103]=$_REQUEST['bnombre'];
list($sTabla105, $sDebugTabla)=f105_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla106='';
if ($_REQUEST['paso']!=0){
	//Permisos por perfil
	$aParametros106[0]=$_REQUEST['unad05id'];
	$aParametros106[91]=$_REQUEST['bsistema'];
	$aParametros106[92]=$_REQUEST['bmodulo'];
	$aParametros106[93]=$_REQUEST['bpermiso'];
	$aParametros106[101]=$_REQUEST['paginaf106'];
	$aParametros106[102]=$_REQUEST['lppf106'];
	list($sTabla106, $sDebugTabla)=f106_TablaDetalleV2($aParametros106, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_105']);
echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/css/principal.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<script language="javascript">
<!--
function limpiapagina(){
	expandesector(98);
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	expandesector(98);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	var objdiv= document.getElementById('div_p'+codigo);
	var objban= document.getElementById('boculta'+codigo);
	var otroestado='none';
	if (estado=='none'){otroestado='block';}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	var objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function imprimelista(){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_105.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_105.value;
		window.document.frmlista.submit();
	}
function imprimep(){
		window.document.frmimpp.submit();
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
		expandesector(98);
		window.document.frmedita.paso.value=13;
		window.document.frmedita.submit();
		}
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.unad05id.value;
	if ((datos[1]!='')){
		xajax_f105_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.unad05id.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function paginarf105(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf105.value;
	params[102]=window.document.frmedita.lppf105.value;
	params[103]=window.document.frmedita.bnombre.value;
	//document.getElementById('div_f105detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf105" name="paginaf105" type="hidden" value="'+params[101]+'" /><input id="lppf105" name="lppf105" type="hidden" value="'+params[102]+'" />';
	xajax_f105_HtmlTabla(params);
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function objinicial(){
	document.getElementById("unad05id").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function paginarf106(){
	var params=new Array();
	params[0]=window.document.frmedita.unad05id.value;
	params[91]=window.document.frmedita.bsistema.value;
	params[92]=window.document.frmedita.bmodulo.value;
	params[93]=window.document.frmedita.bpermiso.value;
	params[101]=window.document.frmedita.paginaf106.value;
	params[102]=window.document.frmedita.lppf106.value;
	xajax_f106_HtmlTabla(params);
	}
function mantener_sesion(){xajax_sesion_mantener();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	var divAyuda=document.getElementById('div_ayuda_'+sCampo);
	if (typeof divAyuda==='undefined'){
		}else{
		verboton('cmdAyuda_'+sCampo, 'none');
		var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	var sRetorna=window.document.frmedita.div96v2.value;
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function CambiaSistema(){
	var params=new Array();
	params[91]=window.document.frmedita.bsistema.value;
	xajax_pintar_combo_unad06idmodulo(params);
	paginarf106();
	}
function quitapermiso(idmodulo, idpermiso){
	var params=new Array();
	params[0]=window.document.frmedita.unad05id.value;
	params[1]=window.document.frmedita.unad05id.value;
	params[2]=idmodulo;
	params[3]=idpermiso;
	params[91]=window.document.frmedita.bsistema.value;
	params[92]=window.document.frmedita.bmodulo.value;
	params[93]=window.document.frmedita.bpermiso.value;
	params[101]=window.document.frmedita.paginaf106.value;
	params[102]=window.document.frmedita.lppf106.value;
	xajax_f106_Eliminar(params);
	}
function anexapermiso(idmodulo, idpermiso){
	var valores=new Array();
	var params=new Array();
	valores[1]=window.document.frmedita.unad05id.value;
	valores[2]=idmodulo;
	valores[3]=idpermiso;
	valores[4]='S';
	valores[100]=0;
	params[0]=window.document.frmedita.unad05id.value;
	params[91]=window.document.frmedita.bsistema.value;
	params[92]=window.document.frmedita.bmodulo.value;
	params[93]=window.document.frmedita.bpermiso.value;
	params[101]=window.document.frmedita.paginaf106.value;
	params[102]=window.document.frmedita.lppf106.value;
	xajax_f106_Guardar(valores, params);
	}
function revisaf106(){
	paginarf106();
	}
// -->
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p105.php" target="_blank">
<input id="r" name="r" type="hidden" value="105" />
<input id="id105" name="id105" type="hidden" value="<?php echo $_REQUEST['unad05id']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	$bHayImprimir=true;
	$sScript='imprimep()';
	$sClaseBoton='btEnviarPDF';
	if ($id_rpt!=0){$sScript='verrpt()';}
	}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_105'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta105" name="boculta105" type="hidden" value="<?php echo $_REQUEST['boculta105']; ?>" />
<label class="Label30">
<input id="btexpande105" name="btexpande105" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(105,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta105']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge105" name="btrecoge105" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(105,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta105']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p105" style="display:<?php if ($_REQUEST['boculta105']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
 ?>
<label class="Label90">
<?php
echo $ETI['unad05id'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unad05id" name="unad05id" type="text" value="<?php echo $_REQUEST['unad05id']; ?>" onchange="RevisaLlave()" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
<?php
	}else{
	echo html_oculto('unad05id', $_REQUEST['unad05id']);
	}
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad05reservado'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad05reservado;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad05delegable'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad05delegable;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad05aplicativo'];
?>
</label>
<label>
<?php
echo $html_unad05aplicativo;
?>
</label>
<label class="L">
<?php
echo $ETI['unad05nombre'];
?>

<input id="unad05nombre" name="unad05nombre" type="text" value="<?php echo $_REQUEST['unad05nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad05nombre']; ?>"/>
</label>
<?php
// -- Inicia Grupo campos 106 Permisos por perfil
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_106'];
?>
</label>
<input id="boculta106" name="boculta106" type="hidden" value="<?php echo $_REQUEST['boculta106']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel106" name="btexcel106" type="button" value="Exportar" class="btMiniExcel" onclick="imprime106();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande106" name="btexpande106" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(106,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta106']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge106" name="btrecoge106" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(106,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta106']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p106" style="display:<?php if ($_REQUEST['boculta106']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label60">
<?php
echo 'Sistema';
?>
</label>
<label class="Label220">
<?php 
echo $html_bsistema;
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad06idmodulo'];
?>
</label>
<label class="Label350"><div id="div_bmodulo">
<?php
echo $html_bmodulo;
?>
</div></label>
<label class="Label90">
<?php
echo $ETI['unad06idpermiso'];
?>
</label>
<label class="Label200">
<?php
echo $html_bpermiso;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f106detalle">
<?php
echo $sTabla106;
?>
</div>
<?php
	}
//fin de si el paso es 2
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 106 Permisos por perfil
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p105
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label90">
Nombre
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf105()" autocomplete="off"/>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f105detalle">
<?php
echo $sTabla105;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_105" name="titulo_105" type="hidden" value="<?php echo $ETI['titulo_105']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_105'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
	//El script que cambia el sector que se muestra
?>

<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
-->
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>