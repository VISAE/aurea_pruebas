<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
/** Archivo evenparticipante.php.
* Modulo 1904 even04eventoparticipante.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date Friday, September 6, 2019
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$_REQUEST['debug']=1;
	}
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
$iCodModulo=1904;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
$mensajes_1904='lg/lg_1904_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1904)){$mensajes_1904='lg/lg_1904_es.php';}
require $mensajes_todas;
require $mensajes_1902;
require $mensajes_1904;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$bCerrado=true;
	if ($bDebug){
		$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
    if (seg_revisa_permiso($iCodModulo, 1707, $objDB)){
        $sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$bOtroUsuario=true;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';}
			}else{
			$sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
			$_REQUEST['deb_doc']='';
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario Modulo '.$iCodModulo.' Permiso.<br>';}
		$_REQUEST['deb_doc']='';
		}
	}else{
	$_REQUEST['deb_doc']='';
	}
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=evenparticipante.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 1904 even04eventoparticipante
require 'lib1904inscribe.php';
require 'lib1902.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f1904_HtmlTabla');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
if(!isset($sError)){$sError='';}
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf1904'])==0){$_REQUEST['paginaf1904']=1;}
if (isset($_REQUEST['lppf1904'])==0){$_REQUEST['lppf1904']=20;}
if (isset($_REQUEST['boculta1904'])==0){$_REQUEST['boculta1904']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even04idevento'])==0){$_REQUEST['even04idevento']='';}
if (isset($_REQUEST['even04idparticipante'])==0){$_REQUEST['even04idparticipante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['even04idparticipante_td'])==0){$_REQUEST['even04idparticipante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['even04idparticipante_doc'])==0){$_REQUEST['even04idparticipante_doc']='';}
if (isset($_REQUEST['even04id'])==0){$_REQUEST['even04id']='';}
if (isset($_REQUEST['even04institucion'])==0){$_REQUEST['even04institucion']='';}
if (isset($_REQUEST['even04cargo'])==0){$_REQUEST['even04cargo']='';}
if (isset($_REQUEST['even04correo'])==0){$_REQUEST['even04correo']='';}
if (isset($_REQUEST['even04telefono'])==0){$_REQUEST['even04telefono']='';}
if (isset($_REQUEST['even04estadoasistencia'])==0){$_REQUEST['even04estadoasistencia']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if (isset($_REQUEST['even04idparticipante_rs'])==0){$_REQUEST['even04idparticipante_rs']='';}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
list($even04idparticipante_rs, $_REQUEST['even04idparticipante'], $_REQUEST['even04idparticipante_td'], $_REQUEST['even04idparticipante_doc'])=html_tercero($_REQUEST['even04idparticipante_td'], $_REQUEST['even04idparticipante_doc'], $_REQUEST['even04idparticipante'], 0, $objDB);
$objCombos->nuevo('even04estadoasistencia', $_REQUEST['even04estadoasistencia'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT even13id AS id, even13nombre AS nombre FROM even13estadoasistencia ORDER BY even13nombre';
$html_even04estadoasistencia=$objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso']==0){
	$html_even04idevento=f1904_HTMLComboV2_even04idevento($objDB, $objCombos, $_REQUEST['even04idevento']);
	}else{
	list($even04idevento_nombre, $sErrorDet)=tabla_campoxid('even02evento','even02nombre','even02id',$_REQUEST['even04idevento'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_even04idevento=html_oculto('even04idevento', $_REQUEST['even04idevento'], $even04idevento_nombre);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf1904()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(1904, 1, $objDB, 'paginarf1904()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=1904;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
	$seg_5=1;
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_1904'];
$aParametros[101]=$_REQUEST['paginaf1904'];
$aParametros[102]=$_REQUEST['lppf1904'];
if($sError=='') {
    $aParam[0]=$_REQUEST['deb_doc'];
    $aParam[1]='';
    $respuesta=f1902_Cargar_Participante($aParam);
    $_REQUEST['even04idparticipante'] = $respuesta[0];
    $_REQUEST['even04idparticipante_td'] = $respuesta[1];
    $_REQUEST['even04idparticipante_doc'] = $respuesta[2];
    $_REQUEST['even04idparticipante_rs'] = $respuesta[3];
    $_REQUEST['even04institucion'] = $respuesta[4];
    $_REQUEST['even04cargo'] = $respuesta[5];
    $_REQUEST['even04correo'] = $respuesta[6];
    $_REQUEST['even04telefono'] = $respuesta[7];
    $aParametros[103] = $_REQUEST['even04idparticipante'];
    list($sTabla1904, $sDebugTabla)=f1904_TablaDetalleV2($aParametros, $objDB, $bDebug);
    $sDebug=$sDebug.$sDebugTabla;
    } else {
    $aParametros[103] = '';
    }
//$aParametros[104]=$_REQUEST['blistar'];
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_1904']);
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
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
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	}
function ter_retorna(){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (sRetorna!=''){
		var idcampo=window.document.frmedita.div96campo.value;
		var illave=window.document.frmedita.div96llave.value;
		var did=document.getElementById(idcampo);
		var dtd=document.getElementById(idcampo+'_td');
		var ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	var params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		if (idcampo=='even04idparticipante'){
			params[4]='RevisaLlave';
			}
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_1904.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_1904.value;
		window.document.frmlista.nombrearchivo.value='Participantes';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	if (window.document.frmedita.seg_6.value==1){
		asignarvariables();
		window.document.frmimpp.action='e1904.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p1904.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		}else{
		window.alert("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function paginarf1904(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1904.value;
	params[102]=window.document.frmedita.lppf1904.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f1904detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1904" name="paginaf1904" type="hidden" value="'+params[101]+'" /><input id="lppf1904" name="lppf1904" type="hidden" value="'+params[102]+'" />';
	xajax_f1904_HtmlTabla(params);
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
	document.getElementById("even04idevento").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f1904_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='even04idparticipante'){
		ter_traerxid('even04idparticipante', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
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
// -->
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p1904.php" target="_blank">
<input id="r" name="r" type="hidden" value="1904" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
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
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
$bHayImprimir2=false;
$sScript='imprimep()';
$sClaseBoton='btEnviarPDF'; //btUpPrint
if ($seg_5==1){$bHayImprimir2=true;}
if ($bHayImprimir2){
?>
<input id="cmdImprimir2" name="cmdImprimir2" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1904'].'</h2>';
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
<input id="boculta1904" name="boculta1904" type="hidden" value="<?php echo $_REQUEST['boculta1904']; ?>" />
<label class="Label30">
<input id="btexpande1904" name="btexpande1904" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1904,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1904" name="btrecoge1904" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1904,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1904" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<?php
if ($sError==''){
?>
<label class="Label90">
<?php
echo 'Documento';
?>
</label>
<label class="Label90">
<?php
echo html_oculto('even04idparticipante_td', $_REQUEST['even04idparticipante_td']);
?>
</label>
<label class="Label220">
<?php
echo html_oculto('even04idparticipante_doc', $_REQUEST['even04idparticipante_doc']);
?>
</label>
<label class="Label90">
<?php
echo 'Nombre';
?>
</label>
<label class="Label350">
<?php
echo html_oculto('even04idparticipante_rs', $_REQUEST['even04idparticipante_rs']);
?>
</label>
<label class="L">
<?php
echo $ETI['even04institucion'];
?>
<div class="salto1px"></div>
<?php
echo html_oculto('even04institucion', $_REQUEST['even04institucion']);
?>
</label>
<label class="L">
<?php
echo $ETI['even04cargo'];
?>
<div class="salto1px"></div>
<?php
echo html_oculto('even04cargo', $_REQUEST['even04cargo']);
?>
</label>
<label class="L">
<?php
echo $ETI['even04correo'];
?>
<div class="salto1px"></div>
<?php
echo html_oculto('even04correo', $_REQUEST['even04correo']);
?>
</label>
<label class="L">
<?php
echo $ETI['even04telefono'];
?>
<div class="salto1px"></div>
<?php
echo html_oculto('even04telefono', $_REQUEST['even04telefono']);
?>
</label>
<?php
}
?>

<?php
if (false){
//Ejemplo de boton de ayuda
//echo html_BotonAyuda('NombreCampo');
//echo html_DivAyudaLocal('NombreCampo');
}
if ($bconexpande){
//Este es el cierre del div_p1904
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
?>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1904()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
}
?>
<?php
echo ' '.$csv_separa;
// aqui ojo
?>

<div id="div_f1904detalle">
<?php
//echo $sTabla1904;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque_mis_eventos'].'</h3>';
?>
</div>
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta1904" name="boculta1904" type="hidden" value="<?php echo $_REQUEST['boculta1904']; ?>" />
<label class="Label30">
<input id="btexpande1904" name="btexpande1904" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1904,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1904" name="btrecoge1904" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1904,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1904" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['even04idevento'];
?>
</label>
<label>
<?php
echo $html_even04idevento;
?>
</label>
<div class="salto1px"></div>
<?php
if (false){
?>
<label class="Label60">
<?php
echo $ETI['even04id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('even04id', $_REQUEST['even04id']);
?>
</label>
<label class="L">
<?php
echo $ETI['even04institucion'];
?>

<input id="even04institucion" name="even04institucion" type="text" value="<?php echo $_REQUEST['even04institucion']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04institucion']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04cargo'];
?>

<input id="even04cargo" name="even04cargo" type="text" value="<?php echo $_REQUEST['even04cargo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04cargo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04correo'];
?>

<input id="even04correo" name="even04correo" type="text" value="<?php echo $_REQUEST['even04correo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04correo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04telefono'];
?>

<input id="even04telefono" name="even04telefono" type="text" value="<?php echo $_REQUEST['even04telefono']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04telefono']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['even04estadoasistencia'];
?>
</label>
<label>
<?php
echo $html_even04estadoasistencia;
?>
</label>

<?php
}
?>

<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p1904
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1904()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
	}
?>
<?php
echo ' '.$csv_separa;
// aqui ojo
?>

<div id="div_f1904detalle">
<?php
if(isset($sTabla1904)) {
    echo $sTabla1904;
    }
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque_eventos_futuros'].'</h3>';
?>
</div>
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta1904" name="boculta1904" type="hidden" value="<?php echo $_REQUEST['boculta1904']; ?>" />
<label class="Label30">
<input id="btexpande1904" name="btexpande1904" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1904,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1904" name="btrecoge1904" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1904,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1904" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['even04idevento'];
?>
</label>
<label>
<?php
echo $html_even04idevento;
?>
</label>
<div class="salto1px"></div>
<?php
if (false){
?>
<label class="Label60">
<?php
echo $ETI['even04id'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('even04id', $_REQUEST['even04id']);
?>
</label>
<label class="L">
<?php
echo $ETI['even04institucion'];
?>

<input id="even04institucion" name="even04institucion" type="text" value="<?php echo $_REQUEST['even04institucion']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04institucion']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04cargo'];
?>

<input id="even04cargo" name="even04cargo" type="text" value="<?php echo $_REQUEST['even04cargo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04cargo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04correo'];
?>

<input id="even04correo" name="even04correo" type="text" value="<?php echo $_REQUEST['even04correo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04correo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04telefono'];
?>

<input id="even04telefono" name="even04telefono" type="text" value="<?php echo $_REQUEST['even04telefono']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04telefono']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['even04estadoasistencia'];
?>
</label>
<label>
<?php
echo $html_even04estadoasistencia;
?>
</label>

<?php
}
?>

<?php
if (false){
//Ejemplo de boton de ayuda
//echo html_BotonAyuda('NombreCampo');
//echo html_DivAyudaLocal('NombreCampo');
}
if ($bconexpande){
//Este es el cierre del div_p1904
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
?>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1904()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
}
?>
<?php
echo ' '.$csv_separa;
// aqui ojo
?>

<div id="div_f1904detalle">
<?php
//echo $sTabla1904;
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
<input id="titulo_1904" name="titulo_1904" type="hidden" value="<?php echo $ETI['titulo_1904']; ?>" />
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


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_1904'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1904'].'</h2>';
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_1904.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>